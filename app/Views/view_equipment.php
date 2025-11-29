<main>
    <section class="hero-bg py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="card shadow p-4 border-0">
                        <div class="col col-md-10 mx-auto">

                            <h3 class="mb-3">Equipment Details</h3>

                            <!-- Item Name -->
                            <div class="form-group mb-2">
                                <label class="form-label">Item Name</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['item_name']); ?>">
                            </div>

                            <!-- Item Type -->
                            <div class="form-group mb-2">
                                <label class="form-label">Item Type</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['item_type']); ?>">
                            </div>

                            <!-- Quantity -->
                            <div class="form-group mb-2">
                                <label class="form-label">Quantity</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['quantity']); ?>">
                            </div>

                            <!-- Item Condition -->
                            <div class="form-group mb-2">
                                <label class="form-label">Condition</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['item_condition']); ?>">
                            </div>

                            <!-- Location -->
                            <div class="form-group mb-2">
                                <label class="form-label">Location</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['location']); ?>">
                            </div>

                            <!-- Status -->
                            <div class="form-group mb-2">
                                <label class="form-label">Status</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['status']); ?>">
                            </div>

                            <!-- Date Created -->
                            <div class="form-group mb-2">
                                <label class="form-label">Date Created</label>
                                <input type="text" 
                                       class="form-control" 
                                       readonly
                                       value="<?= esc($equipment['datecreated']); ?>">
                            </div>

                            <div class="form-group mt-3">
                                <a href="<?= base_url('equipments'); ?>" class="btn btn-warning px-4">Back</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
