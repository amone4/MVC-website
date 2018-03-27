<?php

class User {
	private $database;

	public function __construct() {
		$this->database = new Database();
	}

	public function validateCredentials($data) {
		$this->database->query('SELECT sno, password FROM users WHERE email = :email');
		$this->database->bind('email', $data['email'], PDO::PARAM_STR);
		$single = $this->database->single();
		if ($this->database->rowCount() === 1) {
			if (password_verify($data['password'], $single->password)) {
				return $single->sno;
			}
		}
		return false;
	}

	public function emailExists($email) {
		$this->database->query('SELECT sno FROM users WHERE email = :email');
		$this->database->bind('email', $email, PDO::PARAM_STR);
		$this->database->execute();
		if ($this->database->rowCount() === 1) {
			return $this->database->single()->sno;
		} else {
			return false;
		}
	}

	public function phoneExists($phone) {
		$this->database->query('SELECT sno FROM users WHERE phone = :phone');
		$this->database->bind('phone', $phone, PDO::PARAM_STR);
		$this->database->execute();
		if ($this->database->rowCount() === 1) {
			return $this->database->single()->sno;
		} else {
			return false;
		}
	}

	public function register($data) {
		$this->database->query('INSERT INTO users (name, email, password, phone) VALUES (:name, :email, :password, :phone)');
		$this->database->bind('name', $data['name'], PDO::PARAM_STR);
		$this->database->bind('email', $data['email'], PDO::PARAM_STR);
		$this->database->bind('phone', $data['phone'], PDO::PARAM_STR);
		$this->database->bind('password', $data['password'], PDO::PARAM_STR);
		if ($this->database->execute()) {
			$this->database->query('SELECT last_insert_id() AS id FROM users');
			return $this->database->single()->id;
		} else {
			return 0;
		}
	}

	public function serialNumberExists($sno) {
		$this->database->query('SELECT sno FROM users WHERE sno = :sno');
		$this->database->bind('sno', $sno, PDO::PARAM_INT);
		$this->database->execute();
		if ($this->database->rowCount() === 1) {
			return $this->database->single()->sno;
		} else {
			return false;
		}
	}

	public function confirmEmail($sno) {
		$this->database->query('UPDATE users SET confirm_email = 1 WHERE sno = :sno');
		$this->database->bind('sno', $sno, PDO::PARAM_INT);
		return $this->database->execute();
	}

	public function confirmPassword($data) {
		$this->database->query('SELECT password FROM users WHERE sno = :sno');
		$this->database->bind('sno', $data['sno'], PDO::PARAM_INT);
		$password = $this->database->single()->password;
		return password_verify($data['password'], $password);
	}

	public function changePassword($data) {
		$this->database->query('UPDATE users SET password = :password WHERE sno = :sno');
		$this->database->bind('sno', $data['sno'], PDO::PARAM_INT);
		$this->database->bind('password', $data['password'], PDO::PARAM_STR);
		return $this->database->execute();
	}

	public function storeResetCode($sno, $code) {
		$this->database->query('UPDATE users SET reset_password = :code WHERE sno = :sno');
		$this->database->bind('sno', $sno, PDO::PARAM_INT);
		$this->database->bind('code', $code, PDO::PARAM_STR);
		return $this->database->execute();
	}

	public function verifyResetCode($sno, $code) {
		$this->database->query('SELECT sno FROM users WHERE sno = :sno AND reset_password = :code');
		$this->database->bind('sno', $sno, PDO::PARAM_INT);
		$this->database->bind('code', $code, PDO::PARAM_STR);
		$this->database->execute();
		return $this->database->rowCount() === 1;
	}

	public function resetPassword($data) {
		$this->database->query('UPDATE users SET password = :password, reset_password = 0 WHERE sno = :sno');
		$this->database->bind('sno', $data['sno'], PDO::PARAM_INT);
		$this->database->bind('password', $data['password'], PDO::PARAM_STR);
		return $this->database->execute();
	}

	public function storeOTP($data) {
		$this->database->query('UPDATE users SET otp = :otp WHERE sno = :sno');
		$this->database->bind('otp', $data['otp'], PDO::PARAM_STR);
		$this->database->bind('sno', $data['sno'], PDO::PARAM_INT);
		return $this->database->execute();
	}

	public function getOTP($sno) {
		$this->database->query('SELECT otp FROM users WHERE sno = :sno');
		$this->database->bind('sno', $sno, PDO::PARAM_INT);
		return $this->database->single()->otp;
	}

	public function resetOTP($sno) {
		$this->database->query('UPDATE users SET otp = 0 WHERE sno = :sno');
		$this->database->bind('sno', $sno, PDO::PARAM_INT);
		return $this->database->execute();
	}
}