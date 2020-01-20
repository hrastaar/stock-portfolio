<?php
	// required file to login
    require 'config/config.php';
    session_start();
	// Connect to DB 
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $errorMsg = "";
	// Check for DB connection error
	if( $mysqli->connect_errno ) {
		echo $mysqli->connect_error;
        // Exit the program if there's an error. There's no reason to continue the program.
        echo "Error connecting to the database";
        // gracefully end the php script
		exit();
    }
    
    if( isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] == true) {
        header("Location: portfolio.php");
    } else {
        if( isset($_POST["username"]) && isset($_POST["password"]) ) {
            // try to log in
            if(empty($_POST["username"]) || empty($_POST["password"]) ) {
                // empty text field
                $errorMsg = "Please enter a username and password";
            } else {
                // check credentials with database
                $usernameInput = $_POST["username"];
                $passwordInput = $_POST["password"];
                // Hash user input of password to compare this string to the password stored in the users table
                $passwordInput = hash("sha256", $passwordInput);

                $query = "SELECT * FROM users
                            WHERE username = '" . $usernameInput . "' AND password = '" . $passwordInput . "';";
                
                $results = $mysqli->query($query);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }
                // If there is a match, we will get at least one result back
                if( $results->num_rows > 0) {
                    // Log them in!
                    $_SESSION['logged_in'] = true;
                    $_SESSION['username'] = $_POST['username'];
                    $idQuery = "SELECT * FROM users WHERE username='" . $_POST['username'] . "';";
                    $userID = $mysqli->query($idQuery);
                    $user1 = $userID->fetch_assoc();
                    $userID = $user1['user_id'];
                    echo $userID;
                    $_SESSION['userID'] = $userID;
                    header('Location: portfolio.php');
                } else {
                    $errorMsg = "Invalid login credentials";
                }
            } // end else-statement
        } // end if-statement
    } // end else-statement

?>
<!-- The in-class lectures helped a lot with the bootstrap class formatting -->
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Log In</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/login.css" type="text/css">
</head>
<body>
	<div class="container">
        <div class="row">
            <h1 class="col-12 mt-4 mb-4 white" style="color: white; text-align: center;">Stock Market Portfolio</h1>
        </div>
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Log In</h1>
		</div>
	</div>
	<div class="container">
        <form action="login.php" method="POST">
            <?php
                if($errorMsg != "") {
                    echo ("<p class='errorMsg'>" . $errorMsg . "</p>");
                }
            ?>
			<div class="row">
				<label for="title-id" class="col-form-label text-sm-right">Username:</label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="title-id" name="username" placeholder="username">
				</div>
			</div>
            <br>
            <div class="row">
				<label for="title-id" class="col-form-label text-sm-right">Password:</label>
				<div class="col-sm-9">
					<input type="password" class="form-control" id="title-id" name="password" placeholder="password">
				</div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 col-md-6">
                    <button class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" type="submit">Log In</button>
                </div>
            </div>
        </form>
    
        <br>
        <hr class="smaller-hr">
        <br>

        <div class="row">
        <div class="col-12 col-md-6">
                <button class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" onclick='window.location.href = "index.php"'>Back to Menu</button>
            </div>
        </div>
    </div>
</body>
</html>