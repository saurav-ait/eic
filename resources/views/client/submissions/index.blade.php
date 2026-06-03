@extends('admin-master')

@section('content')

<main class="main-content">

    {{-- HEADER --}}
    <div class="page-header">

        <div>
            <h1 class="page-title">
                Submission Management
            </h1>

            <p class="page-subtitle">
                Manage all passport submissions
            </p>
        </div>

        <div>
            <a href="{{ route('submissions.create') }}"
               class="btn btn-primary">
                + Add Submission
            </a>
        </div>

    </div>

    {{-- STATISTICS --}}
    <div class="stats-grid">

        <div class="stat-card">
            <h3>{{ $total ?? 0 }}</h3>
            <span>Total</span>
        </div>

        <div class="stat-card blue">
            <h3>{{ $submitted ?? 0 }}</h3>
            <span>Submitted</span>
        </div>

        <div class="stat-card orange">
            <h3>{{ $processing ?? 0 }}</h3>
            <span>Processing</span>
        </div>

        <div class="stat-card green">
            <h3>{{ $approved ?? 0 }}</h3>
            <span>Approved</span>
        </div>

        <div class="stat-card red">
            <h3>{{ $rejected ?? 0 }}</h3>
            <span>Rejected</span>
        </div>

        <div class="stat-card purple">
            <h3>{{ $returned ?? 0 }}</h3>
            <span>Returned</span>
        </div>

        <div class="stat-card dark">
            <h3>{{ $completed ?? 0 }}</h3>
            <span>Completed</span>
        </div>

    </div>

    {{-- FILTER CARD --}}
    <div class="card">

        <form method="GET">

            <div class="filter-grid">

                <div>
                    <label>Search</label>

                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           class="form-control"
                           placeholder="Submission No / Passport / File No">
                </div>

                <div>
                    <label>Country</label>

                    <select name="country" class="form-control">

                        <option value="">
                            All Countries
                        </option>

                        @foreach($countries as $country)

                            <option value="{{ $country->id }}"
                                {{ request('country') == $country->id ? 'selected' : '' }}>

                                {{ $country->name }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label>Status</label>

                    <select name="status" class="form-control">

                        <option value="">All Status</option>

                        @foreach([
                            'Draft',
                            'Submitted',
                            'Processing',
                            'Document Requested',
                            'Approved',
                            'Rejected',
                            'Returned',
                            'Completed'
                        ] as $status)

                            <option value="{{ $status }}"
                                {{ request('status') == $status ? 'selected' : '' }}>

                                {{ $status }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label>From Date</label>

                    <input type="date"
                           name="from_date"
                           value="{{ request('from_date') }}"
                           class="form-control">
                </div>

                <div>
                    <label>To Date</label>

                    <input type="date"
                           name="to_date"
                           value="{{ request('to_date') }}"
                           class="form-control">
                </div>

            </div>

            <div class="filter-actions">

                <button class="btn btn-primary">
                    Filter
                </button>

                <a href="{{ route('submissions.index') }}"
                   class="btn btn-secondary">

                    Reset

                </a>

            </div>

        </form>

    </div>

    {{-- TABLE --}}
    <div class="card">

        <div class="table-responsive">

            <table class="table">

                <thead>

                <tr>

                    <th>#</th>

                    <th>Submission No</th>

                    <th>Passport No</th>

                    <th>Country</th>

                    <th>Lawyer</th>

                    <th>Date</th>

                    <th>Status</th>

                    <th width="180">
                        Action
                    </th>

                </tr>

                </thead>

                <tbody>

                @forelse($submissions as $submission)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            {{ $submission->submission_no }}
                        </td>

                        <td>
                            {{ $submission->passport->passport_number ?? '-' }}
                        </td>

                        <td>
                            {{ $submission->country->name ?? '-' }}
                        </td>

                        <td>
                            {{ $submission->lawyer->name ?? '-' }}
                        </td>

                        <td>
                            {{ date('d M Y', strtotime($submission->submission_date)) }}
                        </td>

                        <td>

                            @php

                                $badge = match($submission->status){

                                    'Submitted' => 'primary',

                                    'Processing' => 'warning',

                                    'Approved' => 'success',

                                    'Rejected' => 'danger',

                                    'Returned' => 'purple',

                                    'Completed' => 'dark',

                                    default => 'secondary'

                                };

                            @endphp

                            <span class="badge badge-{{ $badge }}">
                                {{ $submission->status }}
                            </span>

                        </td>

                        <td>

                            <div class="action-buttons">

                                <a href="{{ route('submissions.edit',$submission) }}"
                                   class="btn btn-sm btn-primary">

                                    Edit

                                </a>

                                <form action="{{ route('submissions.destroy',$submission) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        onclick="return confirm('Delete this submission?')"
                                        class="btn btn-sm btn-danger">

                                        Delete

                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9" class="text-center">

                            No submissions found

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

        <div class="pagination-wrapper">

            {{ $submissions->links() }}

        </div>

    </div>

</main>

@endsection

@section('styles')

<style>

.main-content{
    padding:25px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title{
    margin:0;
    font-size:28px;
    font-weight:700;
}

.page-subtitle{
    color:#64748b;
}

.card{
    background:#fff;
    border-radius:14px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 3px 15px rgba(0,0,0,.05);
}

.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(170px,1fr));
    gap:15px;
    margin-bottom:20px;
}

.stat-card{
    background:#fff;
    padding:20px;
    border-radius:12px;
    text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,.05);
}

.stat-card h3{
    margin:0;
    font-size:28px;
}

.filter-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:15px;
}

.form-control{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:8px;
}

.filter-actions{
    margin-top:20px;
    display:flex;
    gap:10px;
}

.table{
    width:100%;
    border-collapse:collapse;
}

.table th{
    background:#2563eb;
    color:#fff;
    padding:12px;
}

.table td{
    padding:12px;
    border-bottom:1px solid #eee;
}

.btn{
    padding:10px 14px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    cursor:pointer;
}

.btn-primary{
    background:#2563eb;
    color:white;
}

.btn-danger{
    background:#dc2626;
    color:white;
}

.btn-secondary{
    background:#64748b;
    color:white;
}

.btn-sm{
    padding:6px 10px;
    font-size:13px;
}

.action-buttons{
    display:flex;
    gap:8px;
}

.badge{
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge-primary{
    background:#dbeafe;
    color:#1d4ed8;
}

.badge-success{
    background:#dcfce7;
    color:#15803d;
}

.badge-danger{
    background:#fee2e2;
    color:#dc2626;
}

.badge-warning{
    background:#fef3c7;
    color:#d97706;
}

.badge-purple{
    background:#ede9fe;
    color:#7c3aed;
}

.badge-dark{
    background:#e2e8f0;
    color:#0f172a;
}

.badge-secondary{
    background:#f1f5f9;
    color:#475569;
}

.pagination-wrapper{
    margin-top:20px;
}

</style>

@endsection