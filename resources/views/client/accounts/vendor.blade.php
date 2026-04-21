@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Vendor Ledger: {{ $vendor ?? 'All Vendors' }}</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('accounts.vendors') }}" class="btn primary" style="text-decoration: none;">← Back to Vendors</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success" style="background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:15px;">
            {{ session('success') }}
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
                    <th>Amount</th>
                    <th>Balance</th>
                </tr>
            </thead>

            <tbody>
                @foreach($accounts as $acc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $acc->date }}</td>
                    <td><span class="pill">{{ $acc->entry_type }}</span></td>
                    <td class="amount">৳{{ number_format($acc->amount, 2) }}</td>
                    <td class="amount">৳{{ number_format($acc->running_balance ?? 0, 2) }}</td>
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

.alert-success{background:#d1fae5;color:#065f46;padding:12px;border-radius:8px;margin-bottom:15px}
</style>
@endsection