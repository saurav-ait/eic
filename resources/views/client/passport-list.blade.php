@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- Top Bar --}}
    <div class="top-bar" style="justify-content: space-between; display: flex; align-items: center; flex-wrap: wrap; gap:10px;">
        <div class="top-bar-title">
            <h1>Passport List</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="toast-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="toast-error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Search --}}
    <div style="margin-bottom: 20px; display:flex; justify-content: space-between; flex-wrap: wrap; gap:10px;">
        <form action="{{ route('passports.index') }}" method="GET" style="display:flex; gap:10px; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Passport No / Name" 
                class="input-search">
            <button type="submit" class="btn-primary">Search</button>
        </form>
        <a href="{{ route('passports.create') }}" class="btn-primary">Add Passport</a>
    </div>

    {{-- Passport Table --}}
    <div class="content-section">
        <table class="table">
            <thead>
                <tr>
                    <th>Passport Number</th>
                    <th>Name</th>
                    <th style="text-align:center;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($passports as $passport)
                    <tr>
                        <td>{{ $passport->passport_number }}</td>
                        <td>{{ $passport->familyname }} {{ $passport->givenname }}</td>
                        <td style="text-align:center;">
                            <a href="{{ route('passports.show', $passport) }}" class="btn-primary btn-sm">View</a>

                            {{-- Delete --}}
                            <form action="{{ route('passports.destroy', $passport) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this passport?')"
                                    @if($passport->job_subcategory_id) disabled title="Assigned passports cannot be deleted" @endif>
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;">No passports found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div style="margin-top:15px;">
            {{ $passports->withQueryString()->links() }}
        </div>
    </div>

</main>

{{-- Styles --}}
<style>
.main-content {
    padding: 20px;
}

/* Buttons */
.btn-primary {
    background-color: #1E4BA6;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 12px;
    font-size: 13px;
}
.btn-primary:hover { background-color: #163A7A; }

.btn-primary.btn-sm { padding: 5px 10px; font-size:12px; }

.btn-danger {
    background-color: #E74C3C;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 12px;
    font-size: 13px;
}
.btn-danger:hover { background-color: #C0392B; }

.btn-danger.btn-sm { padding:5px 10px; font-size:12px; }

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 10px;
}

.table th {
    background: #1E4BA6;
    color: #fff;
    font-weight: 600;
    text-align: left;
}

/* Inputs */
.input-search {
    padding: 8px 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

/* Toasts */
.toast-success {
    background: #d1fae5;
    padding: 10px;
    border-radius: 6px;
    color: #065f46;
    margin-bottom: 15px;
}

.toast-error {
    background: #fed7d7;
    padding: 10px;
    border-radius: 6px;
    color: #c53030;
    margin-bottom: 15px;
}

/* Responsive */
@media(max-width:768px){
    .top-bar { flex-direction: column; align-items: flex-start; gap:10px; }
    .table th, .table td { font-size: 12px; padding:6px; }
    .btn-primary, .btn-danger { font-size:12px; padding:6px 10px; }
}
</style>
@endsection