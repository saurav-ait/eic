@extends('admin-master')

@section('content')

<main class="main-content">

    <div class="page-header">
        <div>
            <h1 class="page-title">Create Submission</h1>
            <p class="page-subtitle">Add a new passport submission record</p>
        </div>

        <a href="{{ route('submissions.index') }}" class="btn btn-secondary">
            Back
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('submissions.store') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="passport_id">Passport <span class="required">*</span></label>
                    <select name="passport_id" id="passport_id" class="form-control" required>
                        <option value="">Select Passport</option>
                        @foreach ($passports as $passport)
                            <option value="{{ $passport->id }}" {{ old('passport_id') == $passport->id ? 'selected' : '' }}>
                                {{ trim(($passport->givenname ?? '') . ' ' . ($passport->familyname ?? '')) ?: 'Passport Holder' }}
                                - {{ $passport->passport_number }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="lawyer_id">Lawyer</label>
                    <select name="lawyer_id" id="lawyer_id" class="form-control">
                        <option value="">Select Lawyer</option>
                        @foreach ($lawyers as $lawyer)
                            <option value="{{ $lawyer->id }}" {{ old('lawyer_id') == $lawyer->id ? 'selected' : '' }}>
                                {{ $lawyer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="lawyer_group_id">Lawyer Group</label>
                    <select name="lawyer_group_id" id="lawyer_group_id" class="form-control">
                        <option value="">Select Lawyer Group</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ old('lawyer_group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="country_id">Country</label>
                    <select name="country_id" id="country_id" class="form-control">
                        <option value="">Select Country</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="submission_no">Submission Number</label>
                    <input disabled type="text" id="submission_no" value="{{ old('submission_no') ?? $submissionNo }}" class="form-control">
                    <input type="hidden" name="submission_no" value="{{ old('submission_no') ?? $submissionNo }}">
                </div>

                <div class="form-group">
                    <label for="application_number">Application Number</label>
                    <input type="text" name="application_number" id="application_number" value="{{ old('application_number') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="file_number">File Number</label>
                    <input type="text" name="file_number" id="file_number" value="{{ old('file_number') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="embassy_name">Embassy Name</label>
                    <input type="text" name="embassy_name" id="embassy_name" value="{{ old('embassy_name') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="visa_type">Visa Type</label>
                    <select name="visa_type" id="visa_type" class="form-control">
                        <option value="">Select Visa Type</option>
                        @if(isset($jobCategories) && $jobCategories->count())
                            @foreach($jobCategories as $category)
                                <option value="{{ $category->id }}" {{ old('visa_type') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label for="submission_date">Submission Date <span class="required">*</span></label>
                    <input type="date" name="submission_date" id="submission_date" value="{{ old('submission_date') }}" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="decision_date">Decision Date</label>
                    <input type="date" name="decision_date" id="decision_date" value="{{ old('decision_date') }}" class="form-control">
                </div>

                <div class="form-group">
                    <label for="status">Status <span class="required">*</span></label>
                    <select name="status" id="status" class="form-control" required>
                        @foreach ([
                            'Draft',
                            'Submitted',
                            'Processing',
                            'Document Requested',
                            'Approved',
                            'Rejected',
                            'Returned',
                            'Completed'
                        ] as $status)
                            <option value="{{ $status }}" {{ old('status', 'Draft') == $status ? 'selected' : '' }}>
                                {{ $status }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="remarks">Remarks</label>
                    <textarea name="remarks" id="remarks" rows="4" class="form-control">{{ old('remarks') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    Save Submission
                </button>
            </div>
        </form>
    </div>

</main>
<style>
.main-content{
    padding:25px;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

.page-title{
    margin:0;
    font-size:28px;
    font-weight:700;
}

.page-subtitle{
    margin:6px 0 0;
    color:#64748b;
}

.card{
    background:#fff;
    border-radius:14px;
    padding:20px;
    box-shadow:0 3px 15px rgba(0,0,0,.05);
}

.form-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:18px;
}

.form-group label{
    display:block;
    margin-bottom:7px;
    font-weight:600;
    color:#334155;
}

.form-control{
    width:100%;
    padding:10px;
    border:1px solid #d8dee9;
    border-radius:8px;
    background:#fff;
}

.form-control:focus{
    outline:none;
    border-color:#2563eb;
    box-shadow:0 0 0 3px rgba(37,99,235,.12);
}

.full-width{
    grid-column:1 / -1;
}

.required{
    color:#dc2626;
}

.form-actions{
    margin-top:22px;
}

.btn{
    display:inline-block;
    padding:10px 14px;
    border:none;
    border-radius:8px;
    text-decoration:none;
    cursor:pointer;
}

.btn-primary{
    background:#2563eb;
    color:white;
}

.btn-secondary{
    background:#64748b;
    color:white;
}

.alert{
    padding:14px 16px;
    border-radius:8px;
    margin-bottom:20px;
}

.alert-danger{
    background:#fee2e2;
    color:#991b1b;
}

.alert ul{
    margin:0;
    padding-left:20px;
}
</style>
@endsection