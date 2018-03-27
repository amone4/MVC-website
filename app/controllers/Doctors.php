<?php

class Doctors extends Controller {
	private $doctor;

	public function __construct() {
		$this->doctor = $this->model('Doctor');
	}

	public function index($city, $category) {
		if (!empty($city) && ctype_digit($city)) {
			if (!empty($category) && ctype_digit($category)) {
				$this->view('doctors', $this->doctor->filter($city, $category));
			} else {
				redirect('cities');
			}
		} else {
			redirect('cities');
		}
	}

	public function search() {
		if (isset($_GET['search']) && !empty($_GET['search'])) {
			$name = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
			if (ctype_alpha($name)) {
				$this->view('doctors', $this->doctor->search(strtolower($name)));
			} else {
				redirect('');
			}
		} else {
			redirect('');
		}
	}
}