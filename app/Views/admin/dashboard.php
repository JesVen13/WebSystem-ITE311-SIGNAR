<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<<<<<<< HEAD
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Admin Dashboard</h1>
        <div>
            <span class="me-3">Logged in as <strong><?= esc($name) ?></strong> (Admin)</span>
=======
</head>
<body class="p-4">
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Admin Dashboard</h1>
        <div>
            <span class="me-3">Logged in as <?= esc($name) ?> (<?= esc($role) ?>)</span>
>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
            <a href="<?= base_url('/admin/create') ?>" class="btn btn-primary">Create User</a>
            <a href="<?= base_url('/logout') ?>" class="btn btn-secondary">Logout</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
<<<<<<< HEAD
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <h2><?= $totalUsers ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Teachers</h5>
                    <h2><?= $totalTeachers ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Students</h5>
                    <h2><?= $totalStudents ?? 0 ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Admins</h5>
                    <h2><?= $totalAdmins ?? 0 ?></h2>
                </div>
            </div>
        </div>
    </div>

=======
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Users</h5>
            <div class="table-responsive">
<<<<<<< HEAD
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Registered</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (! empty($users)): foreach ($users as $u): 
                        $isAdminUser = $u['role'] === 'admin';
                        $isCurrentUser = $u['id'] == session()->get('user_id');
                        $roleBadgeClass = $isAdminUser ? 'badge bg-warning' : ($u['role'] === 'teacher' ? 'badge bg-success' : 'badge bg-info');
                    ?>
                        <tr>
                            <td><?= esc($u['id']) ?></td>
                            <td>
                                <?= esc($u['name']) ?>
                                <?php if ($isCurrentUser): ?>
                                    <span class="badge bg-primary">You</span>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($u['email']) ?></td>
                            <td><span class="<?= $roleBadgeClass ?>"><?= esc(ucfirst($u['role'])) ?></span></td>
                            <td>
                                <?php if (isset($u['created_at'])): ?>
                                    <?= date('M d, Y', strtotime($u['created_at'])) ?>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($isAdminUser && !$isCurrentUser): ?>
                                    <span class="text-muted">Admin (Protected)</span>
                                <?php else: ?>
                                    <a href="<?= base_url("/admin/edit/{$u['id']}") ?>" class="btn btn-sm btn-warning">Edit</a>
                                    <?php if (!$isAdminUser && !$isCurrentUser): ?>
                                        <a href="<?= base_url("/admin/delete/{$u['id']}") ?>"
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete user <?= esc($u['name']) ?>?');">Delete</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; else: ?>
                        <tr><td colspan="6" class="text-center">No registered users found.</td></tr>
=======
                <table class="table table-striped">
                    <thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th><th>Actions</th></tr></thead>
                    <tbody>
                    <?php if (! empty($users)): foreach ($users as $u): ?>
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
>>>>>>> c3cd521911bcd31f5d0997904ea5026bc1bd85f7
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>