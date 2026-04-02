@extends('admin-master')

@section('content')
<main class="main-content">
    <div class="top-bar">
        <div class="top-bar-title">
            <h1>Add New Passport</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div>
            <a href="{{ route('passports.index') }}" class="btn-primary">Back to List</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger form-status" style="color: red; text-align:center; margin-bottom:20px;">
            <ul style="list-style:none; padding:0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="content-section" style="padding:30px;">
        <form action="{{ route('passports.store') }}" method="POST">
            @csrf

            {{-- Personal Information --}}
            <fieldset style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <legend style="font-weight:700; color:#1E4BA6;">Personal Information</legend>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">

                    <div>
                        <label for="familyname">Family Name</label>
                        <input type="text" name="familyname" id="familyname" value="{{ old('familyname') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>
                    
                    <div>
                        <label for="givenname">Given Name</label>
                        <input type="text" name="givenname" id="givenname" value="{{ old('givenname') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="father_name">Father's Name</label>
                        <input type="text" name="father_name" id="father_name" value="{{ old('father_name') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="mother_name">Mother's Name</label>
                        <input type="text" name="mother_name" id="mother_name" value="{{ old('mother_name') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="date_of_birth">Date of Birth</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="place_of_birth">Place of Birth</label>
                        <input type="text" name="place_of_birth" id="place_of_birth" value="{{ old('place_of_birth') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="gender">Gender</label>
                        <select name="gender" id="gender" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                            <option value="">Select</option>
                            <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="nationality">Nationality</label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>
                </div>
            </fieldset>

            {{-- Passport Information --}}
            <fieldset style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <legend style="font-weight:700; color:#1E4BA6;">Passport Details</legend>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">

                    <div>
                        <label for="passport_number">Passport Number</label>
                        <input type="text" name="passport_number" id="passport_number" value="{{ old('passport_number') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="issue_date">Issue Date</label>
                        <input type="date" name="issue_date" id="issue_date" value="{{ old('issue_date') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="expiry_date">Expiry Date</label>
                        <input type="date" name="expiry_date" id="expiry_date" value="{{ old('expiry_date') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="place_of_issue">Place of Issue</label>
                        <input type="text" name="place_of_issue" id="place_of_issue" value="{{ old('place_of_issue') }}" required style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                </div>
            </fieldset>

            {{-- Contact Information --}}
            <fieldset style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <legend style="font-weight:700; color:#1E4BA6;">Contact Information</legend>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <label for="address">Address</label>
                        <input type="text" name="address" id="address" value="{{ old('address') }}" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>

                    <div>
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                    </div>
                </div>
            </fieldset>

            {{-- Submit --}}
            <div style="text-align:center; margin-top: 20px;">
                <button type="submit" class="btn-primary" style="padding: 12px 30px; font-size:16px;">Save Passport</button>
            </div>
        </form>
    </div>
</main>
@endsection