<?php
// in regard to session variables
session_start();

// test for sid with password
if(isset($_POST['sid']) && isset($_POST['password'])) {
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect("localhost", "root", "rambo", "team_project");
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	// Escape user inputs for security
	$sid = mysqli_real_escape_string($link, $_POST['sid']);
	$password = mysqli_real_escape_string($link, $_POST['password']);
	
	// attempt insert query execution
	$sql = "SELECT sid, f_name, l_name, password
			FROM students
			WHERE sid = $sid
			AND password = '$password'";
	
	// run query
	$result = mysqli_query($link, $sql);
	
	// empty result
	if($result) {
		
		$row = mysqli_fetch_assoc($result);
		
		// if user is valid, create session variables
		$_SESSION['valid_student'] = $row["f_name"] .
									 ' ' . $row["l_name"];
										
		$_SESSION['sid'] = $sid;
	}
	 
	// close connection
	mysqli_close($link);
}
?>

<?php
// check user status
if(isset($_SESSION['valid_student'])) {
	
	echo "
		<head>
		<title>Student Area</title>
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
			<h1>Student Area</h1>
			<br />
			
			<div class = 'container'>
				<form action = 'Student_Show_Schedule.php' method = 'post'>
					<input type = 'submit' value = 'Show Schedule'>
				</form>
				<form action = 'Student_Add_Course.php' method = 'post'>
					<input type = 'submit' value = 'Add Course'>
				</form>
				<form action = 'Student_Drop_Course.php' method = 'post'>
					<input type = 'submit' value = 'Drop Course'>
				</form>
			</div>
		</body>
	";
	
	// show student f_name and l_name
	echo 'You are logged in as ' . $_SESSION['valid_student'] . '.<br />';
	// give the user a chance to log out
	echo '<a href = "Student_Logout.php">Log Out</a><br />';
}
else {
	
	if(isset($sid)) {
		echo 'Your login attempt was invalid.<br />';
	}
	else {
		echo 'Type in your ID and your password below.<br />';
	}
	// login form
	echo "
		<br />
		<form action = 'Student_Main.php' method = 'post'>
			<label>Student ID</label>
			<input type = 'text' name = 'sid'><br />
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