@extends('admin-master')

@section('content')
<main class="main-content">

    <div class="page-header">
        <div>
            <h1 class="page-title">Submission Histories</h1>
            <p class="page-subtitle">Submission: {{ $submission->submission_no }}</p>
        </div>
        <div style="display:flex; gap:10px;">
            <a href="{{ route('submission.histories.create', $submission) }}" class="btn btn-primary">
                Add History
            </a>
            <a href="{{ route('submissions.show', $submission) }}" class="btn btn-secondary">
                Back
            </a>
        </div>
    </div>

    <div class="card">
        <table class="table">

            <thead>

                <tr>

                    <th>Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                    <th width="160">Action</th>

                </tr>

            </thead>

            <tbody>

                @forelse($histories as $history)

                <tr>

                    <td>
                        {{ $history->history_date }}
                    </td>

                    <td>

                        <span class="badge bg-primary">

                            {{ $history->status }}

                        </span>

                    </td>

                    <td>
                        {{ $history->remarks }}
                    </td>

                    <td>

                        <a href="{{ route('submission.histories.edit',$history) }}"
                           class="btn btn-warning btn-sm">

                            Edit

                        </a>

                        <form method="POST"
                              action="{{ route('submission.histories.destroy',$history) }}"
                              style="display:inline;">

                            @csrf
                            @method('DELETE')

                            <button
                                onclick="return confirm('Delete?')"
                                class="btn btn-danger btn-sm">

                                Delete

                            </button>

                        </form>

                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="4">
                        No history found
                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

        {{ $histories->links() }}

    </div>

</main>

{{-- ================= STYLES ================= --}}
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

.btn-warning{
    background:#f59e0b;
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

/* TEXT */
.muted {
    color:#777;
    font-size:12px;
}

</style>

@endsection