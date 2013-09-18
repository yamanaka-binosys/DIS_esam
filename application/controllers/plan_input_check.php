<?php

class Plan_input_check extends MY_Controller {

	function index($select_day = NULL){
		try{
			log_message('debug',"========== controllers plan_input_check index start ==========");
			// 初期化
			$data_no = "";
			$group_count = 0;
			$data = NULL;
			$html_string['check_data'] = "";
//			$data = $this->common_manager->init(SHOW_PLAN_C);
			
			// ポストデータ処理
			foreach ($_POST as $key => $value) {
				if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action'){
					continue;
				}
				// キーの後ろ2桁を取得して同じ物をグルーピングする
				if($data_no === substr($key, -2)){
					$data[$group_count][$key] = $value;
				}else{
					$data[$group_count]['data_no'] = $data_no;
					$data[$group_count][("ymd_".$data_no)] = $select_day;
					$group_count++;
					$data[$group_count][$key] = $value;
				}
				$data_no = substr($key, -2);
			}
			
			foreach ($data as $key => $value){
				if(!empty($value['data_no'])){
					$action_name = 'action_type_'.$value['data_no'];
					log_message('debug',"\$action_name = $action_name");
					if($value[$action_name] === 'srntb110'){
						$html_string['check_data'] .= $this->honbu_html($data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb120'){
						$html_string['check_data'] .= $this->tenpo_html($data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb130'){
						$html_string['check_data'] .= $this->dairi_html($data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb140'){
						$html_string['check_data'] .= $this->office_html($data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb160'){
						$html_string['check_data'] .= $this->gyousya_html($data[$key],$value['data_no']);
					}
				}
			}
			
			
			log_message('debug',"========== controllers plan_input_check index end ==========");
			$this->display($html_string);
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-INPUT-CHECK-index'));
			//$this->error_view($e);
		}
	}
	
	/**
	 * 共通で使用する表示処理
	 * 
	 * @access public
	 * @param  array $data 各種HTML作成時に必要な値
	 * @return nothing
	 */
	function display($data = NULL){
		try{
			log_message('debug',"========== controllers plan_input_check display start ==========");
			log_message('debug',"========== controllers plan_input_check display end ==========");
			// 表示処理
			$this->load->view(MY_VIEW_PLAN_INPUT_CHECK, $data, FALSE);
			return;
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
		}
	}
	
	function honbu_html($data = NULL,$no = NULL){
		// 引数チェック
		if(is_null($data) OR is_null($no)){
			return NULL;
		}
		// 初期化
		$html_string = "";
		$item_name = "";
		
		$html_string .= "<table  class=\"item-table\">\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th class=\"time-head\">時間</th>\n";
		$html_string .= "<th class=\"kbn-head\">活動区分</th>\n";
		$html_string .= "<th class=\"aite-head\">相手先</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<td >\n";
		if(isset($data['sth_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['sth_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['stm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['stm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label> ～ </label>\n";
		if(isset($data['edh_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edh_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['edm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "<td >販売店本部</td>\n";
		if(isset($data['aitesknm_'.$no])){
			$html_string .= "<td >".$data['aitesknm_'.$no]."</td>\n";
		}else{
			$html_string .= "<td ></td>\n";
		}
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th colspan=\"2\">項目</th>\n";
		$html_string .= "<th>内容</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		if(isset($data['sdn_mitumori_'.$no])){
			$item_name .= "見積り提示 ";
		}
		if(isset($data['sdn_siyokaknin_'.$no])){
			$item_name .= "採用企画の確認 ";
		}
		if(isset($data['sdn_hnbikekaku_'.$no])){
			$item_name .= "販売計画 ";
		}
		if(isset($data['sdn_claim_'.$no])){
			$item_name .= "クレーム対応 ";
		}
		if(isset($data['sdn_uribatan_'.$no])){
			$item_name .= "売場提案応 ";
		}
		if(isset($data['other_'.$no])){
			$item_name .= "その他 ";
		}
		if($data['sdn_hnktan_'.$no] == 0){
			//$item_name .= "今期提案 ";
		}else if($data['sdn_hnktan_'.$no] == 1){
			//$item_name .= "来期提案 ";
		}
		if(isset($data['sdn_shohin_'.$no])){
			$item_name .= "商品案内 ";
		}
		if(isset($data['sdn_tnwrkeka_'.$no])){
			$item_name .= "棚割結果確認 ";
		}
		if(isset($data['sdn_donyutan_'.$no])){
			$item_name .= "導入提案 ";
		}
		if(isset($data['sdn_mdteian_'.$no])){
			$item_name .= "MD提案 ";
		}
		if(isset($data['sdn_tanawari_'.$no])){
			$item_name .= "棚割提案 ";
		}
		if(isset($data['sdn_tnwrbi_'.$no])){
			$item_name .= "販売店の棚割日情報 ";
		}
		if(isset($data['sdn_donyutume_'.$no])){
			$item_name .= "導入日の詰め ";
		}
		$html_string .= "<td colspan=\"2\" class=\"item-list\"><div>".$item_name."</div></td>\n";
		$html_string .= "<td class=\"content-list\" >\n";
		if(isset($data['sdn_yotei_'.$no])){
			$html_string .= "<pre >".$data['sdn_yotei_'.$no]."</pre>\n";
		}else{
			$html_string .= "<pre  ></pre>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "</table>\n";
		$html_string .= "<br>\n";
		$html_string .= "\n";
		
		return $html_string;
	}
	
	function tenpo_html($data = NULL,$no = NULL){
		// 引数チェック
		if(is_null($data) OR is_null($no)){
			return NULL;
		}
		// 初期化
		$html_string = "";
		$item_name = "";
		
		$html_string .= "<table  class=\"item-table\">\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th class=\"time-head\">時間</th>\n";
		$html_string .= "<th class=\"kbn-head\">活動区分</th>\n";
		$html_string .= "<th class=\"aite-head\">相手先</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<td >\n";
		if(isset($data['sth_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['sth_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['stm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['stm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label> ～ </label>\n";
		if(isset($data['edh_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edh_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['edm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "<td >店舗</td>\n";
		if(isset($data['aitesknm_'.$no])){
			$html_string .= "<td >".$data['aitesknm_'.$no]."</td>\n";
		}else{
			$html_string .= "<td ></td>\n";
		}
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th colspan=\"2\">項目</th>\n";
		$html_string .= "<th>内容</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		if(isset($data['ktd_johosusu_'.$no])){
			$item_name .= "情報収集 ";
		}
		if(isset($data['ktd_tnpyobi01_'.$no])){
			$item_name .= "クレーム対応 ";
		}
		if(isset($data['ktd_johoanai_'.$no])){
			$item_name .= "商品情報案内 ";
		}
		if(isset($data['ktd_tnkikosyo_'.$no])){
			$item_name .= "展開場所・ｱｳﾄ展開交渉 ";
		}
		if(isset($data['ktd_suisnhanbai_'.$no])){
			$item_name .= "推奨販売交渉 ";
		}
		if(isset($data['ktd_jyutyu_'.$no])){
			$item_name .= "受注促進 ";
		}
		if(isset($data['ktd_satuei_'.$no])){
			$item_name .= "売場撮影 ";
		}
		if(isset($data['ktd_beta_'.$no])){
			$item_name .= "ベタ付け ";
		}
		if(isset($data['ktd_mente_'.$no])){
			$item_name .= "売場メンテナンス ";
		}
		if(isset($data['ktd_zaiko_'.$no])){
			$item_name .= "在庫確認 ";
		}
		if(isset($data['ktd_hoju_'.$no])){
			$item_name .= "商品補充 ";
		}
		if(isset($data['ktd_hanskseti_'.$no])){
			$item_name .= "販促物の設置 ";
		}
		if(isset($data['ktd_yamazumi_'.$no])){
			$item_name .= "山積み ";
		}
		if(isset($data['mr_'.$no])){
			$item_name .= "競合店調査（MR） ";
		}
		$html_string .= "<td colspan=\"2\" class=\"item-list\"><div>".$item_name."</div></td>\n";
		$html_string .= "<td  class=\"content-list\">\n";
		if(isset($data['sagyo_yotei_'.$no])){
			$html_string .= "<pre >".$data['sagyo_yotei_'.$no]."</pre>\n";
		}else{
			$html_string .= "<pre  ></pre>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "</table>\n";
		$html_string .= "<br>\n";
		$html_string .= "\n";
		
		return $html_string;
	}
	
	function dairi_html($data = NULL,$no = NULL){
		// 引数チェック
		if(is_null($data) OR is_null($no)){
			return NULL;
		}
		// 初期化
		$html_string = "";
		$item_name = "";
		
		$html_string .= "<table  class=\"item-table\">\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th class=\"time-head\">時間</th>\n";
		$html_string .= "<th class=\"kbn-head\">活動区分</th>\n";
		$html_string .= "<th class=\"aite-head\">相手先</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<td >\n";
		if(isset($data['sth_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['sth_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['stm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['stm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label> ～ </label>\n";
		if(isset($data['edh_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edh_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['edm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "<td >代理店</td>\n";
		if(isset($data['aitesknm_'.$no])){
			$html_string .= "<td >".$data['aitesknm_'.$no]."</td>\n";
		}else{
			$html_string .= "<td ></td>\n";
		}
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th colspan=\"2\">項目</th>\n";
		$html_string .= "<th>内容</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		if(isset($data['sdn_mitsumorifollow_'.$no])){
			$item_name .= "一般店見積り提示・商談フォロー ";
		}
		if(isset($data['sdn_syouhin_'.$no])){
			$item_name .= "商品案内 ";
		}
		if(isset($data['sdn_kikaku_'.$no])){
			$item_name .= "企画案内 ";
		}
		if(isset($data['sdn_jiseki_'.$no])){
			$item_name .= "実績報告（経理・配荷） ";
		}
		if(isset($data['sdn_yobi01_'.$no])){
			$item_name .= "クレーム対応 ";
		}
		if(isset($data['sdn_mitsumori_'.$no])){
			$item_name .= "見積り提示 ";
		}
		if(isset($data['sdn_jizenutiawase_'.$no])){
			$item_name .= "販売店商談事前打合せ ";
		}
		if(isset($data['sdn_kikakujyoukyou_'.$no])){
			$item_name .= "情報収集・企画導入状況確認 ";
		}
		if(isset($data['sdn_kanriyobi01_'.$no])){
			$item_name .= "クレーム対応 ";
		}
		if(isset($data['sdn_logistics_'.$no])){
			$item_name .= "受発注・物流関連 ";
		}
		if(isset($data['sdn_torikmi_'.$no])){
			$item_name .= "取組会議 ";
		}
		$html_string .= "<td colspan=\"2\" class=\"item-list\"><div>".$item_name."</div></td>\n";
		$html_string .= "<td class=\"content-list\" >\n";
		if(isset($data['sdn_yotei_'.$no])){
			$html_string .= "<pre >".$data['sdn_yotei_'.$no]."</pre>\n";
		}else{
			$html_string .= "<pre  ></pre>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "</table>\n";
		$html_string .= "<br>\n";
		$html_string .= "\n";
		
		return $html_string;
	}
	
	function office_html($data = NULL,$no = NULL){
		// 引数チェック
		if(is_null($data) OR is_null($no)){
			return NULL;
		}
		// 初期化
		$html_string = "";
		$item_name = "";
		
		$html_string .= "<table  class=\"item-table\">\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th class=\"time-head\">時間</th>\n";
		$html_string .= "<th class=\"kbn-head\">活動区分</th>\n";
		$html_string .= "<th class=\"aite-head\">相手先</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<td >\n";
		if(isset($data['sth_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['sth_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['stm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['stm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label> ～ </label>\n";
		if(isset($data['edh_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edh_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['edm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "<td >内勤</td>\n";
		$html_string .= "<td ></td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th colspan=\"2\">項目</th>\n";
		$html_string .= "<th>内容</th>\n";
		$html_string .= "</tr>\n";
		if(isset($data['sntsagyo_'.$no]) && $data['sntsagyo_'.$no]!=""){
			$item_name .= $data['sntsagyo_'.$no];
		}else{
			switch ($data['sagyoniyo_'.$no]){
				case '001':
					$item_name .= "月次商談資料作成";
					break;
				case "002":
					$item_name .= "半期提案資料作成";
					break;
				case '003':
					$item_name .= "棚割作成";
					break;
				case '004':
					$item_name .= "得意先依頼資料作成";
					break;
				case '005':
					$item_name .= "POS分析";
					break;
				case '006':
					$item_name .= "受注調整";
					break;
				case '007':
					$item_name .= "SFA見直し";
					break;
				case '008':
					$item_name .= "MDリベート・ERP処理";
					break;
				case '009':
					$item_name .= "値引";
					break;
				case '010':
					$item_name .= "社内資料作成（部・ユニット）";
					break;
				case '011':
					$item_name .= "社内資料作成（本部）";
					break;
				case '012':
					$item_name .= "社内資料作成（事業部）";
					break;
				case '013':
					$item_name .= "個別面談";
					break;
				case '014':
					$item_name .= "情報メモ入力";
					break;
				case '015':
					$item_name .= "情報メモ閲覧";
					break;
				case '016':
					$item_name .= "日報入力";
					break;
				case '017':
					$item_name .= "環境整備";
					break;
				case '018':
					$item_name .= "その他";
					break;
				default:
					break;
			}
			
		}
		$html_string .= "<tr>\n";
		$html_string .= "<td colspan=\"2\" class=\"item-list\"><div>".$item_name."</div></td>\n";
		$html_string .= "<td class=\"content-list\" >\n";
		$html_string .= "<pre  >".$data['kekka_'.$no]."</pre>\n";
		$html_string .= "</td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "</table>\n";
		$html_string .= "<br>\n";
		$html_string .= "\n";
		
		return $html_string;
	}
	
	function gyousya_html($data = NULL,$no = NULL){
		// 引数チェック
		if(is_null($data) OR is_null($no)){
			return NULL;
		}
		// 初期化
		$html_string = "";
		$item_name = "";
		
		$html_string .= "<table class=\"item-table\">\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th class=\"time-head\">時間</th>\n";
		$html_string .= "<th class=\"kbn-head\">活動区分</th>\n";
		$html_string .= "<th class=\"aite-head\">相手先</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<td >\n";
		if(isset($data['sth_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['sth_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['stm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['stm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label> ～ </label>\n";
		if(isset($data['edh_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edh_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "<label>：</label>\n";
		if(isset($data['edm_'.$no])){
			$html_string .= "<label>".sprintf('%02d',$data['edm_'.$no])."</label>\n";
		}else{
			$html_string .= "<label></label>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "<td >業者</td>\n";
		$html_string .= "<td ></td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<th colspan=\"2\">項目</th>\n";
		$html_string .= "<th>内容</th>\n";
		$html_string .= "</tr>\n";
		$html_string .= "<tr>\n";
		$html_string .= "<td class=\"item-list\" colspan=\"2\" ></td>\n";
		$html_string .= "<td class=\"content-list\">\n";
		if(isset($data['memo_'.$no])){
			$html_string .= "<pre  >".$data['memo_'.$no]."</pre>\n";
		}else{
			$html_string .= "<pre  ></pre>\n";
		}
		$html_string .= "</td>\n";
		$html_string .= "</tr>\n";
		$html_string .= "</table>\n";
		$html_string .= "<br>\n";
		$html_string .= "\n";
		
		return $html_string;
	}
	
}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
