<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h2 class="mb-0">Create Course</h2>
        <a href="<?= base_url('teacher/dashboard') ?>" class="btn btn-outline-secondary btn-sm">Back</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $err): ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <form method="post" action="<?= base_url('teacher/courses') ?>">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label" for="title">Course Title</label>
                    <input
                        type="text"
                        class="form-control"
                        id="title"
                        name="title"
                        value="<?= esc(old('title') ?? '') ?>"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label" for="description">Description</label>
                    <textarea
                        class="form-control"
                        id="description"
                        name="description"
                        rows="5"
                    ><?= esc(old('description') ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
