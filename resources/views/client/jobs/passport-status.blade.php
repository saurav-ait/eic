@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Passport Status</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert-success-box">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER + SEARCH --}}
    <div class="filter-bar">
        <input type="text" id="search" placeholder="🔍 Search Passport Number or Name">

        <select id="statusFilter">
            <option value="">All Status</option>
            @foreach($statuses as $status)
                <option value="{{ $status->id }}">{{ $status->name }}</option>
            @endforeach
        </select>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <h3>All Passports</h3>

        <table class="modern-table" id="passportTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Passport No</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @foreach($passports as $key => $passport)
                <tr 
                    data-name="{{ strtolower($passport->name) }}"
                    data-passport="{{ strtolower($passport->passport_number) }}"
                    data-status="{{ $passport->status_id }}"
                >
                    <td>{{ $key + 1 }}</td>

                    <td class="passport-no">
                        {{ $passport->passport_number }}
                    </td>

                    <td>
                        {{ $passport->name }}
                    </td>

                    <td>
                        <span class="status-badge">
                            {{ $passport->status->name ?? 'N/A' }}
                        </span>
                    </td>

                    <td>
                        <form action="{{ route('jobs.passport.status.update') }}" method="POST" class="inline-form">
                            @csrf

                            <input type="hidden" name="passport_id" value="{{ $passport->id }}">

                            <select name="status_id" required>
                                <option value="">Select</option>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}"
                                        {{ $passport->status_id == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="btn-update">Update</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</main>

{{-- JS --}}
<script>
document.getElementById('search').addEventListener('keyup', function() {
    let value = this.value.toLowerCase();

    document.querySelectorAll('#passportTable tbody tr').forEach(row => {
        let name = row.dataset.name;
        let pass = row.dataset.passport;

        row.style.display = (name.includes(value) || pass.includes(value)) ? '' : 'none';
    });
});

document.getElementById('statusFilter').addEventListener('change', function() {
    let value = this.value;

    document.querySelectorAll('#passportTable tbody tr').forEach(row => {
        row.style.display = (!value || row.dataset.status == value) ? '' : 'none';
    });
});
</script>

{{-- STYLES --}}
<style>

/* SUCCESS ALERT */
.alert-success-box {
    background: #e6fffa;
    border-left: 5px solid #38b2ac;
    padding: 12px 15px;
    border-radius: 6px;
    margin-bottom: 20px;
    color: #065f46;
    font-weight: 600;
}

/* FILTER BAR */
.filter-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.filter-bar input,
.filter-bar select {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 14px;
    min-width: 220px;
}

/* CARD */
.table-card {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

.table-card h3 {
    margin-bottom: 20px;
    color: #1E4BA6;
}

/* TABLE */
.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table th {
    background: #1E4BA6;
    color: white;
    padding: 12px;
    text-align: left;
    font-size: 13px;
}

.modern-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
}

.modern-table tr:hover {
    background: #f8faff;
}

/* BADGE */
.status-badge {
    background: #e3f2fd;
    color: #1E4BA6;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

/* FORM */
.inline-form {
    display: flex;
    gap: 10px;
    align-items: center;
}

.inline-form select {
    padding: 8px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* BUTTON */
.btn-update {
    background: #1E4BA6;
    color: white;
    border: none;
    padding: 8px 14px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    transition: 0.3s;
}

.btn-update:hover {
    background: #0f2f6d;
}

/* MOBILE */
@media (max-width: 768px) {
    .inline-form {
        flex-direction: column;
        align-items: flex-start;
    }

    .filter-bar {
        flex-direction: column;
    }
}

</style>

@endsection