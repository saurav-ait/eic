@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- ================= TOP HEADER ================= --}}
    <div class="page-header">

        <div>
            <h1 class="page-title">Passport Details</h1>
            <p class="page-subtitle">
                {{ now()->format('l, F j, Y') }}
            </p>
        </div>

        <div class="header-actions no-print">

            <button onclick="printPassportInfo()" class="btn btn-dark">
                Print
            </button>

            <a href="{{ route('documents.create', $passport) }}" class="btn btn-primary">
                + Document
            </a>

            <a href="{{ route('videos.create', $passport) }}" class="btn btn-primary">
                + Video
            </a>

        </div>

    </div>

    {{-- ================= PASSPORT PROFILE ================= --}}
    <div class="passport-card" id="passport-print-area">

        {{-- PROFILE TOP --}}
        <div class="profile-top">

            <div class="profile-left">

                @php
                    $photo = $passport->documents->where('type', 'Photo')->first();
                @endphp
                
                <div class="profile-avatar">
                
                    @if($photo)
                
                        <img src="{{ asset('public/storage/'.$photo->file) }}"
                             alt="{{ $passport->full_name }}">
                
                    @else
                
                        {{ strtoupper(substr($passport->full_name,0,1)) }}
                
                    @endif
                
                </div>

                <div>
                    <h2>{{ $passport->full_name }}</h2>

                    <div class="profile-tags">

                        <span class="tag primary">
                            Passport: {{ $passport->passport_number }}
                        </span>

                        <span class="tag success">
                            {{ $passport->nationality }}
                        </span>

                        <span class="tag dark">
                            {{ $passport->gender }}
                        </span>

                    </div>
                </div>

            </div>

        </div>

        {{-- SECTION --}}
        <div class="section-title">
            Personal Information
        </div>

        <div class="details-grid">

            <div class="detail-box">
                <span>Agent</span>
                <strong>
                    {{ $passport->agent ? $passport->agent->name : 'No agent assigned' }}
                </strong>
            </div>

            <div class="detail-box">
                <span>Interested Country</span>
                <strong>
                    {{ $passport->country ? $passport->country->name : 'No country assigned' }}
                </strong>
            </div>

            <div class="detail-box">
                <span>Family Name</span>
                <strong>{{ $passport->familyname }}</strong>
            </div>

            <div class="detail-box">
                <span>Given Name</span>
                <strong>{{ $passport->givenname }}</strong>
            </div>

            <div class="detail-box">
                <span>Father's Name</span>
                <strong>{{ $passport->father_name }}</strong>
            </div>

            <div class="detail-box">
                <span>Mother's Name</span>
                <strong>{{ $passport->mother_name }}</strong>
            </div>

            <div class="detail-box">
                <span>Date of Birth</span>
                <strong>{{ $passport->date_of_birth }}</strong>
            </div>

            <div class="detail-box">
                <span>Place of Birth</span>
                <strong>{{ $passport->place_of_birth }}</strong>
            </div>

            <div class="detail-box">
                <span>Place of Issue</span>
                <strong>{{ $passport->place_of_issue }}</strong>
            </div>

            <div class="detail-box">
                <span>Issue Date</span>
                <strong>{{ $passport->issue_date }}</strong>
            </div>

            <div class="detail-box">
                <span>Expiry Date</span>
                <strong>{{ $passport->expiry_date }}</strong>
            </div>

            <div class="detail-box">
                <span>Occupation</span>
                <strong>{{ $passport->occupation }}</strong>
            </div>

            <div class="detail-box">
                <span>Phone</span>
                <strong>{{ $passport->phone }}</strong>
            </div>

            <div class="detail-box">
                <span>Email</span>
                <strong>{{ $passport->email }}</strong>
            </div>

            <div class="detail-box">
                <span>Marital Status</span>
                <strong>{{ $passport->marital_status }}</strong>
            </div>

            @if($passport->marital_status != 'Single' && $passport->spouse_name)

            <div class="detail-box">
                <span>Spouse Name</span>
                <strong>{{ $passport->spouse_name }}</strong>
            </div>

            @endif

        </div>

        {{-- ADDRESS --}}
        <div class="section-title">
            Address Information
        </div>

        <div class="address-box">
            {{ $passport->address }}
        </div>

    </div>

    {{-- ================= DOCUMENTS ================= --}}
    <div class="content-card">

        <div class="card-head">
            <h3>Documents</h3>
        </div>

        <div class="table-wrapper">

            <table class="modern-table">

                <thead>
                    <tr>
                        <th>Type</th>
                        <th>File</th>
                        <th width="220">Action</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($passport->documents as $doc)

                    <tr>

                        <td>{{ $doc->type }}</td>

                        <td>
                            <a href="{{ asset('public/storage/'.$doc->file) }}"
                               target="_blank"
                               class="table-link">
                                View File
                            </a>
                        </td>

                        <td>

                            <div class="action-group">

                                <a href="{{ asset('public/storage/'.$doc->file) }}"
                                   download
                                   class="btn btn-primary btn-sm">
                                    Download
                                </a>

                                <form action="{{ route('documents.destroy', $doc->id) }}"
                                      method="POST">

                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm">
                                        Delete
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="3" class="empty-state">
                            No documents available
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- ================= VIDEOS ================= --}}
    <div class="content-card">

        <div class="card-head">
            <h3>Videos</h3>
        </div>

        <div class="video-grid">

            @forelse($passport->videos as $video)

            <div class="video-card">

                @if($video->file)

                    <video controls>
                        <source src="{{ asset('public/storage/'.$video->file) }}"
                                type="video/mp4">
                    </video>

                    <a href="{{ asset('public/storage/'.$video->file) }}"
                       download
                       class="btn btn-primary btn-full">
                        Download
                    </a>

                @elseif($video->url)

                    @php
                        preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/))([^\&\?\/]+)/', $video->url, $match);
                        $embed = isset($match[1])
                            ? 'https://www.youtube.com/embed/'.$match[1]
                            : null;
                    @endphp

                    @if($embed)

                        <iframe src="{{ $embed }}" allowfullscreen></iframe>

                    @else

                        <div class="empty-state">
                            Invalid YouTube Link
                        </div>

                    @endif

                @endif

                <form action="{{ route('videos.destroy', $video->id) }}"
                      method="POST">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-full">
                        Delete
                    </button>

                </form>

            </div>

            @empty

            <div class="empty-state full-width">
                No videos available
            </div>

            @endforelse

        </div>

    </div>

