@extends('admin-master')

@section('content')
<main class="main-content">
    <div class="top-bar" style="justify-content: space-between; display: flex; align-items: center; flex-wrap: wrap; gap:10px;">
        <div class="top-bar-title">
            <h1>Add New Passport</h1>
            <p>{{ now()->format('l, F j, Y') }}</p>
        </div>
        <div style="display:flex; gap:10px;">
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
                <div class="form-group">
                    <label>Marital Status</label>

                    <select name="marital_status"
                            id="marital_status"
                            class="form-control"
                            required>
                        <option value="">Select</option>

                        <option value="Single"{{ old('marital_status')=='Single' ? 'selected' : '' }}>Single</option>

                        <option value="Married"{{ old('marital_status')=='Married' ? 'selected' : '' }}>Married</option>

                        <option value="Widow"{{ old('marital_status')=='Widow' ? 'selected' : '' }}>Widow</option>

                        <option value="Divorced"{{ old('marital_status')=='Divorced' ? 'selected' : '' }}   >Divorced</option>
                    </select>
                </div>
                <div class="form-group"></div>
                    <label for="spouse_name">Spouse Name</label>
                    <input type="text" disabled="disabled" name="spouse_name" id="spouse_name" value="{{ old('spouse_name') }}" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                </div>
                <div class="form-group"></div>
                    <label for="occupation">Occupation</label>
                    <input type="text" name="occupation" id="occupation" value="{{ old('occupation') }}" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
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

            {{-- Agent Information --}}
            <fieldset style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
                <legend style="font-weight:700; color:#1E4BA6;">Agent Information</legend>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                    <div>
                        <label for="agent_id">Agent</label>
                        <select name="agent_id" id="agent_id" style="width:100%; padding:10px; border-radius:5px; border:1px solid #ccc;">
                            <option value="">Select Agent</option>
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ old('agent_id') == $agent->id ? 'selected' : '' }}>
                                    {{ $agent->name }}
                                </option>
                            @endforeach
                        </select>
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

{{-- Styles --}}
<style>
.main-content {
    padding: 20px;
}

/* Buttons */
.btn-primary {
    background-color: #1E4BA6;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 8px 12px;
    font-size: 13px;
}
.btn-primary:hover { background-color: #163A7A; }

/* Form */
label {
    display: block;
    font-weight: 600;
    margin-bottom: 5px;
    color: #333;
}

fieldset {
    border: 1px solid #ccc;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 25px;
}

legend {
    font-weight: 700;
    color: #1E4BA6;
    padding: 0 10px;
}

input[type="text"],
input[type="date"],
input[type="email"],
select {
    width: 100%;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    font-size: 14px;
}

input[type="text"]:focus,
input[type="date"]:focus,
input[type="email"]:focus,
select:focus {
    outline: none;
    border-color: #1E4BA6;
    box-shadow: 0 0 5px rgba(30, 75, 166, 0.3);
}

/* Alert */
.alert {
    padding: 15px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.alert-danger {
    background-color: #fed7d7;
    color: #c53030;
    border: 1px solid #fc8181;
}

.alert ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

/* Responsive */
@media(max-width:768px){
    .top-bar { flex-direction: column; align-items: flex-start; gap:10px; }
    input[type="text"],
    input[type="date"],
    input[type="email"],
    select { font-size: 12px; padding: 8px; }
    .btn-primary { font-size:12px; padding:6px 10px; }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const maritalStatus = document.getElementById('marital_status');
    const spouseName    = document.getElementById('spouse_name');

    function toggleSpouseField() {

        if (maritalStatus.value === 'Single') {

            spouseName.disabled = true;
            spouseName.value = '';

        } else {

            spouseName.disabled = false;
        }
    }

    toggleSpouseField();

    maritalStatus.addEventListener('change', toggleSpouseField);
});
</script>
@endsection