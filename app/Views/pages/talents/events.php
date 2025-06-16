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
      <i class="bi bi-plus-circle"></i> Add Event
    </button>
  </div>

    <div class="col-md-4">
      <div class="card event-card">
        <!-- If you have an image field, use it; else use a placeholder -->
        <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
        <div class="card-body">
          <h5 class="card-title">{{ event.event_name }}</h5>
          <p class="card-text text-muted mb-1">
            <i class="bi bi-geo-alt"></i>
          </p>
          <p class="card-text">
            <small class="text-muted">
              <i class="bi bi-calendar-event"></i>
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
            <p><strong>Address:</strong>
              {{ event.address.barangay }},
              {{ event.address.city }},
              {{ event.address.country }} 
            </p>
            <p><strong>Coordinates:</strong> {{ event.location.lat }}, {{ event.location.long }}</p>
          </div>
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


<?= $this->section('local_js') ?>
<script>
    let mainMap, formMap, selectedMarker;

    // If you have a time string like "14:30"
  function to12Hour(time24) {
      const [hour, minute] = time24.split(':');
      const date = new Date();
      date.setHours(hour, minute);
      return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', hour12: true });
  }


  function formatDate(dateStr) {
    const [year, month, day] = dateStr.split('-');
    const dateObj = new Date(year, month - 1, day);
    return dateObj.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
  }


  function getCSRFToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  }

async function initMainMap() {
  mainMap = L.map('map').setView([11.2759193, 125.0117496], 12);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
  }).addTo(mainMap);

  // Fetch event locations from API
  try {
    const res = await axios.get('/event_planner/events/locations/');
    const events = res.data.events;
    events.forEach(event => {
      const marker = L.marker([event.lat, event.lng]).addTo(mainMap);
      marker.on('click', () => {
  // Exclude these fields
  const exclude = [
    'event_name', 'event_description', 'event_date',
    'full_address', 'barangay', 'city', 'lat', 'lng'
  ];
  let details = '';
  for (const [key, value] of Object.entries(event)) {
    if (!exclude.includes(key) && value) {
      // Format key to be more readable
      const label = key.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
      details += `<div><strong>${label}:</strong> ${value}</div>`;
    }
  }
  const [date, time] = event.event_date.split(' ');
  const time12hr = to12Hour(time);
  const formattedDate = formatDate(date);

  // Always show the main info at the top
  document.getElementById('markerModalLabel').textContent = event.event_name;
  document.getElementById('markerModalBody').innerHTML = `
    <div><strong>Description:</strong> ${event.event_description}</div>
      <div><strong>Date:</strong> ${formattedDate}</div>
  <div><strong>Time:</strong> ${time12hr}</div>
    <div><strong>Address:</strong> ${event.full_address}</div>
    <div><strong>Barangay:</strong> ${event.barangay}</div>
    <div><strong>City:</strong> ${event.city}</div>
    <hr>
    ${details}
  `;
  const modal = new bootstrap.Modal(document.getElementById('markerModal'));
  modal.show();
});
    });
  } catch (err) {
    console.error('Failed to load event locations', err);
  }
}
  
function initFormMap() {
    formMap = L.map('formMap').setView([11.2759, 125.0117], 12);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: '&copy; OpenStreetMap contributors'
    }).addTo(formMap);

    formMap.on('click', async function (e) {
      const lat = e.latlng.lat;
      const lon = e.latlng.lng;

      if (selectedMarker) formMap.removeLayer(selectedMarker);
      selectedMarker = L.marker([lat, lon]).addTo(formMap);

      try {
        const res = await axios.get('https://nominatim.openstreetmap.org/reverse', {
          params: { format: 'json', lat, lon }
        });

        const addr = res.data.address;

        document.querySelector('[name="street_address"]').value = addr.road || '';
        document.querySelector('[name="barangay"]').value = addr.suburb || addr.village || '';
        document.querySelector('[name="city"]').value = addr.city || addr.town || addr.municipality || '';
        document.querySelector('[name="country"]').value = addr.country || '';
        document.querySelector('[name="zip_code"]').value = addr.postcode || '';

        // Store lat/lon on form
        const form = document.getElementById('eventForm');
        form.dataset.lat = lat;
        form.dataset.lon = lon;

      } catch (err) {
        alert("Reverse geocoding failed.");
      }
    });
  }

  document.getElementById("eventForm").addEventListener("submit", async function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);

    const lat = form.dataset.lat;
    const lon = form.dataset.lon;

    if (!lat || !lon) {
      alert("Please select a location on the map.");
      return;
    }

    const postData = {
      event_name: formData.get("event_name"),
      event_date: formData.get("event_date"),
      description: formData.get("description"),

      street_address: formData.get("street_address"),
      barangay: formData.get("barangay"),
      city: formData.get("city"),
      country: formData.get("country"),
      zip_code: formData.get("zip_code"),

      lat: parseFloat(lat),
      long: parseFloat(lon),
    };

    try {
      await axios.post("/event_planner/events/create", postData, {
        headers: {
          "X-CSRFToken": getCSRFToken(),
        }
      });
      alert("Event created successfully!");

      form.reset();
      form.dataset.lat = "";
      form.dataset.lon = "";

      const modal = bootstrap.Modal.getInstance(document.getElementById('addEventModal'));
      modal.hide();

      if (selectedMarker) {
        formMap.removeLayer(selectedMarker);
        selectedMarker = null;
      }

    } catch (error) {
      console.error(error);
      alert("Failed to add event.");
    }
  });

  document.getElementById('addEventModal').addEventListener('shown.bs.modal', function () {
    setTimeout(() => {
      if (!formMap) {
        initFormMap();
      } else {
        formMap.invalidateSize();
      }
    }, 200);
  });


  initMainMap();
  document.addEventListener('DOMContentLoaded', function () {
  var eventDetailModal = document.getElementById('eventDetailModal');
  eventDetailModal.addEventListener('show.bs.modal', function (event) {
    var button = event.relatedTarget;
    var eventData = JSON.parse(button.getAttribute('data-event'));

    document.getElementById('modalEventName').textContent = eventData.name;
    document.getElementById('modalEventDescription').textContent = eventData.description;
    document.getElementById('modalEventDate').textContent = eventData.date;
    document.getElementById('modalEventTime').textContent = eventData.time;
    document.getElementById('modalEventStreet').textContent = eventData.street;
    document.getElementById('modalEventBarangay').textContent = eventData.barangay;
    document.getElementById('modalEventCity').textContent = eventData.city;
    document.getElementById('modalEventCountry').textContent = eventData.country;
    document.getElementById('modalEventZip').textContent = eventData.zip;
  });
});
</script>

<?= $this->endSection() ?>

