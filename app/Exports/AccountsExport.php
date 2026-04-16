<?php

namespace App\Exports;

use App\Models\Account;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Account::orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Date',
            'Entry Type',
            'Vendor Type',
            'Vendor Name',
            'Purpose',
            'Details',
            'Country',
            'Last Status',
            'Amount',
            'Balance',
        ];
    }

    public function map($account): array
    {
        return [
            $account->date,
            $account->entry_type,
            $account->vendor_type,
            $account->vendor_name,
            $account->purpose,
            $account->details,
            $account->country,
            $account->last_status,
            number_format($account->amount, 2),
            number_format($account->balance, 2),
        ];
    }
}
