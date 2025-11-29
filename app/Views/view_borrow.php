<main class="d-flex align-items-center justify-content-center">
    <section class="hero-bg py-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow p-4 border-0">

                        <h3 class="mb-3">Borrow Equipment</h3>

                        <?php if (session()->getFlashdata('success')): ?>
                            <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
                        <?php endif; ?>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
                        <?php endif; ?>

                        <div class="table-responsive">
                            <table class="table table-hover table-striped align-middle">
                                <thead class="text-center">
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Type</th>
                                        <th>Quantity</th>
                                        <th>Borrow</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if (!empty($equipments)): ?>
                                        <?php foreach ($equipments as $eq): ?>
                                            <tr>
                                                <td><?= esc($eq['item_name']); ?></td>
                                                <td><?= esc($eq['item_type']); ?></td>
                                                <td><?= esc($eq['quantity']); ?></td>
                                                <td>
                                                    <?php if ($eq['quantity'] > 0): ?>
                                                        <a href="<?= base_url('borrow/form/' . $eq['item_id']); ?>" 
                                                           class="btn btn-primary btn-sm">Borrow</a>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Unavailable</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="text-muted">No equipment available.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>

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
