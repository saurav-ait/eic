<?php

namespace App\Imports;

use App\Models\Account;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class AccountsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function rules(): array
    {
        return [
            'date' => ['required', 'date'],
            'entry_type' => ['required', Rule::in(Account::ENTRY_TYPES)],
            'vendor_type' => ['required', Rule::in(Account::VENDOR_TYPES)],
            'vendor_name' => ['required', 'string', 'max:255'],
            'purpose' => ['required', Rule::in(Account::PURPOSES)],
            'details' => ['nullable', 'string', 'max:1000'],
            'country' => ['nullable', 'string', 'max:255'],
            'last_status' => ['nullable', Rule::in(Account::STATUSES)],
            'amount' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ];
    }

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
