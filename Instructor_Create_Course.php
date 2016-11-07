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

// check user status
if(isset($_SESSION['valid_instructor'])) {
	
	if(isset($_POST['c_name']) && isset($_POST['start_time']) &&
	   isset($_POST['end_time']) && isset($_POST['c_day']) &&
	   isset($_POST['location'])) {
		   
		// retrieve sid from session variable
		$iid = $_SESSION['iid'];
		
		// Attempt MySQL server connection. Assuming you are running MySQL
		// server with default setting (user 'root' with 'rambo' password)
		$link = mysqli_connect("localhost", "root", "rambo", "team_project");
		 
		// test connection
		if($link === false) {
			die("ERROR");
		}
		
		// escape user inputs for security
		$c_name = mysqli_real_escape_string($link, $_POST['c_name']);
		$start_time = mysqli_real_escape_string($link, $_POST['start_time']);
		$end_time = mysqli_real_escape_string($link, $_POST['end_time']);
		$c_day = mysqli_real_escape_string($link, $_POST['c_day']);
		$location = mysqli_real_escape_string($link, $_POST['location']);
		
		// military time conversion
		if($_POST['am_pm_start'] == 'pm') {$start_time += 1200;}
		if($_POST['am_pm_end'] == 'pm') {$start_time += 1200;}
		
		// first query and result
		//-----------------------------------------------------------------------------
		$cid = 200000;

		$sql = "SELECT cid FROM courses";
		
		$result = mysqli_query($link, $sql);
		
		$used_cid_array = array();
		
		while($new_row = mysqli_fetch_assoc($result)) {
			$used_cid_array[] = $new_row["cid"];
		}
		
		$max = count($used_cid_array);
		for($i = 0; $i < $max; ++$i) {
			if($cid == $used_cid_array[$i]) {
				$cid++;
			}
			else {
				break;
			}
		}
		
		//-----------------------------------------------------------------------------
		if($cid > 299999) {
			echo "
				<body>
				<p>
				There are currently too many courses to add another.
				</p>
				</body>
			";
			
			instructor_links($_SESSION['valid_instructor']);
		}
		else if(strlen($c_name) > 1 &&
				strlen($start_time) > 1 &&
				strlen($end_time) > 1 &&
				strlen($c_day) > 1 &&
				strlen($location) > 1) {
		
			// second query and result
			//-------------------------------------------------------------------------
			$sql = "SELECT c_name
					FROM courses
					WHERE c_name = '$c_name'
					AND end_time = $end_time
					AND c_day = '$c_day'
					AND location = '$location'
				   ";
					
			$result = mysqli_query($link, $sql);
		
			// if result is not empty
			if(mysqli_num_rows($result) > 0) {
				
				echo "
					<body>
					<p>
					That course currently exists.
					</p>
					</body>
				";
				
				instructor_links($_SESSION['valid_instructor']);
			}
			else {
				
				// third query and result
				//--------------------------------------------------------------------
				$sql = "INSERT INTO courses VALUES (
							$cid, $iid, '$c_name',
							$start_time, $end_time,
							'$c_day', '$location'
						)
					   ";
				
				$result = mysqli_query($link, $sql);
				
				// attempt insert query
				if($result) {
					echo $c_name . ' has been added.<br /><br />';
					
					instructor_links($_SESSION['valid_instructor']);
				}
				else {
					echo "ERROR";
					
					instructor_links($_SESSION['valid_instructor']);
				}
			}
		}
		else {
			echo "Each course field must be greater than one character.";
			
			instructor_links($_SESSION['valid_instructor']);
		}
		 
		// close connection
		mysqli_close($link);
		
	}
	else {
		
		echo "
			<head>
			<title>Create Course</title>
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
				<h1>Create Course</h1>
				<br />
				
				<div class = 'container'>
					<form action = 'Instructor_Create_Course.php' method = 'post'>
						<label>Course Name</label>
						<input type = 'text' name = 'c_name'>
						<br />
						<label>Start Time</label>
						<input type = 'text' name = 'start_time'>
						<select name = 'am_pm_start'>
							<option value = 'am' selected>am</option>
							<option value = 'pm'>pm</option>
						</select>
						<br />
						<label>End Time</label>
						<input type = 'text' name = 'end_time'>
						<select name = 'am_pm_end'>
							<option value = 'am' selected>am</option>
							<option value = 'pm'>pm</option>
						</select>
						<br />
						<label>Days</label>
						<select name = 'c_day'>
							<option value = 'MW' selected>MW</option>
							<option value = 'TR'>TR</option>
						</select>
						<br />
						<label>Location</label>
						<input type = 'text' name = 'location'>
						<input type = 'submit' value = 'Create Course'>
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