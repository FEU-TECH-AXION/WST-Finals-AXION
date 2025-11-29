<?php
// ==============================================
// File: app/Views/itso/users.php
// ==============================================
?>
<?= $this->include('include/view_nav_itso') ?>
<style>
.dashboard-container { min-height: 100vh; background: #f8f9fa; }
.navbar-custom { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
.sidebar { min-height: calc(100vh - 56px); background: white; border-right: 1px solid #dee2e6; padding: 20px; }
.content-area { padding: 30px; }
.user-card { background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
.user-photo { width: 60px; height: 60px; object-fit: cover; border-radius: 50%; }
.badge-role { padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; }
.badge-itso { background: #667eea; color: white; }
.badge-associate { background: #f093fb; color: white; }
.badge-student { background: #4facfe; color: white; }
.badge-active { background: #28a745; color: white; }
.badge-inactive { background: #6c757d; color: white; }
.btn-action { padding: 5px 15px; margin: 0 3px; font-size: 0.875rem; }
</style>

<nav class="navbar navbar-custom">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1 text-white">User Management</span>
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
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('itso/dashboard') ?>">Dashboard</a></li>
                <li class="nav-item mb-2"><a class="nav-link active" href="<?= base_url('itso/users') ?>">User Management</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('equipments') ?>">Equipment Management</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">Reports</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="<?= base_url('profile') ?>">Profile</a></li>
                <li class="nav-item mb-2"><a class="nav-link" href="#">Settings</a></li>
            </ul>
        </div>
        
        <div class="col-md-10 content-area">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Management</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle"></i> Add New User
                </button>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="user-card">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <input type="text" id="searchUser" class="form-control" placeholder="Search by name or email...">
                    </div>
                    <div class="col-md-3">
                        <select id="filterRole" class="form-select">
                            <option value="">All Roles</option>
                            <option value="itso">ITSO</option>
                            <option value="associate">Associate</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select id="filterStatus" class="form-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>User ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Date Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="userTableBody">
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                            $userPhoto = $user['profile_photo'] 
                                                ? base_url('public/uploads/profiles/' . $user['profile_photo'])
                                                : base_url('/public/assets/img/default-avatar.png');
                                            ?>
                                            <img src="<?= $userPhoto ?>" alt="User" class="user-photo">
                                        </td>
                                        <td><?= esc($user['user_id']) ?></td>
                                        <td><?= esc($user['name']) ?></td>
                                        <td><?= esc($user['email']) ?></td>
                                        <td>
                                            <span class="badge badge-<?= $user['role'] ?>">
                                                <?= strtoupper($user['role']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-<?= $user['status'] ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                        <td><?= date('M d, Y', strtotime($user['date_created'])) ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-info btn-action" onclick="viewUser('<?= $user['user_id'] ?>')">
                                                <i class="bi bi-eye"></i> View
                                            </button>
                                            <button class="btn btn-sm btn-warning btn-action" onclick="editUser('<?= $user['user_id'] ?>')">
                                                <i class="bi bi-pencil"></i> Edit
                                            </button>
                                            <?php if ($user['status'] === 'active'): ?>
                                                <button class="btn btn-sm btn-danger btn-action" onclick="deactivateUser('<?= $user['user_id'] ?>')">
                                                    <i class="bi bi-x-circle"></i> Deactivate
                                                </button>
                                            <?php else: ?>
                                                <button class="btn btn-sm btn-success btn-action" onclick="activateUser('<?= $user['user_id'] ?>')">
                                                    <i class="bi bi-check-circle"></i> Activate
                                                </button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="text-center">No users found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('itso/users/create') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">User ID</label>
                        <input type="text" name="user_id" class="form-control" required>
                        <small class="text-muted">Format: STD001, ASC001, ITSO001, etc.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="">Select Role</option>
                            <option value="itso">ITSO Personnel</option>
                            <option value="associate">Associate</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('itso/users/update') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" id="edit_user_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" id="edit_role" class="form-select" required>
                            <option value="itso">ITSO Personnel</option>
                            <option value="associate">Associate</option>
                            <option value="student">Student</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password (leave empty to keep current)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control" accept="image/*">
                        <small class="text-muted">Leave empty to keep current photo</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View User Modal -->
<div class="modal fade" id="viewUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <img id="view_photo" src="" alt="User" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover;">
                </div>
                <table class="table">
                    <tr><th>User ID:</th><td id="view_user_id"></td></tr>
                    <tr><th>Name:</th><td id="view_name"></td></tr>
                    <tr><th>Email:</th><td id="view_email"></td></tr>
                    <tr><th>Role:</th><td id="view_role"></td></tr>
                    <tr><th>Status:</th><td id="view_status"></td></tr>
                    <tr><th>Date Created:</th><td id="view_date_created"></td></tr>
                    <tr><th>Last Updated:</th><td id="view_date_updated"></td></tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
const users = <?= json_encode($users ?? []) ?>;

function viewUser(userId) {
    const user = users.find(u => u.user_id === userId);
    if (user) {
        const photoPath = user.profile_photo 
            ? '<?= base_url("public/uploads/profiles/") ?>' + user.profile_photo
            : '<?= base_url("/public/assets/img/default-avatar.png") ?>';
        
        document.getElementById('view_photo').src = photoPath;
        document.getElementById('view_user_id').textContent = user.user_id;
        document.getElementById('view_name').textContent = user.name;
        document.getElementById('view_email').textContent = user.email;
        document.getElementById('view_role').innerHTML = `<span class="badge badge-${user.role}">${user.role.toUpperCase()}</span>`;
        document.getElementById('view_status').innerHTML = `<span class="badge badge-${user.status}">${user.status}</span>`;
        document.getElementById('view_date_created').textContent = new Date(user.date_created).toLocaleDateString();
        document.getElementById('view_date_updated').textContent = new Date(user.date_updated).toLocaleDateString();
        
        new bootstrap.Modal(document.getElementById('viewUserModal')).show();
    }
}

function editUser(userId) {
    const user = users.find(u => u.user_id === userId);
    if (user) {
        document.getElementById('edit_user_id').value = user.user_id;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_role').value = user.role;
        
        new bootstrap.Modal(document.getElementById('editUserModal')).show();
    }
}

function deactivateUser(userId) {
    if (confirm('Are you sure you want to deactivate this user?')) {
        window.location.href = '<?= base_url("itso/users/deactivate/") ?>' + userId;
    }
}

function activateUser(userId) {
    if (confirm('Are you sure you want to activate this user?')) {
        window.location.href = '<?= base_url("itso/users/activate/") ?>' + userId;
    }
}

// Search and filter functionality
document.getElementById('searchUser').addEventListener('input', filterUsers);
document.getElementById('filterRole').addEventListener('change', filterUsers);
document.getElementById('filterStatus').addEventListener('change', filterUsers);

function filterUsers() {
    const search = document.getElementById('searchUser').value.toLowerCase();
    const role = document.getElementById('filterRole').value;
    const status = document.getElementById('filterStatus').value;
    
    const rows = document.querySelectorAll('#userTableBody tr');
    rows.forEach(row => {
        const name = row.cells[2]?.textContent.toLowerCase() || '';
        const email = row.cells[3]?.textContent.toLowerCase() || '';
        const userRole = row.cells[4]?.textContent.toLowerCase() || '';
        const userStatus = row.cells[5]?.textContent.toLowerCase() || '';
        
        const matchSearch = name.includes(search) || email.includes(search);
        const matchRole = !role || userRole.includes(role);
        const matchStatus = !status || userStatus.includes(status);
        
        row.style.display = matchSearch && matchRole && matchStatus ? '' : 'none';
    });
}
</script>

<?= $this->include('include/view_footer') ?>