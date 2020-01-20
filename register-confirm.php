<?php
	require 'config/config.php';

	// make sure everything is properly submitted
	if (!isset($_POST['username']) || empty($_POST['username'])
		|| !isset($_POST['password']) || empty($_POST['password']) 
		||!isset($_POST['password-match']) || empty($_POST['password-match']) ) {
		$error = "Please fill out all required fields.";
	} else if($_POST["password"] != $_POST["password-match"]) {
		// check that passwords match before actually doing DB stuff
		$error = "Passwords don't match!";
	}
	else {
		// Connect to the database and add this new user into the users table
		$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if($mysqli->connect_errno) {
			echo $mysqli->connect_error;
			exit();
		}
		// Sanitize user input: from your lectures
		$username = $mysqli->real_escape_string($_POST['username']);
		$password = $_POST['password'];

		// Hash password, using sha256
		$password = hash("sha256", $password);

		// Check if this user already exists in the database
		$queryUser= "SELECT * FROM users WHERE username = '" . $username . "';";
		$results_registered = $mysqli->query($queryUser);
		if(!$results_registered) {
			echo $mysqli->error;
			exit();
		}

		// if the user already exists show this message
		if( $results_registered->num_rows > 0 ) {
			$error = "Username has been already taken. Please choose another one.";
		} else {
			// if original username, lets put it into the users database
			$sqlAddUser = "INSERT INTO users(username, password) VALUES('" . $username . "', '" . $password . "');";
			$results = $mysqli->query($sqlAddUser);
			// if a problem shows up, lets echo the error
			if (!$results) {
				echo $mysqli->error;
			}
		}
		// finally close the sqli connection!
		$mysqli->close();
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Registration Confirmation | Song Database</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="css/login.css" type="text/css">
	</head>
	<body>
		<div class="container">
			<div class="row">
				<h1 class="col-12">User Registration</h1>
			</div>
		</div>

		<div class="container">

			<div class="row">
				<div class="col-12">
					<?php if ( isset($error) && !empty($error) ) : ?>
						<div class="text-danger"><?php echo $error; ?></div>
					<?php else : ?>
						<div class="text-success"><?php echo $_POST['username']; ?> was successfully registered.</div>
					<?php endif; ?>
			</div>
			<div class="row">
				<div class="col-12">
					<a href="login.php" role="button" class="btn button-custom">Login</a>
					<a href="register.php" role="button" class="btn button-custom">Back</a>
				</div>
			</div>
		</div>
	</body>
</html>