<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .card { border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card p-4 shadow-lg">
        <h2 class="mb-3">👋 Welcome, <span class="text-primary"><?= esc($name) ?></span>!</h2>
        <p class="mb-4">You are logged in as <strong class="text-success"><?= esc($role) ?></strong>.</p>

        <?php if (isset($role) && $role === 'admin'): ?>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Total Users</h5>
                        <p class="display-6 mb-0"><?= esc($roleData['totalUsers'] ?? 0) ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Total Courses</h5>
                        <p class="display-6 mb-0"><?= esc($roleData['totalCourses'] ?? 0) ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3">
                        <h5>Total Enrollments</h5>
                        <p class="display-6 mb-0"><?= esc($roleData['totalEnrollments'] ?? 0) ?></p>
                    </div>
                </div>
            </div>
        <?php elseif (isset($role) && $role === 'teacher'): ?>
            <div class="card p-3 mt-2">
                <h5>My Courses</h5>
                <p class="display-6 mb-0"><?= esc($roleData['myCourses'] ?? 0) ?></p>
            </div>
        <?php else: ?>
            <div class="card p-3 mt-2">
                <h5>My Enrollments</h5>
                <p class="display-6 mb-0"><?= esc($roleData['myEnrollments'] ?? 0) ?></p>
            </div>
        <?php endif; ?>

        <div class="text-end">
            <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
</body>
</html>


