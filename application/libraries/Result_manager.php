<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Result_manager {
	
	function set_result_data($shbn = NULL,$select_day = NULL)
	{
		log_message('debug',"========== libraries result_manager set_result_data start ==========");
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
		
		log_message('debug',"========== libraries result_manager set_result_data end ==========");
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
		log_message('debug',"========== libraries result_manager _get_result_data start ==========");
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
					$return_data['data'][$key]['out_situation'] =  $CI->result_table_manager->set_out_situation_table($count,$check,$return_data['data'][$key]);
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
					$return_data['data'][$key]['sagyoniyo'] =  $CI->item_manager->set_dropdown_in_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$return_data['data'][$key]['sagyoniyo'],$tag_name);
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
		log_message('debug',"========== libraries result_manager _get_result_data end ==========");
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
		// 契約金額/半期
		$variable_name = "seiykuriage_".$data_no;
		if(empty($honbu_data[$variable_name])){
			$record_data['seiykuriage'] = 0;
		}else{
			$record_data['seiykuriage'] = $honbu_data[$variable_name];
		}
		// 成約内容
		$variable_name = "seiykniyo_".$data_no;
		$record_data['seiykniyo'] = $honbu_data[$variable_name];
		// 不成約内容
		$variable_name = "fseiykniyo_".$data_no;
		$record_data['fseiykniyo'] = $honbu_data[$variable_name];
		// 保留内容
		$variable_name = "horyuniyo_".$data_no;
		$record_data['horyuniyo'] = $honbu_data[$variable_name];
		// その他内容 
		$variable_name = "sonotaniyo_".$data_no;
		$record_data['sonotaniyo'] = $honbu_data[$variable_name];
		// 指示コメント
		$variable_name = "sijicmt01_".$data_no;
		$record_data['sijicmt01'] = $honbu_data[$variable_name];
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		$record_data['kakninshbn'] = $honbu_data[$variable_name];
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		// 一時保存フラグ
		$variable_name = "hold_flg_".$data_no;
		$record_data['hold_flg'] = $honbu_data[$variable_name];
		
		$record_data['jyohonum'] = $honbu_data[$jyohonum_name];

		// 情報No取得
		if (empty($honbu_data[$jyohonum_name])) {
			$CI->srntb010->insert_srntb010_data($record_data);
			return $CI->db->insert_id();
		}else{
			$CI->srntb010->update_srntb010_data($record_data);
			return $honbu_data[$jyohonum_name];
		}
		
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
		$aiteskcd = "aiteskcd_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		
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
		// 作業結果
		$variable_name = "sdn_niyo_".$data_no;
		$record_data['sdn_niyo'] = $tenpo_data[$variable_name];
		// 企画情報アイテム１
		$variable_name = "out_situationcd01_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationcd01'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　月１
		$variable_name = "out_situationmonth01_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationmonth01'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　場所１
		$variable_name = "out_situationbasyo01_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationbasyo01'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム２
		$variable_name = "out_situationcd02_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationcd02'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　月２
		$variable_name = "out_situationmonth02_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationmonth02'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　場所２
		$variable_name = "out_situationbasyo02_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationbasyo02'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム３
		$variable_name = "out_situationcd03_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationcd03'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　月３
		$variable_name = "out_situationmonth03_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationmonth03'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　場所３
		$variable_name = "out_situationbasyo03_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationbasyo03'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム４
		$variable_name = "out_situationcd04_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationcd04'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　月４
		$variable_name = "out_situationmonth04_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationmonth04'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　場所４
		$variable_name = "out_situationbasyo04_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationbasyo04'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム５
		$variable_name = "out_situationcd05_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationcd05'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　月５
		$variable_name = "out_situationmonth05_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationmonth05'] = $tenpo_data[$variable_name];
		}
		// 企画情報アイテム　場所５
		$variable_name = "out_situationbasyo05_".$data_no;
		if ( ! empty($tenpo_data[$variable_name])) {
			$record_data['out_situationbasyo05'] = $tenpo_data[$variable_name];
		}
		
		// 指示コメント
		$variable_name = "sijicmt01_".$data_no;
		$record_data['sijicmt01'] = $tenpo_data[$variable_name];

		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		$record_data['kakninshbn'] = $tenpo_data[$variable_name];
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		// 一時保存フラグ
		$variable_name = "hold_flg_".$data_no;
		$record_data['hold_flg'] = $tenpo_data[$variable_name];
				
		$record_data['jyohonum'] = $tenpo_data[$jyohonum_name];

		// 情報No取得
		if (empty($tenpo_data[$jyohonum_name])) {
			$CI->srntb020->insert_srntb020_data($record_data);
		}else{
			$CI->srntb020->update_srntb020_data($record_data);
		}

		return $record_data;
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
		$CI->load->model('srntb030');
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
		$variable_name = "aiteskrank_".$data_no;
		$record_data['aiteskrank'] = $dairi_data[$variable_name];
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
		// 商談目的と結果詳細
		$variable_name = "sdn_niyo_".$data_no;
		$record_data['sdn_niyo'] = $dairi_data[$variable_name];
		// 指示コメント
		$variable_name = "sijicmt01_".$data_no;
		$record_data['sijicmt01'] = $dairi_data[$variable_name];
		
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		$record_data['kakninshbn'] = $dairi_data[$variable_name];
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		// 一時保存フラグ
		$variable_name = "hold_flg_".$data_no;
		$record_data['hold_flg'] = $dairi_data[$variable_name];
		
		
		$record_data['jyohonum'] = $dairi_data[$jyohonum_name];
		
		// 情報No取得
		if (empty($dairi_data[$jyohonum_name])) {
			$CI->srntb030->insert_srntb030_data($record_data);
			return $CI->db->insert_id();
		}else{
			$CI->srntb030->update_srntb030_data($record_data);
			return $dairi_data[$jyohonum_name];
		}
	
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
		$CI->load->model('srntb040');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		
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
		
		// 指示コメント
		$variable_name = "sijicmt01_".$data_no;
		$record_data['sijicmt01'] = $office_data[$variable_name];

		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		$record_data['kakninshbn'] = $office_data[$variable_name];
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		// 一時保存フラグ
		$variable_name = "hold_flg_".$data_no;
		$record_data['hold_flg'] = $office_data[$variable_name];
		
		
		$record_data['jyohonum'] = $office_data[$jyohonum_name];
		
		// 情報No取得
		if (empty($office_data[$jyohonum_name])) {
			$CI->srntb040->insert_srntb040_data($record_data);
		}else{

			$CI->srntb040->update_srntb040_data($record_data);
		}
		return;
	}
	
	/**
	 * 業者情報登録
	 * 
	 */
	function record_gyousya_data($shbn,$gyousya_data,$data_no){
		// 引数チェック
		if (is_null($gyousya_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb060');
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
		// 企業名
		$variable_name = "gyoshanm_".$data_no;
		$record_data['gyoshanm'] = $gyousya_data[$variable_name];
		// メモ
		$variable_name = "memo_".$data_no;
		$record_data['memo'] = $gyousya_data[$variable_name];
		// 指示コメント
		$variable_name = "sijicmt_".$data_no;
		$record_data['sijicmt'] = $gyousya_data[$variable_name];
		
		// 確認者番
		$variable_name = "kakninshbn_".$data_no;
		$record_data['kakninshbn'] = $gyousya_data[$variable_name];
		// 登録フラグ
		$record_data['recode_flg'] = '1';
		// 一時保存フラグ
		$variable_name = "hold_flg_".$data_no;
		$record_data['hold_flg'] = $gyousya_data[$variable_name];
		
		$record_data['jyohonum'] = $gyousya_data[$jyohonum_name];

		// 情報No取得
		if (empty($gyousya_data[$jyohonum_name])) {
			$CI->srntb060->insert_srntb060_data($record_data);
		}else{
			$CI->srntb060->update_srntb060_data($record_data);
		}
		
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
//		$office_count = MY_ZERO; // 内勤件数
		
		// 予定情報をテーブルより取得
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
				$result_data = $CI->srntb110->get_srntb110_data($value);
				if( ! is_null($result_data)){
					// アクションタイプ設定
					$result_data['0']['action_type'] = MY_ACTION_TYPE_HONBU;
					// 予備項目取得
					$view_item = $this->get_view_item('SRNTB010');
					
					// データマージ
					$return_data['data'][$key] = array_merge($view_item, $result_data['0']);
					
					$return_data['data'][$key]['uid'] = create_random_string();
					//情報no削除
					unset($return_data['data'][$key]['jyohonum']);
					
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
				
					$return_data['data'][$key]['uid'] = create_random_string();
					//情報no削除
					unset($return_data['data'][$key]['jyohonum']);
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
					$return_data['data'][$key]['uid'] = create_random_string();
					//情報no削除
					unset($return_data['data'][$key]['jyohonum']);
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
					$return_data['data'][$key]['uid'] = create_random_string();
					//情報no削除
					unset($return_data['data'][$key]['jyohonum']);
			
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
					//情報no削除
					$return_data['data'][$key]['uid'] = create_random_string();
					unset($return_data['data'][$key]['jyohonum']);

				}
			}
			$return_data['data'][$key]['count'] = $count;
			$action_cnt++;
		}
		log_message('debug',"========== libraries result_manager _get_plan_data end ==========");
		return $return_data;
	}
	
	
	function record_honbu_rireki($shbn,$honbu_data,$data_no,$motojyohonum){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srktb010');
		$CI->load->model('sgmtb010');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		$kubun = "001";
		$aiteskcd = NULL;
		$shnm = "";
		
		// 社員名の取得
		$sh_data = $CI->sgmtb010->get_search_user_data($shbn);
		if(!is_null($sh_data)){
			$shnm = $sh_data['shinnm'];
		}
		
		// 社番
		$record_data['tantoshbn'] = $shbn;
		// 社員名
		$record_data['tantoshnm'] = $shnm;
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
		// 相手先名
		$variable_name = "aitesknm_".$data_no;
		$record_data['aitesknm'] = $honbu_data[$variable_name];
		// 相手先コード
		if(is_null($aiteskcd)){
			$variable_name = "aiteskcd_".$data_no;
					$record_data['aiteskcd'] = $honbu_data[$variable_name];
		}else{
			$record_data['aiteskcd'] = $aiteskcd;
		}
		// 相手先ランク
		$variable_name = "aiteskrank_".$data_no;
		$record_data['aiteskrank'] = $honbu_data[$variable_name];
		// 面談者１
		$variable_name = "mendannm01_".$data_no;
		$record_data['mendnnm01'] = $honbu_data[$variable_name];
		// 面談者２
		$variable_name = "mendannm02_".$data_no;
		$record_data['mendnnm02'] = $honbu_data[$variable_name];
		// 同行者
		$variable_name = "doukounm01_".$data_no;
		$record_data['dokoshin'] = $honbu_data[$variable_name];
		// 場所
		$variable_name = "basyo_".$data_no;
		$record_data['basyo'] = $honbu_data[$variable_name];
		// 契約金額
		$variable_name = "seiykuriage_".$data_no;
		if(empty($honbu_data[$variable_name])){
			$record_data['seiykuriage'] = 0;
		}else{
			$record_data['seiykuriage'] = $honbu_data[$variable_name];
		}
		// 成約内容
		$variable_name = "seiykniyo_".$data_no;
		$record_data['seiykniyo'] = $honbu_data[$variable_name];
		// 不成約内容
		$variable_name = "fseiykniyo_".$data_no;
		$record_data['fseiykniyo'] = $honbu_data[$variable_name];
		// 保留内容
		$variable_name = "horyuniyo_".$data_no;
		$record_data['horyuniyo'] = $honbu_data[$variable_name];
		// その他内容 
		$variable_name = "sonotaniyo_".$data_no;
		$record_data['sonotaniyo'] = $honbu_data[$variable_name];
		// 活動区分
		$record_data['kubun'] = $kubun;
		// 登録元情報ナンバー
		$record_data['motojyohonum'] = $motojyohonum;
				
		// 情報No取得
		$jyoho_data = $CI->srktb010->get_jyohonum($motojyohonum, $kubun);
		if(is_null($jyoho_data)){
			$CI->srktb010->insert_srktb010_data($record_data);
		}else{
			$CI->srktb010->update_srktb010_data($record_data);
		}

		return;
	}
	
	function record_dairi_rireki($shbn,$honbu_data,$data_no,$motojyohonum){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srktb010');
		$CI->load->model('sgmtb010');
		$jyohonum_name = "jyohonum_".$data_no;
		$edbn_name = "edbn_".$data_no;
		$variable_name = "";
		$kubun = "003";
		$aiteskcd = NULL;
		$shnm = "";
		$record_data = array();
		
		// 社員名の取得
		$sh_data = $CI->sgmtb010->get_search_user_data($shbn);
		if(!is_null($sh_data)){
			$shnm = $sh_data['shinnm'];
		}
		
		// 社番
		$record_data['tantoshbn'] = $shbn;
		// 社員名
		$record_data['tantoshnm'] = $shnm;
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
		// 相手先名
		$variable_name = "aitesknm_".$data_no;
		$record_data['aitesknm'] = $honbu_data[$variable_name];
		// 相手先コード
		if(is_null($aiteskcd)){
			$variable_name = "aiteskcd_".$data_no;
			$record_data['aiteskcd'] = $honbu_data[$variable_name];
		}else{
			$record_data['aiteskcd'] = $aiteskcd;
		}
		// 相手先ランク
		$variable_name = "aiteskrank_".$data_no;
		$record_data['aiteskrank'] = $honbu_data[$variable_name];
		// 面談者１
		$variable_name = "mendannm01_".$data_no;
		$record_data['mendnnm01'] = $honbu_data[$variable_name];
		// 面談者２
		$variable_name = "mendannm02_".$data_no;
		$record_data['mendnnm02'] = $honbu_data[$variable_name];
		// 同行者
		$variable_name = "doukounm01_".$data_no;
		$record_data['dokoshin'] = $honbu_data[$variable_name];
		// 作業結果
		$variable_name = "sdn_niyo_".$data_no;
		$record_data['syodankekka'] = $honbu_data[$variable_name];
		// 活動区分
		$record_data['kubun'] = $kubun;
		// 登録元情報ナンバー
		$record_data['motojyohonum'] = $motojyohonum;
				
		// 情報No取得
		$jyoho_data = $CI->srktb010->get_jyohonum($motojyohonum,$kubun);
		if (is_null($jyoho_data)) {
			$CI->srktb010->insert_srktb010_data($record_data);
		} else {
			$CI->srktb010->update_srktb010_data($record_data);
		}
		
		return;
	}
	
	function record_confirmer_data($confirmer_data){
		if(empty($confirmer_data['shbn'])){
			log_message('debug',"shbn is empty");
		}else if(empty($confirmer_data['select_day'])){
			log_message('debug',"select_day is empty");
		}else if(empty($confirmer_data['kakninshbn'])){
			log_message('debug',"kakninshbn is empty");
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srwtb010');
		$kakninshbn = NULL;
		// 確認者番を配列に保存
		$kakninshbn = explode(" ", $confirmer_data['kakninshbn']);
		if($kakninshbn === FALSE){
			log_message('debug',"explode kakninshbn is FALSE");
			return;
		}
				
		// 削除処理
		$CI->srwtb010->delete_srwtb010_data($confirmer_data['shbn'],$confirmer_data['select_day']);
		// 確認者数分登録処理を行う
		foreach ($kakninshbn as $key => $value) {
			// SRWTB010 使用時パラメータ作成処理
			// 確認者社番
			$record_data['kashbn'] = $value;
			// 依頼社番
			$record_data['irshbn'] = $confirmer_data['shbn'];
			// 種別
			$record_data['shubetu'] = '01';
			// 確認フラグ
			$record_data['kakninflg'] = '0';
			// 日付
			$record_data['ymd'] = $confirmer_data['select_day'];
			// 掲載期限
			$year = substr($confirmer_data['select_day'],0,4);
			$month = (int)substr($confirmer_data['select_day'],4,2);
			$day = (int)substr($confirmer_data['select_day'],6,2);
			$kigen = date("Ymd", time() + 24 * 60 * 60 * 7);
			$record_data['kigen'] = $kigen;
			// 閲覧状況
			$record_data['etujukyo'] = '0';
			// コメント
			$record_data['comment'] = '0';

			$CI->srwtb010->insert_srwtb010_data($record_data);
		}
		
		return;
	}
	
	function delete_confirmer_data($shbn = NULL,$select_day = NULL){
	
		if(is_null($shbn) OR is_null($select_day)){
			return;
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model('common');
		$CI->load->model('srwtb010');
//		$CI->load->model('srntb050');
		
		$ret_data = $CI->common->get_result_count($shbn,$select_day);
		if($ret_data['0']['hon_cnt'] != 0){
			return;
		}else if($ret_data['0']['ten_cnt'] != 0){
			return;
		}else if($ret_data['0']['dai_cnt'] != 0){
			return;
		}else if($ret_data['0']['off_cnt'] != 0){
			return;
		}else if($ret_data['0']['gyo_cnt'] != 0){
			return;
		}
		// 削除処理
		$CI->srwtb010->delete_srwtb010_data($shbn,$select_day);
//		$CI->srntb050->delete_srntb050_data($shbn,$select_day);
		
		return;
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
		$CI->load->library('result_table_manager');
		$data = $CI->common_manager->init(SHOW_RESULT_A);
		$data['result_flg'] = 1;
		$data['action_result']="";
		
		$data_no = "";
		$group_count = -1;
		$CI->load->model(array('common','srntb010','srntb020','srntb030','srntb040','srntb060'));
		foreach ($_POST as $key => $value) {
			if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action' OR $key === 'action_type_00' OR $key === 'check_hold'){
				$data[$key]=$value;
				continue;
			}
	
			if($value=="srntb010"){
				$value ="honbu";
				$view_item = $this->get_view_item('SRNTB010');
				$data['action_result'][$group_count+1]= $view_item;
			}else if($value=="srntb020"){
				$value ="tenpo";
				$view_item = $this->get_view_item('SRNTB020');
				$data['action_result'][$group_count+1]= $view_item;
				$out_situation =  $CI->result_table_manager->set_out_situation_table(sprintf('%02d', $group_count+2),NULL);
			}else if($value=="srntb030"){  
				$value ="dairi";
				$view_item = $this->get_view_item('SRNTB030');
				$data['action_result'][$group_count+1]= $view_item;
			}else if($value=="srntb040"){
				$value ="office";
				$CI->load->library('item_manager');
				// 作業内容
				$tag_name = 'sagyoniyo_'.sprintf('%02d', $group_count+2);
				$sagyo_list =  $CI->item_manager->set_dropdown_in_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$post[$tag_name],$tag_name);
			}else if($value=="srntb060"){
				$value ="gyousya";
			}
			
			if(substr($key, 0, -3)=="sth"){
			
				$sthm = sprintf('%02d', $value);
				continue;
			}else if(substr($key, 0, -3)=="stm"){
				$sthm .= sprintf('%02d', $value);
				$value=$sthm;
				$key="sthm_".$data_no ;
			}else if(substr($key, 0, -3)=="edh"){
				$edhm = sprintf('%02d', $value);
				continue;
			}else if(substr($key, 0, -3)=="edm"){
				$edhm .= sprintf('%02d', $value);
				$value=$edhm;
				$key="edhm_".$data_no ;
			}
			
			if($value=="on"){
				$value='1';
			}
			if(substr($key, 0, -3)=="sagyoniyo"){continue;}
			// キーの後ろ2桁を取得して同じ物をグルーピングする
			if($data_no === substr($key, -2)){
				$data['action_result'][$group_count][substr($key, 0, -3)] = $value;
			}else{
				$group_count++;
				$data_no = substr($key, -2);
				$data['action_result'][$group_count]['count'] = $data_no;
				$data['action_result'][$group_count][substr($key, 0, -3)] = $value;
				if(isset($sagyo_list) && $sagyo_list!=""){
					$data['action_result'][$group_count]['sagyoniyo'] = $sagyo_list;
				}
				if(isset($out_situation) && $out_situation!=""){
					$data['action_result'][$group_count]['out_situation'] = $out_situation;
				}
			}
			$data_no = substr($key, -2);
		}
		
		return $data;
	}

    function st_et_check($shbn, $select_day, $st, $et){
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srntb010');
        $CI->load->model('srntb020');
        $CI->load->model('srntb030');
        $CI->load->model('srntb040');
        $CI->load->model('srntb060');
        
        // ここで登録済みの時刻とブッキングしていないか確認する。ブッキングしていたら真を返す
        if($CI->srntb010->st_et_check($shbn, $select_day, $st, $et)) return true;
        if($CI->srntb020->st_et_check($shbn, $select_day, $st, $et)) return true;
        if($CI->srntb030->st_et_check($shbn, $select_day, $st, $et)) return true;
        if($CI->srntb040->st_et_check($shbn, $select_day, $st, $et)) return true;
        if($CI->srntb060->st_et_check($shbn, $select_day, $st, $et)) return true;

        return FALSE;
	}
	
}

// END Result_manager class

/* End of file Result_manager.php */
/* Location: ./application/libraries/Result_manager.php */
