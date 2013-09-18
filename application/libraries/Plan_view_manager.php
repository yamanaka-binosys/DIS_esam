<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Plan_view_manager {
	
	function set_plan_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries plan_view_manager set_plan_data start ==========");
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
		
		log_message('debug',"========== libraries plan_view_manager set_plan_data end ==========");
		return $pran_data['data'];
	}
	
	private function _get_plan_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries plan_view_manager _get_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
			
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('common','srntb110','srntb120','srntb130','srntb140','srntb160'));
		$result       = FALSE;   // チェック結果
		$plan_data    = NULL;    // 予定情報
		$return_data  = NULL;    // 整形済み予定情報
		
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
					$tag_name = 'sagyoniyo_'.$count;
//					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_in_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_disabled_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
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
			$return_data['data'][$key]['count'] = $count;
			$action_cnt++;
		}
		log_message('debug',"========== libraries plan_view_manager _get_plan_data end ==========");
		return $return_data;
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
}

// END Plan_view_manager class

/* End of file Plan_view_manager.php */
/* Location: ./application/libraries/plan_view_manager.php */
