<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class File_uploade_manager {
	
	/**
	 * ファイルアップロード処理
	 * @access	public
	 * @param	string $tmp_data：アップロード情報
	 * @return	nothing
	 */
	function file_upload($tmp_data = NULL) 
	{
		log_message('debug',"========== File_uploade_manager file_upload start ==========");
		$CI =& get_instance();
		$up_dir = NULL;
		$res = FALSE;
		$fname = NULL;
		$base_url = $CI->config->item('base_url');
		if(!is_null($tmp_data)){
			$up_dir = FILE_DIR.$tmp_data['dir_name']."/";
			log_message('debug',"up_url = ".$up_dir);
			
			if(file_exists($up_dir)){
				mkdir($up_dir.$tmp_data['jyohonum']."/");
				$fname = mb_convert_encoding($tmp_data['file_name'], "SJIS");
				$fname2 = mb_convert_encoding($tmp_data['file_name'], "UTF8");
				log_message('debug',"file_name = ".$fname);
				move_uploaded_file($tmp_data['tmp_name'], $up_dir.$tmp_data['jyohonum']."/".$fname);
				$res =  $fname2;
				
			}else{
				log_message('debug',"アップロード先のディレクトリが見つかりません。{$up_dir}");
			}
			
		}
		log_message('debug',"========== File_uploade_manager file_upload end ==========");
		return $res;
	}
	
	function memo_file_upload($tmp_data = NULL) 
	{
		log_message('debug',"========== File_uploade_manager file_upload start ==========");
		$CI =& get_instance();
		$up_dir = NULL;
		$res = FALSE;
		$fname = NULL;
		$base_url = $CI->config->item('base_url');

		if(!is_null($tmp_data)){
			$up_dir = FILE_DIR.$tmp_data['dir_name']."/";
			log_message('debug',"up_url = ".$up_dir);
			if(file_exists($up_dir)){
				mkdir($up_dir.$tmp_data['jyohonum'].$tmp_data['edbn']."/");
				$fname = mb_convert_encoding($tmp_data['file_name'], "SJIS");
				$fname2 = mb_convert_encoding($tmp_data['file_name'], "UTF8");
			log_message('debug',"file_name = ".$fname);
				if(isset($tmp_data['edbn']) && $tmp_data['edbn'] != ""){
					move_uploaded_file($tmp_data['tmp_name'], $up_dir.$tmp_data['jyohonum'].$tmp_data['edbn']."/".$fname);
					$res = $base_url."files/memo/".$tmp_data['jyohonum'].$tmp_data['edbn']."/".$fname2;
				}else{
					move_uploaded_file($tmp_data['tmp_name'], $up_dir.$tmp_data['jyohonum']."/".$fname);
					$res =  $base_url."files/memo/".$tmp_data['jyohonum']."/".$fname2;
				}
			}else{
				log_message('debug',"error");
			}
			
		}
		log_message('debug',"========== File_uploade_manager file_upload end ==========");
		return $res;
	}
	
		/**
	 * ファイルアップロード処理
	 * @access	public
	 * @param	string $tmp_data：アップロード情報
	 * @return	nothing
	 */
	function result_file_upload($tmp_data) 
	{
		log_message('debug',"========== File_uploade_manager file_upload start ==========");
		$CI =& get_instance();
		$up_dir = NULL;
		$res = FALSE;
		$fname = NULL;
		$base_url = $CI->config->item('base_url');
		if(!is_null($tmp_data)){
			$up_dir = FILE_DIR.$tmp_data['dir_name']."/";
			log_message('debug',"up_url = ".$up_dir);
			if(file_exists($up_dir)){
				if(!file_exists($up_dir.$tmp_data['jyohonum']."/")){
					mkdir($up_dir.$tmp_data['jyohonum']."/");
				}
				mkdir($up_dir.$tmp_data['jyohonum']."/".$tmp_data['uploadtime']."/");
				$fname = mb_convert_encoding($tmp_data['file_name'], "SJIS");
				$fname2 = mb_convert_encoding($tmp_data['file_name'], "UTF8");
				log_message('debug',"file_name = ".$fname);
				move_uploaded_file($tmp_data['tmp_name'], $up_dir.$tmp_data['jyohonum']."/".$tmp_data['uploadtime']."/".$fname);
				$res =  $fname2;
				
			}else{
				log_message('debug',"アップロード先のディレクトリが見つかりません。{$up_dir}");
			}
			
		}
		log_message('debug',"========== File_uploade_manager file_upload end ==========");
		return $res;
	}
}



// END File_uploade_manager class

/* End of file file_uploade_manager.php */
/* Location: ./application/libraries/file_uploade_manager.php */
