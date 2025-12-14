<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - For LMS-System</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;  
        }

        .register-container {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px 32px;
        }

        .register-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .register-header h1 {
            font-size: 28px;
            color: #1a202c;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .register-header p {
            color: #718096;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #2d3748;
            margin-bottom: 8px;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
            outline: none;
            background: white;
        }

        input:focus,
        select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        input.error {
            border-color: #f56565;
        }

        .error-message {
            color: #f56565;
            font-size: 13px;
            margin-top: 6px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 8px;
        }

        .submit-btn:hover {
            background: #5568d3;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            color: #718096;
            font-size: 14px;
        }

        .login-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #742a2a;
            border: 1px solid #fc8181;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h1>Create Account</h1>
            <p>LMS-System Registration</p>
        </div>

        <form action="<?= base_url('register') ?>" method="post" id="registerForm">
            <?= csrf_field() ?>

            <!-- Success/Error Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-error">
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->get('validation')): ?>
                <div class="alert alert-error">
                    <?= session()->get('validation')->listErrors() ?>
                </div>
            <?php endif; ?>

            <!-- Name -->
            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?= old('name') ?>"
                    placeholder="pangalan nimo"
                    required>
                <div class="error-message" id="nameError">Only letters, spaces, and ñ/Ñ are allowed</div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= old('email') ?>"
                    placeholder="imong gmail"
                    required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="At least 6 characters"
                    required>
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirm">Confirm Password</label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    placeholder="Re-enter your password"
                    required>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role">Select role</label>
                <?php $selRole = old('role', 'student'); ?>
                <select id="role" name="role" required>
                    <option value="student" <?= $selRole === 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="teacher" <?= $selRole === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                </select>
            </div>

            <button type="submit" class="submit-btn">Create Account</button>
        </form>

        <div class="login-link">
            Already have an account? <a href="<?= base_url('login') ?>">Sign in</a>
        </div>
    </div>

    <script>
        // Validate name field - only letters, spaces, and ñ/Ñ allowed
        const nameInput = document.getElementById('name');
        const nameError = document.getElementById('nameError');
        const form = document.getElementById('registerForm');

        nameInput.addEventListener('input', function(e) {
            // Remove any characters that are not letters, spaces, ñ, or Ñ
            // This regex allows: a-z, A-Z, spaces, ñ, Ñ
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