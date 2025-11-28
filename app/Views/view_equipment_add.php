<main>
    <section class="hero-bg">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow p-4 border-0">
                    <div class="col col-md-8 mx-auto">

                        <form action="<?= base_url('equipments/insert') ?>" method="post">
                            <h4 class="mb-3 text-center">Add New Equipment</h4>

                            <!-- Item Name -->
                            <div class="form-group mb-3">
                                <label for="item_name" class="form-label">Item Name</label>
                                <input type="text" 
                                       name="item_name" 
                                       id="item_name"
                                       class="form-control <?= isset($validation) && $validation->hasError('item_name') ? 'is-invalid' : '' ?>"
                                       value="<?= set_value('item_name') ?>">

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('item_name') : '' ?>
                                </div>
                            </div>

                            <!-- Item Type -->
                            <div class="form-group mb-3">
                                <label for="item_type" class="form-label">Item Type</label>
                                <input type="text" 
                                       name="item_type" 
                                       id="item_type"
                                       class="form-control <?= isset($validation) && $validation->hasError('item_type') ? 'is-invalid' : '' ?>"
                                       value="<?= set_value('item_type') ?>">

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('item_type') : '' ?>
                                </div>
                            </div>

                            <!-- Parent Item (nullable) -->
                            <div class="form-group mb-3">
                                <label for="parent_item_id" class="form-label">Parent Item (Optional)</label>
                                <input type="number" 
                                       name="parent_item_id" 
                                       id="parent_item_id"
                                       class="form-control <?= isset($validation) && $validation->hasError('parent_item_id') ? 'is-invalid' : '' ?>"
                                       value="<?= set_value('parent_item_id') ?>">

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('parent_item_id') : '' ?>
                                </div>
                            </div>

                            <!-- Quantity -->
                            <div class="form-group mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" 
                                       name="quantity" 
                                       id="quantity"
                                       class="form-control <?= isset($validation) && $validation->hasError('quantity') ? 'is-invalid' : '' ?>"
                                       value="<?= set_value('quantity') ?>">

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('quantity') : '' ?>
                                </div>
                            </div>

                            <!-- Item Condition -->
                            <div class="form-group mb-3">
                                <label for="item_condition" class="form-label">Condition</label>
                                <select name="item_condition" 
                                        id="item_condition" 
                                        class="form-control <?= isset($validation) && $validation->hasError('item_condition') ? 'is-invalid' : '' ?>">
                                    <option value="">Select Condition</option>
                                    <option value="Good" <?= set_select('item_condition', 'Good') ?>>Good</option>
                                    <option value="Fair" <?= set_select('item_condition', 'Fair') ?>>Fair</option>
                                    <option value="Poor" <?= set_select('item_condition', 'Poor') ?>>Poor</option>
                                </select>

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('item_condition') : '' ?>
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="form-group mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" 
                                       name="location" 
                                       id="location"
                                       class="form-control <?= isset($validation) && $validation->hasError('location') ? 'is-invalid' : '' ?>"
                                       value="<?= set_value('location') ?>">

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('location') : '' ?>
                                </div>
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" 
                                        id="status"
                                        class="form-control <?= isset($validation) && $validation->hasError('status') ? 'is-invalid' : '' ?>">
                                    <option value="">Select Status</option>
                                    <option value="Available" <?= set_select('status', 'Available') ?>>Available</option>
                                    <option value="Borrowed" <?= set_select('status', 'Borrowed') ?>>Borrowed</option>
                                    <option value="Damaged" <?= set_select('status', 'Damaged') ?>>Damaged</option>
                                </select>

                                <div class="invalid-feedback">
                                    <?= isset($validation) ? $validation->getError('status') : '' ?>
                                </div>
                            </div>

                            <!-- Buttons -->
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
