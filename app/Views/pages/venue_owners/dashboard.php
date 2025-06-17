<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>Dashboard<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <h1 class="h3 mb-4">Dashboard</h1>

    <div class="row">
        <!-- Statistics Cards -->
        <div class="col-md-4 mb-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total Venues</h5>
                    <h2 class="card-text" id="totalVenues">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Active Bookings</h5>
                    <h2 class="card-text" id="activeBookings">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h5 class="card-title">Pending Bookings</h5>
                    <h2 class="card-text" id="pendingBookings">0</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="card mt-4">
        <div class="card-header">
            <h5 class="card-title mb-0">Recent Bookings</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Organizer</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="recentBookings">
                        <!-- Recent bookings will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('local_javascript') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
});

function loadDashboardData() {
    // Load total venues
    axios.get('<?= base_url('venue/list') ?>')
        .then(response => {
            document.getElementById('totalVenues').textContent = response.data.venues.length;
        })
        .catch(error => {
            console.error('Error loading venues:', error);
        });

    // Load bookings
    axios.get('<?= base_url('booking/list') ?>')
        .then(response => {
            const bookings = response.data.bookings;
            
            // Count active and pending bookings
            const activeBookings = bookings.filter(b => b.status === 'approved').length;
            const pendingBookings = bookings.filter(b => b.status === 'pending').length;
            
            document.getElementById('activeBookings').textContent = activeBookings;
            document.getElementById('pendingBookings').textContent = pendingBookings;

            // Display recent bookings (last 5)
            const recentBookings = bookings.slice(0, 5);
            const container = document.getElementById('recentBookings');
            container.innerHTML = '';

            recentBookings.forEach(booking => {
                container.innerHTML += `
                    <tr>
                        <td>${booking.event_name}</td>
                        <td>${booking.organizer_first_name} ${booking.organizer_last_name}</td>
                        <td>${new Date(booking.event_date).toLocaleDateString()}</td>
                        <td>
                            <span class="badge bg-${getStatusColor(booking.status)}">
                                ${booking.status}
                            </span>
                        </td>
                        <td>
                            <a href="<?= base_url('booking/view/') ?>${booking.id}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> View
                            </a>
                        </td>
                    </tr>
                `;
            });
        })
        .catch(error => {
            console.error('Error loading bookings:', error);
        });
}

function getStatusColor(status) {
    switch(status.toLowerCase()) {
        case 'pending': return 'warning';
        case 'approved': return 'success';
        case 'rejected': return 'danger';
        default: return 'secondary';
    }
}
</script>
<?= $this->endSection() ?>
