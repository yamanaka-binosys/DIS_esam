<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Top_table_manager {
	
	/**
	 * Infomationテーブル作成
	 *
	 * @access public
	 * @param  string 画面遷移先
	 * @return string HTMLデータ
	 */
	public function tab_list_set($info_data)
	{
		$CI =& get_instance();
		$base_url = $CI->config->item('base_url');
		$string_data = NULL; // 活動区分HTML
		
		// HTML作成
		$string_data .= "<table width=\"561px\";>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td style=\"padding-left:0px; border-collapse:collapse; border:1px solid #000000;\" colspan=\"1\">\n";
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<th style=\" border-bottom:1px #000000 solid; height:20px; width:550px; background-color:#FFFF99; padding-top:0;text-align:center;\">Informaion</th>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "<div style=\"margin:0; height:80px; width:550px; overflow:auto; background-color:#FFFFFF;\">\n";
		$string_data .= "<table>\n";
		foreach ($info_data as $key => $value) {
			$string_data .= "<tr>\n";
			$string_data .= "<td style=\" height:12px; width:540px;\" align=\"left\">";
			// 添付ファイル有無判定
			if(isset($value['link'])){
				if (is_null($value['link'])) {
					$string_data .= $value['jyohoniyo'];
				}else{
					$string_data .= "<a href=\"" . $value['link'] . "\">";
					$string_data .= $value['jyohoniyo'];
					$string_data .= "</a>";
				}
			}else{
				if(isset($value['jyohoniyo'])){
					$string_data .= $value['jyohoniyo'];
				}
			}
			$string_data .= "<td>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</div>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		
		return $string_data;
	}
}

// END Top_table_manager class

/* End of file Top_table_manager.php */
/* Location: ./application/libraries/Top_table_manager.php */
