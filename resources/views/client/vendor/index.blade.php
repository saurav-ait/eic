@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Vendor Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <button class="btn-primary" onclick="openModal()">+ Add Vendor</button>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Vendors</h3>
            <p>{{ $vendors->total() }}</p>
        </div>
        <div class="stat-card">
            <h3>Today's Vendors</h3>
            <p>{{ $todayVendors }}</p>
        </div>
        <div class="stat-card">
            <h3>This Month</h3>
            <p>{{ $monthlyVendors }}</p>
        </div>
    </div>

    {{-- SEARCH --}}
    <div class="toolbar">
        <form method="GET" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search vendors...">
            <select name="type">
                <option value="">All Types</option>
                <option value="Client" {{ request('type') == 'Client' ? 'selected' : '' }}>Client</option>
                <option value="Agent" {{ request('type') == 'Agent' ? 'selected' : '' }}>Agent</option>
                <option value="Others" {{ request('type') == 'Others' ? 'selected' : '' }}>Others</option>
            </select>
            <select name="status">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button class="btn-primary">Search</button>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Vendor</th>
                    <th>Type</th>
                    <th>Contact</th>
                    <th>Status</th>
                    <th style="width:160px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($vendors as $vendor)
                <tr>
                    <td>{{ $vendors->firstItem() + $loop->index }}</td>

                    <td>
                        <strong>{{ $vendor->name }}</strong>
                        <div class="muted small">{{ $vendor->created_at->diffForHumans() }}</div>
                    </td>

                    <td>
                        <span class="tag">{{ $vendor->type }}</span>
                    </td>

                    <td>
                        @if($vendor->contact_person)
                            <div>{{ $vendor->contact_person }}</div>
                        @endif
                        @if($vendor->phone)
                            <div class="muted small">{{ $vendor->phone }}</div>
                        @endif
                        @if($vendor->email)
                            <div class="muted small">{{ $vendor->email }}</div>
                        @endif
                    </td>

                    <td>
                        @if($vendor->status)
                            <span class="tag">Active</span>
                        @else
                            <span class="tag" style="background:#f8d7da; color:#721c24;">Inactive</span>
                        @endif
                    </td>

                    <td>
                        <div class="action-row">
                            <button class="btn success btn-xs"
                                onclick='editVendor(
                                    {{ $vendor->id }},
                                    @json($vendor->name),
                                    @json($vendor->type),
                                    @json($vendor->details),
                                    @json($vendor->contact_person),
                                    @json($vendor->phone),
                                    @json($vendor->email),
                                    @json($vendor->status)
                                )'>
                                Edit
                            </button>

                            <form action="{{ route('vendor.destroy', $vendor->id) }}" method="POST"
                                  onsubmit="return confirm('Delete this vendor?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center">No vendors found</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $vendors->withQueryString()->links() }}
        </div>
    </div>

</main>

{{-- ================= MODAL ================= --}}
<div id="vendorModal" class="modal">
    <div class="modal-content">
        <h3 id="modalTitle">Add Vendor</h3>

        <form id="vendorForm" method="POST" onsubmit="return validateForm()">
            @csrf
            <input type="hidden" id="methodField" name="_method">

            <div class="form-row">
                <div class="form-group">
                    <label>Name *</label>
                    <input type="text" name="name" id="name" placeholder="Vendor Name" required maxlength="255">
                </div>

                <div class="form-group">
                    <label>Type *</label>
                    <select name="type" id="type" required>
                        <option value="">Select Type</option>
                        <option value="Client">Client</option>
                        <option value="Agent">Agent</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" name="contact_person" id="contact_person" placeholder="Contact Person" maxlength="255">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="phone" placeholder="Phone Number" maxlength="20">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" placeholder="Email Address" maxlength="255">
                </div>
            </div>

            <div class="form-group">
                <label>Details</label>
                <textarea name="details" id="details" placeholder="Additional Details" maxlength="1000"></textarea>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="status" id="status" value="1" checked>
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
    document.getElementById('vendorModal').style.display = 'flex';
    document.getElementById('vendorForm').action = "{{ route('vendor.store') }}";
    document.getElementById('methodField').value = '';
    document.getElementById('modalTitle').innerText = "Add Vendor";
    document.getElementById('vendorForm').reset();
    document.getElementById('status').checked = true;
}

function closeModal() {
    document.getElementById('vendorModal').style.display = 'none';
}

function validateForm() {
    const name = document.getElementById('name').value.trim();
    const type = document.getElementById('type').value;

    if (!name) {
        alert('Vendor name is required');
        return false;
    }

    if (!type) {
        alert('Vendor type is required');
        return false;
    }

    return true;
}

function editVendor(id, name, type, details, contact_person, phone, email, status) {
    openModal();

    document.getElementById('modalTitle').innerText = "Edit Vendor";
    document.getElementById('vendorForm').action = "{{ route('vendor.store') }}/" + id;
    document.getElementById('methodField').value = "PUT";

    document.getElementById('name').value = name;
    document.getElementById('type').value = type;
    document.getElementById('details').value = details || '';
    document.getElementById('contact_person').value = contact_person || '';
    document.getElementById('phone').value = phone || '';
    document.getElementById('email').value = email || '';
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

/* FORM STYLES */
.form-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.form-row .form-group {
    flex: 1;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #374151;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 14px;
}

.form-group textarea {
    resize: vertical;
    min-height: 80px;
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
    width:600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-actions {
    display:flex;
    justify-content:space-between;
    margin-top: 20px;
}

/* SEARCH */
.search-box {
    display:flex;
    gap:10px;
    margin-bottom:15px;
    align-items: center;
}

.search-box input,
.search-box select {
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
    .modal-content {
        width:95%;
        padding: 15px;
    }

    .form-row {
        flex-direction: column;
        gap: 0;
    }

    .search-box {
        flex-wrap: wrap;
    }
}
</style>

@endsection