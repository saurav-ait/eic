@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Passport List</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <a href="{{ route('passports.create') }}" class="btn-primary">
            + Add Passport
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="toast-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="toast-error">{{ session('error') }}</div>
    @endif

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <form method="GET" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search passport no / name">
            <button class="btn-primary">Search</button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Passport Info</th>
                    <th>Status</th>
                    <th style="width:200px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($passports as $passport)
                <tr>

                    {{-- SERIAL --}}
                    <td>{{ $loop->iteration }}</td>

                    {{-- INFO --}}
                    <td>
                        <strong>{{ $passport->passport_number }}</strong>
                        <div class="muted">
                            {{ $passport->familyname }} {{ $passport->givenname }}
                        </div>
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($passport->job_subcategory_id)
                            <span class="badge assigned">Assigned</span>
                        @else
                            <span class="badge free">Available</span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td>
                        <div class="action-row">

                            <a href="{{ route('passports.show', $passport->id) }}"
                               class="btn success btn-xs">
                               View
                            </a>

                            <form action="{{ route('passports.destroy', $passport->id) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this passport?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn danger btn-xs"
                                    @if($passport->job_subcategory_id)
                                        disabled title="Assigned passports cannot be deleted"
                                    @endif>
                                    Delete
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">
                        No passports found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="pagination">
            {{ $passports->withQueryString()->links() }}
        </div>
    </div>

</main>

{{-- ================= STYLES ================= --}}
<style>

/* HEADER */
.top-bar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:10px;
}

/* TOOLBAR */
.toolbar {
    margin-bottom:15px;
}

.search-box {
    display:flex;
    gap:10px;
}

.search-box input {
    padding:8px 10px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* TABLE CARD */
.table-card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

/* TABLE */
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
    text-align:left;
}

tr:hover {
    background:#f5f8ff;
}

/* BADGES */
.badge {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge.assigned {
    background:#fee2e2;
    color:#b91c1c;
}

.badge.free {
    background:#dcfce7;
    color:#166534;
}

/* ACTION */
.action-row {
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}

/* BUTTONS */
.btn {
    border:none;
    cursor:pointer;
}

.btn-xs {
    padding:5px 8px;
    font-size:12px;
    border-radius:4px;
}

.btn.success { background:#38a169; color:#fff; }
.btn.success:hover { background:#2f855a; }

.btn.danger { background:#e53e3e; color:#fff; }
.btn.danger:hover { background:#c53030; }

.btn-primary {
    background:#1E4BA6;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
}

.btn-primary:hover {
    background:#163A7A;
}

/* TEXT */
.muted {
    font-size:12px;
    color:#777;
}

.text-center {
    text-align:center;
}

/* TOAST */
.toast-success {
    background:#d1fae5;
    padding:10px;
    border-radius:6px;
    color:#065f46;
    margin-bottom:15px;
}

.toast-error {
    background:#fed7d7;
    padding:10px;
    border-radius:6px;
    color:#c53030;
    margin-bottom:15px;
}

/* PAGINATION */
.pagination {
    margin-top:15px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .top-bar {
        flex-direction:column;
        align-items:flex-start;
    }

    th, td {
        font-size:12px;
        padding:8px;
    }

    .btn-primary {
        font-size:12px;
        padding:6px 10px;
    }
}

</style>

@endsection