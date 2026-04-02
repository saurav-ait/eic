@extends('admin-master')

@section('content')

<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Dashboard Overview</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- KPI CARDS --}}
    <div class="dashboard-grid">

        <div class="card">
            <div class="card-icon">👥</div>
            <div class="card-title">Total Users</div>
            <div class="card-value">{{ $totalUsers }}</div>
        </div>

        <div class="card">
            <div class="card-icon">📘</div>
            <div class="card-title">Total Passports</div>
            <div class="card-value">{{ $totalPassports }}</div>
        </div>

        <div class="card">
            <div class="card-icon">✅</div>
            <div class="card-title">Assigned</div>
            <div class="card-value">{{ $assignedPassports }}</div>
        </div>

        <div class="card">
            <div class="card-icon">📂</div>
            <div class="card-title">Categories</div>
            <div class="card-value">{{ $categories }}</div>
        </div>

    </div>

    {{-- MAIN GRID --}}
    <div class="dashboard-layout">

        {{-- LEFT: RECENT ACTIVITY --}}
        <div class="content-card">
            <h3>Recent Passports</h3>

            <table>
                <thead>
                    <tr>
                        <th>Passport</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPassports as $passport)
                        <tr>
                            <td><strong>{{ $passport->passport_number }}</strong></td>
                            <td>{{ $passport->givenname }} {{ $passport->familyname }}</td>
                            <td>
                                @if($passport->job_subcategory_id)
                                    <span class="badge success">Assigned</span>
                                @else
                                    <span class="badge pending">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No recent data</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- RIGHT: QUICK ACTIONS --}}
        <div class="content-card">
            <h3>Quick Actions</h3>

            <div class="quick-actions">
                <a href="{{ route('passports.index') }}" class="qa-btn">📘 Manage Passports</a>
                <a href="{{ route('jobs.assign') }}" class="qa-btn">💼 Assign Passport</a>
                <a href="{{ route('jobs.categories') }}" class="qa-btn">📂 Categories</a>
                <a href="{{ route('jobs.status') }}" class="qa-btn">⚙ Status</a>
            </div>
        </div>

    </div>

</main>

<style>

/* GRID */
.dashboard-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit, minmax(220px,1fr));
    gap:20px;
    margin-bottom:25px;
}

/* CARDS */
.card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card:hover {
    transform:translateY(-4px);
}

.card-icon {
    font-size:26px;
    margin-bottom:10px;
}

.card-title {
    font-size:13px;
    color:#777;
    margin-bottom:5px;
}

.card-value {
    font-size:28px;
    font-weight:700;
    color:#1E4BA6;
}

/* MAIN LAYOUT */
.dashboard-layout {
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:20px;
}

/* CONTENT CARD */
.content-card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

.content-card h3 {
    margin-bottom:15px;
    color:#1E4BA6;
}

/* TABLE */
table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
    font-size:13px;
    text-align:left;
}

td {
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:13px;
}

/* BADGES */
.badge {
    padding:4px 8px;
    border-radius:20px;
    font-size:11px;
    font-weight:600;
}

.badge.success {
    background:#d1fae5;
    color:#065f46;
}

.badge.pending {
    background:#fef3c7;
    color:#92400e;
}

/* QUICK ACTIONS */
.quick-actions {
    display:flex;
    flex-direction:column;
    gap:10px;
}

.qa-btn {
    display:block;
    text-decoration:none;
    background:#f5f7fb;
    padding:12px;
    border-radius:6px;
    font-size:13px;
    font-weight:600;
    color:#1E4BA6;
    transition:0.3s;
}

.qa-btn:hover {
    background:#1E4BA6;
    color:#fff;
}

/* RESPONSIVE */
@media(max-width:900px){
    .dashboard-layout {
        grid-template-columns:1fr;
    }
}

</style>

@endsection