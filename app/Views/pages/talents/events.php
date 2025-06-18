<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
VenueConnect - Home
<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }

        .container {
            position: relative;
            z-index: 1;
            animation: fadeInUp 0.8s ease-in-out;
        }
        .header-section {
            text-align: center;
            margin-bottom: 3rem;
    
        }
        .header-section h1 {
            font-weight: 700;
            font-size: 2.5rem;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
        }
        .header-section h1::after {
            content: '';
            display: block;
            width: 80px;
            height: 4px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            margin: 0.5rem auto;
            border-radius: 2px;
        }
        .header-section p {
            font-size: 1.1rem;
            color: #6c757d;
            font-style: italic;
        }
        .section-title {
            font-weight: 600;
            font-size: 1.8rem;
            color: #2c3e50;
            position: relative;
            margin-bottom: 1.5rem;
            display: inline-block;
        }
        .section-title::after {
            content: '';
            display: block;
            width: 50px;
            height: 3px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            margin-top: 0.5rem;
            border-radius: 2px;
        }
        .event-card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.4s ease;
            background: white;
            overflow: hidden;
    
        }
        .event-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15), 0 0 0 3px rgba(110, 142, 251, 0.1);
        }
        .event-img {
            border-radius: 15px 15px 0 0;
            object-fit: cover;
            height: 180px;
            width: 100%;
            transition: transform 0.3s ease;
        }
        .event-card:hover .event-img {
            transform: scale(1.05);
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 0.75rem;
        }
        .card-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }
        .btn-primary, .btn-success {
            background: linear-gradient(to right, #6e8efb, #a777e3);
            border: none;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover, .btn-success:hover {
            background: linear-gradient(to right, #5a75e8, #8e5ed0);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 142, 251, 0.5);
        }
        .btn-primary::after, .btn-success::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-primary:hover::after, .btn-success:hover::after {
            left: 100%;
        }
        .btn-outline-primary {
            border-color: #6e8efb;
            color: #6e8efb;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-outline-primary:hover {
            background: linear-gradient(to right, #6e8efb, #a777e3);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 12px rgba(110, 142, 251, 0.4);
        }
        .btn-outline-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-outline-primary:hover::after {
            left: 100%;
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
        }
        .btn-secondary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-secondary:hover::after {
            left: 100%;
        }
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: none;
            background: white;
            animation: fadeInUp 0.5s ease-in-out;
        }
        .modal-header {
            background: linear-gradient(to right, #6e8efb, #a777e3);
            color: white;
            border-radius: 15px 15px 0 0;
            border-bottom: none;
        }
        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .modal-body {
            padding: 1.5rem;
        }
        .form-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .form-control, .form-select {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.7rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
        }
        .form-control:focus, .form-select:focus {
            border-color: transparent;
            box-shadow: 0 0 10px rgba(110, 142, 251, 0.4), inset 0 0 0 2px #6e8efb;
            outline: none;
            background: white;
            transform: translateY(-2px);
        }
        .booking-time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            gap: 10px;
            margin-top: 15px;
        }
        .time-slot {
            padding: 8px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            background: white;
        }
        .time-slot:hover {
            background: rgba(110, 142, 251, 0.1);
            border-color: #6e8efb;
            transform: translateY(-2px);
        }
        .time-slot.selected {
            background: linear-gradient(to right, #6e8efb, #a777e3);
            color: white;
            border-color: transparent;
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
        }
        .time-slot.unavailable {
            background: #f8f9fa;
            color: #6c757d;
            cursor: not-allowed;
            opacity: 0.7;
            border-color: #e0e0e0;
        }
        #map {
            height: 600px;
            width: 100%;
            border-radius: 15px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.8s ease-in-out;
        }
        .leaflet-container {
            z-index: 1;
        }
        .alert-info {
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-size: 0.95rem;
            color: #2c3e50;
        }
        .img-fluid.rounded {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="header-section">
        <h1>🎉 Your Local Events</h1>
        <p class="">Find and track concerts happening near you. Explore, attend, and enjoy the local music scene!</p>
    </div>

    <div class="row g-4 mb-5 pe-5">
        <div class="d-flex align-items-center justify-content-between mb-4 ">
            <h4 class="section-title"><i class="bi bi-calendar-week me-2"></i>Your Events</h4>
            <a href="<?= site_url('/talents/talentsEvents') ?>" class="btn btn-primary">
                View All <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4">
                    <div class="card event-card">
                        <img src="<?= base_url('images/serve/' . $event['image_path']) ?>" class="event-img" alt="Event">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($event['event_name']) ?></h5>
                            <p class="card-text text-muted mb-1">
                                <i class="bi bi-geo-alt me-1"></i>
                                <?= esc($event['city']) ?>, <?= esc($event['province'] ?? '') ?>
                            </p>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="bi bi-calendar-event me-1"></i>
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
                                        <h6 class="fw-bold mb-3">Event Details</h6>
                                        <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['event_startdate'])) ?></p>
                                        <p><strong>Location:</strong> <?= esc($event['city']) ?>, <?= esc($event['province'] ?? '') ?></p>
                                        <p><strong>Description:</strong> <?= esc($event['event_description']) ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info bg-info text-center">No events available at the moment.</div>
            </div>
        <?php endif; ?>
    </div>

    <h4 class="section-title mb-4">Event Venues</h4>
    <div class="map-container container">
        <div id="map"></div>
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
                <img class="w-100 rounded mb-3" src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" alt="Venue Image">
                <div id="markerModalBody"></div>
            </div>
        </div>
    </div>
</div>

<!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="eventForm" action="<?= site_url('talents/saveEvent') ?>" method="post"  enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    
                    <div class="col-12 mb-3 text-center">
                        <label for="event_image" class="form-label d-block">Event Cover Image</label>
                        <div class="d-inline-block position-relative">
                            <i class="bi bi-cloud-arrow-up-fill" style="font-size: 3rem; color: #6c757d;"></i>
                            <input type="file" class="form-control mt-2 p-2" name="event_image" id="event_image" accept="image/*">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Event Name</label>
                        <input type="text" class="form-control" name="event_name" required placeholder="Enter the event title">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Event Keywords</label>
                        <input type="text" class="form-control" name="event_keywords" required placeholder="e.g. music, theater, local arts">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Event Description</label>
                        <textarea class="form-control" name="description" required placeholder="Describe the event, purpose, and audience..." minlength="10"></textarea>
                        <div class="invalid-feedback">Event description must be at least 10 characters long.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Start Date</label>
                        <input type="datetime-local" class="form-control" name="start_date" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Date</label>
                        <input type="datetime-local" class="form-control" name="end_date" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Street Address</label>
                        <input type="text" class="form-control" id="street_address" name="street_address" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Barangay</label>
                        <input type="text" class="form-control" id="barangay" name="barangay" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">City/Province</label>
                        <input type="text" class="form-control" id="city" name="city" readonly>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">ZIP Code</label>
                        <input type="text" class="form-control" id="zip_code" name="zip_code" readonly>
                    </div>
                    <input type="hidden" name="lat" id="lat" readonly>
                    <input type="hidden" name="lang" id="lang" readonly>
                    <input type="hidden" name="venue_id" id="venue_id" readonly>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-floppy me-1"></i>Save Event</button>
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
                        <div class="booking-time-slots" id="timeSlots"></div>
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
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
<script>
    const venues = <?= json_encode($venues) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const map = L.map('map').setView([11.7693, 124.8824], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        venues.forEach(venue => {
            const marker = L.marker([venue.lat, venue.lng]).addTo(map);
            marker.bindPopup(
                `<strong><span class="text-info fs-6">₱${venue.rent}</span> - ${venue.venue_name}</strong><br>${venue.barangay}, ${venue.city} <br>${venue.venue_description}<br>
                <button class="btn btn-primary btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#addEventModal" data-venue="${encodeURIComponent(JSON.stringify(venue))}"
                onclick="bookVenue(JSON.parse(decodeURIComponent(this.dataset.venue)))">Book this venue</button>`
            );
        });
    });

    function bookVenue(venue) {
        document.getElementById('addEventModalLabel').innerText = 'Book Venue - ' + venue.venue_name;
        document.querySelector('#street_address').value = venue.street || '';
        document.querySelector('#barangay').value = venue.barangay || '';
        document.querySelector('#city').value = venue.city || '';
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

        for (let hour = 9; hour <= 21; hour++) {
            const startTime = `${hour.toString().padStart(2, '0')}:00`;
            const endTime = `${(hour + 1).toString().padStart(2, '0')}:00`;

            const slot = document.createElement('div');
            slot.className = 'time-slot';
            slot.textContent = `${startTime} - ${endTime}`;
            slot.onclick = () => selectTimeSlot(slot, startTime, endTime);

            timeSlots.appendChild(slot);
        }

        checkAvailability(date);
    }

    function selectTimeSlot(slot, startTime, endTime) {
        document.querySelectorAll('.time-slot').forEach(s => {
            s.classList.remove('selected');
        });

        slot.classList.add('selected');

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
            window.location.reload();
        })
        .catch(error => {
            console.error('Error creating booking:', error);
            alert(error.message || 'Failed to create booking');
        });
    }

    const addEventModal = document.getElementById('addEventModal');

    addEventModal.addEventListener('show.bs.modal', function (event) {
        const urlProfile = "<?= site_url('talents/profile')?>";
        if (<?= empty(session()->get('artist_data'))? 1 : 0 ?>) {
            Swal.fire({
                icon: 'error',
                title: 'Profile Update Required',
                html: `Please <a href="${urlProfile}">update your artist information</a> before proceeding with bookings.`,
                confirmButtonText: 'OK'
            });
            event.preventDefault();
            return;
        }
    });

    document.getElementById('bookingDate').addEventListener('change', generateTimeSlots);
</script>
<?= $this->endSection() ?>
