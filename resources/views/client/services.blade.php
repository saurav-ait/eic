@extends('client-master')
@section('content')

<!-- =========================================
     SERVICES PAGE HEADER
========================================= -->
    <section class="services">
      <h2>Our Immigration & Manpower Services</h2>
    
      <div class="service-grid">
    
        <a href="{{ route('work-visa') }}" class="service-box">
          <h3>Work Visa Assistance</h3>
          <p>We provide professional assistance with work permits for Canada, UK, Australia, and Gulf regions.</p>
        </a>
    
        <a href="#services/study-visa" class="service-box">
          <h3>Study Visa Consulting</h3>
          <p>Admission support, SOP writing, visa filing, and international university placement.</p>
        </a>
    
        <a href="#services/permanent-residency" class="service-box">
          <h3>Permanent Residency</h3>
          <p>Experts in Express Entry, Provincial Nominee Programs, and Skilled Migration pathways.</p>
        </a>
    
        <a href="#services/manpower-recruitment" class="service-box">
          <h3>Manpower Recruitment</h3>
          <p>Skilled and unskilled labor supply for companies in UAE, Qatar, Saudi Arabia, and beyond.</p>
        </a>
    
        <a href="#services/business-investor-visas" class="service-box">
          <h3>Business/Investor Visas</h3>
          <p>Business immigration solutions for Canada, UK, and European nations.</p>
        </a>
    
        <a href="#services/job-placement" class="service-box">
          <h3>Job Placement</h3>
          <p>Verified job opportunities abroad with employer tie-ups.</p>
        </a>
    
      </div>
    </section>

@endsection
