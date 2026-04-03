@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Status Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success" style="margin-bottom:15px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="card-box">

        <div class="card-header">
            <h3>Add New Status</h3>
        </div>

        <form action="{{ route('jobs.status.store') }}" method="POST" class="form-grid">
            @csrf

            {{-- NAME --}}
            <div class="form-group">
                <label>Status Name</label>
                <input type="text" name="name" placeholder="Enter status name" required>
            </div>

            {{-- DESCRIPTION --}}
            <div class="form-group full">
                <label>Description</label>
                <textarea name="description" rows="3" placeholder="Optional description"></textarea>
            </div>

            {{-- BUTTON --}}
            <div class="form-group full">
                <button type="submit" class="btn-primary">+ Add Status</button>
            </div>

        </form>
    </div>

    {{-- TABLE CARD --}}
    <div class="card-box" style="margin-top:20px;">

        <div class="card-header">
            <h3>All Status</h3>
        </div>

        <div style="overflow-x:auto;">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Status Name</th>
                        <th>Description</th>
                        <th width="260">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($statuses as $key => $status)
                    <tr>
                        <td>{{ $key+1 }}</td>

                        <td><strong>{{ $status->name }}</strong></td>

                        <td>{{ $status->description }}</td>

                        <td>
                            <div class="action-group">

                                {{-- EDIT --}}
                                <form action="{{ route('jobs.status.update',$status->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    <input type="text" name="name" value="{{ $status->name }}">
                                    <button class="btn-edit">Update</button>
                                </form>

                                {{-- DELETE --}}
                                <form action="{{ route('jobs.status.delete',$status->id) }}" method="POST"
                                      onsubmit="return confirm('Delete this status?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-delete">Delete</button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @if($statuses->count() == 0)
                    <tr>
                        <td colspan="4" class="text-center">No data found</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>

    </div>

</main>

{{-- ================= STYLE ================= --}}
<style>

/* CARD */
.card-box {
    background:#fff;
    border-radius:10px;
    padding:20px;
    box-shadow:0 3px 10px rgba(0,0,0,0.08);
}

/* HEADER */
.card-header {
    margin-bottom:15px;
    border-bottom:1px solid #eee;
    padding-bottom:8px;
}
.card-header h3 {
    font-size:15px;
    font-weight:700;
    color:#1E4BA6;
}

/* FORM GRID */
.form-grid {
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(250px,1fr));
    gap:15px;
}

/* INPUT GROUP */
.form-group label {
    display:block;
    font-size:12px;
    font-weight:600;
    margin-bottom:4px;
    color:#555;
}

.form-group input,
.form-group textarea {
    width:100%;
    padding:8px 10px;
    border:1px solid #ccc;
    border-radius:5px;
    font-size:12px;
    font-weight:600;
    transition:0.3s;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color:#1E4BA6;
    outline:none;
}

/* FULL WIDTH */
.form-group.full {
    grid-column:1/-1;
}

/* TABLE */
.modern-table {
    width:100%;
    border-collapse:collapse;
    font-size:12px;
}

.modern-table th {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
    text-align:left;
}

.modern-table td {
    padding:10px;
    border-bottom:1px solid #eee;
    font-weight:600;
}

/* ACTION */
.action-group {
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}

.inline-form input {
    padding:5px;
    font-size:11px;
    border:1px solid #ccc;
    border-radius:4px;
}

/* BUTTONS */
.btn-primary {
    background:#1E4BA6;
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:5px;
    font-size:12px;
    font-weight:600;
    cursor:pointer;
}

.btn-edit {
    background:#F4C542;
    border:none;
    padding:6px 10px;
    font-size:11px;
    font-weight:600;
    border-radius:4px;
    cursor:pointer;
}

.btn-delete {
    background:#e74c3c;
    color:#fff;
    border:none;
    padding:6px 10px;
    font-size:11px;
    font-weight:600;
    border-radius:4px;
    cursor:pointer;
}

.text-center {
    text-align:center;
    color:#777;
}

/* ALERT */
.alert {
    padding:10px;
    border-radius:5px;
    font-size:12px;
}

</style>

@endsection