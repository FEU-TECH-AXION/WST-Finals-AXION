<main class="d-flex align-items-center justify-content-center">
    <section class="hero-bg py-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow p-4 border-0">
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Equipments List</h3>
                        
                            <a href="<?= base_url('equipments/add'); ?>" class="btn btn-success">
                                <span class="material-symbols-outlined align-middle">add</span>
                                Add New Equipment
                            </a>
                        </div>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success">
                                <?= session()->getFlashdata('success') ?>
                            </div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="text-center">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Condition</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>

                                <tbody class="text-center">
                                    <?php if (!empty($equipments)): ?>
                                        <?php foreach ($equipments as $equipment): ?>
                                            <tr>
                                                <td><?= esc($equipment['item_name']); ?></td>
                                                <td><?= esc($equipment['item_type']); ?></td>
                                                <td><?= esc($equipment['quantity']); ?></td>
                                                <td><?= esc($equipment['item_condition']); ?></td>
                                                <td><?= esc($equipment['location']); ?></td>
                                                <td><?= esc($equipment['status']); ?></td>
                                                
                                                <td>
                                                    <a href="<?= base_url('equipments/view/' . $equipment['item_id']); ?>" 
                                                       class="btn btn-secondary btn-sm" 
                                                       title="View">
                                                        <span class="material-symbols-outlined">search</span>
                                                    </a>

                                                    <a href="<?= base_url('equipments/edit/' . $equipment['item_id']); ?>" 
                                                       class="btn btn-warning btn-sm" 
                                                       title="Edit">
                                                        <span class="material-symbols-outlined">edit</span>
                                                    </a>

                                                    <a href="<?= base_url('equipments/delete/' . $equipment['item_id']); ?>" 
                                                       class="btn btn-danger btn-sm"
                                                       onclick="return confirm('Are you sure you want to delete this equipment?');"
                                                       title="Delete">
                                                        <span class="material-symbols-outlined">delete</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-muted">No equipment found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                <?= $pager->links(); ?>
                            </div>

                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
