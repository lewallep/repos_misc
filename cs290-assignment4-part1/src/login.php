<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
</head>
<body>
	<fieldset>
	<legend>Please enter your username and then press the Login button.</legend>
		<form name="userLogin" method="post" 
			action="http://web.engr.oregonstate.edu/~lewallep/CS290/assignment4-part1/content1.php">
			<input type="text" name="username"><br>
			<input type="submit" name="submitButton">
		</form>
	</fieldset>
</body>
</html>