<?php
    require 'config/config.php';
    session_start();
    if(!isset($_POST["password"]) && !isset($_POST["password-match"])) {
        // DO NOTHING
    } else if (!isset($_POST['password']) || empty($_POST['password']) 
        ||!isset($_POST['password-match']) || empty($_POST['password-match']) ) {
        $error = "Please fill out all required fields.";
    } else if($_POST["password"] != $_POST["password-match"]) {
        // check that passwords match before actually doing DB stuff
        $error = "Passwords don't match!";
    } else {
        // Connect to the database and add this new user into the users table
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if($mysqli->connect_errno) {
            echo $mysqli->connect_error;
            exit();
        }
        $password = $_POST['password'];

        // Hash password, using sha256
        $password = hash("sha256", $password);

        // Check if this user already exists in the database
        $queryUser= "UPDATE users SET password='" . $password . "' WHERE username = '" . $_SESSION["username"] . "';";
        $results_registered = $mysqli->query($queryUser);
        $successMessage = "You successfully changed your password.";
        
        echo '<script>';
        echo 'alert("' . $successMessage . '");';
        echo '</script>';
        if(!$results_registered) {
            echo $mysqli->error;
            exit();
        }
        // finally close the sqli connection!
        $mysqli->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Change Password</title>
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    	<link rel="stylesheet" href="css/portfolio.css" type="text/css">
        <script src="js/portfolio.js"></script>
        <script
                src="http://code.jquery.com/jquery-3.4.1.min.js"
                integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
                crossorigin="anonymous"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-mg navbar-dark robin">
            <a class="navbar-brand" href="#">Stock Market Portfolio</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="search.php">Search</a>
                    </li>
                    <li class="nav-item">
                    	<a class="nav-link" href="portfolio.php">My Portfolio</a>
                    </li>
                    <?php if (isset($_SESSION["username"]) && !empty($_SESSION["username"]) ) : ?>
                        <li class="nav-item active">
                            <a class="nav-link" href="change-password.php">Change Password<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a onclick="return logout();" class="nav-link" href="index.php">Log Out</a>
                        </li>
                    <?php else : ?>
                        <li class="nav-item">
                        <a class="nav-link" href="login.php">Log In</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    	<div class="container">
    		<div class="row">
    			<h1 class="col-12 mt-4 mb-4">Change Password</h1>
    		</div>
    	</div>
        <div class="container">
            <?php if (!isset($_SESSION["username"]) && empty($_SESSION["username"]) ) : ?>
                <div class="row">
                    <h2 style="text-align: center;">Must Have an Account to Change Password</h2>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <a href="login.php" class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" role="button">Log In</a>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <a href="register.php" class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" role="button">Create an Account</a>
                    </div>
                </div>
            <?php else : ?>

            <form action="change-password.php" method="POST">

                <div class="form-group row">
                    <label for="password-id" class="col-sm-3 col-form-label text-sm-right">Password: <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password-id" name="password">
                        <small id="password-error" class="invalid-feedback">Password needed.</small>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="password-id" class="col-sm-3 col-form-label text-sm-right">Confirm Password: <span class="text-danger">*</span></label>
                    <div class="col-sm-9">
                        <input type="password" class="form-control" id="password-match-id" name="password-match">
                        <small id="password-error" class="invalid-feedback">Must confirm password.</small>
                    </div>
                </div>
                <div class="row">
                    <div class="ml-auto">
                        <span class="text-danger font-italic">* Required</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-6">
                        <button class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" type="submit">Change Password</button>
                    </div>
                </div>
            </form>

            <?php endif; ?>
        </div>
    </body>
</html>