@extends('client-master')
@section('content')

<section class="services">

    {{-- TITLE --}}
    <h2>{{ $subcategory->name }} - Available Positions</h2>

    <p style="text-align: center; margin-bottom: 30px;">
        Browse available {{ $subcategory->name }} positions from our team.
    </p>

    <h2>
        {{ $subcategory->name }}
        ({{ $subcategory->passports->count() }} {{ $subcategory->passports->count() === 1 ? 'Candidate' : 'Candidates' }})
    </h2>

    {{-- GRID --}}
    <div class="service-grid">

        @forelse($subcategory->passports as $passport)

            <a href="{{ route('passports.show', $passport->id) }}" class="service-box">

                <i class="fas fa-folder" style="font-size: 40px; color: #1E4BA6; margin-bottom: 15px;"></i>

                <h3>
                    {{ $passport->givenname }} {{ $passport->familyname }}
                </h3>

                <p>
                    Passport No: {{ $passport->passport_number }} <br>
                    {{ $passport->nationality ?? 'N/A' }} • {{ $passport->gender ?? '' }}
                </p>

            </a>

        @empty

            <div class="service-box" style="grid-column:1/-1; text-align:center;">
                <h3>No Candidates Available</h3>
                <p>Please check back later</p>
            </div>

        @endforelse

    </div>

    {{-- BACK BUTTON --}}
    @php
        $backRoute = route('services');
        if ($subcategory->category && $subcategory->category->service) {
            $backRoute = route('category.subcategories', [$subcategory->category->service->slug, $subcategory->category->slug]);
        }
    @endphp

    <div style="text-align: center; margin-top: 40px;">
        <a href="{{ $backRoute }}" class="btn btn-primary">
            Back to {{ $subcategory->category->name ?? 'categories' }}
        </a>
    </div>

</section>

{{-- ================= STYLES (UNCHANGED) ================= --}}
<style>

.services {
    padding: 40px 20px;
}

.service-grid {
    display:grid;
    grid-template-columns: repeat(auto-fit, minmax(260px,1fr));
    gap:20px;
}

.service-box {
    background:#fff;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.05);
    text-decoration:none;
    color:#333;
    transition:0.25s;
    text-align:center;
}

.service-box:hover {
    transform:translateY(-5px);
    box-shadow:0 8px 20px rgba(0,0,0,0.08);
}

.service-box h3 {
    color:#1E4BA6;
    margin-bottom:10px;
}

.service-box p {
    font-size:14px;
    color:#555;
    line-height:1.5;
}

.btn-primary {
    background-color:#1E4BA6;
    color:white;
    border:none;
    border-radius:5px;
    padding:10px 18px;
    text-decoration:none;
}

.btn-primary:hover {
    background:#163A7A;
}

@media(max-width:768px){
    .service-grid {
        grid-template-columns:1fr;
    }
}

</style>

@endsection