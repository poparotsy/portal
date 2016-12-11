<?php
require ('includes/config.php');
require ('includes/header.php');
$validate = array ();

$user->email = $_POST ['resetEmail'];

// if logged in redirect to userinfo page
if ($user->is_logged_in ()) {
	// header ( 'Location: memberpage.php' );
	
	require 'includes/navBar.php';
}

if (isset ( $_POST ['reset'] )) {
	
	// $validate[] = $user->validEmail($user->email);
	$validate [] = $user->getUserEmail ( $user->email );
	
	if (empty ( array_filter ( $validate ) )) {
		
		$restToken = $user->getRestToken ();
		
		$user->setResetToken ( $restToken, $user->email );
		
		/*
		 * Sending email with a URL includes the reset token.
		 */
		// $mailProperties = array();
		$mailProperties ['email'] = $user->email;
		$mailProperties ['subject'] = "Password Reset";
		$mailProperties ['body'] = "<p>Someone requested that the password be reset.</p>
			<p>If this was a mistake, just ignore this email and nothing will happen.</p>
			<p>To reset your password, visit the following address: 
					<a href='" . DIR . "resetPassword.php?key=$RestToken'>" . DIR . "resetPassword.php?key=$restToken</a></p>";
		
		$user->sendEmail ( $mailProperties );
		
		header ( 'location: index.php?action=reset' );
	}
}

/*
 *
 *
 */

// define page title
$title = 'Reset Account';

// include header template

?>

<div class="container center-div">

	<div class="row">

		<div class="col-lg-9 col-lg-offset-2">
			<form role="form" method="post" action="" autocomplete="off"
				id="resetForm" class="form-horizontal">
				<h2>Reset Password</h2>
				<p>
					<a href='index.php'>Back to login page</a>
				</p>
				<hr>

				<?php
				// check for any errors
				if (isset ( $validate )) {
					foreach ( $validate as $error ) {
						echo '<p class="bg-danger">' . $error . '</p>';
					}
				}
				
				if (isset ( $_GET ['action'] )) {
					
					// check the action
					switch ($_GET ['action']) {
						case 'active' :
							echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
							break;
						case 'reset' :
							echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
							break;
					}
				}
				?>

				<div class="form-group has-feedback">
					<label class="col-md-3 control-label">Email address</label>
					<div class="col-md-6">
						<input type="email" name="resetEmail" id="resetEmail"
							class="form-control" placeholder="Email" value="" tabindex="1">
					</div>
				</div>
				<!-- Captcha -->
				<div class="form-group">
					<label class="col-md-3 control-label" id="captchaOperation"></label>
					<div class="col-md-3">
						<input type="text" class="form-control" name="captcha" />
					</div>
				</div>

				<!-- 		 -->


				<div class="form-group">
					<label class="col-md-3 control-label"></label>
					<div class="col-md-3">

						<!--input type="submit" name="reset" value="Sent Reset Link" class="btn btn-primary" tabindex="2"-->
						<button type="submit" name="reset" class="btn btn-primary"
							tabindex="3">Send Reset Link</button>
					</div>
				</div>
			</form>
		</div>
	</div>


</div>

<script type="text/javascript" src="assets/js/resetForm.js"> </script>

<?php
// include header template
require ('includes/footer.php');

?>
