<?php
    require 'config/config.php';
	session_start();
    if( isset($_SESSION["username"]) && isset($_GET["deleteID"]) && !empty($_GET["deleteID"])) {
        $username = $_SESSION["username"];
        $usernameTitle = $username . "'s";
        // Connect to DB, code based greatly from your lecture, so thanks for making this hw more doable
        $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Check for DB connection error
        if( $mysqli->connect_errno ) {
            echo $mysqli->connect_error;
            // Exit the program if there's an error. There's no reason to continue the program.
            exit();
        }
        $mysqli->set_charset('utf8');
        $sql = "DELETE FROM stock_orders WHERE user_id=" . $_SESSION["userID"] . " " .
        "AND id=" . $_GET['deleteID'] . ";";
        // call to the database
        $results = $mysqli->query($sql);

        // print errors
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
        // close the DB connection
        $mysqli->close();
    } else {
        $usernameTitle = "Must Log In.";
    }
    $costs = 0.00;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Delete Stock</title>
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
    <p id="buyingP" style="display: none;"></p>
    <script>
        // declare variable
        var currRow;
    </script>
    <!-- THE NAV BAR CLASSES CAME FROM LECTURE / HW: SO THANK YOU FOR HELPING WITH THE CLASS SETUPS FOR THEM -->
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
    			<h1 class="col-12 mt-4 mb-4">Deleted Order!</h1>
    		</div>
    	</div>
        <div class="container">
            <?php if (!isset($_SESSION["username"]) || empty($_SESSION["username"]) ) : ?>
                <div class="row">
                    <h2 style="text-align: center;">Must Have an Account to Remove Stock</h2>
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
              <div class="row">
                <h4>Order removed from your portfolio</h4>
              </div>
              <div class="row">
                <div class="col-12 col-md-6">
                    <a href="portfolio.php" class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" role="button">Back to Portfolio</a>
                </div>
               </div>
            <?php endif; ?>
        </div>
    </body>
</html>