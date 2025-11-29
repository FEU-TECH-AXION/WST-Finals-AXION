<?php
// ==============================================
// File: app/Views/admin/dashboard.php
// ==============================================
?>
<?= $this->include('include/view_nav_itso') ?>
<style>
.dashboard-container { min-height: 100vh; background: #f8f9fa; }
.navbar-custom { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.sidebar { min-height: calc(100vh - 56px); background: white; border-right: 1px solid #dee2e6; padding: 20px; }
.content-area { padding: 30px; }
.stat-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.stat-icon { font-size: 2rem; color: #667eea; }
</style>
<nav class="navbar navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white">ITSO Admin Dashboard</span>
        <div class="d-flex align-items-center">
            <?php 
            $photoPath = session()->get('profile_photo')
                ? base_url('public/uploads/profiles/' . session()->get('profile_photo'))
                : base_url('/public/assets/img/default-avatar.png');
            ?>
            <img src="<?= $photoPath ?>" alt="Profile" class="rounded-circle me-2" style="width: 35px; height: 35px; object-fit: cover; border: 2px solid white;">
            <span class="text-white me-3">Welcome, <?= esc(session()->get('name')) ?></span>
            <a href="<?= base_url('logout') ?>" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>
<div class="dashboard-container">
    <div class="row g-0">
        <div class="col-md-2 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a class="nav-link active" href="#">Dashboard</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">User Management</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('equipments') ?>">Equipment Management</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">Reports</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('profile') ?>">Profile</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">Settings</a></li>
            </ul>
        </div>
        <div class="col-md-10 content-area">
            <h2 class="mb-4">Admin Dashboard</h2>
            <div class="row">
                <div class="col-md-3 mb-4"><div class="stat-card"><div class="stat-icon">👥</div><h3 class="mt-3">150</h3><p class="text-muted">Total Users</p></div></div>
                <div class="col-md-3 mb-4"><div class="stat-card"><div class="stat-icon">📊</div><h3 class="mt-3">45</h3><p class="text-muted">Pending Requests</p></div></div>
                <div class="col-md-3 mb-4"><div class="stat-card"><div class="stat-icon">✅</div><h3 class="mt-3">320</h3><p class="text-muted">Completed</p></div></div>
                <div class="col-md-3 mb-4"><div class="stat-card"><div class="stat-icon">💻</div><h3 class="mt-3">25</h3><p class="text-muted">Active Systems</p></div></div>
            </div>
            <div class="stat-card mt-4"><h4>Recent Activities</h4><p class="text-muted">No recent activities to display</p></div>
        </div>
    </div>
</div>
<?= $this->include('include/view_footer') ?>

