<?php
	// required file to login
    require 'config/config.php';
    session_start();
    date_default_timezone_set('America/Los_Angeles');

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
    // only add to history if user logged in and not an order sending (that would duplicate the search twice.)
    if(isset($_SESSION["username"]) && !empty($_SESSION["username"])
            && isset($_GET["index"]) && !empty($_GET["index"])
            &&!isset($_POST["order_quantity"]) && empty($_POST["order_quantity"])
            &&!isset($_POST["order_price"]) && empty($_POST["order_price"])) {
//        $historySQL = "INSERT INTO history(username, stock_index, date) VALUES('" . $_SESSION["username"] . "', '" . $_GET["index"] . "', '" . date("Y-m-d H:i:s") ."');";
//
//        $historyResults = $mysqli->query($historySQL);
        $historySQL = "INSERT INTO stock_history(user_id, stock_index, date) VALUES(" . $_SESSION["userID"] . ", '" . $_GET["index"] . "', '" . date("Y-m-d H:i:s") ."');";
        $historyResults = $mysqli->query($historySQL);

        if(!$historyResults) {
            echo $mysqli->error;
            exit();
        }
    }

	// submit order if possible
    if(isset($_POST["order_index"]) && !empty($_POST["order_index"])
        && isset($_POST["order_quantity"]) && !empty($_POST["order_quantity"])
        && isset($_POST["order_price"]) && !empty($_POST["order_price"])) {
            // ONLY CONNECT TO DB HERE
            if(isset($_SESSION["username"]) && !empty($_SESSION["username"])) {
                // add the user's order to the DB
//                $sql = "INSERT INTO orders(username, stock_index, buy_price, order_quantity, notes)
//                            VALUES('" . $_SESSION["username"] . "', '"
//                    . $_POST['order_index']
//                    . "', '"
//                    . $_POST['order_price']
//                    . "', '" . $_POST['order_quantity']
//                    . "', '" . $_POST['order_notes']
//                    . "');";

                $sql = "INSERT INTO stock_orders(user_id, stock_index, buy_price, order_quantity, notes) 
                            VALUES(" . $_SESSION["userID"] . ", '"
                    . $_POST['order_index']
                    . "', '"
                    . $_POST['order_price']
                    . "', '" . $_POST['order_quantity']
                    . "', '" . $_POST['order_notes']
                    . "');";
                $results = $mysqli->query($sql);
                if(!$results) {
                    echo $mysqli->error;
                    exit();
                }
                // If record has been inserted, mysqli->affected_rows will return 1.
                $isInserted = "";
                if( $mysqli->affected_rows == 1 ) {
                    $isInserted = true;
                }
                $mysqli->close();

                $successMessage = "You successfully added "
                    . $_POST["order_quantity"]
                    . " shares of "
                    . $_POST["order_index"]
                    . " to your portfolio!";
                // send an alert that the order was placed
                echo '<script language="javascript">';
                echo 'alert("' . $successMessage . '");';
                echo '</script>';
                // reset the post values so that a page refresh won't result in another order being placed
                unset($_POST["order_quantity"]);
                unset($_POST["order_price"]);
                unset($_POST["order_notes"]);

            } else {
                // print a warning message that you must be logged in to order
                echo '<hr>';
                echo '<h3>Must Be logged in to order</h3>';
            }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Search Results for <?php echo $_GET["index"];?></title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="css/portfolio.css" type="text/css">
        <script src="js/search_results.js"></script>
    </head>
    <body>
        <!-- uniform navbar - Thanks. the lectures really helped with this portion and the class selection -->
        <nav class="navbar navbar-expand-mg navbar-dark robin">
            <a class="navbar-brand" href="search.php">Stock Market Portfolio</a>
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
                <h2 id="stock-name" class="col-12 mt-4 mb-4"></h2>
            </div>
            <div class="row">
                <h2 id="stock-index"></h2>
            </div>
            <div class="row">
                <h3 id="stock-price" style="color: white;"></h3>
            </div>
            <div class="row">
                <h4 id="daily-change" style="color: white;"></h4>
            </div>
        </div>

        <br>
        <br>

        <div class="container" id="order-form-container">
            <br>
            <div class="row">
                <h3 class="white-font" id="order-heading" style="color: white;">Place An Order</h3>
            </div>
            <br> 

            <form action="search_results.php?index=<?php echo $_GET['index']; ?>" method="POST">
                <input type="hidden" name="order_index" id="order_index" value="<?php echo $_GET["index"]?>">
                <div class="form-group row">
                    <label for="name-id" class="col-sm-3 col-form-label text-sm-right">
                        Quantity:
                    </label>
                    <div class="col-sm-9">
                        <input value = 1 type="number" class="form-control" id="order_quantity" name="order_quantity">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name-id" class="col-sm-3 col-form-label text-sm-right">
                        Buying Price:
                    </label>
                    <div class="col-sm-9">
                        <!-- for my stock prices, must be a positive price, and cannot surpass $1 million -->
                        <input type="number" min="0.00" max="1000000.00" step="0.01" class="form-control" id="order_price" name="order_price">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name-id" class="col-sm-3 col-form-label text-sm-right">
                        Order Notes:
                    </label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" id="order_notes" name="order_notes" placeholder="enter any notes on this <?php echo $_GET["index"]?> order">
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
			    </div>
            </form>
        </div>
    </body>
</html>