</main>
@endsection

@section('styles')

<style>

/* =====================
   GLOBAL
===================== */

body{
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
    color:#1e293b;
}

.main-content{
    padding:25px;
}

/* =====================
   HEADER
===================== */

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:20px;
    flex-wrap:wrap;
    margin-bottom:25px;
}

.page-title{
    font-size:30px;
    font-weight:700;
    margin:0;
}

.page-subtitle{
    color:#64748b;
    margin-top:5px;
    font-size:14px;
}

.header-actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

/* =====================
   BUTTONS
===================== */

.btn{
    border:none;
    padding:10px 16px;
    border-radius:10px;
    font-size:14px;
    cursor:pointer;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    transition:.25s;
    font-weight:600;
}

.btn-primary{
    background:#2563eb;
    color:#fff;
}

.btn-primary:hover{
    background:#1d4ed8;
}

.btn-danger{
    background:#ef4444;
    color:#fff;
}

.btn-danger:hover{
    background:#dc2626;
}

.btn-dark{
    background:#0f172a;
    color:#fff;
}

.btn-dark:hover{
    background:#020617;
}

.btn-sm{
    padding:7px 12px;
    font-size:13px;
}

.btn-full{
    width:100%;
    margin-top:10px;
}

/* =====================
   PASSPORT CARD
===================== */

