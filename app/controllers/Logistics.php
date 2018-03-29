<?php

/**
 * Class Logistics
 *
 * Verifies payments done from logistics
 *
 * The delivery boy needs to deliver the good, and then accept the payment
 * After successful transaction with the customer, he needs to enter the following
 *      transaction ID
 *      customer ID
 *      product ID
 *      amount of order
 *
 * Further processing will be done by the server, if all these match with the database
 * Matching is done with the record, with the same transaction ID
 * The record of the transaction may or may not be deleted after successful payment
 *
 * The payment is verified only if the delivery boy fills in the correct password given to the company
 * The login session will persist only for a single transaction
 */
class Logistics extends Controller {

	public function index() {

		// checking if the form is submitted
		if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

			// checking if the form is filled
			if (postVars($p, ['transaction', 'customer', 'product', 'amount', 'password'])) {

				// sanitizing inputs
				$p = [
					'transaction' => filter_var($p['transaction'], FILTER_SANITIZE_NUMBER_INT),
					'customer' => filter_var($p['customer'], FILTER_SANITIZE_NUMBER_INT),
					'product' => filter_var($p['product'], FILTER_SANITIZE_NUMBER_INT),
					'amount' => filter_var($p['amount'], FILTER_SANITIZE_NUMBER_INT),
					'password' => filter_var($p['password'], FILTER_SANITIZE_STRING)
				];

				// confirming if inputs are valid
				if (ctype_digit($p['transaction']) && ctype_digit($p['customer']) && ctype_digit($p['product']) && ctype_digit($p['amount'])) {

					// recording the payment
					$payment = $this->model('Logistic');
					if ($payment->isLogisticsPaymentSuccessful($p)) {

						// displaying a success message
						enqueueSuccessMessage('Payment successful<br>'
							. 'Transaction ID' . $p['transaction'] . '<br>'
							. 'Customer ID' . $p['customer'] . '<br>'
							. 'Product ID' . $p['product'] . '<br>'
							. 'Amount of Order' . $p['amount']);

					// error messages
					} else {
						enqueueErrorMessage('Payment failed');
					}
				} else {
					enqueueErrorMessage('Please fill in valid details');
				}
			} else {
				enqueueErrorMessage('Please fill in valid details');
			}
		}

		// the view for recording payments
		$this->view('logistics', []);
	}
}