<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deleted Users - SIGNAR Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/admin/dashboard') ?>">
                <i class="fas fa-graduation-cap me-2"></i>Admin Dashboard
            </a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user-circle me-1"></i><?= esc($name) ?>
                </span>
                <a class="nav-link btn btn-outline-light btn-sm" href="<?= site_url('/logout') ?>">
                    <i class="fas fa-sign-out-alt me-1"></i>Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-4">
        <div class="row">
            <!-- Sidebar Menu -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-tachometer-alt me-2"></i>Admin Menu</h5>
                    </div>
                    <div class="list-group list-group-flush">
                        <a href="<?= site_url('/admin/dashboard') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-home me-2"></i>Dashboard
                        </a>
                        <a href="<?= site_url('/admin/users') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="<?= site_url('/admin/deleted-users') ?>" class="list-group-item list-group-item-action active">
                            <i class="fas fa-trash me-2"></i>Deleted Users
                        </a>
                        <a href="<?= site_url('/admin/create') ?>" class="list-group-item list-group-item-action">
                            <i class="fas fa-plus me-2"></i>Create User
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-trash me-2"></i>Deleted Users</h2>
                    <?php if (!empty($deletedUsers)): ?>
                        <a href="<?= site_url('/admin/purge-all') ?>" class="btn btn-danger" 
                       onclick="return confirm('Are you sure you want to permanently delete ALL deleted users? This action cannot be undone!')">
                        <i class="fas fa-trash-alt me-1"></i>Purge All
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i><?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i><?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card text-center border-danger">
                            <div class="card-body">
                                <h5 class="card-title text-danger"><?= count($deletedUsers ?? []) ?></h5>
                                <p class="card-text">Total Deleted Users</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-warning">
                            <div class="card-body">
                                <h5 class="card-title text-warning">
                                    <?= !empty($deletedUsers) ? count(array_filter($deletedUsers, fn($u) => $u['role'] === 'teacher')) : 0 ?>
                                </h5>
                                <p class="card-text">Deleted Teachers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-center border-info">
                            <div class="card-body">
                                <h5 class="card-title text-info">
                                    <?= !empty($deletedUsers) ? count(array_filter($deletedUsers, fn($u) => $u['role'] === 'student')) : 0 ?>
                                </h5>
                                <p class="card-text">Deleted Students</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deleted Users Table -->
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-user-times me-2"></i>Deleted Users List</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($deletedUsers)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                <p class="text-muted fs-5">No deleted users found</p>
                                <p class="text-muted">Users who are deleted will appear here</p>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Deleted On</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($deletedUsers as $user): ?>
                                            <tr>
                                                <td>
                                                    <i class="fas fa-user-slash text-muted me-2"></i>
                                                    <?= esc($user['name']) ?>
                                                </td>
                                                <td><?= esc($user['email']) ?></td>
                                                <td>
                                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'primary') ?>">
                                                        <?= esc($user['role']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        <?= isset($user['deleted_at']) ? date('M d, Y H:i', strtotime($user['deleted_at'])) : 'N/A' ?>
                                                    </small>
                                                </td>
                                                <td>
                                                    <!-- Restore Button -->
                                                    <a href="<?= site_url('/admin/restore/' . $user['id']) ?>" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       onclick="return confirm('Are you sure you want to restore <?= esc($user['name']) ?>?')"
                                                       title="Restore User">
                                                        <i class="fas fa-undo"></i> Restore
                                                    </a>
                                                    
                                                    <!-- Permanent Delete Button -->
                                                    <a href="<?= site_url('/admin/permanent-delete/' . $user['id']) ?>" 
                                                       class="btn btn-sm btn-outline-danger" 
                                                       onclick="return confirm('Are you sure you want to PERMANENTLY delete <?= esc($user['name']) ?>? This action cannot be undone!')"
                                                       title="Permanently Delete">
                                                        <i class="fas fa-trash-alt"></i> Delete Forever
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Info Box -->
                            <div class="alert alert-info mt-3" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> Deleted users can be restored or permanently removed. 
                                Permanent deletion cannot be undone.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>