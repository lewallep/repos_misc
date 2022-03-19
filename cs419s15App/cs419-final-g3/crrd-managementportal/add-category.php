<?php include("_header.php"); ?>
    <div class="page-wrapper page-add-business">
        <h1 class="page-title">Add New Category</h1>
        <form action="form-process.php" method="POST" class="form-horizontal" id="form_add_category">
            <div class="form-group">
                <div class="col-md-3">
                    <label for="category">Category <span class="req">*</span></label>
                    <input class="form-control" type="text" name="category" id="category">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3">
                    <label for="parent_category">Parent Category</label>
                    <select class="form-control" name="parent_category" id="parent_category">
                    <?php $cats = get_main_categories(); ?>
                        <option value="">None</option>
                    <?php foreach ($cats AS $category): ?>
                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-2">
                    <input type="hidden" name="action" value="add_category">
                    <input class="form-control btn btn-success" type="submit" value="Add Category">
                </div>
                <div class="col-md-1">
                    <a href="manage-categories.php" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php include("_footer.php"); ?>