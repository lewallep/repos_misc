<?php
error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
if (isset($_POST['logOut1'])) {
	session_destroy();
	header("Location: http://web.engr.oregonstate.edu/~lewallep/CS290/assignment4-part1/login.php");
	exit();
}

//this if statement is from the lecture videos.
if(isset($_POST['username']) && $_POST['username'] == 'end') {
	$_SESSION = array();
	session_destroy();
	$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
	$filePath = implode('/', $filePath);
	$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
	header("Location : redirect/login.php", true);
	die();
}

//This function is also from the class lecture on PHP sesssions.
if(session_status() == PHP_SESSION_ACTIVE) {
	if(isset($_POST['username'])) {
		$_SESSION['username'] = $_POST['username'];
	}

	if(!isset($_SESSION['visits'])) {
		$_SESSION['visits'] = 0;
	}

	if(strlen($_SESSION['username']) == 0) {
			echo "<p>A username must be entered.  
				Click <a href=\"http://web.engr.oregonstate.edu/~lewallep/CS290/assignment4-part1/login.php\">here</a>
				to return to the login screen.</p><br>";
			session_destroy();
			exit();
	}
	else {	
		$_SESSION['visits']++;
		echo "Hi $_SESSION[username], you have visited this page $_SESSION[visits] times. \n";
		echo "<br>";
		echo "<p><a href=\"http://web.engr.oregonstate.edu/~lewallep/CS290/assignment4-part1/content2.php\">
			Here is the link to content2.php</a></p>";
	}
}
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
<script>

</script>
</head>
<body>
	<fieldset>
	<legend>Press the button below to end your session.</legend>
	<form name="userLogout1" method="post">
		<input type="submit" name="logOut1" value="Logout">
	</form>
	</fieldset>
</body>
</html>
