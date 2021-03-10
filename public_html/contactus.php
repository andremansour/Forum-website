<?php
  include('../private/header.php');
  include('../private/navbar.php');
?>

<div class="container py-5 pageIndicator h-auto" page="contactus">
  <h1 class="text-center display-4">Get in touch!</h1>
  <p class="text-center">Send us a message and we'll get back to you as soon as we can!</p>

  <form id="contactform" class="mt-5">
    <div class="form-row">
      <div class="col-12 mb-3">
        <label for="name">Full Name</label>
        <input type="text" class="b-block w-100 mt-1 rounded" id="name" required>
      </div>
    </div>
    <div class="form-group">
      <label for="exampleFormControlTextarea1" class="mb-1">Your Message</label>
      <textarea id="message" class="d-block rounded w-100" id="exampleFormControlTextarea1" rows="3" required=""></textarea>
    </div>
    <button class="btn btn-primary" type="submit">Submit</button>
  </form>
</div>