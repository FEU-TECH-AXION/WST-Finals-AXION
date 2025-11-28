<main>
   <section class="hero-bg py-5">
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-md-7">
               <div class="card shadow p-4 border-0">
                  <div class="col col-md-10 mx-auto">
                     <h3 class="mb-3">Equipment Details</h3>
                     <form action="" method="post">
                        <div class="form-group mb-2">
                           <label for="equipment_name" class="form-label">Equipment Name</label>
                           <input type="text" name="equipment_name" id="equipment_name" class="form-control" readonly value="<?= esc($equipment['equipment_name']); ?>">
                        </div>

                        <div class="form-group mb-2">
                           <label for="price" class="form-label">Price (₱)</label>
                           <input type="text" name="price" id="price" class="form-control" readonly value="<?= esc($equipment['price']); ?>">
                        </div>

                        <div class="form-group mb-2">
                           <label for="stock" class="form-label">Stock</label>
                           <input type="text" name="stock" id="stock" class="form-control" readonly value="<?= esc($equipment['stock']); ?>">
                        </div>

                        <div class="form-group mb-2">
                           <label for="datecreated" class="form-label">Date Created</label>
                           <input type="text" name="datecreated" id="datecreated" class="form-control" readonly value="<?= esc($equipment['datecreated']); ?>">
                        </div>

                        <div class="form-group">
                           <a href="<?= base_url('equipments'); ?>" class="btn btn-warning">Back</a>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</main>
