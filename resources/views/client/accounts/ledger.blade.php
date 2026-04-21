@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Vendor Ledger</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="header-actions no-print">
            <button onclick="window.print()" class="btn primary">Print Ledger</button>
            <a href="{{ route('accounts.ledger.pdf', $vendor) }}" class="btn primary" style="text-decoration: none;">Download PDF</a>
            <a href="{{ route('accounts.vendors') }}" class="btn primary" style="text-decoration: none;">← Back to Vendors</a>
        </div>
    </div>

    @if($vendor)
    <!-- VENDOR INFO -->
    <div class="card" style="margin-bottom:20px;background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;">
        <h3 style="margin:0;">Vendor: {{ $vendor }}</h3>
    </div>
    @endif

    <!-- LEDGER TABLE -->
    <div class="card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Purpose</th>
                    <th>Details</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @php $runningBalance = 0; @endphp
                @foreach($accounts as $acc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $acc->date }}</td>
                    <td><span class="pill">{{ $acc->entry_type }}</span></td>
                    <td>{{ $acc->purpose }}</td>
                    <td>{{ substr($acc->details, 0, 30) ?? '-' }}{{ strlen($acc->details ?? '') > 30 ? '...' : '' }}</td>
                    <td class="amount">
                        @if(in_array($acc->entry_type, ['Received','Receivable']))
                            ৳{{ number_format($acc->amount, 2) }}
                            @php $runningBalance += $acc->amount; @endphp
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount">
                        @if(in_array($acc->entry_type, ['Payment','Payable','Purchase','Salary','Office costs']))
                            ৳{{ number_format($acc->amount, 2) }}
                            @php $runningBalance -= $acc->amount; @endphp
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount">৳{{ number_format($runningBalance, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</main>
@endsection

@section('styles')
<style>
body{background:#f4f6fb;font-family:system-ui}

.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}

.card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 4px 12px rgba(0,0,0,0.05)}

.modern-table{width:100%;border-collapse:collapse}
.modern-table th{background:#2563eb;color:#fff}
.modern-table th,.modern-table td{padding:12px;border-bottom:1px solid #eee}

.pill{background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:20px;font-size:12px}
.amount{font-weight:600}

.btn{padding:10px 16px;border:none;border-radius:8px;cursor:pointer;font-weight:500}
.btn.primary{background:#2563eb;color:#fff}

.header-actions{display:flex;gap:10px}

/* Print Styles */
@media print {
    /* Hide unnecessary UI elements */
    .no-print, 
    .sidebar, 
    .top-bar, 
    .header-actions,
    nav, 
    footer {
        display: none !important;
    }

    /* Reset layout for paper */
    body {
        background: #fff !important;
        color: #000 !important;
        margin: 0;
        padding: 0;
    }

    .main-content, .card {
        margin: 0 !important;
        padding: 0 !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    .modern-table {
        border: 1px solid #000;
    }

    .modern-table th {
        background-color: #f3f4f6 !important;
        color: #000 !important;
        border-bottom: 2px solid #000 !important;
    }
    
    .modern-table td {
        border-bottom: 1px solid #eee !important;
    }
}
</style>
@endsection