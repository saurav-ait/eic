<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public const ENTRY_TYPES = [
        'Received',
        'Receivable',
        'Payment',
        'Payable',
        'Purchase',
        'Salary',
        'Office costs',
    ];

    public const INCOME_TYPES = ['Received', 'Receivable'];

    public const EXPENSE_TYPES = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

    public const VENDOR_TYPES = ['Client', 'Agent', 'Others'];

    public const PURPOSES = [
        'Advance File Opening',
        'Receive After Permit',
        'Receive during Processing',
        'Receive After Approval',
        'Receive After Visa',
        'Return Against Received',
        'Others',
    ];

    public const STATUSES = ['Pending', 'Approved', 'Rejected', 'Processing', 'Completed'];

    protected $fillable = [
        'date',
        'entry_type',
        'vendor_type',
        'vendor_name',
        'purpose',
        'details',
        'country',
        'last_status',
        'amount',
        'balance',
        'document'
    ];
}
