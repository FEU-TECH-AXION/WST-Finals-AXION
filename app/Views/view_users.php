<main>
    <section>
        <div>
            <h3>Users List</h3>
        </div>
            <div class="col col-md-8 mx-auto">
                <a href="<?= base_url('users/add'); ?>" class="btn btn-primary">
                    <span class="material-symbols-outlined">person_add</span> Add New User
                </a>
                <?php
                    if(session('success')):
                ?>
                        <div class="alert alert-success">
                            <p>
                                <?= session('success'); ?>
                            </p>
                        </div>
                <?php
                    endif;
                ?>
                <table class="table table-hover table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>Image</th>
                            <th>User Name</th>
                            <th>Full Name</th>
                            <th>E-Mail</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user): ?>
                        <tr>
                            <td><a href="<?= base_url("public/profile_images/".$user['profile']); ?>" target="_blank">
                            <img src="<?php
                            if($user['profile']):
                                echo  base_url("public/profile_images/thumbs/".$user['profile']);
                            else:
                                echo base_url("public/profile_images/noprofile.png");
                            endif;
                            ?>" style="height: 50px"></a>
                            </td>
                            <td><?= $user['username']; ?></td>
                            <td><?= $user['fullname']; ?></td>
                            <td><?= $user['email']; ?></td>
                            <td>
                                <a href="<?= base_url('users/view/'.$user['id']) ?>" class="btn btn-secondary btn-sm">
                                    <span class="material-symbols-outlined">
                                        person_search
                                    </span>
                                </a>
                                <a href="<?= base_url('users/edit/'.$user['id']) ?>" class="btn btn-warning btn-sm">
                                    <span class="material-symbols-outlined">
                                        person_edit
                                    </span>
                                </a>
                                <a href="<?= base_url('users/delete/'.$user['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete user?')">
                                    <span class="material-symbols-outlined">
                                        person_remove
                                    </span>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?= $pager->links(); ?>
            </div>
        </div>
    </section>
</main>