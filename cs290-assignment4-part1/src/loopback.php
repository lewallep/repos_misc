<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
?>

<?php
	//echo "<p>Hello this is the first php page from phil.</p>";
	$method = $_SERVER['REQUEST_METHOD'];

	//http://stackoverflow.com/questions/359047/php-detecting-request-type-get-post-put-or-delete
	$request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));	 
	$get_length = 0;

	switch ($method) {
		case 'PUT':
			//echo "<p>PUT detected.</p>";
			break;
		case 'GET':
			$get_length = count($_GET); 

			if ($get_length == 0) {
				//Output the default scenario of no parameters null.
				echo "<p>{\"Type\":\"[GET]\", \"parameters\":null}</p>";
			}
			else {
				//echo "<p>GET detected.</p>";
				$out['Type'] = "GET";
				$append['Parameters'] = $_GET;

				$array4 = $out + $append;

				$JSON_out = json_encode($array4);
				echo "<p>$JSON_out</p>";
			}

			break;
		case 'POST':
			//echo "<p>POST detected.</p>";

			$post_length = count($_POST);

			if ($post_length == 0) {
				//Output the default scenario of no parameters null.
				echo "<p>{\"Type\":\"[POST]\", \"parameters\":null}</p>";
			}
			else {
				$out['Type'] = "POST";
				$append['Parameters'] = $_POST;

				$array4 = $out + $append;

				$JSON_out = json_encode($array4);
				echo "<p>$JSON_out</p>";
			}

			break;
		default:
			echo "<p>Something unexpected happened you are in the default case.</p>";
			echo "<p>No method of data gathering has been detected.</p>";
			break;
	}
?>