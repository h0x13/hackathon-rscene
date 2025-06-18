<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>VenueConnect - Book Your Performance Space</title>
   <!-- Bootstrap CSS -->
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <!-- Google Fonts: Poppins -->
   <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
   <!-- Font Awesome for Icons -->
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
   <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
   <style>
      body {
            font-family: 'Poppins', sans-serif;
            background: #f2f3ff;
            color: #2c3e50;
      }
      .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1514525253161-7a46d19cd819?auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 250px 0;
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
            background: linear-gradient(to right, #6e8efb, #a777e3);
            border: none;
            padding: 10px 50px;
            font-size: 1.1rem;
            border-radius: 10px; /* Reverted to original curvature */
            transition: all 0.3s ease;
      }
      .btn-primary:hover {
            background: linear-gradient(to right, #5a75e8, #8e5ed0);
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(110, 142, 251, 0.5);
      }
      .btn-outline-primary {
            border-color: #6e8efb;
            color: #6e8efb;
            border-radius: 10px; /* Reverted to original curvature */
            transition: all 0.3s ease;
      }
      .btn-outline-primary:hover {
            background-color: #6e8efb;
            color: white;
            transform: translateY(-2px);
      }
      .feature-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 20px;
            overflow: hidden;
            background: white;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-in-out;
      }
      .feature-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
      }
      @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
      }
      .feature-card-body {
            padding: 25px;
            text-align: center;
      }
      .feature-card i {
            color: #6e8efb;
            transition: transform 0.3s ease;
      }
      .feature-card:hover i {
            transform: scale(1.2);
      }
      .venue-card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
            background: white;
            transition: transform 0.3s, box-shadow 0.3s;
            margin-bottom: 30px;
            animation: fadeInUp 0.6s ease-in-out;
      }
      .venue-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
      }
      .venue-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
      }
      .newsletter-section {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
            overflow: hidden;
      }
      .newsletter-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.1);
            z-index: 1;
      }
      .newsletter-section .container {
            position: relative;
            z-index: 2;
      }
      .newsletter-section h3 {
            font-weight: 600;
            margin-bottom: 20px;
      }
      .newsletter-section p {
            font-size: 1.1rem;
      }
      .calltoaction {
            padding: 100px 0;
            text-align: center;
            background: #fff;
            border-radius: 20px;
            margin: 50px auto;
            max-width: 900px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: fadeInUp 0.6s ease-in-out;
      }
      .calltoaction h3 {
            font-weight: 600;
            color: #2c3e50;
      }
      .calltoaction p {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 30px;
      }
      .footer {
            background-color: #2c3e50;
            color: white;
            padding: 60px 0;
            text-align: center;
            position: relative;
      }
      .footer h5 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #a777e3;
      }
      .footer a {
            color: #6e8efb;
            text-decoration: none;
            transition: color 0.3s ease;
      }
      .footer a:hover {
            color: #a777e3;
            text-decoration: underline;
      }
      .footer .social-icons a {
            font-size: 1.5rem;
            margin: 0 10px;
      }
      .navbar {
            background-color: #2c3e50 !important;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      }
      .navbar-brand, .nav-link {
            color: white !important;
            font-weight: 500;
      }
      .nav-link:hover {
            color: #a777e3 !important;
      }
      .nav-item .btn-primary {
            padding: 8px 20px;
            font-size: 0.95rem;
            border-radius: 10px; /* Reverted to original curvature */
      }
      .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease-in-out;
        }
        #map {
            height: 600px;
            width: 100%;
            border-radius: 15px;
        }
   </style>
