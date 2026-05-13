@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Lead Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div style="display:flex;gap:10px;">
            <button class="btn-secondary" onclick="document.getElementById('importModal').style.display='flex'">Import</button>
            <a href="{{ route('leads.export') }}" class="btn-secondary">Export</a>
            <button class="btn-primary" onclick="openModal()">+ Add Lead</button>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    @if($errors->any())
        <div class="alert error">
            <ul style="margin:0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Leads</h3>
            <p>{{ $totalLeads }}</p>
        </div>
        <div class="stat-card">
            <h3>Today</h3>
            <p>{{ $todayLeads }}</p>
        </div>
        <div class="stat-card">
            <h3>This Month</h3>
            <p>{{ $monthlyLeads }}</p>
        </div>
    </div>

    {{-- SEARCH & FILTERS --}}
    <div class="toolbar">
        <form method="GET" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search company, phone, email...">
            
            <select name="country" class="filter-select">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>

            <select name="activity" class="filter-select">
                <option value="">All Activities</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}" {{ request('activity') == $activity->id ? 'selected' : '' }}>
                        {{ $activity->name }}
                    </option>
                @endforeach
            </select>

            <select name="status" class="filter-select">
                <option value="">All Status</option>
<<<<<<< HEAD
                @foreach(['New','Contacted','Email Sent','Converted','Lost'] as $st)
=======
                @foreach(['New','Contacted','Converted','Lost'] as $st)
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
                    <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ $st }}</option>
                @endforeach
            </select>

            <button class="btn-primary">Filter</button>
            <a href="{{ route('leads.index') }}" class="btn-secondary">Reset</a>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Company Info</th>
                    <th>Contact</th>
                    <th>Location</th>
                    <th>Activity Type</th>
                    <th>Status</th>
                    <th style="width:220px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($leads as $lead)
                <tr>
                    <td>{{ $leads->firstItem() + $loop->index }}</td>

                    <td>
                        <strong>{{ $lead->company_name }}</strong>
                        @if($lead->director)
                            <div class="muted small">{{ $lead->director }}</div>
                        @endif
                        <div class="muted small">{{ $lead->created_at->diffForHumans() }}</div>
                    </td>

                    <td>
                        <div>{{ $lead->phone }}</div>
                        <div class="muted small">{{ $lead->email ?? '—' }}</div>
                    </td>

                    <td>
                        <div>{{ $lead->city ?? '—' }}</div>
                        <div class="muted small">{{ $lead->country->name ?? '—' }}</div>
                    </td>

                    <td>
                        <span class="tag">
                            {{ $lead->activity->name ?? '—' }}
                        </span>
                    </td>

                    <td>
                        <span class="status-badge {{ strtolower($lead->status) }}">
                            {{ $lead->status }}
                        </span>
                    </td>

                    <td>
                        <div class="action-row">
                            <button class="btn success btn-xs"
                                onclick='editLead(
                                    {{ $lead->id }},
                                    @json($lead->company_name),
                                    @json($lead->director),
                                    @json($lead->phone),
                                    @json($lead->email),
                                    @json($lead->city),
                                    @json($lead->address),
                                    {{ $lead->country_id }},
                                    {{ $lead->activity_type_id }},
                                    @json($lead->status)
                                )'>
                                Edit
                            </button>

                            @if($lead->email)
                            <form action="{{ route('leads.send-email', $lead->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn info btn-xs" title="Send Email">📧</button>
                            </form>
                            @endif

                            @if($lead->phone)
                            <form action="{{ route('leads.send-text', $lead->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn warning btn-xs" title="Send SMS/WhatsApp">💬</button>
                            </form>
                            @endif

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
                    <td colspan="7" class="text-center">No leads found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $leads->withQueryString()->links() }}
        </div>
    </div>

</main>

