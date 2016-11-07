<?php
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
	
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with no password)
	$link = mysqli_connect("localhost", "root", "rambo", "team_project");
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	// attempt insert query execution
	$sql = "SELECT cid, c_name, start_time, end_time, c_day, location
			FROM courses";
	
	// run query
	$result = mysqli_query($link, $sql);
	
	// empty result
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
?>