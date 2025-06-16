<?= $this->extend('layouts/base') ?>

<?= $this->section('content') ?>
<h2>Available Venues in Region 8</h2>
<div id="venueMap" style="height: 500px;"></div>
<?= $this->endSection() ?>

<?= $this->section('local_javascript') ?>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>

</script>
<?= $this->endSection() ?>