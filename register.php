<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register | Song Database</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/login.css" type="text/css">
</head>
<body>
	<div class="container">
        <div class="row">
            <h1 class=" white col-12 mt-4 mb-4" style="color: white; text-align: center;">Stock Market Portfolio</h1>
        </div>
		<div class="row">
			<h1 class="col-12 mt-4 mb-4">Create an Account</h1>
		</div>
	</div>
	<div class="container">
		<form action="register-confirm.php" method="POST">
			<div class="form-group row">
				<label for="username-id" class="col-sm-3 col-form-label text-sm-right">Username: <span class="text-danger">*</span></label>
				<div class="col-sm-9">
					<input type="text" class="form-control" id="username-id" name="username">
					<small id="username-error" class="invalid-feedback">Username needed.</small>
				</div>
			</div>

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
                    <button class="btn btn-primary btn-lg btn-block mt-4 mt-md-2 button-custom" type="submit">Register</button>
                </div>
            </div>
			<div class="row">
				<div class="col-sm-9 ml-sm-auto">
					<a href="login.php">Already have an account</a>
				</div>
			</div>
		</form>
	</div>
    <script>
		document.querySelector('form').onsubmit = function(){
			if ( document.querySelector('#username-id').value.trim().length == 0 ) {
				document.querySelector('#username-id').classList.add('is-invalid');
			} else {
				document.querySelector('#username-id').classList.remove('is-invalid');
			}
			if ( document.querySelector('#email-id').value.trim().length == 0 ) {
				document.querySelector('#email-id').classList.add('is-invalid');
			} else {
				document.querySelector('#email-id').classList.remove('is-invalid');
			}
			if ( document.querySelector('#password-id').value.trim().length == 0 ) {
				document.querySelector('#password-id').classList.add('is-invalid');
			} else {
				document.querySelector('#password-id').classList.remove('is-invalid');
			}
			// return false prevents the form from being submitted
			// If length is greater than zero, then it means validation has failed. Invert the response and can use that to prevent form from being submitted.
			return ( !document.querySelectorAll('.is-invalid').length > 0 );
		}
	</script>
</body>
</html>