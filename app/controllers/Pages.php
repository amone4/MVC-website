<?php
class Pages extends Controller {
	public function index(){
		$this->view('index', []);
	}

	public function about() {
		$this->view('about', []);
	}

	public function contact() {
		$this->view('contact', []);
	}
}