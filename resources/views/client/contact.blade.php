@extends('client-master')
@section('content')

<!-- =========================================
     CONTACT PAGE HEADER
========================================= -->
<section class="services">
  <h2>Contact Us</h2>

    <form class="contact-form" 
          id="contactForm" 
          action="https://script.google.com/macros/s/AKfycbxdtX-9e8JmExfrFwfnslLbpLv09anxEgY4v8r7mp2eMPgs4H_uN1W8FOMrDE1ZhN99aA/exec"
          method="POST"
          target="hidden_iframe">
    
      <label>Your Name</label>
      <input type="text" name="name" required>
    
      <label>Email</label>
      <input type="email" name="email" required>
    
      <label>Phone</label>
      <input type="text" name="phone" required>
    
      <label>Message</label>
      <textarea name="message" rows="5"></textarea>
    
      <button type="submit" class="btn-primary">Send Message</button>
    
      <p class="form-status" id="formStatus"></p>
    </form>
    
    <iframe name="hidden_iframe" style="display:none;"></iframe>


</section>

<script>
const form = document.getElementById("contactForm");
const status = document.getElementById("formStatus");

form.addEventListener("submit", function() {
    status.innerHTML = "✅ Sending...";

    setTimeout(() => {
    status.innerHTML = "✅ Message sent successfully!";
    form.reset();
    }, 1500);
});
</script>

@endsection
