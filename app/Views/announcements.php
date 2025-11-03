<?= $this->extend('template') ?>

<?= $this->section('content') ?>
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">Announcements</h1>
  </div>

  <?php if (empty($announcements)): ?>
    <div class="alert alert-info">No announcements yet.</div>
  <?php else: ?>
    <div class="row g-3">
      <?php foreach ($announcements as $announcement): ?>
        <div class="col-12">
          <div class="card shadow-sm border-0">
            <div class="card-body">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?= esc($announcement['title'] ?? 'Untitled') ?></h5>
                <small class="text-muted">
                  <?php $date = $announcement['created_at'] ?? null; ?>
                  <?= $date ? esc(date('M d, Y g:i A', strtotime((string) $date))) : '—' ?>
                </small>
              </div>
              <p class="mb-0 mt-2">
                <?= nl2br(esc($announcement['content'] ?? '')) ?>
              </p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?= $this->endSection() ?>


