<?php	

class Result_view extends MY_Controller {

	function index($select_day = NULL,$judg_flg = ""){
		try{
			// 登録ボタン押下判定
			if($_POST){
				// 一日分の日報の登録
				if($_POST['action'] === 'action_submit'){
					$this->action_submit($_POST);
				}
			}
			
			log_message('debug',"========== controllers result_view index start ==========");
			// 初期化
			$this->load->library('common_manager');
			$this->load->library('result_view_manager');
			$this->load->model('sgmtb010');
			$data = NULL;
			$count = FALSE;
			
			// 引数チェック
			if(is_null($select_day)){
				// 選択日が無い場合にはシステム日（当日）を設定する
				$select_day = date('Ymd');
			}
			// データカウンターチェック
			$count = $this->session->userdata('result_view_count');
			log_message('debug',"\$count = $count");
			if($count === FALSE){
				// セッションにデータ件数を保存
				log_message('debug',"session result_view_count = FALSE");
				$session_data = array('result_view_count' => 0);	
				$this->session->set_userdata($session_data);
			}
			log_message('debug',"result_view_count = ".$this->session->userdata('result_view_count'));
			
			// 共通初期化処理
			if($judg_flg === "admin"){
				$data = $this->common_manager->init(SHOW_RESULT_VIEW_ADMIN);
			}else{
				$data = $this->common_manager->init(SHOW_RESULT_VIEW_GENERAL);
			}
			// 選択日取得
			$data['select_day'] = $select_day;
			
			// 社番をセッションより取得
			if($this->session->userdata('others_shbn') === FALSE){
				if(isset($_POST['target_shbn'])){
					$data['target_shbn'] = $_POST['target_shbn'];
				}else{
					$data['target_shbn'] = $this->session->userdata('shbn');
				}
			}else{
				$data['target_shbn'] = $this->session->userdata('others_shbn');
				$this->session->unset_userdata('others_shbn');
			}


			$data['target'] = $this->sgmtb010->get_search_user_data($data['target_shbn']);

			// コメント入力有無を取得
			$data['judg_flg'] = $judg_flg;
			if($judg_flg === "admin"){
				$data['write_flg'] = 1;
			}else if($judg_flg === "general"){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 0;
			}
			// データ取得
			$data['action_result'] = $this->result_view_manager->set_result_data($data['target_shbn'],$select_day);
			// 確認者情報取得
			if(isset($data['action_result'][0]['kakninshbn']) && $data['action_result'][0]['kakninshbn'] != ''){
				$data['kakninshbn'] = $data['action_result'][0]['kakninshbn'];
				if(strpos($data['kakninshbn']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					$confirmer_no = $this->sgmtb010->get_unit_cho_shbn($data['target_shbn']);
					if($data['kakninshbn'] === $confirmer_no){
						$data['kakninshnm'] = "";
					}else{
						$data['kakninshnm'] = $this->common_manager->create_name($data['kakninshbn']);
					}
				}
			}else{
				$data['kakninshbn'] = "";
				$data['kakninshnm'] = "";
			}
			
			if(!$data['action_result']){
				$this->load->view('/parts/error/notfound.php');
				return;
			}
			//見積もりファイル情報取得
			$this->load->model('srntb070');
			$data['mitumori_file']=$this->srntb070->search_file($select_day,$data['target_shbn']);
			
			// 画面表示
			log_message('debug',"========== controllers result index end ==========");
			$this->display($data);
		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-index'));
		//	$this->error_view($e);
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
			log_message('debug',"========== controllers result_view display start ==========");
			// 引数チェック
			if(is_null($data)){
				log_message('debug',"argument data is NULL");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			log_message('debug',"========== controllers result_view display end ==========");
			// 表示処理
			$this->load->view(MY_VIEW_RESULT_V, $data, FALSE);
			
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-display'));
			//$this->error_view($e);
		}
	}
	
	function new_action_view(){
		try{
			log_message('debug',"========== controllers result_view new_action_view start ==========");
			
			// データカウンターセット（"00"固定）
			$data['count'] = "00"; 
			log_message('debug',"\$count = ".$data['count']);
			
			$this->load->view(MY_NEW_VIEW_RESULT_V_ACTION, $data, FALSE);
			
			log_message('debug',"========== controllers result_view new_action_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-new_action_view'));
			//$this->error_view($e);
		}
	}

	function new_honbu_view(){
		try{
			log_message('debug',"========== controllers result_view new_honbu_view start ==========");
			// 初期化
			$this->load->library('result_manager');
			$use_db = 'SRNTB010';
			$data = NULL;
			
			$data['result'] = $this->result_manager->get_view_item($use_db);
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_view_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			
//			$data['admin_flg'] = $this->session->userdata('admin_flg');
			
			// セッションよりコメント入力有無を取得
			$write_flg = $this->session->userdata('write_flg');
			if($write_flg === FALSE){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 1;
			}
			log_message('debug',"write_flg = ".$data['write_flg']);
			
			$this->load->view(MY_NEW_VIEW_RESULT_V_HONBU, $data, FALSE);
			log_message('debug',"========== controllers result_view new_honbu_view end ==========");
			
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-new_honbu_view'));
			//$this->error_view($e);
		}
	}
	
	function new_tenpo_view(){
		try{
			log_message('debug',"========== controllers result_view new_tenpo_view start ==========");
			log_message('debug',"");
			// 初期化
			$this->load->library('result_manager');
			$this->load->library('result_table_manager');
			$tag_name = NULL;
			$check = NULL;
			$use_db = 'SRNTB020';
			$data = NULL;
			
			$data['result'] = $this->result_manager->get_view_item($use_db);
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_view_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_view_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			
			// セッションよりコメント入力有無を取得
			$write_flg = $this->session->userdata('write_flg');
			if($write_flg === FALSE){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 1;
			}
			log_message('debug',"write_flg = ".$data['write_flg']);
						
			// 重点商品ｱｳﾄ展開状況
			$data['out_situation'] = $this->result_table_manager->set_out_situation_table($data['count'],$check);
			
			$this->load->view(MY_NEW_VIEW_RESULT_V_TENPO, $data, FALSE);
			log_message('debug',"========== controllers result_view new_tenpo_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-new_tenpo_view'));
			//$this->error_view($e);
		}
	}
	
	function new_dairi_view(){
		try{
			log_message('debug',"========== controllers result_view new_dairi_view start ==========");
			// 初期化
			$this->load->library('result_manager');
			$use_db = 'SRNTB030';
			$data = NULL;
			
			$data['result'] = $this->result_manager->get_view_item($use_db);

			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_view_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_view_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			
			// セッションよりコメント入力有無を取得
			$write_flg = $this->session->userdata('write_flg');
			if($write_flg === FALSE){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 1;
			}
			log_message('debug',"write_flg = ".$data['write_flg']);
			
			$this->load->view(MY_NEW_VIEW_RESULT_V_DAIRI, $data, FALSE);
			log_message('debug',"========== controllers result_view new_dairi_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-new_dairi_view'));
			//$this->error_view($e);
		}
	}
	
	function new_gyousya_view(){
		try{
			log_message('debug',"========== controllers result_view new_gyousya_view start ==========");
			// 初期化
			$data = NULL;
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_view_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_view_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			
			// セッションよりコメント入力有無を取得
			$write_flg = $this->session->userdata('write_flg');
			if($write_flg === FALSE){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 1;
			}
			log_message('debug',"write_flg = ".$data['write_flg']);
			
			$this->load->view(MY_NEW_VIEW_RESULT_V_GYOUSYA, $data, FALSE);
			log_message('debug',"========== controllers result_view new_gyousya_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-new_gyousya_view'));
			//$this->error_view($e);
		}
	}
	
	function new_office_view(){
		try{
			log_message('debug',"========== controllers result_view new_office_view start ==========");
			$this->load->library('item_manager');
			$tag_name = NULL;
			$check = NULL;
			$count = 1;
			if($count == 0){
				log_message('debug',"Exception count = 0");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_view_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_view_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			
			// セッションよりコメント入力有無を取得
			$write_flg = $this->session->userdata('write_flg');
			if($write_flg === FALSE){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 1;
			}
			log_message('debug',"write_flg = ".$data['write_flg']);
			
			// 作業内容
			$tag_name = 'sagyoniyo_'.$data['count'];
			$data['sagyoniyo'] = $this->item_manager->set_dropdown_in_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$check,$tag_name);
			
			$this->load->view(MY_NEW_VIEW_RESULT_V_OFFICE, $data, FALSE);
			log_message('debug',"========== controllers result_view new_office_view end ==========");
			return;
		}catch(Exception $e){
//			print $e;
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-new_office_view'));
			//$this->error_view($e);
		}
	}
	
	/**
	 * 登録処理
	 * 
	 */
	function action_submit($post = NULL){
		try{
			log_message('debug',"========== controllers result_view action_submit start ==========");
			// 引数チェック
			if(is_null($post) ){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('result_view_manager');
			$this->load->model('common');
			$this->load->model('srwtb010');
			$shbn = $this->session->userdata('shbn');
			$data_no = "";
			$group_count = 0;
			$confirmer_data = array();
			
			// 移動日取得
			$select_day = $post['select_day'];
			foreach ($post as $key => $value) {
				if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'action' OR $key === 'target_shbn'){
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
			// 登録処理
			foreach ($data as $key => $value) {
				if(!empty($value['data_no'])){
					// 確認者番
					$variable_name = 'kakninshbn_'.$value['data_no'];
					$data[$key][$variable_name] = $post['kakninshbn']; 
					// 活動区分
					$action_name = 'action_type_'.$value['data_no'];
					log_message('debug',"\$action_name = $action_name");
					if($value[$action_name] === 'srntb010'){
						$this->result_view_manager->record_honbu_data($post['target_shbn'],$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb020'){
						$this->result_view_manager->record_tenpo_data($post['target_shbn'],$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb030'){
						$this->result_view_manager->record_dairi_data($post['target_shbn'],$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb040'){
						$this->result_view_manager->record_office_data($post['target_shbn'],$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb060'){
						$this->result_view_manager->record_gyousya_data($post['target_shbn'],$data[$key],$value['data_no']);
					}
				}
			}
			// srwtb010　コメント入力有無更新
			$this->srwtb010->update_comment($shbn,$post['target_shbn'],$select_day);
			log_message('debug',"========== controllers result_view action_submit end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result_view-action_submit'));
			//$this->error_view($e);
		}
	}
	
}

/* End of file result_view.php */
/* Location: ./application/controllers/result_view.php */
