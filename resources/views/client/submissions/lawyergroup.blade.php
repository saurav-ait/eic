@extends('admin-master')

@section('content')

<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Lawyers Group Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- GRID --}}
    <div class="grid">

        <div class="card">

            <div class="card-header">
                <h3>Lawyers Groups</h3>
                <p>Create and manage lawyers groups</p>
            </div>

            {{-- FORM --}}
            <form action="{{ route('lawyergroup.store') }}" method="POST" class="form">
                @csrf

                <select name="lawyer_id" required>
                    <option value="">Select Lawyer</option>
                    @foreach($lawyers as $lawyer)
                        <option value="{{ $lawyer->id }}">{{ $lawyer->name }}</option>
                    @endforeach
                </select>

                <input type="text" name="name" placeholder="Lawyer Group Name" required>

                <textarea name="description" placeholder="Description (optional)"></textarea>

                <button class="btn primary">Add Lawyer Group</button>
            </form>

            {{-- TABLE --}}
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Lawyer</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="100">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groups as $index => $group)
                        <tr>
                            <td>{{ $index + 1 }}</td>

                            <td>
                                <span class="tag">{{ $group->lawyer?->name ?? '—' }}</span>
                            </td>

                            <td>
                                <strong>{{ $group->name }}</strong>
                            </td>

                            <td>
                                <span class="desc">
                                    {{ $group->description ?? '—' }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('lawyergroup.destroy', $group->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete lawyer group?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="empty">No lawyers groups found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</main>

{{-- ================= STYLES ================= --}}
<style>

/* GRID */
.grid {
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

/* CARD */
.card {
    background:#fff;
    border-radius:12px;
    padding:20px;
    box-shadow:0 6px 16px rgba(0,0,0,0.05);
}

/* HEADER */
.card-header h3 {
    color:#1E4BA6;
    margin-bottom:5px;
}

.card-header p {
    font-size:13px;
    color:#777;
    margin-bottom:15px;
}

/* FORM */
.form {
    display:flex;
    flex-direction:column;
    gap:10px;
    margin-bottom:15px;
}

/* INPUT */
input, textarea, select {
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:13px;
}

textarea {
    min-height:70px;
    resize:vertical;
}

input:focus, textarea:focus, select:focus {
    border-color:#1E4BA6;
    outline:none;
}

/* BUTTON */
.btn {
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-weight:600;
}

.btn.primary {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
}

.btn.primary:hover {
    background:#163a80;
}

.btn.danger {
    background:#e74c3c;
    color:#fff;
}

.btn.danger:hover {
    background:#c0392b;
}

.btn.sm {
    padding:5px 8px;
    font-size:11px;
}

/* TABLE */
.table-wrapper {
    max-height:300px;
    overflow:auto;
}

table {
    width:100%;
    border-collapse:collapse;
}

th {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
    font-size:12px;
    text-align:left;
}

td {
    padding:10px;
    border-bottom:1px solid #eee;
    font-size:12px;
}

/* TEXT */
.desc {
    color:#555;
    font-size:12px;
    line-height:1.4;
}

.tag {
    background:#e3f2fd;
    padding:4px 8px;
    border-radius:20px;
    font-size:11px;
}

/* EMPTY */
.empty {
    text-align:center;
    color:#999;
    padding:15px;
}

/* ALERT */
.alert-success {
    background:#d1fae5;
    color:#065f46;
    padding:12px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
}

/* RESPONSIVE */
@media(max-width:900px){
    .grid {
        grid-template-columns:1fr;
    }
}

</style>

@endsection