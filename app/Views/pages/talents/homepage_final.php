<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>VenueConnect - Book Your Performance Space</title>
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Font Awesome for Icons -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
   <style>
      body {
         font-family: 'Poppins', sans-serif;
         background-color: #eeebe3;
      }

      .hero-section {
         background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1920&q=80');
         background-size: cover;
         background-position: center;
         color: white;
         padding: 150px 0;
         text-align: center;
      }

      .hero-section h1 {
         font-size: 4rem;
         font-weight: 700;
         text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
      }

      .hero-section p {
         font-size: 1.5rem;
         margin-bottom: 30px;
      }

      .btn-primary {
         background-color: #ca0013;
         border-color: #ca0013;
         padding: 10px 50px;
         font-size: 1.1rem;
         border-radius: 10px;
      }

      .btn-primary:hover {
         background-color: #c72a09;
         border-color: #c72a09;
      }

      .feature-card {
         transition: transform 0.3s, box-shadow 0.3s;
         border: none;
         border-radius: 15px;
         overflow: hidden;
         background: white;
         margin-bottom: 20px;
      }

      .feature-card:hover {
         transform: translateY(-10px);
         box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
      }

      .feature-card img {
         width: 100%;
         height: 200px;
         object-fit: cover;
      }

      .feature-card-body {
         padding: 20px;
         text-align: center;
      }

      .venue-card {
         border-radius: 10px;
         overflow: hidden;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      }

      .venue-card img {
         width: 100%;
         height: 250px;
         object-fit: cover;
      }

      .testimonial-section {
         background-color: #eeebe3;
         padding: 60px 0;
      }

      .testimonial-card {
         background: white;
         padding: 20px;
         border-radius: 10px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         text-align: center;
      }

      .newsletter-section {
         background-color: #ca0013;
         color: white;
         padding: 60px 0;
         text-align: center;
      }

      .newsletter-section input {
         max-width: 400px;
         margin: 0 auto;
      }

      .footer {
         background-color: #343a40;
         color: white;
         padding: 40px 0;
         text-align: center;
      }

      .footer a {
         color: #ca0013;
         text-decoration: none;
      }

      .footer a:hover {
         text-decoration: underline;
      }
   </style>
</head>

