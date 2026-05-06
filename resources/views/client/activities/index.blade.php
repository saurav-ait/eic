@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Activity Types Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <button class="btn-primary" onclick="openModal()">+ Add Activity Type</button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Activity Types</h3>
            <p>{{ $activities->total() }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Activity Type Name</th>
                    <th>Associated Leads</th>
                    <th>Created At</th>
                    <th style="width:160px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($activities as $activity)
                <tr>
                    <td>{{ $activities->firstItem() + $loop->index }}</td>

                    <td>
                        <strong>{{ $activity->name }}</strong>
                    </td>

                    <td>
                        <span class="badge">{{ $activity->leads_count }} leads</span>
                    </td>

                    <td>
                        <div class="muted small">{{ $activity->created_at->format('M d, Y') }}</div>
                    </td>

                    <td>
                        <div class="action-row">
                            <button class="btn success btn-xs"
                                onclick='editActivity(
                                    {{ $activity->id }},
                                    @json($activity->name)
                                )'>
                                Edit
                            </button>

                            <form action="{{ route('activities.destroy', $activity->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this activity type?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No activity types found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $activities->links() }}
        </div>
    </div>

</main>

{{-- ================= MODAL ================= --}}
<div id="activityModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add Activity Type</h3>

        <form id="activityForm" method="POST">
            @csrf
            <input type="hidden" id="methodField" name="_method">

            <input type="text" name="name" id="name" placeholder="Activity Type Name" required>

            <div class="modal-actions">
                <button type="submit" class="btn-primary">Save</button>
                <button type="button" onclick="closeModal()" class="btn danger btn-xs">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= JS ================= --}}
<script>
function openModal() {
    document.getElementById('activityModal').style.display = 'flex';
    document.getElementById('activityForm').action = "{{ route('activities.store') }}";
    document.getElementById('methodField').value = '';
    document.getElementById('modalTitle').innerText = "Add Activity Type";
    document.getElementById('activityForm').reset();
}

function closeModal() {
    document.getElementById('activityModal').style.display = 'none';
}

function editActivity(id, name) {
    openModal();

    document.getElementById('modalTitle').innerText = "Edit Activity Type";
    document.getElementById('activityForm').action = "{{ url('admin/activities') }}/" + id;
    document.getElementById('methodField').value = "PUT";

    document.getElementById('name').value = name;
}
</script>

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

.alert.error {
    background:#fee2e2;
    color:#991b1b;
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
    text-align:left;
}

th {
    background:#1E4BA6;
    color:#fff;
}

tr:hover {
    background:#f5f8ff;
}

/* BADGE */
.badge {
    background:#e3f2fd;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    color:#1976d2;
}

/* ACTION */
.action-row {
    display:flex;
    gap:5px;
}

/* BUTTONS */
.btn-xs {
    padding:4px 8px;
    font-size:12px;
    border-radius:4px;
    border:none;
    cursor:pointer;
}

.btn.success { background:#38a169; color:#fff; }
.btn.danger { background:#e53e3e; color:#fff; }

.btn-primary {
    background:#1E4BA6;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
    border:none;
    cursor:pointer;
}

/* MODAL */
.modal {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%;
    height:100%;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:1000;
}

.modal-content {
    background:#fff;
    padding:25px;
    border-radius:10px;
    width:400px;
    max-width:90%;
}

.modal-content input {
    width:100%;
    margin-bottom:10px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.modal-actions {
    display:flex;
    justify-content:space-between;
    margin-top:15px;
}

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
    .modal-content { width:90%; }
}

</style>

@endsection
