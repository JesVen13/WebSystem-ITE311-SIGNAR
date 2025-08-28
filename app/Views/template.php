<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Lab 3 - ROUTING AND MVC STRUCTURE</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="<?= base_url('MyHome') ?>">Signar vs Laboratories</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link <?= (uri_string() == '' ? 'active' : '') ?>" href="<?= base_url('/') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'about' ? 'active' : '') ?>" href="<?= base_url('about') ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'contact' ? 'active' : '') ?>" href="<?= base_url('contact') ?>">Contact</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- MAIN CONTENT -->
  <div class="container mt-5">
    <?= $this->renderSection('content') ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