<body>
   <!-- Navigation -->
   <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
      <div class="container">
         <a class="navbar-brand" href="#hero">VenueConnect</a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
         </button>
         <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
               <li class="nav-item">
                  <a class="nav-link" href="#features">Features</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="#venues">Venues</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link" href="#contact">Contact</a>
               </li>
               <li class="nav-item">
                  <a class="nav-link btn btn-primary btn-sm ms-3" href="<?= site_url('/register') ?>">Sign Up</a>
               </li>
            </ul>
         </div>
      </div>
   </nav>

   <!-- Hero Section -->
   <section class="hero-section" id="hero">
      <div class="container">
         <h1>Discover Your Perfect Place with VenueConnect</h1>
         <p>Book top-tier venues for your performances with ease and confidence.</p>
         <a href="<?= site_url('/register') ?>" class="btn btn-primary">Start Booking Now</a>
      </div>
   </section>

   <!-- Features Section -->
   <section id="features" class="py-5">
      <div class="container">
         <h2 class="text-center mb-5">Why VenueConnect?</h2>
         <div class="row">
            <div class="col-md-4">
               <div class="feature-card">
                 <div class="text-center pt-4">
                   <i class="fa-solid fa-magnifying-glass fa-2x text-danger"></i>
                 </div>
                 <div class="feature-card-body">
                   <h5>Advanced Venue Search</h5>
                   <p>Filter venues by location, capacity, and amenities to find the perfect stage.</p>
                 </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="feature-card">
                  <div class="text-center pt-4">
                     <i class="fa-solid fa-lock fa-2x text-danger"></i>
                  </div>
                  <div class="feature-card-body">
                     <h5>Secure Booking System</h5>
                     <p>Reserve your venue with our secure, hassle-free booking process.</p>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="feature-card">
                  <div class="text-center pt-4">
                     <i class="fa-solid fa-gauge-high fa-2x text-danger"></i>
                  </div>
                  <div class="feature-card-body">
                     <h5>Artist Dashboard</h5>
                     <p>Manage bookings, schedules, and payments in one intuitive platform.</p>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- Popular Venues Section -->
   <section id="venues" class="py-5 bg-light">
      <div class="container">
         <h2 class="text-center mb-5">Explore Popular Venues</h2>
         <div class="row">
            <div class="col-md-4">
               <div class="venue-card">
                  <img
                     src="https://alchetron.com/cdn/tacloban-city-convention-center-8dd95522-343d-424d-8f17-c1f25e8fe7a-resize-750.jpg"
                     alt="Venue 2">
                  <div class="p-3">
                     <h5>The Grand Theater</h5>
                     <p>New York, NY | Capacity: 500</p>
                     <a href="<?= site_url('/register') ?>" class="btn btn-outline-primary">Book Now</a>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="venue-card">
                  <img
                     src="https://c7.alamy.com/comp/2PJ4AGE/the-philippines-leyte-tacloban-city-hall-2PJ4AGE.jpg"
                     alt="Venue 2">
                  <div class="p-3">
                     <h5>Starlight Hall</h5>
                     <p>Los Angeles, CA | Capacity: 300</p>
                     <a href="<?= site_url('/register') ?>" class="btn btn-outline-primary">Book Now</a>
                  </div>
               </div>
            </div>
            <div class="col-md-4">
               <div class="venue-card">
                  <img
                     src="http://photos.wikimapia.org/p/00/00/54/96/66_960.jpg"
                     alt="Venue 2">
                  <div class="p-3">
                     <h5>Moonlit Stage</h5>
                     <p>Chicago, IL | Capacity: 200</p>
                     <a href="<?= site_url('/register') ?>" class="btn btn-outline-primary">Book Now</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- Testimonials Section -->
   <section id="testimonials" class="testimonial-section">
      <div class="container">
         <h2 class="text-center mb-5">What Artists Say</h2>
         <div class="row">
            <div class="col-md-4">
               <div class="testimonial-card">
                  <p>"VenueConnect made booking our tour venues so easy. The platform is intuitive and reliable!"</p>
                  <h6>Jane Doe, Musician</h6>
               </div>
            </div>
            <div class="col-md-4">
               <div class="testimonial-card">
                  <p>"I found the perfect theater for my play in minutes. Highly recommend VenueConnect!"</p>
                  <h6>John Smith, Playwright</h6>
               </div>
            </div>
            <div class="col-md-4">
               <div class="testimonial-card">
                  <p>"The artist dashboard keeps everything organized. It’s a game-changer for performers."</p>
                  <h6>Emily Lee, Dancer</h6>
               </div>
            </div>
         </div>
      </div>
   </section>

   <!-- Newsletter Signup -->
   <section id="newsletter" class="newsletter-section">
      <div class="container">
         <h3>Stay Updated with VenueConnect</h3>
         <p>Subscribe to our newsletter for the latest venues and artist tips.</p>
         <!-- <form class="d-flex justify-content-center">
            <input type="email" class="form-control w-50 me-2" placeholder="Enter your email">
            <button type="submit" class="btn btn-light">Subscribe</button>
         </form> -->
      </div>
   </section>

   <!-- Call to Action -->
   <section class="py-5 text-center">
      <div class="container">
         <h3>Ready to Perform on Your Dream Stage?</h3>
         <p>Join thousands of artists using VenueConnect to book venues effortlessly.</p>
         <a href="#hero" class="btn btn-primary">Get Started Today</a>
      </div>
   </section>

   <!-- Footer -->
   <footer class="footer">
      <div class="container">
         <div class="row">
            <div class="col-md-4">
               <h5>About VenueConnect</h5>
               <p>VenueConnect is the leading platform for artists to discover and book performance venues with ease.</p>
            </div>
            <div class="col-md-4">
               <h5>Quick Links</h5>
               <p>
                  <a href="features.html">Features</a><br>
                  <a href="venue.html">Venues</a><br>
                  <a href="contact.html">Contact</a>
               </p>
            </div>
            <div class="col-md-4" id="contact">
               <h5>Contact Us</h5>
               <p class="text-start ms-5 ps-5">
                  Email: <a href="mailto:support@VenueConnect.com">support@VenueConnect.com</a><br>
                  Phone: (123) 456-7890<br>
                  Follow us: <a href="#"><i class="fab fa-facebook"></i></a> <a href="#"><i
                        class="fab fa-twitter"></i></a> <a href="#"><i class="fab fa-instagram"></i></a>
               </p>
            </div>
         </div>
         <p class="mt-4">© 2025 VenueConnect. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of
               Service</a></p>
      </div>
   </footer>

   <!-- Bootstrap JS and Popper.js -->
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>

</html>
