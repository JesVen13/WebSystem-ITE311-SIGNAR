<?= $this->extend('template') ?>

<?= $this->section('content') ?>
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0">Announcements</h1>
  </div>

  <?php if (empty($announcements)): ?>
    <div class="alert alert-info">No announcements yet.</div>
  <?php else: ?>
    <div class="list-group">
      <?php foreach ($announcements as $announcement): ?>
        <div class="list-group-item list-group-item-action">
          <div class="d-flex w-100 justify-content-between">
            <h5 class="mb-1"><?= esc($announcement['title'] ?? 'Untitled') ?></h5>
            <small class="text-muted">
              <?php $date = $announcement['created_at'] ?? null; ?>
              <?= $date ? esc(date('M d, Y g:i A', strtotime((string) $date))) : '—' ?>
            </small>
          </div>
          <p class="mb-1"><?= nl2br(esc($announcement['content'] ?? '')) ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
<?= $this->endSection() ?>


