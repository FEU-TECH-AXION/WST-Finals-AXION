<main>
    <section class="hero-bg">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4 border-0">
                    <div class="col col-md-5 mx-auto">
                        <form action="<?= base_url('equipments/equipmentsinsert') ?>" method="post">
                            <h4 class="mb-3 text-center">Add New Equipment</h4>

                            <div class="form-group mb-3">
                                <label for="equipment_name" class="form-label">Equipment Name</label>
                                <input type="text" name="equipment_name" id="equipment_name"
                                    class="form-control <?= isset($validation) && $validation->hasError('equipment_name') ? 'is-invalid' : '' ?>"
                                    value="<?= set_value('equipment_name') ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('equipment_name') : '' ?>
                                    <?= isset($nameExists) ? esc($nameExists) : '' ?>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="price" class="form-label">Price (₱)</label>
                                <input type="number" name="price" id="price"
                                    class="form-control <?= isset($validation) && $validation->hasError('price') ? 'is-invalid' : '' ?>"
                                    value="<?= set_value('price') ?>" step="0.01">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('price') : '' ?>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label for="stock" class="form-label">Stock</label>
                                <input type="number" name="stock" id="stock"
                                    class="form-control <?= isset($validation) && $validation->hasError('stock') ? 'is-invalid' : '' ?>"
                                    value="<?= set_value('stock') ?>">
                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('stock') : '' ?>
                                </div>
                            </div>


                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success px-4">Save</button>
                                <a href="<?= base_url('equipments'); ?>" class="btn btn-warning px-4">Back</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
