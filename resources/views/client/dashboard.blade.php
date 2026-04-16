@extends('admin-master')

@section('styles')
<style>
.main-content { padding:20px; font-family:Arial, sans-serif; }
.top-bar-title h1 { color:#1E4BA6; margin:0; }
.top-bar-title p { margin:2px 0 0; color:#555; font-size:14px; }
.btn-primary { background:#1E4BA6; color:#fff; padding:10px 15px; border:none; border-radius:6px; cursor:pointer; text-decoration:none; display:inline-block; }
.btn-primary:hover { background:#163A7A; }

.stats-cards { display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px; margin-bottom:30px; }
.stats-cards .card { background:#fff; border-radius:10px; padding:20px; box-shadow:0 4px 10px rgba(0,0,0,0.05); text-align:center; }
.stats-cards .card h3 { margin:0 0 10px; font-size:16px; color:#555; }
.stats-cards .card .stat { font-size:28px; font-weight:700; color:#1E4BA6; margin:0; }

.stats-grid { display:flex; gap:20px; margin:20px 0; flex-wrap:wrap; }
.card { background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); flex:1; min-width:200px; text-align:center; position:relative; overflow:hidden; }
.card h3 { margin:10px 0 5px; color:#333; font-size:16px; }
.card .stat { font-size:24px; font-weight:bold; color:#1E4BA6; margin:0; }

.tile { position:relative; color: white; transition: transform 0.3s ease, box-shadow 0.3s ease; border-radius: 15px; }
.tile:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
.tile::before { content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%; background: rgba(255,255,255,0.1); transform: rotate(45deg); transition: 0.5s; opacity: 0; }
.tile:hover::before { opacity: 1; transform: rotate(45deg) translate(50%, 50%); }
.tile .tile-icon { font-size:40px; margin-bottom:10px; position:relative; z-index:1; }
.tile h3 { position:relative; z-index:1; color: white; margin: 10px 0 5px; }
.tile .stat { position:relative; z-index:1; color: white; font-size: 28px; font-weight: bold; margin: 0; }

.income-tile { background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%); }
.expense-tile { background: linear-gradient(135deg, #f44336 0%, #d32f2f 100%); }
.balance-tile { background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%); }

.table-card { background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); margin:20px 0; }
.table-card h3 { margin-bottom:15px; color:#1E4BA6; }
table { width:100%; border-collapse:collapse; }
th,td { padding:10px; text-align:left; border-bottom:1px solid #eee; }
th { background:#f5f5f5; font-weight:600; }

.status-cards { display:flex; gap:15px; margin:20px 0; flex-wrap:wrap; }
.status-card { background:#fff; padding:15px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.1); flex:1; min-width:150px; text-align:center; }
.status-badge { background:#e3f2fd; color:#1E4BA6; padding:5px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; margin-bottom:10px; }
.status-count { font-size:28px; font-weight:bold; color:#1E4BA6; }

.section-title { font-size:18px; font-weight:600; color:#333; margin:30px 0 15px; }

.quick-actions { margin:20px 0; }
.quick-actions a { padding:10px 15px; border-radius:6px; background:#1E4BA6; color:#fff; text-decoration:none; }
.quick-actions a:hover { background:#163A7A; }

.btn-sm { background:#1E4BA6; color:#fff; padding:4px 10px; border-radius:4px; font-size:12px; text-decoration:none; }
.btn-sm:hover { background:#163A7A; }

.amount { text-align:right; }

@media(max-width:768px) {
    .stats-cards { grid-template-columns:1fr; }
}

/* Additional styles for accounts */
.panel-card { background:#fff; border-radius:10px; padding:20px; box-shadow:0 4px 10px rgba(0,0,0,0.05); margin-bottom:20px; }
.panel-card h3 { margin-bottom:15px; color:#1E4BA6; }
.input-field { width:100%; padding:10px; border:1px solid #ddd; border-radius:6px; font-size:14px; }
.btn-danger { background:#e53e3e; color:#fff; padding:10px 15px; border:none; border-radius:6px; cursor:pointer; }
.btn-danger:hover { background:#c53030; }
.action-row { display:flex; gap:5px; }
.tag { background:#e3f2fd; padding:3px 8px; border-radius:12px; font-size:12px; }
</style>
@endsection

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <div class="top-bar-title">
            <h1>Dashboard</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div>
            <button onclick="location.reload()" class="btn-primary">Refresh Now</button>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="stats-cards" style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:20px; margin-bottom:30px;">
        <div class="card">
            <h3>Total Services</h3>
            <p class="stat">{{ $servicesCount ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>Total Categories</h3>
            <p class="stat">{{ $categoriesCount ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>Total Subcategories</h3>
            <p class="stat">{{ $subcategoriesCount ?? 0 }}</p>
        </div>
        <div class="card">
            <h3>Total Passports</h3>
            <p class="stat">{{ $passportsCount ?? 0 }}</p>
        </div>
    </div>

    {{-- ACCOUNTS OVERVIEW --}}
    <div class="section-title">Financial Overview</div>

    <div class="stats-grid">
        <a href="{{ route('accounts.index') }}" class="card tile income-tile" style="text-decoration: none;">
            <div class="tile-icon">💰</div>
            <h3>Total Income</h3>
            <p class="stat">${{ number_format($income, 2) }}</p>
        </a>
        <a href="{{ route('accounts.index') }}" class="card tile expense-tile" style="text-decoration: none;">
            <div class="tile-icon">💸</div>
            <h3>Total Expenses</h3>
            <p class="stat">${{ number_format($expense, 2) }}</p>
        </a>
        <a href="{{ route('accounts.index') }}" class="card tile balance-tile" style="text-decoration: none;">
            <div class="tile-icon">⚖️</div>
            <h3>Current Balance</h3>
            <p class="stat">${{ number_format($balance, 2) }}</p>
        </a>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="quick-actions" style="display:flex; flex-wrap:wrap; gap:15px; margin-bottom:30px;">
        <a href="{{ route('jobs.services') }}" class="btn-primary">Manage Services</a>
        <a href="{{ route('jobs.categories') }}" class="btn-primary">Manage Categories</a>
        <a href="{{ route('jobs.assign') }}" class="btn-primary">Manage Subcategories</a>
        <a href="{{ route('passports.index') }}" class="btn-primary">Manage Passports</a>
        <a href="{{ route('jobs.manage') }}" class="btn-primary">Manage Jobs</a>
    </div>

    {{-- RECENT ACTIVITY TABLE --}}
    <div class="table-card">
        <h3>Recent Passport Assignments</h3>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Passport</th>
                    <th>Subcategory</th>
                    <th>Category</th>
                    <th>Assigned At</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentAssignments as $index => $assignment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $assignment->passport_number }} ({{ $assignment->givenname.' '.$assignment->familyname }})</td>
                        <td>{{ $assignment->subcategory->name ?? '—' }}</td>
                        <td>{{ $assignment->subcategory->category->name ?? '—' }}</td>
                        <td>{{ $assignment->created_at->format('d M, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">No recent assignments.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PASSPORT STATUS MONITOR --}}
    <div class="section-title">Passport Status Monitor</div>

    {{-- STATUS SUMMARY CARDS --}}
    <div class="status-cards">
        @forelse($statuses as $status)
            <div class="status-card">
                <div class="status-badge">{{ $status->name }}</div>
                <div class="status-count">{{ $status->passports_count }}</div>
                <div class="status-label">Passports</div>
            </div>
        @empty
        @endforelse
        <div class="status-card unstatused">
            <div class="status-badge" style="background:#e2e8f0; color:#555;">No Status</div>
            <div class="status-count" style="color:#888;">{{ $unstatusedCount }}</div>
            <div class="status-label">Passports</div>
        </div>
    </div>

    {{-- STATUS PASSPORT TABLE --}}
    <div class="table-card" style="margin-top:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:15px;">
            <h3 style="margin:0;">Passport Status Overview</h3>
            <a href="{{ route('jobs.passport.status') }}" class="btn-primary" style="font-size:12px; padding:7px 12px;">Manage Status</a>
        </div>

        {{-- FILTER --}}
        <div style="margin-bottom:12px;">
            <select id="statusFilter" onchange="filterStatusTable()" style="padding:7px 10px; border-radius:5px; border:1px solid #ccc; font-size:13px;">
                <option value="">All Statuses</option>
                @foreach($statuses as $status)
                    <option value="{{ $status->name }}">{{ $status->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="overflow-x:auto;">
            <table id="statusTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Passport No.</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Subcategory</th>
                        <th>Updated At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($statusPassports as $index => $passport)
                        <tr data-status="{{ $passport->status->name ?? '' }}">
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $passport->passport_number }}</strong></td>
                            <td>{{ $passport->givenname }} {{ $passport->familyname }}</td>
                            <td>
                                <span class="status-pill">{{ $passport->status->name ?? '—' }}</span>
                            </td>
                            <td>{{ $passport->subcategory->name ?? '—' }}</td>
                            <td>{{ $passport->updated_at->format('d M, Y') }}</td>
                            <td>
                                <a href="{{ route('passports.show', $passport) }}" class="btn-sm">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; color:#999;">No passports with status assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</main>

{{-- AUTO REFRESH --}}
<script>
    setInterval(() => {
        fetch(window.location.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newCards = doc.querySelector('.stats-cards');
            const oldCards = document.querySelector('.stats-cards');
            if(newCards && oldCards) oldCards.innerHTML = newCards.innerHTML;
        });
    }, 30000); // every 30 seconds

    function filterStatusTable() {
        const val = document.getElementById('statusFilter').value.toLowerCase();
        document.querySelectorAll('#statusTable tbody tr').forEach(row => {
            const status = (row.dataset.status || '').toLowerCase();
            row.style.display = (!val || status === val) ? '' : 'none';
        });
    }
</script>

@endsection