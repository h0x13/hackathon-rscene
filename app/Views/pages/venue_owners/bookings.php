<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Bookings<?= $this->endSection() ?>

<?php helper('form'); ?>

<?= $this->section('local_css') ?>
<style>
    .booking-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        padding: 20px;
        transition: transform 0.2s;
    }

    .booking-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .booking-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    .status-cancelled { background: #e2e3e5; color: #383d41; }

    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-bottom: 15px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.8rem;
        color: #666;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 0.9rem;
        color: #333;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }

    .btn-action {
        padding: 5px 15px;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-view {
        background: #007bff;
        color: white;
        border: none;
    }

    .btn-view:hover {
        background: #0056b3;
    }

    .btn-approve {
        background: #28a745;
        color: white;
        border: none;
    }

    .btn-approve:hover {
        background: #218838;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
        border: none;
    }

    .btn-reject:hover {
        background: #c82333;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
    }

    .btn-cancel:hover {
        background: #5a6268;
    }

    .filter-select {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 0.9rem;
        margin-bottom: 20px;
        width: 200px;
    }

    .modal-content {
        padding: 20px;
    }

    .modal-section {
        margin-bottom: 20px;
    }

    .modal-section h4 {
        color: #333;
        margin-bottom: 10px;
        font-size: 1.1rem;
    }

    .modal-detail {
        display: flex;
        margin-bottom: 10px;
    }

    .modal-label {
        width: 150px;
        color: #666;
        font-size: 0.9rem;
    }

    .modal-value {
        flex: 1;
        color: #333;
        font-size: 0.9rem;
    }

    .artist-info {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .artist-image {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
    }

    .artist-name {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
    }

    .venue-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 15px;
    }

    .venue-name {
        font-size: 1.1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 5px;
    }

    .venue-address {
        color: #666;
        font-size: 0.9rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">Bookings</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"> <?= session()->getFlashdata('success') ?> </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"> <?= session()->getFlashdata('error') ?> </div>
    <?php endif; ?>

    <form method="get" class="mb-3">
        <select class="filter-select" name="filter" onchange="this.form.submit()">
            <option value="all" <?= ($filter ?? 'all') === 'all' ? 'selected' : '' ?>>All Bookings</option>
            <option value="pending" <?= ($filter ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
            <option value="approved" <?= ($filter ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="rejected" <?= ($filter ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
            <option value="cancelled" <?= ($filter ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
        </select>
    </form>

    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">No bookings found.</div>
    <?php else: ?>
        <?php foreach ($bookings as $booking): ?>
            <div class="booking-card mb-4">
                <div class="booking-header">
                    <h3 class="booking-title"><?= esc($booking['event_name']) ?></h3>
                    <span class="status-badge status-<?= strtolower($booking['booking_status']) ?>">
                        <?= ucfirst($booking['booking_status']) ?>
                    </span>
                </div>
                <div class="booking-details">
                    <div class="detail-item">
                        <span class="detail-label">Artist</span>
                        <span class="detail-value"><?= esc($booking['artist_name'] ?? ($booking['first_name'] ?? '') . ' ' . ($booking['last_name'] ?? '')) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Date</span>
                        <span class="detail-value"><?= date('M d, Y', strtotime($booking['event_startdate'])) ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Time</span>
                        <span class="detail-value">
                            <?= date('h:i A', strtotime($booking['event_startdate'])) ?> -
                            <?= date('h:i A', strtotime($booking['event_enddate'])) ?>
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Venue</span>
                        <span class="detail-value"><?= esc($booking['venue_name'] ?? '') ?></span>
                    </div>
                </div>
                <div class="booking-actions">
                    <?= form_open('booking/update-status/' . $booking['id'], ['class' => 'd-inline']) ?>
                        <input type="hidden" name="status" value="approved">
                        <button type="submit" class="btn btn-success btn-sm">Accept</button>
                    <?= form_close() ?>
                    <?= form_open('booking/update-status/' . $booking['id'], ['class' => 'd-inline']) ?>
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    <?= form_close() ?>
                    <?php if ($booking['booking_status'] !== 'cancelled'): ?>
                        <?= form_open('booking/cancel/' . $booking['id'], ['class' => 'd-inline']) ?>
                            <button type="submit" class="btn btn-secondary btn-sm">Cancel</button>
                        <?= form_close() ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="modal-section">
                    <h4>Event Information</h4>
                    <div class="modal-detail">
                        <span class="modal-label">Event Name:</span>
                        <span class="modal-value" id="modalEventName"></span>
                    </div>
                    <div class="modal-detail">
                        <span class="modal-label">Date:</span>
                        <span class="modal-value" id="modalEventDate"></span>
                    </div>
                    <div class="modal-detail">
                        <span class="modal-label">Time:</span>
                        <span class="modal-value" id="modalEventTime"></span>
                    </div>
                    <div class="modal-detail">
                        <span class="modal-label">Description:</span>
                        <span class="modal-value" id="modalEventDescription"></span>
                    </div>
                </div>

                <div class="modal-section">
                    <h4>Artist Information</h4>
                    <div class="artist-info">
                        <img id="modalArtistImage" class="artist-image" src="" alt="Artist">
                        <div>
                            <div class="artist-name" id="modalArtistName"></div>
                            <div class="detail-value" id="modalArtistEmail"></div>
                        </div>
                    </div>
                </div>

                <div class="modal-section">
                    <h4>Venue Information</h4>
                    <div class="venue-info">
                        <div class="venue-name" id="modalVenueName"></div>
                        <div class="venue-address" id="modalVenueAddress"></div>
                    </div>
                </div>

                <div class="modal-section">
                    <h4>Booking Information</h4>
                    <div class="modal-detail">
                        <span class="modal-label">Status:</span>
                        <span class="modal-value" id="modalBookingStatus"></span>
                    </div>
                    <div class="modal-detail">
                        <span class="modal-label">Booked On:</span>
                        <span class="modal-value" id="modalBookedOn"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentFilter = 'all';
const bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));

function updateFilter(filter) {
    currentFilter = filter;
    loadBookings();
}

function loadBookings() {
    fetch(`<?= base_url('booking/list') ?>?filter=${currentFilter}`)
        .then(response => response.json())
        .then(data => {
            const bookingsList = document.getElementById('bookingsList');
            if (data.bookings.length === 0) {
                bookingsList.innerHTML = '<div class="alert alert-info">No bookings found.</div>';
                return;
            }

            bookingsList.innerHTML = data.bookings.map(booking => `
                <div class="booking-card">
                    <div class="booking-header">
                        <h3 class="booking-title">${booking.event_name}</h3>
                        <span class="status-badge status-${booking.booking_status.toLowerCase()}">
                            ${booking.booking_status.charAt(0).toUpperCase() + booking.booking_status.slice(1)}
                        </span>
                    </div>

                    <div class="booking-details">
                        <div class="detail-item">
                            <span class="detail-label">Artist</span>
                            <span class="detail-value">${booking.artist_name}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Date</span>
                            <span class="detail-value">${new Date(booking.event_startdate).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Time</span>
                            <span class="detail-value">
                                ${new Date(booking.event_startdate).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - 
                                ${new Date(booking.event_enddate).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}
                            </span>
                        </div>
                    </div>

                    <div class="booking-actions">
                        <button class="btn-action btn-view" onclick="viewBooking(${booking.id})">
                            View Details
                        </button>
                        ${booking.booking_status === 'pending' ? `
                            <button class="btn-action btn-approve" onclick="updateStatus(${booking.id}, 'approved')">
                                Approve
                            </button>
                            <button class="btn-action btn-reject" onclick="updateStatus(${booking.id}, 'rejected')">
                                Reject
                            </button>
                        ` : ''}
                        ${booking.booking_status !== 'cancelled' ? `
                            <button class="btn-action btn-cancel" onclick="cancelBooking(${booking.id})">
                                Cancel
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading bookings:', error);
            document.getElementById('bookingsList').innerHTML = 
                '<div class="alert alert-danger">Error loading bookings. Please try again.</div>';
        });
}

function viewBooking(id) {
    fetch(`<?= base_url('booking/view') ?>/${id}`)
        .then(response => response.json())
        .then(data => {
            const booking = data.booking;
            
            // Update modal content
            document.getElementById('modalEventName').textContent = booking.event_name;
            document.getElementById('modalEventDate').textContent = 
                new Date(booking.event_startdate).toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric' 
                });
            document.getElementById('modalEventTime').textContent = 
                `${new Date(booking.event_startdate).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })} - 
                 ${new Date(booking.event_enddate).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' })}`;
            document.getElementById('modalEventDescription').textContent = booking.event_description;
            
            document.getElementById('modalArtistImage').src = booking.artist_image || '<?= base_url('assets/images/default-avatar.png') ?>';
            document.getElementById('modalArtistName').textContent = booking.artist_name;
            document.getElementById('modalArtistEmail').textContent = booking.artist_email;
            
            document.getElementById('modalVenueName').textContent = booking.venue_name;
            document.getElementById('modalVenueAddress').textContent = 
                [booking.street, booking.barangay, booking.city, booking.zip_code].filter(Boolean).join(', ');
            
            document.getElementById('modalBookingStatus').textContent = 
                booking.booking_status.charAt(0).toUpperCase() + booking.booking_status.slice(1);
            document.getElementById('modalBookedOn').textContent = 
                new Date(booking.date_created).toLocaleDateString('en-US', { 
                    month: 'long', 
                    day: 'numeric', 
                    year: 'numeric',
                    hour: 'numeric',
                    minute: '2-digit'
                });

            bookingModal.show();
        })
        .catch(error => {
            console.error('Error loading booking details:', error);
            alert('Error loading booking details. Please try again.');
        });
}

function updateStatus(id, status) {
    if (!confirm(`Are you sure you want to ${status} this booking?`)) {
        return;
    }

    fetch(`<?= base_url('booking/update-status') ?>/${id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        loadBookings();
    })
    .catch(error => {
        console.error('Error updating booking status:', error);
        alert('Error updating booking status. Please try again.');
    });
}

function cancelBooking(id) {
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }

    fetch(`<?= base_url('booking/cancel') ?>/${id}`, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.error) {
            throw new Error(data.error);
        }
        loadBookings();
    })
    .catch(error => {
        console.error('Error cancelling booking:', error);
        alert('Error cancelling booking. Please try again.');
    });
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Set initial filter
    const filterSelect = document.getElementById('filterSelect');
    filterSelect.value = currentFilter;
    
    // Load initial bookings
    loadBookings();
});
</script>

<?= $this->endSection() ?> 