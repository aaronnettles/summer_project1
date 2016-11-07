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
		$sql = "SELECT iid FROM courses
			    WHERE cid = $cid
			   ";
		
		$result = mysqli_query($link, $sql);
		
		if($result) {
			
			// fetch first associative row from result
			$row = mysqli_fetch_assoc($result);
			
			// use iid from that
			$iid = $row['iid'];
			
			// second query for enrollment
			$sql = "INSERT INTO enrollments VALUES (
						$sid, $cid, $iid 
					)
				   ";
				   
			$result = mysqli_query($link, $sql);
			
			if($result) {
				echo 'You have been added to the course with the ID of ' . $cid . '.';
				
				student_links($_SESSION['valid_student']);
			}
			else {
				echo 'ERROR';
				
				student_links($_SESSION['valid_student']);
			}
		}
		else {
			echo 'That course does not exist.<br />';
			
			student_links($_SESSION['valid_student']);
		}
		
		// close connection
		mysqli_close($link);
	}
	else {
		
		echo "
			<head>
			<title>Enrollment</title>
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
				<h1>Enroll in a Course</h1>
				<br />
				
				<div class = 'container'>
					<form action = 'Student_Add_Course.php' method = 'post'>
						<label>Course ID</label>
						<input type = 'text' name = 'cid'>
						<input type = 'submit' value = 'Enroll'>
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