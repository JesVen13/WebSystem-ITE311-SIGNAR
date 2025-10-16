<?= $this->extend('template') ?>

<?= $this->section('content') ?>
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h1 class="h3 mb-0">Teacher Dashboard</h1>
      <small class="text-muted">Minimalist overview</small>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">My Courses</div>
          <div class="display-6 fw-semibold">—</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">Upcoming Lessons</div>
          <div class="display-6 fw-semibold">—</div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">Pending Submissions</div>
          <div class="display-6 fw-semibold">—</div>
        </div>
      </div>
    </div>
  </div>

<?= $this->endSection() ?>


