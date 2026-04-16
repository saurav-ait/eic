@extends('client-master')
@section('content')

<!-- =========================================
     Client - VIDEOS
========================================= -->
<section class="services videos-page">
  <div class="videos-hero">
    <h2>Ahmed Hassan — Light Vehicle Driver</h2>
    <p>Selected videos showcasing experience, driving skills, and safety practices.</p>
  </div>

  <div class="video-grid">
    @if($videos->isNotEmpty())
      @foreach($videos as $video)
        <div class="video-card">
          <div class="thumb">
            @if($video->url)
              <iframe width="560" height="315" src="{{ $video->url }}" title="Driver video" frameborder="0" allowfullscreen></iframe>
            @elseif($video->file)
              <video width="560" height="315" controls>
                <source src="{{ asset('storage/' . $video->file) }}" type="video/mp4">
                Your browser does not support the video tag.
              </video>
            @else
              <div class="thumb-placeholder">Video unavailable</div>
            @endif
          </div>
          <h3>{{ $video->title ?? 'Driver Video ' . $loop->iteration }}</h3>
          <p>{{ $video->description ?? 'Watch this recorded driving demonstration and experience overview.' }}</p>
        </div>
      @endforeach
    @else
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
    @endif
  </div>

  @if($documents->isNotEmpty())
    <section class="documents-section">
      <h3>Available Documents</h3>
      <ul class="documents-list">
        @foreach($documents as $document)
          <li>
            <a href="{{ asset('storage/' . $document->file) }}" target="_blank">
              {{ $document->type ?? 'Document' }}
            </a>
          </li>
        @endforeach
      </ul>
    </section>
  @endif

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

<style>
.videos-page .video-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 20px;
    margin-top: 30px;
}

.documents-section {
    margin: 40px auto;
    max-width: 960px;
    text-align: left;
}

.documents-list {
    list-style: none;
    padding: 0;
    margin: 20px 0 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 12px;
}

.documents-list li {
    background: #fff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.04);
}

.documents-list li a {
    color: #1E4BA6;
    font-weight: 600;
    text-decoration: none;
}

.thumb-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    color: #555;
    height: 315px;
    border-radius: 10px;
}

@media (max-width: 768px) {
    .videos-page .video-grid,
    .documents-list {
        grid-template-columns: 1fr;
    }
}
</style>

@endsection
