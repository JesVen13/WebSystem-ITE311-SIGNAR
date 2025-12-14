<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - SIGNAR Admin</title>
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
                        <a href="<?= site_url('/admin/users') ?>" class="list-group-item list-group-item-action active">
                            <i class="fas fa-users me-2"></i>Manage Users
                        </a>
                        <a href="<?= site_url('/admin/deleted-users') ?>" class="list-group-item list-group-item-action">
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
                    <h2><i class="fas fa-users me-2"></i>Manage Users</h2>
                    <a href="<?= site_url('/admin/create') ?>" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Create New User
                    </a>
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

                <!-- Search Bar -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="get" action="<?= site_url('/admin/users') ?>">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Search by name, email, or role..." 
                                       value="<?= esc($search ?? '') ?>">
                                <button type="submit" class="btn btn-primary">Search</button>
                                <?php if (!empty($search)): ?>
                                    <a href="<?= site_url('/admin/users') ?>" class="btn btn-secondary">Clear</a>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>Users List</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($users)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-users-slash fa-4x text-muted mb-3"></i>
                                <p class="text-muted fs-5">No users found</p>
                                <?php if (!empty($search)): ?>
                                    <p class="text-muted">Try adjusting your search criteria</p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($users as $u): 
                                            $isCurrentUser = $u['id'] == ($currentUserId ?? session()->get('user_id'));
                                        ?>
                                            <tr>
                                                <td><?= esc($u['id']) ?></td>
                                                <td>
                                                    <i class="fas fa-user text-muted me-2"></i>
                                                    <?= esc($u['name']) ?>
                                                    <?php if ($isCurrentUser): ?>
                                                        <span class="badge bg-primary">You</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?= esc($u['email']) ?></td>
                                                <td>
                                                    <?php if ($u['role'] === 'admin'): ?>
                                                        <span class="badge bg-danger">Admin</span>
                                                    <?php elseif ($u['role'] === 'teacher'): ?>
                                                        <span class="badge bg-success">Teacher</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-primary">Student</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($u['is_restricted'] == 1): ?>
                                                        <span class="badge bg-warning text-dark">Restricted</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <!-- Edit Button - Always visible -->
                                                    <a href="<?= site_url("/admin/edit/{$u['id']}") ?>" 
                                                       class="btn btn-sm btn-outline-warning" 
                                                       title="Edit User">
                                                        <i class="fas fa-edit"></i>
                                                    </a>

                                                    <?php if (!$isCurrentUser): ?>
                                                        <!-- Restrict/Unrestrict Button -->
                                                        <?php if ($u['is_restricted'] == 1): ?>
                                                            <a href="<?= site_url("/admin/restrict/{$u['id']}") ?>" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Unrestrict User">
                                                                <i class="fas fa-check"></i>
                                                            </a>
                                                        <?php else: ?>
                                                            <a href="<?= site_url("/admin/restrict/{$u['id']}") ?>" 
                                                               class="btn btn-sm btn-outline-dark" 
                                                               onclick="return confirm('Are you sure you want to restrict this user?')"
                                                               title="Restrict User">
                                                                <i class="fas fa-ban"></i>
                                                            </a>
                                                        <?php endif; ?>

                                                        <!-- Delete Button -->
                                                        <a href="<?= site_url("/admin/delete/{$u['id']}") ?>" 
                                                           class="btn btn-sm btn-outline-danger"
                                                           onclick="return confirm('Are you sure you want to delete <?= esc($u['name']) ?>?')"
                                                           title="Delete User">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <?php if ($pager): ?>
                                <div class="mt-3">
                                    <?= $pager->links() ?>
                                </div>
                            <?php endif; ?>

                            <!-- Info Box -->
                            <div class="alert alert-info mt-3" role="alert">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Note:</strong> You can edit any user, but you cannot restrict or delete your own account.
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