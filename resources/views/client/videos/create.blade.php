@extends('admin-master')

@section('content')
<main class="main-content">

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Upload Video</h1>
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

        <form action="{{ route('videos.store', $passport) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div style="display:grid; gap:20px;">

                {{-- Upload File --}}
                <div>
                    <label>Upload Video File</label>
                    <input type="file" name="video" accept="video/*"
                        style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                </div>

                <div style="text-align:center; font-weight:600;">OR</div>

                {{-- YouTube URL --}}
                <div>
                    <label>YouTube URL</label>
                    <input type="url" name="url"
                        placeholder="https://www.youtube.com/watch?v=XXXX"
                        style="width:100%; padding:10px; border:1px solid #ccc; border-radius:6px;">
                </div>

            </div>

            <div style="text-align:center; margin-top:20px;">
                <button class="btn-primary">Save Video</button>
            </div>
        </form>

    </div>

</main>

<!-- Preview Script -->
<script>
    const input = document.querySelector('input[name="file"]');
    const preview = document.getElementById('videoPreview');

    input.addEventListener('change', function(e) {
        const file = e.target.files[0];

        if (file) {
            const url = URL.createObjectURL(file);
            preview.src = url;
            preview.style.display = 'block';
        }
    });
</script>
@endsection