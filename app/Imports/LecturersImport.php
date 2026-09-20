<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LecturersImport implements ToModel, WithHeadingRow
{
    public $imported = 0;
    public $skipped  = 0;
    public $importErrors = [];

    public function model(array $row)
    {
        if (empty($row['name']) || empty($row['email'])) {
            $this->skipped++;
            return null;
        }

        $existing = User::where('email', trim($row['email']))->first();
        if ($existing) {
            $existing->update([
                'name'  => trim($row['name']),
                'phone' => !empty($row['phone']) ? trim($row['phone']) : $existing->phone,
            ]);
            $this->imported++;
            return null;
        }

        $this->imported++;

        return new User([
            'name'     => trim($row['name']),
            'email'    => trim($row['email']),
            'phone'    => !empty($row['phone']) ? trim($row['phone']) : null,
            'password' => Hash::make(!empty($row['password']) ? trim($row['password']) : 'lecturer123'),
            'role'     => 'lecturer',
            'status'   => 'active',
        ]);
    }
}