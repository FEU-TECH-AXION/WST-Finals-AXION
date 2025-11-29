<main class="d-flex align-items-center justify-content-center">
    <section class="hero-bg py-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">

                    <!-- Top Buttons -->
                    <div class="d-flex justify-content-center mb-4 gap-2">
                        <a href="#active-equipment" class="btn btn-primary">ACTIVE EQUIPMENT LIST</a>
                        <a href="#unusable-equipment" class="btn btn-warning">UNUSABLE EQUIPMENT REPORT</a>
                        <a href="#user-history" class="btn btn-info">USER BORROWING HISTORY</a>
                    </div>

                    <!-- ACTIVE EQUIPMENT LIST -->
                    <div id="active-equipment" class="card shadow p-4 border-0 mb-4">
                        <h3 class="mb-3">Active Equipment List</h3>

                        <form method="get" class="mb-3 d-flex align-items-center gap-2">
                            <label>Filter by Condition:</label>
                            <select name="condition" class="form-select w-auto">
                                <option value="">All</option>
                                <option value="good" <?= ($conditionFilter ?? '')=='good' ? 'selected' : '' ?>>Good</option>
                                <option value="fair" <?= ($conditionFilter ?? '')=='fair' ? 'selected' : '' ?>>Fair</option>
                            </select>
                            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Condition</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($equipmentsActive)): ?>
                                        <?php foreach ($equipmentsActive as $eq): ?>
                                            <tr>
                                                <td><?= esc($eq['item_name']); ?></td>
                                                <td><?= esc(ucfirst($eq['item_type'])); ?></td>
                                                <td><?= esc($eq['quantity']); ?></td>
                                                <td><?= esc(ucfirst($eq['item_condition'])); ?></td>
                                                <td><?= esc($eq['location']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">No active equipment found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- UNUSABLE EQUIPMENT REPORT -->
                    <div id="unusable-equipment" class="card shadow p-4 border-0 mb-4">
                        <h3 class="mb-3">Unusable Equipment Report</h3>

                        <form method="get" class="mb-3 d-flex align-items-center gap-2">
                            <label>Filter by Condition:</label>
                            <select name="condition" class="form-select w-auto">
                                <option value="">All</option>
                                <option value="broken" <?= ($conditionFilter ?? '')=='broken' ? 'selected' : '' ?>>Broken</option>
                                <option value="under repair" <?= ($conditionFilter ?? '')=='under repair' ? 'selected' : '' ?>>Under Repair</option>
                            </select>
                            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Condition</th>
                                        <th>Location</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($equipmentsUnusable)): ?>
                                        <?php foreach ($equipmentsUnusable as $eq): ?>
                                            <tr>
                                                <td><?= esc($eq['item_name']); ?></td>
                                                <td><?= esc(ucfirst($eq['item_type'])); ?></td>
                                                <td><?= esc($eq['quantity']); ?></td>
                                                <td><?= esc(ucfirst($eq['item_condition'])); ?></td>
                                                <td><?= esc($eq['location']); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="5" class="text-muted">No unusable equipment found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- USER BORROWING HISTORY -->
                    <div id="user-history" class="card shadow p-4 border-0 mb-4">
                        <h3 class="mb-3">User Borrowing History</h3>

                        <form method="get" class="mb-3 d-flex align-items-center gap-2">
                            <label>Filter by Role:</label>
                            <select name="role" class="form-select w-auto">
                                <option value="">All</option>
                                <option value="student" <?= ($roleFilter ?? '')=='student' ? 'selected' : '' ?>>Student</option>
                                <option value="associate" <?= ($roleFilter ?? '')=='associate' ? 'selected' : '' ?>>Associate</option>
                            </select>
                            <button type="submit" class="btn btn-secondary btn-sm">Filter</button>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle text-center">
                                <thead>
                                    <tr>
                                        <th>User Name</th>
                                        <th>Role</th>
                                        <th>Item Name</th>
                                        <th>Action</th>
                                        <th>Date</th>
                                        <th>Returned Date</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($history)): ?>
                                        <?php foreach ($history as $h): ?>
                                            <tr>
                                                <td><?= esc($h['first_name'].' '.$h['last_name']); ?></td>
                                                <td><?= esc(ucfirst($h['role'])); ?></td>
                                                <td><?= esc($h['item_name']); ?></td>
                                                <td><?= esc(ucfirst($h['action'])); ?></td>
                                                <td><?= esc($h['borrowed_date'] ?? $h['date_created']); ?></td>
                                                <td><?= esc($h['returned_date'] ?? ''); ?></td>
                                                <td><?= esc(ucfirst($h['status'] ?? '')); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-muted">No borrowing history found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</main>

<script>
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e){
        e.preventDefault();
        document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
    });
});
</script>