.passport-card{
    background:#fff;
    border-radius:24px;
    padding:30px;
    margin-bottom:25px;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.profile-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:30px;
}

.profile-left{
    display:flex;
    align-items:center;
    gap:18px;
}

.profile-avatar{
    width:100px;
    height:100px;
    border-radius:50%;
    background:#2563eb;
    color:#fff;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:32px;
    font-weight:700;
    overflow:hidden;
    border:4px solid #e2e8f0;
}

.profile-avatar img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.profile-left h2{
    margin:0;
    font-size:28px;
    font-weight:700;
}

.profile-tags{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    margin-top:10px;
}

.tag{
    padding:6px 12px;
    border-radius:50px;
    font-size:12px;
    font-weight:600;
}

.tag.primary{
    background:#dbeafe;
    color:#1d4ed8;
}

.tag.success{
    background:#dcfce7;
    color:#15803d;
}

.tag.dark{
    background:#e2e8f0;
    color:#0f172a;
}

/* =====================
   SECTION
===================== */

.section-title{
    font-size:18px;
    font-weight:700;
    margin-bottom:18px;
    margin-top:25px;
    color:#0f172a;
}

/* =====================
   GRID
===================== */

.details-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:16px;
}

.detail-box{
    background:#f8fafc;
    border-radius:16px;
    padding:18px;
    border:1px solid #e2e8f0;
}

.detail-box span{
    display:block;
    font-size:13px;
    color:#64748b;
    margin-bottom:8px;
}

.detail-box strong{
    font-size:15px;
    color:#0f172a;
}

/* =====================
   ADDRESS
===================== */

.address-box{
    background:#f8fafc;
    border:1px solid #e2e8f0;
    padding:20px;
    border-radius:16px;
    line-height:1.7;
}

/* =====================
   CONTENT CARD
===================== */

.content-card{
    background:#fff;
    border-radius:24px;
    padding:25px;
    margin-bottom:25px;
    box-shadow:0 10px 30px rgba(15,23,42,.05);
}

.card-head{
    margin-bottom:20px;
}

.card-head h3{
    margin:0;
    font-size:20px;
}

/* =====================
   TABLE
===================== */

.table-wrapper{
    overflow:auto;
}

.modern-table{
    width:100%;
    border-collapse:collapse;
}

.modern-table th{
    background:#2563eb;
    color:#fff;
    padding:14px;
    text-align:left;
    font-size:14px;
}

.modern-table td{
    padding:14px;
    border-bottom:1px solid #e2e8f0;
}

.table-link{
    color:#2563eb;
    text-decoration:none;
    font-weight:600;
}

.table-link:hover{
    text-decoration:underline;
}

.action-group{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

/* =====================
   VIDEOS
===================== */

.video-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(300px,1fr));
    gap:20px;
}

.video-card{
    border:1px solid #e2e8f0;
    border-radius:20px;
    overflow:hidden;
    padding:15px;
    background:#fff;
}

.video-card video,
.video-card iframe{
    width:100%;
    height:220px;
    border:none;
    border-radius:12px;
}

/* =====================
   EMPTY
===================== */

.empty-state{
    text-align:center;
    color:#64748b;
    padding:30px;
}

.full-width{
    width:100%;
}

/* =====================
   PRINT
===================== */

@media print{

    body *{
        visibility:hidden;
    }

    #passport-print-area,
    #passport-print-area *{
        visibility:visible;
    }

    #passport-print-area{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        box-shadow:none;
        padding:0;
    }

    .no-print{
        display:none !important;
    }

}

/* =====================
   RESPONSIVE
===================== */

@media(max-width:768px){

    .main-content{
        padding:15px;
    }

    .profile-top{
        flex-direction:column;
        align-items:flex-start;
    }

    .profile-left{
        flex-direction:column;
        align-items:flex-start;
    }

    .page-title{
        font-size:24px;
    }

}

</style>
<script>

function printPassportInfo(){
    window.print();
}

</script>
@endsection