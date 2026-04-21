@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Accounts Report</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
            <p style="margin-top:8px;color:#4b5563;font-size:14px;">Report generated on: {{ $generatedAt->format('F j, Y') }}</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('accounts.index') }}" class="btn primary" style="text-decoration: none;">← Back to Accounts</a>
        </div>
    </div>

    <!-- REPORT FILTERS -->
    <div class="card" style="margin-bottom:20px;">
        <h3 style="margin-bottom:15px;">Generate Report</h3>
        <form method="GET" action="{{ route('accounts.report') }}" class="report-filter-form">
            <div class="filter-block">
                <label for="month">Month</label>
                <input type="month" name="month" value="{{ request('month', now()->format('Y-m')) }}" style="padding:8px;border:1px solid #ddd;border-radius:8px;">
            </div>
            <div class="filter-block">
                <label for="from_date">From Date</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}" style="padding:8px;border:1px solid #ddd;border-radius:8px;">
            </div>
            <div class="filter-block">
                <label for="to_date">To Date</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}" style="padding:8px;border:1px solid #ddd;border-radius:8px;">
            </div>
            <div class="filter-block">
                <label for="generated_at">Date to Generate</label>
                <input type="date" name="generated_at" value="{{ request('generated_at', now()->format('Y-m-d')) }}" style="padding:8px;border:1px solid #ddd;border-radius:8px;">
            </div>
            <div style="display:flex;align-items:flex-end;gap:10px;">
                <button type="submit" class="btn primary">Generate Report</button>
                <a href="{{ route('accounts.report') }}" class="btn secondary">Reset</a>
            </div>
        </form>
    </div>

    @if($accounts->count() > 0)
    <!-- SUMMARY STATS -->
    <div class="stats">
        <div class="stat-card income">
            <span>Total Income</span>
            <h2>৳{{ number_format($accounts->whereIn('entry_type', ['Received','Receivable'])->sum('amount'), 2) }}</h2>
        </div>
        <div class="stat-card expense">
            <span>Total Expenses</span>
            <h2>৳{{ number_format($accounts->whereIn('entry_type', ['Payment','Payable','Purchase','Salary','Office costs'])->sum('amount'), 2) }}</h2>
        </div>
        <div class="stat-card balance">
            <span>Net Balance</span>
            <h2>৳{{ number_format($accounts->whereIn('entry_type', ['Received','Receivable'])->sum('amount') - $accounts->whereIn('entry_type', ['Payment','Payable','Purchase','Salary','Office costs'])->sum('amount'), 2) }}</h2>
        </div>
    </div>

    <!-- ACCOUNTS TABLE -->
    <div class="card">
        <h3 style="margin-bottom:15px;">Accounts for {{ $reportLabel }}</h3>
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Vendor</th>
                    <th>Purpose</th>
                    <th>Country</th>
                    <th>Amount</th>
                    <th>Document</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $acc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $acc->date }}</td>
                    <td><span class="pill">{{ $acc->entry_type }}</span></td>
                    <td>{{ $acc->vendor_name }}</td>
                    <td>{{ $acc->purpose }}</td>
                    <td>{{ $acc->country }}</td>
                    <td class="amount">৳{{ number_format($acc->amount, 2) }}</td>
                    <td>
                        @if($acc->document)
                            <a href="{{ asset('storage/'.$acc->document) }}" target="_blank" class="btn primary" style="text-decoration:none;padding:4px 8px;font-size:12px;">View</a>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $acc->last_status }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card" style="text-align:center;padding:40px;">
        <h3>No transactions found for {{ $reportLabel }}</h3>
        <p style="color:#666;margin-top:10px;">Select a different range or add transactions in that period.</p>
    </div>
    @endif

</main>
@endsection

@section('styles')
<style>
body{background:#f4f6fb;font-family:system-ui}

.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}

.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px}
.stat-card{padding:20px;border-radius:14px;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.stat-card span{display:block;font-size:14px;opacity:0.9;margin-bottom:10px}
.stat-card h2{font-size:28px;font-weight:bold;margin:0}
.stat-card.income{background:linear-gradient(135deg,#16a34a,#22c55e)}
.stat-card.expense{background:linear-gradient(135deg,#dc2626,#ef4444)}
.stat-card.balance{background:linear-gradient(135deg,#2563eb,#3b82f6)}

.card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 4px 12px rgba(0,0,0,0.05)}

.modern-table{width:100%;border-collapse:collapse}
.modern-table th{background:#2563eb;color:#fff}
.modern-table th,.modern-table td{padding:12px;border-bottom:1px solid #eee}

.pill{background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:20px;font-size:12px}
.amount{font-weight:600}

.btn{padding:10px 16px;border:none;border-radius:8px;cursor:pointer;font-weight:500}
.btn.primary{background:#2563eb;color:#fff}

.header-actions{display:flex;gap:10px}

.report-filter-form{display:flex;flex-wrap:wrap;gap:15px;align-items:flex-end}
.report-filter-form .filter-block{display:flex;flex-direction:column;min-width:180px}
.report-filter-form label{font-weight:600;margin-bottom:6px;color:#374151}
.report-filter-form input{width:220px}

@media (max-width: 768px) {
    .report-filter-form{flex-direction:column;align-items:stretch}
    .report-filter-form input{width:100%}
}
</style>
@endsection