<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Confirm extends Users {

	public function __construct($request, $code = null) {
		parent::__construct();

		if ($request === 'email' && $code !== null) $this->confirmEmail($code);
		else if ($request === 'phone') $this->confirmPhone();
		else Response::fatal();
	}

	// function to confirm email
	private function confirmEmail($code) {
		// checking if the user is logged in
		if (Misc::validateLogin()) {
			Response::info('You can\'t confirm your email, until you\'re logged in');
			App::dispatchMethod('logout');
		}

		// validating code and fetching ID
		$code = filter_var($code, FILTER_SANITIZE_STRING);
		$id = Crypt::decryptAlpha($code, 6);
		$row = $this->model->select($id);

		if ($this->model->rowCount() === 1) {
			if (($row->code === $code) && (time() - $row->code_sent_on < 864000)) {
				// confirming email
				if ($this->model->update($id, ['confirm_email' => 1, 'code' => '0'])) {

					Response::success('Your email has been confirmed successfully. Login to proceed');
					Response::redirect('users');

				// error messages
				} else Response::fatal('Some error occurred while confirming your email. Try again');
			} else Response::fatal('Your code has expired');
		} else Response::fatal();
	}

	// function to confirm phone number
	private function confirmPhone() {
		// checking if the user is logged in
		if (!Misc::validateLogin()) App::dispatchMethod('logout');

		// checking if phone verification is needed
		$user = $this->model->select(Crypt::decryptAlpha($_SESSION['user'], 6));
		if ($user->confirm_phone == 1) {
			Response::info('Your phone number has already been verified');
			Response::redirect();
		}

		// checking if the form has been submitted
		if (Forms::isSubmitted()) {
			// checking if the form fields have been filled
			if (Forms::post($p, ['otp'])) {
				// sanitizing data
				$p['otp'] = filter_var($p['otp'], FILTER_SANITIZE_NUMBER_INT);

				// checking if the OTP is valid
				if (ctype_digit($p['otp'])) {
					if (($p['otp'] == $user->otp) && (time() - $user->otp_sent_on < 864000)) {
						// confirming phone number
						if ($this->model->update($user->id, ['confirm_phone' => 1, 'otp' => '0'])) {

							Response::success('Your phone number was successfully confirmed');
							Response::redirect('users');

						// error messages
						} else Response::error('Some error occurred while confirming your phone number. Try again');
					} else Response::error('Invalid OTP');
				} else Response::error('Invalid OTP');
			} else Response::error('Please enter valid details in all form fields');
		}

		Response::view('confirm_phone');
	}
}