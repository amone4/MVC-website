<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Crypt {
	const ALGO_AES256 = 1;
	const ALGO_BLOWFISH = 2;

	private static function getCryptMethod($algo) {
		switch ($algo) {
			case self::ALGO_AES256: return 'AES-256-CBC';
			case self::ALGO_BLOWFISH:
			default: return 'BF-CBC';
		}
	}

	private static function limitedCharsEncode($input) {
		return preg_replace('/\//', '&', base64_encode($input));
	}

	private static function limitedCharsDecode($input) {
		return base64_decode(preg_replace('/\&/', '/', $input));
	}

	public static function encrypt($input, $limitChars = false, $algo = self::ALGO_BLOWFISH, $key = PASS) {
		$output = @openssl_encrypt($input, self::getCryptMethod($algo), $key);
		if ($limitChars) $output = self::limitedCharsEncode($output);
		return $output;
	}

	public static function decrypt($input, $limitChars = false, $algo = self::ALGO_BLOWFISH, $key = PASS) {
		$output = ($limitChars ? self::limitedCharsDecode($input) : $input);
		$output = @openssl_decrypt($output, self::getCryptMethod($algo), $key);
		return $output;
	}
}