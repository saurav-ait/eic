@extends('admin-master')

@section('content')
<main class="main-content">
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Passport Details</h1>
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
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#1E4BA6; color:white;">
                    <th style="padding:10px;">Type</th>
                    <th style="padding:10px;">File</th>
                    <th style="padding:10px; text-align:center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($passport->documents as $doc)
                    <tr>
                        <td style="padding:10px;">{{ $doc->type }}</td>
                        <td style="padding:10px;">
                            <a href="{{ asset('public/storage/'.$doc->file) }}" target="_blank">View</a>
                        </td>
                        <td style="padding:10px; text-align:center;">
                            <a href="{{ asset('public/storage/'.$doc->file) }}" download class="btn-primary" style="margin-right:5px; padding:5px 10px;">Download</a>

                            <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background:red; color:white; border:none; padding:5px 10px;">Delete</button>
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
                        <a href="{{ asset('public/storage/'.$video->file) }}" download
                           class="btn-primary"
                           style="display:inline-block; margin-top:10px; width:100%; text-align:center; padding:8px 0;">
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
                        <button type="submit" class="btn-primary" style="background:red; width:100%; padding:8px 0; margin-top:5px;">Delete</button>
                    </form>
                </div>
            @empty
                <p style="text-align:center; grid-column:1/-1;">No videos available</p>
            @endforelse
        </div>
    </div>

</main>
@endsection