@extends('admin-master')

@section('content')

<main class="main-content">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Submission History</h1>
            <p class="page-subtitle">Submission: {{ $submission->submission_no }}</p>
        </div>
        <a href="{{ route('submission.histories.index', $submission) }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    <div class="card">
        <form method="POST"
              action="{{ route('submission.histories.update',$history) }}">

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="history_date">Date</label>
                <input type="date"
                       name="history_date"
                       id="history_date"
                       value="{{ $history->history_date }}"
                       class="form-control" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select name="status"
                        id="status"
                        class="form-control" required>
                    <option value="Submitted" {{ $history->status == 'Submitted' ? 'selected' : '' }}>Submitted</option>
                    <option value="Processing" {{ $history->status == 'Processing' ? 'selected' : '' }}>Processing</option>
                    <option value="Document Requested" {{ $history->status == 'Document Requested' ? 'selected' : '' }}>Document Requested</option>
                    <option value="Approved" {{ $history->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                    <option value="Rejected" {{ $history->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="Returned" {{ $history->status == 'Returned' ? 'selected' : '' }}>Returned</option>
                    <option value="Completed" {{ $history->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>

            <div class="form-group">
                <label for="remarks">Remarks</label>
                <textarea name="remarks"
                          id="remarks"
                          class="form-control"
                          rows="4">{{ $history->remarks }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Update History
                </button>
                <form method="POST"
                      action="{{ route('submission.histories.destroy',$history) }}"
                      style="display:inline; margin-left:10px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger"
                            onclick="return confirm('Delete this history?')">
                        Delete
                    </button>
                </form>
            </div>
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

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 7px;
    font-weight: 600;
    color: #334155;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #d8dee9;
    border-radius: 8px;
    background: #fff;
}

.form-control:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.12);
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

.btn-danger {
    background: #e53e3e;
    color: white;
}

.form-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}
</style>

@endsection