<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Tab_manager {
	
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
		// タブ用HTML-STRING作成
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
	
	/**
	 * タブ設定（登録・変更）
	 * 
	 * @access public
	 * @param  string $page_name 画面名
	 * @param  string $item リンクURLに付属する値がある場合は設定
	 * @return string $tab_data_string HTMLデータ
	 */
	public function set_tab_au($page_name = NULL,$item = NULL)
	{
		// 引数チェック
		if(is_null($page_name))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$base_url  = $CI->config->item('base_url');
		$tab_style = $CI->config->item('c_tab_style'); // タブスタイルデータ
		$tab_list  = $CI->config->item('c_tab_list_au'); // タブデータ
		$tab_data_string = "";
		// タブ用HTML-STRING作成
		$tab_data_string .= "<ul style=\"";
		$tab_data_string .= " list-style:" . $tab_style['ul_list_style'] . ";";
		$tab_data_string .= " border-bottom:" . $tab_style['ul_border_bottom'] . ";";
		$tab_data_string .= " margin:" . $tab_style['ul_margin'] . ";";
		$tab_data_string .= " padding:" . $tab_style['ul_padding'] . ";";
		$tab_data_string .= " height:" . $tab_style['ul_height'] . ";";
		$tab_data_string .= "\">\n";
		// tab 作成
		foreach($tab_list as $key => $value)
		{
			// リンク先設定
			$tab_data_string .= "<li style=\"float:" . $tab_style['li_float'] . "\">";
			$tab_data_string .= "<a";
			$tab_data_string .= " href=\"" . $base_url . "index.php/" . $page_name . $value['link'];
			if( ! is_null($item))
			{
				$tab_data_string .= "/" . $item;
			}
			$tab_data_string .= "\">";
			// 画像設定
			$tab_data_string .= "<img";
			$tab_data_string .= " src=\"" . $base_url . $value['img'] . "\"";
			$tab_data_string .= " alt=\"\"";
			$tab_data_string .= ">";
			$tab_data_string .= "</a>";
			$tab_data_string .= "</li>\n";
		}
		$tab_data_string .= "</ul>\n";
		
		return $tab_data_string;
	}
	
	/**
	 * タブ設定（登録・削除）
	 * 
	 * @access public
	 * @param  string $page_name 画面名
	 * @param  string $item リンクURLに付属する値がある場合は設定
	 * @return string $tab_data_string HTMLデータ
	 */
	public function set_tab_ad($page_name = NULL,$item = NULL)
	{
		// 引数チェック
		if(is_null($page_name))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$base_url  = $CI->config->item('base_url');
		$tab_style = $CI->config->item('c_tab_style'); // タブスタイルデータ
		$tab_list  = $CI->config->item('c_tab_list_ad'); // タブデータ
		$tab_data_string = "";
		// タブ用HTML-STRING作成
		$tab_data_string .= "<ul style=\"";
		$tab_data_string .= " list-style:" . $tab_style['ul_list_style'] . ";";
		$tab_data_string .= " border-bottom:" . $tab_style['ul_border_bottom'] . ";";
		$tab_data_string .= " margin:" . $tab_style['ul_margin'] . ";";
		$tab_data_string .= " padding:" . $tab_style['ul_padding'] . ";";
		$tab_data_string .= " height:" . $tab_style['ul_height'] . ";";
		$tab_data_string .= "\">\n";
		// tab 作成
		foreach($tab_list as $key => $value)
		{
			// リンク先設定
			$tab_data_string .= "<li style=\"float:" . $tab_style['li_float'] . "\">";
			$tab_data_string .= "<a";
			$tab_data_string .= " href=\"" . $base_url . "index.php/" . $page_name . $value['link'];
			if( ! is_null($item))
			{
				$tab_data_string .= "/" . $item;
			}
			$tab_data_string .= "\">";
			// 画像設定
			$tab_data_string .= "<img";
			$tab_data_string .= " src=\"" . $base_url . $value['img'] . "\"";
			$tab_data_string .= " alt=\"\"";
			$tab_data_string .= ">";
			$tab_data_string .= "</a>";
			$tab_data_string .= "</li>\n";
		}
		$tab_data_string .= "</ul>\n";
		
		return $tab_data_string;
	}
	
	

	///// aiba add 20120121 ///////////////////////////////////////////////////////////////////
	/**
	 * タブ設定（登録・変更）
	 *
	 * @access public
	 * @param  string $page_name 画面名
	 * @param  string $item リンクURLに付属する値がある場合は設定
	 * @return string $tab_data_string HTMLデータ
	 */
	public function set_tab_all($page_name = NULL)
	{
		// 引数チェック
		if(is_null($page_name))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$base_url  = $CI->config->item('base_url');
		$tab_style = $CI->config->item('c_tab_style'); // タブスタイルデータ
		$tab_list  = $CI->config->item('c_tab_list_all'); // タブデータ
		$tab_data_string = "";
		// タブ用HTML-STRING作成
		$tab_data_string .= "<ul style=\"";
		$tab_data_string .= " list-style:" . $tab_style['ul_list_style'] . ";";
		$tab_data_string .= " border-bottom:" . $tab_style['ul_border_bottom'] . ";";
		$tab_data_string .= " margin:" . $tab_style['ul_margin'] . ";";
		$tab_data_string .= " padding:" . $tab_style['ul_padding'] . ";";
		$tab_data_string .= " height:" . $tab_style['ul_height'] . ";";
		$tab_data_string .= "\">\n";
		// tab 作成
		foreach($tab_list as $key => $value)
		{
			// リンク先設定
			$tab_data_string .= "<li style=\"float:" . $tab_style['li_float'] . "\">";
			$tab_data_string .= "<a";
			$tab_data_string .= " href=\"" . $base_url . "index.php/" . $page_name . $value['link'];
			$tab_data_string .= "\">";
			// 画像設定
			$tab_data_string .= "<img";
			$tab_data_string .= " src=\"" . $base_url . $value['img'] . "\"";
			$tab_data_string .= " alt=\"\"";
			$tab_data_string .= ">";
			$tab_data_string .= "</a>";
			$tab_data_string .= "</li>\n";
		}
		$tab_data_string .= "</ul>\n";

		return $tab_data_string;
	}
	
	/**
	 * タブ設定（登録・変更）
	 *
	 * @access public
	 * @param  string $page_name 画面名
	 * @param  string $item リンクURLに付属する値がある場合は設定
	 * @return string $tab_data_string HTMLデータ
	 */
	public function set_tab_client($page_name = NULL)
	{
		// 引数チェック
		if(is_null($page_name))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$base_url  = $CI->config->item('base_url');
		$tab_style = $CI->config->item('c_tab_style'); // タブスタイルデータ
		$tab_list  = $CI->config->item('c_tab_list_client'); // タブデータ
		$tab_data_string = "";
		// タブ用HTML-STRING作成
		$tab_data_string .= "<ul style=\"";
		$tab_data_string .= " list-style:" . $tab_style['ul_list_style'] . ";";
		$tab_data_string .= " border-bottom:" . $tab_style['ul_border_bottom'] . ";";
		$tab_data_string .= " margin:" . $tab_style['ul_margin'] . ";";
		$tab_data_string .= " padding:" . $tab_style['ul_padding'] . ";";
		$tab_data_string .= " height:" . $tab_style['ul_height'] . ";";
		$tab_data_string .= "\">\n";
		// tab 作成
		foreach($tab_list as $key => $value)
		{
			// リンク先設定
			if($key == "mt_kari" || $key == "mt_seishiki") {
				$tab_data_string .= "<li style=\"float:right;\">";
			} else {
				$tab_data_string .= "<li style=\"float:" . $tab_style['li_float'] . "\">";
			}
			$tab_data_string .= "<a";
			$tab_data_string .= " href=\"" . $base_url . "index.php/" . $page_name . $value['link'];
			$tab_data_string .= "\">";
			// 画像設定
			$tab_data_string .= "<img";
			$tab_data_string .= " src=\"" . $base_url . $value['img'] . "\"";
			$tab_data_string .= " alt=\"\"";
			$tab_data_string .= ">";
			$tab_data_string .= "</a>";
			$tab_data_string .= "</li>\n";
		}
		$tab_data_string .= "</ul>\n";

		return $tab_data_string;
	}

	/**
	 * タブ設定（登録）
	 * 
	 * @access public
	 * @param  string $page_name 画面名
	 * @param  string $item リンクURLに付属する値がある場合は設定
	 * @return string $tab_data_string HTMLデータ
	 */
	public function set_tab_a($current)
	{
		// 初期化
		$CI =& get_instance();
		$tab_data = "";
		$base_url = $CI->config->item('base_url');
		$tab_list  = $CI->config->item('c_tab_list_a'); // タブデータ
		// タブ用HTML-STRING作成
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
	////////////////////////////////////////////////////////////////////////////////////////////////
}
// END Tab_manager class

/* End of file Tab_manager.php */
/* Location: ./application/libraries/tab_manager.php */
