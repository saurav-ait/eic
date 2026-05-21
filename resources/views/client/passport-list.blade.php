@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Passport List</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <a href="{{ route('passports.create') }}" class="btn-primary">
            + Add Passport
        </a>
    </div>

    {{-- FLASH --}}
    @if(session('success'))
        <div class="toast-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="toast-error">{{ session('error') }}</div>
    @endif

    {{-- STATS --}}
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Passports: {{ $agents->count() }}</h3>
        </div>
    </div>

    {{-- TOOLBAR --}}
    <div class="toolbar">
        <form method="GET" class="search-box">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search passport no / name">
            <select name="agent" onchange="this.form.submit()" style="margin-bottom:0; padding:8px 10px; border:1px solid #ccc; border-radius:6px;">
                <option value="">All Agents</option>
                @foreach($agents as $agent)
                    <option value="{{ $agent->id }}" {{ request('agent') == $agent->id ? 'selected' : '' }}>
                        {{ $agent->name }}
                    </option>
                @endforeach
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
                    <th>Passport Info</th>
                    <th style="width:200px;">Agent</th>
                    <th style="width:200px;">Country</th>
                    <th>Status</th>
                    <th style="width:200px;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($passports as $passport)
                <tr>

                    {{-- SERIAL --}}
                    <td>{{ $loop->iteration }}</td>

                    {{-- INFO --}}
                    <td>
                        <strong>{{ $passport->passport_number }}</strong>
                        <div class="muted">
                            {{ $passport->familyname }} {{ $passport->givenname }}
                        </div>
                    </td>

                    {{-- STATUS --}}
                    <td>
                        @if($passport->job_subcategory_id)
                            <span class="badge assigned">Assigned</span>
                        @else
                            <span class="badge free">Available</span>
                        @endif
                    </td>

                    {{-- AGENT --}}
                    <td>
                        @if($passport->agent)
                            {{ $passport->agent->name }}
                        @else
                            <span class="muted">No agent assigned</span>
                        @endif
                    </td>

                    {{-- COUNTRY --}}
                    <td>
                        @if($passport->country)
                            {{ $passport->country->name }}
                        @else
                            <span class="muted">No country assigned</span>
                        @endif
                    </td>

                    {{-- ACTION --}}
                    <td>
                        <div class="action-row">

                            <a href="{{ route('passports.show', $passport->id) }}"
                               class="btn btn-primary btn-xs" style="text-decoration: none;">
                               View
                            </a>

                            <form action="{{ route('passports.destroy', $passport->id) }}"
                                  method="POST"
                                  class="delete-passport-form"
                                  onsubmit="return confirm('Delete this passport?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn danger btn-xs"
                                    @if($passport->job_subcategory_id)
                                        disabled title="Assigned passports cannot be deleted"
                                    @endif>
                                    Delete
                                </button>
                                <button class="btn btn-primary btn-xs passport-edit-button" type="button"
                                    data-action="{{ route('passports.update', $passport->id) }}"
                                    data-agent-id="{{ $passport->agent_id }}"
                                    data-country-id="{{ $passport->country_id }}">
                                    Edit
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center">
                        No passports found
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION --}}
        <div class="pagination">
            {{ $passports->withQueryString()->links() }}
        </div>
    </div>
    <!-- Edit Modal -->
    <div id="editPassport" class="modal" style="display:none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Passport</h2>
            <form id="editPassportForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="agent_id">Agent</label>
                    <select name="agent_id" id="agent_id">
                        <option value="">Select Agent</option>
                        @foreach($allagents as $agent)
                            <option value="{{ $agent->id }}">{{ $agent->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="country_id">Country</label>
                    <select name="country_id" id="country_id">
                        <option value="">Select Country</option>
                        @foreach(App\Models\Country::orderBy('name')->get() as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-xs">Save Changes</button>
            </form>
        </div>
    </div>
</main>


{{-- ================= STYLES ================= --}}
<style>

/* HEADER */
.top-bar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
    flex-wrap:wrap;
    gap:10px;
}

/* TOOLBAR */
.toolbar {
    margin-bottom:15px;
}

.search-box {
    display:flex;
    gap:10px;
}

.search-box input {
    padding:8px 10px;
    border:1px solid #ccc;
    border-radius:6px;
}

/* TABLE CARD */
.table-card {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

/* TABLE */
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
    text-align:left;
}

tr:hover {
    background:#f5f8ff;
}

/* BADGES */
.badge {
    padding:5px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.badge.assigned {
    background:#fee2e2;
    color:#b91c1c;
}

.badge.free {
    background:#dcfce7;
    color:#166534;
}

/* ACTION */
.action-row {
    display:flex;
    gap:6px;
    flex-wrap:wrap;
}

.modal {
    display:none;
    position:fixed;
    z-index:9999;
    left:0;
    top:0;
    width:100%;
    height:100%;
    overflow:auto;
    background:rgba(0,0,0,0.45);
}

.modal-content {
    background:#fff;
    margin:80px auto;
    padding:24px;
    border-radius:12px;
    width:calc(100% - 40px);
    max-width:520px;
    box-shadow:0 12px 30px rgba(0,0,0,0.18);
    position:relative;
}

.close {
    position:absolute;
    right:16px;
    top:16px;
    font-size:24px;
    cursor:pointer;
    color:#555;
}

.form-group {
    margin-bottom:16px;
}

.form-group label {
    display:block;
    margin-bottom:6px;
    font-weight:600;
}

.form-group select {
    width:100%;
    padding:10px 12px;
    border:1px solid #ccc;
    border-radius:8px;
    background:#fff;
}

/* BUTTONS */
.btn {
    border:none;
    cursor:pointer;
}

.btn-xs {
    padding:5px 8px;
    font-size:12px;
    border-radius:4px;
}

.btn.success { background:#38a169; color:#fff; }
.btn.success:hover { background:#2f855a; }

.btn.danger { background:#e53e3e; color:#fff; }
.btn.danger:hover { background:#c53030; }

.btn-primary {
    background:#1E4BA6;
    color:#fff;
    padding:8px 14px;
    border-radius:6px;
}

.btn-primary:hover {
    background:#163A7A;
}

/* TEXT */
.muted {
    font-size:12px;
    color:#777;
}

.text-center {
    text-align:center;
}

/* TOAST */
.toast-success {
    background:#d1fae5;
    padding:10px;
    border-radius:6px;
    color:#065f46;
    margin-bottom:15px;
}

.toast-error {
    background:#fed7d7;
    padding:10px;
    border-radius:6px;
    color:#c53030;
    margin-bottom:15px;
}

/* PAGINATION */
.pagination {
    margin-top:15px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .top-bar {
        flex-direction:column;
        align-items:flex-start;
    }

    th, td {
        font-size:12px;
        padding:8px;
    }

    .btn-primary {
        font-size:12px;
        padding:6px 10px;
    }
}

</style>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const agentSelect = document.querySelector('select[name="agent"]');
        const searchInput = document.querySelector('.search-box input[name="search"]');
        const deleteForms = document.querySelectorAll('.delete-passport-form');
        const editPassportModal = document.getElementById('editPassport');
        const editPassportForm = document.getElementById('editPassportForm');
        const agentField = document.getElementById('agent_id');
        const countryField = document.getElementById('country_id');
        const closeModal = editPassportModal ? editPassportModal.querySelector('.close') : null;

        if (agentSelect) {
            agentSelect.addEventListener('change', function () {
                this.form.submit();
            });
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        }

        deleteForms.forEach(form => {
            form.addEventListener('submit', function (e) {
                if (!confirm('Delete this passport?')) {
                    e.preventDefault();
                }
            });
        });

        document.querySelectorAll('.passport-edit-button').forEach(button => {
            button.addEventListener('click', function () {
                if (!editPassportModal || !editPassportForm) return;

                editPassportForm.action = this.dataset.action || editPassportForm.action;
                if (agentField) {
                    agentField.value = this.dataset.agentId || '';
                }
                if (countryField) {
                    countryField.value = this.dataset.countryId || '';
                }

                editPassportModal.style.display = 'block';
            });
        });

        if (closeModal) {
            closeModal.addEventListener('click', function () {
                editPassportModal.style.display = 'none';
            });
        }

        window.addEventListener('click', function (e) {
            if (e.target === editPassportModal) {
                editPassportModal.style.display = 'none';
            }
        });
    });
</script>

@endsection