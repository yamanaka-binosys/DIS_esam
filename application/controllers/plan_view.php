<?php

class Plan_view extends MY_Controller {

	function index($select_day = NULL,$target_shbn = NULL){
		try{
			log_message('debug',"========== controllers plan_view index start ==========");
			log_message('debug',"\$select_day = $select_day");
			log_message('debug',"\$target_shbn = $target_shbn");
			// 初期化
//			$this->load->library('plan_manager');
			$this->load->library('plan_view_manager');
			$this->load->library('common_manager');
			$this->load->model('sgmtb010');
			$data = NULL;
			$count = FALSE;
			
			// 引数チェック
			if(is_null($select_day)){
				// 選択日が無い場合にはシステム日（当日）を設定する
				$select_day = date('Ymd');
			}
			// データカウンターチェック
			$count = $this->session->userdata('plan_count');
			log_message('debug',"\$count = $count");
			if($count === FALSE){
				// セッションにデータ件数を保存
				log_message('debug',"session plan_count = FALSE");
				$session_data = array('plan_count' => 0);
				$this->session->set_userdata($session_data);
			}
			log_message('debug',"plan_count = ".$this->session->userdata('plan_count'));
			
			// 共通初期化処理
			$data = $this->common_manager->init(SHOW_PLAN_VIEW);
			// 選択日取得
			$data['select_day'] = $select_day;
			// DBデータ取得
			$shbn = $this->session->userdata('shbn');
			if(is_null($target_shbn)){
				$target_shbn = $this->session->userdata('shbn');
			}

			$data['target'] = $this->sgmtb010->get_search_user_data($target_shbn);

			
//			$data['action_plan'] = $this->plan_manager->set_plan_data($target_shbn, $select_day);
			$data['action_plan'] = $this->plan_view_manager->set_plan_data($target_shbn, $select_day);
			
			if(isset($data['action_plan'][0]['kakninshbn']) && $data['action_plan'][0]['kakninshbn'] != ''){
				$data['kakninshbn'] = $data['action_plan'][0]['kakninshbn'];
				if(strpos($data['kakninshbn']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					$confirmer_no = $this->sgmtb010->get_unit_cho_shbn($shbn);
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
			if(!$data['action_plan']){
				$this->load->view('/parts/error/notfound.php');
				return;
			}
			// 画面表示
			log_message('debug',"========== controllers plan index end ==========");
			$this->display($data);
		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-index'));
//			$this->error_view($e);
		}
	}
	
	/**
	 * 共通で使用する表示処理
	 * 
	 * @access public
	 * @param  array $data 各種HTML作成時に必要な値
	 * @return nothing
	 */
	function display($data = NULL)
	{
		try
		{
			log_message('debug',"========== controllers plan display start ==========");
			// 引数チェック
			if(is_null($data)){
				log_message('debug',"argument data is NULL");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			log_message('debug',"========== controllers plan display end ==========");
			// 表示処理
			$this->load->view(MY_VIEW_PLAN_CHECK, $data, FALSE);
			
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-DISPLAY'));
			$this->error_view($e);
		}
	}
	
	function new_action_view(){
		try{
			log_message('debug',"========== controllers plan_view new_action_view start ==========");
			
			// データカウンターセット（"00"固定）
			$data['count'] = "00"; 
			log_message('debug',"\$count = ".$data['count']);
			
			$this->load->view(MY_NEW_VIEW_PLAN_ACTION, $data, FALSE);
			
			log_message('debug',"========== controllers plan_view new_action_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-new_action_view'));
			//$this->error_view($e);
		}
	}
	
	function new_honbu_view(){
		try{
			log_message('debug',"========== controllers plan_view new_honbu_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB110';
			$data = NULL;
			
			$data['plan'] = $this->plan_manager->get_view_item($use_db);
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			
			$this->load->view(MY_NEW_VIEW_PLAN_HONBU, $data, FALSE);
			log_message('debug',"========== controllers plan_view new_honbu_view end ==========");
			
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-new_honbu_view'));
			//$this->error_view($e);
		}
	}
	
	function new_tenpo_view(){
		try{
			log_message('debug',"========== controllers plan_view new_tenpo_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB120';
			$data = NULL;
			
			$data['plan'] = $this->plan_manager->get_view_item($use_db);
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			
			$this->load->view(MY_NEW_VIEW_PLAN_TENPO, $data, FALSE);
			log_message('debug',"========== controllers plan_view new_tenpo_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-new_tenpo_view'));
			//$this->error_view($e);
		}
	}
	
	function new_dairi_view(){
		try{
			log_message('debug',"========== controllers plan_view new_dairi_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB130';
			$data = NULL;
			
			$data['plan'] = $this->plan_manager->get_view_item($use_db);
						
			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			
			$this->load->view(MY_NEW_VIEW_PLAN_DAIRI, $data, FALSE);
			log_message('debug',"========== controllers plan_view new_dairi_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-new_dairi_view'));
			//$this->error_view($e);
		}
	}
	
	function new_gyousya_view(){
		try{
			log_message('debug',"========== controllers plan_view new_gyousya_view start ==========");
			// 初期化
			$data = NULL;
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			
			$this->load->view(MY_NEW_VIEW_PLAN_GYOUSYA, $data, FALSE);
			log_message('debug',"========== controllers plan_view new_gyousya_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-new_gyousya_view'));
			//$this->error_view($e);
		}
	}
	
	function new_office_view(){
		try{
			log_message('debug',"========== controllers plan_view new_office_view start ==========");
			$this->load->library('item_manager');
			$tag_name = NULL;
			$check = NULL;
			$count = 1;
			if($count == 0){
				log_message('debug',"Exception count = 0");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			
			$this->load->model('sgmtb041');
			$data['PID'] = PLAN_VIEW_ITEM;
			$res = $this->sgmtb041->get_item_visibility_data($data);
			log_message('debug',"\$res = $res");
			if($res === FALSE){
				log_message('debug',"Exception res = NULL");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			
			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			
			// 作業内容
			$tag_name = 'sagyoniyo_'.$data['count'];
			$data['sagyoniyo'] = $this->item_manager->set_dropdown_in_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$check,$tag_name);
			
			
			$this->load->view(MY_NEW_VIEW_PLAN_OFFICE, $data, FALSE);
			log_message('debug',"========== controllers plan_view new_office_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-VIEW-new_office_view'));
			//$this->error_view($e);
		}
	}
	
}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
