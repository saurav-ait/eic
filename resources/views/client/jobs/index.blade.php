@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Jobs Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="toast-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="layout">

        {{-- ================= LEFT PANEL (ASSIGN) ================= --}}
        <div class="left-panel">

            <div class="panel-card">
                <h3>Assign Passport</h3>

                <form action="{{ route('jobs.assign.passport') }}" method="POST">
                    @csrf

                    <label>Subcategory</label>
                    <select name="subcategory_id" required>
                        <option value="">Select Subcategory</option>
                        @foreach($categories as $category)
                            <optgroup label="{{ $category->name }}">
                                @foreach($category->subcategories as $sub)
                                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>

                    <label>Passport</label>
                    <select name="passport_id" required>
                        <option value="">Select Passport</option>
                        @foreach($passports as $passport)
                            @if(!$passport->job_subcategory_id)
                                <option value="{{ $passport->id }}">
                                    {{ $passport->passport_number }} ({{ $passport->givenname }} {{ $passport->familyname }})
                                </option>
                            @endif
                        @endforeach
                    </select>

                    <button class="btn primary full">Assign</button>
                </form>
            </div>

        </div>

        {{-- ================= RIGHT PANEL ================= --}}
        <div class="right-panel">

            {{-- FILTER --}}
            <div class="toolbar">
                <input type="text" id="search" placeholder="Search passport...">

                <select id="filter">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- MODERN JOB OVERVIEW TABLE --}}
            <div class="table-card">
                <table id="table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Passport</th>
                            <th>Subcategory</th>
                            <th style="width:220px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i=1; @endphp
                        @foreach($categories as $category)
                            @foreach($category->subcategories as $sub)
                                @foreach($sub->passports as $passport)
                                    <tr data-cat="{{ $category->id }}" data-pass="{{ strtolower($passport->passport_number) }}">
                                        {{-- INDEX --}}
                                        <td>{{ $i++ }}</td>

                                        {{-- PASSPORT INFO --}}
                                        <td>
                                            <strong>{{ $passport->passport_number }}</strong>
                                            <div class="muted small">
                                                {{ $passport->givenname }} {{ $passport->familyname }}
                                            </div>
                                        </td>

                                        {{-- SUBCATEGORY & CATEGORY --}}
                                        <td>
                                            <span class="tag">{{ $sub->name }}</span>
                                            <div class="muted small">{{ $category->name }}</div>
                                        </td>

                                        {{-- ACTIONS --}}
                                        <td class="action-cell">
                                            <div class="action-row">
                                                {{-- TRANSFER --}}
                                                <form action="{{ route('jobs.assign.passport') }}" method="POST" class="inline-form">
                                                    @csrf
                                                    <input type="hidden" name="passport_id" value="{{ $passport->id }}">
                                                    <select name="subcategory_id" class="compact-select">
                                                        @foreach($categories as $c)
                                                            <optgroup label="{{ $c->name }}">
                                                                @foreach($c->subcategories as $s)
                                                                    <option value="{{ $s->id }}" {{ $s->id == $sub->id ? 'selected' : '' }}>
                                                                        {{ $s->name }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    <button class="btn success btn-xs" title="Transfer passport">↻</button>
                                                </form>

                                                {{-- UNASSIGN --}}
                                                <form action="{{ route('jobs.unassign.passport', $passport->id) }}" method="POST" 
                                                    onsubmit="return confirm('Unassign this passport?')" class="inline-form">
                                                    @csrf
                                                    <button class="btn danger btn-xs" title="Unassign passport">✕</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- STYLES --}}
            <style>
            .table-card {
                background: #fff;
                border-radius: 10px;
                padding: 20px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.05);
                overflow-x: auto;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 14px;
            }

            th, td {
                padding: 12px;
                border-bottom: 1px solid #eee;
                vertical-align: middle;
            }

            thead th {
                background: #1E4BA6;
                color: white;
                font-weight: 600;
                text-align: left;
            }

            tr:hover {
                background: #f3f6fb;
            }

            .muted {
                font-size: 12px;
                color: #777;
            }

            .small {
                font-size: 12px;
            }

            .tag {
                display: inline-block;
                background: #e3f2fd;
                color: #1E4BA6;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
            }

            .action-cell {
                min-width: 220px;
            }

            .action-row {
                display: flex;
                gap: 6px;
                flex-wrap: wrap;
                align-items: center;
            }

            .inline-form {
                display: flex;
                gap: 4px;
                align-items: center;
            }

            .compact-select {
                padding: 5px 8px;
                font-size: 12px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }

            .btn-xs {
                padding: 4px 8px;
                font-size: 12px;
                border-radius: 4px;
                cursor: pointer;
                border: none;
            }

            .btn.success { background: #38a169; color: white; }
            .btn.success:hover { background: #2f855a; }

            .btn.danger { background: #e53e3e; color: white; }
            .btn.danger:hover { background: #c53030; }

            /* Responsive */
            @media(max-width:768px){
                .action-cell {
                    min-width: 100%;
                }
                table th, table td {
                    font-size: 12px;
                    padding: 8px;
                }
            }
            </style>

        </div>

    </div>

</main>

{{-- JS --}}
<script>
document.getElementById('search').addEventListener('keyup', function(){
    let val = this.value.toLowerCase();

    document.querySelectorAll('#table tbody tr').forEach(row=>{
        let p = row.dataset.pass;
        row.style.display = p.includes(val) ? '' : 'none';
    });
});

document.getElementById('filter').addEventListener('change', function(){
    let val = this.value;

    document.querySelectorAll('#table tbody tr').forEach(row=>{
        row.style.display = (!val || row.dataset.cat === val) ? '' : 'none';
    });
});
</script>

{{-- STYLES --}}
<style>

/* LAYOUT */
.layout {
    display: grid;
    grid-template-columns: 320px 1fr;
    gap: 20px;
}

/* LEFT PANEL */
.panel-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    position: sticky;
    top: 20px;
}

/* RIGHT PANEL */
.table-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
}

/* HEADER */
.page-header h1 {
    color: #1E4BA6;
}

/* FORM */
label {
    font-size: 13px;
    font-weight: 600;
    margin-top: 10px;
    display: block;
}

select, input {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* BUTTONS */
.btn {
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.btn.primary { background:#1E4BA6; color:#fff; padding:10px; }
.btn.success { background:#38a169; color:#fff; }
.btn.danger { background:#e53e3e; color:#fff; }

.small {
    padding: 5px 8px;
    font-size: 12px;
}

.full {
    width: 100%;
    margin-top: 15px;
}

/* TABLE IMPROVEMENT */
table {
    table-layout: fixed;
    width: 100%;
}

/* ACTION COLUMN */
.action-cell {
/*    min-width: 180px; */
    width: 200px;
    vertical-align: top;
}

/* ACTION CELL */
.action-cell {
    min-width: 220px;
}

/* ROW LAYOUT */
.action-row {
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}

/* INLINE FORM */
.inline-form {
    display: flex;
    align-items: center;
    gap: 4px;
}

/* COMPACT SELECT */
.compact-select {
    max-width: 150px;
    padding: 5px;
    font-size: 11px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

/* BUTTONS */
.btn-xs {
    padding: 4px 6px;
    font-size: 11px;
    border-radius: 4px;
    cursor: pointer;
}

/* COLORS */
.btn.success {
    background: #38a169;
    color: white;
    border: none;
}

.btn.danger {
    background: #e53e3e;
    color: white;
    border: none;
}

/* HOVER */
.btn.success:hover { background:#2f855a; }
.btn.danger:hover { background:#c53030; }

/* STACKED ACTIONS */
.action-cell form {
    margin-bottom: 6px;
}

/* TRANSFER BOX */
.transfer-box {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

/* SELECT */
.transfer-box select {
    width: 100%;
    padding: 5px;
    font-size: 12px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

/* BUTTONS */
.btn.full {
    width: 100%;
}

.btn.small {
    padding: 6px;
    font-size: 12px;
}

/* BETTER ROW ALIGNMENT */
td {
    vertical-align: middle;
}

/* MOBILE FIX */
@media(max-width:768px){
    .action-cell {
        width: 100%;
    }
}

/* TAG */
.tag {
    background: #e3f2fd;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 12px;
}

/* TEXT */
.muted {
    font-size: 12px;
    color: #777;
}

.small-select {
    padding: 5px;
}

/* ACTION */
.action-group {
    display: flex;
    gap: 5px;
    align-items: center;
}

/* TOOLBAR */
.toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

/* TOAST */
.toast-success {
    background: #d1fae5;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
}

/* MOBILE */
@media(max-width:900px){
    .layout {
        grid-template-columns: 1fr;
    }
}

</style>

@endsection