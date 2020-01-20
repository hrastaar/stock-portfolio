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

	if(isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
        $sql = "SELECT * FROM stock_history WHERE user_id=" . $_SESSION["userID"] . " ORDER BY date DESC;";
        // call to the database
        $results = $mysqli->query($sql);
        // get the number of results
        $numResult = $results->num_rows;
        $currentRow = 0;
        // print errors
        if(!$results) {
            echo $mysqli->error;
            exit();
        }
    }
    // close the DB connection
    $mysqli->close();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Search | NYSE</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
		<script src="js/search.js"></script>
        <link rel="stylesheet" href="css/portfolio.css" type="text/css">
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
	                <a class="nav-link active" href="search.php">Search<span class="sr-only">(current)</span></a>
	              </li>
	              <li class="nav-item">
				  	<a class="nav-link" href="portfolio.php">My Portfolio</a>
	              </li>
	                <?php if (isset($_SESSION["username"]) && !empty($_SESSION["username"]) ) : ?>
	                  <li class="nav-item">
                        <a class="nav-link" href="change-password.php">Change Password</a>
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
	            <h1 class="col-12 mt-4 mb-4">Search for a Stock by Index</h1>
	        </div>
	        <div class="row">
	            <h3 class="col-12 mt-4 mb-4">Examples: FB, AAPL, AMZN, NFLX, GOOGL</h3>
			</div>
		</div>
		<div class="container">

			<form action="search_results.php" method="GET">
	            <div class="row">
					<label for="name-id" class="col-sm-3 col-form-label text-sm-right">Index</label>
					<div class="col-sm-9">
						<input type="text" maxlength="5" class="form-control" id="index" name="index" placeholder="type the stock index here">
					</div>
				</div>
                <br>
				<div class="row">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<button type="submit" class="btn btn-primary">Search</button>
					</div>
				</div>
			</form>
		</div>
        <?php if(isset($_SESSION["username"]) && !empty($_SESSION["username"])) : ?>
            <div id="history" class="container">
                <br>
                <div class="row">
                    <h4>Your Recent Searches</h4>
                </div>
                <hr style="border-color: darkgray;">
                <?php $historyCount = 0;?>
                <?php while($rows = $results->fetch_assoc()) : ?>
                <a class="historyRow" href="search_results.php?index=<?php echo $rows["stock_index"]; ?>">
                    <div class="row">
                        <p>
                            <?php
                            echo $rows["stock_index"];
                            echo "\t";
                            echo $rows["date"];
                            ?>
                        </p>
                    </div><hr style="border-color: darkgray;">
                </a>
                <?php $historyCount = $historyCount+1;
                    if($historyCount > 6) {
                        break;
                    }
                    endwhile;
                ?>
            </div>
        <?php endif; ?>
	</body>
</html>