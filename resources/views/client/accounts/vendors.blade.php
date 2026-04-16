@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Vendor List</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('accounts.index') }}" class="btn primary" style="text-decoration: none;">← Back to Accounts</a>
        </div>
    </div>

    <!-- VENDORS TABLE -->
    <div class="card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor Name</th>
                    <th>Total Transactions</th>
                    <th>Total Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $vendor)
                @php
                    $vendorAccounts = \App\Models\Account::where('vendor_name', $vendor)->get();
                    $totalAmount = $vendorAccounts->sum('amount');
                    $transactionCount = $vendorAccounts->count();
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $vendor }}</td>
                    <td><span class="pill">{{ $transactionCount }}</span></td>
                    <td class="amount">${{ number_format($totalAmount, 2) }}</td>
                    <td>
                        <a href="{{ route('accounts.ledger', ['vendor' => $vendor]) }}" class="btn primary" style="text-decoration: none; padding: 6px 12px; font-size: 12px;">View Ledger</a>
                    </td>
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
</style>
@endsection