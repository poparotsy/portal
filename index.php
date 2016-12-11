<?php
require_once ('includes/config.php');

if ($user->is_logged_in ()) {
	header ( 'Location: main.php' );
}

/*
 * Login Form
 */
if (isset ( $_POST ['submit'] )) {
	
	$user->username = $_POST ['username'];
	$user->password = $_POST ['password'];
	
	if ($user->login ( $user->username, $user->password )) {
		$_SESSION ['username'] = $user->username;
		header ( 'location: memberArea.php' );
		exit ();
	} else {
		$error [] = "Wrong username or password or account has not been activated yet.";
	}
}

/*
 * If the login was successfull
 */
$title = 'Login';

// include header template
require ('includes/header.php');
?>


<div class="container">
<div class="row">
				
			<form role="form" method="post" action="" autocomplete="off" class="form-signin mg-btm">
				<h3 class="heading-desc">Login</h3>

				<?php
				// check for any errors
				if (isset ( $error )) {
					foreach ( $error as $error ) {
						echo '<p class="bg-danger">' . $error . '</p>';
					}
				}
				
				if (isset ( $_GET ['action'] )) {
					
					// check the action
					switch ($_GET ['action']) {
						case 'active' :
							echo "<h4 class='bg-success'>Your account is now active you may now log in.</h4>";
							break;
						case 'reset' :
							echo "<h4 class='bg-success'>Please check your inbox for a reset link.</h4>";
							break;
						case 'resetAccount' :
							echo "<h4 class='bg-success'>Password changed, you may now login.</h4>";
							break;
						case 'joined' 	:
							echo "<h4 class='bg-success'>Please check your inbox for account activation link.</h4>";
					}
				}
				
				?>






<div class="main">
<label>Username</label>
<div class="input-group">
<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
<input type="text" class="form-control" placeholder="Username" value="<?php if(isset($error)){ echo $_POST['username']; } ?>" name="username" id="username" tabindex="1" autofocus>
</div>

<label>Password  <a href="reset.php">(forgot password)</a></label>

<div class="input-group">
<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
<input type="password" class="form-control" placeholder="Password" name="password" id="password" autocomplete="off" tabindex="2">
</div>

<div class="row">
<div class="col-xs-6 col-md-6">
</div>


<div class="col-xs-6 col-md-6 pull-right">
<button type="submit" name="submit" class="btn btn-large btn-success pull-right" tabindex="3">Login</button>
</div>
</div>
</div>
<span class="clearfix"></span>
<div class="login-footer">
<div class="row">
<div class="col-xs-6 col-md-6">
<div class="left-section">
<a href="register.php">Create an account</a>
</div>
</div>
<div class="col-xs-6 col-md-6 pull-right">
</div>
</div>
</div>
</form>
</div>
</div>



<?php
// include header template
require ('includes/footer.php'); 
?>