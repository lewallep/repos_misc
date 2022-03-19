<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');

include "me.php";

$mysqli = new mysqli($db_host, $db_user, $db_pw, $db_name);
if ($mysqli->connect_errno) {
	echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_errnor;
}

	if (isset($_POST['rUserName']))
	{
		$r_user = $_POST['rUserName'];
		$r_pw = $_POST['rUserPw'];

		$query = "SELECT user_name, user_pw FROM menu_users";
		$db_data = mysqli_query($mysqli, $query);
		$login_flag = 0;

		while($row = mysqli_fetch_array($db_data))
		{
			if ($row['user_name'] == $r_user && $row['user_pw'] == $r_pw)
			{
				$login_flag = 1;
			}
		}

		if ($login_flag == 1)
		{
			//start a session heresession_start();
			session_start();
			if (isset($_POST['logOut1'])) {
				session_destroy();
				header("Location: http://web.engr.oregonstate.edu/~lewallep/CS290/f_proj/fp_index.html");
				exit();
			}
			if (isset($_POST['logOut1'])) 
			{
				session_destroy();
				header("Location: http://web.engr.oregonstate.edu/~lewallep/CS290/f_proj/fp_index.html");
				exit();
			}

			//this if statement is from the lecture videos.
			if(isset($_POST['username']) && $_POST['username'] == 'end') 
			{
				$_SESSION = array();
				session_destroy();
				$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
				$filePath = implode('/', $filePath);
				$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
				header("Location : redirect/fp_index.html", true);
				die();
			}
			if(session_status() == PHP_SESSION_ACTIVE) 
			{
				$_SESSION['sUserName'] = $r_user;
				echo "1";
			}
		}
		else
		{
			echo "<p>The Username and or Password you have entered did not match any existing users</p>";
			echo "<p>Please enter another Username and or Password</p>";
		}
	}

	if (isset($_POST['newUserName']))
	{
		$new_user = $_POST['newUserName'];
		$new_pw = $_POST['newUserPW'];
		$new_rest = $_POST['newRestaurant'];

		//query and check for the user.
		$query = "SELECT user_name FROM menu_users";
		$db_data = mysqli_query($mysqli, $query);
		$user_flag = 0;		//Setting a flag to set if the username already exists.

		while($row = mysqli_fetch_array($db_data))
		{
			if ($row['user_name'] == $new_user)
			{
				echo "<p>That user name is already in use.</p>";	//if the user exists return message and ask again.
				echo "<p>Please enter a new username</p>";
				$user_flag = 1;
			}
		}

		//else log the user in by redirecting.
		if ($user_flag == 0) 
		{
			if ($stmt = $mysqli->prepare("INSERT INTO menu_users(user_name, user_pw, resturaunt) VALUES(?, ?, ?)"))
			{
				$stmt->bind_param("sss", $new_user, $new_pw, $new_rest);
				$stmt->execute();
				$stmt->close();

				//start a session heresession_start();
				session_start();
				if (isset($_POST['logOut1'])) {
					session_destroy();
					header("Location: http://web.engr.oregonstate.edu/~lewallep/CS290/f_proj/fp_index.html");
					exit();
				}
				if (isset($_POST['logOut1'])) 
				{
					session_destroy();
					header("Location: http://web.engr.oregonstate.edu/~lewallep/CS290/f_proj/fp_index.html");
					exit();
				}

				//this if statement is from the lecture videos.
				if(isset($_POST['username']) && $_POST['username'] == 'end') 
				{
					$_SESSION = array();
					session_destroy();
					$filePath = explode('/', $_SERVER['PHP_SELF'], -1);
					$filePath = implode('/', $filePath);
					$redirect = "http://" . $_SERVER['HTTP_HOST'] . $filePath;
					header("Location : redirect/fp_index.html", true);
					die();
				}
				if(session_status() == PHP_SESSION_ACTIVE) 
				{
					$_SESSION['sUserName'] = $new_user;
					echo "1";
				}

			}
			else 
			{
				echo "We were unable to process your new user account request<br>";
			}
		}




		

		
		
	}
?>