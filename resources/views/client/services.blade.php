@extends('client-master')
@section('content')

<!-- =========================================
     SERVICES PAGE HEADER
========================================= -->
    <section class="services">
      <h2>Our Immigration & Manpower Services</h2>
    
      <div class="service-grid">

        @forelse($services as $service)
          <a href="{{ route('service.categories', $service->slug) }}" class="service-box">
            <h3>{{ $service->name }}</h3>
            <p>
              {{ \Illuminate\Support\Str::limit($service->description, 120) ?? 'No description available' }}
            </p>
          </a>
        @empty
        @endforelse
    
        <a href="{{ route('work-visa') }}" class="service-box">
          <h3>Work Visa Assistance</h3>
          <p>We provide professional assistance with work permits for Canada, UK, Australia, and Gulf regions.</p>
        </a>
    
      </div>
    </section>

@endsection
