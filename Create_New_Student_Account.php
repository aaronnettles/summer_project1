<?php
	// Attempt MySQL server connection. Assuming you are running MySQL
	// server with default setting (user 'root' with no password)
	$link = mysqli_connect("localhost", "root", "rambo", "team_project");
	 
	// test connection
	if($link === false) {
		die("ERROR");
	}
	
	// Escape user inputs for security
	$first_name = mysqli_real_escape_string($link, $_POST['f_name']);
	$last_name = mysqli_real_escape_string($link, $_POST['l_name']);
	$email = mysqli_real_escape_string($link, $_POST['email']);
	$major = $_POST['major'];
	
	// first query and result
	//-----------------------------------------------------------------------------
	$sid = 100000;

	$sql = "SELECT sid FROM students";
	
	$result = mysqli_query($link, $sql);
	
	$used_sid_array = array();
	
	while($new_row = mysqli_fetch_assoc($result)) {
		$used_sid_array[] = $new_row["sid"];
	}
	
	$max = count($used_sid_array);
	for($i = 0; $i < $max; ++$i) {
		if($sid == $used_sid_array[$i]) {
			$sid++;
		}
		else {
			break;
		}
	}
	
	//-----------------------------------------------------------------------------
	if($sid > 199999) {
		echo "
			<body>
			<p>
			There are currently too many instructors to add another.
			</p>
			</body>
		";
	}
	else if(strlen($first_name) > 1 &&
			strlen($last_name) > 1 &&
			strlen($email) > 1) {
	
		// second query and result
		//-------------------------------------------------------------------------
		$sql = "SELECT f_name, l_name, email
				FROM students
				WHERE f_name = '$first_name'
				AND l_name = '$last_name'
				AND email = '$email'
			   ";
				
		$result = mysqli_query($link, $sql);
	
		// if result is not empty
		if(mysqli_num_rows($result) > 0) {
			
			echo "
				<body>
				<p>
				That name and email already exist for another student.
				</p>
				</body>
			";
		}
		else {
			
			// use a function to generate new password for instructor
			$password = 'pass';
			
			// third query and result
			//---------------------------------------------------------------------
			$sql = "INSERT INTO students VALUES (
						$sid, '$password',
						'$first_name', '$last_name',
						'$email', '$major'
					)
				   ";
			
			$result = mysqli_query($link, $sql);
			
			// attempt insert query
			if($result) {
				
				echo $first_name . ' ' . $last_name . ' has been added.<br />';
				echo 'Your ID is: ' . $sid . '<br />';
				echo 'Your password is: ' . $password . '<br />';
			}
			else {
				echo "ERROR";
			}
		}
	}
	else {
		echo "Names and email must be greater than one character.";
	}
	 
	// close connection
	mysqli_close($link);
?>