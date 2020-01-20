<?php
    require 'config/config.php';
	session_start();
    if( isset($_SESSION["username"]) ) {
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
        $sql = "SELECT * FROM stock_orders WHERE user_id=" . $_SESSION["userID"] . ";";
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
        // close the DB connection
        $mysqli->close();
    } else {
        $usernameTitle = "My";
    }
    $costs = 0.00;
?>
<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>My Portfolio</title>
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
                    <li class="nav-item active">
                    	<a class="nav-link" href="portfolio.php">My Portfolio<span class="sr-only">(current)</span></a>
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
    			<h1 class="col-12 mt-4 mb-4"><?php echo $usernameTitle; ?> Portfolio</h1>
    		</div>
            <p id="curr" style="display: none;"></p>
            <p id="net" style="display: none;">Net Profit/Loss: $0.00</p>
    	</div>
        <div class="container">
            <?php if (!isset($_SESSION["username"]) || empty($_SESSION["username"]) ) : ?>
                <div class="row">
                    <h2 style="text-align: center;">Must Have an Account to Use the Portfolio Functionality</h2>
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
                <!-- ADD AN ELSE STATEMENT HERE FOR LOGGED IN USER: THE HEART OF THE APP -->
            <!-- IMPORTANT: I STILL DON'T KNOW EXACTLY HOW I'M GOING TO MAKE THIS LOOK YET, SO I LEFT IT AT DEFAULT -->
                <table class="port table table-hover table-responsive mt-4 text-light">
                    <thead>
                    <tr>
                        <th>Stock</th>
                        <th>Quantity</th>
                        <th>Your Cost</th>
                        <th>Current Price</th>
                        <th>Notes</th>
                        <th>Remove Stock</th>
                    </tr>
                    </thead>
                    <tbody>
                    <script>var currBalance = 0.00;</script>
                        <?php while($rows = $results->fetch_assoc() ) : ?>
                            <?php $costs += ($rows["order_quantity"]*$rows["buy_price"]); ?>
                            <tr>
                                <td> <?php echo $rows["stock_index"] ?> </td>
                                <td id="quantity-<?php echo $currentRow;?>"> <?php echo $rows["order_quantity"] ?> </td>
                                <td id="price-<?php echo $currentRow;?>"> <?php echo $rows["buy_price"] ?> </td>
                                <td id="curr-price-<?php echo $currentRow;?>"></td>
                                <script>
                                    currRow = document.querySelector("#curr-price-<?php echo $currentRow;?>");
                                    currRow.innerHTML =  "loading...";
                                    $.ajax({
                                        method: "GET",
                                        url: "https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=<?php echo $rows["stock_index"];?>&apikey=PY60RVS8O6E0ZHLK",
                                        async: false
                                    })
                                    .done(function( res ) {
                                        /**** FUNCTIONALITY: SET THE PORTFOLIO BALANCE + NET PROFIT HEADINGS ****/

                                        // IF THERE IS AN ERROR, IT MEANS THAT WE CALLED THE API MORE THAN THE CALL-LIMIT ALLOWS
                                        console.log(res);
                                        // get the price data from the API call, fixed to 2 decimal places
                                        var decimalPrice = Number.parseFloat(res["Global Quote"]["05. price"]).toFixed(2);
                                        console.log(decimalPrice);
                                        currRow.innerHTML = decimalPrice.toString(); // store that value in HTML for easy access in php
                                        // calculate total order value by multiplying by quantity
                                        currBalance += (decimalPrice * parseInt("<?php echo $rows['order_quantity']; ?>"));
                                        //currBalance = currBalance.toFixed(2);

                                        console.log(currBalance);
                                        // update the portfolio value after adding each order's current stock value
                                        document.querySelector("#curr").innerHTML = "Your Portfolio Value: $" + currBalance;
                                        document.querySelector("#buyingP").innerHTML = currBalance;
                                        var buyingPrices = "<?php echo $costs; ?>";
                                        //console.log(currBalance);
                                        let profitElement = document.querySelector("#net");
                                        profitElement.innerHTML = "Net Profit: $" + (parseFloat(document.querySelector("#buyingP").innerHTML) - parseFloat("<?php echo $costs;?>"));
                                        if(parseFloat(document.querySelector("#buyingP").innerHTML) - parseFloat("<?php echo $costs;?>") < 0) {
                                            // if negative show with red font ~~~Very fancy move
                                            profitElement.style.color = "red";
                                        }
                                        //document.querySelector("#current-value").innerHTML = document.querySelector("#curr").innerHTML;
                                    });

                                </script>
                                <?php $currentRow = $currentRow + 1; ?>
                                <td> <?php echo $rows["notes"] ?> </td>
                                <td><a href="delete_stock.php?deleteID=<?php echo $rows['id'];?>">Remove Order</a>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <h3 id="current-value"></h3>
            <h5 id="net-profit-loss">Net Profit/Loss: $0.00</h5>
            <script>
                // update the profits / balance after 2 seconds // should be enough time for gathering data.
                setTimeout(()=>{
                    document.querySelector("#current-value").innerHTML = document.querySelector("#curr").innerHTML;
                    document.querySelector("#net-profit-loss").innerHTML = document.querySelector("#net").innerHTML;
                }, 2000);
            </script>
            <br>
            <br>
            <div class="row">
                <p>Please keep in mind that the stock api can only be called 5 times per minute.</p>
            </div>
        </div>
    </body>
</html>