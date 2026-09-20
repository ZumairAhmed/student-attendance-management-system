<?php

namespace App\Imports;

use App\Models\Subject;
use App\Models\Batch;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SubjectsImport implements ToModel, WithHeadingRow
{
    public $imported = 0;
    public $skipped  = 0;
    public $importErrors = [];

    public function model(array $row)
    {
        if (empty($row['name']) || empty($row['code']) || empty($row['batch_id'])) {
            $this->skipped++;
            return null;
        }

        if (!Batch::find((int)$row['batch_id'])) {
            $this->skipped++;
            $this->importErrors[] = "Batch ID {$row['batch_id']} not found for {$row['name']}";
            return null;
        }

        $userId = null;
        if (!empty($row['lecturer_email'])) {
            $lecturer = User::where('email', trim($row['lecturer_email']))->first();
            $userId   = $lecturer ? $lecturer->id : null;
        }

        $existing = Subject::where('code', trim($row['code']))->first();
        if ($existing) {
            $existing->update([
                'name'     => trim($row['name']),
                'batch_id' => (int)$row['batch_id'],
                'semester' => (int)($row['semester'] ?? 1),
                'user_id'  => $userId ?? $existing->user_id,
            ]);
            $this->imported++;
            return null;
        }

        $this->imported++;

        return new Subject([
            'name'     => trim($row['name']),
            'code'     => trim($row['code']),
            'batch_id' => (int)$row['batch_id'],
            'semester' => (int)($row['semester'] ?? 1),
            'user_id'  => $userId,
        ]);
    }
}