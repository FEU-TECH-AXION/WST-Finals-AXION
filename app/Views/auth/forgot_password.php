<?php
// ==============================================
// File: app/Views/auth/forgot_password.php
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
.auth-logo {
    max-width: 100px;
    margin-bottom: 20px;
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
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <div class="icon-wrapper">
                <i class="bi bi-key"></i>
            </div>
            <h2 class="auth-title">Forgot Password?</h2>
            <p class="auth-subtitle">No worries! Enter your email and we'll help you reset it.</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

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

        <form method="post" action="<?= base_url('forgot-password') ?>">
            <?= csrf_field() ?>

            <div class="mb-4">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" class="form-control" id="email" name="email"
                        placeholder="Enter your registered email" value="<?= old('email') ?>" required>
                </div>
                <small class="text-muted">Enter the email address associated with your account</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                <i class="bi bi-send me-2"></i>Send Reset Instructions
            </button>

            <div class="text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Back to Login
                </a>
            </div>
        </form>

        <div class="mt-4 p-3 bg-light rounded">
            <h6 class="mb-2"><i class="bi bi-info-circle me-2"></i>Instructions:</h6>
            <ol class="small mb-0 ps-3">
                <li>Enter your registered email address</li>
                <li>Check your email for reset instructions</li>
                <li>Follow the link to create a new password</li>
            </ol>
        </div>
    </div>
</div>

<?= $this->include('include/view_footer') ?>