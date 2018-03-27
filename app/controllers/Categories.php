<?php

class Categories extends Controller {
	private $category;

	public function __construct() {
		$this->category = $this->model('Category');
	}

	public function index($param = []) {
		if (isset($param[0]) && !empty($param[0]) && ctype_digit($param[0])) {
			$this->view('categories', ['city' => $param[0], 'categories' => $this->category->select()]);
		} else {
			redirect('cities');
		}
	}
}