@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Upload Document</h1>
            <p>Passport: {{ $passport->passport_number }}</p>
        </div>

        <a href="{{ route('passports.show', $passport) }}" class="btn-primary">← Back</a>
    </div>

    {{-- ERROR ALERT --}}
    @if ($errors->any())
        <div class="alert error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM CARD --}}
    <div class="card form-card">

        <form action="{{ route('documents.store', $passport) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- DOCUMENT TYPE --}}
            <div class="form-group">
                <label>Document Type</label>
                <select name="type" required class="input-field">
                    <option value="">Select Type</option>
                    <option value="Photo">Photo</option>
                    <option value="Passport">Passport</option>
                    <option value="National ID">National ID</option>
                    <option value="Birth Certificate">Birth Certificate</option>
                    <option value="Educational Certificate (SSC)">Educational Certificate (SSC)</option>
                    <option value="Educational Certificate (HSC)">Educational Certificate (HSC)</option>
                    <option value="Educational Certificate (Diploma)">Educational Certificate (Diploma)</option>
                    <option value="CV">CV</option>
                    <option value="Work Permit">Work Permit</option>
                    <option value="Police Clearance">Police Clearance</option>
                    <option value="Driving License">Driver's License</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            {{-- FILE INPUT --}}
            <div class="form-group">
                <label>Upload File</label>
                <input type="file" name="file" required class="input-field" id="fileInput">
                <small class="hint">
                    Allowed: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)
                </small>
            </div>

            {{-- PREVIEW --}}
            <div class="preview-box">
                <iframe id="filePreview"></iframe>
                <img id="imagePreview" />
                <div id="noPreview" class="muted">No preview available</div>
            </div>

            {{-- SUBMIT --}}
            <div class="form-actions">
                <button type="submit" class="btn-primary btn-lg">
                    Upload Document
                </button>
            </div>

        </form>

    </div>

</main>

{{-- ================= JS ================= --}}
<script>
const fileInput = document.getElementById('fileInput');
const iframePreview = document.getElementById('filePreview');
const imagePreview = document.getElementById('imagePreview');
const noPreview = document.getElementById('noPreview');

fileInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const url = URL.createObjectURL(file);

    // reset
    iframePreview.style.display = 'none';
    imagePreview.style.display = 'none';
    noPreview.style.display = 'none';

    if (file.type.startsWith('image/')) {
        imagePreview.src = url;
        imagePreview.style.display = 'block';
    } 
    else if (file.type === 'application/pdf') {
        iframePreview.src = url;
        iframePreview.style.display = 'block';
    } 
    else {
        noPreview.style.display = 'block';
    }
});
</script>

{{-- ================= STYLES ================= --}}
<style>

/* LAYOUT */
.main-content {
    padding: 20px;
}

/* TOP BAR */
.top-bar {
    display:flex;
    justify-content: space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:20px;
}

/* CARD */
.card {
    background:#fff;
    border-radius:12px;
    padding:25px;
    max-width:600px;
    margin:auto;
    box-shadow:0 4px 12px rgba(0,0,0,0.06);
}

/* FORM */
.form-group {
    margin-bottom:18px;
}

label {
    font-weight:600;
    display:block;
    margin-bottom:6px;
}

/* INPUT */
.input-field {
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #d1d5db;
    font-size:14px;
    transition:0.2s;
}

.input-field:focus {
    border-color:#1E4BA6;
    box-shadow:0 0 0 2px rgba(30,75,166,0.1);
}

/* HINT */
.hint {
    font-size:12px;
    color:#777;
}

/* PREVIEW */
.preview-box {
    margin-top:15px;
    border:1px dashed #ccc;
    border-radius:8px;
    padding:10px;
    text-align:center;
    min-height:150px;
}

.preview-box iframe {
    width:100%;
    height:250px;
    display:none;
    border-radius:6px;
}

.preview-box img {
    max-width:100%;
    display:none;
    border-radius:6px;
}

#noPreview {
    font-size:13px;
}

/* BUTTON */
.btn-primary {
    background:#1E4BA6;
    color:#fff;
    border:none;
    border-radius:6px;
    padding:10px 16px;
    cursor:pointer;
}

.btn-primary:hover {
    background:#163A7A;
}

.btn-lg {
    width:100%;
    font-size:15px;
}

/* ALERT */
.alert.error {
    background:#fed7d7;
    color:#c53030;
    padding:12px;
    border-radius:6px;
    margin-bottom:20px;
    max-width:600px;
    margin-left:auto;
    margin-right:auto;
}

.alert ul {
    list-style:none;
    padding:0;
    margin:0;
}

/* TEXT */
.muted {
    color:#777;
}

/* ACTION */
.form-actions {
    margin-top:20px;
}

/* RESPONSIVE */
@media(max-width:768px){
    .card {
        padding:15px;
    }
}

</style>

@endsection
