<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accounts Report - {{ $vendor ?? 'General' }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 10px;
            font-size: 11px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #1E4BA6;
            padding-bottom: 10px;
        }

        .header h1 {
            color: #1E4BA6;
            margin: 0;
            font-size: 22px;
            text-transform: uppercase;
        }

        .header .subtitle {
            font-size: 14px;
            font-weight: bold;
            color: #555;
            margin: 5px 0;
        }

        .header p {
            color: #666;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 7px;
            text-align: left;
        }

        th {
            background-color: #1E4BA6;
            color: white;
            font-size: 10px;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .amount {
            text-align: right;
            white-space: nowrap;
            font-weight: 600;
        }

        .opening-row {
            background: #eef2ff;
            font-weight: bold;
        }

        .summary {
            margin-top: 25px;
            width: 320px;
            margin-left: auto;
            border: 1px solid #ddd;
            padding: 10px;
            background: #f8fafc;
        }

        .summary td {
            border: none;
            padding: 5px;
        }

        .summary .label {
            font-weight: bold;
        }

        .summary .value {
            text-align: right;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            font-size: 9px;
            text-align: center;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>

@php
    $incomeTypes = ['Received', 'Receivable'];
    $expenseTypes = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

    // ✅ SORT DATA PROPERLY
    $sortedAccounts = $accounts->sortBy([
        ['date', 'asc'],
        ['id', 'asc']
    ]);

    // ✅ TOTALS
    $totalIncome = $sortedAccounts->whereIn('entry_type', $incomeTypes)->sum('amount');
    $totalExpenses = $sortedAccounts->whereIn('entry_type', $expenseTypes)->sum('amount');

    // ✅ START BALANCE
    $runningBalance = $openingBalance ?? 0;

    $netBalance = $runningBalance + $totalIncome - $totalExpenses;
@endphp

<div class="header">
    <h1>Accounts Report</h1>

    @if(isset($vendor))
        <div class="subtitle">Vendor: {{ $vendor }}</div>
    @endif

    <p>Date Range: {{ $reportLabel ?? 'All Records' }}</p>
    <p>Generated on {{ now()->format('F j, Y, g:i a') }}</p>
</div>

<table>
    <thead>
        <tr>
            <th width="10%">Date</th>
            <th width="15%">Type</th>
            <th width="20%">Vendor</th>
            <th width="20%">Purpose</th>
            <th width="12%" class="amount">Debit</th>
            <th width="12%" class="amount">Credit</th>
            <th width="15%" class="amount">Balance</th>
        </tr>
    </thead>

    <tbody>

        @php
            $sortedAccounts = $sortedAccounts ?? ($accounts ?? collect());
            $incomeTypes = $incomeTypes ?? ['Received', 'Receivable'];
            $expenseTypes = $expenseTypes ?? ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];
            $runningBalance = $openingBalance ?? 0;
        @endphp

        {{-- ✅ OPENING BALANCE --}}
        @if(isset($openingBalance))
        <tr class="opening-row">
            <td colspan="6">Opening Balance</td>
            <td class="amount">৳{{ number_format($runningBalance, 2) }}</td>
        </tr>
        @endif

        @foreach($sortedAccounts as $acc)

            @php
                $debit = 0;
                $credit = 0;

                if (in_array($acc->entry_type, $incomeTypes)) {
                    $credit = $acc->amount;
                    $runningBalance += $credit;
                } elseif (in_array($acc->entry_type, $expenseTypes)) {
                    $debit = $acc->amount;
                    $runningBalance -= $debit;
                }
            @endphp

            <tr>
                <td>{{ $acc->date }}</td>
                <td>{{ $acc->entry_type }}</td>
                <td>{{ $acc->vendor_name }}</td>
                <td>{{ $acc->purpose }}</td>

                <td class="amount">
                    {{ $debit ? '৳'.number_format($debit,2) : '-' }}
                </td>

                <td class="amount">
                    {{ $credit ? '৳'.number_format($credit,2) : '-' }}
                </td>

                <td class="amount">
                    ৳{{ number_format($runningBalance, 2) }}
                </td>
            </tr>

        @endforeach

    </tbody>
</table>

{{-- ✅ SUMMARY --}}
<div class="summary">
    <table>
        <tr>
            <td class="label">Opening Balance:</td>
            <td class="value">৳{{ number_format($openingBalance ?? 0, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total Income:</td>
            <td class="value">৳{{ number_format($totalIncome, 2) }}</td>
        </tr>
        <tr>
            <td class="label">Total Expenses:</td>
            <td class="value">৳{{ number_format($totalExpenses, 2) }}</td>
        </tr>
        <tr style="border-top:1px solid #ccc;">
            <td class="label">Closing Balance:</td>
            <td class="value">৳{{ number_format($runningBalance, 2) }}</td>
        </tr>
    </table>
</div>

<div class="footer">
    This is a computer-generated report. Printed by {{ Auth::user()->name ?? 'System' }}.
</div>

</body>
</html>