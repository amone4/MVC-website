<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Forms {
	/**
	 * function returns an associative array with keys being all values in given array and each value being null
	 * @param  array $arr array containing the keys of the required associative array
	 * @return array
	 */
	private static function nullArray($arr) {
		$temp = [];
		foreach ($arr as $value) {
			$temp[$value]=null;
		}
		return $temp;
	}

	/**
	 * function checks if the GET variables with index in the array are set and not empty, and then escapes them by escapeData function
	 * @param  array &$get  used to store the escaped version of the data
	 * @param  array $arr contains indices of GET variables
	 * @param  string &$err [optional] stores the index which caused the error to generate
	 * @return boolean       returns true if data was present, false otherwise
	 */
	public static function get(&$get,$arr,&$err=null) {
		$get = self::nullArray($arr);
		foreach ($get as $key => &$value) {
			if (isset($_GET[$key])&&!empty($_GET[$key])) {
				$value=$_GET[$key];
			} else {
				$err=$key;
				return false;
			}
		}
		return true;
	}

	/**
	 * function checks if the POST variables with index in the array are set and not empty, and then escapes them by escapeData function
	 * @param  array &$post  used to store the escaped version of the data
	 * @param  array $arr contains indices of POST variables
	 * @param  string &$err [optional] stores the index which caused the error to generate
	 * @return boolean       returns true if data was present, false otherwise
	 */
	public static function post(&$post,$arr,&$err=null) {
		$post = self::nullArray($arr);
		foreach ($post as $key => &$value) {
			if (isset($_POST[$key])&&!empty($_POST[$key])) {
				$value=$_POST[$key];
			} else {
				$err=$key;
				return false;
			}
		}
		return true;
	}

	/**
	 * function to check if a post form was submitted
	 * @return boolean
	 */
	public static function isSubmitted() {
		return $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']);
	}
}