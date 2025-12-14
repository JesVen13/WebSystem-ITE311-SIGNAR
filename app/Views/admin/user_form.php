<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $user ? 'Edit User' : 'Create User' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }

        .form-container {
            max-width: 550px;
            margin: 0 auto;
        }

        .form-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            padding: 40px 32px;
        }

        .form-header {
            margin-bottom: 32px;
        }

        .form-header h1 {
            font-size: 28px;
            color: #1a202c;
            font-weight: 600;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group input.error {
            border-color: #f56565;
        }

        .form-hint {
            font-size: 13px;
            color: #718096;
            margin-top: 6px;
        }

        .error-message {
            color: #f56565;
            font-size: 13px;
            margin-top: 6px;
        }

        .validation-error {
            color: #f56565;
            font-size: 13px;
            margin-top: 6px;
        }

        .name-error {
            display: none;
        }

        .name-error.show {
            display: block;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            margin-top: 24px;
        }

        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .btn-secondary {
            background: #e2e8f0;
            color: #2d3748;
        }

        .btn-secondary:hover {
            background: #cbd5e0;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                <h1><?= $user ? 'Edit User' : 'Create User' ?></h1>
            </div>

            <?php $validation = session()->getFlashdata('validation') ?? $validation ?? \Config\Services::validation(); ?>

            <form method="post" action="<?= $user ? base_url("/admin/update/{$user['id']}") : base_url('/admin/store') ?>" id="userForm">
                <?= csrf_field() ?>

                <!-- Name -->
                <div class="form-group">
                    <label for="name">Name</label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        value="<?= old('name', $user['name'] ?? '') ?>"
                        placeholder="Juan Dela Cruz"
                        required>
                    <div class="error-message name-error" id="nameError">Only letters, spaces, and ñ/Ñ are allowed</div>
                    <?php if ($validation->getError('name')): ?>
                        <div class="validation-error"><?= $validation->getError('name') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="<?= old('email', $user['email'] ?? '') ?>"
                        placeholder="you@example.com"
                        required>
                    <?php if ($validation->getError('email')): ?>
                        <div class="validation-error"><?= $validation->getError('email') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Role -->
                <div class="form-group">
                    <label for="role">Role</label>
                    <?php $sel = old('role', $user['role'] ?? 'student'); ?>
                    <select id="role" name="role" required>
                        <option value="student" <?= $sel === 'student' ? 'selected' : '' ?>>Student</option>
                        <option value="teacher" <?= $sel === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                        <option value="admin" <?= $sel === 'admin' ? 'selected' : '' ?>>Admin</option>
                    </select>
                    <?php if ($validation->getError('role')): ?>
                        <div class="validation-error"><?= $validation->getError('role') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password"><?= $user ? 'New Password' : 'Password' ?></label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password"
                        placeholder="<?= $user ? 'Leave blank to keep current password' : 'At least 6 characters' ?>"
                        <?= $user ? '' : 'required' ?>>
                    <?php if ($user): ?>
                        <div class="form-hint">Only fill this if you want to change the password</div>
                    <?php endif; ?>
                    <?php if ($validation->getError('password')): ?>
                        <div class="validation-error"><?= $validation->getError('password') ?></div>
                    <?php endif; ?>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">
                        <?= $user ? 'Update User' : 'Create User' ?>
                    </button>
                    <a href="<?= base_url('/admin/dashboard') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Validate name field - only letters, spaces, and ñ/Ñ allowed
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        const form = document.getElementById('userForm');

        nameInput.addEventListener('input', function(e) {
            // Remove any characters that are not letters, spaces, ñ, or Ñ
            const regex = /[^a-zA-ZñÑ\s]/g;
            
            if (regex.test(this.value)) {
                this.value = this.value.replace(regex, '');
                this.classList.add('error');
                nameError.classList.add('show');
            } else {
                this.classList.remove('error');
                nameError.classList.remove('show');
            }
        });

        // Final validation before form submission
        form.addEventListener('submit', function(e) {
            const nameValue = nameInput.value.trim();
            const regex = /^[a-zA-ZñÑ\s]+$/;
            
            if (!regex.test(nameValue)) {
                e.preventDefault();
                nameInput.classList.add('error');
                nameError.classList.add('show');
                nameInput.focus();
            }
        });
    </script>
</body>
</html>