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
            <a href="{{ route('accounts.vendors') }}" class="btn primary" style="text-decoration:none;">Vendors</a>
            <a href="{{ route('accounts.report') }}" class="btn primary" style="text-decoration:none;">Reports</a>

            {{-- FIX #1: Use route() with query params properly instead of raw query string concatenation --}}
            <a href="{{ route('accounts.export.excel', request()->query()) }}" class="btn primary" style="text-decoration:none;">Excel</a>
            <a href="{{ route('accounts.export.pdf', request()->query()) }}" class="btn primary" style="text-decoration:none;">PDF</a>

            <form method="POST" action="{{ route('accounts.import') }}" enctype="multipart/form-data"
                  style="display:inline-flex; gap:8px; align-items:center; margin:0;">
                @csrf
                <label class="btn secondary" for="importDocument" style="margin:0; cursor:pointer;">Import Excel</label>
                <input id="importDocument" type="file" name="document"
                       accept=".xlsx,.xls,.csv" style="display:none;"
                       onchange="this.form.submit()">
            </form>

            <a href="{{ asset('examples/accounts-import-example.csv') }}" class="btn secondary" style="text-decoration:none;">
                Download Import Template
            </a>

            <button id="openModal" class="btn primary">+ Add Entry</button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert success" style="margin-bottom:20px;">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert error" style="margin-bottom:20px;">{{ session('error') }}</div>
    @endif

    <!-- STATS -->
    <div class="stats">
        <div class="stat-card income">
            <span>Total Income</span>
            <h2>{{ number_format($income, 2) }}</h2>
        </div>
        <div class="stat-card expense">
            <span>Total Expense</span>
            <h2>{{ number_format($expense, 2) }}</h2>
        </div>
        <div class="stat-card balance">
            <span>Balance</span>
            <h2>{{ number_format($balance, 2) }}</h2>
        </div>
    </div>

    @if(request()->filled('search') || request()->filled('vendor') || request()->filled('country') || request()->filled('entry_type'))
        <div class="filter-summary" style="margin-bottom:20px; color:#4b5563;">
            Showing totals for the current filtered results.
        </div>
    @endif

    <!-- SEARCH & FILTERS -->
    <div class="card">
        <form method="GET" action="{{ route('accounts.index') }}" class="filter-form">
            <div class="filter-row">

                <div class="filter-group">
                    <label for="search">Search</label>
                    <input type="text" id="search" name="search"
                           value="{{ request('search') }}"
                           placeholder="Search vendor, purpose, details, country..."
                           class="form-input">
                </div>

                <div class="filter-group">
                    <label for="vendor">Vendor</label>
                    <select id="vendor" name="vendor" class="form-input">
                        <option value="">All Vendors</option>
                        @foreach($activeVendors as $vendor)
                            <option value="{{ $vendor->name }}"
                                {{ request('vendor') == $vendor->name ? 'selected' : '' }}>
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
                            <option value="{{ $country->name }}"
                                {{ request('country') == $country->name ? 'selected' : '' }}>
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
                            <option value="{{ $type }}"
                                {{ request('entry_type') == $type ? 'selected' : '' }}>
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
                    <td>{{ $accounts->firstItem() + $loop->index }}</td>
                    <td>{{ $acc->date }}</td>
                    <td><span class="pill">{{ $acc->entry_type }}</span></td>
                    <td>{{ $acc->vendor_name }}</td>
                    <td>{{ $acc->vendor_type }}</td>
                    <td class="amount">৳{{ number_format($acc->amount, 2) }}</td>
                    <td class="amount">৳{{ number_format($acc->balance, 2) }}</td>
                    <td>
                        <span class="status-badge {{ strtolower($acc->last_status ?? 'pending') }}">
                            {{ $acc->last_status ?? 'Pending' }}
                        </span>
                    </td>
                    <td class="action-cell">
                        {{-- FIX #2: Pass data via data attributes instead of inline @json to avoid XSS --}}
                        <button class="icon-btn view"
                                data-entry='@json($acc)'
                                onclick="viewEntry(this)"
                                title="View Details">👁️</button>

                        <button class="icon-btn edit"
                                data-id="{{ $acc->id }}"
                                data-entry='@json($acc)'
                                onclick="editEntry(this)"
                                title="Edit">✏️</button>

                        {{-- FIX #3: Delete confirmation added --}}
                        <form method="POST"
                              action="{{ route('accounts.destroy', $acc->id) }}"
                              onsubmit="return confirm('Are you sure you want to delete this entry? This cannot be undone.');"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="icon-btn delete" title="Delete">🗑</button>
                        </form>
                    </td>
                </tr>
                @endforeach

                @if($accounts->isEmpty())
                <tr>
                    <td colspan="9" style="text-align:center; color:#94a3b8; padding:30px;">
                        No entries found.
                    </td>
                </tr>
                @endif
            </tbody>
        </table>

        @if ($accounts->hasPages())
            <div class="pagination-wrapper">
        
                {{-- Previous --}}
                @if ($accounts->onFirstPage())
                    <span class="disabled">«</span>
                @else
                    <a href="{{ $accounts->previousPageUrl() }}">«</a>
                @endif
        
                {{-- Page Numbers --}}
                @foreach ($accounts->getUrlRange(1, $accounts->lastPage()) as $page => $url)
                    @if ($page == $accounts->currentPage())
                        <span class="active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
        
                {{-- Next --}}
                @if ($accounts->hasMorePages())
                    <a href="{{ $accounts->nextPageUrl() }}">»</a>
                @else
                    <span class="disabled">»</span>
                @endif
        
            </div>
        @endif
    </div>

</main>
{{-- FIX #4: Removed duplicate </main> tag. Only one </main> now. --}}

<!-- VIEW DETAIL MODAL -->
<div id="viewModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="viewTitle">
    <div class="modal-box" style="width:700px; max-height:90vh; overflow-y:auto;">
        <div class="modal-header">
            <h3 id="viewTitle">Account Details</h3>
            <button onclick="closeViewModal()" aria-label="Close">✖</button>
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
                    <span id="viewStatus" class="status-badge pending">Pending</span>
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
                    {{-- FIX #5: Document link is rendered server-side to avoid innerHTML XSS --}}
                    <span id="viewDocument">-</span>
                    {{-- Hidden anchor template — href is set safely via JS textContent, not innerHTML --}}
                    <a id="viewDocumentLink"
                       href="#"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="btn primary"
                       style="display:none; text-decoration:none; padding:6px 12px; font-size:12px;">
                        Download Document
                    </a>
                </div>
            </div>
        </div>

        <div class="modal-actions" style="margin-top:20px; border-top:1px solid #eee; padding-top:15px;">
            <button type="button" class="btn outline" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<!-- ADD / EDIT MODAL -->
<div id="entryModal" class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle">
    <div class="modal-box">
        <div class="modal-header">
            <h3 id="modalTitle">Add Entry</h3>
            <button onclick="closeModal()" aria-label="Close">✖</button>
        </div>

        <form id="entryForm" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label for="formDate">Date <span style="color:red;">*</span></label>
                    <input type="date" name="date" id="formDate" required>
                </div>

                <div class="form-group">
                    <label for="formEntryType">Entry Type <span style="color:red;">*</span></label>
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
                    <label for="formVendorType">Vendor Type</label>
                    <select name="vendor_type" id="formVendorType">
                        <option value="">Select</option>
                        <option>Client</option>
                        <option>Agent</option>
                        <option>Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="formVendorName">Vendor Name</label>
                    <select name="vendor_name" id="formVendorName">
                        <option value="">Select Vendor</option>
                        @foreach($activeVendors as $vendor)
                            <option value="{{ $vendor->name }}"
                                    data-type="{{ $vendor->type }}">
                                {{ $vendor->name }} ({{ $vendor->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="formAmount">Amount <span style="color:red;">*</span></label>
                    <input type="number" name="amount" id="formAmount" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="formCountry">Country</label>
                    <select name="country" id="formCountry">
                        <option value="">Select Country</option>
                        @foreach($activeCountries as $country)
                            <option value="{{ $country->name }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full">
                    <label for="formPurpose">Purpose</label>
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
                    <label for="formDetails">Details</label>
                    <textarea name="details" id="formDetails" rows="3"></textarea>
                </div>

                <div class="form-group">
                    <label for="formStatus">Status</label>
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
                    <label for="formDocument">Document</label>
                    <input type="file" name="document" id="formDocument"
                           accept=".jpg,.jpeg,.png,.pdf">
                    <small style="color:#94a3b8; font-size:11px;">
                        jpg, jpeg, png, pdf — max 5MB
                    </small>
                </div>

            </div>

            <div class="modal-actions">
                <button type="submit" class="btn primary" id="submitBtn">Save</button>
                <button type="button" class="btn outline" onclick="closeModal()">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
(function () {
    'use strict';

    /* -------------------------------------------------------
     * CONSTANTS
     * ----------------------------------------------------- */
    const modal     = document.getElementById('entryModal');
    const viewModal = document.getElementById('viewModal');
    const form      = document.getElementById('entryForm');

    // FIX #6: Base storage URL from a safe Blade variable, not concatenated inside JS
    const storageBase = @json(rtrim(asset('storage'), '/'));

    /* -------------------------------------------------------
     * OPEN ADD MODAL
     * ----------------------------------------------------- */
    document.getElementById('openModal').addEventListener('click', function () {
        openModal('Add Entry', '{{ route('accounts.store') }}', 'POST');
    });

    function openModal(title, action, method) {
        form.reset();
        form.action = action;
        document.getElementById('formMethod').value = method;
        document.getElementById('modalTitle').textContent = title;
        filterVendorsByType();
        modal.style.display = 'flex';
    }

    /* -------------------------------------------------------
     * CLOSE MODALS
     * ----------------------------------------------------- */
    window.closeModal = function () {
        modal.style.display = 'none';
        form.reset();
    };

    window.closeViewModal = function () {
        viewModal.style.display = 'none';
    };

    /* -------------------------------------------------------
     * VENDOR FILTER
     * ----------------------------------------------------- */
    document.getElementById('formVendorType').addEventListener('change', filterVendorsByType);

    function filterVendorsByType() {
        const selectedType  = document.getElementById('formVendorType').value;
        const vendorSelect  = document.getElementById('formVendorName');
        const vendorOptions = vendorSelect.querySelectorAll('option');

        vendorSelect.value = '';

        vendorOptions.forEach(function (option) {
            if (!option.value) {
                option.hidden = false;
                return;
            }
            const vendorType = option.getAttribute('data-type');
            option.hidden = selectedType && vendorType !== selectedType;
        });
    }

    /* -------------------------------------------------------
     * VIEW ENTRY
     * FIX #7: All values set via textContent (never innerHTML)
     *         Document link built safely without string injection
     * ----------------------------------------------------- */
    window.viewEntry = function (btn) {
        const data = JSON.parse(btn.getAttribute('data-entry'));

        setText('viewDate',       data.date);
        setText('viewEntryType',  data.entry_type);
        setText('viewVendorType', data.vendor_type);
        setText('viewVendorName', data.vendor_name);
        setText('viewCountry',    data.country);
        setText('viewPurpose',    data.purpose);
        setText('viewDetails',    data.details);

        document.getElementById('viewAmount').textContent =
            '৳' + parseFloat(data.amount || 0).toFixed(2);
        document.getElementById('viewBalance').textContent =
            '৳' + parseFloat(data.balance || 0).toFixed(2);

        const statusBadge = document.getElementById('viewStatus');
        const status = data.last_status || 'Pending';
        statusBadge.textContent  = status;
        statusBadge.className    = 'status-badge ' + status.toLowerCase();

        // Document link — href set safely, no innerHTML
        const docSpan = document.getElementById('viewDocument');
        const docLink = document.getElementById('viewDocumentLink');

        if (data.document) {
            docSpan.style.display = 'none';
            docLink.href          = storageBase + '/' + data.document;
            docLink.style.display = 'inline-block';
        } else {
            docSpan.textContent   = 'No document';
            docSpan.style.display = '';
            docLink.style.display = 'none';
        }

        viewModal.style.display = 'flex';
    };

    function setText(id, value) {
        document.getElementById(id).textContent = value || '-';
    }

    /* -------------------------------------------------------
     * EDIT ENTRY
     * ----------------------------------------------------- */
    window.editEntry = function (btn) {
        const id   = btn.getAttribute('data-id');
        const data = JSON.parse(btn.getAttribute('data-entry'));

        const action = '{{ route('accounts.update', ':id') }}'.replace(':id', id);
        openModal('Edit Entry #' + id, action, 'PUT');

        setVal('formDate',       data.date);
        setVal('formEntryType',  data.entry_type);
        setVal('formVendorType', data.vendor_type);
        setVal('formAmount',     data.amount);
        setVal('formCountry',    data.country);
        setVal('formPurpose',    data.purpose);
        setVal('formDetails',    data.details);
        setVal('formStatus',     data.last_status);

        // Set vendor after type filter so options are visible
        setTimeout(function () {
            filterVendorsByType();
            setTimeout(function () {
                setVal('formVendorName', data.vendor_name);
            }, 10);
        }, 10);
    };

    function setVal(id, value) {
        if (value !== null && value !== undefined) {
            document.getElementById(id).value = value;
        }
    }

    /* -------------------------------------------------------
     * CLOSE ON BACKDROP CLICK
     * ----------------------------------------------------- */
    window.addEventListener('click', function (e) {
        if (e.target === modal)     closeModal();
        if (e.target === viewModal) closeViewModal();
    });

    /* -------------------------------------------------------
     * FIX #8: Removed the redundant e.preventDefault() + submit() listener.
     *         The form now submits naturally.
     * ----------------------------------------------------- */

})();
</script>

<style>

body {
    background: #f1f5f9;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    color: #1e293b;
}

/* HEADER */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    flex-wrap: wrap;
    gap: 12px;
}
.header h1 { font-size: 24px; font-weight: 600; }
.header p  { color: #64748b; font-size: 14px; }

/* ACTION BUTTONS */
.header-actions {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    align-items: center;
}

.btn {
    padding: 10px 14px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    transition: all .2s ease;
}
.btn.primary            { background: #2563eb; color: #fff; }
.btn.primary:hover      { background: #1d4ed8; }
.btn.secondary          { background: #e2e8f0; color: #334155; }
.btn.secondary:hover    { background: #cbd5e1; }
.btn.outline            { border: 1px solid #cbd5e1; background: #fff; color: #334155; }
.btn.outline:hover      { background: #f8fafc; }

/* ALERT */
.alert {
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
}
.alert.success { background: #dcfce7; color: #166534; }
.alert.error   { background: #fee2e2; color: #991b1b; }

/* STATS */
.stats {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}
.stat-card {
    padding: 22px;
    border-radius: 16px;
    color: #fff;
    box-shadow: 0 8px 20px rgba(0,0,0,.05);
}
.stat-card span   { font-size: 13px; opacity: .9; }
.stat-card h2     { font-size: 26px; margin-top: 5px; }
.stat-card.income  { background: linear-gradient(135deg, #16a34a, #22c55e); }
.stat-card.expense { background: linear-gradient(135deg, #dc2626, #ef4444); }
.stat-card.balance { background: linear-gradient(135deg, #2563eb, #3b82f6); }

/* CARD */
.card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,.04);
}

/* FILTER */
.filter-row {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: flex-end;
}
.filter-group           { flex: 1; min-width: 180px; }
.filter-group label     { display: block; font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 4px; }
.filter-actions         { display: flex; gap: 8px; align-items: flex-end; }

.form-input {
    width: 100%;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    box-sizing: border-box;
}
.form-input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,.15);
}

/* TABLE */
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 13px;
}
.modern-table th {
    background: #2563eb;
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    text-align: left;
    padding: 14px;
}
.modern-table th:first-child { border-radius: 8px 0 0 0; }
.modern-table th:last-child  { border-radius: 0 8px 0 0; }
.modern-table td { padding: 14px; border-bottom: 1px solid #f1f5f9; }
.modern-table tbody tr { background: #fff; transition: background .15s; }
.modern-table tbody tr:hover { background: #f8fafc; }
.amount { font-weight: 600; font-variant-numeric: tabular-nums; }

/* BADGES */
.pill {
    background: #e0f2fe;
    color: #0369a1;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

/* STATUS */
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}
.status-badge.pending    { background: #fef3c7; color: #92400e; }
.status-badge.approved   { background: #dcfce7; color: #166534; }
.status-badge.rejected   { background: #fee2e2; color: #991b1b; }
.status-badge.processing { background: #e0e7ff; color: #3730a3; }
.status-badge.completed  { background: #d1fae5; color: #065f46; }

/* ICON BUTTONS */
.icon-btn {
    border: none;
    padding: 6px 9px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    transition: transform .15s;
    background: transparent;
}
.icon-btn.view   { background: #e0f2fe; }
.icon-btn.edit   { background: #dbeafe; }
.icon-btn.delete { background: #fee2e2; }
.icon-btn:hover  { transform: scale(1.12); }
.action-cell     { display: flex; gap: 6px; align-items: center; }

/* MODAL OVERLAY */
.modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

/* MODAL BOX */
.modal-box {
    background: #fff;
    border-radius: 16px;
    padding: 25px;
    width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 40px rgba(0,0,0,.12);
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}
.modal-header h3  { font-size: 18px; font-weight: 600; }
.modal-header button {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
    color: #64748b;
    line-height: 1;
}

/* FORM GRID */
.form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
.form-group { display: flex; flex-direction: column; gap: 4px; }
.form-group.full { grid-column: span 2; }
.form-group label { font-size: 12px; font-weight: 600; color: #475569; }

.form-group input,
.form-group select,
.form-group textarea {
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 13px;
    font-family: inherit;
    transition: border-color .15s, box-shadow .15s;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,.15);
}
.form-group textarea { resize: vertical; min-height: 80px; }

/* MODAL ACTIONS */
.modal-actions {
    display: flex;
    gap: 10px;
    justify-content: flex-end;
    margin-top: 20px;
}

/* DETAIL VIEW */
.detail-view {
    padding: 20px;
    background: #f8fafc;
    border-radius: 10px;
}
.detail-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 15px;
}
.detail-item label {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 4px;
}
.detail-item span { font-size: 14px; color: #1e293b; }
.detail-item.full { grid-column: span 2; }

/* PAGINATION */

.pagination-wrapper {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 20px;
}

.pagination-wrapper a,
.pagination-wrapper span {
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    border: 1px solid #ccc;
}

.pagination-wrapper a {
    background: #fff;
    color: #1E4BA6;
}

.pagination-wrapper a:hover {
    background: #F4C542;
    color: #111;
}

.pagination-wrapper .active {
    background: #1E4BA6;
    color: #fff;
    border-color: #1E4BA6;
}

.pagination-wrapper .disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .stats        { grid-template-columns: 1fr; }
    .form-grid    { grid-template-columns: 1fr; }
    .form-group.full { grid-column: span 1; }
    .detail-section  { grid-template-columns: 1fr; }
    .detail-item.full { grid-column: span 1; }
    .modal-box    { width: 95vw; padding: 16px; }
}
</style>

@endsection