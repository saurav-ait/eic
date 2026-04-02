@extends('client-master')
@section('content')

<!-- =========================================
     AHMED HASSAN - VIDEOS
========================================= -->
<section class="services videos-page">
  <div class="videos-hero">
    <h2>Ahmed Hassan — Light Vehicle Driver</h2>
    <p>Selected videos showcasing experience, driving skills, and safety practices.</p>
  </div>

  <div class="video-grid">
    <div class="video-card">
      <div class="thumb">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
      </div>
      <h3>Safe City Driving Techniques</h3>
      <p>Short demo of defensive driving in urban areas.</p>
    </div>

    <div class="video-card">
      <div class="thumb">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/3JZ_D3ELwOQ" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
      </div>
      <h3>Vehicle Maintenance Basics</h3>
      <p>Quick checks every driver should perform before starting a shift.</p>
    </div>

    <div class="video-card">
      <div class="thumb">
        <iframe width="560" height="315" src="https://www.youtube.com/embed/L_jWHffIx5E" title="YouTube video player" frameborder="0" allowfullscreen></iframe>
      </div>
      <h3>Customer Service Onboard</h3>
      <p>Tips for professional behaviour when transporting passengers.</p>
    </div>
  </div>

  <div class="graphics-row">
    <div class="stat-card">
      <svg viewBox="0 0 100 100" class="stat-graphic"><circle cx="50" cy="50" r="45" stroke="#F4C542" stroke-width="6" fill="#fff"/></svg>
      <h4>8+ Years</h4>
      <p>Driving Experience</p>
    </div>

    <div class="stat-card">
      <svg viewBox="0 0 100 100" class="stat-graphic"><rect x="10" y="10" width="80" height="80" rx="12" stroke="#1E4BA6" stroke-width="6" fill="#fff"/></svg>
      <h4>Certified</h4>
      <p>Safety & First Aid</p>
    </div>

    <div class="stat-card">
      <svg viewBox="0 0 100 100" class="stat-graphic"><path d="M10 80 Q50 10 90 80" stroke="#0f2f6d" stroke-width="6" fill="none"/></svg>
      <h4>Reliable</h4>
      <p>On-time Deliveries</p>
    </div>
  </div>

  <div style="text-align:center; margin-top:30px;">
    <a href="{{ route('light-vehicle-driver') }}" class="btn btn-primary">Back to Light Vehicle Drivers</a>
  </div>
</section>

@endsection
