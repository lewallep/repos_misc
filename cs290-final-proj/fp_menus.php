<?php
error_reporting(E_ALL);
//ini_set('display_errors', 1);

session_start();
if (isset($_POST['logOut1'])) {
	session_destroy();
	header("Location: http://web.engr.oregonstate.edu/~lewallep/CS290/f_proj/fp_index.html");
	exit();
}

if(isset($_POST['rUserName']) && $_POST['rUserName'] == 'end') {
	$_SESSION = array();
	session_destroy();
	$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
	$filePath = implode('/', $filePath);
	$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
	header("Location : redirect/fp_index.html", true);
	die();
}
if(strlen($_SESSION['sUserName']) == 0) {
		echo "<p>You must Login. 
			Click <a href=\"http://web.engr.oregonstate.edu/~lewallep/CS290/f_proj/fp_index.html\">here</a>
			to return to the login screen.</p><br>";
		session_destroy();
		exit();
}
if (!isset($_SESSION['sUserName']))
{
	echo "The session username is not set";
}
?>

<html>
<head>
	<title>OSU CS290 Final Project</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="fp_style.css">
<script>
</script>
</head>
<body>
<form name="menu_logout" method="post"/>
	<input type="submit" name="logOut1" value="Log me out"/>
</form>
<h3>Please enter the information for a Menu Item</h3>
<form id="image_upload_form" enctype="multipart/form-data" method="POST" action="fp_menus.php">
	Name of Menu Item:&nbsp;&nbsp;<input type="text" name="item_name" value=""><br>
	Amount of Item Sold:&nbsp;&nbsp;<input type="number" name="amount_sold"/><br>
	Item Price:&nbsp;&nbsp;<input type="number" min="0.01" step="0.01" name="price"/><br>
	Image of Menu Item:&nbsp;&nbsp;<input type="file" name="image"><br><br>
	<input type="submit" value="Upload your Menu Item!"><br>
</form>


<p id="insert"></p>

<?php
include "me.php";

//This code was heavily influed by the below tutorial.  Specifically, the code for loading images to the MySQL database.
//https://www.youtube.com/watch?v=o-0bfleqE2g
$mysqli = new mysqli($db_host, $db_user, $db_pw, $db_name);
if ($mysqli->connect_errno)
{
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_errnor;
}

//I need to get the username from the login and use it to get the foreign key.
$user_name = $_SESSION['sUserName'];

$file = $_FILES['image']['tmp_name'];
$item_name = $_POST['item_name'];
$item_amount = $_POST['amount_sold'];
$item_price = $_POST['price'];

$image = file_get_contents($_FILES['image']['tmp_name']);
$image_name = addslashes($_FILES['image']['name']);
$image_size = getimagesize($_FILES['image']['tmp_name']);

if (strlen($item_name) != 0)
{

	$target_dir = "images/";
	$target_file = $target_dir . $image_name;
	$uploadOk = 1;

	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if(isset($_POST["submit"])) {
	    $check = getimagesize($_FILES["image"]["tmp_name"]);
	    if($check !== false) {
	        echo "File is an image - " . $check["mime"] . ".";
	        $uploadOk = 1;
	    } else {
	        echo "File is not an image.";
	        $uploadOk = 0;
	    }
	}
	// Check if file already exists
	if (file_exists($target_file)) {

	    $uploadOk = 2;
	}
	// Check file size
	if ($_FILES["image"]["size"] > 500000) {
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}
	// Allow certain file formats
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
	&& $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}
	// Check if $uploadOk is set to 0 by an error
	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} 
	else if ($uploadok == 0) {
	    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
	        echo "<p>The file ". basename( $_FILES["image"]["name"]). " has been uploaded.</p>";
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}

	if ($image_size == FALSE)
	{
		echo "<p>The image file you have selected is not an image.  Please try again.</p>";
	}

	//Verify all of my number and text inputs.
	$price_float = floatval($item_price);

	if (!is_float($price_float))
	{
		echo "<p>The price you entered is not of the correct format</p>";
	}

	//Getting the user id from the menu_user's table to use as my foreign key insert.
	$query = "SELECT id FROM menu_users WHERE user_name = '$user_name'";
	$q_name = mysqli_query($mysqli, $query);
	$row = mysqli_fetch_array($q_name);
	$fk_id = $row['id'];

	//Insert data into the database.
	if ($stmt = $mysqli->prepare("INSERT INTO menu_food(item_name, amount_sold, price, images_name, menu_item_image, user_id)
		VALUES(?,?,?,?,?,?)")) 
	{
		$stmt->bind_param("sidssi", $item_name, $item_amount, $item_price, $image_name, $image, $fk_id);
		$stmt->execute();
		$stmt->close();
	}

	$query = "SELECT id, item_name, amount_sold, price, images_name FROM menu_food WHERE user_id = {$fk_id}";
	$table_menu = mysqli_query($mysqli, $query);

	echo "<table><caption>Your Menu items</caption>";
	echo "<tr><th>Item Name</th><th>Amount Sold</th><th>Price</th><th>Name of the Image</th><th>Image</th></tr>";
	while ($row = mysqli_fetch_array($table_menu))
	{
		$image_path = "images/" . $row['images_name'];
		echo "<tr>";
		echo "<td>" . $row['item_name'] . "</td>";
		echo "<td class=\"item_name\">" . $row['amount_sold'] . "</td>";
		echo "<td class=\"price\">$" . $row['price'] . "</td>";
		echo "<td>" . $row['images_name'] . "</td>";
		echo "<td><img src='$image_path'></td>";
		echo "</tr>";
	}
	echo "</tr>";
	echo "</table>";

	$mysqli->close();

}
else 
{
	echo "<p>Please enter at least one character for the item name.</p>";
		//Getting the user id from the menu_user's table to use as my foreign key insert.
	$query = "SELECT id FROM menu_users WHERE user_name = '$user_name'";
	$q_name = mysqli_query($mysqli, $query);
	$row = mysqli_fetch_array($q_name);
	$fk_id = $row['id'];

	$query = "SELECT id, item_name, amount_sold, price, images_name FROM menu_food WHERE user_id = {$fk_id}";
	$table_menu = mysqli_query($mysqli, $query);

	echo "<table><caption>Your Menu items</caption>";
	echo "<tr><th>Item Name</th><th>Amount Sold</th><th>Price</th><th>Name of the Image</th><th>Image</th></tr>";
	while ($row = mysqli_fetch_array($table_menu))
	{	
		$image_path = "images/" . $row['images_name'];
		echo "<tr>";
		echo "<td>" . $row['item_name'] . "</td>";
		echo "<td class=\"item_name\">" . $row['amount_sold'] . "</td>";
		echo "<td class=\"price\">$" . $row['price'] . "</td>";
		echo "<td>" . $row['images_name'] . "</td>";
		echo "<td><img src='$image_path'></td>";
		echo "</tr>";
	}
	echo "</tr>";
	echo "</table>";

	$mysqli->close();
}
//After all of the data input checks we are going to query the database with an insert.
?>
</body>
</html>