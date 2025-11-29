<?php
// ==============================================
// File: app/Views/auth/reset_password.php
// ==============================================
?>
<?= $this->include('include/view_nav') ?>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.auth-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    padding: 40px;
    max-width: 450px;
    width: 100%;
}
.auth-title {
    color: #333;
    font-weight: 600;
    margin-bottom: 10px;
}
.auth-subtitle {
    color: #666;
    margin-bottom: 30px;
}
.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}
.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    padding: 12px;
    font-weight: 600;
}
.btn-primary:hover {
    opacity: 0.9;
}
.icon-wrapper {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    color: white;
}
.password-strength {
    height: 5px;
    border-radius: 3px;
    margin-top: 5px;
    transition: all 0.3s;
}
.strength-weak { width: 33%; background: #dc3545; }
.strength-medium { width: 66%; background: #ffc107; }
.strength-strong { width: 100%; background: #28a745; }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <div class="icon-wrapper">
                <i class="bi bi-shield-lock"></i>
            </div>
            <h2 class="auth-title">Reset Password</h2>
            <p class="auth-subtitle">Create a new secure password for your account</p>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $validation->listErrors() ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('reset-password/process') ?>" id="resetForm">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token) ?>">

            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" class="form-control" id="password" name="password"
                        placeholder="Enter new password" required minlength="6">
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                        <i class="bi bi-eye" id="password-icon"></i>
                    </button>
                </div>
                <div class="password-strength" id="strength-bar"></div>
                <small class="text-muted" id="strength-text">Minimum 6 characters</small>
            </div>

            <div class="mb-4">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-lock-fill"></i>
                    </span>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password"
                        placeholder="Confirm new password" required>
                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                        <i class="bi bi-eye" id="confirm_password-icon"></i>
                    </button>
                </div>
                <small class="text-danger d-none" id="password-match">Passwords do not match</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-check-circle me-2"></i>Reset Password
            </button>

            <div class="text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Back to Login
                </a>
            </div>
        </form>

        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Password Requirements:</h6>
            <ul class="small mb-0">
                <li>At least 6 characters long</li>
                <li>Mix of letters and numbers recommended</li>
                <li>Avoid common words or patterns</li>
            </ul>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = document.getElementById(fieldId + '-icon');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.remove('bi-eye');
        icon.classList.add('bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.remove('bi-eye-slash');
        icon.classList.add('bi-eye');
    }
}

// Password strength checker
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    
    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.length >= 10) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    strengthBar.className = 'password-strength';
    if (strength <= 2) {
        strengthBar.classList.add('strength-weak');
        strengthText.textContent = 'Weak password';
        strengthText.className = 'text-danger';
    } else if (strength <= 3) {
        strengthBar.classList.add('strength-medium');
        strengthText.textContent = 'Medium strength';
        strengthText.className = 'text-warning';
    } else {
        strengthBar.classList.add('strength-strong');
        strengthText.textContent = 'Strong password';
        strengthText.className = 'text-success';
    }
});

// Password match checker
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirm = this.value;
    const matchMsg = document.getElementById('password-match');
    
    if (confirm && password !== confirm) {
        matchMsg.classList.remove('d-none');
        this.classList.add('is-invalid');
    } else {
        matchMsg.classList.add('d-none');
        this.classList.remove('is-invalid');
    }
});

// Form validation
document.getElementById('resetForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirm = document.getElementById('confirm_password').value;
    
    if (password !== confirm) {
        e.preventDefault();
        alert('Passwords do not match!');
        return false;
    }
    
    if (password.length < 6) {
        e.preventDefault();
        alert('Password must be at least 6 characters long!');
        return false;
    }
});
</script>

