<?php
// File: app/Views/auth/signup.php
?>
<?= $this->include('include/view_nav') ?>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px 0;
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
</style>

<div class="auth-container">
    <div class="auth-card">
        <div class="text-center">
            <img src="<?= base_url('/public/assets/img/logoPlaceholder.png') ?>" alt="ITSO Logo" class="auth-logo">
            <h2 class="auth-title">Create Account</h2>
            <p class="auth-subtitle">Join ITSO to access our services</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <?php if (isset($validation)): ?>
            <div class="alert alert-danger"><?= $validation->listErrors() ?></div>
        <?php endif; ?>

        <form method="post" action="<?= base_url('signup') ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <!-- Profile Photo Upload -->
            <div class="mb-3 text-center">
                <div class="profile-photo-preview mb-2">
                    <img id="photoPreview" src="<?= base_url('/public/assets/img/default-avatar.png') ?>" 
                        alt="Profile Preview" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid #667eea;">
                </div>
                <label for="profile_photo" class="btn btn-outline-primary btn-sm">
                    📷 Choose Photo
                </label>
                <input type="file" class="d-none" id="profile_photo" name="profile_photo" 
                    accept="image/*" onchange="previewPhoto(event)">
                <div class="text-muted" style="font-size: 0.875rem;">Optional (JPG, PNG, max 2MB)</div>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" 
                    placeholder="Enter your full name" value="<?= old('name') ?>" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" 
                    placeholder="Enter your email" value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" 
                    placeholder="Create a password" required>
                <div style="font-size: 0.875rem; color: #666; margin-top: 5px;">
                    Must be at least 6 characters long
                </div>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                    placeholder="Confirm your password" required>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>

            <div class="text-center">
                <p class="mb-0">Already have an account? 
                    <a href="<?= base_url('login') ?>" class="text-decoration-none fw-bold">Login</a>
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

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('photoPreview').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?= $this->include('include/view_footer') ?>