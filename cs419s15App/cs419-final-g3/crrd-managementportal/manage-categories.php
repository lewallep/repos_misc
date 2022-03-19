<?php include("_header.php"); ?>
<div class="page-wrapper page-manage-categories">
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
    <h1 class="page-title">Manage Categories</h1>
    <a class="btn add-category add-new" href="add-category.php">+ Add New</a>
    <?php $cats = get_main_categories(); ?>
    <div class="row">
        <div class="col-md-4">
        <?php foreach ($cats AS $category): ?>
            <?php $children_cats = get_children_categories($category->id); ?>
            <div class="panel panel-default">
                <div class="panel-heading category-list-group" data-toggle="collapse" data-target="#subcategories-<?php echo $category->id; ?>" aria-expanded="false">
                    <div class="col-xs-1">
                        <span class="badge"><?php echo count($children_cats); ?></span>
                    </div>
                    <strong style="margin-left: 20px;"><?php echo $category->name; ?></strong>
                    <span class="caret"></span>
                    <a href="javascript:void(0);" class="delete-category glyphicon glyphicon-trash pull-right" data-id="<?php echo $category->id; ?>"></a>
                    <a href="update-category.php?id=<?php echo $category->id; ?>" class="glyphicon glyphicon-pencil pull-right"></a>
                </div>
                <ul class="list-group collapse subcategories-list-group" id="subcategories-<?php echo $category->id; ?>">
                <?php foreach ($children_cats AS $child_category): ?>
                    <li class="list-group-item">
                        <a href="javascript:void(0);" class="delete-category glyphicon glyphicon-trash pull-right" data-id="<?php echo $child_category->id; ?>"></a>
                        <a href="update-category.php?id=<?php echo $child_category->id; ?>" class="glyphicon glyphicon-pencil pull-right"></a>
                        <?php echo $child_category->name; ?>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
</div>
<?php include("_footer.php"); ?>