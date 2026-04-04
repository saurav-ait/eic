@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Upload Video</h1>
            <p>Passport: {{ $passport->passport_number }}</p>
        </div>

        <a href="{{ route('passports.show', $passport) }}" class="btn-primary">← Back</a>
    </div>

    {{-- ERROR --}}
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
    <div class="card">

        <form action="{{ route('videos.store', $passport) }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- FILE UPLOAD --}}
            <div class="form-group">
                <label>Upload Video File</label>
                <input type="file" name="video" id="videoInput" accept="video/*" class="input-field">
                <small class="hint">Supported: MP4, MOV, AVI</small>
            </div>

            {{-- OR DIVIDER --}}
            <div class="divider">
                <span>OR</span>
            </div>

            {{-- YOUTUBE URL --}}
            <div class="form-group">
                <label>YouTube URL</label>
                <input type="url" name="url" id="youtubeInput"
                    placeholder="https://www.youtube.com/watch?v=XXXX"
                    class="input-field">
            </div>

            {{-- PREVIEW --}}
            <div class="preview-box">
                <video id="videoPreview" controls></video>
                <iframe id="youtubePreview" frameborder="0" allowfullscreen></iframe>
                <div id="noPreview" class="muted">No preview available</div>
            </div>

            {{-- SUBMIT --}}
            <div class="form-actions">
                <button class="btn-primary btn-lg">Save Video</button>
            </div>

        </form>

    </div>

</main>

{{-- ================= JS ================= --}}
<script>
const videoInput = document.getElementById('videoInput');
const youtubeInput = document.getElementById('youtubeInput');

const videoPreview = document.getElementById('videoPreview');
const youtubePreview = document.getElementById('youtubePreview');
const noPreview = document.getElementById('noPreview');

// RESET FUNCTION
function resetPreview() {
    videoPreview.style.display = 'none';
    youtubePreview.style.display = 'none';
    noPreview.style.display = 'none';
}

// FILE VIDEO PREVIEW
videoInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;

    const url = URL.createObjectURL(file);

    resetPreview();
    videoPreview.src = url;
    videoPreview.style.display = 'block';

    // clear URL field
    youtubeInput.value = '';
});

// YOUTUBE PREVIEW
youtubeInput.addEventListener('input', function() {
    const url = this.value;

    const match = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/);
    const embed = match ? "https://www.youtube.com/embed/" + match[1] : null;

    resetPreview();

    if (embed) {
        youtubePreview.src = embed;
        youtubePreview.style.display = 'block';

        // clear file input
        videoInput.value = '';
    } else if (url.length > 5) {
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

/* DIVIDER */
.divider {
    text-align:center;
    margin:15px 0;
    position:relative;
}

.divider span {
    background:#fff;
    padding:0 10px;
    color:#888;
    font-size:13px;
}

.divider::before {
    content:'';
    position:absolute;
    width:100%;
    height:1px;
    background:#ddd;
    top:50%;
    left:0;
    z-index:-1;
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

.preview-box video,
.preview-box iframe {
    width:100%;
    height:250px;
    border-radius:6px;
    display:none;
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