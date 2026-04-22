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
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #1E4BA6;
            color: white;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount {
            text-align: right;
            white-space: nowrap;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f3f4f6;
            border: 1px solid #ddd;
            width: 300px;
            margin-left: auto;
        }
        .summary table {
            margin-top: 0;
            border: none;
        }
        .summary td {
            border: none;
            padding: 4px 8px;
        }
        .summary .label { font-weight: bold; }
        .summary .value { text-align: right; font-weight: bold; }
        .income { color: #16a34a; }
        .expense { color: #dc2626; }
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
        $totalIncome = $accounts->whereIn('entry_type', $incomeTypes)->sum('amount');
        $totalExpenses = $accounts->whereIn('entry_type', $expenseTypes)->sum('amount');
        $netBalance = $totalIncome - $totalExpenses;
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
                <th width="25%">Purpose</th>
                <th width="15%" class="amount">Amount</th>
                <th width="15%" class="amount">Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts->sortBy('date') as $account)
            <tr>
                <td>{{ $account->date }}</td>
                <td>{{ $account->entry_type }}</td>
                <td>{{ $account->vendor_name }}</td>
                <td>{{ $account->purpose }}</td>
                <td class="amount">&#2547;{{ number_format($account->amount, 2) }}</td>
                <td class="amount">&#2547;{{ number_format($account->balance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td class="label">Total Income:</td>
                <td class="value income">&#2547;{{ number_format($totalIncome, 2) }}</td>
            </tr>
            <tr>
                <td class="label">Total Expenses:</td>
                <td class="value expense">&#2547;{{ number_format($totalExpenses, 2) }}</td>
            </tr>
            <tr style="border-top: 1px solid #ccc;">
                <td class="label">Net Balance:</td>
                <td class="value" style="font-size: 14px;">&#2547;{{ number_format($netBalance, 2) }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This is a computer-generated report. Printed by {{ Auth::user()->name ?? 'System' }}.
    </div>
</body>
</html>