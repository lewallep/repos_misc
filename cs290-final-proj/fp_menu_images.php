<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "me.php";

//This code was heavily influed by the below tutorial.  Specifically, the code for loading images to the MySQL database.
//https://www.youtube.com/watch?v=o-0bfleqE2g
$mysqli = new mysqli($db_host, $db_user, $db_pw, $db_name);
if ($mysqli->connect_errno)
{
	//echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_errnor;
}

$id = addslashes($_REQUEST['id']);

echo $id;

$query = "SELECT menu_item_image FROM menu_food WHERE id = '$id'";
$q_name = mysqli_query($mysqli, $query);
$image = mysqli_fetch_array($q_name);
$image = $image['menu_item_image'];


echo "<p>this sucks</p>";

?>