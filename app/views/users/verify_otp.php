<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/forms.css">

	<div class="row">
		<div class="col-10 offset-1 col-md-6 offset-md-3 card">
			<h2 class="text-center card-title">Login with OTP</h2>
			<form action="<?php echo URLROOT; ?>/users/verifyOTP/<?php echo $data['phone']; ?>" method="post" class="col-sm-10 offset-sm-1 card-body small">

				<?php dequeMessages(); ?>

				<div class="field">
					<label for="phone">Phone</label>
					<input disabled value="<?php echo $data['phone']; ?>" type="number" class="form-control" name="phone" id="phone">
				</div>
				<div class="field">
					<label for="otp">Enter OTP</label>
					<input type="text" class="form-control" name="otp" id="otp">
				</div>
				<input type="submit" name="submit" id="submit" value="Submit" class="form-control btn btn-danger"><br><br>
			</form>
		</div>
	</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>