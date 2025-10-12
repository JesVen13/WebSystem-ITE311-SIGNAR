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
      <?php $session = session(); $isLoggedIn = (bool) $session->get('isLoggedIn'); $role = (string) ($session->get('role') ?? ''); ?>
      <a class="navbar-brand" href="<?= base_url('/') ?>">ITE311</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link <?= (uri_string() == '' ? 'active' : '') ?>" href="<?= base_url('/') ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'about' ? 'active' : '') ?>" href="<?= base_url('about') ?>">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= (uri_string() == 'contact' ? 'active' : '') ?>" href="<?= base_url('contact') ?>">Contact</a>
          </li>
          <?php if ($isLoggedIn): ?>
            <li class="nav-item">
              <a class="nav-link <?= (uri_string() == 'dashboard' ? 'active' : '') ?>" href="<?= base_url('dashboard') ?>">Dashboard</a>
            </li>
            <?php if ($role === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="#">User Management</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Reports</a></li>
            <?php elseif ($role === 'teacher'): ?>
              <li class="nav-item"><a class="nav-link" href="#">My Courses</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Gradebook</a></li>
            <?php elseif ($role === 'student'): ?>
              <li class="nav-item"><a class="nav-link" href="#">My Classes</a></li>
              <li class="nav-item"><a class="nav-link" href="#">Assignments</a></li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav ms-auto">
          <?php if ($isLoggedIn): ?>
            <li class="nav-item"><span class="navbar-text text-white me-3">Role: <?= esc($role) ?></span></li>
            <li class="nav-item"><a class="btn btn-outline-light" href="<?= base_url('logout') ?>">Logout</a></li>
          <?php else: ?>
            <li class="nav-item"><a class="btn btn-outline-light" href="<?= base_url('login') ?>">Login</a></li>
            <li class="nav-item ms-2"><a class="btn btn-primary" href="<?= base_url('register') ?>">Register</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container mt-5">
    <?= $this->renderSection('content') ?>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
