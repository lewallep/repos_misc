<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <img src="assets/images/crrd_logo_circle.png" width="40" />
            <a class="navbar-brand" href="<?php echo SITE_ROOT; ?>">CRRD Management Portal</a>
        </div>
    	<ul class="nav navbar-nav">
    		<li class="businesses-link<?php if($fileName == '' || $fileName == 'index.php' || $fileName == 'add-business.php' || $fileName == 'update-business.php'){ echo ' active'; } ?>">
                <a href="<?php echo SITE_ROOT; ?>">Manage Businesses</a>
            </li>
    		<li class="categories-link<?php if($fileName == 'manage-categories.php' || $fileName == 'add-category.php' || $fileName == 'update-category.php' ){ echo ' active'; } ?>">
    			<a href="manage-categories.php">Manage Categories</a>
    		</li>
    	</ul>
    	<ul class="nav navbar-nav navbar-right">
		<?php if(!empty($_SESSION['user_type']) && ($_SESSION['user_type'] == '1')): ?>
			<li class="users-link<?php if($fileName == 'manage-users.php' ){ echo ' active'; } ?>"><a href='manage-users.php'>Manage Users</a></li>
        <?php endif; ?>
			<li class="logout-link"><a href="<?php echo SITE_ROOT.'login.php?logout'; ?>">Logout</a></li>
        </ul>
    </div>
</nav>