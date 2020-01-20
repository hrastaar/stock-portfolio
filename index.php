<?php
    # start + destroy current session
	session_start();
	session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
    	<meta charset="UTF-8">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
    	<title>Stock Market Portfolio</title>
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="css/index.css" type="text/css">
    </head>
    <body>
    	<div class="container">
    		<div class="row">
    			<h1 class="col-12 mt-4 mb-4" style="text-align: center;">Welcome to Your Stock Index</h1>
    		</div>
            <div class="row">
                <img id="welcome-image" src="welcome-image.png" alt="welcome image failed to load" style="width: 65%;">
            </div>
    	</div>

        <br>
        <br>

    	<div class="container">
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
            <br>
            <div class="row">
    			<div class="col-12 col-md-6">
    				<a href="search.php" class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" role="button">Continue as Guest</a>
    			</div>
    		</div>
        </div>
        <br>
        <hr>
        <div class="container">
            <div class="row">
                <h2 class="col-12 mt-4 mb-4">Search for Live Stock Data</h2>
            </div>
            <div class="detail">
                <p>Using the Alpha-Vantage JavaScript API, this stock portfolio tracker gathers live NYSE data and formats the data into a concise and informative platform.</p>
            </div>
            <br>
            <hr class="smaller-hr"> 
            <div class="row">
                <h2 class="col-12 mt-4 mb-4">Create your own Portfolio</h2>
            </div>
            <div class="detail">
                <p>You are able to add and remove any stocks you want to your virtual portfolio. This allows you to create and edit your dream portfolio.</p>
                <p>You can edit your 'buying price' when adding a stock to your portfolio and view the profitability of your portfolio by comparing your buying prices with current stock price data</p>
            </div>
        </div>
    </body>
</html>