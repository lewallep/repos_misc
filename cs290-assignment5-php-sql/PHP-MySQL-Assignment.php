<?php
error_reporting(E_ALL);
//ini_set('display_errors', 'On');
?>

<?php
//the connection function to my database.
//I am going to use the connection for add and to query for a list of categories.
$mysqli = new mysqli("", "", "", "");
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

//Begin code to add a Video.
$video_name = $_POST['v_name'];
$video_category = $_POST['category'];
$video_length = $_POST['length'];

$name_length = strlen($video_name);

if ($name_length == 0) {
	echo "<p><b>You must enter a Name of a Video</b></p><br>";
}

//I check to make see if the rented checkbox is check.  
//Then I convert the boolean value to an integer.
if (!isset($_POST['checked_in'])) {
	$video_rented = 0;
}
else {
	$video_rented = 1;
}

if ($name_length > 0) {
	if ($stmt = $mysqli->prepare("INSERT INTO VideoStore(name, category, length, rented) VALUES (?, ?, ?, ?)")) {

		$stmt->bind_param("ssii", $video_name, $video_category, $video_length, $video_rented);

		$stmt->execute();

		$stmt->close();
	}
	else {
		echo "The insert statement failed\n\n";
	}
}
$mysqli->close();
$name_length = 0;
?>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"></meta>
<script>
	function myReload() {
		window.location.reload(true);
	}
</script>
</head>
<body>
	<fieldset>
	<legend>The fields below are to add information to the Video Database</legend>
		<form name="add-video" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
			Name of Video<input type="text" name="v_name"></input><br>
			Category of Video<input type="text" name="category"></input><br>
			Length of Video in minutes<input type="number" name="length"></input><br>
			Is the Video Checked-in<input type="checkbox" name="checked_in" checked="checked"></input><br>
			<input type="Submit" name="add_video" value="Add Video"></input><br><br>
		</form>		
	</fieldset>

	<form name="delete-all" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
		<input type="Submit" name="delete_button" value="Delete All Entries">
			This will Delete all of the Video entries from the table but leave the Table intact.</input>
	</form>

	<form>
	<select name="category">

<?php
$dbc = new mysqli("oniddb.cws.oregonstate.edu", "lewallep-db", "IOKtRAT9l5frVGTe", "lewallep-db");
if ($dbc->connect_errno) {
	echo "Failed to connect to MySQL: (" . $dbc->connect_errno . ") " . $dbc->connect_error;
}


//I used this Stack Overflow reference
//http://stackoverflow.com/questions/190702/mysql-select-n-rows-but-with-only-unique-values-in-one-column
$category_select = "SELECT id, category FROM VideoStore GROUP BY category ORDER BY category";

$cat_list = mysqli_query($dbc, $category_select);

echo "<option value=\"*\">All Categories</option>\n  ";
while($row = mysqli_fetch_array($cat_list))
{
	echo "<option value=\"".$row['category']."\">".$row['category']."</option>\n";
}

$mysqli->close();

?>

	</select>
		<input type="submit" value="Filter by Category" name="category_filter"><br>
	</form>

<?php	

	$dbc = new mysqli("oniddb.cws.oregonstate.edu", "lewallep-db", "IOKtRAT9l5frVGTe", "lewallep-db");
		if ($dbc->connect_errno) {
			echo "Failed to connect to MySQL: (" . $dbc->connect_errno . ") " . $dbc->connect_error;
		}

	echo "<p>Table of movies in our Video Store</p><br>";

	echo "<table id=\"results\" style=\"width:100%\" border=\"1p\"><tr><th>Delete this Record</th><th>Name of Video</th><th>Category of Video</th>
		<th>Length of Video in Minutes</th><th>Is Video Checked In</th></tr>";

	//From php freaks
	//http://www.phpfreaks.com/tutorial/working-with-checkboxes-and-a-database
	$table_query = "SELECT id, name, category, length, rented FROM VideoStore ORDER BY name ASC";

	$table = mysqli_query($dbc, $table_query);

	while(list($id, $name, $category, $length, $rented) = mysqli_fetch_array($table)) {
		$checked = ($rented == 1) ? 'checked="checked"' : '';
		echo "<tr><td><form action='".$_SERVER['PHP_SELF']."' method='post'><input type='hidden' id='id' 
			name='delete_form' value='$id'/><input type='submit' name='formDelete' id='formDelete' 
			value='Delete' onclick='myReload()'/></form></td><td>$name</td><td>$category</td><td>$length</td>";
		//Now outputting the checkbox	
		echo'<td><input type="checkbox" name="rented[]" value="'.$id.'" '.$checked.'/></td></tr>';
	}

	$deleteId = $_POST['delete_form'];

	$rtd = "DELETE FROM VideoStore WHERE ID =" . $deleteId;

	$myDelete = mysqli_query($dbc, $rtd); 

	$dbc->close();

?>
	</table>
</body>
</html>










