<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
<div class="container">

    <!-- TOP BAR -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Users</h1>

        <div>
            <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-secondary me-2">⬅ Back to Dashboard</a>
            <a href="<?= base_url('/admin/create') ?>" class="btn btn-primary">Create User</a>
        </div>
    </div>

    <!-- FLASH MESSAGE -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>


    <table class="table table-striped align-middle">
        <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th>
        </tr>
        </thead>

        <tbody>
        <?php if (!empty($users)): foreach ($users as $u): ?>
            <tr>
                <td><?= esc($u['id']) ?></td>
                <td><?= esc($u['name']) ?></td>
                <td><?= esc($u['email']) ?></td>

                <!-- ROLE -->
                <td>
                    <?php if ($u['role'] === 'admin'): ?>
                        <span class="badge bg-danger">Admin</span>
                    <?php else: ?>
                        <?= esc($u['role']) ?>
                    <?php endif; ?>
                </td>

                <!-- RESTRICTED STATUS -->
                <td>
                    <?php if ($u['is_restricted'] == 1): ?>
                        <span class="badge bg-warning text-dark">Restricted</span>
                    <?php else: ?>
                        <span class="badge bg-success">Active</span>
                    <?php endif; ?>
                </td>

                <!-- ACTIONS -->
                <td>
                    <!-- EDIT -->
                    <a href="<?= base_url("/admin/edit/{$u['id']}") ?>"
                       class="btn btn-sm btn-warning">Edit</a>
                    <?php if ($u['role'] !== 'admin'): ?>
                        <?php if ($u['is_restricted'] == 1): ?>
                            <a href="<?= base_url("/admin/restrict/{$u['id']}") ?>"
                               class="btn btn-sm btn-secondary">Unrestrict</a>
                        <?php else: ?>
                            <a href="<?= base_url("/admin/restrict/{$u['id']}") ?>"
                               class="btn btn-sm btn-dark">Restrict</a>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- DELETE (hidden for admin) -->
                    <?php if ($u['role'] !== 'admin'): ?>
                        <a href="<?= base_url("/admin/delete/{$u['id']}") ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Delete user #<?= esc($u['id']) ?>?');">
                            Delete
                        </a>
                    <?php else: ?>
                        <span class="text-muted">Protected</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; else: ?>
            <tr>
                <td colspan="6" class="text-center">No users found.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

</div>
</body>
</html>
