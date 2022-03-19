<?php

/**
 * A simple PHP Login Script / ADVANCED VERSION
 * For more versions (one-file, minimal, framework-like) visit http://www.php-login.net
 *
 * @author Panique
 * @link http://www.php-login.net
 * @link https://github.com/panique/php-login-advanced/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// check for minimum PHP version
if (version_compare(PHP_VERSION, '5.3.7', '<')) {
    exit('Sorry, this script does not run on a PHP version smaller than 5.3.7 !');
} else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
    // if you are using PHP 5.3 or PHP 5.4 you have to include the password_api_compatibility_library.php
    // (this library adds the PHP 5.5 password hashing functions to older versions of PHP)
    require_once('libraries/password_compatibility_library.php');
}
// include the config
require_once('inc/config.php');

// include the to-be-used language, english by default. feel free to translate your project and include something else
require_once('translations/en.php');

// include the PHPMailer library
require_once('libraries/PHPMailer.php');

// load the login class
require_once('classes/Login.php');

// create a login object. when this object is created, it will do all login/logout stuff automatically
// so this single line handles the entire login process.
$login = new Login();
?>

<?php if ($login->isUserLoggedIn() == true): ?>
    <?php header('Location: '.SITE_ROOT); ?>
<?php else: ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | CRRD</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
    <script src="assets/js/vendor/jquery.min.js"></script>
	<script src="assets/js/main.js"></script>
    <script src="assets/js/plugins.js"></script>
</head>
<body>
    <header>
        <h1 class="main-title"><img src="assets/images/crrd_logo_circle.png"> Corvallis Reuse and Repair Directory</h1>
        <h2 class="secondary-title">Management Portal Login</h2>
    </header>
	<div class="container">
        <div class="row">
    		<div class="col-md-4 col-md-offset-4">
        		<div class="panel panel-login">
    			  	<div class="panel-heading">
    			    	<form method="POST" action="index.php" name="loginform" accept-charset="UTF-8" role="form">
                        <fieldset>
                        	<?php
                        		if (session_id() == '' || !isset($_SESSION)) {
    							    session_start();
    							}
                        		if(!empty($_SESSION['login_msg'])){
                        			?>
                        			<div class="alert alert-danger">
                        				<?php echo $_SESSION['login_msg']; ?>
                        			</div>
                        			<?php
                        			unset($_SESSION['login_msg']);
                        		}
                        	?>
    			    	  	<div class="form-group">
    			    		    <input type="text" id="user_name" class="span4" name="user_name" placeholder="Username" required style='padding:0px 10px;'>
    			    		</div>
    			    		<div class="form-group">
    			    			<input type="password" id="user_password" class="span4" name="user_password" placeholder="Password" autocomplete="off" required style='padding:0px 10px;'>
    			    		</div>
    			    		<div class="checkbox">
    			    	    	<label>
    			    	    		<input type="checkbox" name="user_rememberme" value="1"> <?php echo WORDING_REMEMBER_ME; ?>
    			    	    	</label>
    			    	    </div>
    			    		<input type="submit" name="login" class="form-control btn btn-login" value="<?php echo WORDING_LOGIN; ?>">
    			    	</fieldset>
    			      	</form>
    			    </div>
    			</div>
    		</div>
    	</div>
    </div>
    <div class="img-wrapper">
        <a href="http://sustainablecorvallis.org/" target="_blank"><img src="assets/images/csc_logo.png"></a>
    </div>
</body>
</html>
<?php endif; ?>