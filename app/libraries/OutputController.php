<?php

defined('_INDEX_EXEC') or die('Restricted access');

class OutputController {
	private static $output;

	public static function init() {
		OutputController::$output = [
			'messages' => [],
			'redirect' => [
				'valid' => false,
				'link' => ''
			],
			'view' => '',
			'data' => []
		];
	}

	public static function view($view, $data = null) {
		OutputController::$output['view'] = $view;
		if ($data !== null)
			OutputController::data($data);
	}

	public static function data($data) {
		OutputController::$output['data'] = $data;
	}

	public static function success($message) {
		array_push(OutputController::$output['messages'], [
			'type' => 'success',
			'message' => $message
		]);
	}

	public static function info($message) {
		array_push(OutputController::$output['messages'], [
			'type' => 'info',
			'message' => $message
		]);
	}

	public static function error($message) {
		array_push(OutputController::$output['messages'], [
			'type' => 'error',
			'message' => $message
		]);
	}

	public static function fatal($message = 'Invalid URL') {
		if (App::get('isAPIRequest'))
			OutputController::renderJSON(['fatal' => $message]);
		else require_once APPROOT . '/views/message.php';
	}

	public static function redirect($link = '') {
		OutputController::$output['view'] = '';
		OutputController::$output['data'] = [];
		OutputController::$output['redirect'] = [
			'valid' => true,
			'link' => $link
		];
		OutputController::render();
	}

	public static function render() {
		if (App::get('isAPIRequest'))
			OutputController::renderJSON(OutputController::$output);
		else
			OutputController::renderHTML(OutputController::$output);
	}

	private static function renderHTML($output) {
		foreach ($output['messages'] as $message)
			Messages::{$message['type']}($message['message']);

		if ($output['redirect']['valid']) {
			header('Location: ' . URLROOT . '/' . $output['redirect']['link']);
			die();
		}

		if (!empty($output['view'])) {
			$output['view'] = explode('/', $output['view']);
			$componentViewsPath = APPROOT . '/components/';
			if (isset($output['view'][1])) {
				$componentViewsPath .= $output['view'][0] . '/views/';
				unset($output['view'][0]);
			} else $componentViewsPath .= App::get('component') . '/views/';
			$data = $output['data'];
			if (file_exists($componentViewsPath . $output['view'][0] .  '.php')) {
				if (file_exists($componentViewsPath . 'header.php'))
					require_once $componentViewsPath . 'header.php';
				else require_once APPROOT . '/views/header.php';
				if (file_exists($componentViewsPath . 'navbar.php'))
					require_once $componentViewsPath . 'navbar.php';
				echo '<div id="container">';
				require_once $componentViewsPath . $output['view'][0] . '.php';
				echo '</div>';
				if (file_exists($componentViewsPath . 'footer.php'))
					require_once $componentViewsPath . 'footer.php';
				else require_once APPROOT . '/views/footer.php';
			} else OutputController::fatal('View does not exists');
		}
		die();
	}

	private static function renderJSON($output) {
		header('Content-type: application/json');
		if (!empty($output['view'])) {
			$view = explode('/', $output['view']);
			if (isset($view[1]))
				$output['view'] = $view[0] . '/' . $view[1];
			else
				$output['view'] = App::get('component') . '/' . $view[0];
		}
		echo json_encode($output);
		die();
	}
}
OutputController::init();