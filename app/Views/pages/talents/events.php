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
        .booking-time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        .time-slot {
            padding: 8px;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .time-slot:hover {
            background-color: #f8f9fa;
        }
        .time-slot.selected {
            background-color: #0d6efd;
            color: white;
            border-color: #0d6efd;
        }
        .time-slot.unavailable {
            background-color: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.7;
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
      </div>

      <!-- Event Details Modal -->
      
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
            <textarea class="form-control" name="description" required placeholder="Describe the event, purpose, and audience..." minlength="10"></textarea>
            <div class="invalid-feedback">Event description must be at least 10 characters long.</div>
          </div>

          <div class="col-md-6">
            <label>Start Date</label>
            <input type="datetime-local" class="form-control" name="start_date" required>
          </div>

          <div class="col-md-6">
            <label>End Date</label>
            <input type="datetime-local" class="form-control" name="end_date" required>
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
            <label>City/Province</label>
            <input type="text" class="form-control" id="city" name="city" readonly>
          </div>
          <div class="col-md-4">
            <label>ZIP Code</label>
            <input type="text" class="form-control" id="zip_code" name="zip_code" readonly>
          </div>

          <input type="hidden" name="lat" id="lat" readonly>
          <input type="hidden" name="lang" id="lang" readonly>
          <input type="hidden" name="venue_id" id="venue_id" readonly>


        <div class="modal-footer">
          <button type="submit" class="btn btn-success"><i class="bi bi-floppy"></i> Save Event</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book Event</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="bookingForm">
                    <input type="hidden" id="eventId" name="event_id">
                    <input type="hidden" id="startTime" name="start_time">
                    <input type="hidden" id="endTime" name="end_time">
                    
                    <div class="mb-3">
                        <label class="form-label">Select Date</label>
                        <input type="date" class="form-control" id="bookingDate" required min="<?= date('Y-m-d') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Time Slot</label>
                        <div class="booking-time-slots" id="timeSlots">
                            <!-- Time slots will be populated here -->
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="bookingNotes" name="notes" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod" name="payment_method" required>
                            <option value="">Select payment method</option>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitBooking()">Book Now</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


<?= $this->section('local_javascript') ?>
<script>
    const venues = <?= json_encode($venues) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([11.7693, 124.8824], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        venues.forEach(venue => {
            const marker = L.marker([venue.lat, venue.lng]).addTo(map);
            marker.bindPopup(
                `<strong><span class="text-info fs-6">P${venue.rent}</span> - ${venue.venue_name}</strong><br>${venue.barangay}, ${venue.city}<br>${venue.venue_description}<br>
                <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addEventModal" data-venue="${encodeURIComponent(JSON.stringify(venue))}"
        onclick="bookVenue(JSON.parse(decodeURIComponent(this.dataset.venue)))">Book this venue</button>`
            )
        });
    });

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
      document.querySelector('#venue_id').value = venue.id || '';
      document.querySelector('[name="zip_code"]').value = venue.zip_code || '';
    }

    function showBookingModal(eventId) {
        document.getElementById('eventId').value = eventId;
        document.getElementById('bookingDate').value = '';
        document.getElementById('startTime').value = '';
        document.getElementById('endTime').value = '';
        document.getElementById('bookingNotes').value = '';
        document.getElementById('paymentMethod').value = '';
        document.getElementById('timeSlots').innerHTML = '';
        
        new bootstrap.Modal(document.getElementById('bookingModal')).show();
    }

    function generateTimeSlots() {
        const date = document.getElementById('bookingDate').value;
        if (!date) return;

        const timeSlots = document.getElementById('timeSlots');
        timeSlots.innerHTML = '';

        // Generate time slots from 9 AM to 9 PM
        for (let hour = 9; hour <= 21; hour++) {
            const startTime = `${hour.toString().padStart(2, '0')}:00`;
            const endTime = `${(hour + 1).toString().padStart(2, '0')}:00`;
            
            const slot = document.createElement('div');
            slot.className = 'time-slot';
            slot.textContent = `${startTime} - ${endTime}`;
            slot.onclick = () => selectTimeSlot(slot, startTime, endTime);
            
            timeSlots.appendChild(slot);
        }

        // Check availability for each slot
        checkAvailability(date);
    }

    function selectTimeSlot(slot, startTime, endTime) {
        // Remove selection from all slots
        document.querySelectorAll('.time-slot').forEach(s => {
            s.classList.remove('selected');
        });
        
        // Add selection to clicked slot
        slot.classList.add('selected');
        
        // Update hidden inputs
        document.getElementById('startTime').value = `${document.getElementById('bookingDate').value} ${startTime}`;
        document.getElementById('endTime').value = `${document.getElementById('bookingDate').value} ${endTime}`;
    }

    function checkAvailability(date) {
        const eventId = document.getElementById('eventId').value;
        
        fetch(`<?= base_url('booking/check-availability') ?>/${eventId}?date=${date}`)
            .then(response => response.json())
            .then(data => {
                const slots = document.querySelectorAll('.time-slot');
                slots.forEach(slot => {
                    const [startTime] = slot.textContent.split(' - ');
                    if (data.unavailable_slots.includes(startTime)) {
                        slot.classList.add('unavailable');
                        slot.onclick = null;
                    }
                });
            })
            .catch(error => {
                console.error('Error checking availability:', error);
            });
    }

    function submitBooking() {
        const formData = {
            event_id: document.getElementById('eventId').value,
            start_time: document.getElementById('startTime').value,
            end_time: document.getElementById('endTime').value,
            notes: document.getElementById('bookingNotes').value,
            payment_method: document.getElementById('paymentMethod').value
        };

        if (!formData.start_time || !formData.end_time) {
            alert('Please select a time slot');
            return;
        }

        fetch('<?= base_url('booking/create') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            alert('Booking created successfully!');
            bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
            // Optionally refresh the page or update the UI
            window.location.reload();
        })
        .catch(error => {
            console.error('Error creating booking:', error);
            alert(error.message || 'Failed to create booking');
        });
    }

    // Add event listener for date change
    document.getElementById('bookingDate').addEventListener('change', generateTimeSlots);
</script>

<?= $this->endSection() ?>
