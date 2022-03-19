<?php include("_header.php"); ?>
<?php
    if (!empty($_GET['id']))
    {
        $current_category = get_category($_GET['id']);
        if (empty($current_category))
        {
            die("No category exists with the specified ID.");
        }
    }
    else
        die("No category ID specified.");
?>
    <div class="page-wrapper page-add-business">
        <h1 class="page-title">Update Category</h1>
        <form action="form-process.php" method="POST" class="form-horizontal" id="form_update_category">
            <div class="form-group">
                <div class="col-md-3">
                    <label for="category">Category <span class="req">*</span></label>
                    <input class="form-control" type="text" name="category" id="category"  value="<?php echo $current_category->name; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3">
                    <label for="parent_category">Parent Category</label>
                    <select class="form-control" name="parent_category" id="parent_category">
                    <?php $cats = get_main_categories(); ?>
                        <option value="">None</option>
                    <?php foreach ($cats AS $category): ?>
                        <?php if ($category->id != $current_category->id): ?>
                        <option value="<?php echo $category->id; ?>"<?php echo ($category->id == $current_category->parent_id) ? ' selected' : ''; ?>><?php echo $category->name; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-2">
                    <input type="hidden" name="category_id" value="<?php echo $current_category->id; ?>">
                    <input type="hidden" name="action" value="update_category">
                    <input class="form-control btn btn-success" type="submit" value="Update Category">
                </div>
                <div class="col-md-1">
                    <a href="manage-categories.php" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php include("_footer.php"); ?>