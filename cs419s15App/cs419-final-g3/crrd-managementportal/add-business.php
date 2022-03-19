<?php include("_header.php"); ?>
    <div class="page-wrapper page-add-business">
    	<h1 class="page-title">Add New Business</h1>
        <form action="form-process.php" method="POST" class="form-horizontal" id="form_add_business">
            <div class="form-group">
                <div class="col-md-4">
                    <label for="business_name">Business Name <span class="req">*</span></label>
                    <input class="form-control"  type="text" name="business_name" id="business_name">
                </div>
                <div class="col-md-2">
                    <label for="phone">Phone</label>
                    <input class="form-control" type="text" name="phone" id="phone" placeholder="(555) 555-5555">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="website">Website</label>
                    <input class="form-control" type="text" name="website" id="website" placeholder="http://www.example.com">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="address">Address <span class="req">*</span></label>
                    <input class="form-control" type="text" name="address" id="address">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="address2">Address 2</label>
                    <input class="form-control" type="text" name="address2" id="address2">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3">
                    <label for="city">City <span class="req">*</span></label>
                    <input class="form-control" type="text" name="city" id="city">
                </div>
                <div class="col-md-1">
                    <label for="state">State <span class="req">*</span></label>
                    <input class="form-control" type="text" name="state" id="state" placeholder="OR">
                </div>
                <div class="col-md-2">
                    <label for="zip">Zip <span class="req">*</span></label>
                    <input class="form-control" type="text" name="zip" id="zip">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="hours">Hours</label>
                    <textarea class="form-control" name="hours" id="hours" rows="5" placeholder="8:00am - 5:00pm M-F"></textarea>
                </div>
            </div>
            <div class="checkbox">
                <label for="reuse">
                    <input type="checkbox" name="reuse" id="reuse">
                    Reuse facility
                </label>
            </div>
            <div class="checkbox">
                <label for="repair">
                    <input type="checkbox" name="repair" id="repair">
                    Repair facility
                </label>
            </div>
            <h3>Category <span class="req">*</span></h3>
            <div class="form-group">
                <?php $cats = get_main_categories(); ?>
                <ul class="main-categories">
                <?php foreach ($cats AS $category): ?>
                    <li class="checkbox">
                        <label class="main-cat">
                            <input type="checkbox" class="main-cat-checkbox" name="category[]" value="<?php echo $category->id; ?>">
                            <strong><?php echo $category->name; ?></strong>
                        </label>
                        <?php $children_cats = get_children_categories($category->id); ?>
                        <ul class="subcategories">
                        <?php foreach ($children_cats AS $child_category): ?>
                            <li class="checkbox">
                                <label class="sub-cat">
                                    <input type="checkbox" class="sub-cat-checkbox" name="category[]" value="<?php echo $child_category->id; ?>">
                                    <?php echo $child_category->name; ?>
                                </label>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="notes">Notes</label>
                    <textarea name="notes" rows="5" id="notes" class="form-control"></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-2">
                    <input type="hidden" name="action" value="add_business">
                    <input class="form-control btn-success" type="submit" value="Add Business">
                </div>
                <div class="col-md-1">
                    <a href="<?php echo SITE_ROOT; ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php include("_footer.php"); ?>