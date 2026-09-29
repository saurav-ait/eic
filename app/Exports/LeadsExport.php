<?php

namespace App\Exports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LeadsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Lead::with(['country', 'activity'])->get();
    }

    public function headings(): array
    {
        return [
            'company_name',
            'director',
            'phone',
            'email',
            'city',
            'address',
            'country',
            'activity_type',
            'status'
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->company_name,
            $lead->director,
            $lead->phone,
            $lead->email,
            $lead->city,
            $lead->address,
            $lead->country->name ?? '',
            $lead->activity->name ?? '',
            $lead->status,
        ];
    }
}
