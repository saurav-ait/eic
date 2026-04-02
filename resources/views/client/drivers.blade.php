@extends('client-master')
@section('content')

<!-- =========================================
     DRIVERS CATEGORIES
========================================= -->
    <section class="services">
      <h2>Drivers - Job Categories</h2>
      <p style="text-align: center; margin-bottom: 30px;">Explore various driver positions available internationally</p>
    
      <div class="service-grid">
    
        <a href="{{ route('light-vehicle-driver') }}" class="service-box">
          <h3>Light Vehicle Driver</h3>
          <p>Professional drivers for cars, sedans, SUVs, and small vans. Suitable for private, corporate, and shuttle services.</p>
        </a>
    
        <div class="service-box">
          <h3>Heavy Vehicle Driver</h3>
          <p>Experienced drivers for heavy commercial vehicles, specialized transport, and logistics operations in international markets.</p>
        </div>
    
        <div class="service-box">
          <h3>Truck Driver (All types)</h3>
          <p>Comprehensive truck driving positions including long-haul, regional, and specialized cargo transport across borders.</p>
        </div>
    
        <div class="service-box">
          <h3>Trailer Driver</h3>
          <p>Professional trailer operation and management positions for shipping, logistics, and freight transportation networks.</p>
        </div>
    
        <div class="service-box">
          <h3>Bus Driver</h3>
          <p>Public and private bus driver roles including intercity, airport shuttle, and corporate transportation services.</p>
        </div>
    
        <div class="service-box">
          <h3>Forklift Driver</h3>
          <p>Certified forklift operators for warehouses, factories, and distribution centers with safety certifications included.</p>
        </div>
    
        <div class="service-box">
          <h3>Delivery Driver</h3>
          <p>Last-mile delivery and courier driver positions for e-commerce, logistics, and courier service companies worldwide.</p>
        </div>
    
      </div>

      <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('work-visa') }}" class="btn btn-primary">Back to Work Visa Categories</a>
      </div>
    </section>

@endsection
