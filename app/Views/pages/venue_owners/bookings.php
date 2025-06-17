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
                            <th>Booker</th>
                            <th>Date & Time</th>
                            <th>Venue</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment</th>
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
                        <p><strong>Time:</strong> <span id="eventTime"></span></p>
                        <p><strong>Status:</strong> <span id="eventStatus"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Booker Information</h6>
                        <p><strong>Name:</strong> <span id="bookerName"></span></p>
                        <p><strong>Email:</strong> <span id="bookerEmail"></span></p>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6>Venue Information</h6>
                        <p><strong>Name:</strong> <span id="venueName"></span></p>
                        <p><strong>Address:</strong> <span id="venueAddress"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Payment Information</h6>
                        <p><strong>Total Amount:</strong> <span id="totalAmount"></span></p>
                        <p><strong>Payment Status:</strong> <span id="paymentStatus"></span></p>
                        <div id="paymentHistory">
                            <h6>Payment History</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Amount</th>
                                            <th>Method</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paymentHistoryBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="bookingActions">
                <!-- Action buttons will be dynamically added here -->
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Payment Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="paymentForm">
                    <input type="hidden" id="paymentBookingId">
                    <div class="mb-3">
                        <label class="form-label">Payment Status</label>
                        <select class="form-select" id="paymentStatus" required>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <select class="form-select" id="paymentMethod" required>
                            <option value="cash">Cash</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="credit_card">Credit Card</option>
                            <option value="gcash">GCash</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Transaction ID</label>
                        <input type="text" class="form-control" id="transactionId">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="updatePayment()">Update Payment</button>
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

function getPaymentStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'pending': return 'warning';
        case 'completed': return 'success';
        case 'failed': return 'danger';
        case 'refunded': return 'info';
        default: return 'secondary';
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

