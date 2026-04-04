@extends('admin-master')

@section('content')

<main class="main-content">

    {{-- HEADER --}}
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>My Profile</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    <div class="profile-card">

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf

            {{-- BASIC INFO --}}
            <h3 class="section-title">Basic Information</h3>

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" value="{{ $user->name }}" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" value="{{ $user->email }}" disabled>
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="e.g. +8801XXXXXXXXX">
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" rows="3" placeholder="Enter your address">{{ $user->address }}</textarea>
            </div>

            <hr class="divider">

            {{-- PASSWORD --}}
            <h3 class="section-title">Change Password</h3>

            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="password" placeholder="Leave blank to keep current password">
            </div>

            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="password_confirmation">
            </div>

            {{-- SUBMIT --}}
            <button class="btn-primary full">Update Profile</button>

        </form>

    </div>

</main>

{{-- ================= STYLES ================= --}}
<style>

/* CARD */
.profile-card {
    max-width:550px;
    margin:auto;
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
}

/* SECTION */
.section-title {
    font-size:14px;
    font-weight:700;
    margin-bottom:10px;
    color:#1E4BA6;
}

/* FORM */
.form-group { margin-bottom:15px; }

label {
    font-size:13px;
    font-weight:600;
    display:block;
    margin-bottom:5px;
}

/* INPUT */
input, textarea {
    width:100%;
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
}

input:focus, textarea:focus {
    border-color:#1E4BA6;
    box-shadow:0 0 0 2px rgba(30,75,166,0.1);
}

/* BUTTON */
.btn-primary {
    background:#1E4BA6;
    color:#fff;
    padding:10px;
    border:none;
    border-radius:6px;
}

.btn-primary:hover {
    background:#163A7A;
}

.full { width:100%; }

/* ALERT */
.alert.success {
    background:#d1fae5;
    color:#065f46;
    padding:10px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
}

/* DIVIDER */
.divider {
    margin:20px 0;
    border-top:1px solid #eee;
}

</style>

@endsection