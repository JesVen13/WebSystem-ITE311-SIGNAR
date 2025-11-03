<?= $this->extend('template') ?>

<?= $this->section('content') ?>
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item">
        <a href="<?= base_url('student/dashboard') ?>" class="text-decoration-none text-primary">Home</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">Announcements</li>
    </ol>
  </nav>

  <!-- Page Header -->
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">Announcements</h1>
    <small class="text-muted">Stay updated with the latest news</small>
  </div>

  <!-- Announcements List -->
  <?php if (empty($announcements)): ?>
    <div class="alert alert-info shadow-sm">No announcements yet.</div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($announcements as $announcement): ?>
        <div class="col-12">
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0"><?= esc($announcement['title'] ?? 'Untitled') ?></h5>
                <small class="text-muted">
                  <?= isset($announcement['created_at']) 
                      ? date('M d, Y g:i A', strtotime($announcement['created_at'])) 
                      : '—' ?>
                </small>
              </div>
              <p class="mb-0"><?= nl2br(esc($announcement['content'] ?? '')) ?></p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?= $this->endSection() ?>
