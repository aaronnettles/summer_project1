<?php
// in regard to session variables
session_start();

function instructor_links($val_ins) {
	
	// show instructor f_name and l_name
	echo '<br /><br />You are logged in as ' . $val_ins . '.<br />';
	// return to instructor main
	echo '<a href = "Instructor_Main.php">Return to Main Instructor Page</a><br />';
	// give the instructor a chance to log out
	echo '<a href = "Instructor_Logout.php">Log Out</a><br />';
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
if(isset($_SESSION['valid_instructor'])) {

	// retrieve sid from session variable
	$iid = $_SESSION['iid'];
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with 'rambo' password)
	$link = mysqli_connect("localhost", "root", "rambo", "team_project");
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}

	// query to get course information
	$sql_a = "SELECT * FROM courses
			  WHERE iid = $iid
			 ";
	
	$result_a = mysqli_query($link, $sql_a);
	
	// print courses
	if(mysqli_num_rows($result_a) > 0) {
		while($row_a = mysqli_fetch_assoc($result_a)) {
			echo 'Course ID: ' . $row_a["cid"];
			echo '<br />';
			echo 'Course Name: ' . $row_a["c_name"];
			echo '<br />';
			echo 'Time: ';
			echo time_convert($row_a["start_time"]);
			echo ' to ';
			echo time_convert($row_a["end_time"]);
			echo '<br />';
			echo 'Days: ' . $row_a["c_day"] . '<br />';
			echo 'Location: ' . $row_a["location"] . '<br /><br />';
			
			$cid_temp = $row_a["cid"];
			
			// query to get student information
			$sql_b = "SELECT * FROM students
					  WHERE sid IN (
						  SELECT sid FROM enrollments
						  WHERE cid = $cid_temp
					  )
					 ";
	
			$result_b = mysqli_query($link, $sql_b);
			
			if(mysqli_num_rows($result_b) > 0) {
				while($row_b = mysqli_fetch_assoc($result_b)) {
					echo 'Student ID: ' . $row_b["sid"];
					echo '  ';
					echo 'Major: ' . $row_b["major"];
					echo '  ';
					echo 'Name: ' . $row_b["f_name"];
					echo ' ' . $row_b["l_name"];
					echo '  ';
					echo 'Email: ' . $row_b["email"];
					echo '<br />';
				}
			}
			else {
				echo '0 students enrolled<br /><br />';
			}
			
			echo '<br /><br />';
		}
	}
	else {
		echo '0 courses';
	}
	 
	// close connection
	mysqli_close($link);

	instructor_links($_SESSION['valid_instructor']);
	
}
else {
	
	echo 'You must be logged in as an instructor to view this page.';
	// return to instructor main
	echo '<a href = "Instructor_Main.php">Main Instructor Page</a><br />';
}
?>