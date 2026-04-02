@extends('client-master')
@section('content')

<!-- =========================================
     ASSESSMENT SECTION
========================================= -->
<section class="services">
  <h2>Free Immigration Assessment</h2>

  <form action="mailto:your-email@example.com" method="post" enctype="text/plain" class="contact-form">

    <label>Full Name</label>
    <input type="text" name="Name" required>

    <label>Email</label>
    <input type="email" name="Email" required>

    <label>Phone</label>
    <input type="text" name="Phone" required>

    <label>Preferred Country</label>
    <select name="Country">
      <option>Canada</option>
      <option>UK</option>
      <option>Australia</option>
      <option>UAE / Gulf</option>
      <option>Europe</option>
    </select>

    <label>Your Work Experience</label>
    <textarea name="Experience" rows="5"></textarea>

    <button type="submit" class="btn-primary">Submit Assessment</button>

  </form>
</section>

@endsection
