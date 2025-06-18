<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Bookings<?= $this->endSection() ?>

<?php helper('booking'); ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">Event Bookings</h1>
    
    <div class="card mb-4">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group" role="group" id="filterButtons">
                    <button type="button" class="btn btn-outline-primary active" data-filter="all">All</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="pending">Pending</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="approved">Approved</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="rejected">Rejected</button>
                    <button type="button" class="btn btn-outline-primary" data-filter="cancelled">Cancelled</button>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Artist</th>
                            <th>Date & Time</th>
                            <th>Venue</th>
                            <th>Price Range</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <!-- Bookings will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Booking Details Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Booking Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Event Information</h6>
                        <p><strong>Name:</strong> <span id="eventName"></span></p>
                        <p><strong>Description:</strong> <span id="eventDescription"></span></p>
                        <p><strong>Date:</strong> <span id="eventDate"></span></p>
                        <p><strong>Status:</strong> <span id="eventStatus"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Artist Information</h6>
                        <p><strong>Name:</strong> <span id="artistName"></span></p>
                        <p><strong>Price Range:</strong> <span id="priceRange"></span></p>
                        <p><strong>Payment Option:</strong> <span id="paymentOption"></span></p>
                        <p><strong>Hours:</strong> <span id="hours"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12">
                        <h6>Venue Information</h6>
                        <p><strong>Name:</strong> <span id="venueName"></span></p>
                        <p><strong>Address:</strong> <span id="venueAddress"></span></p>
                        <p><strong>Description:</strong> <span id="venueDescription"></span></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="bookingActions">
                <!-- Action buttons will be dynamically added here -->
            </div>
        </div>
    </div>
</div>

<?= $this->section('local_js') ?>
<script>
let currentFilter = 'all';

function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'pending': return 'warning';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        case 'cancelled': return 'secondary';
        default: return 'primary';
    }
}

function updateFilterButtons(activeFilter) {
    const buttons = document.querySelectorAll('#filterButtons .btn');
    buttons.forEach(btn => {
        if (btn.dataset.filter === activeFilter) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    });
}

function formatDateTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    return date.toLocaleString();
}

function formatDate(dateTimeStr) {
    const date = new Date(dateTimeStr);
    return date.toLocaleDateString();
}

function loadBookings() {
    const tbody = document.getElementById('bookingsTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="7" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </td>
        </tr>
    `;

    fetch(`<?= base_url('booking/list') ?>?filter=${currentFilter}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.bookings) {
                throw new Error('No bookings data received');
            }
            
            const bookings = data.bookings;
            tbody.innerHTML = '';
            
            if (bookings.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center">No bookings found</td>
                    </tr>
                `;
                return;
            }
            
            bookings.forEach(booking => {
                const statusClass = getStatusColor(booking.booking_status);
                
                tbody.innerHTML += `
                    <tr>
                        <td>${booking.event_name}</td>
                        <td>${booking.artist_name}</td>
                        <td>${formatDateTime(booking.date_created)}</td>
                        <td>${booking.venue_name}</td>
                        <td>${booking.price_range}</td>
                        <td><span class="badge bg-${statusClass}">${booking.booking_status}</span></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info" onclick="viewBooking(${booking.id})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                ${booking.booking_status === 'pending' ? `
                                    <button class="btn btn-sm btn-success" onclick="updateBookingStatus(${booking.id}, 'approved')">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="updateBookingStatus(${booking.id}, 'rejected')">
                                        <i class="bi bi-x"></i> Reject
                                    </button>
                                ` : ''}
                                ${['pending', 'approved'].includes(booking.booking_status) ? `
                                    <button class="btn btn-sm btn-warning" onclick="cancelBooking(${booking.id})">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                ` : ''}
                            </div>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error loading bookings:', error);
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center text-danger">
                        Failed to load bookings. Please try again.
                    </td>
                </tr>
            `;
        });
}

function viewBooking(id) {
    fetch(`<?= base_url('booking/view') ?>/${id}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (!data.booking) {
                throw new Error('No booking data received');
            }
            
            const booking = data.booking;
            
            // Update modal content
            document.getElementById('eventName').textContent = booking.event_name;
            document.getElementById('eventDescription').textContent = booking.event_description;
            document.getElementById('eventDate').textContent = formatDate(booking.event_startdate);
            document.getElementById('eventStatus').innerHTML = `<span class="badge bg-${getStatusColor(booking.event_status)}">${booking.event_status}</span>`;
            
            document.getElementById('artistName').textContent = booking.artist_name;
            document.getElementById('priceRange').textContent = booking.price_range;
            document.getElementById('paymentOption').textContent = booking.payment_option;
            document.getElementById('hours').textContent = booking.hours;
            
            document.getElementById('venueName').textContent = booking.venue_name;
            document.getElementById('venueAddress').textContent = [
                booking.street,
                booking.barangay,
                booking.city,
                booking.zip_code
            ].filter(Boolean).join(', ');
            document.getElementById('venueDescription').textContent = booking.venue_description;
            
            // Update action buttons
            const actionsDiv = document.getElementById('bookingActions');
            actionsDiv.innerHTML = '';
            
            if (booking.booking_status === 'pending') {
                actionsDiv.innerHTML += `
                    <button type="button" class="btn btn-success" onclick="updateBookingStatus(${booking.id}, 'approved')">
                        <i class="bi bi-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger" onclick="updateBookingStatus(${booking.id}, 'rejected')">
                        <i class="bi bi-x"></i> Reject
                    </button>
                `;
            }
            
            if (['pending', 'approved'].includes(booking.booking_status)) {
                actionsDiv.innerHTML += `
                    <button type="button" class="btn btn-warning" onclick="cancelBooking(${booking.id})">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                `;
            }
            
            // Show modal
            new bootstrap.Modal(document.getElementById('bookingModal')).show();
        })
        .catch(error => {
            console.error('Error loading booking details:', error);
            alert('Failed to load booking details. Please try again.');
        });
}

function updateBookingStatus(id, status) {
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
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to update booking status');
            });
        }
        return response.json();
    })
    .then(data => {
        alert(data.message || 'Booking status updated successfully');
        bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
        loadBookings();
    })
    .catch(error => {
        console.error('Error updating booking status:', error);
        alert(error.message || 'Failed to update booking status');
    });
}

function cancelBooking(id) {
    if (!confirm('Are you sure you want to cancel this booking?')) {
        return;
    }

    fetch(`<?= base_url('booking/cancel') ?>/${id}`, {
        method: 'POST'
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to cancel booking');
            });
        }
        return response.json();
    })
    .then(data => {
        alert(data.message || 'Booking cancelled successfully');
        bootstrap.Modal.getInstance(document.getElementById('bookingModal')).hide();
        loadBookings();
    })
    .catch(error => {
        console.error('Error cancelling booking:', error);
        alert(error.message || 'Failed to cancel booking');
    });
}

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Add click event listeners to filter buttons
    document.querySelectorAll('#filterButtons .btn').forEach(button => {
        button.addEventListener('click', function() {
            currentFilter = this.dataset.filter;
            updateFilterButtons(currentFilter);
            loadBookings();
        });
    });

    // Load initial bookings
    loadBookings();
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?> 