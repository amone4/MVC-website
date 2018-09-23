<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Password extends Users {

	public function __construct($request, $code = null) {
		parent::__construct();

		if ($request === 'forgot') $this->passwordForgot();
		else if ($request === 'reset' && $code !== null) $this->passwordReset($code);
		else if ($request === 'change') $this->passwordChange();
		else Output::fatal();
	}

	// function for forgot password
	private function passwordForgot() {
		// checking if the user is logged in
		if (Misc::validateLogin()) Output::redirect();

		// checking if the form was submitted
		if (Forms::isSubmitted()) {
			// checking if the form fields were filled
			if (Forms::post($p, ['email'])) {
				// sanitizing data
				$p['email'] = filter_var($p['email'], FILTER_SANITIZE_EMAIL);
				// checking if the email entered is valid
				if (Validations::email($p['email'])) {
					if (($row = $this->model->selectWhere(['email' => $p['email']])) && $this->model->rowCount() === 1) {

						// generating confirmation code for email
						$code = Crypt::encryptAlpha($row->id, 6);
						// mailing the confirmation code
						$message = '<p>Reset your password by clicking on the link below<br><a href="' . URLROOT . '/users/password/reset/' . $code . '">Reset password</a></p>';
						if (Misc::writeMessage($message, 'code.txt') || mail($p['email'], 'Reset your password', $message, 'From: noreply@example.com' . "\r\n")) {
							// updating the confirmation code
							if ($this->model->update($row->id, ['code' => $code, 'code_sent_on' => time()])) {

								Output::success('Link to reset the password was successfully sent.');
								Output::redirect('users');

							// error messages
							} else Output::error('Some error occurred. Try again');
						} else Output::error('Some error occurred while sending the mail. Try again');
					} else Output::error('This email is not registered');
				} else Output::error('Invalid email');
			} else Output::error('Please enter valid details in all form fields');
		}

		Output::view('password_forgot');
	}

	// function to reset password
	private function passwordReset($code) {
		// checking if the user is logged in
		if (Misc::validateLogin()) {
			Output::info('You can\'t reset your password, because you\'re logged in');
			App::dispatchMethod('logout');
		}

		// validating code and fetching ID
		$code = filter_var($code, FILTER_SANITIZE_STRING);
		$id = Crypt::decryptAlpha($code, 6);
		if (($row = $this->model->select($id)) && $this->model->rowCount() === 1) {
			if ($row->code === $code) {

				// checking if the form has been submitted
				if (Forms::isSubmitted()) {
					// checking if the form fields have been filled
					if (Forms::post($p, ['password', 'confirmPassword'])) {
						// validating passwords
						if (Validations::password($p['password']) && Validations::password($p['confirmPassword'])) {
							// checking if the passwords match
							if ($p['password'] === $p['confirmPassword']) {

								// confirmation code reset and password reset
								$p['password'] = password_hash($p['password'], PASSWORD_DEFAULT);
								if ($this->model->update($id, ['code' => '0', 'password' => $p['password']])) {

									Output::success('Your password has been successfully reset. Login to proceed');
									Output::redirect('users');

								// error messages
								} else Output::error('Some error occurred while resetting your password. Try again');
							} else Output::error('Passwords don\'t match');
						} else Output::error('Invalid password');
					} else Output::error('Please enter valid details in all form fields');
				}

				Output::view('password_reset', $code);

			} else Output::fatal();
		} else Output::fatal();
	}

	// function to change password
	private function passwordChange() {
		// checking if the user is logged in
		if (!Misc::validateLogin()) App::dispatchMethod('logout');

		// checking if the form has been submitted
		if (Forms::isSubmitted()) {
			// checking if the form fields have been filled
			if (Forms::post($p, ['oldPassword', 'newPassword', 'confirmPassword'])) {
				// validating passwords
				if (Validations::password($p['oldPassword']) && Validations::password($p['newPassword']) && Validations::password($p['confirmPassword'])) {
					// checking if the passwords match
					if ($p['newPassword'] === $p['confirmPassword']) {

						$user = $this->model->select(Crypt::decryptAlpha($_SESSION['user'], 6));
						// checking if the old password is correct
						if (password_verify($p['oldPassword'], $user->password)) {

							// changing the password
							$p['newPassword'] = password_hash($p['newPassword'], PASSWORD_DEFAULT);
							if ($this->model->update($user->id, ['password' => $p['newPassword']])) {

								Output::success('Your password has been successfully changed. Login again to continue');
								App::dispatchMethod('logout');

							// error messages
							} else Output::error('Some error occurred while changing your password');
						} else Output::error('Old password is incorrect');
					} else Output::error('Passwords don\'t match');
				} else Output::error('Invalid password');
			} else Output::error('Please enter valid details in all form fields');
		}

		Output::view('password_change');
	}
}