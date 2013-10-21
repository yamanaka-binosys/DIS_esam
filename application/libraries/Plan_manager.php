<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Plan_manager {
	
    public $error_date;     // エラーが発生した日付を格納

    public $regular_flag = false;   // 定期予定の判断フラグ(TRUEが定期予定）
    public $regular_ID = NULL;      // 社員番号＋timestampでグループIDを設定する
    
	/**
	 * 
	 */
	function post_check($post_data = NULL,$conf_data = NULL)
	{
		log_message('debug',"========== libraries plan_manager post_check start ==========");
		// 引数チェック
		if(is_null($post_data) OR is_null($conf_data)){
			log_message('debug',"post data is NULL");
			return;
		}
//		log_message('debug'," ===========   post data   ============ ");
//		foreach ($post_data as $key => $value) {
//			log_message('debug',$key . " = " . $value);
//		}
//		log_message('debug'," ===========   post data   ============ ");
		// 選択日付有無確認
		if(isset($post_data['select_day']))
		{
			log_message('debug',"POST select_day = " . $post_data['select_day']);
			$conf_data['day'] = $post_data['select_day'];
		}
		// ファンクション名有無確認
		if(isset($post_data['select_func']))
		{
			log_message('debug',"POST select_func = " . $post_data['select_func']);
			$conf_data['func'] = $post_data['select_func'];
		}
		// 活動区分有無確認
		if(isset($post_data['action_type_0']))
		{
			log_message('debug',"POST action_type_0 = " . $post_data['action_type_0']);
			$conf_data['action_type'] = $post_data['action_type_0'];
			$conf_data['post_check'] = TRUE;
		}
		// 押下ボタン有無確認
		if(isset($post_data['do_action_0']))
		{
			log_message('debug',"POST do_action_0 = " . $post_data['do_action_0']);
			$conf_data['do_action'] = $post_data['do_action_0'];
		}
		// 
		if (isset($post_data['action_type1'])) {
			log_message('debug',"POST action_type1 = " . $post_data['action_type1']);
			$conf_data['change'] = TRUE;
		}
		log_message('debug',"========== libraries plan_manager post_check end ==========");
		return $conf_data;
	}
	/**
	 * 削除タブ選択時、ポストデータチェック
	 * 
	 * 
	 * 
	 */
	function del_post_check($post_data = NULL,$conf_data = NULL)
	{
		log_message('debug',"========== libraries plan_manager del_post_check start ==========");
		// 引数チェック
		if(is_null($post_data) OR is_null($conf_data)){
			log_message('debug',"post data is NULL");
			return;
		}
		// 選択日付有無確認
		if(isset($post_data['select_day']))
		{
			log_message('debug',"POST select_day = " . $post_data['select_day']);
			$conf_data['day'] = $post_data['select_day'];
		}
		// ファンクション名有無確認
		if(isset($post_data['select_func']))
		{
			log_message('debug',"POST select_func = " . $post_data['select_func']);
			$conf_data['func'] = $post_data['select_func'];
		}
		// 選択ボタン押下確認
		if (isset($post_data['do_action']) AND $post_data['do_action'] === 'select') {
			$conf_data['del_existence'] = TRUE;
		}
		log_message('debug',"========== libraries plan_manager del_post_check end ==========");
		return $conf_data;
	}
	
	function get_select_del_post_data($post = NULL,$shbn = NULL)
	{
		if (is_null($post) OR is_null($shbn)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
//		log_message('debug'," ====  post data  ==== ");
//		foreach ($post as $key => $value) {
//			log_message('debug',$key . " = " . $value);
//		}
		
		// 初期化
		$post_data['start_date'] = NULL;
		$post_data['end_date'] = NULL;
		$post_data['shbn'] = NULL;
		$post_data['name'] = NULL;
		
		// 開始日付存在チェック
		if ( ! checkdate((int)$post['del_s_month'],(int)$post['del_s_day'],(int)$post['del_s_year'])) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}else{
//			$post_data['start_date'] = $post['del_s_year'] . $post['del_s_month'] . $post['del_s_day'];
			$post_data['start_date'] = $post['del_s_year'] . sprintf('%02d',$post['del_s_month']) . sprintf('%02d',$post['del_s_day']);
		}
		// 終了日付存在チェック
		if ( ! checkdate((int)$post['del_e_month'],(int)$post['del_e_day'],(int)$post['del_e_year'])) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}else{
//			$post_data['end_date'] = $post['del_e_year'] . $post['del_e_month'] . $post['del_e_day'];
			$post_data['end_date'] = $post['del_e_year'] . sprintf('%02d',$post['del_e_month']) . sprintf('%02d',$post['del_e_day']);
		}
		// 社番チェック
		if ($post['del_shbn'] !== '') {
			$post_data['shbn'] = $post['del_shbn'];
		}
		// 名前チェック
		if ($post['del_name'] !== '') {
			$post_data['name'] = $post['del_name'];
		}
		if ($post['del_shbn'] !== '' AND $post['del_name'] !== '') {
			$post_data['shbn'] = $shbn;
		}
		return $post_data;
	}
	
	function set_new_action_type_string()
	{
		log_message('debug',"========== libraries plan_manager set_new_action_type_string start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->library('plan_table_manager');
		$conf_item = NULL; // 活動区分設定名
		$new_action_type_string = NULL; // 活動区分HTML文字列
		
		// 新規活動区分名取得
		$conf_item = $this->get_new_action_type_data();
//		log_message('debug',"conf_item for : ");
//		foreach ($conf_item as $key => $value) {
//			log_message('debug',$key . " = " . $value);
//		}
		// 新規活動区分HTML取得
		$new_action_type_string = $CI->plan_table_manager->set_new_action_type_table($conf_item);
		
		log_message('debug',"========== libraries plan_manager set_new_action_type_string end ==========");
		return $new_action_type_string;
	}
	
	function get_new_action_type_data()
	{
		log_message('debug',"========== libraries plan_manager get_new_action_type_data start ==========");
		$conf_item = NULL;
		$conf_item['field_id']          = 'plan_field_0';
		$conf_item['field_class']       = 'plan_0';
		$conf_item['action_type_name']  = 'action_type_0';
		$conf_item['action_type_check'] = 'non';
		$conf_item['button_view_name']  = 'do_action_0';
		$conf_item['button_del_name']   = 'do_action_0';
		$conf_item['button_move_name']  = 'do_action_0';
		$conf_item['button_copy_name']  = 'do_action_0';
		log_message('debug',"========== libraries plan_manager get_new_action_type_data end ==========");
		return $conf_item;
	}
	
	function set_plan_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries plan_manager set_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
		// 引数チェック
		if (is_null($shbn) OR is_null($select_day)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('plan_table_manager');
		// DBより選択日の情報を取得し、表示に合う様に変換
		$pran_data = $this->_get_plan_data($shbn,$select_day);
		
		// データ有無チェック
		if ($pran_data['existence'] === FALSE) {
			log_message('debug',"data existence is FALSE");
			return NULL;
		}
		// 表示文字列を作成
//		$plan_data_string = $CI->plan_table_manager->set_table_string($pran_data);
		
		log_message('debug',"========== libraries plan_manager set_plan_data end ==========");
		return $pran_data['data'];
	}
	

	
	
	function set_post_data($conf_data = NULL,$post_data = NULL)
	{
		log_message('debug',"========== libraries plan_manager set_post_data start ==========");
		if (is_null($conf_data) OR is_null($post_data)) {
			log_message('debug',"conf_data or post_data is NULL");
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('plan_table_manager');
		$return_string = NULL;
		$plan_data_count = MY_ZERO;
		
		// ポストデータ取得
		if ($conf_data['change'] === TRUE) {
			foreach ($post_data as $key => $value) {
				$tmp = substr($key, -1);
				$name = substr($key, 0, -1);
				if (is_numeric($tmp)) {
					$view_data[$tmp][$name] = $value;
				}
			}
//			log_message('debug',"=========================================view data");
//			foreach ($view_data as $key => $value) {
//				log_message('debug',$key." = ".$value);
//				foreach ($value as $key2 => $value2) {
//					log_message('debug',$key2." = ".$value2);
//				}
//			}
			// フィールド名とデータを使用してHTMLを作成
			foreach ($view_data as $key => $value) {
				$plan_data_count++;
//				$plan_data_count = $key;
				if (isset($value['action_type'])) {
					if ($value['action_type'] === MY_ACTION_TYPE_HONBU) {
//						if ($value['do_action'] === 'delete') {
//							log_message('debug',"============  action-type = honbu | do-action = delete");
//						}else if ($value['do_action'] === 'move') {
//							log_message('debug',"============  action-type = honbu | do-action = move");
//						}else if ($value['do_action'] === 'copy') {
//							log_message('debug',"============  action-type = honbu | do-action = copy");
//						}
						// フィールド名取得
						$conf_item = $this->get_field_name_honbu($plan_data_count);
						// フィールド名と取得データを使用してHTMLを作成
						$return_string .= $CI->plan_table_manager->set_honbu_table_string($conf_item,$value);
//						$return_string .= $CI->plan_table_manager->set_honbu_table($conf_item,$value);
					}else if ($value['action_type'] === MY_ACTION_TYPE_TENPO) {
						// フィールド名取得
						$conf_item = $this->get_field_name_tenpo($plan_data_count);
						// フィールド名と取得データを使用してHTMLを作成
						$return_string .= $CI->plan_table_manager->set_tenpo_table_string($conf_item,$value);
//						$return_string .= $CI->plan_table_manager->set_tenpo_table($conf_item,$value);
					}else if ($value['action_type'] === MY_ACTION_TYPE_DAIRI) {
						// フィールド名取得
						$conf_item = $this->get_field_name_dairi($plan_data_count);
						// フィールド名と取得データを使用してHTMLを作成
						$return_string .= $CI->plan_table_manager->set_dairi_table_string($conf_item,$value);
//						$return_string .= $CI->plan_table_manager->set_dairi_table($conf_item,$value);
					}else if ($value['action_type'] === MY_ACTION_TYPE_OFFICE) {
						// フィールド名取得
						$conf_item = $this->get_field_name_office($plan_data_count);
						// フィールド名と取得データを使用してHTMLを作成
						$return_string .= $CI->plan_table_manager->set_office_table_string($conf_item,$value);
//						$return_string .= $CI->plan_table_manager->set_office_table($conf_item,$value);
					}
				}
			}
			// 新規登録フィールド
			if (isset($view_data[MY_ZERO]['do_action_'])) {
//				if ($view_data[MY_ZERO]['do_action_'] === 'view') {
				if ($view_data[MY_ZERO]['do_action_'] === '表示') {
					if ($view_data['0']['action_type_'] === MY_ACTION_TYPE_HONBU) {
						$plan_data_count++;
						// フィールド名取得
						$conf_item = $this->get_field_name_honbu($plan_data_count);
						// 空データ取得
						$data_null = $this->set_initial_value(MY_ACTION_TYPE_HONBU);
						$return_string .= $CI->plan_table_manager->set_honbu_table($conf_item,$data_null);
					}else if ($view_data['0']['action_type_'] === MY_ACTION_TYPE_TENPO) {
						$plan_data_count++;
						// フィールド名取得
						$conf_item = $this->get_field_name_tenpo($plan_data_count);
						// 空データ取得
						$data_null = $this->set_initial_value(MY_ACTION_TYPE_TENPO);
						$return_string .= $CI->plan_table_manager->set_tenpo_table($conf_item,$data_null);
					}else if ($view_data['0']['action_type_'] === MY_ACTION_TYPE_DAIRI) {
						$plan_data_count++;
						// フィールド名取得
						$conf_item = $this->get_field_name_dairi($plan_data_count);
						// 空データ取得
						$data_null = $this->set_initial_value(MY_ACTION_TYPE_DAIRI);
						$return_string .= $CI->plan_table_manager->set_dairi_table($conf_item,$data_null);
					}else if ($view_data['0']['action_type_'] === MY_ACTION_TYPE_OFFICE) {
						$plan_data_count++;
						// フィールド名取得
						$conf_item = $this->get_field_name_office($plan_data_count);
						// 空データ取得
						$data_null = $this->set_initial_value(MY_ACTION_TYPE_OFFICE);
						$return_string .= $CI->plan_table_manager->set_office_table($conf_item,$data_null);
					}
//				}else if($view_data[MY_ZERO]['do_action_'] === 'delete'){
				}else if($view_data[MY_ZERO]['do_action_'] === '削除'){
					log_message('debug',"!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! action delete 1");
				}
			}
//		}else if($post_data['do_action_0'] === 'view'){
		}else if($post_data['do_action_0'] === '表示'){
			if ($post_data['action_type_0'] === MY_ACTION_TYPE_HONBU) {
				// フィールド名取得
				$conf_item = $this->get_field_name_honbu(1);
				// 空データ取得
				$data_null = $this->set_initial_value(MY_ACTION_TYPE_HONBU);
				$return_string .= $CI->plan_table_manager->set_honbu_table($conf_item,$data_null);
			}else if ($post_data['action_type_0'] === MY_ACTION_TYPE_TENPO) {
				// フィールド名取得
				$conf_item = $this->get_field_name_tenpo(1);
				// 空データ取得
				$data_null = $this->set_initial_value(MY_ACTION_TYPE_TENPO);
				$return_string .= $CI->plan_table_manager->set_tenpo_table($conf_item,$data_null);
			}else if ($post_data['action_type_0'] === MY_ACTION_TYPE_DAIRI) {
				// フィールド名取得
				$conf_item = $this->get_field_name_dairi(1);
				// 空データ取得
				$data_null = $this->set_initial_value(MY_ACTION_TYPE_DAIRI);
				$return_string .= $CI->plan_table_manager->set_dairi_table($conf_item,$data_null);
			}else if ($post_data['action_type_0'] === MY_ACTION_TYPE_OFFICE) {
				// フィールド名取得
				$conf_item = $this->get_field_name_office(1);
				// 空データ取得
				$data_null = $this->set_initial_value(MY_ACTION_TYPE_OFFICE);
				$return_string .= $CI->plan_table_manager->set_office_table($conf_item,$data_null);
			}
		}else if($post_data['do_action_0'] === 'delete'){
			log_message('debug',"!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! action delete 0");
		}
		log_message('debug',"========== libraries plan_manager set_post_data end ==========");
		return $return_string;
	}
	
	function get_header_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries plan_manager get_header_data start ==========");
		if (is_null($shbn) OR is_null($select_day)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('common');
		
		$header_data = $CI->common->get_header_plan_data($shbn,$select_day);
		if (is_null($header_data)) {
			$ret_data['0']['time'] = '';
			$ret_data['0']['kubun'] = '';
			$ret_data['0']['aitesknm'] = '';
		}else{
			$count = MY_ZERO;
			foreach ($header_data as $key => $value) {
				$start = substr($value['sthm'],0,2) . ":" . substr($value['sthm'],2,2);
				$end = substr($value['edhm'],0,2) . ":" . substr($value['edhm'],2,2);
				$ret_data[$count]['time'] = $start . "～" . $end;
				$ret_data[$count]['kubun'] = $value['kubun'];
				$ret_data[$count]['aitesknm'] = $value['aitesknm'];
				$count++;
			}
		}
		log_message('debug',"========== libraries plan_manager get_header_data end ==========");
		return $ret_data;
	}
	
	function get_header_day($select_day = NULL)
	{
		log_message('debug',"========== libraries plan_manager get_header_day start ==========");
		if (is_null($select_day)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$week_ja = $CI->config->item('c_day_week_ja');
		$year = substr($select_day,0,4);
		$month = substr($select_day,4,2);
		$day = substr($select_day,6,2);
		$weekday = $week_ja[date("w", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day, $year))]; // 曜日取得（日本語）
		
		$ret_data['year'] = $year;
		$ret_data['month'] = $month;
		$ret_data['day'] = $day;
		$ret_data['weekday'] = $weekday;
		
		log_message('debug',"========== libraries plan_manager get_header_day end ==========");
		return $ret_data;
	}
	
	/**
	 * 社番・日付から予定情報をDBより取得し、整形した後返す
	 * 
	 * @access private
	 * @param  string $shbn 社番
	 * @param  string $select_day 日付
	 * @return array $return_data 整形済予定情報
	 */
	private function _get_plan_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries plan_manager _get_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
			
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('common','srntb110','srntb120','srntb130','srntb140','srntb160'));
		$result       = FALSE;   // チェック結果
		$plan_data    = NULL;    // 予定情報
		$return_data  = NULL;    // 整形済み予定情報
//		$office_count = MY_ZERO; // 内勤件数
		
		// 予定情報をテーブルより取得
		$summary_data = $CI->common->get_select_plan_data($shbn,$select_day);

		$return_data['count'] = $summary_data['count']; // 取得データ件数
		log_message('debug',"summary_data count = " . $summary_data['count']);
		if($summary_data['count'] == MY_ZERO){
			$return_data['existence'] = FALSE;
			log_message('debug',"existence = FALSE");
			return $return_data;
		}else{
			$return_data['existence'] = TRUE;
			log_message('debug',"existence = TRUE");
		}
		// データ取得件数分回して表示データを取得（個別）
		$action_cnt = 1;
		foreach($summary_data['data'] as $key => $value){
			$count = sprintf('%02d', $action_cnt);
			log_message('debug',"dbname is " . $value['dbname']);
			
			// 本部
			if($value['dbname'] === 'srntb110'){
				// DBよりデータ取得
				$plan_data = $CI->srntb110->get_srntb110_data($value);
				if( ! is_null($plan_data)){
					// アクションタイプ設定
					$plan_data['0']['action_type'] = MY_ACTION_TYPE_HONBU;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB110');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $plan_data['0']);
				}

			// 店舗
			}else if($value['dbname'] === 'srntb120'){
				// DBよりデータ取得
				$plan_data = $CI->srntb120->get_srntb120_data($value);
				if( ! is_null($plan_data)){
					// アクションタイプ設定
					$plan_data['0']['action_type'] = MY_ACTION_TYPE_TENPO;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB120');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $plan_data['0']);
				}
				
			// 代理店
			}else if($value['dbname'] === 'srntb130'){
				// DBよりデータ取得
				$plan_data = $CI->srntb130->get_srntb130_data($value);
				if( ! is_null($plan_data)){
					// アクションタイプ設定
					$plan_data['0']['action_type'] = MY_ACTION_TYPE_DAIRI;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB130');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $plan_data['0']);
					
				}
				
			// 内勤
			}else if($value['dbname'] === 'srntb140'){
				// DBよりデータ取得
				$plan_data = $CI->srntb140->get_srntb140_data($value);
				if( ! is_null($plan_data)){
					// アクションタイプ設定
					$plan_data['0']['action_type'] = MY_ACTION_TYPE_OFFICE;
					
					// データマージ
					$return_data['data'][$key] = $plan_data['0'];
					
					$CI->load->library('item_manager');
					// 作業内容
//					$tag_name = 'sagyoniyo_'.$action_cnt;
					$tag_name = 'sagyoniyo_'.$count;
					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_in_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
				}
			// 業者
			}else if($value['dbname'] === 'srntb160'){
				// DBよりデータ取得
				$plan_data = $CI->srntb160->get_srntb160_data($value);
				
				if( ! is_null($plan_data)){
					// アクションタイプ設定
					$plan_data['0']['action_type'] = MY_ACTION_TYPE_GYOUSYA;
					// 設定
					$return_data['data'][$key] = $plan_data['0'];
				}
			}
//			$return_data['data'][$key]['count'] = $action_cnt;
			$return_data['data'][$key]['count'] = $count;
			$action_cnt++;
		}
				
//		log_message('debug',"return_data for : ");
//		foreach ($return_data['data'] as $key1 => $value1) {
//			foreach ($value1 as $key2 => $value2) {
//				log_message('debug',$key2 . " = " . $value2);
//			}
//		}
		log_message('debug',"========== libraries plan_manager _get_plan_data end ==========");
		return $return_data;
	}
	

	
	
	function set_select_table($del_data = NULL)
	{
		log_message('debug',"========== libraries plan_manager set_select_table start ==========");
		// 引数チェック
		if (is_null($del_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->loaditemdspname->library('plan_table_manager');
		
		$format_data = $this->formatting_select_data($del_data);
		$return_string = $CI->plan_table_manager->set_select_table($format_data);
		log_message('debug',"========== libraries plan_manager set_select_table end ==========");
		return $return_string;
	}
	
	function formatting_select_data($del_data)
	{
		log_message('debug',"========== libraries plan_manager formatting_select_data start ==========");
		// 初期化
		$CI =& get_instance();
		$base_url = $CI->config->item('base_url');
		$fomat_data = NULL;
		
		$count = MY_ZERO;
		$data_count = MY_ZERO;
		foreach ($del_data['data'] as $key => $value) {
			if ($count === MY_ZERO) {
				$fomat_data[$count]['shbn'] = $value['shbn'];
				$fomat_data[$count]['date'] = $value['ymd'];
//				$fomat_data[$count]['date'] = substr($value['ymd'],4,2) . "/" . substr($value['ymd'],6,2);
				$fomat_data[$count]['url']  = $base_url . "index.php/plan/auth/" . $value['ymd'] . "/" . $value['shbn'];
				$fomat_data[$count][$data_count]['kubun'] = $value['kubun'];
				$fomat_data[$count][$data_count]['aitesknm'] = $value['aitesknm'];
				$count++;
				$data_count++;
			}else if($fomat_data[$count -1]['shbn'] === $value['shbn'] AND $fomat_data[$count -1]['date'] === $value['ymd']){
				$fomat_data[$count -1][$data_count]['kubun'] = $value['kubun'];
				$fomat_data[$count -1][$data_count]['aitesknm'] = $value['aitesknm'];
				$data_count++;
			}else{
				$data_count = MY_ZERO;
				$fomat_data[$count]['shbn'] = $value['shbn'];
				$fomat_data[$count]['date'] = $value['ymd'];
//				$fomat_data[$count]['date'] = substr($value['ymd'],4,2) . "/" . substr($value['ymd'],6,2);
				$fomat_data[$count]['url']  = $base_url . "index.php/plan/auth/" . $value['ymd'] . "/" . $value['shbn'];
				$fomat_data[$count][$data_count]['kubun'] = $value['kubun'];
				$fomat_data[$count][$data_count]['aitesknm'] = $value['aitesknm'];
				$count++;
			}
		}
		log_message('debug',"========== libraries plan_manager formatting_select_data end ==========");
		return $fomat_data;
	}
	
	
	/**
	 * 
	 */
	function set_del_string($post_data = NULL)
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->library('plan_table_manager');
		
		// フィールド名取得
		$conf_item = $this->get_field_name_del();
		
		if ($post_data['del_existence'] === FALSE) {
			// 空の値を取得
			$data_value = $this->set_initial_value('del');
		}else{
			$tmp = $this->set_initial_value('del');
			// データ整形
			$data_value = $this->formatting_data($post_data,$tmp,'del');
		}
//		log_message('debug',"-----------------------------------------");
//		foreach ($data_value as $key => $value) {
//			log_message('debug',$key . " = " . $value);
//		}
//		log_message('debug',"-----------------------------------------");
		// フィールド名と取得データを使用してHTMLを作成
		$return_string = $CI->plan_table_manager->show_delete_data($conf_item,$data_value);
		return $return_string;
		// 表示カラム名取得
		// データ取得（ポストデータ）
		// HTML-STRING取得
	}
	
	function set_day_link($select_day = NULL)
	{
		if (is_null($select_day)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('plan_table_manager');
		$year = substr($select_day,0,4);
		$month = substr($select_day,4,2);
		$day = substr($select_day,6,4);
		$next_day = date("Ymd", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+1, $year));
		$before_day = date("Ymd", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day-1, $year));
		
		$base_url = $CI->config->item('base_url');
		
		$link_day['next_day'] = $base_url . "index.php/plan/index/" . $next_day;
		$link_day['before_day'] = $base_url . "index.php/plan/index/" . $before_day;
		
		return $link_day;
	}
	
	/**
	 * 登録・変更タブの文字列を取得
	 * 
	 * @access public
	 * @param  string $select_day 選択日付（YYYYMMDD）
	 * @return string $tab_data HTML-STRING文字列
	 */
	public function get_tab_data($select_day)
	{
		log_message('debug',"========== Plan_manager get_tab_data start ==========");
		log_message('debug',"\$select_day = $select_day");
		
		// 引数チェック
		if( ! isset($select_day))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('tab_manager');
		$tab_data = NULL;
		$plan_name = MY_PLAN . "/";
		log_message('debug',"\$plan_name = $plan_name");
		// タブ用HTML-STRING取得
		$tab_data = $CI->tab_manager->set_tab_ad($plan_name,$select_day);
		
		log_message('debug',"========== Plan_manager get_tab_data end ==========");
		return $tab_data;
	}
	
	/**
	 * 予定情報取得・登録時に必要な初期値を設定する
	 * 
	 * @access public
	 * @param  nothing
	 * @return array $return_array 活動区分に合った連想配列
	 */
	public function set_initial_value($init_name = NULL)
	{
		log_message('debug',"========== Plan_manager set_initial_value start ==========");
		// 引数チェック
		if(is_null($init_name))
		{
			return NULL;
		}
		// 初期化
		$return_array = array();
		
		switch ($init_name) {
			case MY_ACTION_TYPE_HONBU:
				$return_array = array(
					'action_type'       => MY_ACTION_TYPE_HONBU,
					'jyohonum'          => '', // 情報ナンバー
					'edbn'              => '', // 枝番
					'year'              => '', // 年
					'month'             => '', // 月
					'day'               => '', // 日
					'start_hour'        => '', // 開始時
					'start_minute'      => '', // 開始分
					'end_hour'          => '', // 終了時
					'end_minute'        => '', // 終了分
					'shop_name'         => '', // 場所名
					'honbu_kubun'       => '', // 販売店名・地区本部
					'mendan_name1'      => '', // 面談者１
					'mendan_name2'      => '', // 面談者２
					'doukou_name1'      => '', // 同行者１
					'doukou_name2'      => '', // 同行者２
					'honbu_location'    => '', // 場所
					'getsuji_syoudan1'  => '', // 月次商談内容１
					'getsuji_syoudan2'  => '', // 月次商談内容２
					'getsuji_syoudan3'  => '', // 月次商談内容３
					'getsuji_syoudan4'  => '', // 月次商談内容４
					'getsuji_syoudan5'  => '', // 月次商談内容５
					'getsuji_syoudan6'  => '', // 月次商談内容６
					'getsuji_syoudan7'  => '', // 月次商談内容７
					'getsuji_syoudan8'  => '', // 月次商談内容８
					'getsuji_syoudan9'  => '', // 月次商談内容９
					'getsuji_syoudan10' => '', // 月次商談内容１０
					'getsuji_syoudan11' => '', // 月次商談内容１１
					'getsuji_syoudan12' => '', // 月次商談内容１２
					'getsuji_syoudan13' => '', // 月次商談内容１３
					'getsuji_syoudan14' => '', // 月次商談内容１４
					'hanki_teian1'      => '', // 半期提案内容１
					'hanki_teian2'      => '', // 半期提案内容２
					'hanki_teian3'      => '', // 半期提案内容３
					'hanki_teian4'      => '', // 半期提案内容４
					'hanki_teian5'      => '', // 半期提案内容５
					'hanki_teian6'      => '', // 半期提案内容６
					'hanki_teian7'      => '', // 半期提案内容７
					'hanki_teian8'      => '', // 半期提案内容８
					'hanki_teian9'      => '', // 半期提案内容９
					'hanki_teian10'     => '', // 半期提案内容１０
					'hanki_teian11'     => '', // 半期提案内容１１
					'hanki_teian12'     => '', // 半期提案内容１２
					'hanki_teian13'     => '', // 半期提案内容１３
					'hanki_teian14'     => '', // 半期提案内容１４
					'other'             => '', // その他
					'other1'            => '', // その他内容１
					'other2'            => '', // その他内容２
					'other3'            => '', // その他内容３
					'other4'            => '', // その他内容４
					'other5'            => '', // その他内容５
					'category1'         => '', // カテゴリー内容１
					'category2'         => '', // カテゴリー内容２
					'category3'         => '', // カテゴリー内容３
					'category4'         => '', // カテゴリー内容４
					'category5'         => '', // カテゴリー内容５
					'shodan_plan'       => '', // 商談予定
					'other_label1'      => '', // 不明領域１
					'other_label2'      => ''  // 不明領域２
				);
				break;
			case MY_ACTION_TYPE_TENPO:
				$return_array = array(
					'action_type'    => MY_ACTION_TYPE_TENPO,
					'jyohonum'        => '', // 情報ナンバー
					'edbn'            => '', // 枝番
					'year'            => '', // 年
					'month'           => '', // 月
					'day'             => '', // 日
					'start_hour'      => '', // 開始時
					'start_minute'    => '', // 開始分
					'end_hour'        => '', // 終了時
					'end_minute'      => '', // 終了分
					'shop_name'       => '', // 場所名
					'tenpo_kubun'     => '', // 店舗区分
					'mendan_name1'    => '', // 面談者１
					'mendan_name2'    => '', // 面談者２
					'doukou_name1'    => '', // 同行者１
					'doukou_name2'    => '', // 同行者２
					'honbu_location'  => '', // 場所
					'tenpo_syoudan1'  => '', // 店舗商談内容１
					'tenpo_syoudan2'  => '', // 店舗商談内容２
					'tenpo_syoudan3'  => '', // 店舗商談内容３
					'tenpo_syoudan4'  => '', // 店舗商談内容４
					'tenpo_syoudan5'  => '', // 店舗商談内容５
					'tenpo_syoudan6'  => '', // 店舗商談内容６
					'tenpo_syoudan7'  => '', // 店舗商談内容７
					'tenpo_syoudan8'  => '', // 店舗商談内容８
					'tenpo_syoudan9'  => '', // 店舗商談内容９
					'tenpo_syoudan10' => '', // 店舗商談内容１０
					'tenpo_syoudan11' => '', // 店舗商談内容１１
					'tenpo_syoudan12' => '', // 店舗商談内容１２
					'tenpo_syoudan13' => '', // 店舗商談内容１３
					'tenpo_syoudan14' => '', // 店舗商談内容１４
					'tennai_action1'  => '', // 店内作業内容１
					'tennai_action2'  => '', // 店内作業内容２
					'tennai_action3'  => '', // 店内作業内容３
					'tennai_action4'  => '', // 店内作業内容４
					'tennai_action5'  => '', // 店内作業内容５
					'tennai_action6'  => '', // 店内作業内容６
					'tennai_action7'  => '', // 店内作業内容７
					'tennai_action8'  => '', // 店内作業内容８
					'tennai_action9'  => '', // 店内作業内容９
					'tennai_action10' => '', // 店内作業内容１０
					'tennai_action11' => '', // 店内作業内容１１
					'tennai_action12' => '', // 店内作業内容１２
					'tennai_action13' => '', // 店内作業内容１３
					'tennai_action14' => '', // 店内作業内容１４
					'other'           => '', // その他
					'other1'          => '', // その他内容１
					'other2'          => '', // その他内容２
					'other3'          => '', // その他内容３
					'other4'          => '', // その他内容４
					'other5'          => '', // その他内容５
					'category1'       => '', // カテゴリー内容１
					'category2'       => '', // カテゴリー内容２
					'category3'       => '', // カテゴリー内容３
					'category4'       => '', // カテゴリー内容４
					'category5'       => '', // カテゴリー内容５
					'work_plan'       => ''  // 商談予定
				);
				break;
			case MY_ACTION_TYPE_DAIRI:
				$return_array = array(
					'action_type'    => MY_ACTION_TYPE_DAIRI,
					'jyohonum'       => '', // 情報ナンバー
					'edbn'           => '', // 枝番
					'year'           => '', // 年
					'month'          => '', // 月
					'day'            => '', // 日
					'start_hour'     => '', // 開始時
					'start_minute'   => '', // 開始分
					'end_hour'       => '', // 終了時
					'end_minute'     => '', // 終了分
					'shop_name'      => '', // 場所名
					'dairi_kubun'    => '', // 代理店区分
					'dairi_rank'     => '', // 代理店ランク
					'mendan_name1'   => '', // 面談者１
					'mendan_name2'   => '', // 面談者２
					'doukou_name1'   => '', // 同行者１
					'doukou_name2'   => '', // 同行者２
					'dairi_ippan1'   => '', // 店舗商談内容１
					'dairi_ippan2'   => '', // 店舗商談内容２
					'dairi_ippan3'   => '', // 店舗商談内容３
					'dairi_ippan4'   => '', // 店舗商談内容４
					'dairi_ippan5'   => '', // 店舗商談内容５
					'dairi_ippan6'   => '', // 店舗商談内容６
					'dairi_ippan7'   => '', // 店舗商談内容７
					'dairi_ippan8'   => '', // 店舗商談内容８
					'dairi_ippan9'   => '', // 店舗商談内容９
					'dairi_ippan10'  => '', // 店舗商談内容１０
					'dairi_ippan11'  => '', // 店舗商談内容１１
					'dairi_ippan12'  => '', // 店舗商談内容１２
					'dairi_ippan13'  => '', // 店舗商談内容１３
					'dairi_ippan14'  => '', // 店舗商談内容１４
					'kanri_hanbai1'  => '', // 店内作業内容１
					'kanri_hanbai2'  => '', // 店内作業内容２
					'kanri_hanbai3'  => '', // 店内作業内容３
					'kanri_hanbai4'  => '', // 店内作業内容４
					'kanri_hanbai5'  => '', // 店内作業内容５
					'kanri_hanbai6'  => '', // 店内作業内容６
					'kanri_hanbai7'  => '', // 店内作業内容７
					'kanri_hanbai8'  => '', // 店内作業内容８
					'kanri_hanbai9'  => '', // 店内作業内容９
					'kanri_hanbai10' => '', // 店内作業内容１０
					'kanri_hanbai11' => '', // 店内作業内容１１
					'kanri_hanbai12' => '', // 店内作業内容１２
					'kanri_hanbai13' => '', // 店内作業内容１３
					'kanri_hanbai14' => '', // 店内作業内容１４
					'other'          => '', // その他
					'other1'         => '', // その他内容１
					'other2'         => '', // その他内容２
					'other3'         => '', // その他内容３
					'other4'         => '', // その他内容４
					'other5'         => '', // その他内容５
					'shoudan_plan'   => ''  // 商談予定
				);
				break;
			case MY_ACTION_TYPE_OFFICE:
				$return_array = array(
					'action_type'     => MY_ACTION_TYPE_OFFICE,
					'jyohonum'        => '', // 情報ナンバー
					'edbn'            => '', // 枝番
					'year'            => '', // 年１
					'month'           => '', // 月１
					'day'             => '', // 日１
					'start_hour'      => '', // 開始時１
					'start_minute'    => '', // 開始分１
					'end_hour'        => '', // 終了時１
					'end_minute'      => '', // 終了分１
					'office_work'     => '', // 作業内容１
					'action_result'   => '', // 結果情報１
					'other_action'    => '', // その他作業１
				);
				break;
			case 'del':
				$return_array = array(
					'action_type'         => 'del',
					'del_s_year'          => '',
					'del_s_month'         => '',
					'del_s_day'           => '',
					'del_e_year'          => '',
					'del_e_month'         => '',
					'del_e_day'           => '',
					'del_shbn'            => '',
					'del_name'            => '',
					'del_do_action'       => ''
				);
				break;
			default:
				$return_array = array(
					'select_day'        => '', // 選択日付
					'select_func'       => '', // タブ名
					'action_type'       => '', // 活動区分
					'do_action'         => ''  // 選択ボタン名
				);
				break;
		}
		log_message('debug',"========== Plan_manager set_initial_value end ==========");
		return $return_array;
	}
	
	/**
	 * 
	 * 
	 * 
	 * 
	 * 
	 * 
	 */
	function formatting_data($model_data = NULL,$value_data = NULL,$init_name)
	{
		
		if(is_null($model_data) OR is_null($value_data))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		log_message('debug',"====================================================get data");
		foreach ($value_data as $key => $value) {
			log_message('debug',$key." = ".$value);
		}
		switch ($init_name) {
			case MY_ACTION_TYPE_HONBU:
				// DBの値を取得
				$model_data['jyohonum']          = $value_data['jyohonum'];
				$model_data['edbn']              = $value_data['edbn'];
				$model_data['year']              = substr($value_data['ymd'],0,4);
				$model_data['month']             = substr($value_data['ymd'],4,2);
				$model_data['day']               = substr($value_data['ymd'],6,2);
				$model_data['start_hour']        = substr($value_data['sthm'],0,2);
				$model_data['start_minute']      = substr($value_data['sthm'],2,2);
				$model_data['end_hour']          = substr($value_data['edhm'],0,2);
				$model_data['end_minute']        = substr($value_data['edhm'],2,2);
				$model_data['shop_name']         = $value_data['aitesknm'];
				$model_data['honbu_kubun']       = $value_data['kbncd'];
				$model_data['mendan_name1']      = $value_data['mendannm01'];
				$model_data['mendan_name2']      = $value_data['mendannm02'];
				$model_data['doukou_name1']      = $value_data['doukounm01'];
				$model_data['doukou_name2']      = $value_data['doukounm02'];
				$model_data['honbu_location']    = $value_data['basyo'];
				$model_data['getsuji_syoudan1']  = $value_data['sdn_mitumori'];
				$model_data['getsuji_syoudan2']  = $value_data['sdn_siyokaknin'];
				$model_data['getsuji_syoudan3']  = $value_data['sdn_hnbikekaku'];
				$model_data['getsuji_syoudan4']  = $value_data['sdn_claim'];
				$model_data['getsuji_syoudan5']  = $value_data['sdn_uribatan'];
				$model_data['getsuji_syoudan6']  = $value_data['sdn_gtjyobi01'];
				$model_data['getsuji_syoudan7']  = $value_data['sdn_gtjyobi02'];
				$model_data['getsuji_syoudan8']  = $value_data['sdn_gtjyobi03'];
				$model_data['getsuji_syoudan9']  = $value_data['sdn_gtjyobi04'];
				$model_data['getsuji_syoudan10'] = $value_data['sdn_gtjyobi05'];
				$model_data['hanki_teian1']      = $value_data['sdn_shohin'];
				$model_data['hanki_teian2']      = $value_data['sdn_donyutan'];
				$model_data['hanki_teian3']      = $value_data['sdn_mdteian']; 
				$model_data['hanki_teian4']      = $value_data['sdn_tanawari'];
				$model_data['hanki_teian5']      = $value_data['sdn_tnwrbi'];
				$model_data['hanki_teian6']      = $value_data['sdn_donyutume'];
				$model_data['hanki_teian7']      = $value_data['sdn_tnwrkeka'];
				$model_data['hanki_teian8']      = $value_data['sdn_hnkyobi01'];
				$model_data['hanki_teian9']      = $value_data['sdn_hnkyobi02'];
				$model_data['hanki_teian10']     = $value_data['sdn_hnkyobi03'];
				$model_data['hanki_teian11']     = $value_data['sdn_hnkyobi04'];
				$model_data['hanki_teian12']     = $value_data['sdn_hnkyobi05'];
				$model_data['other']             = $value_data['other'];
				$model_data['category1']         = $value_data['cte_kamithubunrui'];
				$model_data['category2']         = $value_data['cte_kakoudaibunrui'];
				$model_data['shodan_plan']       = $value_data['sdn_yotei'];
//				$model_data['other_label1']      = $value_data[''];
//				$model_data['other_label2']      = $value_data[''];
				break;
			case MY_ACTION_TYPE_TENPO:
				$model_data['jyohonum']         = $value_data['jyohonum'];
				$model_data['edbn']             = $value_data['edbn'];
				$model_data['year']             = substr($value_data['ymd'],0,4);
				$model_data['month']            = substr($value_data['ymd'],4,2);
				$model_data['day']              = substr($value_data['ymd'],6,2);
				$model_data['start_hour']       = substr($value_data['sthm'],0,2);
				$model_data['start_minute']     = substr($value_data['sthm'],2,2);
				$model_data['end_hour']         = substr($value_data['edhm'],0,2);
				$model_data['end_minute']       = substr($value_data['edhm'],2,2);
				$model_data['shop_name']        = $value_data['aitesknm'];
				$model_data['tenpo_kubun']      = $value_data['tnpkb'];
				$model_data['mendan_name1']     = $value_data['mendannm01'];
				$model_data['mendan_name2']     = $value_data['mendannm02'];
				$model_data['doukou_name1']     = $value_data['doukounm01'];
				$model_data['doukou_name2']     = $value_data['doukounm02'];
				$model_data['tenpo_syoudan1']   = $value_data['ktd_johosusu'];
				$model_data['tenpo_syoudan2']   = $value_data['ktd_johoanai'];
				$model_data['tenpo_syoudan3']   = $value_data['ktd_tnkikosyo'];
				$model_data['tenpo_syoudan4']   = $value_data['ktd_suisnhanbai'];
				$model_data['tenpo_syoudan5']   = $value_data['ktd_jyutyu'];
				$model_data['tenpo_syoudan6']   = $value_data['ktd_tnpyobi01'];
				$model_data['tenpo_syoudan7']   = $value_data['ktd_tnpyobi02'];
				$model_data['tenpo_syoudan8']   = $value_data['ktd_tnpyobi03'];
				$model_data['tenpo_syoudan9']   = $value_data['ktd_tnpyobi04'];
				$model_data['tenpo_syoudan10']  = $value_data['ktd_tnpyobi05'];
				$model_data['tennai_action1']   = $value_data['ktd_satuei'];
				$model_data['tennai_action2']   = $value_data['ktd_mente'];
				$model_data['tennai_action3']   = $value_data['ktd_zaiko'];
				$model_data['tennai_action4']   = $value_data['ktd_hoju'];
				$model_data['tennai_action5']   = $value_data['ktd_hanskseti'];
				$model_data['tennai_action6']   = $value_data['ktd_yamazumi'];
				$model_data['tennai_action7']   = $value_data['ktd_beta'];
				$model_data['tennai_action8']   = $value_data['ktd_sdnigiyobi01'];
				$model_data['tennai_action9']   = $value_data['ktd_sdnigiyobi02'];
				$model_data['tennai_action10']  = $value_data['ktd_sdnigiyobi03'];
				$model_data['tennai_action11']  = $value_data['ktd_sdnigiyobi04'];
				$model_data['tennai_action12']  = $value_data['ktd_sdnigiyobi05'];
				$model_data['other']            = $value_data['other'];
				$model_data['other1']           = $value_data['mr'];
				$model_data['category1']        = $value_data['cte_kamithubunrui'];
				$model_data['category2']        = $value_data['cte_kakoudaibunrui'];
				$model_data['work_plan']        = $value_data['sagyo_yotei'];
//				$model_data['other_label1']     = $value_data['yobim01'];
//				$model_data['other_label2']     = $value_data['yobim02'];
				break;
			case MY_ACTION_TYPE_DAIRI:
				$model_data['jyohonum']         = $value_data['jyohonum'];
				$model_data['edbn']             = $value_data['edbn'];
				$model_data['year']             = substr($value_data['ymd'],0,4);
				$model_data['month']            = substr($value_data['ymd'],4,2);
				$model_data['day']              = substr($value_data['ymd'],6,2);
				$model_data['start_hour']       = substr($value_data['sthm'],0,2);
				$model_data['start_minute']     = substr($value_data['sthm'],2,2);
				$model_data['end_hour']         = substr($value_data['edhm'],0,2);
				$model_data['end_minute']       = substr($value_data['edhm'],2,2);
				$model_data['shop_name']        = $value_data['aitesknm'];
				$model_data['dairi_kubun']      = $value_data['kbncd'];
				$model_data['dairi_rank']       = $value_data['rnkcd'];
				$model_data['mendan_name1']     = $value_data['mendannm01'];
				$model_data['mendan_name2']     = $value_data['mendannm02'];
				$model_data['doukou_name1']     = $value_data['doukounm01'];
				$model_data['doukou_name2']     = $value_data['doukounm02'];
				$model_data['dairi_ippan1']     = $value_data['sdn_mitsumorifollow'];
				$model_data['dairi_ippan2']     = $value_data['sdn_syouhin'];
				$model_data['dairi_ippan3']     = $value_data['sdn_kikaku'];
				$model_data['dairi_ippan4']     = $value_data['sdn_jiseki'];
				$model_data['dairi_ippan5']     = $value_data['sdn_yobi01'];
				$model_data['dairi_ippan6']     = $value_data['sdn_yobi02'];
				$model_data['dairi_ippan7']     = $value_data['sdn_yobi03'];
				$model_data['dairi_ippan8']     = $value_data['sdn_yobi04'];
				$model_data['dairi_ippan9']     = $value_data['sdn_yobi05'];
//				$model_data['dairi_ippan10']    = $value_data[''];
				$model_data['kanri_hanbai1']    = $value_data['sdn_mitsumori'];
				$model_data['kanri_hanbai2']    = $value_data['sdn_jizenutiawase'];
				$model_data['kanri_hanbai3']    = $value_data['sdn_kikakujyoukyou'];
				$model_data['kanri_hanbai4']    = $value_data['sdn_kanriyobi01'];
				$model_data['kanri_hanbai5']    = $value_data['sdn_kanriyobi02'];
				$model_data['kanri_hanbai6']    = $value_data['sdn_kanriyobi03'];
				$model_data['kanri_hanbai7']    = $value_data['sdn_kanriyobi04'];
				$model_data['kanri_hanbai8']    = $value_data['sdn_kanriyobi05'];
				$model_data['other']            = $value_data['sdn_sonota'];
				$model_data['other1']           = $value_data['sdn_logistics'];
				$model_data['other2']           = $value_data['sdn_torikmi'];
				$model_data['shoudan_plan']     = $value_data['sdn_yotei'];
//				$model_data['other_label1']     = $value_data['yobim01'];
//				$model_data['other_label2']     = $value_data['yobim02'];
				break;
			case MY_ACTION_TYPE_OFFICE:
				$model_data['jyohonum']         = $value_data['jyohonum'];
				$model_data['edbn']             = $value_data['edbn'];
				$model_data['year']             = substr($value_data['ymd'],0,4);
				$model_data['month']            = substr($value_data['ymd'],4,2);
				$model_data['day']              = substr($value_data['ymd'],6,2);
				$model_data['start_hour']       = substr($value_data['sthm'],0,2);
				$model_data['start_minute']     = substr($value_data['sthm'],2,2);
				$model_data['office_work']      = $value_data['sagyoniyo'];
				$model_data['action_result']    = $value_data['kekka'];
				$model_data['end_hour']         = substr($value_data['edhm'],0,2);
				$model_data['end_minute']       = substr($value_data['edhm'],2,2);
				$model_data['other_action']     = $value_data['sntsagyo'];
//				$model_data['other_label1']     = $value_data['yobim01'];
//				$model_data['other_label2']     = $value_data['yobim02'];
				break;
			case 'del':
				$model_data['del_s_year']          = $value_data['del_s_year'];
				$model_data['del_s_month']         = $value_data['del_s_month'];
				$model_data['del_s_day']           = $value_data['del_s_day'];
				$model_data['del_e_year']          = $value_data['del_e_year'];
				$model_data['del_e_month']         = $value_data['del_e_month'];
				$model_data['del_e_day']           = $value_data['del_e_day'];
				$model_data['del_shbn']            = $value_data['del_shbn'];
				$model_data['del_name']            = $value_data['del_name'];
				$model_data['del_do_action']       = $value_data['del_do_action'];
				break;
			default:
				//
				break;
		}
//		log_message('debug',"----------------   test2   -----------------------");
//		foreach ($model_data as $key1 => $value1) {
//			log_message('debug',$key1 . " = " . $value1);
//		}
//		log_message('debug',"----------------   test2   -----------------------");
		
		return $model_data;
	}
	
	function get_honbu_db_variable_name(){
		$db_variable_name = array(
			'jyohonum',
			'edbn',
			'sth',
			'stm',
			'edh',
			'edm',
			'aitesknm',
			'aiteskrank',
			'mendannm01',
			'mendannm02',
			'doukounm',
			'basyo',
			'sdn_mitumori',
			'sdn_siyokaknin',
			'sdn_hnbikekaku',
			'sdn_claim',
			'sdn_uribatan',
			'sdn_gtjyobi01',
			'sdn_gtjyobi02',
			'sdn_gtjyobi03',
			'sdn_gtjyobi04',
			'sdn_gtjyobi05',
			'sdn_cte_tessue',
			'sdn_cte_toilet',
			'sdn_cte_kitchen',
			'sdn_cte_wipe',
			'sdn_cte_baby',
			'sdn_cte_feminine',
			'sdn_cte_silver',
			'sdn_cte_mask',
			'sdn_cte_pet',
			'sdn_cte_yobi01',
			'sdn_cte_yobi02',
			'sdn_cte_yobi03',
			'sdn_cte_yobi04',
			'sdn_cte_yobi05',
			'other',
			'sdn_hnktan',
			'sdn_shohin',
			'sdn_tnwrkeka',
			'sdn_donyutan',
			'sdn_mdteian',
			'sdn_tanawari',
			'sdn_tnwrbi',
			'sdn_donyutume',
			'sdn_hnkyobi01',
			'sdn_hnkyobi02',
			'sdn_hnkyobi03',
			'sdn_hnkyobi04',
			'sdn_hnkyobi05',
			'hnk_cte_tessue',
			'hnk_cte_silver',
			'hnk_cte_toilet',
			'hnk_cte_mask',
			'hnk_cte_kitchen',
			'hnk_cte_pet',
			'hnk_cte_wipe',
			'hnk_cte_baby',
			'hnk_cte_feminine',
			'hnk_cte_yobi01',
			'hnk_cte_yobi02',
			'hnk_cte_yobi03',
			'hnk_cte_yobi04',
			'hnk_cte_yobi05',
			'sdn_yotei'
		);
		return $db_variable_name;
	}
	
	function get_honbu_variable_name($data_no){
		$db_variable_name = $this->get_honbu_db_variable_name();
		foreach ($db_variable_name as $key => $value) {
			$variable_name[] = $value."_".$data_no;
		}
		return $variable_name;
	}
	
	/**
	 * 活動区分店舗の画面表示時カラム名取得
	 * 
	 */
	function get_field_name_tenpo($data_counter = MY_ZERO)
	{
		// 引数チェック
		if($data_counter === MY_ZERO){
			return NULL;
		}
		// 初期化
		$conf_item = array();
		
		$conf_item['jyohonum']                = 'jyohonum' . $data_counter;
		$conf_item['edbn']                    = 'edbn' . $data_counter;
		$conf_item['field_id']                = 'plan_field' . $data_counter;
		$conf_item['field_class']             = 'plan' . $data_counter;
		$conf_item['action_type_name']        = 'action_type' . $data_counter;
		$conf_item['action_type_check']       = MY_ACTION_TYPE_TENPO;
		$conf_item['button_view_name']        = 'do_action' . $data_counter;
		$conf_item['button_del_name']         = 'do_action' . $data_counter;
		$conf_item['button_move_name']        = 'do_action' . $data_counter;
		$conf_item['button_copy_name']        = 'do_action' . $data_counter;
		$conf_item['drop_tenpo_kubun_name']   = 'tenpo_kubun' . $data_counter;
		$conf_item['label_value1']            = '';
		$conf_item['label_for_name1']         = 'lavel' . $data_counter;
		$conf_item['label_value2']            = '';
		$conf_item['label_for_name2']         = 'lavel' . $data_counter;
		$conf_item['year_name']               = 'year' . $data_counter;
		$conf_item['month_name']              = 'month' . $data_counter;
		$conf_item['day_name']                = 'day' . $data_counter;
		$conf_item['start_hour_name']         = 's_hour_name' . $data_counter;
		$conf_item['start_minute_name']       = 's_minute_name' . $data_counter;
		$conf_item['end_hour_name']           = 'e_hour_name' . $data_counter;
		$conf_item['end_minute_name']         = 'e_minute_name' . $data_counter;
		$conf_item['shop_name']               = 'shop' . $data_counter;
		$conf_item['shop_name_link']          = '/xxxxxx/xxxxxx';
		$conf_item['mendan_name1']            = 'mendan1_name' . $data_counter;
		$conf_item['mendan_name2']            = 'mendan2_name' . $data_counter;
		$conf_item['doukou_name1']            = 'doukou1_name' . $data_counter;
		$conf_item['doukou_name1_link']       = '/xxxxxxxx/xxxxxxxx';
		$conf_item['doukou_name2']            = 'doukou2_name' . $data_counter;
		$conf_item['doukou_name2_link']       = '/xxxxxxxx/xxxxxxxx';
		$conf_item['other_name']              = 'other' . $data_counter;
		$conf_item['other_name1']             = 'other_no' . $data_counter;
		$conf_item['category_name1']          = 'cate1_name' . $data_counter;
		$conf_item['category_name2']          = 'cate2_name' . $data_counter;
		$conf_item['work_plan_name']          = 'shodan_plan' . $data_counter;
		
		return $conf_item;
	}
	
	/**
	 * 活動区分代理店の画面表示時カラム名取得
	 * 
	 */
	function get_field_name_dairi($data_counter = MY_ZERO)
	{
		// 引数チェック
		if($data_counter === MY_ZERO){
			return NULL;
		}
		// 初期化
		$conf_item = array();
		
		$conf_item['jyohonum']                    = 'jyohonum' . $data_counter;
		$conf_item['edbn']                        = 'edbn' . $data_counter;
		$conf_item['field_id']                    = 'plan_field' . $data_counter;
		$conf_item['field_class']                 = 'plan' . $data_counter;
		$conf_item['action_type_name']            = 'action_type' . $data_counter;
		$conf_item['action_type_check']           = MY_ACTION_TYPE_DAIRI;
		$conf_item['button_view_name']            = 'do_action' . $data_counter;
		$conf_item['button_del_name']             = 'do_action' . $data_counter;
		$conf_item['button_move_name']            = 'do_action' . $data_counter;
		$conf_item['button_copy_name']            = 'do_action' . $data_counter;
		$conf_item['drop_dairi_kubun_name']       = 'dairi_kubun' . $data_counter;
		$conf_item['drop_dairi_rank_name']        = 'dairi_rank' . $data_counter;
		$conf_item['label_value1']                = '';
		$conf_item['label_for_name1']             = 'label' . $data_counter;
		$conf_item['label_value2']                = '';
		$conf_item['label_for_name2']             = 'label' . $data_counter;
		$conf_item['year_name']                   = 'year' . $data_counter;
		$conf_item['month_name']                  = 'month' . $data_counter;
		$conf_item['day_name']                    = 'day' . $data_counter;
		$conf_item['start_hour_name']             = 's_hour_name' . $data_counter;
		$conf_item['start_minute_name']           = 's_minute_name' . $data_counter;
		$conf_item['end_hour_name']               = 'e_hour_name' . $data_counter;
		$conf_item['end_minute_name']             = 'e_minute_name' . $data_counter;
		$conf_item['shop_name']                   = 'shop' . $data_counter;
		$conf_item['shop_name_link']              = '/xxxxxx/xxxxxx';
		$conf_item['mendan_name1']                = 'mendan1_name' . $data_counter;
		$conf_item['mendan_name2']                = 'mendan2_name' . $data_counter;
		$conf_item['doukou_name1']                = 'doukou1_name' . $data_counter;
		$conf_item['doukou_name1_link']           = '/xxxxxxxx/xxxxxxxx';
		$conf_item['doukou_name2']                = 'doukou2_name' . $data_counter;
		$conf_item['doukou_name2_link']           = '/xxxxxxxx/xxxxxxxx';
		$conf_item['other_name']                  = 'other' . $data_counter;
		$conf_item['work_plan_name']              = 'work_plan' . $data_counter;
		
		return $conf_item;
	}
	
	/**
	 * 活動区分内勤の画面表示時カラム名取得
	 * 
	 */
	function get_field_name_office($data_counter = MY_ZERO)
	{
		// 引数チェック
		if($data_counter === MY_ZERO){
			return NULL;
		}
		// 初期化
		$conf_item = array();
		
		$conf_item['jyohonum']                = 'jyohonum' . $data_counter;
		$conf_item['edbn']                    = 'edbn' . $data_counter;
		$conf_item['field_id']                = 'plan_field' . $data_counter;
		$conf_item['field_class']             = 'plan' . $data_counter;
		$conf_item['action_type_name']        = 'action_type' . $data_counter;
		$conf_item['action_type_check']       = MY_ACTION_TYPE_OFFICE;
		$conf_item['button_view_name']        = 'do_action' . $data_counter;
		$conf_item['button_del_name']         = 'do_action' . $data_counter;
		$conf_item['button_move_name']        = 'do_action' . $data_counter;
		$conf_item['button_copy_name']        = 'do_action' . $data_counter;
		$conf_item['year']                    = 'year' . $data_counter;
		$conf_item['month']                   = 'month' . $data_counter;
		$conf_item['day']                     = 'day' . $data_counter;
		$conf_item['start_hour']              = 's_hour_name' . $data_counter;
		$conf_item['start_minute']            = 's_minute_name' . $data_counter;
		$conf_item['office_work_name']        = 'office_work' . $data_counter;
		$conf_item['action_result']           = 'result' . $data_counter;
		$conf_item['end_hour']                = 'e_hour_name' . $data_counter;
		$conf_item['end_minute']              = 'e_minute_name' . $data_counter;
		$conf_item['other_action']            = 'other_action' . $data_counter;
//		$conf_item['other_label1']            = 'other1_label' . $data_counter;
//		$conf_item['other_label2']            = 'other2_label' . $data_counter;
		
		return $conf_item;
	}
	
	/**
	 * 削除タブ表示時のフィールド名称を表示
	 * 
	 */
	function get_field_name_del()
	{
		// 初期化
		$conf_item = array();
		
		$conf_item['del_s_year']          = 'del_s_year';
		$conf_item['del_s_month']         = 'del_s_month';
		$conf_item['del_s_day']           = 'del_s_day';
		$conf_item['del_e_year']          = 'del_e_year';
		$conf_item['del_e_month']         = 'del_e_month';
		$conf_item['del_e_day']           = 'del_e_day';
		$conf_item['del_shbn']            = 'del_shbn';
		$conf_item['del_name']            = 'del_name';
		$conf_item['del_do_action']       = 'del_do_action';
		
		return $conf_item;
	}
	
	/**
	 * 本部情報取得
	 * 
	 */
	function get_honbu_data($shbn, $date){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb110');
		return $CI->srntb110->select_plan_data($shbn, $date);
	}
	
	/**
	 * 本部情報登録
	 * 
	 */
	function record_honbu_data($shbn,$honbu_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb110');
		$CI->load->model('srntb170');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		
		// 社番
		$record_data['shbn'] = $shbn;
		// 日付
		$variable_name = "ymd_".$data_no;
		$record_data['ymd'] = $honbu_data[$variable_name];
		// 開始時刻（時）
		$variable_name = "sth_".$data_no;
		$record_data['sthm'] = sprintf('%02d', $honbu_data[$variable_name]);
		// 開始時刻（分）
		$variable_name = "stm_".$data_no;
		$record_data['sthm'] .= sprintf('%02d', $honbu_data[$variable_name]);
		// 終了時刻（時）
		$variable_name = "edh_".$data_no;
		$record_data['edhm'] = sprintf('%02d', $honbu_data[$variable_name]);
		// 終了時刻（分）
		$variable_name = "edm_".$data_no;
		$record_data['edhm'] .= sprintf('%02d', $honbu_data[$variable_name]);
		// 相手先コード
		$variable_name = "aiteskcd_".$data_no;
		$record_data['aiteskcd'] = $honbu_data[$variable_name];
		// 相手先名
		$variable_name = "aitesknm_".$data_no;
		$record_data['aitesknm'] = $honbu_data[$variable_name];
		// 相手先ランク
		$variable_name = "aiteskrank_".$data_no;
		$record_data['aiteskrank'] = $honbu_data[$variable_name];
		// 面談者１
		$variable_name = "mendannm01_".$data_no;
		$record_data['mendannm01'] = $honbu_data[$variable_name];
		// 面談者２
		$variable_name = "mendannm02_".$data_no;
		$record_data['mendannm02'] = $honbu_data[$variable_name];
		// 同行者
		$variable_name = "doukounm01_".$data_no;
		$record_data['doukounm01'] = $honbu_data[$variable_name];
		// 場所
		$variable_name = "basyo_".$data_no;
		$record_data['basyo'] = $honbu_data[$variable_name];
		// 見積り提示
		$variable_name = "sdn_mitumori_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_mitumori'] = '1';
			}
		}
		// 採用企画の確認
		$variable_name = "sdn_siyokaknin_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_siyokaknin'] = '1';
			}
		}
		// 販売計画
		$variable_name = "sdn_hnbikekaku_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_hnbikekaku'] = '1';
			}
		}
		// クレーム対応
		$variable_name = "sdn_claim_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_claim'] = '1';
			}
		}
		// 売場提案
		$variable_name = "sdn_uribatan_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_uribatan'] = '1';
			}
		}
		// 商談月次予備１
		$variable_name = "sdn_gtjyobi01_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_gtjyobi01'] = '1';
			}
		}
		// 商談月次予備２
		$variable_name = "sdn_gtjyobi02_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_gtjyobi02'] = '1';
			}
		}
		// 商談月次予備３
		$variable_name = "sdn_gtjyobi03_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_gtjyobi03'] = '1';
			}
		}
		// 商談月次予備４
		$variable_name = "sdn_gtjyobi04_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_gtjyobi04'] = '1';
			}
		}
		// 商談月次予備５
		$variable_name = "sdn_gtjyobi05_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_gtjyobi05'] = '1';
			}
		}
		// 月次カテゴリーティシュー
		$variable_name = "sdn_cte_tessue_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_tessue'] = '1';
			}
		}
		// 月次カテゴリートイレット
		$variable_name = "sdn_cte_toilet_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_toilet'] = '1';
			}
		}
		// 月次カテゴリーキッチン
		$variable_name = "sdn_cte_kitchen_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_kitchen'] = '1';
			}
		}
		// 月次カテゴリーワイプ
		$variable_name = "sdn_cte_wipe_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_wipe'] = '1';
			}
		}
		// 月次カテゴリーベビー
		$variable_name = "sdn_cte_baby_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_baby'] = '1';
			}
		}
		// 月次カテゴリーフェミニン
		$variable_name = "sdn_cte_feminine_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_feminine'] = '1';
			}
		}
		// 月次カテゴリーシルバー
		$variable_name = "sdn_cte_silver_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_silver'] = '1';
			}
		}
		// 月次カテゴリーマスク
		$variable_name = "sdn_cte_mask_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_mask'] = '1';
			}
		}
		// 月次カテゴリーペット
		$variable_name = "sdn_cte_pet_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_pet'] = '1';
			}
		}
		// 月次カテゴリー予備１
		$variable_name = "sdn_cte_yobi01_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_yobi01'] = '1';
			}
		}
		// 月次カテゴリー予備２
		$variable_name = "sdn_cte_yobi02_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_yobi02'] = '1';
			}
		}
		// 月次カテゴリー予備３
		$variable_name = "sdn_cte_yobi03_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_cte_yobi03'] = '1';
			}
		}
		// その他
		$variable_name = "other_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['other'] = '1';
			}
		}
		// 半期提案（今期、来期）
		$variable_name = "sdn_hnktan_".$data_no;
		$record_data['sdn_hnktan'] = $honbu_data[$variable_name];
		// 商品案内
		$variable_name = "sdn_shohin_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_shohin'] = '1';
			}
		}
		// 棚割結果確認
		$variable_name = "sdn_tnwrkeka_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_tnwrkeka'] = '1';
			}
		}
		// 導入提案
		$variable_name = "sdn_donyutan_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_donyutan'] = '1';
			}
		}
		// ＭＤ提案
		$variable_name = "sdn_mdteian_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_mdteian'] = '1';
			}
		}
		// 棚割提案
		$variable_name = "sdn_tanawari_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_tanawari'] = '1';
			}
		}
		// 販売店の棚割日情報
		$variable_name = "sdn_tnwrbi_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_tnwrbi'] = '1';
			}
		}
		// 導入日の詰め
		$variable_name = "sdn_donyutume_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_donyutume'] = '1';
			}
		}
		// 半期予備１
		$variable_name = "sdn_hnkyobi01_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_hnkyobi01'] = '1';
			}
		}
		// 半期予備２
		$variable_name = "sdn_hnkyobi02_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_hnkyobi02'] = '1';
			}
		}
		// 半期予備３
		$variable_name = "sdn_hnkyobi03_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_hnkyobi03'] = '1';
			}
		}
		// 半期予備４
		$variable_name = "sdn_hnkyobi04_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_hnkyobi04'] = '1';
			}
		}
		// 半期予備５
		$variable_name = "sdn_hnkyobi05_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['sdn_hnkyobi05'] = '1';
			}
		}
		// 半期カテゴリーティシュー
		$variable_name = "hnk_cte_tessue_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_tessue'] = '1';
			}
		}
		// 半期カテゴリートイレット
		$variable_name = "hnk_cte_toilet_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_toilet'] = '1';
			}
		}
		// 半期カテゴリーキッチン
		$variable_name = "hnk_cte_kitchen_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_kitchen'] = '1';
			}
		}
		// 半期カテゴリーワイプ
		$variable_name = "hnk_cte_wipe_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_wipe'] = '1';
			}
		}
		// 半期カテゴリーベビー
		$variable_name = "hnk_cte_baby_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_baby'] = '1';
			}
		}
		// 半期カテゴリーフェミニン
		$variable_name = "hnk_cte_feminine_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_feminine'] = '1';
			}
		}
		// 半期カテゴリーシルバー
		$variable_name = "hnk_cte_silver_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_silver'] = '1';
			}
		}
		// 半期カテゴリーマスク
		$variable_name = "hnk_cte_mask_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_mask'] = '1';
			}
		}
		// 半期カテゴリーペット
		$variable_name = "hnk_cte_pet_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_pet'] = '1';
			}
		}
		// 半期カテゴリー予備１
		$variable_name = "hnk_cte_yobi01_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_yobi01'] = '1';
			}
		}
		// 半期カテゴリー予備２
		$variable_name = "hnk_cte_yobi02_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_yobi02'] = '1';
			}
		}
		// 半期カテゴリー予備３
		$variable_name = "hnk_cte_yobi03_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			if ($honbu_data[$variable_name] === 'on') {
				$record_data['hnk_cte_yobi03'] = '1';
			}
		}
		// 商談予定
		$variable_name = "sdn_yotei_".$data_no;
		$record_data['sdn_yotei'] = $honbu_data[$variable_name];
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		if ( ! empty($honbu_data[$variable_name])) {
			$record_data['kakninshbn'] = $honbu_data[$variable_name];
		}
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		
		$record_data['jyohonum'] = $honbu_data[$jyohonum_name];

		// 情報No取得
		if (empty($honbu_data[$jyohonum_name])) {
			$CI->srntb110->insert_srntb110_data($record_data);
            
            // ここで定期予定か単独予定なのかを判断し srntb170 に登録する
            if ($this->regular_flag){
                $CI->srntb170->insert_srntb170_data('srntb110', $CI->srntb110->latest_jyohonum, $this->regular_ID);
            }
            
            
		}else{
			$CI->srntb110->update_srntb110_data($record_data);
		}
		return;
	}
	

	
	
	function record_tenpo_data($shbn,$tenpo_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb120');
		$CI->load->model('srntb170');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		$aiteskcd = "aiteskcd_".$data_no;
		
		// 社番
		$record_data['shbn'] = $shbn;
		// 日付
		$variable_name = "ymd_".$data_no;
		$record_data['ymd'] = $tenpo_data[$variable_name];
		// 開始時刻（時）
		$variable_name = "sth_".$data_no;
		$record_data['sthm'] = sprintf('%02d', $tenpo_data[$variable_name]);
		// 開始時刻（分）
		$variable_name = "stm_".$data_no;
		$record_data['sthm'] .= sprintf('%02d', $tenpo_data[$variable_name]);
		// 終了時刻（時）
		$variable_name = "edh_".$data_no;
		$record_data['edhm'] = sprintf('%02d', $tenpo_data[$variable_name]);
		// 終了時刻（分）
		$variable_name = "edm_".$data_no;
		$record_data['edhm'] .= sprintf('%02d', $tenpo_data[$variable_name]);
		// 相手先名
		$variable_name = "aitesknm_".$data_no;
		$record_data['aitesknm'] = $tenpo_data[$variable_name];
		// 相手先ランク
		$variable_name = "aiteskrank_".$data_no;
		$record_data['aiteskrank'] = $tenpo_data[$variable_name];
		// 相手先コード
		$variable_name = "aiteskcd_".$data_no;
		$record_data['aiteskcd'] = $tenpo_data[$variable_name];
		// 店舗区分
		if($tenpo_data[$aiteskcd] === 'XXXXXXXX'){
			$record_data['tnpkb'] = '2';
		}else{
			$record_data['tnpkb'] = '1';
		}
		// 面談者１
		$variable_name = "mendannm01_".$data_no;
		$record_data['mendannm01'] = $tenpo_data[$variable_name];
		// 面談者２
		$variable_name = "mendannm02_".$data_no;
		$record_data['mendannm02'] = $tenpo_data[$variable_name];
		// 同行者
		$variable_name = "doukounm01_".$data_no;
		$record_data['doukounm01'] = $tenpo_data[$variable_name];
		// 情報収集
		$variable_name = "ktd_johosusu_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_johosusu'] = '1';
			}
		}
		// 商品情報案内
		$variable_name = "ktd_johoanai_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_johoanai'] = '1';
			}
		}
		// 展開場所・ｱｳﾄ展開交渉
		$variable_name = "ktd_tnkikosyo_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_tnkikosyo'] = '1';
			}
		}
		// 推奨販売交渉
		$variable_name = "ktd_suisnhanbai_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_suisnhanbai'] = '1';
			}
		}
		// 受注促進
		$variable_name = "ktd_jyutyu_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_jyutyu'] = '1';
			}
		}
		// 売場撮影
		$variable_name = "ktd_satuei_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_satuei'] = '1';
			}
		}
		// ﾍﾞﾀ付け
		$variable_name = "ktd_beta_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_beta'] = '1';
			}
		}
		// 売場メンテナンス
		$variable_name = "ktd_mente_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_mente'] = '1';
			}
		}
		// 在庫確認
		$variable_name = "ktd_zaiko_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_zaiko'] = '1';
			}
		}
		// 商品補充
		$variable_name = "ktd_hoju_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_hoju'] = '1';
			}
		}
		// 販促物の設置
		$variable_name = "ktd_hanskseti_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_hanskseti'] = '1';
			}
		}
		// 山積み
		$variable_name = "ktd_yamazumi_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_yamazumi'] = '1';
			}
		}
		// 予備１（店舗商談１）
		$variable_name = "ktd_tnpyobi01_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_tnpyobi01'] = '1';
			}
		}
		// 予備２（店舗商談２）
		$variable_name = "ktd_tnpyobi02_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_tnpyobi02'] = '1';
			}
		}
		// 予備３（店舗商談３）
		$variable_name = "ktd_tnpyobi03_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_tnpyobi03'] = '1';
			}
		}
		// 予備４（店舗商談４）
		$variable_name = "ktd_tnpyobi04_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_tnpyobi04'] = '1';
			}
		}
		// 予備５（店舗商談５）
		$variable_name = "ktd_tnpyobi05_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_tnpyobi05'] = '1';
			}
		}
		// 予備１（商談以外１）
		$variable_name = "ktd_sdnigiyobi01_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_sdnigiyobi01'] = '1';
			}
		}
		// 予備２（商談以外２）
		$variable_name = "ktd_sdnigiyobi02_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_sdnigiyobi02'] = '1';
			}
		}
		// 予備３（商談以外３）
		$variable_name = "ktd_sdnigiyobi03_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_sdnigiyobi03'] = '1';
			}
		}
		// 予備４（商談以外４）
		$variable_name = "ktd_sdnigiyobi04_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_sdnigiyobi04'] = '1';
			}
		}
		// 予備５（商談以外５）
		$variable_name = "ktd_sdnigiyobi05_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['ktd_sdnigiyobi05'] = '1';
			}
		}
		// 競合店調査(MR)
		$variable_name = "mr_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['mr'] = '1';
			}
		}
		// カテゴリーティシュー
		$variable_name = "cte_tessue_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_tessue'] = '1';
			}
		}
		// カテゴリーシルバー
		$variable_name = "cte_silver_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_silver'] = '1';
			}
		}
		// カテゴリートイレット
		$variable_name = "cte_toilet_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_toilet'] = '1';
			}
		}
		// カテゴリーマスク
		$variable_name = "cte_mask_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_mask'] = '1';
			}
		}
		// カテゴリーキッチン
		$variable_name = "cte_kitchen_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_kitchen'] = '1';
			}
		}
		// カテゴリーペット
		$variable_name = "cte_pet_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_pet'] = '1';
			}
		}
		// カテゴリーワイプ
		$variable_name = "cte_wipe_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_wipe'] = '1';
			}
		}
		// カテゴリーベビー
		$variable_name = "cte_baby_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_baby'] = '1';
			}
		}
		// カテゴリーフェミニン
		$variable_name = "cte_feminine_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_feminine'] = '1';
			}
		}
		// 予備１（カテゴリー１）
		$variable_name = "cte_yobi01_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_yobi01'] = '1';
			}
		}
		// 予備２（カテゴリー２）
		$variable_name = "cte_yobi02_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_yobi02'] = '1';
			}
		}
		// 予備３（カテゴリー３）
		$variable_name = "cte_yobi03_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_yobi03'] = '1';
			}
		}
		// 予備４（カテゴリー４）
		$variable_name = "cte_yobi04_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_yobi04'] = '1';
			}
		}
		// 予備５（カテゴリー５）
		$variable_name = "cte_yobi05_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			if ($tenpo_data[$variable_name] === 'on') {
				$record_data['cte_yobi05'] = '1';
			}
		}
		// 作業予定
		$variable_name = "sagyo_yotei_".$data_no;
		$record_data['sagyo_yotei'] = $tenpo_data[$variable_name];
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['kakninshbn'] = $tenpo_data[$variable_name];
		}
		
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		
		$record_data['jyohonum'] = $tenpo_data[$jyohonum_name];

		// 情報No取得
		if (empty($tenpo_data[$jyohonum_name])) {
			$CI->srntb120->insert_srntb120_data($record_data);
            
            // ここで定期予定か単独予定なのかを判断し srntb170 に登録する
            if ($this->regular_flag){
                $CI->srntb170->insert_srntb170_data('srntb120', $CI->srntb120->latest_jyohonum, $this->regular_ID);
            }
            
		}else{
			$CI->srntb120->update_srntb120_data($record_data);
		}
		return;
	}
	
	/**
	 * 代理店データ登録
	 * 
	 */
	function record_dairi_data($shbn,$dairi_data,$data_no)
	{
		// 引数チェック
		if (is_null($dairi_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb130');
		$CI->load->model('srntb170');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		
		// 社番
		$record_data['shbn'] = $shbn;
		// 日付
		$variable_name = "ymd_".$data_no;
		$record_data['ymd'] = $dairi_data[$variable_name];
		// 開始時刻（時）
		$variable_name = "sth_".$data_no;
		$record_data['sthm'] = sprintf('%02d', $dairi_data[$variable_name]);
		// 開始時刻（分）
		$variable_name = "stm_".$data_no;
		$record_data['sthm'] .= sprintf('%02d', $dairi_data[$variable_name]);
		// 終了時刻（時）
		$variable_name = "edh_".$data_no;
		$record_data['edhm'] = sprintf('%02d', $dairi_data[$variable_name]);
		// 終了時刻（分）
		$variable_name = "edm_".$data_no;
		$record_data['edhm'] .= sprintf('%02d', $dairi_data[$variable_name]);
		// 相手先コード
		$variable_name = "aiteskcd_".$data_no;
		$record_data['aiteskcd'] = $dairi_data[$variable_name];
		// 相手先名
		$variable_name = "aitesknm_".$data_no;
		$record_data['aitesknm'] = $dairi_data[$variable_name];
		// 相手先ランク
		$variable_name = "rnkcd_".$data_no;
		$record_data['rnkcd'] = $dairi_data[$variable_name];
		// 面談者１
		$variable_name = "mendannm01_".$data_no;
		$record_data['mendannm01'] = $dairi_data[$variable_name];
		// 面談者２
		$variable_name = "mendannm02_".$data_no;
		$record_data['mendannm02'] = $dairi_data[$variable_name];
		// 同行者
		$variable_name = "doukounm01_".$data_no;
		$record_data['doukounm01'] = $dairi_data[$variable_name];
		//一般店見積り提示・商談フォロー
		$variable_name = "sdn_mitsumorifollow_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_mitsumorifollow'] = '1';
			}
		}
		//商品案内
		$variable_name = "sdn_syouhin_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_syouhin'] = '1';
			}
		}
		//企画案内
		$variable_name = "sdn_kikaku_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kikaku'] = '1';
			}
		}
		//実績報告（経理・配荷）
		$variable_name = "sdn_jiseki_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_jiseki'] = '1';
			}
		}
		// 予備１
		$variable_name = "sdn_yobi01_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_yobi01'] = '1';
			}
		}
		// 予備２
		$variable_name = "sdn_yobi02_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_yobi02'] = '1';
			}
		}
		// 予備３
		$variable_name = "sdn_yobi03_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_yobi03'] = '1';
			}
		}
		// 予備４
		$variable_name = "sdn_yobi04_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_yobi04'] = '1';
			}
		}
		// 予備５
		$variable_name = "sdn_yobi05_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_yobi05'] = '1';
			}
		}
		// 見積り提示
		$variable_name = "sdn_mitsumori_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_mitsumori'] = '1';
			}
		}
		// 販売店商談事前打合せ
		$variable_name = "sdn_jizenutiawase_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_jizenutiawase'] = '1';
			}
		} 
		// 情報収集・企画導入状況確認
		$variable_name = "sdn_kikakujyoukyou_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kikakujyoukyou'] = '1';
			}
		}
		// 予備１
		$variable_name = "sdn_kanriyobi01_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kanriyobi01'] = '1';
			}
		}
		// 予備２
		$variable_name = "sdn_kanriyobi02_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kanriyobi02'] = '1';
			}
		}
		// 予備３
		$variable_name = "sdn_kanriyobi03_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kanriyobi03'] = '1';
			}
		}
		// 予備４
		$variable_name = "sdn_kanriyobi04_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kanriyobi04'] = '1';
			}
		}
		// 予備５
		$variable_name = "sdn_kanriyobi05_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_kanriyobi05'] = '1';
			}
		}
		// 受発注・物流関連
		$variable_name = "sdn_logistics_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_logistics'] = '1';
			}
		}
		// 取組会議
		$variable_name = "sdn_torikmi_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			if ($dairi_data[$variable_name] === 'on') {
				$record_data['sdn_torikmi'] = '1';
			}
		}
		// 商談予定
		$variable_name = "sdn_yotei_".$data_no;
		$record_data['sdn_yotei'] = $dairi_data[$variable_name];
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		if ( ! empty($dairi_data[$variable_name])) {
			$record_data['kakninshbn'] = $dairi_data[$variable_name];
		}
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		
		// 情報ナンバー
		$record_data['jyohonum'] = $dairi_data[$jyohonum_name];

		// 情報No取得
		if (empty($dairi_data[$jyohonum_name])) {
			$CI->srntb130->insert_srntb130_data($record_data);
            
            // ここで定期予定か単独予定なのかを判断し srntb170 に登録する
            if ($this->regular_flag){
                $CI->srntb170->insert_srntb170_data('srntb130', $CI->srntb130->latest_jyohonum, $this->regular_ID);
            }
            
		}else{
			$CI->srntb130->update_srntb130_data($record_data);
		}
		return;
	}
	
	/**
	 * 内勤データ登録
	 * 
	 */
	function record_office_data($shbn,$office_data,$data_no)
	{
		// 引数チェック
		if (is_null($office_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb140');
		$CI->load->model('srntb170');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
	
		// 枝番
		$record_data['edbn'] = $office_data[$edbn_name];
		// 社番
		$record_data['shbn'] = $shbn;
		// 日付
		$variable_name = "ymd_".$data_no;
		$record_data['ymd'] = $office_data[$variable_name];
		// 開始時刻（時）
		$variable_name = "sth_".$data_no;
		$record_data['sthm'] = sprintf('%02d', $office_data[$variable_name]);
		// 開始時刻（分）
		$variable_name = "stm_".$data_no;
		$record_data['sthm'] .= sprintf('%02d', $office_data[$variable_name]);
		// 終了時刻（時）
		$variable_name = "edh_".$data_no;
		$record_data['edhm'] = sprintf('%02d', $office_data[$variable_name]);
		// 終了時刻（分）
		$variable_name = "edm_".$data_no;
		$record_data['edhm'] .= sprintf('%02d', $office_data[$variable_name]);

		// 作業内容
		$variable_name = "sagyoniyo_".$data_no;
		$record_data['sagyoniyo'] = $office_data[$variable_name];

		// その他作業
		$variable_name = "sntsagyo_".$data_no;
		$record_data['sntsagyo'] = $office_data[$variable_name];

		// 結果情報
		$variable_name = "kekka_".$data_no;
		$record_data['kekka'] = $office_data[$variable_name];
		
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		if ( ! empty($office_data[$variable_name])) {
			$record_data['kakninshbn'] = $office_data[$variable_name];
		}
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		
		// 情報ナンバー
		$record_data['jyohonum'] = $office_data[$jyohonum_name];

		// 情報No取得
		if (empty($office_data[$jyohonum_name])) {
			$CI->srntb140->insert_srntb140_data($record_data);
            
            // ここで定期予定か単独予定なのかを判断し srntb170 に登録する
            if ($this->regular_flag){
                $CI->srntb170->insert_srntb170_data('srntb140', $CI->srntb140->latest_jyohonum, $this->regular_ID);
            }
            
		}else{
			$CI->srntb140->update_srntb140_data($record_data);
		}
		return;
	}



	/**
	 * 業者データ登録
	 * 
	 */
	function record_gyousya_data($shbn,$gyousya_data,$data_no)
	{
		// 引数チェック
		if (is_null($gyousya_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb160');
		$CI->load->model('srntb170');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		
		// 社番
		$record_data['shbn'] = $shbn;
		// 日付
		$variable_name = "ymd_".$data_no;
		$record_data['ymd'] = $gyousya_data[$variable_name];
		// 開始時刻（時）
		$variable_name = "sth_".$data_no;
		$record_data['sthm'] = sprintf('%02d', $gyousya_data[$variable_name]);
		// 開始時刻（分）
		$variable_name = "stm_".$data_no;
		$record_data['sthm'] .= sprintf('%02d', $gyousya_data[$variable_name]);
		// 終了時刻（時）
		$variable_name = "edh_".$data_no;
		$record_data['edhm'] = sprintf('%02d', $gyousya_data[$variable_name]);
		// 終了時刻（分）
		$variable_name = "edm_".$data_no;
		$record_data['edhm'] .= sprintf('%02d', $gyousya_data[$variable_name]);
		// メモ
		$variable_name = "memo_".$data_no;
		$record_data['memo'] = $gyousya_data[$variable_name];
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		if ( ! empty($gyousya_data[$variable_name])) {
			$record_data['kakninshbn'] = $gyousya_data[$variable_name];
		}
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		
		// 情報ナンバー
		$record_data['jyohonum'] = $gyousya_data[$jyohonum_name];
		
		// 情報No取得
		if (empty($gyousya_data[$jyohonum_name])) {
			$CI->srntb160->insert_srntb160_data($record_data);
            
            // ここで定期予定か単独予定なのかを判断し srntb170 に登録する
            if ($this->regular_flag){
                $CI->srntb170->insert_srntb170_data('srntb160', $CI->srntb160->latest_jyohonum, $this->regular_ID);
            }
            
		}else{
			$CI->srntb160->update_srntb160_data($record_data);
		}
		
		return;
	}


	
	/**
	 * 予備項目取得
	 */
	function get_view_item($use_db = NULL){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('sgmtb041');
		$res = FALSE;
		
		// 引数チェック
		if(is_null($use_db)){
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		
		$db_data = $CI->sgmtb041->plan_view_setting(PLAN_VIEW_ITEM,$use_db);
		if($db_data === FALSE){
			log_message('debug',"Exception db_data = NULL");
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		foreach ($db_data as $key => $value) {
			$res['vi_'.$value['dbitem']]['itemdspname'] = $value['itemdspname'];
			$res['vi_'.$value['dbitem']]['dispflg'] = $value['dispflg'];
		}
		return $res;
	}
	
	function action_move($shbn,$move_day,$jyohonum,$edbn,$post_data,$data_no){
		
		// 情報No初期化
		$variable_name = "jyohonum_".$data_no;
		$post_data[$variable_name] = "";
		// 枝番初期化
		$variable_name = "edbn_".$data_no;
		$post_data[$variable_name] = "";
		// 日付初期化
		$variable_name = "ymd_".$data_no;
		$post_data[$variable_name] = $move_day;
		
		// 活動区分取得
		$variable_name = "action_type_".$data_no;
		$action_type = $post_data[$variable_name];
		if($action_type === 'srntb110'){
			// 本部データ登録
			$this->record_honbu_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb120'){
			// 店舗データ登録
			$this->record_tenpo_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb130'){
			// 代理店データ登録
			$this->record_dairi_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb140'){
			// 内勤データ登録
			$this->record_office_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb160'){
			// 業者データ登録
			$this->record_gyousya_data($shbn,$post_data,$data_no);
		}
		// 登録済の予定削除
		if(!empty($jyohonum)){
			$this->delete_action($action_type,$jyohonum,$edbn);
		}
		
		return;
	}
	
	function delete_action($action_type,$jyohonum,$edbn){
		if($action_type === 'srntb110'){
			// 本部データ削除
			$this->delete_honbu_data($jyohonum,$edbn);
		}else if($action_type === 'srntb120'){
			// 店舗データ削除
			$this->delete_tenpo_data($jyohonum,$edbn);
		}else if($action_type === 'srntb130'){
			// 代理店データ削除
			$this->delete_dairi_data($jyohonum,$edbn);
		}else if($action_type === 'srntb140'){
			// 内勤データ削除
			$this->delete_office_data($jyohonum,$edbn);
		}else if($action_type === 'srntb160'){
			// 業者データ削除
			$this->delete_gyousya_data($jyohonum,$edbn);
		}
	}



	
	function delete_honbu_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb110');
		
		$CI->srntb110->delete_srntb110_data($jyohonum,$edbn);
		
		return;
	}

	
	function delete_tenpo_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb120');
		
		$CI->srntb120->delete_srntb120_data($jyohonum,$edbn);
		
		return;
	}
	

	
	function delete_dairi_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb130');
		
		$CI->srntb130->delete_srntb130_data($jyohonum,$edbn);
		
		return;
	}


	
	function delete_office_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb140');
		
		$CI->srntb140->delete_srntb140_data($jyohonum,$edbn);
		
		return;
	}
	


	function delete_gyousya_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb160');
		
		$CI->srntb160->delete_srntb160_data($jyohonum,$edbn);
		
		return;
	}

	
	function get_plan_new_data($post){
		log_message('debug',"========= plan get_plan_new_data start =========");
		$value_name = NULL;
		$no = 0;
		$add_data = NULL;
		$pos = 0;
		if(count($post) > 0){
			// DB登録用データ変換
			foreach($post as $key => $value){
				if($key !== 'select_day'){
					// 業務区分なし以外
					if($key !== 'action_type_00'){
						$val_length = strlen($key);
						// カラム名取り出し
						$value_name = substr($key,0,$val_length -3);
						// 何番目かを取得
						$no = substr($key,-2,2);
						switch($value_name){
							// 時間
							case 'sth':
								$add_data[(int)$no]['sthm'] = sprintf('%02d',($value)); 
								break;
							case 'stm':
								$add_data[(int)$no]['sthm'] .= sprintf('%02d',((int)$value));
								break;
							case 'edh':
								$add_data[(int)$no]['edhm'] = sprintf('%02d',((int)$value)); 
								break;
							case 'edm':
								$add_data[(int)$no]['edhm'] .= sprintf('%02d',((int)$value));
								break;
							case 'aiteskrank': // ランク
								$add_data[(int)$no]['kbncd'] = $value;
								break;
							// 日付とチェックボックス以外
							case 'aitesknm':
							case 'mendannm01':
							case 'mendannm02':
							case 'doukounm':
							case 'basyo':
							case 'ki':
							case 'sdn_yotei':
							case 'action_type':
								$add_data[(int)$no][$value_name] = $value;
								break;
							// チェックボックス項目
							default:
								$add_data[(int)$no][$value_name] = ($value === 'on') ? '1' : '0';
								break;
						}
						// 選択している日付
						$add_data[(int)$no]['ymd'] = $post['select_day'];
						log_message('debug',"add_data[".(int)$no."][".$value_name."] = ".$value);
						log_message('debug',"add_data[".(int)$no."][sthm] = ".$value);
						log_message('debug',"add_data[".(int)$no."][edhm] = ".$value);
						log_message('debug',"add_data[".(int)$no."][action_type] = ".$value);
					}
				}
			}
		}
		log_message('debug',"========= plan get_plan_new_data end =========");

		return $add_data;
	}
	
	function get_confirmer_no($shbn = NULL,$kakninshbn){
		log_message('debug',"================================ get_confirmer_no start");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$kakninshbn = $kakninshbn");
		if(is_null($shbn)){
			log_message('debug',"shbn is NULL");
			return;
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('sgmtb010');
		$existence_flg = TRUE;
		
		// 社番よりユニット長番号を取得
		$confirmer_no = $CI->sgmtb010->get_unit_cho_shbn($shbn);
		log_message('debug',"\$confirmer_no = $confirmer_no");
		if($confirmer_no === ''){
			log_message('debug',"nothing confirmer no");
			return $kakninshbn;
		}
		
		if(empty($kakninshbn)){
			$kakninshbn = $confirmer_no;
		}else{
			// 確認者番を配列に保存
			$kakunin = explode(" ",$kakninshbn);
			if($kakunin === FALSE){
				log_message('debug',"explode kakunin is FALSE");
				$kakninshbn = $confirmer_no;
				return $kakninshbn;
			}
			// 配列内の確認者番の中にユニット長番号が有るか確認
			foreach ($kakunin as $key => $value) {
				log_message('debug',"\$value = $value");
				if($confirmer_no === $value){
					$existence_flg = FALSE;
				}
			}
			// ユニット長番号を追加
			if($existence_flg === TRUE){
				$kakninshbn .= " ".$confirmer_no;
			}
		}
		
		log_message('debug',"\$kakninshbn = $kakninshbn");
		log_message('debug',"================================ get_confirmer_no end");
		return $kakninshbn;
	}
	
	
	function post_dataset($post){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('common_manager');
		
		$data = $CI->common_manager->init(SHOW_PLAN_A);
		$data['result_flg'] = 1;
		$data['action_plan']="";
		
		$data_no = "";
		$group_count = -1;
		$CI->load->model(array('common','srntb110','srntb120','srntb130','srntb140','srntb160'));
		foreach ($_POST as $key => $value) {
			if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action' OR $key === 'action_type_00'){
				continue;
			}
	
			if($value=="srntb110"){
				$value ="honbu";
				$view_item = $this->get_view_item('SRNTB110');
				$data['action_plan'][$group_count+1]= $view_item;
			}else if($value=="srntb120"){
				$value ="tenpo";
				$view_item = $this->get_view_item('SRNTB120');
				$data['action_plan'][$group_count+1]= $view_item;
			}else if($value=="srntb130"){
				$value ="dairi";
				$view_item = $this->get_view_item('SRNTB130');
				$data['action_plan'][$group_count+1]= $view_item;
			}else if($value=="srntb140"){
				$value ="office";
				$CI->load->library('item_manager');
				// 作業内容
				$tag_name = 'sagyoniyo_'.sprintf('%02d', $group_count+2);
				$sagyo_list =  $CI->item_manager->set_dropdown_in_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$post[$tag_name],$tag_name);
			}else if($value=="srntb160"){
				$value ="gyousya";
			}
			
			if(substr($key, 0, -3)=="sth"){
				$sthm = $value;
				continue;
			}else if(substr($key, 0, -3)=="stm"){
				$sthm .= $value;
				$value=$sthm;
				$key="sthm_".$data_no ;
			}else if(substr($key, 0, -3)=="edh"){
				$edhm = $value;
				continue;
			}else if(substr($key, 0, -3)=="edm"){
				$edhm .= $value;
				$value=$edhm;
				$key="edhm_".$data_no ;
			}
			
			if($value=="on"){
				$value='1';
			}
			if(substr($key, 0, -3)=="sagyoniyo"){continue;}
			// キーの後ろ2桁を取得して同じ物をグルーピングする
			if($data_no === substr($key, -2)){
				$data['action_plan'][$group_count][substr($key, 0, -3)] = $value;
			}else{
				$group_count++;
				$data_no = substr($key, -2);
				$data['action_plan'][$group_count]['count'] = $data_no;
				$data['action_plan'][$group_count][substr($key, 0, -3)] = $value;
				if(isset($sagyo_list) && $sagyo_list!=""){
					$data['action_plan'][$group_count]['sagyoniyo'] = $sagyo_list;
				}
			}
			$data_no = substr($key, -2);
		}
		return $data;
	}

    //
    // すでに予約済みの時刻かをチェックする
    //
    function st_et_check($shbn, $select_day, $start_hour, $start_minute, $end_hour, $end_minute) {

        log_message('debug', "========== " . __METHOD__ . " (" . $shbn . ", " . $select_day . ", " . $start_hour . ", " . $start_minute . ", " . $end_hour . ", " . $end_minute . ") start ==========");

        // 初期化
        $CI = & get_instance();
        $CI->load->model('srntb110');
        $CI->load->model('srntb120');
        $CI->load->model('srntb130');
        $CI->load->model('srntb140');
        $CI->load->model('srntb160');

        // 予定開始時刻と予定終時刻をtimestamp型にする
        log_message('debug', "========== " . __METHOD__ . " (" . substr($select_day, 4, 2) . ", " . substr($select_day, 6, 2) . ", " . substr($select_day, 0, 4) . ", " . $start_hour . ", " . $start_minute . ", " . $end_hour . ", " . $end_minute . ") start ==========");

        $st = mktime($start_hour, $start_minute, '00', substr($select_day, 4, 2), substr($select_day, 6, 2), substr($select_day, 0, 4));
        $et = mktime($end_hour, $end_minute, '00', substr($select_day, 4, 2), substr($select_day, 6, 2), substr($select_day, 0, 4));

        // ここで登録済みの時刻とブッキングしていないか確認する。ブッキングしていたら真を返す
        if ($CI->srntb110->st_et_check($shbn, $st, $et)){
            $this->error_date = $CI->srntb110->error_date;
            return true;
        }
        if ($CI->srntb120->st_et_check($shbn, $st, $et)){
            $this->error_date = $CI->srntb120->error_date;
            return true;
        }
        if ($CI->srntb130->st_et_check($shbn, $st, $et)){
            $this->error_date = $CI->srntb130->error_date;
            return true;
        }
        if ($CI->srntb140->st_et_check($shbn, $st, $et)){
            $this->error_date = $CI->srntb140->error_date;
            return true;
        }
        if ($CI->srntb160->st_et_check($shbn, $st, $et)){
            $this->error_date = $CI->srntb160->error_date;
            return true;
        }

        return FALSE;   // 時刻の重複がなければ偽を返す
    }
	
	
}

// END Plan_manager class

/* End of file Plan_manager.php */
/* Location: ./application/libraries/plan_manager.php */
