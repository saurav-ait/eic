@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Lead Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <button class="btn-primary" onclick="openModal()">+ Add Lead</button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Leads</h3>
            <p>{{ $leads->total() }}</p>
        </div>
        <div class="stat-card">
            <h3>Today</h3>
            <p>{{ $todayLeads }}</p>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="toolbar">
        <form method="GET" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, phone, email...">
            <button class="btn-primary">Search</button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Lead Info</th>
                    <th>Contact</th>
                    <th>Source</th>
                    <th>Interested For</th>
                    <th style="width:160px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leads as $lead)
                <tr>
                    {{-- ✅ Correct pagination index --}}
                    <td>{{ $leads->firstItem() + $loop->index }}</td>

                    <td>
                        <strong>{{ $lead->name }}</strong>
                        <div class="muted small">{{ $lead->created_at->diffForHumans() }}</div>
                    </td>

                    <td>
                        <div>{{ $lead->phone }}</div>
                        <div class="muted small">{{ $lead->email ?? '—' }}</div>
                    </td>

                    <td>
                        <span class="source-badge {{ strtolower($lead->source) }}">
                            {{ $lead->source }}
                        </span>
                    </td>

                    <td>
                        <span class="tag">
                            {{ $lead->service->name ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <div class="action-row">

                            {{-- ✅ SAFE EDIT BUTTON --}}
                            <button class="btn success btn-xs"
                                onclick='editLead(
                                    {{ $lead->id }},
                                    @json($lead->name),
                                    @json($lead->phone),
                                    @json($lead->email),
                                    @json($lead->source),
                                    @json($lead->service_id)
                                )'>
                                Edit
                            </button>

                            {{-- DELETE --}}
                            <form action="{{ route('leads.destroy', $lead->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this lead?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger btn-xs">Delete</button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center">No leads found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $leads->withQueryString()->links() }}
        </div>
    </div>

</main>

{{-- ================= MODAL ================= --}}
<div id="leadModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add Lead</h3>

        <form id="leadForm" method="POST">
            @csrf
            <input type="hidden" id="methodField" name="_method">

            <input type="text" name="name" id="name" placeholder="Full Name" required>
            <input type="text" name="phone" id="phone" placeholder="Phone" required>
            <input type="email" name="email" id="email" placeholder="Email">

            {{-- ✅ FIXED SOURCE SELECT --}}
            <select name="source" id="source" class="input-field" required>
                <option value="">Select Source</option>
                @foreach(['Facebook','Email','WhatsApp','Agent','Management'] as $src)
                    <option value="{{ $src }}">{{ $src }}</option>
                @endforeach
            </select>

            <select name="service_id" id="service_id" class="input-field">
                <option value="">Select Service</option>

                @foreach($services as $service)
                    <option value="{{ $service->id }}">
                        {{ $service->name }}
                    </option>
                @endforeach
            </select>

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
    document.getElementById('leadModal').style.display = 'flex';
    document.getElementById('leadForm').action = "{{ route('leads.store') }}";
    document.getElementById('methodField').value = '';
    document.getElementById('modalTitle').innerText = "Add Lead";

    document.getElementById('leadForm').reset();
    document.getElementById('source').value = '';
    document.getElementById('service_id').value = '';
}

function closeModal() {
    document.getElementById('leadModal').style.display = 'none';
}

function editLead(id, name, phone, email, source) {
    openModal();

    document.getElementById('modalTitle').innerText = "Edit Lead";
    document.getElementById('leadForm').action = "{{ url('admin/leads') }}/" + id;
    document.getElementById('methodField').value = "PUT";

    document.getElementById('name').value = name;
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email;
    document.getElementById('source').value = source;
    document.getElementById('service_id').value = service_id ?? '';
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

/* SOURCE BADGE */
.source-badge {
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.source-badge.facebook { background:#e7f3ff; color:#1877f2; }
.source-badge.email { background:#f3e8ff; color:#7c3aed; }
.source-badge.whatsapp { background:#dcfce7; color:#16a34a; }
.source-badge.agent { background:#fff7ed; color:#ea580c; }
.source-badge.management { background:#f1f5f9; color:#334155; }

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