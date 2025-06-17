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
    <h1 class="fw-bold mb-2">🎵 Discover Local Books and Events 📖</h1>
    <p class="text-muted">Find and track concerts happening near you. Explore, attend, and enjoy the local music scene!</p>
</div>

  <div class="row g-4 mb-5 pe-0 pe-md-5">
    
  <div class="container d-flex align-items-center justify-content-between mt-5">
    <h4 class="mb-3"><i class="bi bi-calendar"></i> Upcoming Events</h4>
    <a href="<?= site_url('/talents/allEvents') ?>" class="btn btn-sm btn-primary mb-0">
      View All <i class="bi bi-arrow-right"></i>
    </a>
  </div>
  <div class="row g-4 mb-5 pe-0 pe-md-5">
    <?php if (!empty($events)): ?>
    <?php foreach ($events as $event): ?>
      <div class="col-md-4">
        <div class="card event-card">
          <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
          <div class="card-body">
            <h5 class="card-title"><?= esc($event['event_name']) ?></h5>
            <p class="card-text text-muted mb-1">
              <i class="bi bi-geo-alt"></i>
              <?= esc($event['city']) ?>, <?= esc($event['country'] ?? '') ?>
            </p>
            <p class="card-text">
              <small class="text-muted">
                <i class="bi bi-calendar-event"></i>
                <?= date('F j, Y', strtotime($event['event_startdate'])) ?>
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
        <div class="modal fade" id="eventModal<?= esc($event['id']) ?>" tabindex="-1">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><?= esc($event['event_name']) ?></h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="img-fluid rounded" alt="Event Image">
                </div>
                <div class="col-md-6">
                  <h6>Event Details</h6>
                  <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['event_startdate'])) ?></p>
                  <p><strong>Location:</strong> <?= esc($event['city']) ?>, <?= esc($event['province'] ?? '') ?></p>
                  <p><strong>Description:</strong> <?= esc($event['event_description']) ?></p>
                  
                </div>
              </div>
            </div>
          </div>
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
      <div class="modal-body" id="markerModalBody">
        Marker content here...
      </div>
    </div>
  </div>
</div>  


<?= $this->endSection() ?>


<?= $this->section('local_javascript') ?>

<script>
  const venues = <?= json_encode($eventsForMap) ?>;
  
    console.log(venues);
    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([11.2445, 125.0036], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        venues.forEach(venue => {
            const marker = L.marker([venue.lat, venue.lng]).addTo(map);
            marker.bindPopup(
                `<strong><span class="text-info fs-5">P${venue.rent}</span> - ${venue.event_name}</strong><br>${venue.city}, ${venue.country}<br>${venue.event_description}<br>`
            ).openPopup();
        });
    });
</script>

<?= $this->endSection() ?>