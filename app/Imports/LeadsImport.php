<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lead([
            'company_name' => $row['company_name'] ?? $row[0],
            'director' => $row['director'] ?? $row[1] ?? null,
            'phone' => $row['phone'] ?? $row[2],
            'email' => $row['email'] ?? $row[3] ?? null,
            'city' => $row['city'] ?? $row[4] ?? null,
            'address' => $row['address'] ?? $row[5] ?? null,
            'country_id' => $row['country_id'] ?? $row[6] ?? 1,
            'activity_type_id' => $row['activity_type_id'] ?? $row[7] ?? 1,
            'status' => $row['status'] ?? $row[8] ?? 'New',
        ]);
    }
}