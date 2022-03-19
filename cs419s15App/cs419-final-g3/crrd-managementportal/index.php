<?php include("_header.php"); ?>
<?php $businesses = get_businesses(); ?>
    <div class="page-wrapper page-manage-businesses">
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
    	<h1 class="page-title">Manage Businesses</h1>
        <a class="btn add-business add-new" href="add-business.php">+ Add New</a>
        <table id="business-list" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
			<thead>
                <tr>
                    <th></th>
    				<th>Name</th>
    				<th>Address</th>
    				<th>Address 2</th>
    				<th>City</th>
    				<th>State</th>
    				<th>Zip</th>
    				<th>Main Category</th>
    				<th>Subcategories</th>
    			</tr>
            </thead>
            <tbody>
            <?php foreach ($businesses AS $business): ?>
                <?php
                    $info = $business['info'];

                    $categories = array();
                    foreach ($business['categories'] AS $category)
                    {
                        if ($category->parent_id === null)
                        {
                            $categories['main'][] = $category->name;
                        }
                        else
                            $categories['sub'][] = $category->name;
                    }
                    sort($categories['main']);
                    sort($categories['sub']);
                    $main_categories = (!empty($categories['main'])) ? implode(', ', $categories['main']) : '';
                    $sub_categories = (!empty($categories['sub'])) ? implode(', ', $categories['sub']) : '';
                ?>
                <tr>
                    <td>
                        <a href="update-business.php?id=<?php echo $info->id; ?>" class="glyphicon glyphicon-pencil"></a>
                        <a href="javascript:void(0);" class="delete-business glyphicon glyphicon-trash" data-id="<?php echo $info->id; ?>"></a>
                    </td>
                    <td><?php echo $info->name; ?></td>
                    <td><?php echo $info->address; ?></td>
                    <td><?php echo $info->address2; ?></td>
                    <td><?php echo $info->city; ?></td>
                    <td><?php echo $info->state; ?></td>
                    <td><?php echo $info->zip; ?></td>
                    <td><?php echo $main_categories; ?></td>
                    <td><?php echo $sub_categories; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php include("_footer.php"); ?>


