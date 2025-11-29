<main>
    <section class="hero-bg py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card shadow p-4 border-0">
                        <h3 class="mb-3">Borrow Equipment</h3>

                        <form action="<?= base_url('borrow/submit'); ?>" method="post">
                            <input type="hidden" name="item_id" value="<?= $equipment['item_id']; ?>">

                            <!-- Item Name -->
                            <div class="form-group mb-3">
                                <label class="form-label">Item Name</label>
                                <input type="text" class="form-control" readonly 
                                       value="<?= esc($equipment['item_name']); ?>">
                            </div>

                            <!-- Accessories -->
                            <div class="form-group mb-3">
                                <label class="form-label">Accessories</label>
                                <input type="text" class="form-control" readonly 
                                       value="<?= empty($accessories) ? 'None' : implode(', ', array_column($accessories, 'item_name')); ?>">
                            </div>

                            <!-- Borrow Date -->
                            <div class="form-group mb-3">
                                <label class="form-label">Borrow Date</label>
                                <input type="text" class="form-control" readonly 
                                       value="<?= date('Y-m-d H:i'); ?>">
                            </div>

                            <!-- Expected Return -->
                            <div class="form-group mb-3">
                                <label class="form-label">Expected Return Date</label>
                                <input type="date" name="expected_return_date" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary px-4">Borrow Now</button>
                            <a href="<?= base_url('borrow'); ?>" class="btn btn-secondary">Cancel</a>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
