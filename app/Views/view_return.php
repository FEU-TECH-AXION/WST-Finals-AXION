<main class="d-flex align-items-center justify-content-center">
    <section class="hero-bg py-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card shadow p-4 border-0">

                        <h3 class="mb-3">Borrowed Items</h3>

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
                                        <th>Borrow Date</th>
                                        <th>Expected Return</th>
                                        <th>Accessories</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php if (!empty($borrowed_items)): ?>
                                        <?php foreach ($borrowed_items as $item): ?>
                                            <tr>
                                                <td><?= esc($item['item_name']); ?></td>
                                                <td><?= esc($item['item_type']); ?></td>
                                                <td><?= esc($item['borrow_date']); ?></td>
                                                <td><?= esc($item['expected_return_date']); ?></td>
                                                <td>
                                                    <?= empty($item['accessories']) 
                                                        ? 'None' 
                                                        : implode(', ', array_column($item['accessories'], 'item_name')) ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('return/form/' . $item['borrow_id']); ?>" 
                                                       class="btn btn-success btn-sm" 
                                                       title="Return">
                                                        <span class="material-symbols-outlined">assignment_return</span> Return
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-muted">No borrowed items found.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div> <!-- table-responsive -->

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
