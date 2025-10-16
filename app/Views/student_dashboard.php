<?= $this->extend('template') ?>

<?= $this->section('content') ?>
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0">Student Dashboard</h1>
      <small class="text-muted">Minimal and focused</small>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-md-6">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">Latest Announcements</div>
          <div class="fw-semibold mt-1">Visit the Announcements page to view updates.</div>
          <a href="<?= base_url('announcements') ?>" class="btn btn-sm btn-primary mt-3">Go to Announcements</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">My Enrollments</div>
          <div class="fw-semibold mt-1">—</div>
        </div>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>


