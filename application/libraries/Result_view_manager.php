<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Result_view_manager {
	
	function set_result_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries result_view_manager set_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
		// 引数チェック
		if (is_null($shbn) OR is_null($select_day)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('result_table_manager');
		// DBより選択日の情報を取得し、表示に合う様に変換
		$result_data = $this->_get_result_data($shbn,$select_day);
		
		// データ有無チェック
		if ($result_data['existence'] === FALSE) {
			log_message('debug',"data existence is FALSE");
			return NULL;
		}
		
		log_message('debug',"========== libraries result_view_manager set_result_data end ==========");
		return $result_data['data'];
	}
	
	/**
	 * 社番・日付から実績情報をDBより取得し、整形した後返す
	 * 
	 * @access private
	 * @param  string $shbn 社番
	 * @param  string $select_day 日付
	 * @return array $return_data 整形済実績情報
	 */
	private function _get_result_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries result_view_manager _get_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
			
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('common','srntb010','srntb020','srntb030','srntb040','srntb060'));
		$result       = FALSE;   // チェック結果
		$result_data    = NULL;    // 実績情報
		$return_data  = NULL;    // 整形済み実績情報
//		$office_count = MY_ZERO; // 内勤件数
		
		// 実績情報をテーブルより取得
		$summary_data = $CI->common->get_select_result_data($shbn,$select_day);

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
			if($value['dbname'] === 'srntb010'){
				// DBよりデータ取得
				$result_data = $CI->srntb010->get_srntb010_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_HONBU;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB010');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
				}

			// 店舗
			}else if($value['dbname'] === 'srntb020'){
				// DBよりデータ取得
				$result_data = $CI->srntb020->get_srntb020_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_TENPO;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB020');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
					
					$CI->load->library('result_table_manager');
					// 作業内容
					$tag_name = 'out_situation_'.$action_cnt;
					$check = NULL;
//					$return_data['data'][$key]['out_situation'] = $CI->result_table_manager->set_out_situation_table($count,$check,$return_data['data'][$key]);
					$return_data['data'][$key]['out_situation'] = $CI->result_table_manager->set_out_disabled_situation_table($count,$check,$return_data['data'][$key]);
				}
				
			// 代理店
			}else if($value['dbname'] === 'srntb030'){
				// DBよりデータ取得
				$result_data = $CI->srntb030->get_srntb030_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_DAIRI;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB030');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
					
				}
				
			// 内勤
			}else if($value['dbname'] === 'srntb040'){
				// DBよりデータ取得
				$result_data = $CI->srntb040->get_srntb040_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_OFFICE;
					// 設定
					$return_data['data'][$key] = $result_data['0'];
					
					$CI->load->library('item_manager');
					// 作業内容
					$tag_name = 'sagyoniyo_'.$count;
//					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_in_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_disabled_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
				}
				
			// 業者
			}else if($value['dbname'] === 'srntb060'){
				// DBよりデータ取得
				$result_data = $CI->srntb060->get_srntb060_data($value);
				
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_GYOUSYA;
					// 設定
					$return_data['data'][$key] = $result_data['0'];
				}
			}
			$return_data['data'][$key]['count'] = $count;
			$action_cnt++;
		}
