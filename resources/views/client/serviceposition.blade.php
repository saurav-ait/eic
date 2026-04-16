@extends('client-master')
@section('content')

<!-- =========================================
     DRIVERS CATEGORIES
========================================= -->
    <section class="services">
      <h2>{{ $category->name }} - Job Categories</h2>
      <p style="text-align: center; margin-bottom: 30px;">{{ $category->category_description ?? 'Explore various positions available internationally' }}</p>
    
      <div class="service-grid">

        @forelse($category->subcategories as $sub)
          <a href="{{ route('service-work-type', ['slug' => $sub->slug]) }}" class="service-box">
            <h3>{{ $sub->name }}</h3>
            <p>{{ $sub->subcategory_description ?? 'Explore job opportunities in this category' }}</p>
          </a>
        @empty
          <p style="text-align: center; width: 100%;">No subcategories available at the moment. Please check back later.</p>
        @endforelse

        <a href="{{ route('service-work-type') }}" class="service-box">
          <h3>Light Vehicle Driver</h3>
          <p>Professional drivers for cars, sedans, SUVs, and small vans. Suitable for private, corporate, and shuttle services.</p>
        </a>
    
      </div>

      <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('work-visa') }}" class="btn btn-primary">Back to Work Visa Categories</a>
      </div>
    </section>

@endsection
