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
	$department = $_POST['department'];
	
	// first query and result
	//-----------------------------------------------------------------------------
	$iid = 300000;

	$sql = "SELECT iid FROM instructors";
	
	$result = mysqli_query($link, $sql);
	
	$used_iid_array = array();
	
	while($new_row = mysqli_fetch_assoc($result)) {
		$used_iid_array[] = $new_row["iid"];
	}
	
	$max = count($used_iid_array);
	for($i = 0; $i < $max; ++$i) {
		if($iid == $used_iid_array[$i]) {
			$iid++;
		}
		else {
			break;
		}
	}
	
	//-----------------------------------------------------------------------------
	if($iid > 399999) {
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
				FROM instructors
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
				That name and email already exist for another instructor.
				</p>
				</body>
			";
		}
		else {
			
			// use a function to generate new password for instructor
			$password = 'pass';
			
			// third query and result
			//--------------------------------------------------------------------
			$sql = "INSERT INTO instructors VALUES (
						$iid, '$password',
						'$first_name', '$last_name',
						'$email', '$department'
					)
				   ";
			
			$result = mysqli_query($link, $sql);
			
			// attempt insert query
			if($result) {
				
				echo $first_name . ' ' . $last_name . ' has been added.<br />';
				echo 'Your ID is: ' . $iid . '<br />';
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