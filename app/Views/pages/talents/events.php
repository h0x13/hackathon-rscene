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
    <h1 class="fw-bold mb-2">🎉 Your Local Events</h1>
    <p class="text-muted">Find and track concerts happening near you. Explore, attend, and enjoy the local music scene!</p>
</div>

<div class="row g-4 mb-5 pe-0 pe-md-5">
  <div class="container d-flex align-items-center justify-content-between mt-5">
    <h4 class=""><i class="bi bi-calendar-week"></i> Your Events</h4>
    <a role="button" href="<?= site_url('/talents/talentsEvents') ?>" class="btn btn-sm btn-primary mb-0">
      View All <i class="bi bi-arrow-right"></i>
    </a>
  </div>

<?php if (!empty($events)): ?>
    <?php foreach ($events as $event): ?>
      <div class="col-md-4">
        <div class="card event-card">
          <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
          <div class="card-body">
            <h5 class="card-title"><?= esc($event['event_name']) ?></h5>
            <p class="card-text text-muted mb-1">
              <i class="bi bi-geo-alt"></i>
              <?= esc($event['city']) ?>, <?= esc($event['province'] ?? '') ?>
            </p>
            <p class="card-text">
              <small class="text-muted">
                <i class="bi bi-calendar-event"></i>
                <?= date('F j, Y', strtotime($event['event_date'])) ?>
              </small>
            </p>
            <button
              class="btn btn-outline-primary btn-sm"
              data-bs-toggle="modal"
              data-bs-target="#eventModal<?= esc($event['id']) ?>">
              View Details
            </button>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  <?php else: ?>
    <div class="col-12">
      <div class="alert alert-info text-center">You have no events yet.</div>
    </div>
  <?php endif; ?>
</div>





<h4 class="mb-3">Event Venues</h4>

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
      <form id="eventForm" action="<?= site_url('talents/saveEvent') ?>" method="post">
        <div class="modal-header">
          <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body row g-3">
          <!-- Event Details -->
          <div class="col-md-6">
            <label>Event Name</label>
            <input type="text" class="form-control" name="event_name" required placeholder="Enter the event title">
          </div>

          <div class="col-md-6">
            <label>Event Keywords</label>
            <input type="text" class="form-control" name="event_keywords" required placeholder="e.g. music, theater, local arts">
          </div>

          <div class="col-md-12">
            <label>Event Description</label>
            <textarea class="form-control" name="description" required placeholder="Describe the event, purpose, and audience..."></textarea>
          </div>

          <div class="col-md-12">
            <label>Event Date</label>
            <input type="datetime-local" class="form-control" name="event_date" required>
          </div>
          <!-- Auto-Filled Address Fields -->
          <div class="col-md-6">
            <label>Street Address</label>
            <input type="text" class="form-control" id="street_address" name="street_address" readonly>
          </div>
          <div class="col-md-6">
            <label>Barangay</label>
            <input type="text" class="form-control" id="barangay" name="barangay" readonly>
          </div>
          <div class="col-md-4">
            <label>City</label>
            <input type="text" class="form-control" id="city" name="city" readonly>
          </div>
          <div class="col-md-4">
            <label>Province</label>
            <input type="text" class="form-control" id="province" name="province" readonly>
          </div>
          <div class="col-md-4">
            <label>ZIP Code</label>
            <input type="text" class="form-control" id="zip_code" name="zip_code" readonly>
          </div>

          <input type="hidden" name="lat" id="lat" readonly>
          <input type="hidden" name="lang" id="lang" readonly>


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
    const venues = <?= json_encode($venues) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([11.2445, 125.0036], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        venues.forEach(venue => {
            const marker = L.marker([venue.lat, venue.lng]).addTo(map);
            marker.bindPopup(
                `<strong>${venue.name}</strong><br>${venue.city}, ${venue.province}<br>${venue.description}<br>
                <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addEventModal" data-venue="${encodeURIComponent(JSON.stringify(venue))}"
        onclick="bookVenue(JSON.parse(decodeURIComponent(this.dataset.venue)))">Book this venue</button>`
            );
        });
    });

      console.log(venues);
    function bookVenue(venue) {
      // Update title and visible text
      document.getElementById('addEventModalLabel').innerText = 'Book Venue - ' + venue.name;
      // Correctly select input fields by their actual IDs
    document.querySelector('#street_address').value = venue.street || '';
    document.querySelector('#barangay').value = venue.barangay || '';
    document.querySelector('#city').value = venue.city || '';
    document.querySelector('#province').value = venue.province || '';
    document.querySelector('#lang').value = venue.lng || '';
    document.querySelector('#lat').value = venue.lat || '';
    document.querySelector('[name="zip_code"]').value = venue.zip_code || '';
    }
</script>

<?= $this->endSection() ?>
