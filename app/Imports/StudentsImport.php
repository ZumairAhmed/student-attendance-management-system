<?php

namespace App\Imports;

use App\Models\Student;
use App\Models\Batch;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public $imported = 0;
    public $skipped  = 0;
    public $importErrors = [];

    public function model(array $row)
    {
        if (empty($row['full_name']) || empty($row['index_no']) || empty($row['batch_id'])) {
            $this->skipped++;
            return null;
        }

        if (!Batch::find((int)$row['batch_id'])) {
            $this->skipped++;
            $this->importErrors[] = "Batch ID {$row['batch_id']} not found for {$row['full_name']}";
            return null;
        }

        $existing = Student::where('index_no', trim($row['index_no']))->first();
        if ($existing) {
            $existing->update([
                'full_name' => trim($row['full_name']),
                'batch_id'  => (int)$row['batch_id'],
                'email'     => !empty($row['email']) ? trim($row['email']) : $existing->email,
                'phone'     => !empty($row['phone']) ? trim($row['phone']) : $existing->phone,
                'status'    => 'active',
            ]);
            $this->imported++;
            return null;
        }

        $this->imported++;

        return new Student([
            'full_name' => trim($row['full_name']),
            'index_no'  => trim($row['index_no']),
            'batch_id'  => (int)$row['batch_id'],
            'email'     => !empty($row['email']) ? trim($row['email']) : null,
            'phone'     => !empty($row['phone']) ? trim($row['phone']) : null,
            'status'    => 'active',
        ]);
    }
}