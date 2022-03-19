<?php include("_header.php"); ?>
    <div class="page-wrapper page-manage-users">
    <?php if(!empty($_SESSION['feedback'])): ?>
        <?php if ($_SESSION['feedback']['type'] == 'error'): ?>
            <div class="alert alert-danger" role="alert">
        <?php else: ?>
            <div class="alert alert-success" role="alert">
        <?php endif; ?>
                <?php echo $_SESSION['feedback']['message']; ?>
            </div>
        <?php unset($_SESSION['feedback']); ?>
    <?php endif; ?>
        <h1 class="page-title">Manage Users</h1>
        <button class="btn add-user add-new">+ Add New</button>
        <div class="row" id="form_add_user" style="display:none;">
            <div class="col-md-4">
                <form action="form-process.php" method="POST">
                    <div class="form-group">
                        <label for="new_user_name">Username <span class="req">*</span></label>
                        <input type="text" class="form-control" name="new_user_name" id="new_user_name">
                    </div>
                    <div class="form-group">
                        <label for="new_user_email">Email <span class="req">*</span></label>
                        <input type="email" class="form-control" name="new_user_email" id="new_user_email">
                    </div>
                    <div class="form-group">
                        <label for="new_user_password">Password <span class="req">*</span></label>
                        <input type="password" class="form-control" name="new_user_password" id="new_user_password">
                    </div>
                    <div class="form-group">
                        <label for="new_user_password_repeat">Re-enter Password <span class="req">*</span></label>
                        <input type="password" class="form-control" name="new_user_password_repeat" id="new_user_password_repeat">
                    </div>
                    <div class="checkbox">
                        <label for="new_user_admin">
                            <input type="checkbox" name="new_user_admin" id="new_user_admin" value="1">
                            Super Admin User (can edit other users)
                        </label>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="action" value="add_user">
                        <input type="submit" class="btn btn-success" id="add_user_save" value="Add User">
                        <input type="button" class="btn btn-default" id="add_user_cancel" value="Cancel">
                    </div>
                </form>
            </div>
        </div>
        <div class="row" id="form_update_user" style="display:none;">
            <div class="col-md-4">
                <form action="form-process.php" method="POST">
                    <div class="form-group">
                        <label for="update_user_name">Username <span class="req">*</span></label>
                        <input type="text" class="form-control" name="update_user_name" id="update_user_name">
                    </div>
                    <div class="form-group">
                        <label for="update_user_email">Email <span class="req">*</span></label>
                        <input type="email" class="form-control" name="update_user_email" id="update_user_email">
                    </div>
                    <div class="checkbox">
                        <label for="password_change">
                            <input type="checkbox" name="password_change" id="password_change" value="1">
                            Change password
                        </label>
                    </div>
                    <div class="update-password" style="display:none;">
                        <div class="form-group">
                            <label for="update_user_password">New Password <span class="req">*</span></label>
                            <input type="password" class="form-control" name="update_user_password" id="update_user_password">
                        </div>
                        <div class="form-group">
                            <label for="update_user_password_repeat">Re-enter Password <span class="req">*</span></label>
                            <input type="password" class="form-control" name="update_user_password_repeat" id="update_user_password_repeat">
                        </div>
                    </div>
                    <div class="checkbox">
                        <label for="update_user_admin">
                            <input type="checkbox" name="update_user_admin" id="update_user_admin" value="1">
                            Super Admin User (can edit other users)
                        </label>
                    </div>
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="action" value="update_user">
                    <input type="submit" class="btn btn-success" id="update_user_save" value="Update User">
                    <input type="button" class="btn btn-default" id="update_user_cancel" value="Cancel">
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Admin</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <?php $users = get_users(); ?>
                    <tbody>
                    <?php $i = 0; ?>
                    <?php foreach ($users AS $user): ?>
                        <?php $i = $i + 1; ?>
                        <tr id="tr_<?php echo $user->user_id; ?>">
                            <td class="uName"><?php echo $user->user_name; ?></td>
                            <td class="uEmail"><?php echo $user->user_email; ?></td>
                            <td class="uAdmin"><?php echo (($user->user_type == 1) ? "Yes" : "No"); ?></td>
                            <td><a href="javascript:void(0);" data-id="<?php echo $user->user_id; ?>" class="edit-user glyphicon glyphicon-pencil"></a></td>
                            <td>
                            <?php if ($_SESSION['user_id'] != $user->user_id): ?>
                                <a href="javascript:void(0);" data-id="<?php echo $user->user_id; ?>" class="delete-user glyphicon glyphicon-trash"></a>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php include("_footer.php"); ?>