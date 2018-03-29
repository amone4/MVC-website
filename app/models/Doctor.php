<?php

class Doctor {
	private $database;

	public function __construct() {
		$this->database = new Database();
	}

	public function filter($city, $category) {
		$this->database->query('SELECT doctors.sno, lower(doctors.name) AS name, doctors.photo, lower(doctors.description) AS description, lower(cities.name) AS city, lower(categories.name) AS category
										FROM doctors, cities, categories
										WHERE doctors.city = :city
										AND doctors.category = :category
										ORDER BY doctors.name');
		$this->database->bind('city', $city, PDO::PARAM_INT);
		$this->database->bind('category', $category, PDO::PARAM_INT);
		$set = $this->database->resultSet();
		if ($this->database->rowCount() === 0)
			enqueueErrorMessage('No results found');
		return $set;
	}

	public function search($name) {
		$this->database->query('SELECT doctors.sno, lower(doctors.name) AS name, doctors.photo, lower(doctors.description) AS description, lower(cities.name) AS city, lower(categories.name) AS category
										FROM doctors, cities, categories
										WHERE cities.name LIKE :name
										AND doctors.city = cities.sno
										AND doctors.category = categories.sno
										ORDER BY doctors.name');
		$this->database->bind('name', '%' . $name . '%', PDO::PARAM_STR);
		$set = $this->database->resultSet();
		if ($this->database->rowCount() === 0)
			enqueueErrorMessage('No results found');
		return $set;
	}
}