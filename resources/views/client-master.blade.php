<!DOCTYPE html>
<html lang="en">
<head>
  <!-- ===============================
       META & TITLE
  ================================ -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EIC – Manpower & Immigration Consulting</title>

  <!-- ===============================
       STYLES & FONTS
  ================================ -->
  <link rel="stylesheet" href="{{ asset('public/css/styles.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
  
    <!--<link rel="stylesheet" href="css/global.css">-->
    <!--<link rel="stylesheet" href="css/header.css">-->
    <!--<link rel="stylesheet" href="css/buttons.css">-->
    <!--<link rel="stylesheet" href="css/hero.css">-->
    <!--<link rel="stylesheet" href="css/services.css">-->
    <!--<link rel="stylesheet" href="css/forms.css">-->
    <!--<link rel="stylesheet" href="css/footer.css">-->
    <!--<link rel="stylesheet" href="css/whatsapp.css">-->
    <!--<link rel="stylesheet" href="css/responsive.css">-->

</head>

<body>

<!-- =========================================
     HEADER SECTION
========================================= -->
<header class="site-header transparent-header" id="siteHeader">
  <div class="header-container">
      
        <!-- HAMBURGER BUTTON -->
        <div class="hamburger" id="hamburger">
          <span></span>
          <span></span>
          <span></span>
        </div>


    <!-- LOGO -->
    <!--<div class="logo-area">-->
    <!--  <img src="logo.png" alt="Euro Immigration Consulting Logo" class="site-logo">-->
    <!--</div>-->
    
    <div class="logo-area">
      <a href="{{ route('home') }}">
        <img src="{{ asset('public/images/logo.png') }}" class="site-logo logo-default">
        <img src="{{ asset('public/images/logo-white.png') }}" class="site-logo logo-scrolled">
      </a>
      
    </div>


    <!-- NAVIGATION -->
    <nav class="main-nav">
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('services') }}">Services</a>
      <a href="{{ route('countries') }}">Countries</a>
      <a href="{{ route('jobs') }}">Jobs</a>
      <a href="{{ route('contact') }}" class="btn-primary">Contact</a>
    </nav>

  </div>
</header>

@yield('content')

<!-- =========================================
     FOOTER SECTION
========================================= -->
<footer class="site-footer">
  <div class="footer-container">

    <!-- BRAND -->
    <div class="footer-brand">
      <a href="{{ route('home') }}">
        <img src="{{ asset('public/images/logo-white.png') }}" alt="Logo" class="footer-logo">
      </a>
      <h3>Euro Immigration Consulting S.R.L.</h3>
      <h5>Employment Agency Licensed and Approved by Romanian Government.</h5>
      <p>Your trusted partner for Work Visas, Study Visas, PR & Overseas Jobs.</p>
      <p>License No J40/10901/2022;</p>
      <p>CUI - 46271458;</p>
    </div>

    <!-- QUICK LINKS -->
    <div class="footer-links">
      <h4>Quick Links</h4>
      <a href="{{ route('home') }}">Home</a>
      <a href="{{ route('services') }}">Services</a>
      <a href="{{ route('countries') }}">Countries</a>
      <a href="{{ route('jobs') }}">Jobs</a>
      <a href="{{ route('contact') }}">Contact</a>
    </div>

    <!-- CONTACT INFO -->
    <div class="footer-contact">
      <h4>Contact Us</h4>
      <p>📞 +880 1728 719500</p>
      <p>📧 euroimmiconsulting@gmail.com</p>
      <p>📍 Romania Office: Lămâiului St. no 4, Camera 1, Sector 1, Bucharest, Romania</p>
      <p>📍 Bangladesh Office: 1/5 Sat Masjid Road, Mohammadpur, Dhaka, Bangladesh</p>
    </div>

  </div>

  <div class="footer-bottom">
    <p>© 2025 Euro Immigration Consulting S.R.L. All Rights Reserved.</p>
  </div>
</footer>

<!-- =========================================
     WHATSAPP FLOATING BUTTON
