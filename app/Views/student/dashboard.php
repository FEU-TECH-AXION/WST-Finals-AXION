<?php
// ==============================================
// File: app/Views/user/dashboard.php
// ==============================================
?>
<?= $this->include('include/view_nav_student') ?>
<style>
.dashboard-container { min-height: 100vh; background: #f8f9fa; }
.navbar-custom { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.sidebar { min-height: calc(100vh - 56px); background: white; border-right: 1px solid #dee2e6; padding: 20px; }
.content-area { padding: 30px; }
.stat-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
.stat-icon { font-size: 2rem; color: #667eea; }text-align: center; transition: transform 0.3s; }
.service-card:hover { transform: translateY(-5px); }
.service-icon { font-size: 3rem; margin-bottom: 15px; }
</style>
<nav class="navbar navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white">AXION User Portal</span>
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
                <li class="nav-item mb-2"><a class="nav-link active" href="<?= base_url('student/dashboard#') ?>">Dashboard</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('borrow') ?>">Borrow</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('borrow') ?>">Return</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('profile') ?>">Profile</a></li>
            </ul>
        </div>
        <div class="col-md-10 content-area">
            <h2 class="mb-4">Welcome to ITSO Portal</h2>
            <div class="row">
                <div class="col-md-4 mb-4"><div class="service-card"><div class="service-icon">💻</div><h5>Technical Support</h5><p class="text-muted">Get help with technical issues</p><a href="#" class="btn btn-primary btn-sm">Submit Request</a></div></div>
                <div class="col-md-4 mb-4"><div class="service-card"><div class="service-icon">📱</div><h5>Software Access</h5><p class="text-muted">Request software licenses</p><a href="#" class="btn btn-primary btn-sm">Request Access</a></div></div>
                <div class="col-md-4 mb-4"><div class="service-card"><div class="service-icon">🌐</div><h5>Network Support</h5><p class="text-muted">Report connectivity issues</p><a href="#" class="btn btn-primary btn-sm">Report Issue</a></div></div>
            </div>
            <div class="card mt-4"><div class="card-body"><h4>My Recent Requests</h4><p class="text-muted">You haven't submitted any requests yet</p></div></div>
        </div>
    </div>
</div>
<?= $this->include('include/view_footer') ?>