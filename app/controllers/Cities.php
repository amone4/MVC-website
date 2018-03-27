<?php

class Cities extends Controller {
	private $city;

	public function __construct() {
		$this->city = $this->model('City');
	}

	public function index() {
		$this->view('cities', $this->city->select());
	}
}