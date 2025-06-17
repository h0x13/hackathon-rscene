<?= $this->extend('layouts/base') ?>

<?= $this->section('title') ?>
Talents - Home
<?= $this->endSection() ?>

<?= $this->section('local_css') ?>
    <style>
        .event-card {
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07);
            transition: box-shadow 0.2s;
        }
        .event-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.13);
        }
        .event-img {
            border-radius: 12px 12px 0 0;
            object-fit: cover;
            height: 180px;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto 32px auto;
        }
      </style>

<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="text-center mb-4">
    <h1 class="fw-bold mb-2">🎉 Your Local Events</h1>
    <p class="text-muted">Find and track concerts happening near you. Explore, attend, and enjoy the local music scene!</p>
</div>

  <div class="row g-4 mb-5 pe-0 pe-md-5">
    
  <div class="container d-flex align-items-center justify-content-between mt-5">
    <h4 class="mb-3"><i class="bi bi-calendar"></i>Your Upcoming Events</h4>
    <a href="<?= site_url('/talents/events') ?>" role="button" class="btn btn-sm btn-secondary mb-0"><i class="bi bi-arrow-left"></i> Back</a>
  </div>

    <?php if (!empty($events)): ?>
    <?php foreach ($events as $event): ?>
      <div class="col-md-4">
        <div class="card event-card">
          <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=400&q=80" class="card-img-top event-img" alt="Concert">
          <div class="card-body">
            <h5 class="card-title"><?= esc($event['event_name']) ?></h5>
            <p class="card-text text-muted mb-1">
              <i class="bi bi-geo-alt"></i>
              <?= esc($event['city']) ?>, <?= esc($event['country'] ?? '') ?>
            </p>
            <p class="card-text">
              <small class="text-muted">
                <i class="bi bi-calendar-event"></i>
                <?= date('F j, Y', strtotime($event['event_date'])) ?>
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
    <?php endforeach; ?>
  <?php else: ?>
    <div class="col-12">
      <div class="alert alert-info text-center">You have no events yet.</div>
    </div>
  <?php endif; ?>
</div>



<?= $this->endSection() ?>
