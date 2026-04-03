@extends('admin-master')

@section('content')

<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Assign Passport</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- SUCCESS --}}
    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- MAIN CARD --}}
    <div class="assign-wrapper">

        <div class="assign-card">

            <div class="card-header">
                <h3>Assign Passport to Subcategory</h3>
                <p>Select a passport and assign it to a job subcategory</p>
            </div>

            <form action="{{ route('jobs.assign.passport') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label>Subcategory</label>
                    <select name="subcategory_id" required>
                        <option value="">Select Subcategory</option>
                        @foreach($services as $service)
                            <optgroup label="🔹 {{ $service->name }}">
                                @foreach($service->categories as $category)
                                    @foreach($category->subcategories as $sub)
                                        <option value="{{ $sub->id }}">
                                            {{ $category->name }} → {{ $sub->name }}
                                        </option>
                                    @endforeach
                                @endforeach
                            </optgroup>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Passport</label>
                    <select name="passport_id" required>
                        <option value="">Select Passport</option>
                        @foreach($passports as $passport)
                            @if(!$passport->job_subcategory_id)
                                <option value="{{ $passport->id }}">
                                    {{ $passport->passport_number }} — {{ $passport->givenname }} {{ $passport->familyname }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary full">
                    Assign Passport
                </button>

            </form>

        </div>

    </div>

</main>

{{-- ================= STYLES ================= --}}
<style>

/* WRAPPER */
.assign-wrapper {
    display:flex;
    justify-content:center;
    align-items:flex-start;
    margin-top:30px;
}

/* CARD */
.assign-card {
    width:100%;
    max-width:500px;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 8px 20px rgba(0,0,0,0.06);
}

/* HEADER */
.card-header h3 {
    color:#1E4BA6;
    margin-bottom:5px;
}

.card-header p {
    font-size:13px;
    color:#777;
    margin-bottom:20px;
}

/* FORM */
.form-group {
    margin-bottom:18px;
}

.form-group label {
    display:block;
    font-size:13px;
    font-weight:600;
    margin-bottom:6px;
    color:#444;
}

/* INPUT */
select {
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:13px;
    transition:0.2s;
}

select:focus {
    border-color:#1E4BA6;
    outline:none;
}

/* BUTTON */
.btn-primary {
    background:#1E4BA6;
    color:#fff;
    border:none;
    padding:10px;
    border-radius:6px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

.btn-primary:hover {
    background:#163a80;
}

.full {
    width:100%;
}

/* ALERT */
.alert-success {
    background:#d1fae5;
    color:#065f46;
    padding:12px;
    border-radius:6px;
    text-align:center;
    margin-bottom:20px;
}

/* RESPONSIVE */
@media(max-width:600px){
    .assign-card {
        padding:20px;
    }
}

</style>

@endsection