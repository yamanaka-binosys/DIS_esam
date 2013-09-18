<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Plan_table_manager {
	
	/**
	 * 活動区分選択用HTML-STRINGを作成
	 * 
	 * @access public
	 * @param  string $item 実績・予定判定
	 * @param  string $chek 活動区分選択判定
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_new_action_type_table($conf_item = NULL)
	{
		log_message('debug',"========== libraries plan_table_manager set_new_action_type_table start ==========");
		// 引数チェック
		if(is_null($conf_item))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL; // 活動区分HTML
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id']    = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name']  = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		log_message('debug',"========== libraries plan_table_manager set_new_action_type_table end ==========");
		return $string_data;
	}
	
	function set_table_string($plan_data = NULL)
	{
		log_message('debug',"========== libraries plan_table_manager set_table_string start ==========");
		// 引数チェック
		if (is_null($plan_data)) {
			log_message('debug',"argument plan_data NULL");
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$return_string = NULL;
		$CI =& get_instance();
		$CI->load->library('plan_manager');
		$office_count = MY_ZERO;
		
		// 取得したデータ数分HTMLを作成する
		for ($i=0; $i < $plan_data['count']; $i++) {
			$plan_data_count = $i + 1; // コンフィグデータカウント
			if ($plan_data['data'][$i]['action_type'] === MY_ACTION_TYPE_HONBU) {
				// フィールド名取得
				$conf_item = $CI->plan_manager->get_field_name_honbu($plan_data_count);
				// フィールド名と取得データを使用してHTMLを作成
				$return_string .= $this->set_honbu_table($conf_item,$plan_data['data'][$i]);
			}else if($plan_data['data'][$i]['action_type'] === MY_ACTION_TYPE_TENPO){
				// フィールド名取得
				$conf_item = $CI->plan_manager->get_field_name_tenpo($plan_data_count);
				// フィールド名と取得データを使用してHTMLを作成
				$return_string .= $this->set_tenpo_table($conf_item,$plan_data['data'][$i]);
			}else if($plan_data['data'][$i]['action_type'] === MY_ACTION_TYPE_DAIRI){
				// フィールド名取得
				$conf_item = $CI->plan_manager->get_field_name_dairi($plan_data_count);
				// フィールド名と取得データを使用してHTMLを作成
				$return_string .= $this->set_dairi_table($conf_item,$plan_data['data'][$i]);
			}else if($plan_data['data'][$i]['action_type'] === MY_ACTION_TYPE_OFFICE){
				// フィールド名取得
				$conf_item = $CI->plan_manager->get_field_name_office($plan_data_count);
				// フィールド名と取得データを使用してHTMLを作成
				$return_string .= $this->set_office_table($conf_item,$plan_data['data'][$i]);
			}
		}
		
		log_message('debug',"========== libraries plan_table_manager set_table_string end ==========");
		
		return $return_string;
		
	}
	
	/**
	 * 活動区分（本部）の新規HTML-STRINGを作成
	 * 
	 * @access public
	 * @param  string $conf_item HTML設定ファイル
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_honbu_table($conf_item = NULL,$pran_data)
	{
		log_message('debug',"================================================ honbu data");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// データ番号取得
		$data_cont = substr($conf_item['action_type_name'],-1);
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（本部）
		$check = MY_ACTION_TYPE_HONBU;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（本部）
		$drop_honbu_kubun = $CI->config->item(MY_SELECT_HONBU_KUBUN);
		$drop_honbu_kubun['name'] = $conf_item['drop_honbu_kubun_name'];
		if (isset($pran_data['honbu_kubun'])) {
			$drop_honbu_kubun['check'] = $pran_data['honbu_kubun'];
		}
		// ドロップダウン項目設定（本部場所）
		$drop_honbu_location = $CI->config->item(MY_SELECT_HONBU_LOCATION);
		$drop_honbu_location['name'] = $conf_item['drop_honbu_location'];
		if (isset($pran_data['honbu_location'])) {
			$drop_honbu_location['check'] = $pran_data['honbu_location'];
		}
		// 月次商談設定項目設定
		$getsuji_syoudan_data = $CI->config->item(MY_GETSUJI_NEGO);
		$getsuji_data = $getsuji_syoudan_data['data'];
		$getsuji_right = $getsuji_syoudan_data['count_right'];
		$getsuji_left = $getsuji_syoudan_data['count_left'];
		// 半期提案項目設定
		$hanki_teian_data = $CI->config->item(MY_HANKI_TEIAN);
		$hanki_data = $hanki_teian_data['data'];
		$hanki_right = $hanki_teian_data['count_right'];
		$hanki_left = $hanki_teian_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		$string_data .= "<table>\n<tr>\n<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
		$string_data .= "<td>\n";
		// 日付設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"\">年</td>\n";
		}
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"\">月</td>\n";
		}
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"\">日</td>\n";
		}
		$string_data .= "</tr>\n";
		// 開始時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['start_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['start_hour'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['start_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['start_minute'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['end_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['end_hour'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['end_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['end_minute'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 販売店名・地区本部設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['shop_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"". $pran_data['shop_name'] ."\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 区分設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_honbu_kubun) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 面談者設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan_name1'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan_name1'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan_name2'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan_name2'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		// 同行者設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">同行者</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou_name1'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou_name1'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou_name2'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou_name2'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 場所設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">場所</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_honbu_location) . "\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談内容設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"height:200px; width:800px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>【商談内容】</td>\n";
		$string_data .= "</tr>\n";
		// 月次商談設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">月次商談</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		$s_td_string = "<td style=\"height:24px\">";
		$e_td_string = "</td>\n";
		for($i=MY_ZERO; $i < 7; $i++)
		{
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $getsuji_left + $i;
			// 左側表示
			if( ! empty($getsuji_data[$leftcount]) AND $leftcount < $getsuji_right)
			{
				$data_name = 'getsuji_syoudan' . ($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($leftcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($leftcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $getsuji_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $getsuji_right + $i;
			// 右側表示
			if( ! empty($getsuji_data[$righcount])){
				$data_name = 'getsuji_syoudan' . ($righcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($righcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($righcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $getsuji_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 半期提案設定
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">半期提案</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $hanki_left + $i;
			// 左側表示
			if( ! empty($hanki_data[$leftcount]) AND $leftcount < $hanki_right)
			{
				$data_name = 'hanki_teian' . ($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($leftcount + 1) ."_teian" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($leftcount + 1) ."_teian" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $hanki_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $hanki_right + $i;
			// 右側表示
			if( ! empty($hanki_data[$righcount])){
				$data_name = 'hanki_teian' . ($righcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($righcount + 1) ."_teian" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($righcount + 1) ."_teian" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $hanki_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// その他設定
		$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
		$string_data .= "<tr>\n";
		if ( ! empty($pran_data['other'])) {
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\" checked>その他</td>\n";
		}else{
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\">その他</td>\n";
		}
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// カテゴリー設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>【カテゴリー】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n";
		$string_data .= "<tr>\n";
		if ( ! empty($pran_data['category1'])) {
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\" checked>紙中分類</td>\n";
		}else{
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\">紙中分類</td>\n";
		}
		if ( ! empty($pran_data['category2'])) {
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\" checked>加工品大分類</td>\n";
		}else{
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\">加工品大分類</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 商談予定設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\">\n";
		if (isset($pran_data['shodan_plan'])) {
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['shodan_plan_name'] . "\">".$pran_data['shodan_plan']."</textarea>\n";
		}else{
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['shodan_plan_name'] . "\"></textarea>\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 不明領域設定
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "<br>\n";
		// フィールドセット終了
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（店舗）の新規HTML-STRINGを作成
	 * 
	 * 
	 */
	public function set_tenpo_table($conf_item = NULL,$pran_data)
	{
		log_message('debug',"=====================================tenpo data");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// データ番号取得
		$data_cont = substr($conf_item['action_type_name'],-1);
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_TENPO;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（店舗）
		$drop_tenpo_kubun = $CI->config->item(MY_SELECT_TENPO_KUBUN);
		$drop_tenpo_kubun['name'] = $conf_item['drop_tenpo_kubun_name'];
		if (isset($pran_data['tenpo_kubun'])) {
			$drop_tenpo_kubun['check'] = $pran_data['tenpo_kubun'];
		}
		// 月次商談設定項目設定
		$tenpo_syoudan_data = $CI->config->item(MY_TENPO_SHOUDAN);
		$tenpo_data = $tenpo_syoudan_data['data'];
		$tenpo_right = $tenpo_syoudan_data['count_right'];
		$tenpo_left = $tenpo_syoudan_data['count_left'];
		// 半期提案項目設定
		$tennai_work_data = $CI->config->item(MY_TENNAI_WORK);
		$tennai_data = $tennai_work_data['data'];
		$tennai_right = $tennai_work_data['count_right'];
		$tennai_left = $tennai_work_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
		$string_data .= "<td>\n";
		// 日付設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"\">年</td>\n";
		}
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"\">月</td>\n";
		}
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"\">日</td>\n";
		}
		$string_data .= "</tr>\n";
		// 開始時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['start_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['start_hour'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['start_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['start_minute'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['end_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['end_hour'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['end_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['end_minute'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 販売店名・地区本部設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['shop_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"" . $pran_data['shop_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 区分設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_tenpo_kubun) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 面談者設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan_name1'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan_name1'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan_name2'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan_name2'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		// 同行者設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">同行者</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou_name1'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou_name1'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou_name2'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou_name2'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談内容設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"height:200px; width:800px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>【商談内容】</td>\n";
		$string_data .= "</tr>\n";
		// 店舗商談設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">店舗商談</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		$s_td_string = "<td style=\"height:24px\">";
		$e_td_string = "</td>\n";
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $tenpo_left + $i;
			// 左側表示
			if( ! empty($tenpo_data[$leftcount]) AND $i < $tenpo_right)
			{
//				$data_name = 'tenpo'.($leftcount + 1).'_syoudan';
				$data_name = 'tenpo_syoudan'.($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($leftcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($leftcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tenpo_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $tenpo_right + $i;
			// 右側表示
			if( ! empty($tenpo_data[$righcount])){
//				$data_name = 'tenpo'.($righcount + 1).'_syoudan';
				$data_name = 'tenpo_syoudan'.($righcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($righcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($righcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tenpo_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// 店内作業設定
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">店内作業</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $tennai_left + $i;
			// 左側表示
			if( ! empty($tennai_data[$leftcount]) AND $leftcount < $tennai_right)
			{
//				$data_name = 'tennai'.($leftcount + 1).'_action';
				$data_name = 'tennai_action'.($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($leftcount + 1) . "_action" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($leftcount + 1) . "_action" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tennai_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $tennai_right + $i;
			// 右側表示
			if( ! empty($tennai_data[$righcount])){
//				$data_name = 'tennai'.($righcount + 1).'_action';
				$data_name = 'tennai_action'.($righcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($righcount + 1) . "_action" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($righcount + 1) . "_action" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tennai_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// その他設定
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
		$string_data .= "<tr>\n";
		if ( ! empty($pran_data['other'])) {
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\" checked>その他</td>\n";
		}else{
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\">その他</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= $s_td_string . "&ensp;" . $e_td_string;
		if ( ! empty($pran_data['other1'])) {
			$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"" . $conf_item['other_name1'] . "\" checked>" . $e_td_string;
		}else{
			$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"" . $conf_item['other_name1'] . "\">" . $e_td_string;
		}
		$string_data .= $s_td_string . "競合店調査（MR）" . $e_td_string;
		$string_data .= $s_td_string . "&ensp;" . $e_td_string;
		$string_data .= $s_td_string . "&ensp;" . $e_td_string;
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 6; $i++)
		{
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// カテゴリー設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>【カテゴリー】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n";
		$string_data .= "<tr>\n";
		if ( ! empty($pran_data['category1'])) {
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\" checked>紙中分類</td>\n";
		}else{
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\">紙中分類</td>\n";
		}
		if ( ! empty($pran_data['category2'])) {
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\" checked>加工品大分類</td>\n";
		}else{
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\">加工品大分類</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 商談予定設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">【作業予定】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\">\n";
		if (isset($pran_data['work_plan'])) {
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\">".$pran_data['work_plan']."</textarea>\n";
		}else{
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 不明領域設定
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		// フィールドセット終了
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		$string_data .= "<br>\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（代理店）の新規HTML-STRINGを作成
	 * 
	 */
	public function set_dairi_table($conf_item = NULL,$pran_data)
	{
		log_message('debug',"=====================================dairi data");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// データ番号取得
		$data_cont = substr($conf_item['action_type_name'],-1);
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_DAIRI;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（代理店区分）
		$drop_dairi_kubun = $CI->config->item(MY_SELECT_DAIRI_KUBUN);
		$drop_dairi_kubun['name'] = $conf_item['drop_dairi_kubun_name'];
		if (isset($pran_data['dairi_kubun'])) {
			$drop_dairi_kubun['check'] = $pran_data['dairi_kubun'];
		}
		// ドロップダウン項目設定（代理店ランク）
		$drop_dairi_rank = $CI->config->item(MY_SELECT_DAIRI_RANK);
		$drop_dairi_rank['name'] = $conf_item['drop_dairi_rank_name'];
		if (isset($pran_data['dairi_rank'])) {
			$drop_dairi_rank['check'] = $pran_data['dairi_rank'];
		}
		// 代理店・一般設定項目設定
		$dairi_ippan_data = $CI->config->item(MY_DAIRI_IPPAN);
		$ippan_data = $dairi_ippan_data['data'];
		$ippan_right = $dairi_ippan_data['count_right'];
		$ippan_left = $dairi_ippan_data['count_left'];
		// 管理販売項目設定
		$kanri_hanbai_data = $CI->config->item(MY_KANRI_HANBAI);
		$kanri_data = $kanri_hanbai_data['data'];
		$kanri_right = $kanri_hanbai_data['count_right'];
		$kanri_left = $kanri_hanbai_data['count_left'];
		// 管理その他項目設定
		$kanri_other_data = $CI->config->item(MY_KANRI_OTHER);
		$other_data = $kanri_other_data['data'];
		$other_right = $kanri_other_data['count_right'];
		$other_left = $kanri_other_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
		$string_data .= "<td>\n";
		// 日付設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"\">年</td>\n";
		}
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"\">月</td>\n";
		}
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"\">日</td>\n";
		}
		$string_data .= "</tr>\n";
		// 開始時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['start_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['start_hour'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['start_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['start_minute'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['end_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['end_hour'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['end_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['end_minute'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 卸名
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">卸名</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['shop_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"" . $pran_data['shop_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 区分設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_dairi_kubun) . "\n";
		$string_data .= "</td>\n</tr>\n";
		// ランク設定
		$string_data .= "<tr>\n<td colspan=\"2\">ランク</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_dairi_rank) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 面談者設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan_name1'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan_name1'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan_name2'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan_name2'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		// 同行者設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">同行者</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou_name1'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou_name1'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou_name2'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou_name2'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談内容設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"height:200px; width:800px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>【商談内容】</td>\n";
		$string_data .= "</tr>\n";
		// 対代理店・対一般店
		$string_data .= "<tr>\n";
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">対代理店・対一般店</td>\n";
		$string_data .= "</tr>\n";
		$s_td_string = "<td style=\"height:24px\">";
		$e_td_string = "</td>\n";
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $ippan_left + $i;
			// 左側表示
			if( ! empty($ippan_data[$leftcount]) AND $leftcount < $ippan_right)
			{
				$data_name = 'dairi_ippan'.($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($leftcount + 1) . "_ippan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($leftcount + 1) . "_ippan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $ippan_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $ippan_right + $i;
			// 右側表示
			if( ! empty($ippan_data[$righcount])){
				$data_name = 'dairi_ippan'.($righcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($righcount + 1) . "_ippan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($righcount + 1) . "_ippan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $ippan_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// 対管理販売店
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">対管理販売店</td>\n";
		$string_data .= "</tr>\n";
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $kanri_left + $i;
			// 左側表示
			if( ! empty($kanri_data[$leftcount]) AND $leftcount < $kanri_right)
			{
				$data_name = 'kanri_hanbai'.($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($leftcount + 1) . "_hanbai" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($leftcount + 1) . "_hanbai" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $kanri_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $kanri_right + $i;
			// 右側表示
			if( ! empty($kanri_data[$righcount])){
				$data_name = 'kanri_hanbai'.($righcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($righcount + 1) . "_hanbai" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($righcount + 1) . "_hanbai" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $kanri_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// その他設定
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
		$string_data .= "<tr>\n";
		if ( ! empty($pran_data['other'])) {
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\" checked>その他</td>\n";
		}else{
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\">その他</td>\n";
		}
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++)
		{
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $other_left + $i;
			// 左側表示
			if( ! empty($other_data[$leftcount]) AND $leftcount < $other_right)
			{
				$data_name = 'other'.($leftcount + 1);
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other" . ($leftcount + 1) . "_no" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other" . ($leftcount + 1) . "_no" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $other_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談予定設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\">\n";
		if (isset($pran_data['shoudan_plan'])) {
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\">".$pran_data['shoudan_plan']."</textarea>\n";
		}else{
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 不明領域設定
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "<br>\n";
		// フィールドセット終了
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（内勤）の新規HTML-STRINGを作成
	 * 
	 */
	public function set_office_table($conf_item = NULL,$pran_data)
	{
		log_message('debug',"=====================================office data");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_OFFICE;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（内勤作業）
		$office_work_data = $CI->config->item(MY_SELECT_OFFICE_WORK);
		$office_work_data['name'] = $conf_item['office_work_name'];
		if (isset($pran_data['office_work'])) {
			$office_work_data['check'] = $pran_data['office_work'];
		}
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		// 内勤設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"7\">日付</td>\n";
		$string_data .= "<td colspan=\"6\">時刻</td>\n";
		$string_data .= "<td colspan=\"2\">作業内容</td>\n";
		$string_data .= "<td>結果情報</td>";
		$string_data .= "</tr>\n";
		// 日付設定
		$string_data .= "<tr>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year'] . "\" value=\"" . $pran_data['year'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>年</td>\n";
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['month'] . "\" value=\"" . $pran_data['month'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['month'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>月</td>\n";
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['day'] . "\" value=\"" . $pran_data['day'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['day'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>日</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		// 開始時刻設定
		$string_data .= "<td>開始</td>\n";
		if (isset($pran_data['start_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour'] . "\" value=\"" . $pran_data['start_hour'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>時</td>\n";
		if (isset($pran_data['start_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute'] . "\" value=\"" . $pran_data['start_minute'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>分</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		// 内勤作業設定
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($office_work_data) . "\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['action_result'])) {
			$string_data .= "<td rowspan=\"2\"><textarea style=\"height:40px; width:300px\" name=\"" . $conf_item['action_result'] . "\">".$pran_data['action_result']."</textarea></td>\n";
		}else{
			$string_data .= "<td rowspan=\"2\"><textarea style=\"height:40px; width:300px\" name=\"" . $conf_item['action_result'] . "\"></textarea></td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"7\">&ensp;</td>\n";
		$string_data .= "<td>終了</td>\n";
		if (isset($pran_data['end_hour'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour'] . "\" value=\"" . $pran_data['end_hour'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>時</td>\n";
		if (isset($pran_data['end_minute'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute'] . "\" value=\"" . $pran_data['end_minute'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>分</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['other_action'])) {
			$string_data .= "<td style=\"padding-left:10px\">その他<input type=\"text\" size=\"15\" maxlength=\"15\" name=\"" . $conf_item['other_action'] . "\" value=\"" . $pran_data['other_action'] . "\"></td>\n";
		}else{
			$string_data .= "<td style=\"padding-left:10px\">その他<input type=\"text\" size=\"15\" maxlength=\"15\" name=\"" . $conf_item['other_action'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "<br>\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（本部）の新規HTML-STRINGを作成
	 * 
	 * @access public
	 * @param  string $conf_item HTML設定ファイル
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_honbu_table_string($conf_item = NULL,$pran_data)
	{
		log_message('debug',"================================================ honbu data string");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// データ番号取得
		$data_cont = substr($conf_item['action_type_name'],-1);
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（本部）
		$check = MY_ACTION_TYPE_HONBU;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（本部）
		$drop_honbu_kubun = $CI->config->item(MY_SELECT_HONBU_KUBUN);
		$drop_honbu_kubun['name'] = $conf_item['drop_honbu_kubun_name'];
		if (isset($pran_data['honbu_kubun'])) {
			$drop_honbu_kubun['check'] = $pran_data['honbu_kubun'];
		}
		// ドロップダウン項目設定（本部場所）
		$drop_honbu_location = $CI->config->item(MY_SELECT_HONBU_LOCATION);
		$drop_honbu_location['name'] = $conf_item['drop_honbu_location'];
		if (isset($pran_data['honbu_location'])) {
			$drop_honbu_location['check'] = $pran_data['honbu_location'];
		}
		// 月次商談設定項目設定
		$getsuji_syoudan_data = $CI->config->item(MY_GETSUJI_NEGO);
		$getsuji_data = $getsuji_syoudan_data['data'];
		$getsuji_right = $getsuji_syoudan_data['count_right'];
		$getsuji_left = $getsuji_syoudan_data['count_left'];
		// 半期提案項目設定
		$hanki_teian_data = $CI->config->item(MY_HANKI_TEIAN);
		$hanki_data = $hanki_teian_data['data'];
		$hanki_right = $hanki_teian_data['count_right'];
		$hanki_left = $hanki_teian_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		$string_data .= "<table>\n<tr>\n<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
		$string_data .= "<td>\n";
		// 日付設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"\">年</td>\n";
		}
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"\">月</td>\n";
		}
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"\">日</td>\n";
		}
		$string_data .= "</tr>\n";
		// 開始時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['s_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['s_hour_name'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['s_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['s_minute_name'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['e_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['e_hour_name'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['e_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['e_minute_name'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 販売店名・地区本部設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['shop'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"".$pran_data['shop']."\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 区分設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_honbu_kubun) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 面談者設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan1_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan1_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan2_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan2_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		// 同行者設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">同行者</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou1_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou1_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou2_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou2_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 場所設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">場所</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_honbu_location) . "\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談内容設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"height:200px; width:800px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>【商談内容】</td>\n";
		$string_data .= "</tr>\n";
		// 月次商談設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">月次商談</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		$s_td_string = "<td style=\"height:24px\">";
		$e_td_string = "</td>\n";
		for($i=MY_ZERO; $i < 7; $i++)
		{
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $getsuji_left + $i;
			// 左側表示
			if( ! empty($getsuji_data[$leftcount]) AND $leftcount < $getsuji_right)
			{
				$data_name = 'getsuji'.($leftcount + 1).'_syoudan';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($leftcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($leftcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $getsuji_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $getsuji_right + $i;
			// 右側表示
			if( ! empty($getsuji_data[$righcount])){
				$data_name = 'getsuji'.($righcount + 1).'_syoudan';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($righcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji" . ($righcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $getsuji_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 半期提案設定
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">半期提案</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $hanki_left + $i;
			// 左側表示
			if( ! empty($hanki_data[$leftcount]) AND $leftcount < $hanki_right)
			{
				$data_name = 'hanki'.($leftcount + 1).'_teian';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($leftcount + 1) . "_teian" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($leftcount + 1) . "_teian" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $hanki_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $hanki_right + $i;
			// 右側表示
			if( ! empty($hanki_data[$righcount])){
				$data_name = 'hanki'.($righcount + 1).'_teian';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($righcount + 1) . "_teian" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki" . ($righcount + 1) . "_teian" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $hanki_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// その他設定
		$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
		$string_data .= "<tr>\n";
		if ( ! empty($pran_data['other'])) {
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\" checked>その他</td>\n";
		}else{
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\">その他</td>\n";
		}
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// カテゴリー設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>【カテゴリー】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td style=\"padding-left:10px\">\n";
		if ( ! empty($pran_data['cate1_name'])) {
			$string_data .= "<input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\" checked>紙中分類</td>\n";
		}else{
			$string_data .= "<input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\">紙中分類</td>\n";
		}
		if ( ! empty($pran_data['cate2_name'])) {
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\" checked>加工品大分類</td>\n";
		}else{
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\">加工品大分類</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 商談予定設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\">\n";
		if ( ! empty($pran_data['shodan_plan'])) {
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['shodan_plan_name'] . "\" id=\"shodan_plan\">". $pran_data['shodan_plan'] ."</textarea>\n";
		}else{
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['shodan_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 不明領域設定
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "<br>\n";
		// フィールドセット終了
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（店舗）の新規HTML-STRINGを作成
	 * 
	 * 
	 */
	public function set_tenpo_table_string($conf_item = NULL,$pran_data)
	{
		log_message('debug',"=====================================tenpo data string");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// データ番号取得
		$data_cont = substr($conf_item['action_type_name'],-1);
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_TENPO;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（店舗）
		$drop_tenpo_kubun = $CI->config->item(MY_SELECT_TENPO_KUBUN);
		$drop_tenpo_kubun['name'] = $conf_item['drop_tenpo_kubun_name'];
		if (isset($pran_data['tenpo_kubun'])) {
			$drop_tenpo_kubun['check'] = $pran_data['tenpo_kubun'];
		}
		// 月次商談設定項目設定
		$tenpo_syoudan_data = $CI->config->item(MY_TENPO_SHOUDAN);
		$tenpo_data = $tenpo_syoudan_data['data'];
		$tenpo_right = $tenpo_syoudan_data['count_right'];
		$tenpo_left = $tenpo_syoudan_data['count_left'];
		// 半期提案項目設定
		$tennai_work_data = $CI->config->item(MY_TENNAI_WORK);
		$tennai_data = $tennai_work_data['data'];
		$tennai_right = $tennai_work_data['count_right'];
		$tennai_left = $tennai_work_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
		$string_data .= "<td>\n";
		// 日付設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"\">年</td>\n";
		}
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"\">月</td>\n";
		}
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"\">日</td>\n";
		}
		$string_data .= "</tr>\n";
		// 開始時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['s_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['s_hour_name'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['s_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['s_minute_name'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['e_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['e_hour_name'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['e_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['e_minute_name'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 販売店名・地区本部設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['shop'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"" . $pran_data['shop'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
		}
		if (isset($conf_item['shop_name_link'])) {
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
		}else{
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='';\">";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 区分設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_tenpo_kubun) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 面談者設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan1_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan1_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan2_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan2_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		// 同行者設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">同行者</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou1_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou1_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou2_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou2_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談内容設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"height:200px; width:800px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>【商談内容】</td>\n";
		$string_data .= "</tr>\n";
		// 店舗商談設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">店舗商談</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		$s_td_string = "<td style=\"height:24px\">";
		$e_td_string = "</td>\n";
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $tenpo_left + $i;
			// 左側表示
			if( ! empty($tenpo_data[$leftcount]) AND $i < $tenpo_right)
			{
				$data_name = 'tenpo'.($leftcount + 1).'_syoudan';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($leftcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($leftcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tenpo_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $tenpo_right + $i;
			// 右側表示
			if( ! empty($tenpo_data[$righcount])){
				$data_name = 'tenpo'.($righcount + 1).'_syoudan';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($righcount + 1) . "_syoudan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo" . ($righcount + 1) . "_syoudan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tenpo_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// 店内作業設定
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">店内作業</td>\n";
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $tennai_left + $i;
			// 左側表示
			if( ! empty($tennai_data[$leftcount]) AND $leftcount < $tennai_right)
			{
				$data_name = 'tennai'.($leftcount + 1).'_action';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($leftcount + 1) . "_action" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($leftcount + 1) . "_action" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tennai_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $tennai_right + $i;
			// 右側表示
			if( ! empty($tennai_data[$righcount])){
				$data_name = 'tennai'.($leftcount + 1).'_action';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($righcount + 1) . "_action" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai" . ($righcount + 1) . "_action" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $tennai_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// その他設定
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">\n";
		if ( ! empty($pran_data['other'])) {
			$string_data .= "<input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\" checked>その他</td>\n";
		}else{
			$string_data .= "<input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\">その他</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= $s_td_string . "&ensp;" . $e_td_string;
		if ( ! empty($pran_data['other_no'])) {
			$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"" . $conf_item['other_name1'] . "\" checked>" . $e_td_string;
		}else{
			$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"" . $conf_item['other_name1'] . "\">" . $e_td_string;
		}
		$string_data .= $s_td_string . "競合店調査（MR）" . $e_td_string;
		$string_data .= $s_td_string . "&ensp;" . $e_td_string;
		$string_data .= $s_td_string . "&ensp;" . $e_td_string;
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 6; $i++)
		{
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// カテゴリー設定
		$string_data .= "<tr>\n";
		$string_data .= "<td>【カテゴリー】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td style=\"padding-left:10px\">";
		if ( ! empty($pran_data['cate1_name'])) {
			$string_data .= "<input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\" checked>紙中分類</td>\n";
		}else{
			$string_data .= "<input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\">紙中分類</td>\n";
		}
		if ( ! empty($pran_data['cate2_name'])) {
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\" checked>加工品大分類</td>\n";
		}else{
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\">加工品大分類</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 商談予定設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">【作業予定】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\">\n";
		if (isset($pran_data['shodan_plan'])) {
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" checked>".$pran_data['shodan_plan']."</textarea>\n";
		}else{
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\"></textarea>\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 不明領域設定
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		// フィールドセット終了
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		$string_data .= "<br>\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（代理店）の新規HTML-STRINGを作成
	 * 
	 */
	public function set_dairi_table_string($conf_item = NULL,$pran_data)
	{
		log_message('debug',"=====================================dairi data string");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// データ番号取得
		$data_cont = substr($conf_item['action_type_name'],-1);
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_DAIRI;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（代理店区分）
		$drop_dairi_kubun = $CI->config->item(MY_SELECT_DAIRI_KUBUN);
		$drop_dairi_kubun['name'] = $conf_item['drop_dairi_kubun_name'];
		if (isset($pran_data['dairi_kubun'])) {
			$drop_dairi_kubun['check'] = $pran_data['dairi_kubun'];
		}
		// ドロップダウン項目設定（代理店ランク）
		$drop_dairi_rank = $CI->config->item(MY_SELECT_DAIRI_RANK);
		$drop_dairi_rank['name'] = $conf_item['drop_dairi_rank_name'];
		if (isset($pran_data['dairi_rank'])) {
			$drop_dairi_rank['check'] = $pran_data['dairi_rank'];
		}
		// 代理店・一般設定項目設定
		$dairi_ippan_data = $CI->config->item(MY_DAIRI_IPPAN);
		$ippan_data = $dairi_ippan_data['data'];
		$ippan_right = $dairi_ippan_data['count_right'];
		$ippan_left = $dairi_ippan_data['count_left'];
		// 管理販売項目設定
		$kanri_hanbai_data = $CI->config->item(MY_KANRI_HANBAI);
		$kanri_data = $kanri_hanbai_data['data'];
		$kanri_right = $kanri_hanbai_data['count_right'];
		$kanri_left = $kanri_hanbai_data['count_left'];
		// 管理その他項目設定
		$kanri_other_data = $CI->config->item(MY_KANRI_OTHER);
		$other_data = $kanri_other_data['data'];
		$other_right = $kanri_other_data['count_right'];
		$other_left = $kanri_other_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
		$string_data .= "<td>\n";
		// 日付設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"\">年</td>\n";
		}
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"\">月</td>\n";
		}
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"\">日</td>\n";
		}
		$string_data .= "</tr>\n";
		// 開始時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['s_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['s_hour_name'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['s_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['s_minute_name'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['e_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['e_hour_name'] . "\">時</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"\">時</td>\n";
		}
		if (isset($pran_data['e_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['e_minute_name'] . "\">分</td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"\">分</td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 卸名
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">卸名</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['shop'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"" . $pran_data['shop'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 区分設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_dairi_kubun) . "\n";
		$string_data .= "</td>\n</tr>\n";
		// ランク設定
		$string_data .= "<tr>\n<td colspan=\"2\">ランク</td>\n</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_dairi_rank) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
		// 面談者設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan1_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan1_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['mendan2_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan2_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		// 同行者設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">同行者</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou1_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou1_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n<tr>\n";
		$string_data .= "<td>&ensp;</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['doukou2_name'])) {
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou2_name'] . "\">";
		}else{
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"\">";
		}
		$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談内容設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"3\">\n";
		$string_data .= "<table style=\"height:200px; width:800px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>【商談内容】</td>\n";
		$string_data .= "</tr>\n";
		// 対代理店・対一般店
		$string_data .= "<tr>\n";
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">対代理店・対一般店</td>\n";
		$string_data .= "</tr>\n";
		$s_td_string = "<td style=\"height:24px\">";
		$e_td_string = "</td>\n";
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $ippan_left + $i;
			// 左側表示
			if( ! empty($ippan_data[$leftcount]) AND $leftcount < $ippan_right)
			{
				$data_name = 'dairi'.($leftcount + 1).'_ippan';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($leftcount + 1) . "_ippan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($leftcount + 1) . "_ippan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $ippan_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $ippan_right + $i;
			// 右側表示
			if( ! empty($ippan_data[$righcount])){
				$data_name = 'dairi'.($righcount + 1).'_ippan';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($righcount + 1) . "_ippan" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi" . ($righcount + 1) . "_ippan" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $ippan_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// 対管理販売店
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\" style=\"height:20px\">対管理販売店</td>\n";
		$string_data .= "</tr>\n";
		for ($i=MY_ZERO; $i < 7; $i++) { 
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $kanri_left + $i;
			// 左側表示
			if( ! empty($kanri_data[$leftcount]) AND $leftcount < $kanri_right)
			{
				$data_name = 'kanri'.($leftcount + 1).'_hanbai';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($leftcount + 1) . "_hanbai" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($leftcount + 1) . "_hanbai" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $kanri_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$righcount = $kanri_right + $i;
			// 右側表示
			if( ! empty($kanri_data[$righcount])){
				$data_name = 'kanri'.($righcount + 1).'_hanbai';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($righcount + 1) . "_hanbai" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri" . ($righcount + 1) . "_hanbai" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $kanri_data[$righcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		// その他設定
		$string_data .= "<td>\n";
		$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
		$string_data .= "<tr>\n";
		if (isset($pran_data['other'])) {
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\" checked>その他</td>\n";
		}else{
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"" . $conf_item['other_name'] . "\">その他</td>\n";
		}
		$string_data .= "</tr>\n";
		// 表示データ設定
		for ($i=MY_ZERO; $i < 7; $i++)
		{
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$leftcount = $other_left + $i;
			// 左側表示
			if( ! empty($other_data[$leftcount]) AND $leftcount < $other_right)
			{
				$data_name = 'other'.($leftcount + 1).'_no';
				if ( ! empty($pran_data[$data_name])) {
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other" . ($leftcount + 1) . "_no" . $data_cont . "\" checked>" . $e_td_string;
				}else{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other" . ($leftcount + 1) . "_no" . $data_cont . "\">" . $e_td_string;
				}
				$string_data .= $s_td_string . $other_data[$leftcount] . $e_td_string;
			}else{
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			}
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
		// 商談予定設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"5\">\n";
		if (isset($pran_data['work_plan'])) {
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\">".$pran_data['work_plan']."</textarea>\n";
		}else{
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
		// 不明領域設定
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "<tr>\n<td colspan=\"5\">\n";
		$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
		$string_data .= "</td>\n</tr>\n";
		$string_data .= "</table>\n";
		$string_data .= "<br>\n";
		// フィールドセット終了
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		
		return $string_data;
	}
	
	/**
	 * 活動区分（内勤）の新規HTML-STRINGを作成
	 * 
	 */
	public function set_office_table_string($conf_item = NULL,$pran_data)
	{
		log_message('debug',"=====================================office data string");
		foreach ($pran_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_OFFICE;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（内勤作業）
		$office_work_data = $CI->config->item(MY_SELECT_OFFICE_WORK);
		$office_work_data['name'] = $conf_item['office_work_name'];
		if (isset($pran_data['office_work'])) {
			$office_work_data['check'] = $pran_data['office_work'];
		}
		
		// 活動区分設定
		$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_view_name']."\" value=\"表示\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_del_name']."\" value=\"削除\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_move_name']."\" value=\"移動\">\n";
		$string_data .= "<input type=\"submit\" name=\"".$conf_item['button_copy_name']."\" value=\"コピー\">\n";
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= "<br>\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['jyohonum'] . "\" value=\"" . $pran_data['jyohonum'] . "\" />\n";
		$string_data .= "<input type=\"hidden\" name=\"" . $conf_item['edbn'] . "\" value=\"" . $pran_data['edbn'] . "\" />\n";
		// 内勤設定
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"7\">日付</td>\n";
		$string_data .= "<td colspan=\"6\">時刻</td>\n";
		$string_data .= "<td colspan=\"2\">作業内容</td>\n";
		$string_data .= "<td>結果情報</td>";
		$string_data .= "</tr>\n";
		// 日付設定
		$string_data .= "<tr>\n";
		if (isset($pran_data['year'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year'] . "\" value=\"" . $pran_data['year'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>年</td>\n";
		if (isset($pran_data['month'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['month'] . "\" value=\"" . $pran_data['month'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['month'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>月</td>\n";
		if (isset($pran_data['day'])) {
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['day'] . "\" value=\"" . $pran_data['day'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['day'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>日</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		// 開始時刻設定
		$string_data .= "<td>開始</td>\n";
		if (isset($pran_data['s_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour'] . "\" value=\"" . $pran_data['s_hour_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>時</td>\n";
		if (isset($pran_data['s_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute'] . "\" value=\"" . $pran_data['s_minute_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>分</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		// 内勤作業設定
		$string_data .= "<td>\n";
		$string_data .= $CI->item_manager->set_variable_dropdown_string($office_work_data) . "\n";
		$string_data .= "</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['result'])) {
			$string_data .= "<td rowspan=\"2\"><textarea style=\"height:40px; width:300px\" name=\"" . $conf_item['action_result'] . "\" value=\"\">".$pran_data['result']."</textarea></td>\n";
		}else{
			$string_data .= "<td rowspan=\"2\"><textarea style=\"height:40px; width:300px\" name=\"" . $conf_item['action_result'] . "\" value=\"\"></textarea></td>\n";
		}
		$string_data .= "</tr>\n";
		// 終了時刻設定
		$string_data .= "<tr>\n";
		$string_data .= "<td colspan=\"7\">&ensp;</td>\n";
		$string_data .= "<td>終了</td>\n";
		if (isset($pran_data['e_hour_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour'] . "\" value=\"" . $pran_data['e_hour_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>時</td>\n";
		if (isset($pran_data['e_minute_name'])) {
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute'] . "\" value=\"" . $pran_data['e_minute_name'] . "\"></td>\n";
		}else{
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "<td>分</td>\n";
		$string_data .= "<td>&ensp;</td>\n";
		if (isset($pran_data['other_action'])) {
			$string_data .= "<td style=\"padding-left:10px\">その他<input type=\"text\" size=\"15\" maxlength=\"15\" name=\"" . $conf_item['other_action'] . "\" value=\"" . $pran_data['other_action'] . "\"></td>\n";
		}else{
			$string_data .= "<td style=\"padding-left:10px\">その他<input type=\"text\" size=\"15\" maxlength=\"15\" name=\"" . $conf_item['other_action'] . "\" value=\"\"></td>\n";
		}
		$string_data .= "</tr>\n";
		
		$string_data .= "</table>\n";
		$string_data .= "<br>\n";
		
		return $string_data;
	}
	
	
	public function show_delete_data($conf_item = NULL,$pran_data = NULL)
	{
//		log_message('debug',"show_delete_data");
//		log_message('debug',"conf_item for : ");
//		foreach ($conf_item as $key => $value) {
//			log_message('debug',$key . " = " . $value);
//		}
//		
//		log_message('debug',"pran_data for : ");
//		foreach ($pran_data as $key => $value) {
//			log_message('debug',$key . " = " . $value);
//		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$string_data = NULL;
		
		// ボタン項目設定(選択)
		$button_select = $CI->config->item(MY_BUTTON_SELECT);
		
		$string_data .= "<table>\n";
		$string_data .= "<tr>";
		$string_data .= "<td>日付</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['del_s_year'])) {
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_s_year'] . "\" size=\"4\" maxlength=\"4\" value=\"" . $pran_data['del_s_year'] . "\">　年\n";
		}else{
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_s_year'] . "\" size=\"4\" maxlength=\"4\" value=\"\">　年\n";
		}
		if (isset($pran_data['del_s_month'])) {
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_s_month'] . "\" size=\"2\" maxlength=\"2\" value=\"" . $pran_data['del_s_month'] . "\">　月\n";
		}else{
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_s_month'] . "\" size=\"2\" maxlength=\"2\" value=\"\">　月\n";
		}
		if (isset($pran_data['del_s_month'])) {
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_s_day'] . "\" size=\"2\" maxlength=\"2\" value=\"" . $pran_data['del_s_month'] . "\">　日\n";
		}else{
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_s_day'] . "\" size=\"2\" maxlength=\"2\" value=\"\">　日\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "<td>　～　</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['del_e_year'])) {
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_e_year'] . "\" size=\"4\" maxlength=\"4\" value=\"" . $pran_data['del_e_year'] . "\">　年\n";
		}else{
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_e_year'] . "\" size=\"4\" maxlength=\"4\" value=\"\">　年\n";
		}
		if (isset($pran_data['del_e_month'])) {
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_e_month'] . "\" size=\"2\" maxlength=\"2\" value=\"" . $pran_data['del_e_month'] . "\">　月\n";
		}else{
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_e_month'] . "\" size=\"2\" maxlength=\"2\" value=\"\">　月\n";
		}
		if (isset($pran_data['del_e_day'])) {
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_e_day'] . "\" size=\"2\" maxlength=\"2\" value=\"" . $pran_data['del_e_day'] . "\">　日\n";
		}else{
			$string_data .= "<input type=\"text\" name=\"" . $conf_item['del_e_day'] . "\" size=\"2\" maxlength=\"2\" value=\"\">　日\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td>社番</td>\n";
		$string_data .= "<td>\n";
		if (isset($pran_data['del_shbn'])) {
			$string_data .= "<input type=\"text\" class=\"input\" name=\"" . $conf_item['del_shbn'] . "\" size=\"5\" maxlength=\"5\" value=\"" . $pran_data['del_shbn'] . "\" />\n";
		}else{
			$string_data .= "<input type=\"text\" class=\"input\" name=\"" . $conf_item['del_shbn'] . "\" size=\"5\" maxlength=\"5\" value=\"\" />\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "<td>社員名</td>\n";
		$string_data .= "<td>\n";
		if ($pran_data['del_name']) {
			$string_data .= "<input type=\"text\" class=\"input\" name=\"" . $conf_item['del_name'] . "\" size=\"16\" maxlength=\"10\" value=\"" . $pran_data['del_name'] . "\" />\n";
		}else{
			$string_data .= "<input type=\"text\" class=\"input\" name=\"" . $conf_item['del_name'] . "\" size=\"16\" maxlength=\"10\" value=\"\" />\n";
		}
		$string_data .= "</td>\n";
		$string_data .= "<td>\n";
//		$string_data .= $CI->item_manager->set_variable_button_string($button_select) . "\n";
		$string_data .= "<input type=\"button\" name=\"" . $conf_item['del_do_action'] . "\" value=\"選択\" />\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
		
		return $string_data;
		
	}
	
	
	function set_select_table($format_data = NULL)
	{
		if (is_null($format_data)) {
			return NULL;
		}
		// 初期化
		$CI =& get_instance();
//		$CI->load->library('item_manager');
		$string_data = NULL;
		
		$string_data .= "<table style=\"border:1px #000000 solid\" width=\"850px\">\n";
		$string_data .= "<tr>\n";
		$string_data .= "<th style=\"width=80px\">日付</th>\n";
		$string_data .= "<th style=\"width=70px\">区分</th>\n";
		$string_data .= "<th style=\"width=120px\">相手先名</th>\n";
		$string_data .= "<th style=\"width=70px\">区分</th>\n";
		$string_data .= "<th style=\"width=120px\">相手先名</th>\n";
		$string_data .= "<th style=\"width=70px\">区分</th>\n";
		$string_data .= "<th style=\"width=120px\">相手先名</th>\n";
		$string_data .= "<th style=\"width=70px\">区分</th>\n";
		$string_data .= "<th style=\"width=120px\">相手先名</th>\n";
		$string_data .= "</tr>\n";
		foreach ($format_data as $key => $value) {
			$string_data .= "<tr>\n";
			$string_data .= "<td><a href=\"" . $value['url'] . "\">" . substr($value['date'],4,2) . "/" . substr($value['date'],6,2) . "</a></td>\n";
			$string_data .= "<td>" . $value['0']['kubun'] . "</td>\n";
			$string_data .= "<td>" . $value['0']['aitesknm'] . "</td>\n";
			if (isset($value['1']['kubun'])) {
				$string_data .= "<td>" . $value['1']['kubun'] . "</td>\n";
				$string_data .= "<td>" . $value['1']['aitesknm'] . "</td>\n";
			}else{
				$string_data .= "<td></td>\n";
				$string_data .= "<td></td>\n";
			}
			if (isset($value['2']['kubun'])) {
				$string_data .= "<td>" . $value['2']['kubun'] . "</td>\n";
				$string_data .= "<td>" . $value['2']['aitesknm'] . "</td>\n";
			}else{
				$string_data .= "<td></td>\n";
				$string_data .= "<td></td>\n";
			}
			if (isset($value['3']['kubun'])) {
				$string_data .= "<td>" . $value['3']['kubun'] . "</td>\n";
				$string_data .= "<td>" . $value['3']['aitesknm'] . "</td>\n";
			}else{
				$string_data .= "<td></td>\n";
				$string_data .= "<td></td>\n";
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		
		return $string_data;
	}
	
}

// END Plan_table_manager class

/* End of file Plan_table_manager.php */
/* Location: ./application/libraries/plan_table_manager.php */
