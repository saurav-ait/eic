@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Passport Details</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <div class="action-buttons">
            <a href="{{ route('documents.create', $passport) }}" class="btn-primary">+ Document</a>
            <a href="{{ route('videos.create', $passport) }}" class="btn-primary">+ Video</a>
        </div>
    </div>

    {{-- PASSPORT INFO --}}
    <div class="card info-card">
        <h2>{{ $passport->full_name }}</h2>

        <div class="info-grid">
            <div><strong>Passport No:</strong> {{ $passport->passport_number }}</div>
            <div><strong>Date of Birth:</strong> {{ $passport->date_of_birth }}</div>
            <div><strong>Place of Birth:</strong> {{ $passport->place_of_birth }}</div>
            <div><strong>Issue Date:</strong> {{ $passport->issue_date }}</div>
            <div><strong>Expiry Date:</strong> {{ $passport->expiry_date }}</div>
            <div><strong>Nationality:</strong> {{ $passport->nationality }}</div>
            <div><strong>Gender:</strong> {{ $passport->gender }}</div>
            <div><strong>Phone:</strong> {{ $passport->phone }}</div>
        </div>

        <div class="full-width">
            <strong>Address:</strong>
            <p class="muted">{{ $passport->address }}</p>
        </div>
    </div>

    {{-- DOCUMENTS --}}
    <div class="card">
        <div class="card-header">
            <h3>Documents</h3>
        </div>

        <div class="table-wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>File</th>
                        <th style="text-align:center;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($passport->documents as $doc)
                        <tr>
                            <td>{{ $doc->type }}</td>
                            <td>
                                <a href="{{ asset('public/storage/'.$doc->file) }}" target="_blank" class="link">
                                    View File
                                </a>
                            </td>
                            <td style="text-align:center;">
                                <div class="action-row center">
                                    <a href="{{ asset('public/storage/'.$doc->file) }}" download class="btn-primary btn-sm">
                                        Download
                                    </a>

                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="empty">No documents available</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- VIDEOS --}}
    <div class="card">
        <div class="card-header">
            <h3>Videos</h3>
        </div>

        <div class="video-grid">
            @forelse($passport->videos as $video)
                <div class="video-card">

                    {{-- FILE VIDEO --}}
                    @if($video->file)
                        <video controls>
                            <source src="{{ asset('public/storage/'.$video->file) }}" type="video/mp4">
                        </video>

                        <a href="{{ asset('public/storage/'.$video->file) }}" download class="btn-primary btn-full">
                            Download
                        </a>

                    {{-- YOUTUBE --}}
                    @elseif($video->url)
                        @php
                            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $video->url, $match);
                            $embed = isset($match[1]) ? 'https://www.youtube.com/embed/'.$match[1] : null;
                        @endphp

                        @if($embed)
                            <iframe src="{{ $embed }}" allowfullscreen></iframe>
                        @else
                            <p class="error">Invalid YouTube Link</p>
                        @endif
                    @endif

                    {{-- DELETE --}}
                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn-danger btn-full">Delete</button>
                    </form>
                </div>
            @empty
                <div class="empty full">No videos available</div>
            @endforelse
        </div>
    </div>

</main>

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

/* CARDS */
.card {
    background:#fff;
    border-radius:10px;
    padding:20px;
    margin-bottom:25px;
    box-shadow:0 4px 10px rgba(0,0,0,0.05);
}

.card-header {
    margin-bottom:15px;
}

/* INFO */
.info-card h2 {
    margin-bottom:15px;
    color:#1E4BA6;
}

.info-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap:10px;
    margin-bottom:10px;
}

.full-width {
    margin-top:10px;
}

/* TABLE */
.table {
    width:100%;
    border-collapse:collapse;
}

.table th, .table td {
    padding:10px;
    border-bottom:1px solid #eee;
}

.table th {
    background:#1E4BA6;
    color:#fff;
}

/* VIDEO GRID */
.video-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
    gap:20px;
}

.video-card {
    background:#fff;
    border:1px solid #eee;
    padding:10px;
    border-radius:8px;
}

.video-card video,
.video-card iframe {
    width:100%;
    height:200px;
    border-radius:6px;
}

/* BUTTONS */
.btn-primary {
    background:#1E4BA6;
    color:#fff;
    padding:8px 12px;
    border-radius:6px;
    text-decoration:none;
    border:none;
    cursor:pointer;
}

.btn-primary:hover { background:#163A7A; }

.btn-danger {
    background:#e53e3e;
    color:#fff;
    border:none;
    padding:8px;
    border-radius:6px;
    cursor:pointer;
}

.btn-danger:hover { background:#c53030; }

.btn-sm { font-size:12px; padding:5px 8px; }
.btn-full { width:100%; margin-top:8px; }

/* ACTION */
.action-row {
    display:flex;
    gap:6px;
}

.center { justify-content:center; }

/* TEXT */
.muted { color:#777; }
.link { color:#1E4BA6; text-decoration:none; }
.link:hover { text-decoration:underline; }

.empty {
    text-align:center;
    color:#777;
    padding:15px;
}

.error {
    color:red;
    text-align:center;
}

/* RESPONSIVE */
@media(max-width:768px){
    .top-bar {
        flex-direction:column;
        align-items:flex-start;
    }
}

</style>

@endsection