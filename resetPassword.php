<?php
require ('includes/config.php');

// if logged in redirect to members page
if ($user->is_logged_in ()) {
	header ( 'Location: memberArea.php' );
}

/*
 * TO DO
 * - Add email filed
 * - resetToken to null in the DB
 * - Add change my password page [either by itslef or in the user profile page]
 */

// ****
$key = $_GET ['key'];
$user->password = $_POST ['password'];
$user->email = $_POST ['resetPasswordEmail'];

$stop = $user->checkResetRequest ( $key );

if (isset ( $_POST ['changePassword'] )) {
	
	$validate [] = $user->validatePassword ( $user->password, $_POST ['passwordConfirm'] );
	
	$validate [] = $user->checkResetRequest ( $key, $user->email );
	
	if (empty ( array_filter ( $validate ) )) {
		
		$hashedPassword = $user->hashPassword ( $user->password );
		$user->resetPassword ( $hashedPassword, $key );
		
		// redirect to index page
		header ( 'Location: login.php?action=resetAccount' );
		exit ();
	}
}

// define page title
$title = 'Reset Account';

// include header template
require ('includes/header.php');
?>

<div class="container-fluid center-div">
	<div class="row">
		<section>
			<div class="col-md-9 col-md-offset-2">
		
	    			<?php
								// if (isset ( $stop )) {
								
								// echo "<p class='bg-danger'>$stop</p>";
								
								// } else {
								// 								?>

				<div class="page-header">
					<h2>Change Password</h2>
				</div>

				<form role="form" method="post" action="" autocomplete="off"
					class="form-horizontal" id="resetPasswordForm">
					<input type="hidden" name="key" value="<?php echo $_GET['key']; ?>">
			

					<?php
					// check for any errors
					if (isset ( $validate )) {
						foreach ( $validate as $error ) {
							echo '<p class="bg-danger">' . $error . '</p>';
						}
					}
					
					// // check the action
					// switch ($_GET ['action']) {
					// case 'active' :
					// echo "<h2 class='bg-success'>Your account is now active you may now log in.</h2>";
					// break;
					// case 'reset' :
					// echo "<h2 class='bg-success'>Please check your inbox for a reset link.</h2>";
					// break;
					// }
					?>
			<!-------Email--------->
					<div class="form-group">
						<label class="col-md-3 control-label">Email address</label>
						<div class="col-md-6">
							<input type="email" name="resetPasswordEmail"
								id="resetPasswordEmail" class="form-control"
								placeholder="Email Address" tabindex="1">
						</div>
					</div>
					<!--------------->

					<!-------------->
					<div class="form-group">
						<label class="col-md-3 control-label">Password</label>
						<div class="col-md-3">
							<input type="password" name="password" id="password"
								class="form-control" placeholder="Password" tabindex="2">
						</div>
						<div class="col-md-3">
							<input type="password" name="passwordConfirm"
								id="passwordConfirm" class="form-control"
								placeholder="Confirm Password" tabindex="3">
						</div>
					</div>
					<!--  -->
					<!-- Captcha -->
					<div class="form-group">
						<label class="col-md-3 control-label" id="captchaOperation"></label>
						<div class="col-md-2">
							<input type="text" class="form-control" name="captcha"
								tabindex="4" />
						</div>
					</div>

					<!-- BTN -->

					<div class="form-group">
						<div class="col-md-9 col-lg-offset-3">
							<button type="submit" name="changePassword"
								class="btn btn-primary" tabindex="5">Change Password</button>
						</div>
					</div>

				</form>

				<!--?php } ?-->

			</div>
		</section>
	</div>
</div>
<script type="text/javascript" src="assets/js/resetPassword.js"> </script>

<?php
// // include header template
require 'includes/footer.php';
?>