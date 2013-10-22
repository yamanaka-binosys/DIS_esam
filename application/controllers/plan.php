<?php

class Plan extends MY_Controller {
	


	function index($select_day = NULL,$confirmer=NULL){
				
		try{
			$action_flg="0";
			// 登録ボタン押下判定を入れる
			if($_POST){
				if($_POST['action'] === 'action_move'){
						$this->action_move($_POST,$_POST['data_count']);
						$msg = "活動区分を移動しました。";
					
				// 業務区分のコピー
				}else if($_POST['action'] === 'action_copy'){
					$this->action_copy($_POST,$_POST['data_count']);
					$msg = "活動区分をコピーしました。";
					
				// 一日分の予定のコミット
				}else if($_POST['action'] === 'action_submit'){
					$msg = $this->action_submit($_POST);
					$save_flg ="1";
					$action_flg="1";
					
					if($msg=="開始時刻と終了時刻の時系列が誤っています。"){
						$this->load->library('plan_manager');
						$this->load->library('common_manager');
						$data =  $this->plan_manager->post_dataset($_POST);
						$data['result_flg'] = 1;
						// 選択日取得
						$data['select_day'] = $select_day;
						$shbn = $this->session->userdata('shbn');
						$data['errmsg'] = $msg;
						$post_flg="1";
					}
				// 一日分の予定の移動
				}else if($_POST['action'] === 'action_day_move'){
					$this->action_day_move($_POST);
					$msg = "スケジュールを移動しました。";
					$this->session->unset_userdata('checker_ednm');
				// 一日分の予定の削除
				}else if($_POST['action'] === 'action_day_delete'){
					$this->action_day_delete($_POST);
					$msg = "スケジュールを削除しました。";
					$this->session->unset_userdata('checker_ednm');
				// 一日分の予定のコピー
				}else if($_POST['action'] === 'action_day_copy'){
					$this->action_day_copy($_POST);
						$msg = "スケジュールをコピーしました。";
						$this->session->unset_userdata('checker_ednm');
				// 確認者選択
				}else if($_POST['action'] === 'action_select_checker'){
				// 入力確認
				}else if($_POST['action'] === 'action_input_check'){
					return;
				// 相手先選択
				}else if($_POST['action'] === 'action_select_client'){
				}
			}
			if(isset($post_flg) && $post_flg=="1"){
				if (isset($errmsg)) { $data['errmsg'] = $errmsg; }
				if (isset($action_flg)) { $data['action_flg'] = $action_flg; }
			}else{
				if(isset($confirmer) &&  $confirmer !=""){
				}else if(isset($save_flg) && $save_flg !=""){
				}else{
				$this->session->unset_userdata('checker_ednm');
				}
				
				log_message('debug',"========== controllers plan index start ==========");
				// 初期化
				$this->load->library('plan_manager');
				$this->load->library('common_manager');
				$this->load->library('user_agent');
				$this->load->model('sgmtb010');
				$base_url = $this->config->item('base_url');
				$before_url = $this->agent->referrer();
				$calendar_url = $base_url."index.php/calendar/index";
				$top_url = $base_url."index.php/top/index";
				$data = NULL;
				$count = FALSE;
				// 引数チェック
				if(is_null($select_day)){
					// 選択日が無い場合にはシステム日（当日）を設定する
					$select_day = date('Ymd');
				}
				
				$this->session->unset_userdata('plan_count');
				// データカウンターチェック
				$session_data = array('plan_count' => 100);
				$this->session->set_userdata($session_data);
				log_message('debug',"plan_count = ".$this->session->userdata('plan_count'));

				// セッションに戻り先URLを保存
				$from_url = $this->config->item('base_url')."index.php/plan/confirmer/".$select_day;
				$session_data = array('from_url' => $from_url);
				$this->session->set_userdata($session_data);
	//			log_message('debug',"\$from_url = $from_url");

				// 共通初期化処理
				$data = $this->common_manager->init(SHOW_PLAN_A);
				
				// 選択日取得
				$data['select_day'] = $select_day;
				
				// DBデータ取得
				$shbn = $this->session->userdata('shbn');

				$tmp_flg=NULL;
				// フラグ判定
				if($tmp_flg){
					
				}else{
					// 通常
					$data['result_flg'] = 1;
					$data['action_plan'] = $this->plan_manager->set_plan_data($shbn, $select_day);
					
				}
				
				// セッション情報より社番を取得
				$kakninshbn = $this->session->userdata('kakninshbn');
				
				$this->session->unset_userdata('kakninshbn');
				$other_view = $this->session->userdata('other_view');
				$this->session->unset_userdata('other_view');
				
				// セッションに確認者社番がある場合
				if($kakninshbn){
					$data['kakninshbn'] = $kakninshbn;
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
				}else if($other_view === 'confirmer' AND empty($kakninshbn)){
					$data['kakninshbn'] = "";
					$data['kakninshnm'] = "";
				}else if(isset($data['action_plan'][0]['kakninshbn']) && $data['action_plan'][0]['kakninshbn'] != ''){
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
				
				if (isset($msg)) { $data['infomsg'] = $msg; }
				if (isset($errmsg)) { $data['errmsg'] = $errmsg; }
				if (isset($remainData)) { $plan['plan'] = $remainData; }
				if (isset($action_flg)) { $data['action_flg'] = $action_flg; }
			}
			

			
			// 画面表示
			log_message('debug',"========== controllers plan index end ==========");
			$this->display($data);

		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-INDEX'));
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
//			$this->load->view(MY_VIEW_LAYOUT, $data);
			$this->load->view(MY_VIEW_PLAN, $data, FALSE);

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-DISPLAY'));
			$this->error_view($e);
		}
	}

	function confirmer($select_day){
		try{
			log_message('debug',"========== controllers plan confirmer start ==========");
			$this->load->library('common_manager');
			$kakunin = "";
			$cnt = 0;
			// 確認者社番をセッションから取得
			$kakunin_shbn = $this->session->userdata('checker_shbn');
			// セッションから情報取得後セッション破棄
			$this->session->unset_userdata('checker_shbn');
			$this->session->unset_userdata('busyo_shbn');
			$this->session->unset_userdata('group_shbn');
			if($kakunin_shbn){
				foreach ($kakunin_shbn as $key => $value) {
					if(!isset($value)){
						break;
					}
					if($cnt != 0){
						$kakunin .= " ";
					}
					$kakunin .= $value;
					$cnt++;
				}
			}
			$session_data = array('kakninshbn' => $kakunin);
			$this->session->set_userdata($session_data);
			$session_data = array('other_view' => 'confirmer');
			$this->session->set_userdata($session_data);
			log_message('debug',"========== controllers plan confirmer end ==========");
			$this->index($select_day,"1");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-CONFIRMER'));
			$this->error_view($e);
		}
	}



	function new_action_view(){
		try{
			log_message('debug',"========== controllers plan new_action_view start ==========");

			// データカウンターセット（"00"固定）
			$data['count'] = "00";
			log_message('debug',"\$count = ".$data['count']);

			$this->load->view(MY_NEW_VIEW_PLAN_ACTION, $data, FALSE);

			log_message('debug',"========== controllers plan new_action_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-new_action_view'));
			//$this->error_view($e);
		}
	}

	function new_honbu_view(){
		try{
			log_message('debug',"========== controllers plan new_honbu_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB110';
			$data = NULL;

			$data['plan'] = $this->plan_manager->get_view_item($use_db);

			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			
			$this->load->helper('common');
			$data['uid'] = create_random_string();

			$this->load->view(MY_NEW_VIEW_PLAN_HONBU, $data, FALSE);
			log_message('debug',"========== controllers plan new_honbu_view end ==========");

			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-new_honbu_view'));
			//$this->error_view($e);
		}
	}

	function new_tenpo_view(){
		try{
			log_message('debug',"========== controllers plan new_tenpo_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB120';
			$data = NULL;

			$data['plan'] = $this->plan_manager->get_view_item($use_db);

			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			$this->load->helper('common');
			$data['uid'] = create_random_string();

			$this->load->view(MY_NEW_VIEW_PLAN_TENPO, $data, FALSE);
			log_message('debug',"========== controllers plan new_tenpo_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-new_tenpo_view'));
			//$this->error_view($e);
		}
	}

	function new_dairi_view(){
		try{
			log_message('debug',"========== controllers plan new_dairi_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB130';
			$data = NULL;

			$data['plan'] = $this->plan_manager->get_view_item($use_db);

			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			$this->load->helper('common');
			$data['uid'] = create_random_string();

			$this->load->view(MY_NEW_VIEW_PLAN_DAIRI, $data, FALSE);
			log_message('debug',"========== controllers plan new_dairi_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-new_dairi_view'));
			//$this->error_view($e);
		}
	}

	function new_gyousya_view(){
		try{
			log_message('debug',"========== controllers plan new_gyousya_view start ==========");
			// 初期化
			$data = NULL;

			// セッションよりデータ件数取得
			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);
			log_message('debug',"\$count = $count");
			// バックグラウンドカラー変更フラグ（登録済：１、未登録：０）
			$data['result_flg'] = 0;
			log_message('debug',"result_flg = ".$data['result_flg']);
			$this->load->helper('common');
			$data['uid'] = create_random_string();

			$this->load->view(MY_NEW_VIEW_PLAN_GYOUSYA, $data, FALSE);
			log_message('debug',"========== controllers plan new_gyousya_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-new_gyousya_view'));
			//$this->error_view($e);
		}
	}

	function new_office_view(){
		try{
			log_message('debug',"========== controllers plan new_office_view start ==========");
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
				$count = 99;
			}else{
				$count--;
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
			$extra = 'class="selected" title="作業内容" ';
			$data['sagyoniyo'] = $this->item_manager->set_dropdown_in_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$check,$tag_name,$extra);
			$this->load->helper('common');
			$data['uid'] = create_random_string();

			$this->load->view(MY_NEW_VIEW_PLAN_OFFICE, $data, FALSE);
			log_message('debug',"========== controllers plan new_office_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-new_office_view'));
			//$this->error_view($e);
		}
	}

	function action_move($post = NULL,$data_no = "00"){
		try{
			log_message('debug',"========== controllers plan action_move start ==========");
			// 引数チェック
			if(is_null($post) OR $data_no === "00"){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$shbn = $this->session->userdata('shbn');
			$move_day = "";
			$jyohonum = "";
			$edbn = "";

			// 移動日取得
			$variable_name = 'move_copy_day_'.$data_no;
			if(empty($post[$variable_name])){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}else{
				$move_day = substr($post[$variable_name],0,4).substr($post[$variable_name],5,2).substr($post[$variable_name],8,2);
			}
			// 情報No取得
			$variable_name = 'jyohonum_'.$data_no;
			if(!empty($post[$variable_name])){
				$jyohonum = $post[$variable_name];
			}
			// 枝番取得
			$variable_name = 'edbn_'.$data_no;
			if(!empty($post[$variable_name])){
				$edbn = $post[$variable_name];
			}
			// 確認者番取得
			$variable_name = 'kakninshbn_'.$data_no;
			$post[$variable_name] = $post['kakninshbn'];

			// 移動処理
			$this->plan_manager->action_move($shbn,$move_day,$jyohonum,$edbn,$post,$data_no);

			log_message('debug',"========== controllers plan action_move end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_move'));
			//$this->error_view($e);
		}
	}

	function action_copy($post = NULL,$data_no = "00"){
		try{
			log_message('debug',"========== controllers plan action_copy start ==========");
			// 引数チェック
			if(is_null($post) OR $data_no === "00"){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$shbn = $this->session->userdata('shbn');
			$move_day = "";
			$jyohonum = "";
			$edbn = "";

			// 移動日取得
			$variable_name = 'move_copy_day_'.$data_no;
			if(empty($post[$variable_name])){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}else{
				$move_day = substr($post[$variable_name],0,4).substr($post[$variable_name],5,2).substr($post[$variable_name],8,2);
			}
			// コピー処理
			$this->plan_manager->action_move($shbn,$move_day,$jyohonum,$edbn,$post,$data_no);

			log_message('debug',"========== controllers plan action_copy end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_copy'));
			//$this->error_view($e);
		}
	}
	/*
	function action_delete($post = NULL,$data_no = "00"){
		try{
			log_message('debug',"========== controllers plan action_delete start ==========");
			// 引数チェック
			if(is_null($post) OR $data_no === "00"){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$dbname = "";
			$jyohonum = "";
			$edbn = "";

			// 情報Noを取得し、エスケープされた情報NOでない場合に削除処理を行う
			$variable_name = 'jyohonum_'.$data_no;
			if($post[$variable_name] === MY_JYOHONUM_ESC OR empty($post[$variable_name])){
				return;
			}

			$jyohonum = $post[$variable_name];
			$variable_name = 'edbn_'.$data_no;
			$edbn = $post[$variable_name];
			$variable_name = 'action_type_'.$data_no;
			$dbname = $post[$variable_name];

			$this->plan_manager->delete_action($dbname,$jyohonum,$edbn);

			log_message('debug',"========== controllers plan action_delete end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
		}
	}
	*/
	function action_delete(){
		log_message('debug',"========== controllers plan action_delete start ==========");
		// 初期化
//		log_message('debug',$_POST['dbname']);
//		log_message('debug',$_POST['jyohonum']);
//		log_message('debug',$_POST['edbn']);
		$dbname = $_POST['dbname'];
		$jyohonum = $_POST['jyohonum'];
		$edbn = $_POST['edbn'];
		$this->load->library('plan_manager');
		$this->plan_manager->delete_action($dbname,$jyohonum,$edbn);
		log_message('debug',"========== controllers plan action_delete end ==========");
//		return;
	}

    /*
     * 削除対象が定期予定かそうでないかを判定する
     */
    function check_delete(){
		log_message('debug',"========== " . __METHOD__ . " start ==========");
		// 初期化
		log_message('debug',$_POST['dbname']);
		log_message('debug',$_POST['jyohonum']);
//		log_message('debug',$_POST['edbn']);
		
        $dbname = $_POST['dbname'];
		$jyohonum = $_POST['jyohonum'];
		$this->load->library('plan_manager');
		
        $ret = $this->plan_manager->check_delete_action($dbname, $jyohonum);
        if ( $ret != '0'){
            log_message('debug',"========== " . __METHOD__ . " return " . $ret . " ==========");
            echo $ret;
        }else{
            log_message('debug',"========== " . __METHOD__ . " return 0 ==========");
            echo '0';
        }

        log_message('debug',"========== " . __METHOD__ . " end ==========");

        /*
         * ajaxサンプル
         * 
        http://www.ibm.com/developerworks/jp/web/library/wa-aj-codeigniter/
        public function username_taken()
        {
          $this->load->model('MUser', '', TRUE);
          $username = trim($_POST['username']);
          // if the username exists return a 1 indicating true
          if ($this->MUser->username_exists($username)) {
            echo '1';
          }
        }
        */
        
	}

	function action_regular_delete(){
		log_message('debug',"========== " . __METHOD__ . " start ==========");
		// 初期化
		log_message('debug',$_POST['groupid']);
		$groupid = $_POST['groupid'];
        $edbn = $_POST['edbn'];
		$this->load->library('plan_manager');
		$this->plan_manager->regular_delete_action($groupid, $edbn);
		log_message('debug',"========== " . __METHOD__ . " end ==========");
//		return;
	}

    function action_submit($post){
		try{
			log_message('debug',"========== controllers plan action_submit start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			
			// 初期化
			$old_data = $post;
			$this->load->library('plan_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');
			$data_no = "";
			$group_count = 0;
			$mes ="スケジュールを登録しました。";

			// 移動日取得
			$select_day = $post['select_day'];
			foreach ($post as $key => $value) {
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

			// ユニット長社番チェック（確認者番に無ければ追加）
			$kakninshbn = $this->plan_manager->get_confirmer_no($shbn,$post['kakninshbn']);

            // 時刻重複事前チェック
            $stt = array();
            $ett = array();
            $i = 0;
			foreach ($data as $key => $value) {
				if(!empty($value['data_no'])){
					$stt[$i] = strtotime($select_day . 't' . $data[$key]['sth_'.$value['data_no']].$data[$key]['stm_'.$value['data_no']] . '00');
					$ett[$i] = strtotime($select_day . 't' . $data[$key]['edh_'.$value['data_no']].$data[$key]['edm_'.$value['data_no']] . '00');
                    $i++;
                }
            }
            for($j=0;$j<$i;$j++){
                for($k=0;$k<$i;$k++){
                    if($stt[$k] < $stt[$j] && $stt[$j] < $ett[$k]){
						$msg ="開始時刻と終了時刻の時間帯で重複しているものがあります。";
						return $msg;
                    }
                    if($stt[$k] < $ett[$j] && $ett[$j] < $ett[$k]){
						$msg ="開始時刻と終了時刻の時間帯で重複しているものがあります。";
						return $msg;
                    }
                }
            }

			// 登録処理
			foreach ($data as $key => $value) {
				if(!empty($value['data_no'])){
					//チェック処理					
					$start_time =$data[$key]['sth_'.$value['data_no']].$data[$key]['stm_'.$value['data_no']];
					$end_time =  $data[$key]['edh_'.$value['data_no']].$data[$key]['edm_'.$value['data_no']];
					if(intval($start_time) >= intval($end_time)){
						$mes ="開始時刻と終了時刻の時系列が誤っています。";
						// 共通初期化処理
						$this->load->library('common_manager');
						$o_data = $this->common_manager->init(SHOW_PLAN_A);
						foreach($old_data as $key=>$value){
							$o_data[$key] = $value;
						}
						return $mes;
					}
					
					
					// エスケープされた情報No、枝番を戻す
					$variable_name = 'jyohonum_'.$value['data_no'];
					if($data[$key][$variable_name] === MY_JYOHONUM_ESC){
						$data[$key][$variable_name] = "";
						$variable_name = 'edbn_'.$value['data_no'];
						$data[$key][$variable_name] = "";
					}
					// 確認者番
					$variable_name = 'kakninshbn_'.$value['data_no'];
//					$data[$key][$variable_name] = $post['kakninshbn'];
					$data[$key][$variable_name] = $kakninshbn;
					// 活動区分
					$action_name = 'action_type_'.$value['data_no'];
					log_message('debug',"\$action_name = $action_name");
					if($value[$action_name] === 'srntb110'){
						$this->plan_manager->record_honbu_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb120'){
						$this->plan_manager->record_tenpo_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb130'){
						$this->plan_manager->record_dairi_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb140'){
						$this->plan_manager->record_office_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb160'){
						$this->plan_manager->record_gyousya_data($shbn,$data[$key],$value['data_no']);
					}
				}
			}
			log_message('debug',"========== controllers plan action_submit end ==========");
			return $mes;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_submit'));
			//$this->error_view($e);
		}
	}

	function action_day_move($post){
		try{
			log_message('debug',"========== controllers plan action_day_move start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');
			$move_day = $post['move_day'];
			$data_no = "";
			$group_count = 0;
			$check_res = FALSE;

			// 移動日取得
			$year = substr($post['move_day'],0,4);
			$month = substr($post['move_day'],4,2);
			$day = substr($post['move_day'],6,2);
			$check_res = checkdate($month, $day, $year);
			if($check_res === TRUE){
				$move_day = $post['move_day'];
			}else if($check_res === FALSE){
				log_message('copy_day is not existence');
				return;
			}

			foreach ($post as $key => $value) {
				if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action'){
					continue;
				}
				// キーの後ろ2桁を取得して同じ物をグルーピングする
				if($data_no === substr($key, -2)){
					$data[$group_count][$key] = $value;
				}else{
					$data[$group_count]['data_no'] = $data_no;
					$data[$group_count][("ymd_".$data_no)] = $move_day;
					$group_count++;
					$data[$group_count][$key] = $value;
				}
				$data_no = substr($key, -2);
			}

			// ユニット長社番チェック（確認者番に無ければ追加）
			$kakninshbn = $this->plan_manager->get_confirmer_no($shbn,$post['kakninshbn']);

			// 登録処理
			foreach ($data as $key => $value) {
				if(!empty($value['data_no'])){
					// 情報No取得
					$variable_name = 'jyohonum_'.$value['data_no'];
					$data[$key][$variable_name] = "";
					// 枝番取得
					$variable_name = 'edbn_'.$value['data_no'];
					$data[$key][$variable_name] = "";
					// 確認者番
					$variable_name = 'kakninshbn_'.$value['data_no'];
//					$data[$key][$variable_name] = $post['kakninshbn'];
					$data[$key][$variable_name] = $kakninshbn;
					// 活動区分
					$action_name = 'action_type_'.$value['data_no'];
					log_message('debug',"\$action_name = $action_name");
					if($value[$action_name] === 'srntb110'){
						$this->plan_manager->record_honbu_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb120'){
						$this->plan_manager->record_tenpo_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb130'){
						$this->plan_manager->record_dairi_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb140'){
						$this->plan_manager->record_office_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb160'){
						$this->plan_manager->record_gyousya_data($shbn,$data[$key],$value['data_no']);
					}
				}
			}
			// 削除処理
			$res = $this->common->get_select_day_plan_data($shbn,$post['select_day']);
			if($res === FALSE){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			foreach ($res as $key => $value){
				$this->plan_manager->delete_action($value['dbname'],$value['jyohonum'],$value['edbn']);
			}

			log_message('debug',"========== controllers plan action_day_move end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_day_move'));
			//$this->error_view($e);
		}
	}

	function action_day_delete($post = NULL){
		try{
			log_message('debug',"========== controllers plan action_day_delete start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');

			$res = $this->common->get_select_day_plan_data($shbn,$post['select_day']);
			if($res === FALSE){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			foreach ($res as $key => $value){
				$this->plan_manager->delete_action($value['dbname'],$value['jyohonum'],$value['edbn']);
			}

			log_message('debug',"========== controllers plan action_day_delete end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_day_delete'));
			//$this->error_view($e);
		}
	}

	function action_day_copy($post = NULL){
		try{
			log_message('debug',"========== controllers plan action_day_copy start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');
			$data_no = "";
			$group_count = 0;
			$check_res = FALSE;
			$data = array();
			// ユニット長社番チェック（確認者番に無ければ追加）
			$kakninshbn = $this->plan_manager->get_confirmer_no($shbn,$post['kakninshbn']);

			// 一時テーブルに保存
			$select_day = $post['select_day'];
			foreach ($post as $key => $value){
				if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action' OR $key === 'copy_day'){
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
					// 情報No、の採番
					$variable_name = 'jyohonum_'.$value['data_no'];
					if(empty($data[$key][$variable_name])){
						$data[$key][$variable_name] = MY_JYOHONUM_ESC;
					}
					// 確認者番
					$variable_name = 'kakninshbn_'.$value['data_no'];
					$data[$key][$variable_name] = $kakninshbn;
					// 活動区分
					$action_name = 'action_type_'.$value['data_no'];
					log_message('debug',"\$action_name = $action_name");
					if($value[$action_name] === 'srntb110'){
					}else if($value[$action_name] === 'srntb120'){
					}else if($value[$action_name] === 'srntb130'){
					}else if($value[$action_name] === 'srntb140'){
					}else if($value[$action_name] === 'srntb160'){
					}
				}
			}


			// 初期化
			$data = array();
			$data_no = "";
			$group_count = 0;
			// 移動日取得
			$year = substr($post['copy_day'],0,4);
			$month = substr($post['copy_day'],4,2);
			$day = substr($post['copy_day'],6,2);
			$check_res = checkdate($month, $day, $year);
			if($check_res === TRUE){
				$select_day = $post['copy_day'];
			}else if($check_res === FALSE){
				log_message('copy_day is not existence');
				return;
			}
			foreach ($post as $key => $value) {
				if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action' OR $key === 'copy_day'){
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
					// 情報No、枝番を初期化する
					$variable_name = 'jyohonum_'.$value['data_no'];
					$data[$key][$variable_name] = "";
					$variable_name = 'edbn_'.$value['data_no'];
					$data[$key][$variable_name] = "";
					// 確認者番
					$variable_name = 'kakninshbn_'.$value['data_no'];
					$data[$key][$variable_name] = $kakninshbn;
					// 活動区分
					$action_name = 'action_type_'.$value['data_no'];
					log_message('debug',"\$action_name = $action_name");
					if($value[$action_name] === 'srntb110'){
						$this->plan_manager->record_honbu_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb120'){
						$this->plan_manager->record_tenpo_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb130'){
						$this->plan_manager->record_dairi_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb140'){
						$this->plan_manager->record_office_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb160'){
						$this->plan_manager->record_gyousya_data($shbn,$data[$key],$value['data_no']);
					}
				}
			}
			log_message('debug',"========== controllers plan action_day_copy end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_day_copy'));
			//$this->error_view($e);
		}
	}


	function action_input_check($post = NULL){
		try{
			log_message('debug',"========== controllers plan action_select_client start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
//			$this->load->library('plan_manager');
			$shbn = $this->session->userdata('shbn');
			$base_url = $this->config->item('base_url');
			$data_no = "";
			$group_count = 0;
			// 一時テーブルに保存
//			$select_day = $post['select_day'];
			foreach ($post as $key => $value) {
//				if($key === 'select_day'){
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

			foreach ($data as $key => $value) {
				foreach ($value as $key1 => $value1) {
					log_message('debug',"\$value1 = $value1");
				}

			}


			log_message('debug',"========== controllers plan action_select_client end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-action_input_check'));
			//$this->error_view($e);
		}
	}



	/**
	 * 社番・選択日有無チェック
	 *
	 * @access private
	 * @param  string $shbn 社番
	 * @param  string $select_day 選択日付（YYYYMMDD）
	 * @return bool $result
	 */
/*
	 private function _check_argument($shbn,$select_day)
	{
		// 初期化
		$result = FALSE;

		// 社番有無確認
		if(is_null($shbn))
		{
			return $result;
		// 日付情報有無確認
		}else if(is_null($select_day)){
			return $result;
		}else{
			$result = TRUE;
		}
		return $result;
	}
*/

	function check_view($select_day = NULL,$shbn = NULL){
		try{
			log_message('debug',"========== controllers plan check_view start ==========");
			// 引数チェック
			if(is_null($select_day) OR is_null($shbn)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 自分の社番をセッションより取得
			$my_shbn = $this->session->userdata('shbn');
			// セッションに部下の社番を保存
			$session_data = array('others_shbn' => $shbn);
			$this->session->set_userdata($session_data);
			
			header("Location: ".$this->config->item('base_url')."index.php/plan_view/index/".$select_day."/".$shbn);
			
			log_message('debug',"========== controllers plan check_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'PLAN-check_view'));
			//$this->error_view($e);
		}
	}
	
}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
