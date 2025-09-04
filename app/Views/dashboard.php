<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f4f6f9;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="card p-4 shadow-lg">
        <h2 class="mb-3">👋 Welcome, <span class="text-primary"><?= esc($name) ?></span>!</h2>
        <p class="mb-4">
            You are logged in as 
            <strong class="text-success"><?= esc($role) ?></strong>.
        </p>

        <div class="text-end">
            <a href="<?= base_url('/logout') ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</div>
</body>
</html>
