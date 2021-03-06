<?php

defined('_INDEX_EXEC') or die('Restricted access');

class User extends Model {

	public function __construct() {
		parent::__construct();
		$this->tableName = 'users';
	}

	public function getNewID() {
		$this->database->query('SELECT AUTO_INCREMENT AS id FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = :db AND TABLE_NAME = :table');
		$this->database->bind('db', DB_NAME, PDO::PARAM_STR);
		$this->database->bind('table', $this->tableName, PDO::PARAM_STR);
		return $this->database->single()->id;
	}
}