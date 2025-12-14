<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SIGNAR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= site_url('/admin/dashboard') ?>">
                <i class="fas fa-graduation-cap me-2"></i>  Admin Dashboard
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
                    <a href="<?= site_url('/admin/dashboard') ?>" class="list-group-item list-group-item-action active">
                        <i class="fas fa-home me-2"></i>Dashboard
                    </a>
                    <a href="<?= site_url('/admin/users') ?>" class="list-group-item list-group-item-action">
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
            
            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-primary"><?= $totalUsers ?></h5>
                            <p class="card-text">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-success"><?= $totalTeachers ?></h5>
                            <p class="card-text">Teachers</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-info"><?= $totalStudents ?></h5>
                            <p class="card-text">Students</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h5 class="card-title text-warning"><?= $totalAdmins ?></h5>
                            <p class="card-text">Admins</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Active Users</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($users)): ?>
                        <p class="text-muted">No active users</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($users as $user): 
                                        $isCurrentUser = $user['id'] == $currentUserId;
                                    ?>
                                        <tr>
                                            <td>
                                                <?= esc($user['name']) ?>
                                                <?php if ($isCurrentUser): ?>
                                                    <span class="badge bg-primary">You</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'primary') ?>">
                                                    <?= esc($user['role']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td>
                                                <!-- Edit Button - Always visible -->
                                                <a href="<?= site_url('/admin/edit/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                
                                                <?php if (!$isCurrentUser): ?>
                                                    <!-- Restrict Button - Only for others -->
                                                    <a href="<?= site_url('/admin/restrict/' . $user['id']) ?>" class="btn btn-sm btn-outline-warning" 
                                                       onclick="return confirm('Are you sure you want to restrict this user?')">
                                                        <i class="fas fa-ban"></i>
                                                    </a>
                                                    
                                                    <!-- Delete Button - Only for others -->
                                                    <a href="<?= site_url('/admin/delete/' . $user['id']) ?>" class="btn btn-sm btn-outline-danger"
                                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Restricted Users Table -->
            <?php if (!empty($restrictedUsers)): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-user-slash me-2"></i>Restricted Users</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($restrictedUsers as $user): 
                                        $isCurrentUser = $user['id'] == $currentUserId;
                                    ?>
                                        <tr>
                                            <td>
                                                <?= esc($user['name']) ?>
                                                <?php if ($isCurrentUser): ?>
                                                    <span class="badge bg-primary">You</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= esc($user['email']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : ($user['role'] === 'teacher' ? 'success' : 'primary') ?>">
                                                    <?= esc($user['role']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-warning">Restricted</span>
                                            </td>
                                            <td>
                                                <?php if (!$isCurrentUser): ?>
                                                    <a href="<?= site_url('/admin/restrict/' . $user['id']) ?>" class="btn btn-sm btn-outline-success"
                                                       onclick="return confirm('Are you sure you want to unrestrict this user?')">
                                                        <i class="fas fa-check"></i> Unrestrict
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>