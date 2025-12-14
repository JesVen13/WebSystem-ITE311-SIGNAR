<?= $this->extend('templates/admin_template') ?>
<?= $this->section('content') ?>

<style>
    .edit-user-container {
        max-width: 500px;
        margin: 40px auto;
    }

    .edit-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        padding: 32px;
    }

    .edit-header {
        margin-bottom: 32px;
    }

    .edit-header h3 {
        font-size: 24px;
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
        display: none;
    }

    .error-message.show {
        display: block;
    }

    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 24px;
        font-size: 14px;
    }

    .alert-danger {
        background: #fed7d7;
        color: #742a2a;
        border: 1px solid #fc8181;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
    }

    .alert li {
        margin: 4px 0;
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

<div class="edit-user-container">
    <div class="edit-card">
        <div class="edit-header">
            <h3>Edit User</h3>
        </div>

        <?php if (session()->has('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach (session('errors') as $error): ?>
                        <li><?= $error ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('admin/update-user/' . $user['id']) ?>" method="post" id="editUserForm">
            <?= csrf_field() ?>
            
            <!-- Name -->
            <div class="form-group">
                <label for="name">Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?= esc($user['name']) ?>" 
                    required>
                <div class="error-message" id="nameError">Only letters, spaces, and ñ/Ñ are allowed</div>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email">Email</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= esc($user['email']) ?>" 
                    required>
            </div>

            <!-- Role -->
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                    <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                    <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                </select>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">New Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Leave blank to keep current password">
                <div class="form-hint">Only fill this if you want to change the password</div>
            </div>

            <!-- Buttons -->
            <div class="btn-group">
                <button type="submit" class="btn btn-primary">Update User</button>
                <a href="<?= base_url('admin/dashboard') ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Validate name field - only letters, spaces, and ñ/Ñ allowed
    const nameInput = document.getElementById('name');
    const nameError = document.getElementById('nameError');
    const form = document.getElementById('editUserForm');

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

<?= $this->endSection() ?>