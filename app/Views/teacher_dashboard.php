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
          <div class="display-6 fw-semibold"><?= (int)($courseCount ?? 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">Upcoming Lessons</div>
          <div class="display-6 fw-semibold"><?= (int)($lessonsCount ?? 0) ?></div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-4">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">Pending Submissions</div>
          <div class="display-6 fw-semibold"><?= (int)($pendingSubmissions ?? 0) ?></div>
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($myCourses)): ?>
  <div class="card mt-3 shadow-sm border-0">
    <div class="card-body">
      <h5 class="card-title mb-2">Your Courses</h5>
      <ul class="mb-0">
        <?php foreach ($myCourses as $c): ?>
          <li class="small"><strong><?= esc($c['title']) ?></strong> <?= !empty($c['description']) ? '— ' . esc($c['description']) : '' ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  </div>
  <?php endif; ?>

<?= $this->endSection() ?>


