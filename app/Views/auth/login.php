<?php
// File: app/Views/auth/login.php
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
.divider {
    text-align: center;
    margin: 20px 0;
    position: relative;
}
.divider:before, .divider:after {
    content: "";
    position: absolute;
    top: 50%;
    width: 45%;
    height: 1px;
    background: #ddd;
}
.divider:before { left: 0; }
.divider:after { right: 0; }
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <img src="<?= base_url('/public/assets/img/logoPlaceholder.png') ?>" alt="ITSO Logo" class="auth-logo">
            <h2 class="auth-title">Welcome Back</h2>
            <p class="auth-subtitle">Login to access your dashboard</p>
        </div>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('login') ?>">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email"
                    placeholder="Enter your email" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                    placeholder="Enter your password" required>
            </div>

            <div class="mb-3 text-end">
                <a href="<?= base_url('forgot_password') ?>" class="text-decoration-none">
                    Forgot Password?
                </a>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>

            <div class="divider">
                <span class="bg-white px-3 text-muted">OR</span>
            </div>

            <div class="text-center">
                <p class="mb-0">Don't have an account? 
                    <a href="<?= base_url('signup') ?>" class="text-decoration-none fw-bold">Sign Up</a>
                </p>
            </div>

            <div class="text-center mt-3">
                <a href="<?= base_url('/') ?>" class="text-muted text-decoration-none">
                    ← Back to Home
                </a>
            </div>
        </form>
    </div>
</div>

<?= $this->include('include/view_footer') ?>