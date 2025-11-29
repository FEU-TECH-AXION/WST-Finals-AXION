<?php
// File: app/Views/profile.php
?>
<?= $this->include('include/header') ?>

<style>
.profile-container { min-height: 100vh; background: #f8f9fa; padding: 30px 0; }
.profile-card { background: white; border-radius: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 40px; max-width: 600px; margin: 0 auto; }
.profile-photo-large { width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #667eea; margin-bottom: 20px; }
.btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
</style>

<div class="profile-container">
    <div class="container">
        <div class="profile-card">
            <div class="text-center mb-4">
                <h2>My Profile</h2>
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

            <form method="post" action="<?= base_url('profile/update') ?>" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="text-center mb-4">
                    <?php 
                    $photoPath = session()->get('profile_photo') 
                        ? base_url('public/uploads/profiles/' . session()->get('profile_photo'))
                        : base_url('/public/assets/img/default-avatar.png');
                    ?>
                    <img id="currentPhoto" src="<?= $photoPath ?>" alt="Profile Photo" class="profile-photo-large">

                    <div class="mt-3">
                        <label for="profile_photo" class="btn btn-primary btn-sm">📷 Change Photo</label>
                        <input type="file" class="d-none" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewNewPhoto(event)">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= esc(session()->get('name')) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?= esc(session()->get('email')) ?>" readonly>
                    <small class="text-muted">Email cannot be changed</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <input type="text" class="form-control" value="<?= ucfirst(session()->get('role')) ?>" readonly>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Change Password (Optional)</h5>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Leave blank to keep current password">
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" name="new_password">
                </div>

                <div class="mb-3">
                    <label for="confirm_new_password" class="form-label">Confirm New Password</label>
                    <input type="password" class="form-control" id="confirm_new_password" name="confirm_new_password">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Update Profile</button>
                    <a href="<?= base_url($backLink ?? '/') ?>" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewNewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('currentPhoto').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?= $this->include('include/footer') ?>