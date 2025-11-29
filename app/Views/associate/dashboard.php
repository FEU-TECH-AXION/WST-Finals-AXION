<?php
// ==============================================
// File: app/Views/associate/dashboard.php
// ==============================================
?>
<?= $this->include('include/view_head') ?>
<style>
.dashboard-container { min-height: 100vh; background: #f8f9fa; }
.navbar-custom { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white; }
.sidebar { min-height: calc(100vh - 56px); background: white; border-right: 1px solid #dee2e6; padding: 20px; }
.content-area { padding: 30px; }
.stat-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
</style>
<nav class="navbar navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white">ITSO Associate Dashboard</span>
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
                <li class="nav-item mb-2"><a class="nav-link" href="#">My Tickets</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">Assigned Tasks</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('profile') ?>">Profile</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">Knowledge Base</a></li>
            </ul>
        </div>
        <div class="col-md-10 content-area">
            <h2 class="mb-4">Associate Dashboard</h2>
            <div class="row">
                <div class="col-md-4 mb-4"><div class="stat-card"><h3>12</h3><p class="text-muted">Assigned Tickets</p></div></div>
                <div class="col-md-4 mb-4"><div class="stat-card"><h3>8</h3><p class="text-muted">Resolved Today</p></div></div>
                <div class="col-md-4 mb-4"><div class="stat-card"><h3>4</h3><p class="text-muted">Pending</p></div></div>
            </div>
            <div class="stat-card mt-4"><h4>Your Tasks</h4><p class="text-muted">No tasks assigned yet</p></div>
        </div>
    </div>
</div>
<?= $this->include('include/footer') ?>