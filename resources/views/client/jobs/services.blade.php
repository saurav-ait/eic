@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar" style="justify-content: space-between; display:flex; align-items:center;">
        <div class="top-bar-title">
            <h1>Service Management</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div>
            <button id="add-service-btn" class="btn-primary">Add Service</button>
        </div>
    </div>

    {{-- SUCCESS / ERROR MESSAGE --}}
    @if(session('success'))
        <div class="alert alert-success form-status">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-error form-status">{{ session('error') }}</div>
    @endif

    <div class="content-section">

        {{-- ADD SERVICE FORM (TOGGLE) --}}
        <div id="add-service-form" class="panel-card" style="display:none;">
            <h3>Add New Service</h3>
            <form action="{{ route('services.store') }}" method="POST">
                @csrf
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <input type="text" name="name" placeholder="Service Name" required class="input-field">
                    <textarea name="description" placeholder="Service Description" class="input-field" rows="3"></textarea>
                    <button class="btn-primary">Add Service</button>
                </div>
            </form>
        </div>

        {{-- SERVICES TABLE --}}
        <div class="table-card">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th style="width:220px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $index => $service)
                        <tr>
                            <td>{{ $index+1 }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->description ?? '—' }}</td>
                            <td class="action-cell">
                                <div class="action-row">
                                    {{-- EDIT --}}
                                    <a href="{{ route('services.edit', $service->id) }}" class="btn success btn-xs">✎ Edit</a>

                                    {{-- DELETE (PREVENT IF ASSIGNED) --}}
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" 
                                        onsubmit="return confirm('Are you sure you want to delete this service?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn danger btn-xs"
                                        @if($service->categories->count() > 0 || $service->subcategories->count() > 0) 
                                            disabled title="Cannot delete assigned service"
                                        @endif>✕ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top:15px;">
                {{ $services->withQueryString()->links() }}
            </div>
        </div>

    </div>
</main>

{{-- JS TOGGLE ADD FORM --}}
<script>
document.getElementById('add-service-btn').addEventListener('click', function(){
    const form = document.getElementById('add-service-form');
    form.style.display = (form.style.display === 'none') ? 'block' : 'none';
});
</script>

{{-- STYLES --}}
<style>
/* PANEL CARD */
.panel-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* TABLE CARD */
.table-card {
    background: #fff;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    overflow-x: auto;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

th, td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

thead th {
    background: #1E4BA6;
    color: white;
    font-weight: 600;
    text-align: left;
}

tr:hover { background: #f3f6fb; }

/* ACTIONS */
.action-cell { min-width: 220px; }
.action-row { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
.btn-xs { padding: 4px 8px; font-size: 12px; border-radius: 4px; cursor: pointer; border:none; }
.btn.success { background: #38a169; color:white; } .btn.success:hover{ background:#2f855a; }
.btn.danger { background: #e53e3e; color:white; } .btn.danger:hover{ background:#c53030; }
.btn-primary { background-color:#1E4BA6; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; }
.btn-primary:hover { background-color:#163A7A; }

/* INPUT FIELDS */
.input-field {
    padding: 10px;
    border-radius:6px;
    border:1px solid #ccc;
    width:100%;
    font-size:14px;
}

/* ALERTS */
.form-status {
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    text-align:center;
}
.alert-success { background:#d1fae5; color:#065f46; }
.alert-error { background:#fed7d7; color:#c53030; }

/* RESPONSIVE */
@media(max-width:768px){
    .action-cell { min-width:100%; }
}
</style>
@endsection