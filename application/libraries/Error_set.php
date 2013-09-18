<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Error_set {
	
	function get_message($error = NULL,$item = NULL)
	{
		// 初期化
		$CI =& get_instance();
		$e_statement = $CI->config->item('e_statement'); // エラー文言取得
		$error_message = "";
		$error_message = $e_statement[$error];
		
		// 引数有無チェック
/*
 		if(is_null($item))
		{
			$error_message = $e_statement[$error];
		}
*/		
		return $error_message;
	}
}

// END Error_set class

/* End of file Error_set.php */
/* Location: ./application/libraries/Error_set.php */
