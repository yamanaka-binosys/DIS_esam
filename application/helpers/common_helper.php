<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * bytelen
 * http://zombiebook.seesaa.net/article/33192046.html
 *
 * @param mixed $data
 * @retrn int $length
 */
function bytelen($data)
{
	return strlen(bin2hex($data)) / 2;
}

/**
 * startsWith
 * http://blog.anoncom.net/2009/02/20/124.html
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function startsWith($haystack, $needle){
	return strpos($haystack, $needle, 0) === 0;
}

/**
 * endsWith
 * http://blog.anoncom.net/2009/02/20/124.html
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function endsWith($haystack, $needle){
	$length = (strlen($haystack) - strlen($needle));
	if( $length <0) return false;
	return strpos($haystack, $needle, $length) !== false;
}

/**
 * matchesIn
 * http://blog.anoncom.net/2009/02/20/124.html
 *
 * @param string $haystack
 * @param string $needle
 * @return boolean
 */
function matchesIn($haystack, $needle){
	return strpos($haystack, $needle) !== false;
}

/**
 * ランダム文字列の生成
 */
function create_random_string($len=9) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	mt_srand();
	$str = "";
	for ($i = 0; $i < $len; $i++) {
		$str .= $chars[mt_rand(0, strlen($chars) - 1)];
	}
	return $str;
}

function debug($obj) {
	ob_start();
	var_dump($obj);
	$ret = ob_get_contents();
	ob_end_clean();
	log_message('debug', $ret);
}