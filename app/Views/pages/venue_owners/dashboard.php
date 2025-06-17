<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
VenueConnect - Dashboard
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
        .container {
            position: relative;
            z-index: 1;
        }
        .header-section {
            margin-bottom: 2rem;
            animation: fadeInUp 0.8s ease-in-out;
        }
        .header-section h1 {
            font-weight: 700;
            font-size: 2rem;
            color: #2c3e50;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            display: inline-block;
        }
        .header-section h1::after {
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
        .card.bg-primary {
            background: linear-gradient(to right, #a777e3, #a777e3);
            color: white;
        }
        .card.bg-success {
            background: linear-gradient(to right, #28a745, #4caf50);
            color: white;
        }
        .card.bg-warning {
            background: linear-gradient(to right, #ffc107, #ffca2c);
            color: #2c3e50;
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        .card-text {
            font-size: 1.8rem;
            font-weight: 700;
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
        .table {
            border-radius: 10px;
            overflow: hidden;
            background: white;
        }
        .table th {
            background: linear-gradient(to right,#bdc0cb,#bdc0cb);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.75rem;
        }
        .table td {
            border: none;
            padding: 0.75rem;
            vertical-align: middle;
            color: #2c3e50;
        }
        .table tbody tr {
            transition: background 0.3s ease;
        }
        .table tbody tr:hover {
            background: rgba(110, 142, 251, 0.05);
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.5em 0.8em;
            border-radius: 5px;
            font-weight: 500;
        }
        .btn-info {
            background: #6e8efb;
            border: none;
            border-radius: 8px;
            padding: 0.4rem 0.8rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-info:hover {
            background: #a777e3;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(110, 142, 251, 0.3);
        }
        .btn-info::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: 0.5s;
        }
        .btn-info:hover::after {
            left: 100%;
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
<div class="container py-4">
    <div class="header-section">
        <h1>Dashboard</h1>
    </div>

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
                     <!-- Sample data for template/demo purposes -->
                     <tr>
                        <td>Wedding Reception</td>
                        <td>Jane Doe</td>
                        <td>2024-07-15</td>
                        <td><span class="badge bg-success">approved</span></td>
                        <td>
                           <a href="#" class="btn btn-sm btn-info">
                              <i class="bi bi-eye"></i> View
                           </a>
                        </td>
                     </tr>
                  </tbody>
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
