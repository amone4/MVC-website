<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Send extends Users {

	public function __construct($request) {
		parent::__construct();

		if ($request === 'otp') $this->sendOTP();
		elseif ($request === 'code') $this->sendCode();
		else OutputController::fatal();
	}

	// function to send OTP for confirming the phone number
	private function sendOTP() {
		// checking if the user is logged in
		if (!Misc::validateLogin()) App::dispatchMethod('logout');

		// checking if phone verification is needed
		$user = $this->model->select(Crypt::decryptAlpha($_SESSION['user'], 6));
		if ($user->confirm_phone == 1) {
			OutputController::info('Your phone number has already been verified');
			OutputController::redirect('users');
		}

		// generating otp
		$otp = rand(100, 999) * substr(time(), -3);
		$otp = substr($otp, 0, 2) . substr($otp, -2);
		$otp = substr($otp + $user->id, 0, 4);

		// storing OTP
		if ($this->model->update($user->id, ['otp' => $otp, 'otp_sent_on' => time()])) {
			// sending OTP
			if (Misc::writeMessage($user->phone . ': ' . $otp, 'otp.txt') || Misc::sendOTP(['phone' => $user->phone, 'otp' => $otp])) {

				OutputController::success('OTP was successfully sent to your registered phone number');
				OutputController::redirect('users/confirm/phone');

			// error messages
			} else OutputController::fatal('Some error occurred while sending the OTP. Try again');
		} else OutputController::fatal('Some error occurred while storing your OTP. Try again');
	}

	// function to send confirmation code for confirming the email
	private function sendCode() {
		// checking if the user is logged in
		if (!Misc::validateLogin()) App::dispatchMethod('logout');

		$user = $this->model->select(Crypt::decryptAlpha($_SESSION['user'], 6));

		// checking if phone verification is needed
		if ($user->confirm_email == 1) {
			OutputController::error('Your email has already been verified');
			OutputController::redirect('users');
		}

		// generating code
		$code = Crypt::encryptAlpha($user->id, 6);
		// mailing the confirmation code
		$message = '<p>Confirm your email by clicking on the link below<br><a href="' . URLROOT . '/users/confirm/email/' . $code . '">Confirm email</a></p>';

		// storing code
		if ($this->model->update($user->id, ['code' => $code, 'code_sent_on' => time()])) {
			// sending code
			if (Misc::writeMessage($message, 'code.txt') || mail($user->email, 'Confirm your email', $message, 'From: noreply@example.com' . "\r\n")) {

				OutputController::success('Confirmation code was successfully sent to your registered email');
				OutputController::redirect('users');

			// error messages
			} else OutputController::fatal('Some error occurred while sending the confirmation code. Try again');
		} else OutputController::fatal('Some error occurred while storing your confirmation code. Try again');
	}
}