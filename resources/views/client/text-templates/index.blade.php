@extends('admin-master')

@section('content')
<main class="main-content">

    <div class="top-bar">
        <div class="top-bar-title">
            <h1>SMS/WhatsApp Templates</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <button class="btn-primary" onclick="openModal()">+ Add Template</button>
    </div>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Activity Type</th>
                    <th>Message Preview</th>
                    <th>Characters</th>
                    <th style="width:160px;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($templates as $template)
                <tr>
                    <td>{{ $templates->firstItem() + $loop->index }}</td>
                    <td><span class="tag">{{ $template->activityType->name ?? '—' }}</span></td>
                    <td><div class="text-truncate">{{ Str::limit($template->body, 60) }}</div></td>
                    <td><span class="badge">{{ strlen($template->body) }} chars</span></td>
                    <td>
                        <div class="action-row">
                            <button class="btn success btn-xs"
                                onclick='editTemplate({{ $template->id }}, {{ $template->activity_type_id }}, @json($template->body))'>
                                Edit
                            </button>
                            <form action="{{ route('text-templates.destroy', $template->id) }}" method="POST"
                                  onsubmit="return confirm('Delete?')">
                                @csrf @method('DELETE')
                                <button class="btn danger btn-xs">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No templates found</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="pagination">{{ $templates->links() }}</div>
    </div>

</main>

<div id="templateModal" class="modal">
    <div class="modal-content" style="width:700px;">
        <h3 id="modalTitle">Add Text Template</h3>
        
        <div class="placeholder-info">
            <strong>Available Placeholders:</strong>
            <div class="placeholder-grid">
                <span class="placeholder-tag">{company_name}</span>
                <span class="placeholder-tag">{director}</span>
                <span class="placeholder-tag">{phone}</span>
                <span class="placeholder-tag">{email}</span>
                <span class="placeholder-tag">{city}</span>
                <span class="placeholder-tag">{address}</span>
                <span class="placeholder-tag">{country}</span>
                <span class="placeholder-tag">{activity_type}</span>
                <span class="placeholder-tag">{status}</span>
                <span class="placeholder-tag">{date}</span>
                <span class="placeholder-tag">{time}</span>
            </div>
        </div>

        <form id="templateForm" method="POST">
            @csrf
            <input type="hidden" id="methodField" name="_method">

            <select name="activity_type_id" id="activity_type_id" class="input-field" required>
                <option value="">Select Activity Type</option>
                @foreach($activities as $activity)
                    <option value="{{ $activity->id }}">{{ $activity->name }}</option>
                @endforeach
            </select>

            <textarea name="body" id="body" placeholder="Message Body - Use placeholders like {company_name}, {director}, etc. (Max 1000 characters)" rows="8" maxlength="1000" required></textarea>
            <div class="char-count">
                <span id="charCount">0</span> / 1000 characters
            </div>

            <div class="modal-actions">
                <button type="submit" class="btn-primary">Save</button>
                <button type="button" onclick="closeModal()" class="btn danger btn-xs">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('templateModal').style.display = 'flex';
    document.getElementById('templateForm').action = "{{ route('text-templates.store') }}";
    document.getElementById('methodField').value = '';
    document.getElementById('modalTitle').innerText = "Add Text Template";
    document.getElementById('templateForm').reset();
    updateCharCount();
}

function closeModal() {
    document.getElementById('templateModal').style.display = 'none';
}

function editTemplate(id, activity_type_id, body) {
    openModal();
    document.getElementById('modalTitle').innerText = "Edit Text Template";
    document.getElementById('templateForm').action = "{{ url('admin/text-templates') }}/" + id;
    document.getElementById('methodField').value = "PUT";
    document.getElementById('activity_type_id').value = activity_type_id;
    document.getElementById('body').value = body;
    updateCharCount();
}

function updateCharCount() {
    const body = document.getElementById('body');
    const charCount = document.getElementById('charCount');
    charCount.textContent = body.value.length;
}

document.getElementById('body').addEventListener('input', updateCharCount);
</script>

<style>
.alert.success { background:#d1fae5; color:#065f46; padding:10px; border-radius:6px; margin-bottom:15px; text-align:center; }
.table-card { background:#fff; padding:20px; border-radius:10px; box-shadow:0 4px 10px rgba(0,0,0,0.05); }
table { width:100%; border-collapse:collapse; }
th, td { padding:12px; border-bottom:1px solid #eee; text-align:left; }
th { background:#1E4BA6; color:#fff; }
tr:hover { background:#f5f8ff; }
.tag { background:#e3f2fd; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; color:#1976d2; }
.badge { background:#fef3c7; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; color:#92400e; }
.action-row { display:flex; gap:5px; }
.btn-xs { padding:4px 8px; font-size:12px; border-radius:4px; border:none; cursor:pointer; }
.btn.success { background:#38a169; color:#fff; }
.btn.danger { background:#e53e3e; color:#fff; }
.btn-primary { background:#1E4BA6; color:#fff; padding:8px 14px; border-radius:6px; border:none; cursor:pointer; }
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; z-index:1000; }
.modal-content { background:#fff; padding:25px; border-radius:10px; max-width:90%; max-height:90vh; overflow-y:auto; }
.modal-content input, .modal-content textarea, .input-field { width:100%; margin-bottom:10px; padding:10px; border:1px solid #ccc; border-radius:6px; font-family:inherit; }
.modal-actions { display:flex; justify-content:space-between; margin-top:15px; }
.text-center { text-align:center; }
.text-truncate { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; max-width:300px; }
.char-count { text-align:right; font-size:12px; color:#777; margin-top:-5px; margin-bottom:10px; }
.placeholder-info { background:#f0f9ff; padding:15px; border-radius:8px; margin-bottom:15px; border-left:4px solid #1E4BA6; }
.placeholder-info strong { color:#1E4BA6; display:block; margin-bottom:8px; }
.placeholder-grid { display:flex; flex-wrap:wrap; gap:6px; }
.placeholder-tag { background:#fff; color:#1E4BA6; padding:4px 8px; border-radius:4px; font-size:11px; font-family:monospace; border:1px solid #bfdbfe; cursor:pointer; }
.placeholder-tag:hover { background:#1E4BA6; color:#fff; }
</style>

@endsection
