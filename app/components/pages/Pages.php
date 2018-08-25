<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Pages extends Controller {

	public function __construct() {
		$this->component = 'Pages';
	}

	public function index() {
		$this->renderView('home');
	}
}