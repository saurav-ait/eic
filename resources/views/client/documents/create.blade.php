@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Upload Document</h1>
            <p>Passport: {{ $passport->passport_number }}</p>
        </div>

        <div>
            <a href="{{ route('passports.show', $passport) }}" class="btn-primary">Back</a>
        </div>
    </div>

    <!-- Error Messages -->
    @if ($errors->any())
        <div class="alert alert-danger form-status" style="color:red; text-align:center; margin-bottom:20px;">
            <ul style="list-style:none; padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Upload Form -->
    <div class="content-section" style="padding:30px; max-width:600px; margin:auto;">

        <form action="{{ route('documents.store', $passport) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Document Type -->
            <div style="margin-bottom:20px;">
                <label style="font-weight:600;">Document Type</label>
                <select name="type" required
                    style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    <option value="">Select Type</option>
                    <option value="CV">CV</option>
                    <option value="Work Permit">Work Permit</option>
                    <option value="Police Clearance">Police Clearance</option>
                    <option value="Others">Others</option>
                </select>
            </div>

            <!-- File Upload -->
            <div style="margin-bottom:20px;">
                <label style="font-weight:600;">Upload File</label>
                <input type="file" name="file" required
                    style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                <small style="color:#666;">
                    Allowed: PDF, DOC, DOCX, JPG, PNG (Max: 5MB or as configured)
                </small>
            </div>

            <!-- Preview -->
            <div style="margin-bottom:20px;">
                <iframe id="filePreview" style="width:100%; height:300px; display:none; border:1px solid #ccc; border-radius:8px;"></iframe>
                <img id="imagePreview" style="max-width:100%; display:none; border-radius:8px; margin-top:10px;" />
            </div>

            <!-- Submit -->
            <div style="text-align:center;">
                <button type="submit" class="btn-primary" style="padding:10px 25px;">
                    Upload Document
                </button>
            </div>

        </form>

    </div>

</main>

<!-- Preview Script -->
<script>
    const fileInput = document.querySelector('input[name="file"]');
    const iframePreview = document.getElementById('filePreview');
    const imagePreview = document.getElementById('imagePreview');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (!file) return;

        const url = URL.createObjectURL(file);

        // Reset
        iframePreview.style.display = 'none';
        imagePreview.style.display = 'none';

        // Image preview
        if (file.type.startsWith('image/')) {
            imagePreview.src = url;
            imagePreview.style.display = 'block';
        } 
        // PDF preview
        else if (file.type === 'application/pdf') {
            iframePreview.src = url;
            iframePreview.style.display = 'block';
        }
    });
</script>

@endsection