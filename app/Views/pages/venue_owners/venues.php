<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>My Venues<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
<style>
    #map {
        height: 300px;
        width: 100%;
        border-radius: 4px;
        border: 1px solid #dee2e6;
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
        border-radius: 4px;
        display: none;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">My Venues</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Venue List</h5>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVenueModal">
                    <i class="bi bi-plus-lg"></i> Add New Venue
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
                                            <i class="bi bi-geo-alt"></i> <?= esc($venue['street']) ?>, <?= esc($venue['barangay']) ?>, <?= esc($venue['city']) ?>
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="bi bi-currency-dollar"></i> Rent: <?= number_format($venue['rent'], 2) ?> | 
                                            <i class="bi bi-people"></i> Capacity: <?= $venue['capacity'] ?>
                                        </small>
                                    </p>
                                    <div class="btn-group">
                                        <!-- <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editVenueModal<?= $venue['id'] ?>"> -->
                                        <!--     <i class="bi bi-pencil"></i> Edit -->
                                        <!-- </button> -->
                                        <a href="<?= base_url('venue/delete/' . $venue['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this venue?')">
                                            <i class="bi bi-trash"></i> Delete
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
                                                <div id="editMap<?= $venue['id'] ?>" style="height: 300px;"></div>
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
                        <div id="map" style="height: 300px;"></div>
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
    }
});

// Initialize edit map when edit modal is shown
<?php foreach ($venues as $venue): ?>
document.getElementById('editVenueModal<?= $venue['id'] ?>').addEventListener('shown.bs.modal', function () {
    initEditMap(<?= $venue['id'] ?>, <?= $venue['lat'] ?>, <?= $venue['lon'] ?>);
});
<?php endforeach; ?>

// Reset form when modal is hidden
document.getElementById('addVenueModal').addEventListener('hidden.bs.modal', function () {
    resetForm();
});
</script>
<?= $this->endSection() ?> 
