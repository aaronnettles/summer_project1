<?php
	// in regard to session variables
	session_start();
	
	// make copies of session variables
	$old_student = $_SESSION['valid_student'];
	$old_sid = $_SESSION['sid'];
	
	// unset session variables
	unset($_SESSION['valid_student']);
	unset($_SESSION['sid']);
	
	// end session
	session_destroy();
?>

<?php
	if(!empty($old_student) && !empty($old_sid)) {
		
		echo "
			<html>
			<body>
			<h1>Have a great day " . $old_student . "!</h1>
		";
		
		echo "
			<br />
			<a href = 'Index.html'>Return to Home Page</a>
			</body>
			</html>
		";
	}
	else {
		
		echo "
			<html>
			<body>
			You were not logged in and therefore, cannot be logged out.
			<br />
			<a href = 'Index.html'>Return to Home Page</a>
			</body>
			</html>
		";
	}
?>