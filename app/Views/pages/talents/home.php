<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
VenueConnect - Discover Events
<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
        .search-bar {
            max-width: 600px;
            margin: 0 auto 2.5rem;
            position: relative;
            
        }
        .search-bar input {
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 0.8rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }
        .search-bar input:focus {
            border-color: transparent;
            box-shadow: 0 0 12px rgba(110, 142, 251, 0.4), inset 0 0 0 2px #6e8efb;
            outline: none;
            transform: translateY(-2px);
            background: white;
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
            transform: translateY(-2px);
        }
        .btn-outline-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .btn-outline-primary:hover::after {
            left: 100%;
        }

        .map-container {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            
        }
        #map {
            height: 600px;
            border-radius: 15px;
        }
        .section-title {
            font-weight: 700;
            font-size: 1.8rem;
            color: #2c3e50;
            position: relative;
            margin-bottom: 2rem;
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
        .alert-info {
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
            color: #2c3e50;
            
        }
        .modal-content {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            border: none;
            background: white;
            
        }
        .modal-header {
            border-bottom: 1px solid #e0e0e0;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            color: white;
            border-radius: 15px 15px 0 0;
        }
        .modal-title {
            font-weight: 600;
            font-size: 1.25rem;
        }
        .modal-body {
            font-size: 0.95rem;
            color: #2c3e50;
            padding: 1.5rem;
        }
        .btn-close {
            filter: brightness(0) invert(1);
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
        <h1>🎵 Discover Local Events 📖</h1>
        <p >Explore and attend exciting concerts and events happening near you!</p>
    </div>

    <div class="search-bar">
        <input type="text" class="form-control" placeholder="Search events by name or location..." aria-label="Search events">
    </div>

<div class="row g-4 mb-5 pe-5">  
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h4 class="section-title"><i class="bi bi-calendar me-2"></i>Upcoming Events</h4>
        <a href="<?= site_url('/talents/allEvents') ?>" class="btn btn-primary">
            View All <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>

        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Event">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($event['event_name']) ?></h5>
                            <p class="card-text text-muted mb-1">
                                <i class="bi bi-geo-alt me-1"></i>
                                <?= esc($event['city']) ?>, <?= esc($event['country'] ?? '') ?>
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
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    <!-- Modal -->
                    <div class="modal" id="eventModal<?= esc($event['id']) ?>" tabindex="-1">
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
                <div class="alert alert-info bg-info text-center">No events available at the moment.</div>
            </div>
        <?php endif; ?>
    </div> 


    <h4 class="section-title mb-4">Event Locations</h4>
    <div class="map-container container pe-5">
        <div id="map"></div>
    </div>
</div>

<!-- Reusable Modal -->
<div class="modal fade" id="markerModal" tabindex="-1" aria-labelledby="markerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="markerModalLabel">Event Details</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="markerModalBody">
                Event details will appear here...
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('local_javascript') ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const venues = <?= json_encode($events) ?>;

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
<?= $this->endSection() ?>
