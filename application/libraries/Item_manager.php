<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Item_manager {
	
	/**
	 * ドロップダウンリストのHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $dropdown_name ドロップダウン名
	 * @param  int $check セレクト位置
	 * @return string $dropdown_string ドロップダウンHTML文字列
	 */
	public function set_dropdown_string($dropdown_name = NULL,$check = NULL)
	{
		// 引数チェック
		if(is_null($dropdown_name))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$val = $CI->config->item($dropdown_name);
		$dropdown_string = NULL;
		// 表示文言の設定があれば取得
		if(empty($val['title_name']))
		{
			$dropdown_string = $val['title_name'];
		}
		// 選択箇所の指定があれば指定に従う
		if(is_null($check))
		{
			$dropdown_string .= form_dropdown($val['name'],$val['data'],$val['check'],$extra);
		}else{
			$dropdown_string .= form_dropdown($val['name'],$val['data'],$check,$extra);
		}
		
		return $dropdown_string;
	}
	
	/**
	 * ドロップダウンリストのHTMLを作成し、文字列として返す
	 * (表示項目可変型 セレクト位置必須)
	 * 
	 * @access public
	 * @param  string $dropdown_set ドロップダウンセッティングデータ
	 * @param  int $check セレクト位置
	 * @return string $dropdown_string ドロップダウンHTML文字列
	 */
	public function set_variable_dropdown_string($dropdown_set = NULL)
	{
		// 引数チェック
		if(is_null($dropdown_set))
		{
		//	throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
//		$val = $CI->config->item($dropdown_name);
		$dropdown_string = NULL;
		// 表示文言の設定があれば取得
		if(empty($dropdown_set['title_name']))
		{
			$dropdown_string = $dropdown_set['title_name'];
		}

		if(empty($dropdown_set['id_name']))
		{
			$dropdown_set['id_name'] = NULL;
		}
		
		if(empty($dropdown_set['extra']))
		{
			$dropdown_set['extra'] = NULL;
		}
		// ドロップダウンリスト文字列作成
		$dropdown_string .= form_dropdown($dropdown_set['name'],$dropdown_set['data'],$dropdown_set['check'],$dropdown_set['extra']);
		
		return $dropdown_string;
	}
	
	/**
	 * ボタンのHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $button_data ボタンデータ
	 * @return string $button_string ボタンHTML文字列
	 */
	public function set_variable_button_string($button_data = NULL)
	{
		// 引数チェック
		if(is_null($button_data))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$button_string = NULL;
		
		$button_string = form_button($button_data);
		
		return $button_string;
	}
	
	/**
	 * ボタンのHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $button_name ボタン名
	 * @return string $button_string ボタンHTML文字列
	 */
	public function set_button_string($button_name = NULL)
	{
		// 引数チェック
		if(is_null($button_name))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$val = $CI->config->item($button_name);
		$button_string = NULL;
		
		$button_string = form_button($val);
		
		return $button_string;
	}
	
	/**
	 * ラベルのHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $label_name ラベル名
	 * @return string $label_string ラベルHTML文字列
	 */
	public function set_label_string($label_name = NULL)
	{
		// 引数チェック
		if(is_null($label_name))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$val = $CI->config->item($label_name);
		$label_string = NULL;
		
		$label_string = form_label($val['value'],$val['for_name']);
		
		return $label_string;
	}
	
	/**
	 * ラベルのHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $label_data 
	 * @return string $label_string ラベルHTML文字列
	 */
	public function set_variable_label_string($label_data = NULL)
	{
		// 引数チェック
		if(is_null($label_data))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
//		$val = $CI->config->item($label_data);
		$label_string = NULL;
		
		$label_string = form_label($label_data['value'],$label_data['for_name']);
		
		return $label_string;
	}
	
	
	
	
	/**
	 * フィールドセット開始のHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $field_name フィールドセット名
	 * @return string $field_start_string フィールドセットHTML文字列
	 */
	public function set_start_field_string($field_name = NULL)
	{
		// 引数チェック
		if(is_null($field_name))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$val = $CI->config->item($field_name);
		$field_start_string = NULL;
		
		$field_start_string = form_fieldset('', $val);
		
		return $field_start_string;
	}
	
	/**
	 * フィールドセット終了のHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  nothing
	 * @return string $field_end_string フィールドセットHTML文字列
	 */
	public function set_end_field_string()
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$field_end_string = NULL;
		
		$field_end_string = form_fieldset_close();
		
		return $field_end_string;
	}
	
	/**
	 * フィールドセット開始のHTMLを作成し、文字列として返す
	 * （フィールドID、フィールドクラス可変）
	 * 
	 * @access public
	 * @param  string $field_data フィールド
	 * @return string $field_start_string フィールドセットHTML文字列
	 */
	public function set_start_variable_field_string($field_data = NULL)
	{
		// 引数チェック
		if(is_null($field_data))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
//		$val = $CI->config->item($field_data);
		$field_start_string = NULL;
		
//		$field_start_string = form_fieldset('', $val);
		$field_start_string = form_fieldset('', $field_data);
		
		return $field_start_string;
	}
	
	
	
	/**
	 * リストボックスのHTMLを作成し、文字列として返す
	 * 
	 * @access public
	 * @param  string $listbox_name リストボックス名
	 * @param  string $data_array 表示するデータを連想配列で設定
	 * @return string $listbox_string リストボックスHTML文字列
	 */
	public function set_listbox_string($listbox_name = NULL,$data_array = NULL,$selected = NULL)
	{
		// 引数チェック
		if(is_null($listbox_name))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
//		$CI->load->helper('form');
		$conf_list = $CI->config->item($listbox_name);
		$conf_list['data'] = $data_array;
		$listbox_string = NULL;
		
		// タイトル名有無判定
		if( ! empty($conf_list['title_name']))
		{
			$listbox_string .= $conf_list['title_name'];
		}
		$listbox_string .= "<select";
		// リスト名有無判定
		if( ! empty($conf_list['name']))
		{
			$listbox_string .= " name=\"" . $conf_list['name'] . "\"";
		}
		// リスト横幅設定
		if(! empty($conf_list['width']))
		{
			$listbox_string .= " style=\"width:" . $conf_list['width'] . ";\"";
		}
		// リストサイズ判定
		if( ! empty($conf_list['size']))
		{
			$listbox_string .= " size=\"" . $conf_list['size'] . "\"";
		}else{
			// リストサイズのデフォルト設定
			$listbox_string .= " size=\"" . MY_DEFAULT_LIST_SIZE . "\"";
		}
		$listbox_string .= ">\n";
		// データ部生成
		if( ! is_null($data_array))
		{
			foreach($conf_list['data'] as $key => $value)
			{
				$listbox_string .= "<option";
				$listbox_string .= " value=\"" . $key . "\"";
				if($key === $selected)
				{
					$listbox_string .= " selected";
				}
				$listbox_string .= ">";
				$listbox_string .= $value;
				$listbox_string .= "</option>\n";
			}
		}
		$listbox_string .= "</select>";
		
		return $listbox_string;
	}
	
	/**
	 * ドロップダウンHTMLを作成し、返却する
	 * 
	 * @access public
	 * @param  string $kbn_id   区分ID
	 * @param  string $tag_name ドロップダウンリスト名を変更したい場合のみ使用
	 * @param  string $check    ドロップダウンリスト初期表示を変更する場合に使用
	 * @param  string $id_name  ドロップダウンリストid名を設定する場合に使用
	 * @return string $return_string ドロップダウンリストHTML文字列
	 */
	function set_dropdown_in_db_string($kbn_id = NULL,$tag_name = NULL,$check = NULL,$id_name = NULL,$extra=NULL){
		// 
		log_message('debug',"\$id_name = $id_name");
		if (is_null($kbn_id) OR is_null($tag_name)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$CI->load->model('sgmtb030');
		$conf = $CI->config->item(MY_SELECT_GENERAL_PURPOSE);
		$name = NULL;
		$val = NULL;
		$ret_data['000'] = '';
		$ret_string = '';
		// 項目名設定
		$conf['name'] = $tag_name;
		// 初期表示値設定
		if ( ! is_null($check)) {
			$conf['check'] = $check;
		}
		// id名設定
		if( ! is_null($id_name)) {
			$conf['id_name'] = $id_name;
		}
		// その他項目設定
		if( ! is_null($extra)) {
			$conf['extra'] = $extra;
		}
		
		// DBよりデータを取得し、表示用に変更
		$db_data = $CI->sgmtb030->get_list_name($kbn_id);
		if (is_null($db_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		foreach ($db_data as $key => $value) {
			log_message('debug',"======================================= drop test data");
			foreach ($value as $key2 => $value2) {
				log_message('debug',$key2." = ".$value2);
				if ($key2 === 'kbncd') {
					$name = $value2;
				}
				if ($key2 === 'ichiran') {
					$val = $value2;
				}
			}
			$ret_data[$name] = $val;
			log_message('debug',$name." = ".$ret_data[$name]);
			$name = NULL;
			$val = NULL;
		}
		$conf['data'] = $ret_data;
		$ret_string = $this->set_variable_dropdown_string($conf) . "\n";
		log_message('debug',$ret_string);
		
		return $ret_string;
	}
	
	function set_dropdown_disabled_db_string($kbn_id = NULL,$tag_name = NULL,$check = NULL,$id_name = NULL,$extra=NULL){
		log_message('debug',"\$id_name = $id_name");
		if (is_null($kbn_id) OR is_null($tag_name)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$CI->load->model('sgmtb030');
		$conf = $CI->config->item(MY_SELECT_GENERAL_PURPOSE);
		$name = NULL;
		$val = NULL;
		$ret_data['000'] = '';
		$ret_string = '';
		// 項目名設定
		$conf['name'] = $tag_name;
		// 初期表示値設定
		if ( ! is_null($check)) {
			$conf['check'] = $check;
		}
		// id名設定
		if( ! is_null($id_name)) {
			$conf['id_name'] = $id_name;
		}
		// その他項目設定
//		if( ! is_null($extra)) {
//			$conf['extra'] = $extra;
//		}
		$conf['extra'] = 'disabled="true"';
		
		// DBよりデータを取得し、表示用に変更
		$db_data = $CI->sgmtb030->get_list_name($kbn_id);
		if (is_null($db_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		foreach ($db_data as $key => $value) {
			foreach ($value as $key2 => $value2) {
				log_message('debug',$key2." = ".$value2);
				if ($key2 === 'kbncd') {
					$name = $value2;
				}
				if ($key2 === 'ichiran') {
					$val = $value2;
				}
			}
			$ret_data[$name] = $val;
			log_message('debug',$name." = ".$ret_data[$name]);
			$name = NULL;
			$val = NULL;
		}
		$conf['data'] = $ret_data;
		$ret_string = $this->set_variable_dropdown_string($conf) . "\n";
		log_message('debug',$ret_string);
		
		return $ret_string;
	}

	/**
	 * ドロップダウンHTMLを作成し、返却する(企画情報アイテム)
	 * 
	 * @access public
	 * @param  string $kbn_id   区分ID
	 * @param  string $tag_name ドロップダウンリスト名を変更したい場合のみ使用
	 * @param  string $check    ドロップダウンリスト初期表示を変更する場合に使用
	 * @param  string $id_name  ドロップダウンリストid名を設定する場合に使用
	 * @return string $return_string ドロップダウンリストHTML文字列
	 */
//	function set_dropdown_plan_item_string($kbn_id = NULL,$tag_name = NULL,$check = NULL,$id_name = NULL){
	function set_dropdown_plan_item_string($tag_name = NULL,$check = NULL,$id_name = NULL){
		log_message('debug',"========== libraries item_manager set_dropdown_plan_item_string start ==========");
		log_message('debug',"\$tag_name = $tag_name");
		log_message('debug',"\$check = $check");
		log_message('debug',"\$id_name = $id_name");
		if (is_null($tag_name)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
//		$CI->load->model('sgmtb030');
		$CI->load->model('sgmtb080');
		$conf = $CI->config->item(MY_SELECT_GENERAL_PURPOSE);
		$name = NULL;
		$val = NULL;
		$ret_data['000'] = '';
		$ret_string = '';
		// 項目名設定
		$conf['name'] = $tag_name;
		// 初期表示値設定
		if ( ! is_null($check)) {
			$conf['check'] = $check;
		}else{
			$conf['check'] = '0000';
		}
		// id名設定
		if( ! is_null($id_name)) {
			$conf['id_name'] = $id_name;
		}
		
		// DBよりデータを取得し、表示用に変更
//		$db_data = $CI->sgmtb030->get_list_name($kbn_id);
		$db_data = $CI->sgmtb080->get_all_item_name();
		if (is_null($db_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		foreach ($db_data as $key => $value) {
			$name = $value['dbnricd'].$value['itemcd'];
			$val = $value['itemnm'];
			
			$ret_data[$name] = $val;
			log_message('debug',$name." = ".$ret_data[$name]);
			$name = NULL;
			$val = NULL;
		}
		$conf['data'] = $ret_data;
		$ret_string = $this->set_variable_dropdown_string($conf) . "\n";
		log_message('debug',$ret_string);
		
		log_message('debug',"========== libraries item_manager set_dropdown_plan_item_string end ==========");
		return $ret_string;
	}
	
	function set_dropdown_disabled_item_string($tag_name = NULL,$check = NULL,$id_name = NULL){
		log_message('debug',"========== libraries item_manager set_dropdown_disabled_item_string start ==========");
		log_message('debug',"\$tag_name = $tag_name");
		log_message('debug',"\$check = $check");
		log_message('debug',"\$id_name = $id_name");
		if (is_null($tag_name)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$CI->load->model('sgmtb080');
		$conf = $CI->config->item(MY_SELECT_GENERAL_PURPOSE);
		$name = NULL;
		$val = NULL;
		$ret_data['000'] = '';
		$ret_string = '';
		// 項目名設定
		$conf['name'] = $tag_name;
		// 初期表示値設定
		if ( ! is_null($check)) {
			$conf['check'] = $check;
		}else{
			$conf['check'] = '0000';
		}
		// id名設定
		if( ! is_null($id_name)) {
			$conf['id_name'] = $id_name;
		}
		// disabled設定
		$conf['extra'] = 'disabled="true"';
		// DBよりデータを取得し、表示用に変更
		$db_data = $CI->sgmtb080->get_all_item_name();
		if (is_null($db_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		foreach ($db_data as $key => $value) {
			$name = $value['dbnricd'].$value['itemcd'];
			$val = $value['itemnm'];
			
			$ret_data[$name] = $val;
			log_message('debug',$name." = ".$ret_data[$name]);
			$name = NULL;
			$val = NULL;
		}
		$conf['data'] = $ret_data;
		$ret_string = $this->set_variable_dropdown_string($conf) . "\n";
		log_message('debug',$ret_string);
		
		log_message('debug',"========== libraries item_manager set_dropdown_disabled_item_string end ==========");
		return $ret_string;
	}
	
}

// END Item_manager class

/* End of file Item_manager.php */
/* Location: ./application/libraries/Item_manager.php */
