<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token }}">
    <title>
    <?= $this->renderSection('title') ?>
    </title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Axios -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('css/style.css') ?>">
    <?= $this->renderSection('local_css') ?>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
      <div class="sidebar-header text-info">
         <i class="bi bi-palette"></i> ArtScene
         <span class="close-btn" onclick="toggleSidebar()"><i class="bi bi-x"></i></span>
      </div>
 
          <a href="<?= site_url('/talents') ?>"><i class="bi bi-house-door"></i> Home</a>
          <a href="<?= site_url('/talents/events') ?>"><i class="bi bi-calendar-event"></i> Events</a>
          <a href="<?= site_url('/profile') ?>"><i class="bi bi-gear"></i> Profile</a>
          <a href="<?= site_url('/logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a>
   </div>


    <!-- Main Content -->
    <div class="content container-md">
        <div class="toggle-block" id="toggleBlock"></div>
        <span class="toggle-btn" id="toggleBtn" onclick="toggleSidebar()"><i class="bi bi-list"></i></span>
        <?= $this->renderSection('content') ?>
    </div>


    <!-- Custom JS -->
    <script src="<?= base_url('js/script.js') ?>"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() { 
            <?php if(session()->getFlashdata('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= session()->getFlashdata('error'); ?>'
                });
            <?php endif; ?>
            
            <?php if(session()->getFlashdata('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= session()->getFlashdata('success'); ?>'
                });
            <?php endif; ?>
        });
    </script>
     <?= $this->renderSection('local_javascript') ?>

</body>
</html>
