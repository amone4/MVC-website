<?php

class Payment {
	private $database;

	public function __construct() {
		$this->database = new Database();
	}

	public function isLogisticsPaymentSuccessful($data) {
		$this->database->query('SELECT sno FROM transactions WHERE
			sno = :sno AND
			customer = :customer AND 
			product = :product AND 
			amount = :amount AND 
			password = :password');

		$this->database->bind('sno', $data['sno'], PDO::PARAM_INT);
		$this->database->bind('customer', $data['customer'], PDO::PARAM_INT);
		$this->database->bind('product', $data['product'], PDO::PARAM_INT);
		$this->database->bind('amount', $data['amount'], PDO::PARAM_INT);
		$this->database->bind('password', $data['password'], PDO::PARAM_STR);

		$this->database->execute();
		if ($this->database->rowCount() === 1) {

			$this->database->query('UPDATE transactions SET paid = 1 WHERE sno = :sno');
			$this->database->bind('sno', $data['transaction'], PDO::PARAM_INT);
			return $this->database->execute();

		} else {
			return false;
		}
	}
}