@extends('admin-master')

@section('content')
<main class="main-content">
    <div class="top-bar" style="justify-content: space-between; display: flex; align-items: center; flex-wrap: wrap; gap:10px;">
        <div class="top-bar-title">
            <h1>Passport Details</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>

        <!-- ACTION BUTTONS -->
        <div style="display:flex; gap:10px;">
            <a href="{{ route('documents.create', $passport) }}" class="btn-primary">Add Document</a>
            <a href="{{ route('videos.create', $passport) }}" class="btn-primary">Add Video</a>
        </div>
    </div>

    <!-- PASSPORT INFO -->
    <div class="content-section" style="margin-top:20px;">
        <h3>{{ $passport->full_name }}</h3>
        <p><strong>Passport Number:</strong> {{ $passport->passport_number }}</p>
        <p><strong>Date of Birth:</strong> {{ $passport->date_of_birth }}</p>
        <p><strong>Place of Birth:</strong> {{ $passport->place_of_birth }}</p>
        <p><strong>Issue Date:</strong> {{ $passport->issue_date }}</p>
        <p><strong>Expiry Date:</strong> {{ $passport->expiry_date }}</p>
        <p><strong>Nationality:</strong> {{ $passport->nationality }}</p>
        <p><strong>Gender:</strong> {{ $passport->gender }}</p>
        <p><strong>Address:</strong> {{ $passport->address }}</p>
        <p><strong>Contact:</strong> {{ $passport->phone }}</p>
    </div>

    <!-- DOCUMENT LIST -->
    <div class="content-section" style="margin-top:30px;">
        <h3>Documents</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>File</th>
                    <th style="text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($passport->documents as $doc)
                    <tr>
                        <td>{{ $doc->type }}</td>
                        <td>
                            <a href="{{ asset('public/storage/'.$doc->file) }}" target="_blank">View</a>
                        </td>
                        <td style="text-align:center;">
                            <a href="{{ asset('public/storage/'.$doc->file) }}" download class="btn-primary btn-sm">Download</a>

                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;">No documents</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- VIDEO LIST -->
    <div class="content-section" style="margin-top:30px;">
        <h3>Videos</h3>
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px,1fr)); gap:20px;">
            @forelse($passport->videos as $video)
                <div style="border:1px solid #ccc; padding:10px; border-radius:6px; background:#fff; position:relative;">
                    @if($video->file)
                        <!-- FILE VIDEO -->
                        <video width="100%" height="220" controls style="border-radius:6px;">
                            <source src="{{ asset('public/storage/'.$video->file) }}" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                        <a href="{{ asset('public/storage/'.$video->file) }}" download class="btn-primary btn-full" style="display:inline-block; margin-top:10px; width:100%; text-align:center;">
                            Download
                        </a>
                    @elseif($video->url)
                        <!-- YOUTUBE VIDEO -->
                        @php
                            preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $video->url, $match);
                            $embed = isset($match[1]) ? 'https://www.youtube.com/embed/'.$match[1] : null;
                        @endphp

                        @if($embed)
                            <iframe width="100%" height="220" src="{{ $embed }}" frameborder="0" allowfullscreen style="border-radius:6px;"></iframe>
                        @else
                            <p style="color:red; text-align:center;">Invalid YouTube link</p>
                        @endif
                    @endif

                    <!-- DELETE BUTTON -->
                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST" style="margin-top:5px;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-danger" style="width:100%;">Delete</button>
                    </form>
                </div>
            @empty
                <p style="text-align:center; grid-column:1/-1;">No videos available</p>
            @endforelse
        </div>
    </div>

</main>

{{-- Styles --}}
<style>
.main-content {
    padding: 20px;
}

/* Buttons */
.btn-primary {
    background-color: #1E4BA6;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 12px;
    font-size: 13px;
}
.btn-primary:hover { background-color: #163A7A; }

.btn-primary.btn-sm { padding: 5px 10px; font-size:12px; }

.btn-primary.btn-full { padding: 8px 0; }

.btn-danger {
    background-color: #E74C3C;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 12px;
    font-size: 13px;
}
.btn-danger:hover { background-color: #C0392B; }

.btn-danger.btn-sm { padding:5px 10px; font-size:12px; }

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.table th, .table td {
    border: 1px solid #ddd;
    padding: 10px;
}

.table th {
    background: #1E4BA6;
    color: #fff;
    font-weight: 600;
    text-align: left;
}

/* Responsive */
@media(max-width:768px){
    .top-bar { flex-direction: column; align-items: flex-start; gap:10px; }
    .table th, .table td { font-size: 12px; padding:6px; }
    .btn-primary, .btn-danger { font-size:12px; padding:6px 10px; }
}
</style>
@endsection