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

// convert floats to integers of 3 or 4 digits for class times in database
function time_convert($mil_time) {
	
	$civ_time = $mil_time;
	if($civ_time >= 1300) {
		$civ_time -= 1200;
	}
	
	$time_array = str_split($civ_time);
	$time_digits = strlen($civ_time);
	
	for($i = 0; $i < $time_digits; ++$i) {
		echo $time_array[$i];
		if($i == $time_digits - 3) {
			echo ':';
		}
	}
	
	if($mil_time < 1200) {
		echo 'am';
	}
	else {
		echo 'pm';
	}
}

// check user status
if(isset($_SESSION['valid_student'])) {

	// retrieve sid from session variable
	$sid = $_SESSION['sid'];
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect("localhost", "root", "rambo", "team_project");
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}

	// query to get course information
	$sql = "SELECT * FROM courses
			WHERE cid IN (
				SELECT cid FROM enrollments
				WHERE sid = $sid
			)
		   ";
	
	$result = mysqli_query($link, $sql);
	
	// print courses
	if($result) {
		while($row = mysqli_fetch_assoc($result)) {
			echo 'Course ID: ' . $row["cid"];
			echo '<br />';
			echo 'Course Name: ' . $row["c_name"];
			echo '<br />';
			echo 'Time: ';
			echo time_convert($row["start_time"]);
			echo ' to ';
			echo time_convert($row["end_time"]);
			echo '<br />';
			echo 'Days: ' . $row["c_day"] . '<br />';
			echo 'Location: ' . $row["location"] . '<br /><br />';
		}
	}
	else {
		echo '0 results';
	}
	 
	// close connection
	mysqli_close($link);

	student_links($_SESSION['valid_student']);
	
}
else {
	
	echo 'You must be logged in as a student to view this page.';
	// return to instructor main
	echo '<a href = "Student_Main.php">Main Student Page</a><br />';
}
?>