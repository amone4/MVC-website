<?php

define('_INDEX_EXEC', 1);

require_once 'bootstrap.php';

class CLI {
	public static function start() {
		self::help();
		while (true) {
			self::printCreatable();
			$choice = self::getInput()[0];
			switch($choice) {
				case '1':
					echo 'Name of component: ';
					$componentName = self::getInput();
					if (!self::componentExists($componentName)) {
						self::createComponent($componentName);
					} else echo 'Component already appears to exist' . "\n";
					break;
				case '2':
					echo 'Name of component: ';
					$componentName = self::getInput();
					if (self::componentExists($componentName)) {
						echo 'Name of model: ';
						$modelName = self::getInput();
						self::createModel($componentName, $modelName);
					} else echo 'Component doesn\'t exist' . "\n";
					break;
				case '3':
					echo 'Name of component: ';
					$componentName = self::getInput();
					if (self::componentExists($componentName)) {
						echo 'Name of method: ';
						$methodName = self::getInput();
						self::createMethod($componentName, $methodName);
					} else echo 'Component doesn\'t exist' . "\n";
					break;
				case '4':
					echo 'Name of component: ';
					$componentName = self::getInput();
					if (self::componentExists($componentName)) {
						echo 'Name of view: ';
						$viewName = self::getInput();
						self::createView($componentName, $viewName);
					} else echo 'Component doesn\'t exist' . "\n";
					break;
				default:
					echo 'Invalid choice' . "\n";
			}
		}
	}

	private static function help() {
		echo 'Welcome to CLI for MVC framework' . "\n";
		echo 'Type exit() for exiting the interface anytime' . "\n";
	}

	private static function printCreatable() {
		echo 'Create:' . "\n";
		echo '1. Component' . "\n";
		echo '2. Model' . "\n";
		echo '3. Method' . "\n";
		echo '4. View' . "\n";
		echo 'Enter your choice: ';
	}

	private static function getInput() {
		$input = readline();
		if (strpos($input, 'exit()') === 0)
			exit(1);
		else return $input;
	}

	private static function componentExists($componentName) {
		return (file_exists(APPROOT . '/components/' . $componentName) ||
			file_exists(APPROOT . '/components/' . $componentName . '/' . ucwords($componentName) . '.php'));
	}

	private static function createComponent($componentName) {
		mkdir(APPROOT . '/components/' . $componentName);
		$file = fopen(APPROOT . '/components/' . $componentName . '/' . ucwords($componentName) . '.php', 'w');
		fwrite($file, self::getComponentFileContents($componentName));
		fclose($file);
		mkdir(APPROOT . '/components/' . $componentName . '/methods');
		mkdir(APPROOT . '/components/' . $componentName . '/views');
		echo 'Component created' . "\n";
		self::createView($componentName, 'index');
	}

	private static function getComponentFileContents($name) {
		return '<?php' . "\n"
		. "\n" . 'defined(\'_INDEX_EXEC\') or die(\'Restricted access\');' . "\n"
		. "\n" . 'class ' . ucwords($name) . ' extends Controller {' . "\n"
		. "\n\t" . 'public function __construct() {}' . "\n"
		. "\n\t" . 'public function index() {' . "\n"
		. "\t\t" . 'Response::view(\'index\');'
		. "\n\t" . '}' . "\n"
		. '}';
	}

	private static function createModel($componentName, $modelName) {
		if (!file_exists(APPROOT . '/components/' . $componentName . '/' . ucwords($modelName) . '.php')) {
			$file = fopen(APPROOT . '/components/' . $componentName . '/' . ucwords($modelName) . '.php', 'w');
			fwrite($file, self::getModelFileContents($modelName));
			fclose($file);
			echo 'Model created' . "\n";
		} else echo 'Model already exists' . "\n";
	}

	private static function getModelFileContents($name) {
		return '<?php' . "\n"
		. "\n" . 'defined(\'_INDEX_EXEC\') or die(\'Restricted access\');' . "\n"
		. "\n" . 'class ' . ucwords($name) . ' extends Model {' . "\n"
		. "\n\t" . 'public function __construct() {'
		. "\n\t\t" . '$this->tableName = \'\';'
		. "\n\t\t" . 'parent::__construct();'
		. "\n\t" . '}'
		. "\n" . '}';
	}

	private static function createMethod($componentName, $methodName) {
		if (!file_exists(APPROOT . '/components/' . $componentName . '/methods/' . ucwords($methodName) . '.php')) {
			$file = fopen(APPROOT . '/components/' . $componentName . '/methods/' . ucwords($methodName) . '.php', 'w');
			fwrite($file, self::getMethodFileContents($methodName, $componentName));
			fclose($file);
			echo 'Method created' . "\n";
		} else echo 'Method already exists' . "\n";
	}

	private static function getMethodFileContents($name, $component) {
		return '<?php' . "\n"
		. "\n" . 'defined(\'_INDEX_EXEC\') or die(\'Restricted access\');' . "\n"
		. "\n" . 'class ' . ucwords($name) . ' extends ' . ucwords($component) . ' {' . "\n"
		. "\n\t" . 'public function __construct() {' . "\n"
		. "\t\t" . 'parent::__construct();'
		. "\n\t" . '}' . "\n"
		. '}';
	}

	private static function createView($componentName, $viewName) {
		if (!file_exists(APPROOT . '/components/' . $componentName . '/views/' . $viewName . '.php')) {
			$file = fopen(APPROOT . '/components/' . $componentName . '/views/' . $viewName . '.php', 'w');
			fwrite($file, self::getViewFileContents());
			fclose($file);
			echo 'View created' . "\n";
		} else echo 'View already exists' . "\n";
	}

	private static function getViewFileContents() {
		return '<?php' . "\n\n" . 'defined(\'_INDEX_EXEC\') or die(\'Restricted access\');' . "\n";
	}
}

CLI::start();