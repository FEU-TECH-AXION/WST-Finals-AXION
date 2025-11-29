<main class="d-flex align-items-center justify-content-center">
    <section class="hero-bg py-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow p-4 border-0">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h3 class="mb-0">Reserve Equipment</h3>
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
                                        <th>Availability</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if (!empty($equipments)): ?>
                                        <?php foreach ($equipments as $eq): ?>
                                            <tr>
                                                <td><?= esc($eq['item_name']); ?></td>
                                                <td><?= esc($eq['item_type']); ?></td>
                                                <td>
                                                    <?= $eq['reserved'] ? '<span class="text-danger">Reserved</span>' : '<span class="text-success">Available</span>'; ?>
                                                </td>
                                                <td>
                                                    <?php if (!$eq['reserved']): ?>
                                                        <a href="<?= base_url('reserve/form/' . $eq['item_id']); ?>" 
                                                           class="btn btn-primary btn-sm">
                                                            Reserve
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn btn-secondary btn-sm" disabled>Reserve</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-muted">No equipment available for reservation.</td>
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
