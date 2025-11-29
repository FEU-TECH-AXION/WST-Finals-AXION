<main class="d-flex align-items-center justify-content-center">
    <section class="hero-bg py-5 w-100">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow p-4 border-0">
                        <h3 class="mb-3">Reserve Equipment</h3>

                        <?php if (session()->getFlashdata('error')): ?>
                            <div class="alert alert-danger">
                                <?= session()->getFlashdata('error') ?>
                            </div>
                        <?php endif; ?>

                        <form action="<?= base_url('reserve/submit'); ?>" method="POST">
                            <?= csrf_field() ?>

                            <input type="hidden" name="item_id" value="<?= esc($equipment['item_id']); ?>">

                            <div class="mb-3">
                                <label class="form-label">Item Name</label>
                                <input type="text" class="form-control" value="<?= esc($equipment['item_name']); ?>" readonly>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Reserved Date</label>
                                <input type="date" name="reserved_date" class="form-control" min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required>
                                <small class="text-muted">Must be at least 1 day ahead</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Reserve Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
