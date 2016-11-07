<?php
// in regard to session variables
session_start();

function student_links($val_stu) {
	
	// show student f_name and l_name
	echo '<br /><br />You are logged in as ' . $val_stu . '.<br />';
	// return to student main
	echo '<a href = "Student_Main.php">Main Student Page</a><br />';
	// give the student a chance to log out
	echo '<a href = "Student_Logout.php">Log Out</a><br />';
}

// check user status
if(isset($_SESSION['valid_student'])) {
	
	if(isset($_POST['cid'])) {
		
		// retrieve sid from session variable
		$sid = $_SESSION['sid'];
		
		// Attempt MySQL server connection. Assuming you are running MySQL
		// server with default setting (user 'root' with 'rambo' password)
		$link = mysqli_connect("localhost", "root", "rambo", "team_project");
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// escape user inputs for security
		$cid = mysqli_real_escape_string($link, $_POST['cid']);

		// first query to find the cid and associated iid
		$sql = "DELETE FROM enrollments
			    WHERE sid = $sid
				AND cid = $cid
			   ";
		
		$result = mysqli_query($link, $sql);
		
		if($result) {
			echo 'You have been removed from that course.';
			
			student_links($_SESSION['valid_student']);
		}
		else {
			echo 'You were not enrolled in that course.<br />';
			
			student_links($_SESSION['valid_student']);
		}
		
		// close connection
		mysqli_close($link);
	}
	else {
		
		echo "
			<head>
			<title>Drop</title>
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
				<h1>Drop Out Of A Course</h1>
				<br />
				
				<div class = 'container'>
					<form action = 'Student_Drop_Course.php' method = 'post'>
						<label>Course ID</label>
						<input type = 'text' name = 'cid'>
						<input type = 'submit' value = 'Drop'>
						<input type = 'reset' value = 'Reset Form'>
					</form>
				</div>
			</body>
		";
	
		student_links($_SESSION['valid_student']);
	}
	
}
else {
	
	echo 'You must be logged in as a student to view this page.';
	// return to instructor main
	echo '<a href = "Student_Main.php">Main Student Page</a><br />';
}
?>