<?php
// File: app/Views/auth/forgot_password.php
?>
<?= $this->include('templates/header') ?>
<style>
.auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.auth-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); padding: 40px; max-width: 450px; width: 100%; }
.auth-logo { max-width: 100px; margin-bottom: 20px; }
.btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; font-weight: 600; }
</style>
<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <img src="<?= base_url('/public/assets/img/logoPlaceholder.png') ?>" alt="ITSO Logo" class="auth-logo">
            <h2 class="auth-title">Forgot Password?</h2>
            <p class="auth-subtitle">Enter your email to receive a password reset link</p>
        </div>
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if (isset($validation)): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>
        <form method="post" action="<?= base_url('forgot-password') ?>">
            <?= csrf_field() ?>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your registered email" value="<?= old('email') ?>" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">Send Reset Link</button>
            <div class="text-center">
                <a href="<?= base_url('login') ?>" class="text-decoration-none">← Back to Login</a>
            </div>
        </form>
    </div>
</div>
<?= $this->include('templates/footer') ?>

<?php
// =============================================
// File: app/Views/auth/reset_password.php
?>
<?= $this->include('templates/header') ?>
<style>
.auth-container { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.auth-card { background: white; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,0.1); padding: 40px; max-width: 450px; width: 100%; }
.auth-logo { max-width: 100px; margin-bottom: 20px; }
.btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 12px; font-weight: 600; }
</style>
<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <img src="<?= base_url('/public/assets/img/logoPlaceholder.png') ?>" alt="ITSO Logo" class="auth-logo">
            <h2 class="auth-title">Reset Password</h2>
            <p class="auth-subtitle">Enter your new password</p>
        </div>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
            <div class="text-center"><a href="<?= base_url('forgot-password') ?>" class="btn btn-primary">Request New Reset Link</a></div>
        <?php else: ?>
            <?php if (isset($validation)): ?>
                <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
            <?php endif; ?>
            <form method="post" action="<?= base_url('reset-password/' . $token) ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Reset Password</button>
                <div class="text-center"><a href="<?= base_url('login') ?>" class="text-decoration-none">← Back to Login</a></div>
            </form>
        <?php endif; ?>
    </div>
</div>
<?= $this->include('templates/footer') ?>