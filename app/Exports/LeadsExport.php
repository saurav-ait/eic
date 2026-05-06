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
            'ID',
            'Company Name',
            'Director',
            'Phone',
            'Email',
            'City',
            'Address',
            'Country',
            'Activity Type',
            'Status',
            'Created At'
        ];
    }

    public function map($lead): array
    {
        return [
            $lead->id,
            $lead->company_name,
            $lead->director,
            $lead->phone,
            $lead->email,
            $lead->city,
            $lead->address,
            $lead->country->name ?? '',
            $lead->activity->name ?? '',
            $lead->status,
            $lead->created_at->format('Y-m-d H:i:s')
        ];
    }
}