========================================= -->
<a href="https://wa.me/8801670571644?text=Hello%20I%20want%20to%20apply%20for%20overseas%20job"
   class="whatsapp-chat"
   target="_blank"
   rel="noopener"
   id="whatsapp-btn"
   aria-label="Chat with us on WhatsApp">

  <span class="whatsapp-tooltip">Chat with us</span>

  <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg"
       alt="Chat on WhatsApp">
</a>

<!-- =========================================
     SCRIPTS
========================================= -->

<!-- Swiper -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper(".hero-slider", {
    loop: true,
    autoplay: { delay: 4000 },
    pagination: { el: ".swiper-pagination", clickable: true },
  });
</script>

<!-- WhatsApp Analytics -->
<script>
  document.getElementById("whatsapp-btn").addEventListener("click", function() {
    if (typeof gtag === "function") {
      gtag("event", "whatsapp_click", {
        event_category: "engagement",
        event_label: "WhatsApp Floating Button",
      });
    }
  });
</script>

<!-- Header Scroll Effect -->
<script>
  const header = document.getElementById("siteHeader");
  window.addEventListener("scroll", function () {
    if (window.scrollY > 80) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  });
</script>

<!-- Hambuger Menu -->
<script>
  const hamburger = document.getElementById("hamburger");
  const nav = document.querySelector(".main-nav");

  hamburger.addEventListener("click", () => {
    hamburger.classList.toggle("active");
    nav.classList.toggle("active");
  });
</script>

<!-- Process Section -->
<script>
  const reveals = document.querySelectorAll(".reveal");

  function revealOnScroll() {
    for (let i = 0; i < reveals.length; i++) {
      const windowHeight = window.innerHeight;
      const revealTop = reveals[i].getBoundingClientRect().top;
      const revealPoint = 100;

      if (revealTop < windowHeight - revealPoint) {
        reveals[i].classList.add("active");
      }
    }
  }

  window.addEventListener("scroll", revealOnScroll);
  revealOnScroll(); // run on load
</script>

<script>
  const flyElements = document.querySelectorAll(".fly-left, .fly-right");

  function flyOnScroll() {
    flyElements.forEach(el => {
      const top = el.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;

      if (top < windowHeight - 100) {
        el.classList.add("fly-active");
      }
    });
  }

  window.addEventListener("scroll", flyOnScroll);
  flyOnScroll();
</script>

<script>
  const whyCards = document.querySelectorAll('.why-card');

  const whyObserver = new IntersectionObserver(entries => {
    entries.forEach((entry, index) => {
      if (entry.isIntersecting) {
        setTimeout(() => {
          entry.target.classList.add('show');
        }, index * 150); // stagger effect
      }
    });
  }, { threshold: 0.2 });

  whyCards.forEach(card => {
    whyObserver.observe(card);
  });
</script>

<script>
  const form = document.getElementById("subscribeForm");
  const emailInput = document.getElementById("emailInput");
  const button = document.getElementById("subscribeBtn");
  const loader = button.querySelector(".btn-loader");
  const text = button.querySelector(".btn-text");
  const successBox = document.getElementById("subscribeSuccess");

  const GOOGLE_SHEET_URL = "https://script.google.com/macros/s/AKfycbxDF_4R_8OxXd7T8bgaskFZRJxLiNwNKaDcQhAIG235BngVaEzuuNKRmY-j6hMdxS7-/exec";

  form.addEventListener("submit", function (e) {
    e.preventDefault();

    loader.style.display = "inline-block";
    text.style.display = "none";

    fetch(GOOGLE_SHEET_URL, {
      method: "POST",
      body: JSON.stringify({ email: emailInput.value })
    })
    .then(res => res.json())
    .then(data => {
      loader.style.display = "none";
      text.style.display = "inline";

      if (data.status === "success") {
        successBox.classList.add("active");
        form.reset();
      } else {
        alert("Submission Failed!");
      }
    })
    .catch(err => {
      loader.style.display = "none";
      text.style.display = "inline";
      alert("Network Error!");
      console.error(err);
    });
  });

  function closeSuccess() {
    successBox.classList.remove("active");
  }
</script>



</body>
</html>
