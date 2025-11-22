<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?= $user ? 'Edit User' : 'Create User' ?></title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background: #f4f8ff; /* very light blue */
        font-family: "Inter", sans-serif;
    }
    .card-custom {
        background: #ffffff;
        border: 1px solid #e3eaff;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
    .page-title {
        font-weight: 700;
        color: #264796; /* soft blue */
    }
    .form-label {
        font-weight: 600;
        color: #2c3e50;
    }
    .form-control, .form-select {
        border-radius: 10px;
        padding: 10px 14px;
        border: 1px solid #cdd8f6;
    }
    .btn-primary {
        background: #3c6feb;
        border: none;
        padding: 10px 22px;
        border-radius: 10px;
    }
    .btn-secondary {
        border-radius: 10px;
        padding: 10px 22px;
    }
</style>

</head>
<body class="p-4">

<div class="container" style="max-width: 650px;">
    
    <h1 class="page-title mb-4">
        <?= $user ? 'Edit User' : 'Create User' ?>
    </h1>

    <div class="card-custom">

        <?php $validation = session()->getFlashdata('validation') ?? $validation ?? \Config\Services::validation(); ?>

        <form method="post" action="<?= $user ? base_url("/admin/update/{$user['id']}") : base_url('/admin/store') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Name</label>
                <input name="name" class="form-control" value="<?= old('name', $user['name'] ?? '') ?>">
                <?php if ($validation->getError('name')): ?>
                    <div class="text-danger small"><?= $validation->getError('name') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input name="email" class="form-control" value="<?= old('email', $user['email'] ?? '') ?>">
                <?php if ($validation->getError('email')): ?>
                    <div class="text-danger small"><?= $validation->getError('email') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <?php 
                $sel = old('role', $user['role'] ?? ''); 
                $isAdmin = isset($user['role']) && $user['role'] === 'admin';
                ?>
                <select name="role" class="form-select" <?= $isAdmin ? 'disabled' : '' ?>>
                    <?php if ($isAdmin): ?>
                        <option value="admin" selected>admin (Cannot be changed)</option>
                    <?php else: ?>
                        <option value="teacher" <?= $sel === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                        <option value="student" <?= $sel === 'student' ? 'selected' : '' ?>>Student</option>
                    <?php endif; ?>
                </select>

                <?php if ($isAdmin): ?>
                    <input type="hidden" name="role" value="admin">
                    <small class="text-muted">Admin role cannot be changed</small>
                <?php endif; ?>

                <?php if ($validation->getError('role')): ?>
                    <div class="text-danger small"><?= $validation->getError('role') ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label class="form-label"><?= $user ? 'New Password (leave blank to keep)' : 'Password' ?></label>
                <input name="password" type="password" class="form-control">
                <?php if ($validation->getError('password')): ?>
                    <div class="text-danger small"><?= $validation->getError('password') ?></div>
                <?php endif; ?>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary" type="submit">
                    <?= $user ? 'Update User' : 'Create User' ?>
                </button>
                <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>

    </div>
</div>

</body>
</html>
