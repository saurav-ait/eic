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

    {{-- SUCCESS / ERROR --}}
    @if(session('success'))
        <div class="alert alert-success form-status">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-error form-status">{{ session('error') }}</div>
    @endif

    <div class="content-section">

        {{-- ADD SERVICE FORM --}}
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
                        <th style="width:80px; text-align:center;">Categories</th>
                        <th style="width:180px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($services as $index => $service)
                        <tr>
                            <td>{{ $services->firstItem() + $index }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->description ?? '—' }}</td>
                            <td style="text-align:center;">
                                <span class="badge">{{ $service->categories->count() }}</span>
                            </td>
                            <td class="action-cell">
                                <div class="action-row">
                                    {{-- EDIT BUTTON --}}
                                    <button class="btn success btn-xs" onclick="openEditForm({{ $service->id }}, '{{ addslashes($service->name) }}', '{{ addslashes($service->description) }}')">✎ Edit</button>

                                    {{-- DELETE BUTTON --}}
                                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" 
                                        onsubmit="return confirm('Are you sure you want to delete this service?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn danger btn-xs">✕ Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- PAGINATION --}}
            <div style="margin-top:15px;">
                {{ $services->links() }}
            </div>
        </div>

        {{-- EDIT SERVICE FORM MODAL --}}
        <div id="edit-service-form" class="panel-card" style="display:none; position:fixed; top:20%; left:50%; transform:translateX(-50%); width:400px; z-index:1000;">
            <h3>Edit Service</h3>
            <form id="editServiceForm" method="POST">
                @csrf
                @method('PUT')
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <input type="text" name="name" id="editServiceName" placeholder="Service Name" required class="input-field">
                    <textarea name="description" id="editServiceDescription" placeholder="Service Description" class="input-field" rows="3"></textarea>
                    <div style="display:flex; gap:10px;">
                        <button type="submit" class="btn-success">Update Service</button>
                        <button type="button" class="btn-danger" onclick="closeEditForm()">Cancel</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
</main>

{{-- JS --}}
<script>
function openEditForm(id, name, description) {
    const form = document.getElementById('edit-service-form');
    form.style.display = 'block';

    // Set values safely
    document.getElementById('editServiceName').value = name;
    document.getElementById('editServiceDescription').value = description || '';

    // Build URL using Laravel route helper
    const routeTemplate = "{{ route('services.update', ':id') }}";
    const url = routeTemplate.replace(':id', id);

    document.getElementById('editServiceForm').action = url;
}

function closeEditForm() {
    document.getElementById('edit-service-form').style.display = 'none';
}
</script>

{{-- STYLES --}}
<style>
.panel-card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
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
}
thead th {
    background: #1E4BA6;
    color: white;
    font-weight: 600;
    text-align: left;
}
tr:hover { background: #f3f6fb; }
.action-cell { min-width:180px; }
.action-row { display:flex; gap:6px; flex-wrap:wrap; align-items:center; }
.btn-xs { padding:4px 8px; font-size:12px; border-radius:4px; cursor:pointer; border:none; }
.btn.success { background:#38a169; color:rgb(255, 255, 255); } .btn.success:hover{ background:#2f855a; }
.btn.danger { background:#e53e3e; color:white; } .btn.danger:hover{ background:#c53030; }
.btn-primary { background-color:#1E4BA6; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; }
.btn-primary:hover { background-color:#163A7A; }
.input-field { padding:10px; border-radius:6px; border:1px solid #ccc; width:100%; font-size:14px; }
.form-status { padding:10px; border-radius:6px; margin-bottom:15px; text-align:center; }
.alert-success { background:#d1fae5; color:#065f46; }
.alert-error { background:#fed7d7; color:#c53030; }
.badge { background:#1E4BA6; color:white; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; display:inline-block; }
@media(max-width:768px){ .action-cell { min-width:100%; } }
</style>
@endsection