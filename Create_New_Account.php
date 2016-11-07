<?php
	echo "
		<html>

		<head>
			<title>New Account</title>
			<!-- Comments! -->
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
			<h1>Create New Student Account</h1>
			<br />
			
			<div class = 'container'>
				<form action = 'Create_New_Student_Account.php' method = 'post'>
					<label>First Name</label>
					<input type = 'text' name = 'f_name'>
					<br /><label>Last Name<label>
					<input type = 'text' name = 'l_name'>
					<br /><label>Email Address<label>
					<input type = 'text' name = 'email'>
					<br /><label>Major</label><br />
					<select name = 'major'>
						<option value = 'CS' selected>CS</option>
						<option value = 'IT'>IT</option>
						<option value = 'MATH'>MATH</option>
						<option value = 'PHYS'>PHYS</option>
					</select>
					<br /><br />
					<input type = 'submit' value = 'New Student Account'>
					<input type = 'reset' value = 'Reset Form'>
				</form>
			</div>
			
			<br />
			<h1>Create New Instructor Account</h1>
			<br />
			
			<div class = 'container'>
				<form action = 'Create_New_Instructor_Account.php' method = 'post'>
					<label>First Name</label>
					<input type = 'text' name = 'f_name'>
					<br /><label>Last Name<label>
					<input type = 'text' name = 'l_name'>
					<br /><label>Email Address<label>
					<input type = 'text' name = 'email'>
					<br /><label>Department</label><br />
					<select name = 'department'>
						<option value = 'CS' selected>CS</option>
						<option value = 'IT'>IT</option>
						<option value = 'MATH'>MATH</option>
						<option value = 'PHYS'>PHYS</option>
					</select>
					<br /><br />
					<input type = 'submit' value = 'New Instructor Account'>
					<input type = 'reset' value = 'Reset Form'>
				</form>
			</div>
		</body>
			
		</html>
	";
?>