{{-- ================= ADD/EDIT MODAL ================= --}}
<div id="leadModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add Lead</h3>

        <form id="leadForm" method="POST">
            @csrf
            <input type="hidden" id="methodField" name="_method">

            <input type="text" name="company_name" id="company_name" placeholder="Company Name" required>
            <input type="text" name="director" id="director" placeholder="Director Name">
            <input type="text" name="phone" id="phone" placeholder="Phone" required>
            <input type="email" name="email" id="email" placeholder="Email">
            <input type="text" name="city" id="city" placeholder="City">
            <textarea name="address" id="address" placeholder="Address" rows="2"></textarea>

            <select name="country_id" id="country_id" class="input-field" required>
                <option value="">Select Country</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                @endforeach
            </select>

            <select name="activity_type_id" id="activity_type_id" class="input-field" required>
                <option value="">Select Activity Type</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                @endforeach
            </select>

            <select name="status" id="status" class="input-field">
                <option value="New">New</option>
                <option value="Contacted">Contacted</option>
<<<<<<< HEAD
                <option value="Email Sent">Email Sent</option>
=======
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
                <option value="Converted">Converted</option>
                <option value="Lost">Lost</option>
            </select>

            <div class="modal-actions">
                <button type="submit" class="btn-primary">Save</button>
                <button type="button" onclick="closeModal()" class="btn danger btn-xs">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- ================= IMPORT MODAL ================= --}}
<div id="importModal" class="modal">
    <div class="modal-content">
        <h3>Import Leads</h3>

        <form action="{{ route('leads.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="input-field">
            <p class="muted small">Upload Excel or CSV file with columns: company_name, director, phone, email, city, address, country_id, activity_type_id, status</p>

            <div class="modal-actions">
                <button type="submit" class="btn-primary">Import</button>
                <button type="button" onclick="document.getElementById('importModal').style.display='none'" class="btn danger btn-xs">Cancel</button>
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
}

function closeModal() {
    document.getElementById('leadModal').style.display = 'none';
}

function editLead(id, company_name, director, phone, email, city, address, country_id, activity_type_id, status) {
    openModal();

    document.getElementById('modalTitle').innerText = "Edit Lead";
    document.getElementById('leadForm').action = "{{ url('admin/leads') }}/" + id;
    document.getElementById('methodField').value = "PUT";

    document.getElementById('company_name').value = company_name;
    document.getElementById('director').value = director || '';
    document.getElementById('phone').value = phone;
    document.getElementById('email').value = email || '';
    document.getElementById('city').value = city || '';
    document.getElementById('address').value = address || '';
    document.getElementById('country_id').value = country_id;
    document.getElementById('activity_type_id').value = activity_type_id;
    document.getElementById('status').value = status;
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
}

th {
    background:#1E4BA6;
    color:#fff;
}

tr:hover {
    background:#f5f8ff;
}

/* STATUS BADGE */
.status-badge {
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.status-badge.new { background:#dbeafe; color:#1e40af; }
.status-badge.contacted { background:#fef3c7; color:#92400e; }
<<<<<<< HEAD
.status-badge.email.sent { background:#e0e7ff; color:#4338ca; }
=======
>>>>>>> 01d981d1e63872bc4fbd707b6c33769aea1b5336
.status-badge.converted { background:#d1fae5; color:#065f46; }
.status-badge.lost { background:#fee2e2; color:#991b1b; }

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
.btn.info { background:#3b82f6; color:#fff; }
.btn.warning { background:#f59e0b; color:#fff; }

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
.modal-content textarea,
.input-field {
    width:100%;
    margin-bottom:10px;
    padding:10px;
    border:1px solid #ccc;
    border-radius:6px;
    font-family:inherit;
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
    flex-wrap:wrap;
}

.search-box input {
    flex:1;
    min-width:200px;
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

.filter-select {
    padding:8px;
    border:1px solid #ccc;
    border-radius:6px;
}

.btn-secondary {
    background:#6b7280;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
    border:none;
    cursor:pointer;
    text-decoration:none;
    display:inline-block;
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