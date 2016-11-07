<?php
// in regard to session variables
session_start();

// test for iid with password
if(isset($_POST['iid']) && isset($_POST['password'])) {
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with no password)
	$link = mysqli_connect("localhost", "root", "rambo", "team_project");
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	 
	// Escape user inputs for security
	$iid = mysqli_real_escape_string($link, $_POST['iid']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	 
	// attempt insert query execution
	$sql = "SELECT iid, f_name, l_name, password
			FROM instructors
			WHERE iid = $iid
			AND password = '$password'
		   ";
	
	// run query
	$result = mysqli_query($link, $sql);
	
	// empty result
	if($result) {
		
		$row = mysqli_fetch_assoc($result);
		
		// if user is valid, create session variables
		$_SESSION['valid_instructor'] = $row["f_name"] .
									    ' ' . $row["l_name"];
										
		$_SESSION['iid'] = $iid;
	}
	 
	// close connection
	mysqli_close($link);
}
?>

<?php
// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	echo "
		<head>
		<title>Instructor Area</title>
		<style type = 'text/css'>
			.container {
				width: 200px;
				clear: both;
			}
			.container input {
				width: 100%;
				clear: both;
			}
		</style>
		</head>

		<body>
			<h1>Instructor Area</h1>
			<br />
			
			<div class = 'container'>
				<form action = 'Instructor_Show_Schedule.php' method = 'post'>
					<input type = 'submit' value = 'Show Schedule'>
				</form>
				<form action = 'Instructor_Create_Course.php' method = 'post'>
					<input type = 'submit' value = 'Create Course'>
				</form>
				<form action = 'Instructor_Delete_Course.php' method = 'post'>
					<input type = 'submit' value = 'Delete Course'>
				</form>
			</div>
		</body>
	";
	
	// show instructor f_name and l_name
	echo 'You are logged in as ' . $_SESSION['valid_instructor'] . '.<br />';
	// give the user a chance to log out
	echo '<a href = "Instructor_Logout.php">Log Out</a><br />';
}
else {
	
	if(isset($iid)) {
		echo 'Your login attempt was invalid.<br />';
	}
	else {
		echo 'Type in your ID and your password below.<br />';
	}
	// login form
	echo "
		<br />
		<form action = 'Instructor_Main.php' method = 'post'>
			<label>Instructor ID</label>
			<input type = 'text' name = 'iid'><br />
			<label>Password</label>
			<input type = 'password' name = 'password'><br />
			<input type = 'submit' value = 'Login'>
			<input type = 'reset' value = 'Reset Form'>
		</form>
		<br />
		<a href = 'Index.html'>Return to Home Page</a><br />
	";
}
?>