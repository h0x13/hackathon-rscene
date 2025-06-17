<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
VenueConnect - My Venues
<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background: linear-gradient(135deg, #6e8efb, #a777e3);
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1), transparent 70%);
            z-index: -1;
        }
        .container-fluid {
            position: relative;
            z-index: 1;
        }
        h1 {
            font-weight: 700;
            font-size: 2rem;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
            animation: fadeInUp 0.8s ease-in-out;
        }
        h1::after {
            content: '';
            display: block;
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, #6e8efb, #a777e3);
            margin-top: 0.5rem;
            border-radius: 2px;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.98);
            border: none;
            animation: fadeInUp 0.8s ease-in-out;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background: white;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 15px 15px 0 0;
            padding: 1rem 1.5rem;
        }
        .card-header h5 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
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
        .btn-primary {
            background: linear-gradient(to right, #6e8efb, #a777e3);
            border: none;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:hover {
            background: linear-gradient(to right, #5a75e8, #8e5ed0);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 142, 251, 0.5);
        }
        .btn-primary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-primary:hover::after {
            left: 100%;
        }
        .btn-danger {
            background: #dc3545;
            border: none;
            font-weight: 500;
            border-radius: 8px;
            padding: 0.5rem 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }
        .btn-danger::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-danger:hover::after {
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
        .form-control {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 0.7rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.95);
        }
        .form-control:focus {
            border-color: transparent;
            box-shadow: 0 0 10px rgba(110, 142, 251, 0.4), inset 0 0 0 2px #6e8efb;
            outline: none;
            background: white;
            transform: translateY(-2px);
        }
        #map, [id^="editMap"] {
            height: 300px;
            width: 100%;
            border-radius: 10px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .leaflet-container {
            z-index: 1;
        }
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            border-radius: 8px;
            display: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .text-muted {
            font-size: 0.9rem;
            color: #6c757d !important;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4 py-4">
    <h1>My Venues</h1>

    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Venue List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">
                    <i class="bi bi-plus-lg me-1"></i>Add New Venue
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row" id="venuesContainer">
                <?php if (empty($venues)): ?>
                    <div class="col-12 text-center py-4">
                        <p class="text-muted">No venues found. Add your first venue!</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($venues as $venue): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h5 class="card-title"><?= esc($venue['venue_name']) ?></h5>
                                    <p class="card-text"><?= esc($venue['venue_description']) ?></p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="bi bi-geo-alt me-1"></i>
                                            <?= esc($venue['street']) ?>, <?= esc($venue['barangay']) ?>, <?= esc($venue['city']) ?>
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="bi bi-currency-dollar me-1"></i>Rent: ₱<?= number_format($venue['rent'], 2) ?> |
                                            <i class="bi bi-people me-1"></i>Capacity: <?= $venue['capacity'] ?>
                                        </small>
                                    </p>
                                    <div class="btn-group">
                                        <!-- Uncomment if Edit functionality is needed -->
                                        <!-- <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editVenueModal<?= $venue['id'] ?>">
                                            <i class="bi bi-pencil me-1"></i>Edit
                                        </button> -->
                                        <a href="<?= base_url('venue/delete/' . $venue['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this venue?')">
                                            <i class="bi bi-trash me-1"></i>Delete
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Modal for each venue -->
                        <div class="modal fade" id="editVenueModal<?= $venue['id'] ?>" tabindex="-1" aria-labelledby="editVenueModalLabel<?= $venue['id'] ?>" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editVenueModalLabel<?= $venue['id'] ?>">Edit Venue</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="<?= base_url('venue/edit/' . $venue['id']) ?>" method="POST" id="editVenueForm<?= $venue['id'] ?>">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="editVenueName<?= $venue['id'] ?>" class="form-label">Venue Name</label>
                                                    <input type="text" class="form-control" id="editVenueName<?= $venue['id'] ?>" name="venue_name" value="<?= esc($venue['venue_name']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="editVenueDescription<?= $venue['id'] ?>" class="form-label">Description</label>
                                                    <input type="text" class="form-control" id="editVenueDescription<?= $venue['id'] ?>" name="venue_description" value="<?= esc($venue['venue_description']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Location</label>
                                                <div id="editMap<?= $venue['id'] ?>"></div>
                                                <input type="hidden" name="lat" id="editLat<?= $venue['id'] ?>" value="<?= $venue['lat'] ?>">
                                                <input type="hidden" name="lon" id="editLon<?= $venue['id'] ?>" value="<?= $venue['lon'] ?>">
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="editStreet<?= $venue['id'] ?>" class="form-label">Street Address</label>
                                                    <input type="text" class="form-control" id="editStreet<?= $venue['id'] ?>" name="street" value="<?= esc($venue['street']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label for="editBarangay<?= $venue['id'] ?>" class="form-label">Barangay</label>
                                                    <input type="text" class="form-control" id="editBarangay<?= $venue['id'] ?>" name="barangay" value="<?= esc($venue['barangay']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <label for="editCity<?= $venue['id'] ?>" class="form-label">City</label>
                                                    <input type="text" class="form-control" id="editCity<?= $venue['id'] ?>" name="city" value="<?= esc($venue['city']) ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="editZipCode<?= $venue['id'] ?>" class="form-label">ZIP Code</label>
                                                    <input type="text" class="form-control" id="editZipCode<?= $venue['id'] ?>" name="zip_code" value="<?= esc($venue['zip_code']) ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="editCapacity<?= $venue['id'] ?>" class="form-label">Capacity</label>
                                                    <input type="number" class="form-control" id="editCapacity<?= $venue['id'] ?>" name="capacity" value="<?= esc($venue['capacity']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="editRent<?= $venue['id'] ?>" class="form-label">Rent (PHP)</label>
                                                    <input type="number" step="0.01" class="form-control" id="editRent<?= $venue['id'] ?>" name="rent" value="<?= esc($venue['rent']) ?>" required>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Venue Modal -->
<div class="modal fade" id="addVenueModal" tabindex="-1" aria-labelledby="addVenueModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addVenueModalLabel">Add Venue</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('venue/add') ?>" method="POST" id="venueForm">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="venueName" class="form-label">Venue Name</label>
                            <input type="text" class="form-control" id="venueName" name="venue_name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="venueDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="venueDescription" name="venue_description" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <div id="map"></div>
                        <input type="hidden" name="lat" id="lat">
                        <input type="hidden" name="lon" id="lon">
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="street" class="form-label">Street Address</label>
                            <input type="text" class="form-control" id="street" name="street" required>
                        </div>
                        <div class="col-md-6">
                            <label for="barangay" class="form-label">Barangay</label>
                            <input type="text" class="form-control" id="barangay" name="barangay" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="city" class="form-label">City</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-4">
                            <label for="zipCode" class="form-label">ZIP Code</label>
                            <input type="text" class="form-control" id="zipCode" name="zip_code" required>
                        </div>
                        <div class="col-md-4">
                            <label for="capacity" class="form-label">Capacity</label>
                            <input type="number" class="form-control" id="capacity" name="capacity" required>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="rent" class="form-label">Rent (PHP)</label>
                            <input type="number" step="0.01" class="form-control" id="rent" name="rent" required>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Venue</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('local_javascript') ?>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
let map, marker;
let editMaps = {};

// Initialize map for adding venue
function initMap() {
    map = L.map('map').setView([10.7202, 122.5621], 13); // Iloilo City coordinates
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    map.on('click', function(e) {
        updateMarker(e.latlng);
        fetchAddressDetails(e.latlng);
    });
}

// Initialize edit map for a specific venue
function initEditMap(venueId, lat, lng) {
    if (!editMaps[venueId]) {
        const editMap = L.map(`editMap${venueId}`).setView([lat, lng], 15);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(editMap);

        const editMarker = L.marker([lat, lng]).addTo(editMap);

        editMap.on('click', function(e) {
            editMarker.setLatLng(e.latlng);
            document.getElementById(`editLat${venueId}`).value = e.latlng.lat;
            document.getElementById(`editLon${venueId}`).value = e.latlng.lng;
            fetchEditAddressDetails(e.latlng, venueId);
        });

        editMaps[venueId] = { map: editMap, marker: editMarker };
    }
}

// Update marker position
function updateMarker(latlng) {
    if (marker) {
        marker.setLatLng(latlng);
    } else {
        marker = L.marker(latlng).addTo(map);
    }
    document.getElementById('lat').value = latlng.lat;
    document.getElementById('lon').value = latlng.lng;
}

// Fetch address details from coordinates for add form
async function fetchAddressDetails(latlng) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`);
        const data = await response.json();

        if (data.address) {
            document.getElementById('street').value = data.address.road || '';
            document.getElementById('barangay').value = data.address.suburb || '';
            document.getElementById('city').value = data.address.city || data.address.town || '';
            document.getElementById('zipCode').value = data.address.postcode || '';
        }
    } catch (error) {
        console.error('Error fetching address details:', error);
    }
}

// Fetch address details from coordinates for edit form
async function fetchEditAddressDetails(latlng, venueId) {
    try {
        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latlng.lat}&lon=${latlng.lng}`);
        const data = await response.json();

        if (data.address) {
            document.getElementById(`editStreet${venueId}`).value = data.address.road || '';
            document.getElementById(`editBarangay${venueId}`).value = data.address.suburb || '';
            document.getElementById(`editCity${venueId}`).value = data.address.city || data.address.town || '';
            document.getElementById(`editZipCode${venueId}`).value = data.address.postcode || '';
        }
    } catch (error) {
        console.error('Error fetching address details:', error);
    }
}

// Reset form
function resetForm() {
    document.getElementById('venueForm').reset();
    document.getElementById('lat').value = '';
    document.getElementById('lon').value = '';
    if (marker) {
        map.removeLayer(marker);
        marker = null;
    }
}

// Initialize map when modal is shown
document.getElementById('addVenueModal').addEventListener('shown.bs.modal', function () {
    if (!map) {
        initMap();
    } else {
        map.invalidateSize();
    }
});

// Initialize edit map when edit modal is shown
<?php foreach ($venues as $venue): ?>
document.getElementById('editVenueModal<?= $venue['id'] ?>').addEventListener('shown.bs.modal', function () {
    initEditMap(<?= $venue['id'] ?>, <?= $venue['lat'] ?>, <?= $venue['lon'] ?>);
    editMaps[<?= $venue['id'] ?>].map.invalidateSize();
});
<?php endforeach; ?>

// Reset form when modal is hidden
document.getElementById('addVenueModal').addEventListener('hidden.bs.modal', function () {
    resetForm();
});
</script>
<?= $this->endSection() ?>
