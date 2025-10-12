<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #436eadff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .card {
            width: 450px;
            border-radius: 15px;
            box-shadow: 0px 4px 15px rgba(0,0,0,0.1);
        }
        .invalid-feedback {
            display: block;
        }
    </style>
</head>
<body>
    <div class="card p-4">
        <h2 class="text-center mb-4">Register</h2>

        <?php if(session()->getFlashdata('error')): ?>
            <div class="alert alert-danger"><?= session()->getFlashdata('error'); ?></div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success'); ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('register') ?>">
            <?= csrf_field() ?>

            <!-- Full Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input 
                    type="text" 
                    class="form-control <?= isset($validation) && $validation->hasError('name') ? 'is-invalid' : '' ?>" 
                    name="name" id="name" 
                    value="<?= old('name') ?>" required>
                <?php if(isset($validation) && $validation->hasError('name')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('name') ?></div>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input 
                    type="email" 
                    class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>" 
                    name="email" id="email" 
                    value="<?= old('email') ?>" required>
                <?php if(isset($validation) && $validation->hasError('email')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                <?php endif; ?>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input 
                    type="password" 
                    class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                    name="password" id="password" required>
                <?php if(isset($validation) && $validation->hasError('password')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('password') ?></div>
                <?php endif; ?>
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirm" class="form-label">Confirm Password</label>
                <input 
                    type="password" 
                    class="form-control <?= isset($validation) && $validation->hasError('password_confirm') ? 'is-invalid' : '' ?>" 
                    name="password_confirm" id="password_confirm" required>
                <?php if(isset($validation) && $validation->hasError('password_confirm')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('password_confirm') ?></div>
                <?php endif; ?>
            </div>

            <!-- Role -->
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <select 
                    name="role" id="role" 
                    class="form-select <?= isset($validation) && $validation->hasError('role') ? 'is-invalid' : '' ?>" required>
                    <option value="admin" <?= old('role')==='admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="teacher" <?= old('role')==='teacher' ? 'selected' : '' ?>>Teacher</option>
                    <option value="student" <?= old('role')==='student' ? 'selected' : '' ?>>Student</option>
                </select>
                <?php if(isset($validation) && $validation->hasError('role')): ?>
                    <div class="invalid-feedback"><?= $validation->getError('role') ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>

        <div class="mt-3 text-center">
            <small>Already have an account? <a href="<?= site_url('login') ?>">Login here</a></small>
        </div>
    </div>
</body>
</html>
