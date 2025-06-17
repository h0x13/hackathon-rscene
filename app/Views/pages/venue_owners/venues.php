<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>My Venues<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
<style>
    /* Modern Card Styling */
    .card {
        border: none;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        overflow: hidden;
    }

    .card-header {
        background: transparent;
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Container Styling */
    .container-fluid {
        padding: 0;
        max-width: 100%;
    }

    /* Modern Button Styling */
    .btn {
        border-radius: 8px;
        padding: 0.6rem 1.2rem;
        font-weight: 500;
    }

    .btn-primary {
        background: linear-gradient(45deg, #0d6efd, #0a58ca);
        border: none;
        box-shadow: 0 2px 10px rgba(13, 110, 253, 0.2);
    }

    .btn-warning {
        background: linear-gradient(45deg, #ffc107, #ffb300);
        border: none;
        color: #000;
        box-shadow: 0 2px 10px rgba(255, 193, 7, 0.2);
    }

    .btn-danger {
        background: linear-gradient(45deg, #dc3545, #c82333);
        border: none;
        box-shadow: 0 2px 10px rgba(220, 53, 69, 0.2);
    }

    /* Modern Modal Styling */
    .modal-content {
        border: none;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.5rem;
    }

    /* Form Control Styling */
    .form-control {
        border-radius: 8px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        padding: 0.8rem 1rem;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        border-color: #0d6efd;
    }

    /* Map Styling */
    #map, [id^="editMap"] {
        height: 300px;
        width: 100%;
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .leaflet-container {
        z-index: 1;
        border-radius: 12px;
    }

    /* Image Preview Styling */
    .image-preview {
        position: relative;
        margin-bottom: 1.5rem;
        border: 2px dashed rgba(0, 0, 0, 0.1);
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    .image-preview.dragover {
        border-color: #0d6efd;
        background-color: #e9ecef;
    }
    
    .image-preview img {
        max-width: 100%;
        max-height: 200px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    
    .image-preview .remove-image {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(255, 255, 255, 0.95);
        border: none;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 2;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }
    
    .image-preview .upload-placeholder {
        color: #6c757d;
        font-size: 0.95rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.8rem;
    }
    
    .image-preview .upload-placeholder i {
        font-size: 3rem;
        color: #adb5bd;
    }

    /* Venue Card Specific Styling */
    .venue-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    .card-title {
        font-weight: 600;
        margin-bottom: 1rem;
        color: #2c3e50;
    }

    .card-text {
        color: #6c757d;
        font-size: 0.95rem;
        line-height: 1.6;
    }

    .btn-group {
        gap: 0.5rem;
    }

    /* Loading State */
    .loading {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        background: rgba(255, 255, 255, 0.95);
        padding: 1rem 2rem;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        display: none;
    }

    /* Error Message Styling */
    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: none;
        padding: 0.5rem;
        background: rgba(220, 53, 69, 0.1);
        border-radius: 6px;
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
                                <?php if (!empty($venue['image_path'])): ?>
                                    <img src="<?= base_url('images/serve/' . $venue['image_path']) ?>" class="venue-image" alt="<?= esc($venue['venue_name']) ?>">
                                <?php else: ?>
                                    <div class="venue-image bg-light d-flex align-items-center justify-content-center">
                                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                <?php endif; ?>
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
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editVenueModal<?= $venue['id'] ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
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
                                        <form action="<?= base_url('venue/edit/' . $venue['id']) ?>" method="POST" id="editVenueForm<?= $venue['id'] ?>" enctype="multipart/form-data">
                                            <input type="hidden" name="image_id" value="<?= $venue['image_id'] ?? '' ?>">
                                            <input type="hidden" name="remove_image" value="0" id="removeImage<?= $venue['id'] ?>">
                                            <div class="mb-3">
                                                <label for="editVenueImage<?= $venue['id'] ?>" class="form-label">Venue Image</label>
                                                <div class="image-preview <?= !empty($venue['image_path']) ? 'has-image' : '' ?>" id="editImagePreview<?= $venue['id'] ?>">
                                                    <?php if (!empty($venue['image_path'])): ?>
                                                        <img src="<?= base_url('images/serve/' . $venue['image_path']) ?>" alt="Venue Image">
                                                        <button type="button" class="remove-image" onclick="removeEditImage(<?= $venue['id'] ?>, <?= $venue['image_id'] ?>)">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    <?php else: ?>
                                                        <div class="upload-placeholder">
                                                            <i class="bi bi-cloud-upload"></i>
                                                            <p>Click to upload venue image</p>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <input type="file" class="form-control" id="editVenueImage<?= $venue['id'] ?>" name="venue_image" accept="image/*" onchange="previewEditImage(this, <?= $venue['id'] ?>)">
                                            </div>
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
                <form action="<?= base_url('venue/add') ?>" method="POST" id="venueForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="venueImage" class="form-label">Venue Image</label>
                        <div class="image-preview" id="imagePreview">
                            <div class="upload-placeholder">
                                <i class="bi bi-cloud-upload"></i>
                                <p>Click to upload venue image</p>
                            </div>
                        </div>
                        <input type="file" class="form-control" id="venueImage" name="venue_image" accept="image/*" onchange="previewImage(this)">
                    </div>
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

// Image preview functions
function previewImage(input) {
    const preview = document.getElementById('imagePreview');
    handleImagePreview(input, preview);
}

function previewEditImage(input, venueId) {
    const preview = document.getElementById(`editImagePreview${venueId}`);
    handleImagePreview(input, preview);
}

function handleImagePreview(input, preview) {
    preview.innerHTML = '';
    preview.classList.remove('has-error', 'uploading');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!validTypes.includes(file.type)) {
            showImageError(preview, 'Please select a valid image file (JPEG, PNG, or WebP)');
            input.value = '';
            return;
        }
        
        // Validate file size (2MB max)
        if (file.size > 2 * 1024 * 1024) {
            showImageError(preview, 'Image size should not exceed 2MB');
            input.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.classList.add('has-image');
            const img = document.createElement('img');
            img.src = e.target.result;
            preview.appendChild(img);
            
            const removeBtn = document.createElement('button');
            removeBtn.className = 'remove-image';
            removeBtn.innerHTML = '<i class="bi bi-x-lg"></i>';
            removeBtn.onclick = function(e) {
                e.preventDefault();
                e.stopPropagation();
                input.value = '';
                resetImagePreview(preview);
            };
            preview.appendChild(removeBtn);

            // Add hidden input for remove_image
            const removeImageInput = document.createElement('input');
            removeImageInput.type = 'hidden';
            removeImageInput.name = 'remove_image';
            removeImageInput.value = '0';
            preview.appendChild(removeImageInput);
        }
        reader.readAsDataURL(file);
    }
}

function showImageError(preview, message) {
    preview.classList.add('has-error');
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    preview.appendChild(errorDiv);
}

function resetImagePreview(preview) {
    preview.innerHTML = `
        <div class="upload-placeholder">
            <i class="bi bi-cloud-upload"></i>
            <p>Click to upload venue image</p>
            <small>JPEG, PNG, or WebP (max 2MB)</small>
        </div>
    `;
    preview.classList.remove('has-image', 'has-error', 'uploading');
}

function removeEditImage(venueId, imageId) {
    const preview = document.getElementById(`editImagePreview${venueId}`);
    const input = document.getElementById(`editVenueImage${venueId}`);
    const removeImageInput = document.getElementById(`removeImage${venueId}`);
    const imageIdInput = document.querySelector(`#editVenueForm${venueId} input[name="image_id"]`);
    
    // Set remove_image to 1
    if (removeImageInput) {
        removeImageInput.value = '1';
    }
    
    // Set image_id
    if (imageIdInput) {
        imageIdInput.value = imageId;
    }
    
    // Clear the file input and reset preview
    input.value = '';
    resetImagePreview(preview);
    
    // Log the form data for debugging
    const form = document.getElementById(`editVenueForm${venueId}`);
    const formData = new FormData(form);
    console.log('Form data after removal:', {
        remove_image: formData.get('remove_image'),
        image_id: formData.get('image_id')
    });
}

// Add drag and drop support
function setupDragAndDrop(preview, input) {
    preview.addEventListener('dragover', (e) => {
        e.preventDefault();
        preview.classList.add('dragover');
    });
    
    preview.addEventListener('dragleave', () => {
        preview.classList.remove('dragover');
    });
    
    preview.addEventListener('drop', (e) => {
        e.preventDefault();
        preview.classList.remove('dragover');
        
        if (e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            handleImagePreview(input, preview);
        }
    });
    
    preview.addEventListener('click', (e) => {
        // Don't open file picker if clicking the remove button
        if (e.target.closest('.remove-image')) {
            return;
        }
        if (!preview.classList.contains('has-image')) {
            input.click();
        }
    });
}

// Initialize drag and drop for all image previews
document.addEventListener('DOMContentLoaded', function() {
    const addPreview = document.getElementById('imagePreview');
    const addInput = document.getElementById('venueImage');
    setupDragAndDrop(addPreview, addInput);
    
    <?php foreach ($venues as $venue): ?>
    const editPreview<?= $venue['id'] ?> = document.getElementById(`editImagePreview<?= $venue['id'] ?>`);
    const editInput<?= $venue['id'] ?> = document.getElementById(`editVenueImage<?= $venue['id'] ?>`);
    setupDragAndDrop(editPreview<?= $venue['id'] ?>, editInput<?= $venue['id'] ?>);
    <?php endforeach; ?>
});

// Reset form
function resetForm() {
    document.getElementById('venueForm').reset();
    document.getElementById('lat').value = '';
    document.getElementById('lon').value = '';
    resetImagePreview(document.getElementById('imagePreview'));
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
