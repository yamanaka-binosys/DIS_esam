<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Tab_set {
	
	/**
	 * タブの設定
	 *
	 * @access public
	 * @param  string 画面遷移先
	 * @return string HTMLデータ
	 */
	public function tab_list_set($current)
	{
		// 初期化
		$CI =& get_instance();
		$tab_data = "";
		$base_url = $CI->config->item('base_url');
		$tab_list = $CI->config->item('tab_list'); // タブデータ
		$tab_data .= "<ul id=\"tab\">\n";
		foreach($tab_list as $key => $value)
		{
			$tab_data .= "<input type=\"button\"\n";
			$tab_data .= " id=\"" . $value['id'] . "\"\n";
			$tab_data .= " name=\"" . $value['name'] . "\"\n";
			$tab_data .= " onclick=\"location.href='" . $base_url . "index.php/" . $current . $value['url'] . "'\";\n"; // リンク先
			// CSS設定
			$tab_data .= " style=\"".$value["border"].";\n";    // 枠線
			$tab_data .= " width:".$value["width"].";\n";       // 幅
			$tab_data .= " height:".$value["height"].";\n";     // 高さ
			$tab_data .= " background:url(" . $base_url . $value['img'] . ");\">\n"; // 画像ＵＲＬ
			$tab_data .= "\n";
		}
		$tab_data .= "</ul>\n";

		return $tab_data;
	}	
}

// END Table_set class

/* End of file Table_set.php */
/* Location: ./application/libraries/Table_set.php */
