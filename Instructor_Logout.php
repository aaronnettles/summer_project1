<?php
	// in regard to session variables
	session_start();
	
	// make copies of session variables
	$old_instructor = $_SESSION['valid_instructor'];
	$old_iid = $_SESSION['iid'];
	
	// unset session variables
	unset($_SESSION['valid_instructor']);
	unset($_SESSION['iid']);
	
	// end session
	session_destroy();
?>

<?php
	if(!empty($old_instructor) && !empty($old_iid)) {
		
		echo "
			<html>
			<body>
			<h1>Have a great day " . $old_instructor . "!</h1>
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