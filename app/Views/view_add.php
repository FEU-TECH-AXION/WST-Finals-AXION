<main>
    <div class="col col-md-5 mx-auto">
        <?php
            if(session('errors')):
        ?>
                <div class="alert alert-danger">
                    <p>
                        <?= implode('<br>', session('errors')); ?>
                    </p>
                </div>
        <?php
            endif;
        ?>
        <form action="<?= base_url('users/insert') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile">Profile Picture</label>
                <input type="file" name="profile" id="profile" accept="image/*">
            </div>
            <div class="form-group mb-2">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username" class="form-control" value="<?= set_value('username'); ?>">
            </div>
            <div class="form-group mb-2">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label for="confirmpassword" class="form-label">Confirm Password</label>
                <input type="password" name="confirmpassword" id="confirmpassword" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label for="fullname" class="form-label">Full Name</label>
                <input type="text" name="fullname" id="fullname" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-success">Save</button>
                <a href="<?= base_url('users'); ?>" class="btn btn-warning">Back</a>
            </div>
        </form>
    </div>
</main>