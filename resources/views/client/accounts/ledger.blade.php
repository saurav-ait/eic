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
            <button onclick="window.print()" class="btn primary" type="button">Print Ledger</button>
            <a href="{{ route('accounts.ledger.pdf', $vendor) }}?from_date={{ request('from_date') }}&to_date={{ request('to_date') }}" class="btn primary" style="text-decoration: none;">Download PDF</a>
            <a href="{{ route('accounts.vendors') }}" class="btn primary" style="text-decoration: none;">← Back to Vendors</a>
        </div>
    </div>

    @if($vendor)
    <!-- VENDOR INFO -->
    <div class="card vendor-info-card" style="margin-bottom:20px;background:linear-gradient(135deg,#3b82f6,#2563eb);color:white;">
        <h3 style="margin:0;">Vendor: {{ $vendor }}</h3>
    </div>
    @endif

    <!-- ✅ DATE FILTER -->
    <div class="card no-print" style="margin-bottom:20px;">
        <form method="GET">
            <div style="display:flex; gap:10px; align-items:end; flex-wrap:wrap;">
                <div>
                    <label>From Date</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}" class="input">
                </div>

                <div>
                    <label>To Date</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}" class="input">
                </div>

                <button type="submit" class="btn primary">Filter</button>

                <a href="{{ route('accounts.ledger', $vendor) }}" class="btn">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- PRINT HEADER (hidden in normal view, shown in print) -->
    <div class="print-header" style="display:none;">
        <h1 style="text-align:center; margin-bottom: 5px;">Vendor Ledger Report</h1>
        <h2 style="text-align:center; margin-top: 0; margin-bottom: 15px;">Vendor: {{ $vendor }}</h2>
        <p style="text-align:right; font-size: 12px; margin-bottom: 20px;">Generated on: {{ now()->format('F j, Y, h:i A') }}</p>
    </div>

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
                @if(request('from_date'))
                <tr style="background:#f1f5f9; font-weight:600;">
                    <td colspan="7" style="text-align:right;">
                        Opening Balance (before {{ request('from_date') }}):
                    </td>
                    <td class="amount">৳{{ number_format($openingBalance, 2) }}</td>
                </tr>
                @endif
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
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount">
                        @if(in_array($acc->entry_type, ['Payment','Payable','Purchase','Salary','Office costs']))
                            ৳{{ number_format($acc->amount, 2) }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="amount">৳{{ number_format($acc->running_balance, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="7" style="text-align:right;">Final Balance for {{ $vendor }}:</th>
                    <th class="amount">৳{{ number_format($accounts->last()?->running_balance ?? 0, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

</main>
@endsection

@section('styles')
<style>
body{
    background:#f1f5f9;
    font-family:'Inter', system-ui, -apple-system;
    color:#1e293b;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}
.header h1{
    font-size:24px;
    font-weight:600;
}
.header p{
    font-size:13px;
    color:#64748b;
}

/* ACTION BUTTONS */
.header-actions{
    display:flex;
    gap:10px;
}
.btn{
    padding:10px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:500;
    transition:.2s;
}
.btn.primary{
    background:#2563eb;
    color:#fff;
}
.btn.primary:hover{
    background:#1d4ed8;
}

/* CARD */
.card{
    background:#fff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 8px 20px rgba(0,0,0,0.04);
}

/* VENDOR INFO */
.vendor-info-card{
    border-radius:16px;
    padding:20px;
    font-size:16px;
    font-weight:500;
    letter-spacing:.3px;
}

/* TABLE */
.modern-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    font-size:13px;
}

.modern-table thead th{
    background:#2563eb;
    color:#fff;
    font-weight:500;
    text-align:left;
}

.modern-table th,
.modern-table td{
    padding:14px 12px;
    border-bottom:1px solid #eef2f7;
}

.modern-table tbody tr{
    transition:.2s;
}
.modern-table tbody tr:hover{
    background:#f8fafc;
}

/* ALIGNMENT */
.modern-table td:nth-child(6),
.modern-table td:nth-child(7),
.modern-table td:nth-child(8){
    text-align:right;
    font-variant-numeric: tabular-nums;
}

/* FOOTER */
.modern-table tfoot th{
    background:#f1f5f9;
    font-weight:600;
    border-top:2px solid #cbd5e1;
}

/* BADGE */
.pill{
    background:#e0f2fe;
    color:#0369a1;
    padding:4px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:500;
}

/* AMOUNT */
.amount{
    font-weight:600;
}

/* PRINT STYLES */
@media print {

    .no-print,
    .sidebar,
    .header,
    .header-actions,
    .vendor-info-card,
    nav,
    footer {
        display:none !important;
    }

    body{
        background:#fff !important;
        color:#000 !important;
        font-size:11px;
        margin:0;
        padding:0;
    }

    .print-header{
        display:block !important;
        text-align:center;
        margin-bottom:20px;
        page-break-after:avoid;
    }

    .print-header h1{
        font-size:18px;
        margin:0 0 5px 0;
        font-weight:bold;
    }

    .print-header h2{
        font-size:14px;
        margin:0 0 10px 0;
        font-weight:normal;
    }

    .print-header p{
        font-size:10px;
        margin:0;
        text-align:right;
    }

    .main-content,
    .card{
        margin:0 !important;
        padding:0 !important;
        box-shadow:none !important;
        background:#fff !important;
    }

    .modern-table{
        border-collapse:collapse;
        border:1px solid #000;
        width:100%;
        margin:0;
    }

    .modern-table th,
    .modern-table td{
        border:1px solid #666;
        padding:4px 6px;
        font-size:9px;
        text-align:left;
    }

    .modern-table th{
        background:#f0f0f0 !important;
        color:#000 !important;
        font-weight:bold;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .modern-table tfoot th{
        background:#e0e0e0 !important;
        border-top:2px solid #000 !important;
        font-weight:bold;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }

    .pill{
        background:none !important;
        color:#000 !important;
        padding:0;
        font-weight:normal;
    }

    .amount{
        text-align:right;
        font-weight:bold;
    }

    .modern-table td:nth-child(6),
    .modern-table td:nth-child(7),
    .modern-table td:nth-child(8){
        text-align:right;
    }

    @page {
        margin: 0.5in;
        size: A4;
    }
}
</style>
@endsection

@section('scripts')
<script>
// Print functionality is handled directly in the button onclick
</script>
@endsection