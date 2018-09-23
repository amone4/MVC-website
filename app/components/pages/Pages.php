<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Pages extends ComponentController {

	public function index() {
		OutputController::view('home');
	}
}