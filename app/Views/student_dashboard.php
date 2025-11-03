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
          <?php if (!empty($latestAnnouncements)): ?>
            <ul class="mt-2 mb-0">
              <?php foreach ($latestAnnouncements as $a): ?>
                <li class="small"><strong><?= esc($a['title']) ?></strong> <span class="text-muted">(<?= date('M d', strtotime($a['created_at'])) ?>)</span></li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <div class="fw-semibold mt-1">No announcements yet.</div>
          <?php endif; ?>
          <a href="<?= base_url('announcements') ?>" class="btn btn-sm btn-primary mt-3">Go to Announcements</a>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <div class="card shadow-sm border-0">
        <div class="card-body">
          <div class="text-muted">My Enrollments</div>
          <div class="fw-semibold mt-1">You are enrolled in <?= (int)($enrollmentCount ?? 0) ?> course(s).</div>
          <?php if (!empty($myCourses)): ?>
            <ul class="mt-2 mb-0">
              <?php foreach ($myCourses as $c): ?>
                <li class="small"><?= esc($c['title']) ?></li>
              <?php endforeach; ?>
            </ul>
          <?php endif; ?>
          <div class="text-muted mt-3">Available quizzes across your courses: <strong><?= (int)($myQuizzesCount ?? 0) ?></strong></div>
        </div>
      </div>
    </div>
  </div>
<?= $this->endSection() ?>


