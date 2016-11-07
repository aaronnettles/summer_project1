<?php
// in regard to session variables
session_start();

function instructor_links($val_ins) {
	
	// show instructor f_name and l_name
	echo '<br /><br />You are logged in as ' . $val_ins . '.<br />';
	// return to instructor main
	echo '<a href = "Instructor_Main.php">Main Instructor Page</a><br />';
	// give the instructor a chance to log out
	echo '<a href = "Instructor_Logout.php">Log Out</a><br />';
}

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	if(isset($_POST['cid'])) {
		
		// Attempt MySQL server connection. Assuming you are running MySQL
		// server with default setting (user 'root' with 'rambo' password)
		$link = mysqli_connect("localhost", "root", "rambo", "team_project");
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// escape user inputs for security
		$cid = mysqli_real_escape_string($link, $_POST['cid']);
		
		// query to check whether course currently exists
		$sql = "SELECT cid FROM courses";
		
		// run query
		$result = mysqli_query($link, $sql);
		
		if(!$result) {
			echo 'That course does not currently exist.<br /><br />';
			
			instructor_links($_SESSION['valid_instructor']);
		}
		else {
			// query to delete course from courses
			$sql_c = "DELETE FROM courses
					  WHERE cid = $cid
				     ";
			
			// run query
			$result_c = mysqli_query($link, $sql_c);
			
			// query to delete course from enrollments
			$sql_e = "DELETE FROM enrollments
					  WHERE cid = $cid
				     ";
			
			// run query
			$result_e = mysqli_query($link, $sql_e);
			
			if($result_c && $result_e) {
				echo 'The course with the ID of ' . $cid . ' has been deleted.<br /><br />';
				
				instructor_links($_SESSION['valid_instructor']);
			}
			else {
				echo 'ERROR';
				
				instructor_links($_SESSION['valid_instructor']);
			}
		}
		
	}
	else {
		
		echo "
			<head>
			<title>Delete Course</title>
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
				<h1>Delete Course</h1>
				<br />
				
				<div class = 'container'>
					<form action = 'Instructor_Delete_Course.php' method = 'post'>
						<label>Course ID</label>
						<input type = 'text' name = 'cid'>
						<input type = 'submit' value = 'Delete Course'>
						<input type = 'reset' value = 'Reset Form'>
					</form>
				</div>
			</body>
		";
		
		instructor_links($_SESSION['valid_instructor']);
	}
}
else {
	
	echo 'You must be logged in as an instructor to view this page.';
	// return to instructor main
	echo '<a href = "Instructor_Main.php">Main Instructor Page</a><br />';
}
?>