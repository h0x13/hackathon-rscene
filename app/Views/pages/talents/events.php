<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Talents - Home
<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
    <style>
        .event-card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: box-shadow 0.2s;
        }
        .event-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        }
        .event-img {
            border-radius: 12px 12px 0 0;
            object-fit: cover;
            height: 180px;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto 32px auto;
        }
      </style>

<?= $this->endSection() ?>
      
<?= $this->section('content') ?>

<div class="text-center mb-4">
    <h1 class="fw-bold mb-2">🎉 Local Events</h1>
    <p class="text-muted">Find and track concerts happening near you. Explore, attend, and enjoy the local music scene!</p>
</div>

<div class="row g-4 mb-5 pe-0 pe-md-5">
  <div class="container d-flex alig-items-center justify-content-between mt-5">
    <h4 class=""><i class="bi bi-calendar-week"></i> Your Events</h4>
    <button class="btn btn-sm btn-primary mb-0" data-bs-toggle="modal" data-bs-target="#addEventModal">
      <i class="bi bi-plus-circle"></i> Book a Venue
    </button>
  </div>

    <div class="col-md-4">
      <div class="card event-card">
        <!-- If you have an image field, use it; else use a placeholder -->
        <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
        <div class="card-body">
          <h5 class="card-title">First Event</h5>
          <p class="card-text text-muted mb-1">
            <i class="bi bi-geo-alt"></i>
            San Antonio, Basey Samar
          </p>
          <p class="card-text">
            <small class="text-muted">
              <i class="bi bi-calendar-event"></i>
              June 20, 2025
            </small>
          </p>

            <button 
              class="btn btn-outline-primary btn-sm" 
              data-bs-toggle="modal" 
              data-bs-target="#eventModal{{ event.id }}">
              View Details
            </button>
        </div>
      </div>
    </div>

     
    <div class="col-12">
      <div class="alert alert-info text-center">You have no events yet.</div>
    </div>
</div>


<h4 class="mb-3">Event Locations</h4>

<div class="map-container container">
    <div id="map" style="height: 600px; width: 100%;"></div>
    </div>
</div>

<!-- Reusable Modal -->
<div class="modal fade" id="markerModal" tabindex="-1" aria-labelledby="markerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="markerModalLabel">Marker Title</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <img class="w-100" src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" alt="">
        <div id="markerModalBody">

        </div>
      </div>
    </div>
  </div>
</div>  

<!-- Modal Form -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="eventForm">
        <div class="modal-header">
          <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body row g-3">

          <!-- Event Details -->
          <div class="col-md-6">
            <label>Event Name</label>
            <input type="text" class="form-control" name="event_name" required>
          </div>
          <div class="col-md-6">
            <label>Event Description</label>
            <input type="textbox" class="form-control" name="description" required>
          </div>
          <div class="col-md-6">
            <label>Event Keywords</label>
            <input type="text" class="form-control" name="event_name" required>
          </div>

          <div class="col-md-6">
            <label>Event Date</label>
            <input type="datetime-local" class="form-control" name="event_date" required>
          </div>

          <!-- Address -->
          <!-- Map Area -->
      <div class="col-12">
        <label>Select Location on Map</label>
        <div id="formMap" style="height: 300px; border: 1px solid #ccc;"></div>
      </div>

      <!-- Auto-Filled Address Fields -->
      <div class="col-md-6">
        <label>Street Address</label>
        <input type="text" class="form-control" name="street_address" readonly>
      </div>
      <div class="col-md-6">
        <label>Barangay</label>
        <input type="text" class="form-control" name="barangay" readonly>
      </div>
      <div class="col-md-4">
        <label>City</label>
        <input type="text" class="form-control" name="city" readonly>
      </div>
      <div class="col-md-4">
        <label>Country</label>
        <input type="text" class="form-control" name="country" readonly>
      </div>
      <div class="col-md-4">
        <label>ZIP Code</label>
        <input type="text" class="form-control" name="zip_code" readonly>
      </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('local_javascript') ?>
<script>
     document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([11.2759193, 125.0117496], 12);

    // Add OpenStreetMap tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Sample marker data
  const markers = [
    {
      lat: 11.2759,
      lng: 125.0117,
      title: 'Location A',
      content: 'This is the detail for Location A.'
    },
    {
      lat: 11.29,
      lng: 125.02,
      title: 'Location B',
      content: 'Information about Location B goes here.'
    },
    {
      lat: 11.26,
      lng: 125.00,
      title: 'Location C',
      content: 'More info about Location C.'
    }
  ];

  // Create markers and bind click events
  markers.forEach(data => {
    const marker = L.marker([data.lat, data.lng]).addTo(map);

    marker.on('click', () => {
      // Update modal content
      document.getElementById('markerModalLabel').textContent = data.title;
      document.getElementById('markerModalBody').textContent = data.content;

      // Show modal
      const myModal = new bootstrap.Modal(document.getElementById('markerModal'));
      myModal.show();
    });
  });

});
</script>

<?= $this->endSection() ?>

