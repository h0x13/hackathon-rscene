<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
VenueConnect - All Upcoming Events
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
        }
        .header-section {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease-in-out;
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
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
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
            background: linear-gradient(to right, #5a75e8, #8e5ed0);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(110, 142, 251, 0.5);
        }
        .btn-secondary::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        .btn-secondary:hover::after {
            left: 100%;
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
        .alert-info {
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            font-size: 0.95rem;
            color: #2c3e50;
            animation: fadeIn 0.5s ease-in-out;
        }

        .img-fluid.rounded {
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

    </style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container py-5">
    <div class="header-section">
        <h1>🎵 All Upcoming Events</h1>
        <p class="  ">Discover and explore concerts and events happening near you!</p>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-4 pe-5">
        <h4 class="section-title"><i class="bi bi-calendar me-2"></i>All Upcoming Events</h4>
        <a href="<?= site_url('/talents') ?>" role="button" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>

    <div class="row g-4 mb-5">
        <?php if (!empty($events)): ?>
            <?php foreach ($events as $event): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="event-img" alt="Event">
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
                        </div>
                    </div>
                </div>

                <!-- Event Details Modal -->
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
                                        <h6 class="fw-bold mb-3">Event Details</h6>
                                        <p><strong>Date:</strong> <?= date('F j, Y', strtotime($event['event_startdate'])) ?></p>
                                        <p><strong>Location:</strong> <?= esc($event['city']) ?>, <?= esc($event['country'] ?? '') ?></p>
                                        <p><strong>Description:</strong> <?= esc($event['event_description'] ?? 'No description available.') ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">No events available at the moment.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
