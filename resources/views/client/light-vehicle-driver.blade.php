@extends('client-master')
@section('content')

<!-- =========================================
     LIGHT VEHICLE DRIVER CATEGORY
========================================= -->
    <section class="services">
      <h2>Light Vehicle Driver - Available Positions</h2>
      <p style="text-align: center; margin-bottom: 30px;">Browse available Light Vehicle Driver positions from our team</p>
    
      <div class="service-grid">
    
        <a href="{{ route('ahmed-videos') }}" class="service-box">
          <i class="fas fa-folder" style="font-size: 40px; color: #1E4BA6; margin-bottom: 15px;"></i>
          <h3>Ahmed Hassan</h3>
          <p>Experienced light vehicle driver with 8+ years of professional driving experience. Expertise in city and highway driving.</p>
        </a>
    
        <div class="service-box">
          <i class="fas fa-folder" style="font-size: 40px; color: #1E4BA6; margin-bottom: 15px;"></i>
          <h3>Marcus Johnson</h3>
          <p>Professional chauffeur with excellent safety record. Specialized in corporate and VIP transportation services.</p>
        </div>
    
        <div class="service-box">
          <i class="fas fa-folder" style="font-size: 40px; color: #1E4BA6; margin-bottom: 15px;"></i>
          <h3>Raj Patel</h3>
          <p>Skilled light vehicle operator with expertise in GPS navigation and customer service. Valid international driving permit.</p>
        </div>
    
      </div>

      <div style="text-align: center; margin-top: 40px;">
        <a href="{{ route('drivers') }}" class="btn btn-primary">Back to Driver Categories</a>
      </div>
    </section>

@endsection
