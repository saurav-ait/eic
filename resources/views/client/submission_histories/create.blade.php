@extends('admin-master')

@section('content')

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Add Manual History Entry</h1>
            <p class="page-subtitle">Submission: {{ $submission->submission_no }}</p>
        </div>
        <a href="{{ route('submission.histories.index', $submission) }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="alert-info" style="background:#dbeafe; border-left:4px solid #2563eb; padding:15px; margin-bottom:20px; border-radius:6px; color:#1e40af;">
        <strong>ℹ️ Automatic Histories:</strong> Submission histories are automatically generated when you change the submission status. Use this form to add additional manual history entries with custom remarks.
    </div>

    <div class="card">
        <form method="POST"
              action="{{ route('submission.histories.store',$submission) }}">

        @csrf

        <div class="mb-3">

            <label>Date</label>

            <input type="date"
                   name="history_date"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Status</label>

            <select name="status"
                    class="form-control">

                <option>Submitted</option>
                <option>Processing</option>
                <option>Document Requested</option>
                <option>Approved</option>
                <option>Rejected</option>
                <option>Returned</option>
                <option>Completed</option>

            </select>

        </div>

        <div class="mb-3">

            <label>Remarks</label>

            <textarea name="remarks"
                      rows="5"
                      class="form-control"></textarea>

        </div>

            <button class="btn btn-success">
                Save History
            </button>
        </form>
    </div>
</main>

<style>
.main-content {
    padding: 25px;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-title {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.page-subtitle {
    margin: 6px 0 0;
    color: #64748b;
}

.card {
    background: #fff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 3px 15px rgba(0,0,0,.05);
}

.btn {
    display: inline-block;
    padding: 10px 14px;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    cursor: pointer;
}

.btn-primary {
    background: #2563eb;
    color: white;
}

.btn-secondary {
    background: #64748b;
    color: white;
}

.btn-success {
    background: #16a34a;
    color: white;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #d8dee9;
    border-radius: 8px;
    background: #fff;
    margin-bottom: 15px;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
}

label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
    color: #334155;
}

.mb-3 {
    margin-bottom: 15px;
}

.alert-info {
    background: #dbeafe;
    border-left: 4px solid #2563eb;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 6px;
    color: #1e40af;
    font-size: 14px;
}

.alert-info strong {
    display: block;
    margin-bottom: 5px;
}
</style>

@endsection