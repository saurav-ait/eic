@extends('client-master')
@section('content')

<!-- =========================================
     HERO SLIDER SECTION
========================================= -->
<section class="hero-slider swiper">
  <div class="swiper-wrapper">

    <!-- SLIDE 1 -->
    <div class="swiper-slide hero-slide" style="background-image:url('{{ asset('public/images/slide1.jpg') }}');">
      <div class="hero-content">
        <h1>Your Gateway to Global Opportunities</h1>
        <p>Work Visas • Study Visas • PR • Manpower Recruitment</p>
        <a href="{{ route('assessment') }}" class="btn-primary">Free Assessment</a>
      </div>
    </div>

    <!-- SLIDE 2 -->
    <div class="swiper-slide hero-slide" style="background-image:url('{{ asset('public/images/slide2.jpg') }}');">
      <div class="hero-content">
        <h1>Build Your Career Abroad</h1>
        <p>Trusted Immigration & Overseas Jobs Consultancy</p>
        <a href="{{ route('assessment') }}" class="btn-primary">Explore Services</a>
      </div>
    </div>

    <!-- SLIDE 3 -->
    <div class="swiper-slide hero-slide" style="background-image:url('{{ asset('public/images/slide3.jpg') }}');">
      <div class="hero-content">
        <h1>We Connect Talent With Global Employers</h1>
        <p>Skilled Worker Migration & Verified Job Offers</p>
        <a href="{{ route('assessment') }}" class="btn-primary">Find Jobs</a>
      </div>
    </div>

  </div>

  <div class="swiper-pagination"></div>
</section>

<!-- =========================================
     SERVICES SECTION
========================================= -->
<section class="services">
  <h2>Our Services</h2>

  <div class="service-grid">
    <div class="service-box">
      <h3>Work Visa Assistance</h3>
      <p>Work visas for Canada, UK, UAE, Australia and more.</p>
    </div>

    <div class="service-box">
      <h3>Study Visa</h3>
      <p>University admissions & student visa guidance.</p>
    </div>

    <div class="service-box">
      <h3>Permanent Residency</h3>
      <p>Canada Express Entry, Skilled Migration & PR pathways.</p>
    </div>

    <div class="service-box">
      <h3>Manpower Recruitment</h3>
      <p>Overseas hiring for hospitality, construction, healthcare & more.</p>
    </div>
  </div>
</section>

<!-- =========================================
     Process SECTION
========================================= -->

<section class="process-section">
  <h2 class="process-title">Our Hiring Process</h2>

  <div class="timeline">

    <!-- Your existing process steps go inside this -->

    <div class="process-step reveal">
      <div class="process-text">
        <h3>1. Talent Requisition</h3>
        <p>We receive detailed manpower requirements directly from verified international employers.</p>
      </div>
    </div>

    <div class="process-step reverse reveal">
      <div class="process-text">
        <h3>2. Candidate Sourcing</h3>
        <p>We source skilled candidates through our database, job portals, and professional networks.</p>
      </div>
    </div>

    <div class="process-step reveal">
      <div class="process-text">
        <h3>3. Screening & Shortlisting</h3>
        <p>Applicants go through strict screening, document verification, and skill evaluation.</p>
      </div>
    </div>

    <div class="process-step reverse reveal">
      <div class="process-text">
        <h3>4. Employer Interview & Selection</h3>
        <p>Shortlisted candidates attend employer interviews for final approval.</p>
      </div>
    </div>

    <div class="process-step reveal">
      <div class="process-text">
        <h3>5. Visa Processing & Deployment</h3>
        <p>We handle visa processing, medical tests, orientation, and final deployment.</p>
      </div>
    </div>

  </div>
</section>

<!-- =========================================
     Process SECTION
========================================= -->
<section class="mission-vision-section">

  <div class="mission-vision-container">

    <!-- MISSION -->
    <div class="mission-box fly-left">
      <h2>Our Mission</h2>
      <p>
        Our mission is to connect skilled talent with trusted global employers,
        ensuring transparent, ethical, and reliable overseas recruitment while
        empowering individuals to build successful international careers.
      </p>
    </div>

    <!-- VISION -->
    <div class="vision-box fly-right">
      <h2>Our Vision</h2>
      <p>
        Our vision is to become a globally recognized immigration and manpower
        consultancy, known for excellence, integrity, and long-term success for
        both candidates and employers.
      </p>
    </div>

  </div>

</section>

<!-- =========================================
     Why Choose US SECTION
========================================= -->
<section class="why-choose-us">

  <div class="why-container">
    <h2 class="why-title">Why Choose Us</h2>
    <p class="why-subtitle">
      We deliver trusted, transparent & result-driven immigration and manpower solutions.
    </p>

    <div class="why-grid">

      <div class="why-card">
        <div class="why-icon">🌍</div>
        <h3>Global Network</h3>
        <p>Strong connections with verified employers across multiple countries.</p>
      </div>

      <div class="why-card">
        <div class="why-icon">✅</div>
        <h3>100% Transparent Process</h3>
        <p>No hidden costs, full documentation clarity & legal procedures.</p>
      </div>

      <div class="why-card">
        <div class="why-icon">👨‍💼</div>
        <h3>Expert Consultants</h3>
        <p>Highly experienced immigration & manpower recruitment advisors.</p>
      </div>

      <div class="why-card">
        <div class="why-icon">📄</div>
        <h3>High Visa Success Rate</h3>
        <p>Strong application process built for maximum approval chances.</p>
      </div>

      <div class="why-card">
        <div class="why-icon">🚀</div>
        <h3>Fast Processing</h3>
        <p>Quick documentation & employer matching with minimal delays.</p>
      </div>

      <div class="why-card">
        <div class="why-icon">🤝</div>
        <h3>Lifetime Support</h3>
        <p>Support before departure and after you land abroad.</p>
      </div>

    </div>
  </div>

</section>

<!-- =========================================
     Email Subcription SECTION
========================================= -->
<section class="email-subscription">

  <div class="subscription-container">
    
    <div class="subscription-text">
      <h2>Stay Updated With Us</h2>
      <p>
        Subscribe to receive the latest job updates, visa news, and overseas opportunities directly in your inbox.
      </p>
    </div>

    <form class="subscription-form" id="subscribeForm">
    
      <input 
        type="email" 
        id="emailInput"
        name="email" 
        placeholder="Enter your email address" 
        required
      >
    
      <button type="submit" class="btn-primary" id="subscribeBtn">
        <span class="btn-text">Subscribe</span>
        <span class="btn-loader"></span>
      </button>
    
    </form>
    
    <!-- ✅ SUCCESS POPUP -->
    <div class="subscribe-success" id="subscribeSuccess">
      <div class="popup-box">
        <h3>✅ Subscription Successful!</h3>
        <p>Thank you for subscribing.</p>
        <button onclick="closeSuccess()" class="btn-primary">OK</button>
      </div>
    </div>


  </div>

</section>

<!-- =========================================
     Query SECTION
========================================= -->
<section class="any-query-section">
  <div class="any-query-container">
    <h2>Any Query?</h2>
    <p>Please feel free to contact us anytime. Our team is ready to assist you.</p>
    <a href="contact.php" class="any-query-btn">Contact Us</a>
  </div>
</section>
@endsection