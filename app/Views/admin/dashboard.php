<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<style>
    body {
        background: #f5f5f7;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    h1, h5 {
        font-weight: 600;
    }

    .card-apple {
        border: none;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.06);
        transition: .2s;
    }

    .card-apple:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.09);
    }

    .stat-number {
        font-size: 34px;
        font-weight: 700;
        color: #111;
    }

    .table thead {
        border-bottom: 2px solid #e5e5e5;
    }

    .badge {
        padding: 6px 10px;
        font-size: 0.75rem;
    }

    .apple-btn {
        border-radius: 12px;
        padding: 6px 16px;
        font-weight: 500;
    }

    .btn-primary {
        background: #0071e3;
        border: none;
    }
    .btn-primary:hover {
        background: #0a83ff;
    }

    .btn-danger {
        background: #d70015;
        border: none;
    }
    .btn-danger:hover {
        background: #ff2d3b;
    }

    .btn-warning {
        background: #ff9f0a;
        border: none;
    }
    .btn-warning:hover {
        background: #ffb23d;
    }
</style>
</head>

<body class="p-4">
<div class="container">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Admin Dashboard</h1>

        <div>
            <span class="me-3">Logged in as <strong><?= esc($name) ?></strong> (Admin)</span>
            <a href="<?= base_url('/admin/create') ?>" class="btn btn-primary apple-btn">Create User</a>
            <a href="<?= base_url('/logout') ?>" class="btn btn-secondary apple-btn">Logout</a>
        </div>
    </div>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
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

    <!-- Stats -->
    <div class="row g-4 mb-4">

        <div class="col-md-3">
            <div class="card card-apple p-3">
                <h5>Total Users</h5>
                <div class="stat-number"><?= $totalUsers ?? 0 ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-apple p-3">
                <h5>Teachers</h5>
                <div class="stat-number"><?= $totalTeachers ?? 0 ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-apple p-3">
                <h5>Students</h5>
                <div class="stat-number"><?= $totalStudents ?? 0 ?></div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card card-apple p-3">
                <h5>Admins</h5>
                <div class="stat-number"><?= $totalAdmins ?? 0 ?></div>
            </div>
        </div>

    </div>

    <!-- Active Users -->
    <div class="card card-apple p-3 mb-5">
        <h5 class="mb-3">Active Users</h5>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="bg-light">
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
                <?php if (!empty($users)): foreach ($users as $u): 
                    $isAdminUser = $u['role'] === 'admin';
                    $isCurrentUser = $u['id'] == session()->get('user_id');

                    $badgeClass = match ($u['role']) {
                        'admin' => 'bg-dark',
                        'teacher' => 'bg-success',
                        default => 'bg-info'
                    };
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
                        <td><span class="badge <?= $badgeClass ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($u['created_at'])) ?></td>
                        <td>
                            <?php if (!$isAdminUser && !$isCurrentUser): ?>
                                <a href="<?= base_url('/admin/edit/'.$u['id']) ?>" class="btn btn-warning btn-sm apple-btn">Edit</a>
                                <a href="<?= base_url('/admin/restrict/'.$u['id']) ?>" 
                                   class="btn btn-sm <?= $u['is_restricted'] ? 'btn-success' : 'btn-secondary' ?> apple-btn">
                                   <?= $u['is_restricted'] ? 'Unrestrict' : 'Restrict' ?>
                                </a>
                                <a href="<?= base_url('/admin/delete/'.$u['id']) ?>" 
                                   class="btn btn-danger btn-sm apple-btn"
                                   onclick="return confirm('Remove <?= esc($u['name']) ?>?');">Delete</a>
                            <?php else: ?>
                                <span class="text-muted">Protected</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; else: ?>
                    <tr><td colspan="6" class="text-center text-muted">No active users found.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Restricted Users -->
    <div class="card card-apple p-3 border-danger">
        <h5 class="mb-3 text-danger">Restricted Users</h5>

        <?php if (!empty($restrictedUsers)): ?>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Restricted Since</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($restrictedUsers as $r): ?>
                        <tr>
                            <td><?= esc($r['id']) ?></td>
                            <td><?= esc($r['name']) ?></td>
                            <td><?= esc($r['email']) ?></td>
                            <td><span class="badge bg-secondary"><?= ucfirst($r['role']) ?></span></td>
                            <td><?= date('M d, Y', strtotime($r['updated_at'])) ?></td>
                            <td>
                                <a href="<?= base_url('/admin/restrict/'.$r['id']) ?>" class="btn btn-success btn-sm apple-btn">Unrestrict</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center">No restricted users.</p>
        <?php endif; ?>
    </div>

</div>
</body>
</html>