</head>
<body>
   <!-- Navigation -->
   <nav class="navbar navbar-expand-lg navbar-dark fixed-top p-3">
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
                        <a class="nav-link btn btn-primary btn-sm ms-3" href="/register">Sign Up</a>
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
            <a href="/register" class="btn btn-primary">Start Booking Now</a>
      </div>
   </section>

   <!-- Map Section -->
    <section id="features" class="py-5">
      <div class="container p-5">
            <h2 class="text-center mb-5">Upcoming Performances and Events</h2>
            <div class="map-container pe-5">
                  <div id="map"></div>
            </div>
      </div>
    </section>

   <!-- Features Section -->
   <section id="features" class="py-5">
      <div class="container p-5">
            <h2 class="text-center mb-5">Why VenueConnect?</h2>
            <div class="row">
               <div class="col-md-4">
                  <div class="feature-card">
                        <div class="text-center pt-4">
                           <i class="fa-solid fa-magnifying-glass fa-2x"></i>
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
                           <i class="fa-solid fa-lock fa-2x"></i>
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
                           <i class="fa-solid fa-gauge-high fa-2x"></i>
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
   <section id="venues" class="p-5">
      <div class="container">
            <h2 class="text-center mb-5">Explore Popular Venues</h2>
            <div class="row">
               <div class="col-md-4">
                  <div class="venue-card">
                        <img src="https://alchetron.com/cdn/tacloban-city-convention-center-8dd95522-343d-424d-8f17-c1f25e8fe7a-resize-750.jpg" alt="Venue 1">
                        <div class="p-4">
                           <h5>Astrodome</h5>
                           <p>Tacloban City | Capacity: 4,500</p>
                           <!-- <a href="/register" class="btn btn-outline-primary">Book Now</a> -->
                        </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="venue-card">
                        <img src="https://c7.alamy.com/comp/2PJ4AGE/the-philippines-leyte-tacloban-city-hall-2PJ4AGE.jpg" alt="Venue 2">
                        <div class="p-4">
                           <h5>Starlight Hall</h5>
                           <p>Tacloban City | Capacity: 300</p>
                           <!-- <a href="/register" class="btn btn-outline-primary">Book Now</a> -->
                        </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="venue-card">
                        <img src="http://photos.wikimapia.org/p/00/00/54/96/66_960.jpg" alt="Venue 3">
                        <div class="p-4">
                           <h5>RTR Plaza</h5>
                           <p>Tacloban City | Capacity: 4,200</p>
                           <!-- <a href="/register" class="btn btn-outline-primary">Book Now</a> -->
                        </div>
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
   <section class="calltoaction">
      <div class="container">
            <h3>Ready to Perform on Your Dream Stage?</h3>
            <p>Join thousands of artists using VenueConnect to book venues effortlessly.</p>
            <a href="/register" class="btn btn-primary">Get Started Today</a>
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
                           Phone: +63 9946765907<br>
                           Follow us:
                           <a href="#"><i class="fab fa-facebook social-icons m-1"></i></a>
                           <a href="#"><i class="fab fa-twitter social-icons m-1"></i></a>
                           <a href="#"><i class="fab fa-instagram social-icons m-1"></i></a>
                     </p>
               </div>
            </div>
            <p class="mt-4">© 2025 VenueConnect. All rights reserved. | <a href="#">Privacy Policy</a> | <a href="#">Terms of Service</a></p>
      </div>
   </footer>

   <!-- Bootstrap JS and Popper.js -->
   <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>

   <script src="<?= base_url('js/script.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session()->getFlashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= session()->getFlashdata('error'); ?>'
                });
            <?php endif; ?>

            <?php if(session()->getFlashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= session()->getFlashdata('success'); ?>'
                });
            <?php endif; ?>
        });
    </script>
      s<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
      <script>
      const venues = <?= json_encode($eventForMap) ?>;

      document.addEventListener('DOMContentLoaded', function() {
            const map = L.map('map').setView([11.7693, 124.8824], 10);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            venues.forEach(venue => {
                  const marker = L.marker([venue.lat, venue.lng]).addTo(map);
                  marker.bindPopup(
                  `<strong><span class="text-info fs-5">P${venue.rent}</span> - ${venue.event_name}</strong><br>${venue.city}, ${venue.country}<br>${venue.event_description}<br>`
                  );
            });
      });
      </script>

</body>
</html>
