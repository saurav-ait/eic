@extends('admin-master')

@section('content')
<main class="main-content">

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Submission Details</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- SUBMISSION INFO --}}
    <div class="card info-card" style="margin-bottom:30px;" id="submission-print-area">

        <div class="info-header">
            
                <div>
                    <p class="submission-subtitle">Submission Details Information:</p>
                    <h2 class="submission-number">{{ $submission->submission_no }}</h2>
                </div>
                <div class="action-buttons" style="display:flex; gap:10px;">
                    <button onclick="printSubmissionInfo()" class="btn-primary no-print">
                        Print Details
                    </button>
                    <a href="{{ route('submission.histories.index', $submission) }}" class="btn-primary no-print">
                        View Histories
                    </a>
                </div>
        </div>

        {{-- SUBMISSION INFO GRID--}}
        <div class="info-grid">
            
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

/* INFO HEADER */
.info-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    gap:15px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

.submission-subtitle{
    color:#777;
    margin-top:4px;
    font-size:14px;
}

/* PRINT */
    @media print {

    body *{
        visibility:hidden;
    }

    #submission-print-area,
    #submission-print-area *{
        visibility:visible;
    }

    #submission-print-area{
        position:absolute;
        left:0;
        top:0;
        width:100%;
        background:#fff;
        padding:20px;
    }

    .no-print{
        display:none !important;
    }

    .info-grid{
        grid-template-columns: repeat(2, 1fr);
    }

    .info-card h2{
        color:#000 !important;
    }

}

/* CARD */
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
<script>
function printSubmissionInfo() {
    window.print();
}
</script>

@endsection

