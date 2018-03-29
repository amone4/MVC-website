<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/forms.css">

	<div class="row">
		<div class="col-10 offset-1 col-md-6 offset-md-3 card">
			<h2 class="text-center card-title">Login</h2>

			<div class="btn-group text-center">
				<button class="btn btn-sm btn-outline-success form-control" id="passLogin">Use Password</button>
				<button class="btn btn-sm btn-outline-success form-control" id="otpLogin">Use OTP</button>
			</div>

			<?php dequeMessages(); ?>

			<form method="post" id="passLoginForm" action="<?php echo URLROOT; ?>/users" class="col-sm-10 offset-sm-1 card-body small">

				<div class="field">
					<label for="email">Email</label>
					<input required type="email" name="email" class="form-control" id="email">
				</div>
				<div class="field">
					<label for="password">Password</label>
					<input required type="password" name="password" class="form-control" id="password">
				</div>
				<input type="submit" name="submit" value="Login" class="form-control btn btn-success"><br><br>
				<div class="col-md-8 offset-md-2 text-center">
					<a href="<?php echo URLROOT; ?>/users/register">Register</a> |
					<a href="<?php echo URLROOT; ?>/users/forgot">Forgot password</a>
				</div>
			</form>

			<form method="post" id="otpLoginForm" action="<?php echo URLROOT; ?>/users/createOTP" class="col-sm-10 offset-sm-1 card-body small">

				<div class="field">
					<label for="phone">Phone Number</label>
					<input required type="number" max="9999999999" min="1000000000" name="phone" class="form-control" id="phone">
				</div>
				<input type="submit" name="submit" value="Send OTP" class="form-control btn btn-success"><br><br>
				<div class="col-md-8 offset-md-2 text-center">
					<a href="<?php echo URLROOT; ?>/users/register">Register</a>
				</div>
			</form>

		</div>
	</div>

	<script type="application/javascript">
		$('#passLogin').click(function() {
			$('#passLogin').addClass('active');
			$('#otpLogin').removeClass('active');
			$('#otpLoginForm').fadeOut('fast', function() {
				$('#passLoginForm').fadeIn('fast');
			});
		});

		$('#otpLogin').click(function() {
			$('#otpLogin').addClass('active');
			$('#passLogin').removeClass('active');
			$('#passLoginForm').fadeOut('fast', function() {
				$('#otpLoginForm').fadeIn('fast');
			});
		});

		<?php if (isset($data['otp']) && $data['otp']) {
			echo '$(\'#otpLogin\').click();' . "\n";
		} else {
			echo '$(\'#passLogin\').click();' . "\n";
		} ?>
	</script>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>