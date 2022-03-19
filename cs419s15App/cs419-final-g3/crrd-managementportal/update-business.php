<?php include("_header.php"); ?>
<?php
    if (!empty($_GET['id']))
    {
        $business_data = get_business($_GET['id']);
        $business = $business_data['info'];
        if (empty($business))
        {
            die("No business exists with the specified ID.");
        }
        $business_categories = $business_data['categories'];
        $category_list = array();
        foreach ($business_categories AS $category)
        {
            $category_list[] = $category->id;
        }
    }
    else
        die("No business ID specified.");
?>
    <div class="page-wrapper page-update-business">
        <h1 class="page-title">Update Business</h1>
        <form action="form-process.php" method="POST" class="form-horizontal" id="form_update_business">
            <div class="form-group">
                <div class="col-md-4">
                    <label for="business_name">Business Name <span class="req">*</span></label>
                    <input class="form-control"  type="text" name="business_name" id="business_name" value="<?php echo $business->name; ?>">
                </div>
                <div class="col-md-2">
                    <label for="phone">Phone</label>
                    <input class="form-control" type="text" name="phone" id="phone" placeholder="(555) 555-5555" value="<?php echo $business->phone; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="website">Website</label>
                    <input class="form-control" type="text" name="website" id="website" placeholder="http://www.example.com" value="<?php echo $business->website; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="address">Address <span class="req">*</span></label>
                    <input class="form-control" type="text" name="address" id="address" value="<?php echo $business->address; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="address2">Address 2</label>
                    <input class="form-control" type="text" name="address2" id="address2" value="<?php echo $business->address2; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-3">
                    <label for="city">City <span class="req">*</span></label>
                    <input class="form-control" type="text" name="city" id="city" value="<?php echo $business->city; ?>">
                </div>
                <div class="col-md-1">
                    <label for="state">State <span class="req">*</span></label>
                    <input class="form-control" type="text" name="state" id="state" placeholder="OR" value="<?php echo $business->state; ?>">
                </div>
                <div class="col-md-2">
                    <label for="zip">Zip <span class="req">*</span></label>
                    <input class="form-control" type="text" name="zip" id="zip" value="<?php echo $business->zip; ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-6">
                    <label for="hours">Hours</label>
                    <textarea class="form-control" name="hours" id="hours" rows="5" placeholder="8:00am - 5:00pm M-F"><?php echo $business->hours; ?></textarea>
                </div>
            </div>
            <div class="checkbox">
                <label for="reuse">
                    <input type="checkbox" name="reuse" id="reuse"<?php echo ($business->reuse == 1) ? ' checked' : ''; ?>>
                    Reuse facility
                </label>
            </div>
            <div class="checkbox">
                <label for="repair">
                    <input type="checkbox" name="repair" id="repair"<?php echo ($business->repair == 1) ? ' checked' : ''; ?>>
                    Repair facility
                </label>
            </div>
            <h3>Category <span class="req">*</span></h3>
            <div class="form-group">
                <?php $cats = get_main_categories(); ?>
                <ul class="main-categories">
                <?php foreach ($cats AS $category): ?>
                    <li>
                        <label class="main-cat">
                            <input type="checkbox" class="main-cat-checkbox" name="category[]" value="<?php echo $category->id; ?>"<?php echo (in_array($category->id, $category_list)) ? ' checked' : ''; ?>>
                            <?php echo $category->name; ?>
                        </label>
                    <?php $children_cats = get_children_categories($category->id); ?>
                        <ul class="subcategories">
                        <?php foreach ($children_cats AS $child_category): ?>
                            <li>
                                <label class="sub-cat">
                                    <input type="checkbox" class="sub-cat-checkbox" name="category[]" value="<?php echo $child_category->id; ?>"<?php echo (in_array($child_category->id, $category_list)) ? ' checked' : ''; ?>>
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
                    <textarea name="notes" rows="5" id="notes" class="form-control"><?php echo $business->notes; ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-2">
                    <input type="hidden" name="business_id" value="<?php echo $business->id; ?>">
                    <input type="hidden" name="action" value="update_business">
                    <input class="form-control btn-success" type="submit" value="Update Business">
                </div>
                <div class="col-md-1">
                    <a href="<?php echo SITE_ROOT; ?>" class="btn btn-default">Cancel</a>
                </div>
            </div>
        </form>
    </div>
<?php include("_footer.php"); ?>