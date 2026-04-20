<?php

namespace App\Imports;

use App\Models\Account;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AccountsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Account([
            'date' => isset($row['date']) ? Carbon::parse($row['date'])->format('Y-m-d') : null,
            'entry_type' => $row['entry_type'] ?? null,
            'vendor_type' => $row['vendor_type'] ?? null,
            'vendor_name' => $row['vendor_name'] ?? null,
            'purpose' => $row['purpose'] ?? null,
            'details' => $row['details'] ?? null,
            'country' => $row['country'] ?? null,
            'last_status' => $row['last_status'] ?? 'Pending',
            'amount' => isset($row['amount']) ? floatval($row['amount']) : 0,
            'balance' => 0,
        ]);
    }
}
