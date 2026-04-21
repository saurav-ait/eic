@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Accounts Dashboard</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="header-actions" style="flex-wrap: wrap; gap: 8px; align-items: center;">
            <a href="{{ route('accounts.vendors') }}" class="btn primary" style="text-decoration: none;">Vendors</a>
            <a href="{{ route('accounts.report') }}" class="btn primary" style="text-decoration: none;">Reports</a>
            <a href="{{ route('accounts.export.excel') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn primary" style="text-decoration: none;">Excel</a>
            <a href="{{ route('accounts.export.pdf') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}" class="btn primary" style="text-decoration: none;">PDF</a>
            <form method="POST" action="{{ route('accounts.import') }}" enctype="multipart/form-data" style="display:inline-flex; gap:8px; align-items:center; margin:0;">
                @csrf
                <label class="btn secondary" for="importDocument" style="margin:0;">Import Excel</label>
                <input id="importDocument" type="file" name="document" accept=".xlsx,.xls,.csv" style="display:none;" onchange="this.form.submit()">
            </form>
            <a href="{{ asset('examples/accounts-import-example.csv') }}" class="btn secondary" style="text-decoration: none;">Download Import Template</a>
            <button id="openModal" class="btn primary">+ Add Entry</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert success" style="margin-bottom: 20px;">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert error" style="margin-bottom: 20px;">{{ session('error') }}</div>
    @endif

    <!-- STATS -->
    <div class="stats">
        <div class="stat-card income">
            <span>Total Income</span>
            <h2>{{ number_format($income,2) }}</h2>
        </div>
        <div class="stat-card expense">
            <span>Total Expense</span>
            <h2>{{ number_format($expense,2) }}</h2>
        </div>
        <div class="stat-card balance">
            <span>Balance</span>
            <h2>{{ number_format($balance,2) }}</h2>
        </div>
    </div>
    @if(request()->filled('search') || request()->filled('vendor') || request()->filled('country') || request()->filled('entry_type'))
        <div class="filter-summary" style="margin-bottom: 20px; color: #4b5563;">
            Showing totals for the current filtered results.
        </div>
    @endif

    <!-- SEARCH & FILTERS -->
    <div class="card">
        <form method="GET" action="{{ route('accounts.index') }}" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search" value="{{ request('search') }}"
                           placeholder="Search vendor, purpose, details, country..." class="form-input">
                </div>

                <div class="filter-group">
                    <label for="vendor">Vendor</label>
                    <select id="vendor" name="vendor" class="form-input">
                        <option value="">All Vendors</option>
                        @foreach($activeVendors as $vendor)
                        <option value="{{ $vendor->name }}" {{ request('vendor') == $vendor->name ? 'selected' : '' }}>
                            {{ $vendor->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="country">Country</label>
                    <select id="country" name="country" class="form-input">
                        <option value="">All Countries</option>
                        @foreach($activeCountries as $country)
                        <option value="{{ $country->name }}" {{ request('country') == $country->name ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-group">
                    <label for="entry_type">Entry Type</label>
                    <select id="entry_type" name="entry_type" class="form-input">
                        <option value="">All Types</option>
                        @foreach($entryTypes as $type)
                        <option value="{{ $type }}" {{ request('entry_type') == $type ? 'selected' : '' }}>
                            {{ $type }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn primary">Filter</button>
                    <a href="{{ route('accounts.index') }}" class="btn secondary">Clear</a>
                </div>
            </div>
        </form>
    </div>

    <!-- TABLE -->
    <div class="card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Entry Type</th>
                    <th>Vendor</th>
                    <th>Vendor Type</th>
                    <th>Amount</th>
                    <th>Balance</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @foreach($accounts as $acc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $acc->date }}</td>
                    <td><span class="pill">{{ $acc->entry_type }}</span></td>
                    <td>{{ $acc->vendor_name }}</td>
                    <td>{{ $acc->vendor_type }}</td>
                    <td class="amount">৳{{ number_format($acc->amount,2) }}</td>
                    <td class="amount">৳{{ number_format($acc->balance,2) }}</td>
                    <td>
                        <span class="status-badge {{ strtolower($acc->last_status ?? 'pending') }}">
                            {{ $acc->last_status ?? 'Pending' }}
                        </span>
                    </td>
                    <td class="action-cell">
                        <button class="icon-btn view" onclick='viewEntry(@json($acc))' title="View Details">👁️</button>
                        <button class="icon-btn edit" onclick='editEntry({{ $acc->id }}, @json($acc))' title="Edit">✏️</button>

                        <form method="POST" action="{{ route('accounts.destroy',$acc->id) }}">
                            @csrf @method('DELETE')
                            <button class="icon-btn delete" title="Delete">🗑</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination-wrapper">
            {{ $accounts->links() }}
        </div>
    </div>

</main>

<!-- VIEW DETAIL MODAL -->
<div id="viewModal" class="modal">
    <div class="modal-box" style="width:700px;max-height:90vh;overflow-y:auto;">
        <div class="modal-header">
            <h3 id="viewTitle">Account Details</h3>
            <button onclick="closeViewModal()">✖</button>
        </div>

        <div class="detail-view">
            <div class="detail-section">
                <div class="detail-item">
                    <label>Date</label>
                    <span id="viewDate">-</span>
                </div>
                <div class="detail-item">
                    <label>Entry Type</label>
                    <span id="viewEntryType">-</span>
                </div>
                <div class="detail-item">
                    <label>Vendor Type</label>
                    <span id="viewVendorType">-</span>
                </div>
                <div class="detail-item">
                    <label>Vendor Name</label>
                    <span id="viewVendorName">-</span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-item">
                    <label>Amount</label>
                    <span id="viewAmount" class="amount">৳0.00</span>
                </div>
                <div class="detail-item">
                    <label>Balance</label>
                    <span id="viewBalance" class="amount">৳0.00</span>
                </div>
                <div class="detail-item">
                    <label>Status</label>
                    <span id="viewStatus" class="status-badge">Pending</span>
                </div>
                <div class="detail-item">
                    <label>Country</label>
                    <span id="viewCountry">-</span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-item full">
                    <label>Purpose</label>
                    <span id="viewPurpose">-</span>
                </div>
                <div class="detail-item full">
                    <label>Details</label>
                    <span id="viewDetails" style="white-space:pre-wrap;">-</span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-item full">
                    <label>Document</label>
                    <span id="viewDocument">-</span>
                </div>
            </div>
        </div>

        <div class="modal-actions" style="margin-top:20px;border-top:1px solid #eee;padding-top:15px;">
            <button type="button" class="btn outline" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

</main>

<!-- MODAL -->
<div id="entryModal" class="modal">
    <div class="modal-box">

        <div class="modal-header">
            <h3 id="modalTitle">Add Entry</h3>
            <button onclick="closeModal()">✖</button>
        </div>

        <form id="entryForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <input type="hidden" name="entryId" id="entryId">

            <div class="form-grid">

                <div class="form-group">
                    <label>Date</label>
                    <input type="date" name="date" id="formDate" required>
                </div>

                <div class="form-group">
                    <label>Entry Type</label>
                    <select name="entry_type" id="formEntryType" required>
                        <option value="">Select</option>
                        <option>Received</option>
                        <option>Receivable</option>
                        <option>Payment</option>
                        <option>Payable</option>
                        <option>Purchase</option>
                        <option>Salary</option>
                        <option>Office costs</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Vendor Type</label>
                    <select name="vendor_type" id="formVendorType">
                        <option value="">Select</option>
                        <option>Client</option>
                        <option>Agent</option>
                        <option>Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Vendor Name</label>
                    <select name="vendor_name" id="formVendorName">
                        <option value="">Select Vendor</option>
                        @foreach($activeVendors as $vendor)
                            <option value="{{ $vendor->name }}" data-type="{{ $vendor->type }}">{{ $vendor->name }} ({{ $vendor->type }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" name="amount" id="formAmount" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Country</label>
                    <select name="country" id="formCountry">
                        <option value="">Select Country</option>
                        @foreach($activeCountries as $country)
                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label>Purpose</label>
                    <select name="purpose" id="formPurpose">
                        <option value="">Select</option>
                        <option>Advance File Opening</option>
                        <option>Receive After Permit</option>
                        <option>Receive during Processing</option>
                        <option>Receive After Visa</option>
                        <option>Return Against Received</option>
                        <option>Others</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Details</label>
                    <textarea name="details" id="formDetails"></textarea>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="last_status" id="formStatus">
                        <option value="">No Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Processing">Processing</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Document</label>
                    <input type="file" name="document" id="formDocument">
                </div>

            </div>

            <div class="modal-actions">
                <button type="submit" class="btn primary">Save</button>
                <button type="button" class="btn outline" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
const modal = document.getElementById('entryModal');
const viewModal = document.getElementById('viewModal');
const form = document.getElementById('entryForm');

document.getElementById('openModal').addEventListener('click', function() {
    modal.style.display = 'flex';
    form.reset();
    form.action = "{{ route('accounts.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('modalTitle').innerText = 'Add Entry';
    document.getElementById('entryId').value = '';
    filterVendorsByType(); // Reset vendor filter
});

// Add vendor type change listener for filtering
document.getElementById('formVendorType').addEventListener('change', function() {
    filterVendorsByType();
});

function filterVendorsByType() {
    const selectedType = document.getElementById('formVendorType').value;
    const vendorSelect = document.getElementById('formVendorName');
    const vendorOptions = vendorSelect.querySelectorAll('option');

    // Reset vendor selection
    vendorSelect.value = '';

    // Show/hide vendor options based on selected type
    vendorOptions.forEach(option => {
        if (option.value === '') {
            // Always show the "Select Vendor" option
            option.style.display = 'block';
        } else {
            const vendorType = option.getAttribute('data-type');
            if (!selectedType || vendorType === selectedType) {
                option.style.display = 'block';
            } else {
                option.style.display = 'none';
            }
        }
    });
}

function closeModal() {
    modal.style.display = 'none';
    form.reset();
}

function closeViewModal() {
    viewModal.style.display = 'none';
}

function viewEntry(data) {
    viewModal.style.display = 'flex';
    
    document.getElementById('viewDate').textContent = data.date || '-';
    document.getElementById('viewEntryType').textContent = data.entry_type || '-';
    document.getElementById('viewVendorType').textContent = data.vendor_type || '-';
    document.getElementById('viewVendorName').textContent = data.vendor_name || '-';
    document.getElementById('viewAmount').textContent = '৳' + parseFloat(data.amount || 0).toFixed(2);
    document.getElementById('viewBalance').textContent = '৳' + parseFloat(data.balance || 0).toFixed(2);
    document.getElementById('viewCountry').textContent = data.country || '-';
    document.getElementById('viewPurpose').textContent = data.purpose || '-';
    document.getElementById('viewDetails').textContent = data.details || '-';
    document.getElementById('viewStatus').textContent = data.last_status || 'Pending';
    
    const statusBadge = document.getElementById('viewStatus');
    statusBadge.className = 'status-badge ' + (data.last_status ? data.last_status.toLowerCase() : 'pending');
    
    if (data.document) {
        document.getElementById('viewDocument').innerHTML = '<a href="{{ asset("storage") }}/' + data.document + '" target="_blank" class="btn primary" style="text-decoration:none;padding:6px 12px;font-size:12px;">Download Document</a>';
    } else {
        document.getElementById('viewDocument').textContent = 'No document';
    }
}

function editEntry(id, data) {
    modal.style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Edit Entry #' + id;
    document.getElementById('entryId').value = id;
    document.getElementById('formMethod').value = 'PUT';
    form.action = "{{ route('accounts.update', ':id') }}".replace(':id', id);
    
    if (data.date) document.getElementById('formDate').value = data.date;
    if (data.entry_type) document.getElementById('formEntryType').value = data.entry_type;
    if (data.vendor_type) document.getElementById('formVendorType').value = data.vendor_type;
    if (data.vendor_name) document.getElementById('formVendorName').value = data.vendor_name;
    if (data.amount) document.getElementById('formAmount').value = data.amount;
    if (data.country) document.getElementById('formCountry').value = data.country;
    if (data.purpose) document.getElementById('formPurpose').value = data.purpose;
    if (data.details) document.getElementById('formDetails').value = data.details;
    if (data.last_status) document.getElementById('formStatus').value = data.last_status;
    
    // Filter vendors after setting the vendor type
    setTimeout(() => filterVendorsByType(), 10);
}

window.onclick = function(e) {
    if (e.target == modal) closeModal();
    if (e.target == viewModal) closeViewModal();
}

form.addEventListener('submit', function(e) {
    e.preventDefault();
    this.submit();
});
</script>

<style>
body{
    background:#f1f5f9;
    font-family: 'Inter', system-ui, -apple-system;
    color:#1e293b;
}

/* HEADER */
.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}
.header h1{
    font-size:24px;
    font-weight:600;
}
.header p{
    color:#64748b;
    font-size:14px;
}

/* ACTION BUTTONS */
.header-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn{
    padding:10px 14px;
    border-radius:8px;
    font-size:13px;
    font-weight:500;
    transition:all .2s ease;
}

.btn.primary{
    background:#2563eb;
    color:#fff;
}
.btn.primary:hover{
    background:#1d4ed8;
}

.btn.secondary{
    background:#e2e8f0;
    color:#334155;
}
.btn.secondary:hover{
    background:#cbd5f5;
}

.btn.outline{
    border:1px solid #cbd5e1;
    background:#fff;
}

/* ALERT */
.alert{
    padding:12px 16px;
    border-radius:8px;
    font-size:14px;
}
.alert.success{background:#dcfce7;color:#166534}
.alert.error{background:#fee2e2;color:#991b1b}

/* STATS */
.stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:25px;
}
.stat-card{
    padding:22px;
    border-radius:16px;
    color:#fff;
    box-shadow:0 8px 20px rgba(0,0,0,0.05);
}
.stat-card span{
    font-size:13px;
    opacity:.9;
}
.stat-card h2{
    font-size:26px;
    margin-top:5px;
}
.stat-card.income{background:linear-gradient(135deg,#16a34a,#22c55e)}
.stat-card.expense{background:linear-gradient(135deg,#dc2626,#ef4444)}
.stat-card.balance{background:linear-gradient(135deg,#2563eb,#3b82f6)}

/* CARD */
.card{
    background:#fff;
    border-radius:14px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 6px 18px rgba(0,0,0,0.04);
}

/* FILTER */
.filter-row{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
}
.filter-group{
    flex:1;
    min-width:200px;
}
.filter-group label{
    font-size:12px;
    font-weight:600;
    color:#475569;
}
.form-input{
    width:100%;
    padding:10px;
    border-radius:8px;
    border:1px solid #e2e8f0;
}
.form-input:focus{
    border-color:#2563eb;
    box-shadow:0 0 0 2px rgba(37,99,235,0.15);
}

/* TABLE */
.modern-table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
}
.modern-table th{
    background:#2563eb;
    color:#fff;
    font-size:13px;
}
.modern-table th,
.modern-table td{
    padding:14px;
}
.modern-table tr{
    background:#fff;
}
.modern-table tbody tr{
    transition:.2s;
}
.modern-table tbody tr:hover{
    background:#f8fafc;
}

/* BADGES */
.pill{
    background:#e0f2fe;
    color:#0369a1;
    padding:4px 10px;
    border-radius:20px;
    font-size:12px;
}

/* STATUS */
.status-badge{
    padding:6px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}
.status-badge.pending{background:#fef3c7;color:#92400e}
.status-badge.approved{background:#dcfce7;color:#166534}
.status-badge.rejected{background:#fee2e2;color:#991b1b}
.status-badge.processing{background:#e0e7ff;color:#3730a3}
.status-badge.completed{background:#dcfce7;color:#166534}

/* ACTION BUTTONS */
.icon-btn{
    border:none;
    padding:6px 9px;
    border-radius:6px;
    cursor:pointer;
    font-size:14px;
    transition:.2s;
}
.icon-btn.view{background:#e0f2fe}
.icon-btn.edit{background:#dbeafe}
.icon-btn.delete{background:#fee2e2}
.icon-btn:hover{transform:scale(1.1)}

/* MODAL */
.modal{
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.5);
    justify-content:center;
    align-items:center;
    z-index:1000;
}

.modal-box{
    background:#fff;
    border-radius:16px;
    padding:25px;
    width:600px;
    max-height:90vh;
    overflow:auto;
    box-shadow:0 20px 40px rgba(0,0,0,0.1);
}

.modal-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:15px;
}

/* FORM */
.form-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
.form-group{
    display:flex;
    flex-direction:column;
}
.form-group.full{
    grid-column:span 2;
}
.form-group label{
    font-size:12px;
    font-weight:600;
}
.form-group input,
.form-group select,
.form-group textarea{
    padding:10px;
    border-radius:8px;
    border:1px solid #e2e8f0;
}

/* DETAIL VIEW */
.detail-view{
    padding:20px;
    background:#f8fafc;
    border-radius:10px;
}
.detail-section{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
    margin-bottom:15px;
}
.detail-item.full{
    grid-column:span 2;
}

/* PAGINATION */
.pagination-wrapper{
    margin-top:15px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .stats{grid-template-columns:1fr}
    .form-grid{grid-template-columns:1fr}
}
</style>

@endsection