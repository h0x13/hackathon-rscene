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

<form class="search-bar mb-4">
    <div class="input-group">
        <input type="text" class="form-control" placeholder="Search for events, artists, or locations...">
        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
    </div>
</form>

<h4 class="mb-3"><i class="bi bi-calendar"></i> Upcoming Events</h4>
  <div class="row g-4 mb-5 pe-0 pe-md-5">
    {% for event in events %}
      <div class="col-md-4">
        <div class="card event-card">
          <!-- If you have an image field, use it; else use a placeholder -->
          <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
          <div class="card-body">
            <h5 class="card-title">{{ event.event_name }}</h5>
              {% with address=event.addresses.all.0 %}
                {% if address %}
                  <p class="card-text text-muted mb-1">
                    <i class="bi bi-geo-alt"></i>
                    {{ address.barangay }}, {{ address.city }}
                  </p>
                {% endif %}
              {% endwith %}
            <p class="card-text">
              <small class="text-muted">
                <i class="bi bi-calendar-event"></i>
                {{ event.event_date|date:"F d, Y H:i" }}
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

      <div class="modal fade" id="eventModal{{ event.id }}" tabindex="-1" aria-labelledby="eventModalLabel{{ event.id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="eventModalLabel{{ event.id }}">{{ event.event_name }}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p><strong>Organizer:</strong> {{ event.event_organizer }}</p>
              <p><strong>Description:</strong> {{ event.event_description }}</p>
              <p><strong>Date:</strong> {{ event.event_date|date:"F d, Y H:i" }}</p>
              {% for address in event.addresses.all %}
                <p><strong>Address:</strong> {{ address.street_address }}, {{ address.barangay }}, {{ address.city }}, {{ address.country }}, {{ address.zip_code }}</p>
              {% endfor %}
              <p><strong>Coordinates:</strong> {{ event.location.lat }}, {{ event.location.long }}</p>
            </div>
          </div>
        </div>
      </div>
    {% empty %}
      <div class="col-12">
        <div class="alert alert-info text-center">You have no events yet.</div>
      </div>
    {% endfor %}
</div>


<h4 class="mb-3"><i class="bi bi-book"></i> Latest Books</h4>
<div class="row g-4 mb-5 pe-0 pe-md-5">
    <!-- Example event cards, replace with dynamic content -->
    <div class="col-md-4">
        <div class="card event-card">
            <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
            <div class="card-body">
                <h5 class="card-title">Summer Beats Festival</h5>
                <p class="card-text text-muted mb-1"><i class="bi bi-geo-alt"></i> Central Park</p>
                <p class="card-text"><small class="text-muted"><i class="bi bi-calendar-event"></i> June 30, 2025</small></p>
                <a href="#" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card event-card">
            <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
            <div class="card-body">
                <h5 class="card-title">Jazz Night Live</h5>
                <p class="card-text text-muted mb-1"><i class="bi bi-geo-alt"></i> Blue Note Club</p>
                <p class="card-text"><small class="text-muted"><i class="bi bi-calendar-event"></i> July 5, 2025</small></p>
                <a href="#" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card event-card">
            <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
            <div class="card-body">
                <h5 class="card-title">Indie Rock Night</h5>
                <p class="card-text text-muted mb-1"><i class="bi bi-geo-alt"></i> The Underground</p>
                <p class="card-text"><small class="text-muted"><i class="bi bi-calendar-event"></i> July 12, 2025</small></p>
                <a href="#" class="btn btn-outline-primary btn-sm">View Details</a>
            </div>
        </div>
    </div>
</div>

<h4 class="mb-3">Concert Locations</h4>

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




<?= $this->section('local_js') ?>

<script>
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

</script>

<?= $this->endSection() ?>
