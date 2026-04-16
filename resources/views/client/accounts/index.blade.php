@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- HEADER -->
    <div class="header">
        <div>
            <h1>Accounts Dashboard</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="header-actions">
            <a href="{{ route('accounts.vendors') }}" class="btn primary" style="text-decoration: none;">Vendors</a>
            <a href="{{ route('accounts.report') }}" class="btn primary" style="text-decoration: none;">Reports</a>
            <a href="{{ route('accounts.export.excel') }}" class="btn primary" style="text-decoration: none;">Excel</a>
            <a href="{{ route('accounts.export.pdf') }}" class="btn primary"style="text-decoration: none;">PDF</a>
            <button id="openModal" class="btn primary">+ Add Entry</button>
        </div>
    </div>

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

    <!-- TABLE -->
    <div class="card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Vendor</th>
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
                    <td class="amount">${{ number_format($acc->amount,2) }}</td>
                    <td class="amount">${{ number_format($acc->balance,2) }}</td>
                    <td>
                        <span class="status-badge {{ strtolower($acc->last_status ?? 'pending') }}">
                            {{ $acc->last_status ?? 'Pending' }}
                        </span>
                    </td>
                    <td class="action-cell">
                        <button class="icon-btn edit" onclick='editEntry({{ $acc->id }}, @json($acc))'>✏️</button>

                        <form method="POST" action="{{ route('accounts.destroy',$acc->id) }}">
                            @csrf @method('DELETE')
                            <button class="icon-btn delete">🗑</button>
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
                    <input type="text" name="vendor_name" id="formVendorName">
                </div>

                <div class="form-group">
                    <label>Amount</label>
                    <input type="number" name="amount" id="formAmount" step="0.01" required>
                </div>

                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" id="formCountry">
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
const form = document.getElementById('entryForm');

// Add Entry Modal
document.getElementById('openModal').addEventListener('click', function() {
    modal.style.display = 'flex';
    form.reset();
    form.action = "{{ route('accounts.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('modalTitle').innerText = 'Add Entry';
    document.getElementById('entryId').value = '';
});

function closeModal() {
    modal.style.display = 'none';
    form.reset();
}

function editEntry(id, data) {
    modal.style.display = 'flex';
    document.getElementById('modalTitle').innerText = 'Edit Entry #' + id;
    document.getElementById('entryId').value = id;
    document.getElementById('formMethod').value = 'PUT';
    form.action = "{{ route('accounts.update', ':id') }}".replace(':id', id);
    
    // Populate form fields
    if (data.date) document.getElementById('formDate').value = data.date;
    if (data.entry_type) document.getElementById('formEntryType').value = data.entry_type;
    if (data.vendor_type) document.getElementById('formVendorType').value = data.vendor_type;
    if (data.vendor_name) document.getElementById('formVendorName').value = data.vendor_name;
    if (data.amount) document.getElementById('formAmount').value = data.amount;
    if (data.country) document.getElementById('formCountry').value = data.country;
    if (data.purpose) document.getElementById('formPurpose').value = data.purpose;
    if (data.details) document.getElementById('formDetails').value = data.details;
    if (data.last_status) document.getElementById('formStatus').value = data.last_status;
}

// Close modal on background click
window.onclick = function(e) {
    if (e.target == modal) closeModal();
}

// Handle form submission
form.addEventListener('submit', function(e) {
    e.preventDefault();
    this.submit();
});
</script>

<style>
body{background:#f4f6fb;font-family:system-ui}

.header{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px}

.stats{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:20px}
.stat-card{padding:20px;border-radius:14px;color:#fff;box-shadow:0 4px 12px rgba(0,0,0,0.08)}
.stat-card.income{background:linear-gradient(135deg,#16a34a,#22c55e)}
.stat-card.expense{background:linear-gradient(135deg,#dc2626,#ef4444)}
.stat-card.balance{background:linear-gradient(135deg,#2563eb,#3b82f6)}

.card{background:#fff;bo

.status-badge{display:inline-block;padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600}
.status-badge.pending{background:#fef3c7;color:#92400e}
.status-badge.approved{background:#d1fae5;color:#065f46}
.status-badge.rejected{background:#fee2e2;color:#991b1b}
.status-badge.processing{background:#e0e7ff;color:#3730a3}
.status-badge.completed{background:#d1fae5;color:#065f46}rder-radius:14px;padding:20px;box-shadow:0 4px 12px rgba(0,0,0,0.05)}

.modern-table{width:100%;border-collapse:collapse}
.modern-table th{background:#2563eb;color:#fff}
.modern-table th,.modern-table td{padding:12px;border-bottom:1px solid #eee}

.pill{background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:20px;font-size:12px}
.amount{font-weight:600}

.action-cell{display:flex;gap:6px}
.icon-btn{border:none;padding:6px 10px;border-radius:6px;cursor:pointer}
.icon-btn.edit{background:#e0f2fe}
.icon-btn.delete{background:#fee2e2}

.btn{padding:10px 16px;border:none;border-radius:8px;cursor:pointer;font-weight:500}
.btn.primary{background:#2563eb;color:#fff}
.btn.outline{border:1px solid #ccc;background:#fff}

.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:#0006;justify-content:center;align-items:center}
.modal-box{background:#fff;padding:25px;border-radius:14px;width:600px}

.modal-header{display:flex;justify-content:space-between;margin-bottom:15px}

.form-grid{display:grid;grid-template-columns:1fr 1fr;gap:15px}
.form-group{display:flex;flex-direction:column;font-size:13px}
.form-group.full{grid-column:span 2}
.form-group input,.form-group select,.form-group textarea{
    padding:10px;border:1px solid #ddd;border-radius:8px;margin-top:5px
}

.modal-actions{margin-top:15px;display:flex;gap:10px}

.pagination-wrapper{margin-top:15px}
</style>

@endsection