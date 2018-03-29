<?php require_once APPROOT . '/views/inc/header.php'; ?>

	<link rel="stylesheet" href="<?php echo URLROOT; ?>/css/forms.css">

	<div class="row">
		<div class="col-10 offset-1 col-md-6 offset-md-3 card">
			<h2 class="text-center card-title">Logistics Payment</h2>

			<form action="<?php echo URLROOT; ?>/logistics" method="post" class="col-sm-10 offset-sm-1 card-body small">

				<?php dequeMessages(); ?>

				<div class="field">
					<label for="transaction">Transaction ID</label>
					<input required type="text" class="form-control" name="transaction" id="transaction"><br>
				</div>
				<div class="field">
					<label for="customer">Customer ID</label>
					<input required type="text" class="form-control" name="customer" id="customer"><br>
				</div>
				<div class="field">
					<label for="product">Product ID</label>
					<input required type="text" class="form-control" name="product" id="product"><br>
				</div>
				<div class="field">
					<label for="amount">Amount of Order</label>
					<input required type="text" class="form-control" name="amount" id="amount"><br>
				</div>
				<div class="field">
					<label for="password">Password</label>
					<input required type="password" class="form-control" name="password" id="password"><br>
				</div>

				<input type="submit" name="submit" id="submit" value="Confirm Payment" class="form-control btn btn-success"><br><br>

			</form>
		</div>
	</div>

<?php require_once APPROOT . '/views/inc/footer.php'; ?>