function formatTime(dateTimeStr) {
    const date = new Date(dateTimeStr);
    return date.toLocaleTimeString();
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-PH', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

function loadBookings() {
    const tbody = document.getElementById('bookingsTableBody');
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="text-center">
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
                        <td colspan="8" class="text-center">No bookings found</td>
                    </tr>
                `;
                return;
            }
            
            bookings.forEach(booking => {
                const statusClass = getStatusColor(booking.status);
                const paymentStatusClass = getPaymentStatusColor(booking.payment_status);
                
                tbody.innerHTML += `
                    <tr>
                        <td>${booking.event_name}</td>
                        <td>${booking.booker_first_name} ${booking.booker_last_name}</td>
                        <td>
                            ${formatDate(booking.booking_date)}<br>
                            ${formatTime(booking.start_time)} - ${formatTime(booking.end_time)}
                        </td>
                        <td>${booking.venue_name}</td>
                        <td>${formatCurrency(booking.total_amount)}</td>
                        <td><span class="badge bg-${statusClass}">${booking.status}</span></td>
                        <td><span class="badge bg-${paymentStatusClass}">${booking.payment_status}</span></td>
                        <td>
                            <div class="btn-group" role="group">
                                <button class="btn btn-sm btn-info" onclick="viewBooking(${booking.id})">
                                    <i class="bi bi-eye"></i> View
                                </button>
                                ${booking.status === 'pending' ? `
                                    <button class="btn btn-sm btn-success" onclick="updateBookingStatus(${booking.id}, 'approved')">
                                        <i class="bi bi-check"></i> Approve
                                    </button>
                                    <button class="btn btn-sm btn-danger" onclick="updateBookingStatus(${booking.id}, 'rejected')">
                                        <i class="bi bi-x"></i> Reject
                                    </button>
                                ` : ''}
                                ${['pending', 'approved'].includes(booking.status) ? `
                                    <button class="btn btn-sm btn-warning" onclick="cancelBooking(${booking.id})">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                ` : ''}
                                <button class="btn btn-sm btn-primary" onclick="showPaymentModal(${booking.id})">
                                    <i class="bi bi-credit-card"></i> Payment
                                </button>
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
                    <td colspan="8" class="text-center text-danger">
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
            document.getElementById('eventDate').textContent = formatDate(booking.booking_date);
            document.getElementById('eventTime').textContent = `${formatTime(booking.start_time)} - ${formatTime(booking.end_time)}`;
            document.getElementById('eventStatus').innerHTML = `<span class="badge bg-${getStatusColor(booking.status)}">${booking.status}</span>`;
            document.getElementById('bookerName').textContent = `${booking.booker_first_name} ${booking.booker_last_name}`;
            document.getElementById('bookerEmail').textContent = booking.booker_email;
            document.getElementById('venueName').textContent = booking.venue_name;
            document.getElementById('venueAddress').textContent = [
                booking.street_address,
                booking.barangay,
                booking.city,
                booking.country,
                booking.zip_code
            ].filter(Boolean).join(', ');
            document.getElementById('totalAmount').textContent = formatCurrency(booking.total_amount);
            document.getElementById('paymentStatus').innerHTML = `<span class="badge bg-${getPaymentStatusColor(booking.payment_status)}">${booking.payment_status}</span>`;

            // Update payment history
            const paymentHistoryBody = document.getElementById('paymentHistoryBody');
            paymentHistoryBody.innerHTML = '';
            if (booking.payments && booking.payments.length > 0) {
                booking.payments.forEach(payment => {
                    paymentHistoryBody.innerHTML += `
                        <tr>
                            <td>${formatDateTime(payment.payment_date)}</td>
                            <td>${formatCurrency(payment.amount)}</td>
                            <td>${payment.payment_method}</td>
                            <td><span class="badge bg-${getPaymentStatusColor(payment.status)}">${payment.status}</span></td>
                        </tr>
                    `;
                });
            } else {
                paymentHistoryBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center">No payment history</td>
                    </tr>
                `;
            }
            
            // Update action buttons
            const actionsDiv = document.getElementById('bookingActions');
            actionsDiv.innerHTML = '';
            
            if (booking.status === 'pending') {
                actionsDiv.innerHTML += `
                    <button type="button" class="btn btn-success" onclick="updateBookingStatus(${booking.id}, 'approved')">
                        <i class="bi bi-check"></i> Approve
                    </button>
                    <button type="button" class="btn btn-danger" onclick="updateBookingStatus(${booking.id}, 'rejected')">
                        <i class="bi bi-x"></i> Reject
                    </button>
                `;
            }
            
            if (['pending', 'approved'].includes(booking.status)) {
                actionsDiv.innerHTML += `
                    <button type="button" class="btn btn-warning" onclick="cancelBooking(${booking.id})">
                        <i class="bi bi-x-circle"></i> Cancel
                    </button>
                `;
            }

            actionsDiv.innerHTML += `
                <button type="button" class="btn btn-primary" onclick="showPaymentModal(${booking.id})">
                    <i class="bi bi-credit-card"></i> Update Payment
                </button>
            `;
            
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

function showPaymentModal(bookingId) {
    document.getElementById('paymentBookingId').value = bookingId;
    new bootstrap.Modal(document.getElementById('paymentModal')).show();
}

function updatePayment() {
    const bookingId = document.getElementById('paymentBookingId').value;
    const status = document.getElementById('paymentStatus').value;
    const method = document.getElementById('paymentMethod').value;
    const transactionId = document.getElementById('transactionId').value;

    fetch(`<?= base_url('booking/update-payment') ?>/${bookingId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            status,
            payment_method: method,
            transaction_id: transactionId
        })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Failed to update payment status');
            });
        }
        return response.json();
    })
    .then(data => {
        alert(data.message || 'Payment status updated successfully');
        bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
        loadBookings();
    })
    .catch(error => {
        console.error('Error updating payment status:', error);
        alert(error.message || 'Failed to update payment status');
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