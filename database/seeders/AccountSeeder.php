<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $entries = [
            [
                'date' => '2026-04-01',
                'entry_type' => 'Received',
                'vendor_type' => 'Client',
                'vendor_name' => 'ABC Corp',
                'purpose' => 'Advance File Opening',
                'details' => 'Initial payment for visa processing',
                'country' => 'Canada',
                'last_status' => 'Processing',
                'amount' => 5000.00,
            ],
            [
                'date' => '2026-04-05',
                'entry_type' => 'Payment',
                'vendor_type' => 'Agent',
                'vendor_name' => 'XYZ Services',
                'purpose' => 'Others',
                'details' => 'Monthly office rent',
                'country' => null,
                'last_status' => 'Paid',
                'amount' => 1000.00,
            ],
            [
                'date' => '2026-04-10',
                'entry_type' => 'Received',
                'vendor_type' => 'Client',
                'vendor_name' => 'DEF Ltd',
                'purpose' => 'Receive After Permit',
                'details' => 'Payment after work permit approval',
                'country' => 'UK',
                'last_status' => 'Completed',
                'amount' => 3000.00,
            ],
            [
                'date' => '2026-04-15',
                'entry_type' => 'Salary',
                'vendor_type' => 'Others',
                'vendor_name' => 'Employee A',
                'purpose' => 'Others',
                'details' => 'Monthly salary payment',
                'country' => null,
                'last_status' => 'Paid',
                'amount' => 2000.00,
            ],
        ];

        foreach ($entries as $entry) {
            Account::create($entry);
        }
    }
}