//		log_message('debug',"return_data for : ");
//		foreach ($return_data['data'] as $key1 => $value1) {
//			foreach ($value1 as $key2 => $value2) {
//				log_message('debug',$key2 . " = " . $value2);
//			}
//		}
		log_message('debug',"========== libraries result_view_manager _get_result_data end ==========");
		return $return_data;
	}
	
	/**
	 * 本部情報登録
	 * 
	 */
	function record_honbu_data($shbn,$honbu_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb010');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$comment_name = "sijicmt01_".$data_no;
		
		// 元データ取得
		$condition_data['shbn'] = $shbn;
		$condition_data['jyohonum'] = $honbu_data[$jyohonum_name];
		$condition_data['edbn'] = $honbu_data[$edbn_name];
		$res_data = $CI->srntb010->get_srntb010_data($condition_data);

		// 登録データ作成
		foreach ($res_data[0] as $key => $value) {
			if($key === "jyohonum"){
				$record_data[$key] = $honbu_data[$jyohonum_name];
			}else if($key === "edbn"){
				$record_data[$key] = $honbu_data[$edbn_name];
			}else if($key === "sijicmt01"){
				$record_data[$key] = $honbu_data[$comment_name];
			}else if($key === "createdate"){
				continue;
			}else{
				if(empty($value)){
					continue;
				}
				$record_data[$key] = $value;
			}
		}

		$CI->srntb010->update_srntb010_data($record_data);
		return;
	}
			
	/**
	 * 店舗情報登録
	 * 
	 */
	function record_tenpo_data($shbn,$tenpo_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb020');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$comment_name = "sijicmt01_".$data_no;
		
		// 元データ取得
		$condition_data['shbn'] = $shbn;
		$condition_data['jyohonum'] = $tenpo_data[$jyohonum_name];
		$condition_data['edbn'] = $tenpo_data[$edbn_name];
		$res_data = $CI->srntb020->get_srntb020_data($condition_data);

		// 登録データ作成
		foreach ($res_data[0] as $key => $value) {
			if($key === "jyohonum"){
				$record_data[$key] = $tenpo_data[$jyohonum_name];
			}else if($key === "edbn"){
				$record_data[$key] = $tenpo_data[$edbn_name];
			}else if($key === "sijicmt01"){
				$record_data[$key] = $tenpo_data[$comment_name];
			}else if($key === "createdate"){
				continue;
			}else{
				if(empty($value)){
					continue;
				}
				$record_data[$key] = $value;
			}
		}
		
		$CI->srntb020->update_srntb020_data($record_data);
		
		return;
	}
	
	/**
	 * 代理店データ登録
	 * 
	 */
	function record_dairi_data($shbn,$dairi_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb030');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$comment_name = "sijicmt01_".$data_no;
		
		// 元データ取得
		$condition_data['shbn'] = $shbn;
		$condition_data['jyohonum'] = $dairi_data[$jyohonum_name];
		$condition_data['edbn'] = $dairi_data[$edbn_name];
		$res_data = $CI->srntb030->get_srntb030_data($condition_data);
		
		// 登録データ作成
		foreach ($res_data[0] as $key => $value) {
			if($key === "jyohonum"){
				$record_data[$key] = $dairi_data[$jyohonum_name];
			}else if($key === "edbn"){
				$record_data[$key] = $dairi_data[$edbn_name];
			}else if($key === "sijicmt01"){
				$record_data[$key] = $dairi_data[$comment_name];
			}else if($key === "createdate"){
				continue;
			}else{
				if(empty($value)){
					continue;
				}
				$record_data[$key] = $value;
			}
		}
		
		$CI->srntb030->update_srntb030_data($record_data);
		
		return;
	}
	
	/**
	 * 内勤データ登録
	 * 
	 */
	function record_office_data($shbn,$office_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb040');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$comment_name = "sijicmt01_".$data_no;
		
		// 元データ取得
		$condition_data['shbn'] = $shbn;
		$condition_data['jyohonum'] = $office_data[$jyohonum_name];
		$condition_data['edbn'] = $office_data[$edbn_name];
		$res_data = $CI->srntb040->get_srntb040_data($condition_data);

		// 登録データ作成
		foreach ($res_data[0] as $key => $value) {
			if($key === "jyohonum"){
				$record_data[$key] = $office_data[$jyohonum_name];
			}else if($key === "edbn"){
				$record_data[$key] = $office_data[$edbn_name];
			}else if($key === "sijicmt01"){
				$record_data[$key] = $office_data[$comment_name];
			}else if($key === "createdate"){
				continue;
			}else{
				if(empty($value)){
					continue;
				}
				$record_data[$key] = $value;
			}
		}
		
		$CI->srntb040->update_srntb040_data($record_data);
		
		return;
	}
	
	/**
	 * 業者情報登録
	 * 
	 */
	function record_gyousya_data($shbn,$gyousya_data,$data_no){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb060');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$comment_name = "sijicmt_".$data_no;
		
		// 元データ取得
		$condition_data['shbn'] = $shbn;
		$condition_data['jyohonum'] = $gyousya_data[$jyohonum_name];
		$condition_data['edbn'] = $gyousya_data[$edbn_name];
		$res_data = $CI->srntb060->get_srntb060_data($condition_data);

		// 登録データ作成
		foreach($res_data[0] as $key => $value){
			if($key === "jyohonum"){
				$record_data[$key] = $gyousya_data[$jyohonum_name];
			}else if($key === "edbn"){
				$record_data[$key] = $gyousya_data[$edbn_name];
			}else if($key === "sijicmt"){
				$record_data[$key] = $gyousya_data[$comment_name];
			}else if($key === "createdate"){
				continue;
			}else{
				if(empty($value)){
					continue;
				}
				$record_data[$key] = $value;
			}
		}
		
		$CI->srntb060->update_srntb060_data($record_data);
		
		return;
	}
	
	function get_view_item($use_db = NULL){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('sgmtb041');
		$res = FALSE;
		
		// 引数チェック
		if(is_null($use_db)){
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		
		$db_data = $CI->sgmtb041->result_view_setting(RESULT_VIEW_ITEM,$use_db);
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
	
	function get_out_situation_table($count){
		log_message('debug',"========= result get_out_situation_table start =========");
			// 重点商品ｱｳﾄ展開状況
			$tag_name = 'out_situationcd_'.$count;
			$data['out_situation_cd'] = $this->item_manager->set_dropdown_in_db_string(MY_RESULT_OUT_SITU_KBN,$tag_name,$check,$tag_name);
			$tag_name = 'out_situationbasyo_'.$count;
			$data['out_situation_basyo'] = $this->item_manager->set_dropdown_in_db_string(MY_RESULT_OUT_SITU_BASHO_KBN,$tag_name,$check,$tag_name);
		log_message('debug',"========= result get_out_situation_table start =========");
		return $table_data;
	}
	
	/**
	 * 登録処理
	 * 
	 */
	function action_submit($shbn,$day,$jyohonum,$edbn,$post_data,$data_no){
		
		// 情報No初期化
		$variable_name = "jyohonum_".$data_no;
		$post_data[$variable_name] = "";
		// 枝番初期化
		$variable_name = "edbn_".$data_no;
		$post_data[$variable_name] = "";
		// 日付初期化
		$variable_name = "ymd_".$data_no;
		$post_data[$variable_name] = $day;
		
		// 活動区分取得
		$variable_name = "action_type_".$data_no;
		$action_type = $post_data[$variable_name];
		if($action_type === 'srntb010'){
			// 本部データ登録
			$this->record_honbu_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb020'){
			// 店舗データ登録
			$this->record_tenpo_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb030'){
			// 代理店データ登録
			$this->record_dairi_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb040'){
			// 内勤データ登録
			$this->record_office_data($shbn,$post_data,$data_no);
		}else if($action_type === 'srntb060'){
			// 業者データ登録
			$this->record_gyousya_data($shbn,$post_data,$data_no);
		}
//		// 登録済の実績削除
//		if(empty($jyohonum)){
//			$this->delete_action($action_type,$jyohonum,$edbn);
//		}
		
		return;
	}
	
	function delete_action($action_type,$jyohonum,$edbn){
		if($action_type === 'srntb010'){
			// 本部データ削除
			$this->delete_honbu_data($jyohonum,$edbn);
			$this->delete_rireki($jyohonum,'001');
		}else if($action_type === 'srntb020'){
			// 店舗データ削除
			$this->delete_tenpo_data($jyohonum,$edbn);
			$this->delete_rireki($jyohonum,'002');
		}else if($action_type === 'srntb030'){
			// 代理店データ削除
			$this->delete_dairi_data($jyohonum,$edbn);
			$this->delete_rireki($jyohonum,'003');
		}else if($action_type === 'srntb040'){
			// 内勤データ削除
			$this->delete_office_data($jyohonum,$edbn);
		}else if($action_type === 'srntb060'){
			// 業者データ削除
			$this->delete_gyousya_data($jyohonum,$edbn);
		}
	}
	
	function delete_honbu_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb010');
		
		$CI->srntb010->delete_srntb010_data($jyohonum,$edbn);
		
		return;
	}
	
	function delete_tenpo_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb020');
		
		$CI->srntb020->delete_srntb020_data($jyohonum,$edbn);
		
		return;
	}
	
	function delete_dairi_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb030');
		
		$CI->srntb030->delete_srntb030_data($jyohonum,$edbn);
		
		return;
	}
	
	function delete_office_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb040');
		
		$CI->srntb040->delete_srntb040_data($jyohonum,$edbn);
		
		return;
	}
	
	function delete_gyousya_data($jyohonum,$edbn){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb060');
		
		$CI->srntb060->delete_srntb060_data($jyohonum,$edbn);
		
		return;
	}
	
	function delete_rireki($jyohonum,$kubun){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srktb010');
		
		$CI->srktb010->delete_srktb010_data($jyohonum,$kubun);
		
		return;
	}
	

	function set_plan_data($shbn = NULL,$select_day = NULL){
		log_message('debug',"========== libraries result_manager set_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
		// 引数チェック
		if (is_null($shbn) OR is_null($select_day)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// DBより選択日の情報を取得し、表示に合う様に変換
		$tmp_data = $this->_get_plan_data($shbn,$select_day);
		// データ有無チェック
		if ($tmp_data['existence'] === FALSE) {
			log_message('debug',"data existence is FALSE");
			return NULL;
		}
		log_message('debug',"========== libraries result_manager set_plan_data end ==========");
		return $tmp_data['data'];
	}
	
	private function _get_plan_data($shbn = NULL,$select_day = NULL){
		log_message('debug',"========== libraries result_manager _get_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
		
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('common','srntb110','srntb120','srntb130','srntb140','srntb160'));
		$result       = FALSE;   // チェック結果
		$result_data    = NULL;    // 予定情報
		$return_data  = NULL;    // 整形済み予定情報

		$summary_data = $CI->common->get_plan_data_for_result($shbn,$select_day);
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
		$action_cnt = 1;
		// データ取得件数分回して表示データを取得（個別）
		foreach($summary_data['data'] as $key => $value){
			$count = sprintf('%02d', $action_cnt);
			log_message('debug',"dbname is " . $value['dbname']);
			
			// 本部
			if($value['dbname'] === 'srntb010'){
				// DBよりデータ取得
//				$result_data = $CI->tmpsrntb010->get_tmpsrntb010_data($value);
				$result_data = $CI->srntb110->get_srntb110_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_HONBU;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB010');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
				}

			// 店舗
			}else if($value['dbname'] === 'srntb020'){
				// DBよりデータ取得
				$result_data = $CI->srntb120->get_srntb120_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_TENPO;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB020');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
					
					$CI->load->library('result_table_manager');
					// 作業内容
					$tag_name = 'out_situation_'.$action_cnt;
					$check = NULL;
					$return_data['data'][$key]['out_situation'] =  $CI->result_table_manager->set_out_situation_table($count,$check,$return_data['data'][$key]);
				}
			
			// 代理店
			}else if($value['dbname'] === 'srntb030'){
				// DBよりデータ取得
				$result_data = $CI->srntb130->get_srntb130_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_DAIRI;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB030');
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
				}
			
			// 内勤
			}else if($value['dbname'] === 'srntb040'){
				// DBよりデータ取得
				$result_data = $CI->srntb140->get_srntb140_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_OFFICE;
					
					// データマージ
					$return_data['data'][$key] = $result_data['0'];
					
					$CI->load->library('item_manager');
					// 作業内容
					$tag_name = 'sagyoniyo_'.$count;
					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_in_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
				}
			// 業者
			}else if($value['dbname'] === 'srntb060'){
				// DBよりデータ取得
				$result_data = $CI->srntb160->get_srntb160_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_GYOUSYA;
					// 設定
					$return_data['data'][$key] = $result_data['0'];
				}
			}
			$return_data['data'][$key]['count'] = $count;
			$action_cnt++;
		}
		log_message('debug',"========== libraries result_manager _get_plan_data end ==========");
		return $return_data;
	}
	
}

// END Result_manager class

/* End of file Result_manager.php */
/* Location: ./application/libraries/Result_manager.php */
