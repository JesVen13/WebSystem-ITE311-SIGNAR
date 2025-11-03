<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h1>Users</h1>
            <a href="<?= base_url('/admin/create') ?>" class="btn btn-primary">Create User</a>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <table class="table table-striped">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): foreach ($users as $u): ?>
                    <tr>
                        <td><?= esc($u['id']) ?></td>
                        <td><?= esc($u['name']) ?></td>
                        <td><?= esc($u['email']) ?></td>
                        <td><?= esc($u['role']) ?></td>
                        <td>
                            <a href="<?= base_url("/admin/edit/{$u['id']}") ?>" class="btn btn-sm btn-warning">Edit</a>
                            <a href="<?= base_url("/admin/delete/{$u['id']}") ?>"
                               class="btn btn-sm btn-danger"
                               onclick="return confirm('Delete user #<?= esc($u['id']) ?>?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="5" class="text-center">No users found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>