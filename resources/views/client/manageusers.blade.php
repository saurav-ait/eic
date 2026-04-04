@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Manage Users</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Users</h3>
            <p>{{ $users->count() }}</p>
        </div>

        <div class="stat-card">
            <h3>Admins</h3>
            <p>{{ $users->where('role','Admin')->count() }}</p>
        </div>

        <div class="stat-card">
            <h3>Employees</h3>
            <p>{{ $users->where('role','Employee')->count() }}</p>
        </div>

        <div class="stat-card">
            <h3>Guests</h3>
            <p>{{ $users->where('role','Guest')->count() }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>User Info</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width:180px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td>
                        <strong>{{ $user->name }}</strong>
                        <div class="muted small">
                            Joined {{ $user->created_at->diffForHumans() }}
                        </div>
                    </td>

                    <td>{{ $user->email }}</td>

                    <td>
                        <span class="role-badge {{ strtolower($user->role) }}">
                            {{ $user->role }}
                        </span>
                    </td>

                    <td>
                        <form action="{{ route('update.role', $user->id) }}" method="POST" class="action-row">
                            @csrf

                            <select name="role" class="compact-select">
                                <option value="Admin" {{ $user->role == 'Admin' ? 'selected' : '' }}>Admin</option>
                                <option value="Employee" {{ $user->role == 'Employee' ? 'selected' : '' }}>Employee</option>
                                <option value="Guest" {{ $user->role == 'Guest' ? 'selected' : '' }}>Guest</option>
                            </select>

                            <button class="btn success btn-xs">Update</button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No users found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</main>

{{-- ================= STYLES ================= --}}
<style>

/* ALERT */
.alert.success {
    background:#d1fae5;
    color:#065f46;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
}

/* STATS */
.stats-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap:15px;
    margin-bottom:20px;
}

.stat-card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

.stat-card h3 {
    font-size:14px;
    color:#777;
}

.stat-card p {
    font-size:26px;
    font-weight:bold;
    color:#1E4BA6;
}

/* TABLE */
.table-card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

table {
    width:100%;
    border-collapse:collapse;
}

th, td {
    padding:12px;
    border-bottom:1px solid #eee;
}

th {
    background:#1E4BA6;
    color:#fff;
}

tr:hover {
    background:#f5f8ff;
}

/* ROLE BADGE */
.role-badge {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

/* COLORS */
.role-badge.admin { background:#fee2e2; color:#b91c1c; }
.role-badge.employee { background:#dcfce7; color:#166534; }
.role-badge.guest { background:#e0f2fe; color:#075985; }

/* ACTION */
.action-row {
    display:flex;
    gap:6px;
    align-items:center;
}

/* SELECT */
.compact-select {
    padding:5px;
    border-radius:5px;
    border:1px solid #ccc;
    font-size:12px;
}

/* BUTTONS */
.btn-xs {
    padding:5px 8px;
    font-size:12px;
    border-radius:4px;
    border:none;
    cursor:pointer;
}

.btn.success { background:#38a169; color:#fff; }

/* TEXT */
.muted {
    color:#777;
    font-size:12px;
}

.text-center {
    text-align:center;
}

/* RESPONSIVE */
@media(max-width:768px){
    .action-row {
        flex-direction:column;
        align-items:stretch;
    }
}

</style>

@endsection