<?php

class Category {
	private $database;

	public function __construct() {
		$this->database = new Database();
	}

	public function select() {
		$this->database->query('SELECT lower(name) as name, sno, photo FROM categories ORDER BY name');
		return $this->database->resultSet();
	}

	public function add($data) {
		$this->database->query('INSERT INTO categories (name, photo) VALUES (:name, :photo)');
		$this->database->bind('name', $data['name'], PDO::PARAM_STR);
		$this->database->bind('photo', $data['photo'], PDO::PARAM_STR);
		$this->database->execute();
	}
}