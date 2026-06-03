@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Lawyers Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <button class="btn-primary" onclick="openModal()">+ Add Lawyer</button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Lawyers</h3>
            <p>{{ $lawyers->count() }}</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lawyer Name</th>
                    <th style="text-align:center;">Groups</th>
                    <th>Status</th>
                    <th style="width:160px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($lawyers as $lawyer)
                <tr>
                    {{-- ✅ Correct pagination index --}}
                    <td>{{ $lawyers->firstItem() + $loop->index }}</td>

                    <td>
                        <strong>{{ $lawyer->name }}</strong>
                        <div class="muted small">{{ $lawyer->created_at->diffForHumans() }}</div>
                    </td>

                    <td style="text-align:center;">
                        <span class="badge">{{ $lawyer->lawyergroup->count() }}</span>
                    </td>

                    <td>
                        @if($lawyer->status)
                            <span class="tag">Active</span>
                        @else
                            <span class="tag" style="background:#f8d7da; color:#721c24;">Inactive</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-row">

                            {{-- ✅ SAFE EDIT BUTTON --}}
                            <button class="btn success btn-xs"
                                onclick='editLawyer(
                                    {{ $lawyer->id }},
                                    @json($lawyer->name),
                                    @json($lawyer->phone),
                                    @json($lawyer->email),
                                    @json($lawyer->address),
                                    @json($lawyer->status)
                                )'>
                                Edit
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('lawyer.destroy', $lawyer->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this lawyer?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger btn-xs">Delete</button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No lawyers found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $lawyers->withQueryString()->links() }}
        </div>
    </div>

</main>

{{-- ================= MODAL ================= --}}
<div id="lawyerModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add Lawyer</h3>

        <form id="lawyerForm" method="POST" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" id="methodField" name="_method">

            <input type="text" name="name" id="name" placeholder="Lawyer Name" required maxlength="255">

            <input type="text" name="phone" id="phone" placeholder="Phone Number" required maxlength="255">

            <input type="email" name="email" id="email" placeholder="Email Address" required maxlength="255">

            <textarea name="address" id="address" placeholder="Address" required maxlength="255"></textarea>

            <div class="input-field">
                <label>
                    <input type="checkbox" name="status" id="status" value="1">
                    Active
                </label>
            </div>

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
    document.getElementById('lawyerModal').style.display = 'flex';
    document.getElementById('lawyerForm').action = "{{ route('lawyer.store') }}";
    document.getElementById('methodField').value = '';
    document.getElementById('modalTitle').innerText = "Add Lawyer";
    document.getElementById('lawyerForm').reset();
}

function closeModal() {
    document.getElementById('lawyerModal').style.display = 'none';
}

function validateForm() {
    const name = document.getElementById('name').value.trim();
    const phone = document.getElementById('phone').value.trim();
    const email = document.getElementById('email').value.trim();
    const address = document.getElementById('address').value.trim();

    if (!name) {
        alert('Lawyer name is required');
        return false;
    }

    if (!email) {
        alert('Email address is required');
        return false;
    }

    return true;
}

function editLawyer(id, name, phone, email, address, status) {
    openModal();

    document.getElementById('modalTitle').innerText = "Edit Lawyer";
    const updateUrl = "{{ route('lawyer.update', ['lawyer' => '__id__']) }}".replace('__id__', id);
    document.getElementById('lawyerForm').action = updateUrl;
    document.getElementById('methodField').value = "PUT";

    document.getElementById('name').value = name;
    document.getElementById('phone').value = phone || '';
    document.getElementById('email').value = email || '';
    document.getElementById('address').value = address || '';
    document.getElementById('status').checked = status == 1;

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

.badge { background:#1E4BA6; color:white; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }

.tag {
    background:#e3f2fd;
    padding:4px 8px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

/* HEADER */
.header {
    background:#1E4BA6;
    color:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

/* SOURCE BADGE */
.source-badge {
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
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
}

.modal-content {
    background:#fff;
    padding:25px;
    border-radius:10px;
    width:400px;
}

.modal-content input,
.input-field {
    width:100%;
    margin-bottom:10px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
}

.modal-actions {
    display:flex;
    justify-content:space-between;
}

/* SEARCH */
.search-box {
    display:flex;
    gap:10px;
    margin-bottom:15px;
}

.search-box input {
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* TEXT */
.muted {
    color:#777;
    font-size:12px;
}

.tag {
    background:#e3f2fd;
    padding:4px 8px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

/* RESPONSIVE */
@media(max-width:768px){
    .modal-content { width:90%; }
}

</style>

@endsection