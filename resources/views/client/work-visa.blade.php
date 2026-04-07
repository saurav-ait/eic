@extends('client-master')
@section('content')

<!-- =========================================
     WORK VISA SECTION
========================================= -->
    <section class="services">
      <h2>{{ $service->name }} - Job Categories</h2>
      <p style="text-align: center; margin-bottom: 30px;">{{$service->description}}</p>
    
      <div class="service-grid">

        @forelse($service->categories as $category)
            <a href="{{ route('category.subcategories', $category->slug) }}" class="service-box">
            <h3>{{ $category->name }}</h3>
            <p>
              {{ \Illuminate\Support\Str::limit($category->category_description, 120) ?? 'No description available' }}
            </p>
          </a>
        @endforeach
    
        <a href="{{ route('drivers') }}" class="service-box">
          <h3>Drivers</h3>
          <p>Professional driver positions in Canada, UK, Australia, and Gulf countries. Commercial and private vehicle operation opportunities.</p>
        </a>
    
        <div class="service-box">
          <h3>Construction Workers</h3>
          <p>On-site construction roles including laborers, equipment operators, and supervisory positions across international construction projects.</p>
        </div>
    
        <div class="service-box">
          <h3>Machinery Operators</h3>
          <p>Skilled positions operating heavy machinery, industrial equipment, and specialized manufacturing tools in various sectors.</p>
        </div>
    
        <div class="service-box">
          <h3>Textile & Garments</h3>
          <p>Manufacturing and quality control roles in textile and apparel industries with training and certification support provided.</p>
        </div>
    
        <div class="service-box">
          <h3>Hotel Industry</h3>
          <p>Hospitality sector positions including housekeeping, kitchen staff, front desk, and maintenance roles in premium hotels.</p>
        </div>
    
        <div class="service-box">
          <h3>Cleaners</h3>
          <p>Commercial and residential cleaning positions in offices, hospitals, hotels, and residential complexes worldwide.</p>
        </div>
    
        <div class="service-box">
          <h3>Support Personnel</h3>
          <p>General support staff, administrative assistants, and auxiliary workers across various industries and companies.</p>
        </div>
    
        <div class="service-box">
          <h3>Hospital and Clinic Support Staff</h3>
          <p>Healthcare sector support roles including nursing assistants, orderlies, cleaning staff, and administrative support in medical facilities.</p>
        </div>
    
      </div>

      <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('services') }}" class="btn btn-primary">Back to Services</a>
      </div>
    </section>

@endsection
