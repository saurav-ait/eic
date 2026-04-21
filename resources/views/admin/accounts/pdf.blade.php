<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Accounts Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #333;
            margin: 0;
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
            background-color: #f2f2f2;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .amount {
            text-align: right;
        }
        .summary {
            margin-top: 30px;
            padding: 15px;
            background-color: #f8f8f8;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #333;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin: 10px 0;
        }
        .summary-label {
            font-weight: bold;
        }
        .summary-value {
            font-weight: bold;
        }
        .income { color: green; }
        .expense { color: red; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Accounts Report</h1>
        <p>Generated on {{ date('F j, Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Entry Type</th>
                <th>Vendor Type</th>
                <th>Vendor Name</th>
                <th>Purpose</th>
                <th>Details</th>
                <th>Country</th>
                <th>Amount</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
            <tr>
                <td>{{ $account->date }}</td>
                <td>{{ $account->entry_type }}</td>
                <td>{{ $account->vendor_type }}</td>
                <td>{{ $account->vendor_name }}</td>
                <td>{{ $account->purpose }}</td>
                <td>{{ $account->details }}</td>
                <td>{{ $account->country }}</td>
                <td class="amount">{{ number_format($account->amount, 2) }}</td>
                <td class="amount">{{ number_format($account->balance, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Summary</h3>
        <div class="summary-row">
            <span class="summary-label">Total Income:</span>
            <span class="summary-value income">৳{{ number_format($accounts->whereIn('entry_type', ['Received','Receivable'])->sum('amount'), 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Total Expenses:</span>
            <span class="summary-value expense">৳{{ number_format($accounts->whereIn('entry_type', ['Payment','Payable','Purchase','Salary','Office costs'])->sum('amount'), 2) }}</span>
        </div>
        <div class="summary-row">
            <span class="summary-label">Current Balance:</span>
            <span class="summary-value">৳{{ number_format($accounts->last()->balance ?? 0, 2) }}</span>
        </div>
    </div>
</body>
</html>