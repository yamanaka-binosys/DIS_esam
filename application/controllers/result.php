<?php

class Result extends MY_Controller {

	function index($select_day = NULL,$confirmer=NULL){
		$this->load->helper('common');
		try{
			// アクションチェック前初期化
			$plan_flg = FALSE;
			$action_flg="0";
			$data = NULL;
			
			//セッションに確認者チェック状況がない場合は保存データより探してセッションにセット
			$shbn = $this->session->userdata('shbn');
			$checker_data = "";
			$checker_data = $this->session->userdata('checker_ednm');
			if(isset($checker_data) && $checker_data == ""){
			
				$this->load->model('srntb010');
				$this->load->model('srntb020');
				$this->load->model('srntb030');
				$this->load->model('srntb040');
				$this->load->model('srntb060');
				
				$checker_data = $this->srntb010->get_checkar_data($shbn,$select_day);
				if(!$checker_data){
					$checker_data = $this->srntb020->get_checkar_data($shbn,$select_day);
					if(!$checker_data){
					
						$checker_data = $this->srntb030->get_checkar_data($shbn,$select_day);
						if(!$checker_data){
							$checker_data = $this->srntb040->get_checkar_data($shbn,$select_day);
							if(!$checker_data){
								$checker_data = $this->srntb060->get_checkar_data($shbn,$select_day);
							}
						}
					}
				}
				
				//セッションにセット
				$select_check = explode('/',$checker_data[0]['checker_edbn']);
				array_pop($select_check);
				
				$session_data = array('checker_ednm' => $select_check);
				$this->session->set_userdata($session_data);
			}
			
			// 登録ボタン押下判定
			if($_POST){
				log_message('debug', "************" . $_POST['action'] . "************" );
				// 一日分の日報の削除
				if($_POST['action'] === 'action_day_delete'){
					$this->session->unset_userdata('checker_ednm');
					$this->action_day_delete($_POST);
					$msg = "日報を削除しました。";
				// 一日分の日報のコピー
				}else if($_POST['action'] === 'action_day_copy'){
					$this->session->unset_userdata('checker_ednm');
					$this->action_day_copy($_POST);
					$msg = "日報をコピーしました。";
					$save_flg ="1";
				// 一日分の日報の登録
				}else if($_POST['action'] === 'action_submit'){
			
				
					$msg = $this->action_submit($_POST);
					$action_flg="1";
					

					if($msg=="開始時刻と終了時刻の時系列が誤っています。"){
						$this->load->library('result_manager');
						$this->load->library('common_manager');
						$data =  $this->result_manager->post_dataset($_POST);
						$data['result_flg'] = 1;
						// 選択日取得
						$data['select_day'] = $select_day;
						$shbn = $this->session->userdata('shbn');
						$data['errmsg'] = $msg;
						$post_flg="1";
						
 						$errmsg = $msg;
 						unset($msg);
					}
					
				// スケジュールデータ取得
				}else if($_POST['action'] === 'get_plan_data'){
					$this->session->unset_userdata('checker_ednm');
					$plan_flg = TRUE;
					//$data['aciton_flg']="1";
					/*
				}else if($_POST['action'] === 'file_delete_client'){
					$this->action_select_client($_POST);
					*/
				}
			}
			
			log_message('debug',"========== controllers result index start ==========");
			// 初期化
			$this->load->library('common_manager');
			$this->load->model(array('sgmtb010', 'srwtb010'));
			$this->load->library('user_agent');
			$base_url = $this->config->item('base_url');
			$before_url = $this->agent->referrer();
			$calendar_url = $base_url."index.php/calendar/index";
			$top_url = $base_url."index.php/top/index";
			//$data = NULL;
			$count = FALSE;
			$check_hold = '0';
			
			if(isset($confirmer) &&  $confirmer !=""){
			}else if(isset($save_flg) && $save_flg !=""){
			}else{
			$this->session->unset_userdata('checker_ednm');
			}

			// 引数チェック
			if(is_null($select_day)){
				// 選択日が無い場合にはシステム日（当日）を設定する
				$select_day = date('Ymd');
			}
			
			$this->session->unset_userdata('result_count');
			// データカウンターチェック
			$session_data = array('result_count' => 100);
			$this->session->set_userdata($session_data);
			log_message('debug',"result_count = ".$this->session->userdata('result_count'));
		
			// セッションに戻り先URLを保存
			$from_url = $this->config->item('base_url')."index.php/result/confirmer/".$select_day;
			$session_data = array('from_url' => $from_url);
			$this->session->set_userdata($session_data);

			if(isset($post_flg) && $post_flg="1"){
				// セッションよりコメント入力有無を取得
				$write_flg = $this->session->userdata('write_flg');
				if($write_flg === FALSE){
					$data['write_flg'] = 0;
				}else{
					$data['write_flg'] = 1;
				}
				//見積もりファイル情報取得
				$this->load->model('srntb070');
				$data['mitumori_file']=$this->srntb070->search_file($select_day,$shbn);
				
				// 社番をセッションより取得
				if($this->session->userdata('others_shbn') === FALSE){
					$shbn = $this->session->userdata('shbn');
				}else{
					$shbn = $this->session->userdata('others_shbn');
					$this->session->unset_userdata('others_shbn');
				}
			}else{
				// 共通初期化処理
				$data = $this->common_manager->init(SHOW_RESULT_A);
				
				// 選択日取得
				$data['select_day'] = $select_day;

				// 社番をセッションより取得
				if($this->session->userdata('others_shbn') === FALSE){
					$shbn = $this->session->userdata('shbn');
				}else{
					$shbn = $this->session->userdata('others_shbn');
					$this->session->unset_userdata('others_shbn');
				}
				//見積もりファイル情報取得
				$this->load->model('srntb070');
				$data['mitumori_file']=$this->srntb070->search_file($select_day,$shbn);
				
				// セッションよりコメント入力有無を取得
				$write_flg = $this->session->userdata('write_flg');
				if($write_flg === FALSE){
					$data['write_flg'] = 0;
				}else{
					$data['write_flg'] = 1;
				}
							// フラグ判定
				if($plan_flg){
					$plan_flg = FALSE;
					$data['action_result'] = $this->result_manager->set_plan_data($shbn, $select_day);
				}else{
					// 通常
					$data['action_result'] = $this->result_manager->set_result_data($shbn, $select_day);
				}
			
				// 一時保存フラグ判定
				if ($this->session->userdata('check_hold') !== false)  {
					log_message('debug', '★★★★★★★★★★★★★★session: '.$this->session->userdata('check_hold').'★★★★★★★★★★★★★★');
					$data['check_hold'] = $this->session->userdata('check_hold');
					$this->session->unset_userdata('check_hold');
				} else {
					
					$filter = create_function('$r', 'return $r["recode_flg"] == 1;');
					$registered_data = is_null($data['action_result']) ? array() : array_filter($data['action_result'], $filter);
					// データなし
					if (empty($data['action_result'])) {
						log_message('debug', '★★★★★★★★★★★★★★nodata★★★★★★★★★★★★★★');
						$data['check_hold'] = '0';
					} else {
						// 登録データなし
						if (empty($registered_data)) {
							log_message('debug', '★★★★★★★★★★★★★★no registered★★★★★★★★★★★★★★');
							$data['check_hold'] = '0';
						} else {
							$data['check_hold'] = $this->srwtb010->has_confirmer($shbn, $select_day) ? '0' : '1';
							log_message('debug', '★★★★★★★★★★★★★★ checking: '.$data['check_hold'].'★★★★★★★★★★★★★★');
						}
					}
				}


			}
			
			// セッション情報より社番を取得
			$kakninshbn = $this->session->userdata('kakninshbn');
			$this->session->unset_userdata('kakninshbn');
			$other_view = $this->session->userdata('other_view');
			$this->session->unset_userdata('other_view');

			$confirmer_no = $this->sgmtb010->get_unit_cho_shbn($shbn);
			// セッションに確認者社番がある場合
			if($kakninshbn){
				$data['kakninshbn'] = $kakninshbn;
				if(strpos($data['kakninshbn']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					if($data['kakninshbn'] === $confirmer_no){
						$data['kakninshnm'] = "";
					}else{
						$data['kakninshnm'] = $this->common_manager->create_name($data['kakninshbn']);
						
					}
				}
			}else if($other_view === 'confirmer' AND empty($kakninshbn)){
				$data['kakninshbn'] = "";
				$data['kakninshnm'] = "";
			}else if(isset($data['action_result'][0]['kakninshbn']) && $data['action_result'][0]['kakninshbn'] != ''){
				$data['kakninshbn'] = $data['action_result'][0]['kakninshbn'];
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
			if (isset($action_flg)) { $data['action_flg'] = $action_flg; }
			// 画面表示
			log_message('debug',"========== controllers result index end ==========");
			$this->display($data);
			
		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'result-index'));
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
	function display($data = NULL)
	{
		try
		{
			log_message('debug',"========== controllers result display start ==========");
			// 引数チェック
			if(is_null($data)){
				log_message('debug',"argument data is NULL");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			log_message('debug',"========== controllers result display end ==========");
			// 表示処理
//			$this->load->view(MY_VIEW_LAYOUT, $data);
			$this->load->view(MY_VIEW_RESULT, $data, FALSE);

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-display'));
			//$this->error_view($e);
		}
	}

	function new_action_view(){
		try{
			log_message('debug',"========== controllers result new_action_view start ==========");

			// データカウンターセット（"00"固定）
			$data['count'] = "00";
			log_message('debug',"\$count = ".$data['count']);

			$this->load->view(MY_NEW_VIEW_RESULT_ACTION, $data, FALSE);

			log_message('debug',"========== controllers result new_action_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-new_action_view'));
			//$this->error_view($e);
		}
	}

	function new_honbu_view(){
		try{
			log_message('debug',"========== controllers result new_honbu_view start ==========");
			// 初期化
			$this->load->library('result_manager');
			$use_db = 'SRNTB010';
			$data = NULL;

			$data['result'] = $this->result_manager->get_view_item($use_db);

			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_count' => $count);
			$this->session->set_userdata($session_data);

			$data['admin_flg'] = $this->session->userdata('admin_flg');
			log_message('debug',"\$count = $count");

			// セッションよりコメント入力有無を取得
			$write_flg = $this->session->userdata('write_flg');
			if($write_flg === FALSE){
				$data['write_flg'] = 0;
			}else{
				$data['write_flg'] = 1;
			}
			log_message('debug',"write_flg = ".$data['write_flg']);
			
			$this->load->helper('common');
			$data['uid'] = create_random_string();

			$this->load->view(MY_NEW_VIEW_RESULT_HONBU, $data, FALSE);
			log_message('debug',"========== controllers result new_honbu_view end ==========");

			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-new_honbu_view'));
			//$this->error_view($e);
		}
	}

	function new_tenpo_view(){
		try{
			log_message('debug',"========== controllers result new_tenpo_view start ==========");
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
			$count = $this->session->userdata('result_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_count' => $count);
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
			$this->load->helper('common');
			$data['uid'] = create_random_string();
			$this->load->view(MY_NEW_VIEW_RESULT_TENPO, $data, FALSE);
			log_message('debug',"========== controllers result new_tenpo_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-new_tenpo_view'));
			//$this->error_view($e);
		}
	}

	function new_dairi_view(){
		try{
			log_message('debug',"========== controllers result new_dairi_view start ==========");
			// 初期化
			$this->load->library('result_manager');
			$use_db = 'SRNTB030';
			$data = NULL;

			$data['result'] = $this->result_manager->get_view_item($use_db);

			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_count' => $count);
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
			$this->load->helper('common');
			$data['uid'] = create_random_string();
			$this->load->view(MY_NEW_VIEW_RESULT_DAIRI, $data, FALSE);
			log_message('debug',"========== controllers result new_dairi_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-new_dairi_view'));
			//$this->error_view($e);
		}
	}

	function new_gyousya_view(){
		try{
			log_message('debug',"========== controllers result new_gyousya_view start ==========");
			// 初期化
			$data = NULL;

			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_count' => $count);
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
			$this->load->helper('common');
			$data['uid'] = create_random_string();
			$this->load->view(MY_NEW_VIEW_RESULT_GYOUSYA, $data, FALSE);
			log_message('debug',"========== controllers result new_gyousya_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-new_gyousya_view'));
			//$this->error_view($e);
		}
	}

	function new_office_view(){
		try{
			log_message('debug',"========== controllers result new_office_view start ==========");
			$this->load->library('item_manager');
			$tag_name = NULL;
			$check = NULL;
			$count = 1;
			if($count == 0){
				log_message('debug',"Exception count = 0");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}

			// セッションよりデータ件数取得
			$count = $this->session->userdata('result_count');
			if($count === FALSE){
				$count = 99;
			}else{
				$count--;
			}
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('result_count' => $count);
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
			$extra = 'class="selected" title="作業内容" ';
			$data['sagyoniyo'] = $this->item_manager->set_dropdown_in_db_string(MY_RESULT_SAGYONAIYO_KBN,$tag_name,$check,$tag_name,$extra);
			$this->load->helper('common');
			$data['uid'] = create_random_string();
			$this->load->view(MY_NEW_VIEW_RESULT_OFFICE, $data, FALSE);
			log_message('debug',"========== controllers result new_office_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-new_office_view'));
			//$this->error_view($e);
		}
	}

	/**
	 * 登録処理
	 *
	 */
	function action_submit($post = NULL){
		try{
			log_message('debug',"========== controllers result action_submit start ==========");
			// 引数チェック
			if(is_null($post) ){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$old_data = $post;
			$this->load->library('result_manager');
			$this->load->model(array('common','srwtb010'));
			$shbn = $this->session->userdata('shbn');
			$data_no = "";
			$group_count = 0;
			$confirmer_data = array();
			$hold_flg = '1';
			if ($post['check_hold'] == '1') {
				$msg = "日報を一時保存しました。";
			} else {
				$msg = "日報を登録しました。";
			}
			// 移動日取得
			$select_day = $post['select_day'];
			foreach ($post as $key => $value) {

				if($key === 'select_day'
					 OR $key === 'kakninshbn'
					 OR $key === 'kakninshnm'
					 OR $key === 'move_day'
					 OR $key === 'action'
					 OR $key === 'copy_day'
					 OR $key === 'check_hold'
					 OR $key === 'tempfile'
					 OR $key === 'filenum'
					 ){
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
			$kakninshbn = $this->result_manager->get_confirmer_no($shbn,$post['kakninshbn']);

			// 確認者情報登録処理
			if($post['check_hold'] == '1'){
				$this->srwtb010->delete_srwtb010_data($shbn, $select_day);
				$hold_flg = '1';
			} else {
				$hold_flg = '0';
			}

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
						$msg ="開始時刻と終了時刻の時系列が誤っています。";
						return $msg;
					}
                    
					$tmp_data = NULL;
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
					// 一時保存フラグ
					$variable_name = 'hold_flg_'.$value['data_no'];
					$data[$key][$variable_name] = $hold_flg;

                    // ここで登録済みの時刻とブッキングしていないか確認する。ブッキングしていたら真
                    if($this->result_manager->st_et_check($shbn, $select_day, $start_time, $end_time, $value[$action_name])){
						$msg ="開始時刻と終了時刻の時間帯は既に登録があります。";
						return $msg;
                    }

                    
                    if($value[$action_name] === 'srntb010'){
						
						$motojyohonum = $this->result_manager->record_honbu_data($shbn,$data[$key],$value['data_no']);
						$this->result_manager->record_honbu_rireki($shbn,$data[$key],$value['data_no'],$motojyohonum);
						
					}else if($value[$action_name] === 'srntb020'){
						
						$tmp_data = $this->result_manager->record_tenpo_data($shbn,$data[$key],$value['data_no']);
						
					}else if($value[$action_name] === 'srntb030'){
						
						$motojyohonum = $this->result_manager->record_dairi_data($shbn,$data[$key],$value['data_no']);
						$this->result_manager->record_dairi_rireki($shbn,$data[$key],$value['data_no'],$motojyohonum);
						
					}else if($value[$action_name] === 'srntb040'){
						$this->result_manager->record_office_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb060'){
						$this->result_manager->record_gyousya_data($shbn,$data[$key],$value['data_no']);
					}
				}
			}
			
			//確認者登録処理
			if($post['check_hold'] == '0'){
				$confirmer_data['shbn'] = $shbn;
				$confirmer_data['select_day'] = $post['select_day'];
				$confirmer_data['kakninshbn'] = $kakninshbn;
				$this->result_manager->record_confirmer_data($confirmer_data);
			}
			
			if(isset($_FILES) && $_FILES['tempfile']['name'] !=""){
				$this->load->library('file_uploade_manager');
				$this->load->model('srntb070');
				$update_flg ="";
				//更新判定　hiddenのfilenumで判定
				if(isset($post['filenum']) && $post['filenum'] != ""){
					$update_flg = "1";
					$file_data['filenum']=$post['filenum'];
					$up_flg="1";
				}else{
					//ファイルNoの最大を取得
					$file_data['filenum']=$this->srntb070->max_filenum();
					$up_flg="0";
				}
				if($file_data['filenum']==NULL){
				$file_data['filenum'] = '0';
				
				}
				// 添付ファイル情報
				if(!empty($_FILES['tempfile']['name'])){
				
					$post['file'] = $_FILES['tempfile']['name'];
					log_message('debug',"tmp_file_name = ".$post['file']);
					// ファイルアップロード処理
					$tmp_data = array(
							'tmp_name'  => $_FILES['tempfile']['tmp_name'],
							'file_name' => $_FILES['tempfile']['name'],
							'dir_name'  => 'result',
							'jyohonum'  => $select_day.$shbn,
							'uploadtime'=> date('YmdHis'),
							'flg'		=> $up_flg
					);
					
					$post['file'] = $this->file_uploade_manager->result_file_upload($tmp_data);
				}else{
					$post['file'] = NULL;
				}
				
				
				if($post['file'] != ""){
					//日報の日付を取得
					$file_data['ymd'] = $select_day;
					$file_data['shbn'] = $shbn;
					$file_data['tempfile'] = $post['file'];
					$file_data['uploadtime'] = $tmp_data['uploadtime'];
					
					if($update_flg == "1"){
						//$file_data['filenum'] = $post['filenum'];
						$this->srntb070->update_file($file_data);
					}else{
						$this->srntb070->insert_file($file_data);
					}
				}else{
					//添付なし
				}
			}
					
			//確認者保存処理
			$checker_edbn = $this->session->userdata('checker_ednm');
			$checker ="";
			foreach($checker_edbn as $key => $value){
				$checker .= $value."/";
			}

			$this->load->model('srntb010');
			$this->load->model('srntb020');
			$this->load->model('srntb030');
			$this->load->model('srntb040');
			$this->load->model('srntb060');
			
			$this->srntb010->update_checkar_data($select_day,$shbn,$checker);
			$this->srntb020->update_checkar_data($select_day,$shbn,$checker);
			$this->srntb030->update_checkar_data($select_day,$shbn,$checker);
			$this->srntb040->update_checkar_data($select_day,$shbn,$checker);
			$this->srntb060->update_checkar_data($select_day,$shbn,$checker);
			
			
			
			log_message('debug',"========== controllers result action_submit end ==========");
			return $msg;
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
		}
	}

	function action_move($post = NULL,$data_no = "00"){
		try{
			log_message('debug',"========== controllers result action_move start ==========");
			// 引数チェック
			if(is_null($post) OR $data_no === "00"){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('result_manager');
			$move_day = "";
			$jyohonum = "";
			$edbn = "";

			// 移動日取得
			$variable_day = 'move_copy_day_'.$data_no;
			if(empty($post[$variable_day])){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}else{
				$move_day = $post[$variable_day];
			}
			// 情報No取得
			$variable_day = 'jyohonum_'.$data_no;
			if(empty($post[$variable_day])){
				$jyohonum = $post[$variable_day];
			}
			// 情報No取得
			$variable_day = 'edbn_'.$data_no;
			if(empty($post[$variable_day])){
				$edbn = $post[$variable_day];
			}

			$this->result_manager->move($move_day,$jyohonum,$edbn,$post);

			log_message('debug',"========== controllers result action_move end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-action_move'));
			//$this->error_view($e);
		}
	}

	function action_day_delete($post = NULL){
		try{
			log_message('debug',"========== controllers result action_day_delete start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('result_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');
			$select_day = $post['select_day'];

			$res = $this->common->get_select_day_result_data($shbn,$post['select_day']);
			if($res === FALSE){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			foreach ($res as $key => $value){
				$this->result_manager->delete_action($value['dbname'],$value['jyohonum'],$value['edbn']);
			}

			$this->result_manager->delete_confirmer_data($shbn,$select_day);
			
			$this->delete_file_action();

			log_message('debug',"========== controllers result action_day_delete end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-action_day_delete'));
			//$this->error_view($e);
		}
	}

	function action_day_copy($post = NULL){
		try{
			log_message('debug',"========== controllers result action_day_copy start ==========");
			// 引数チェック
			if(is_null($post) ){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('result_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');
			$data_no = "";
			$group_count = 0;
			$data = array();
			$confirmer_data = array();
			$check_res = FALSE;
			$hold_flg = '1';
			// ユニット長社番チェック（確認者番に無ければ追加）
			$kakninshbn = $this->result_manager->get_confirmer_no($shbn,$post['kakninshbn']);

			// 一時テーブルに保存
			$select_day = $post['select_day'];
			foreach ($post as $key => $value) {
				if($key === 'select_day'
					 OR $key === 'kakninshbn'
					 OR $key === 'kakninshnm'
					 OR $key === 'move_day'
					 OR $key === 'action'
					 OR $key === 'copy_day'
					 OR $key === 'check_hold'
					 OR $key === 'filenum'){
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
					log_message('debug',"\$value[$action_name] = $value[$action_name]");
					if($value[$action_name] === 'srntb010'){
						$data[$key]['hold_flg_'.$value['data_no']] = 1; // hold_flgは必ずTRUE
					//	$this->result_manager->record_tmp_honbu_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb020'){
						$data[$key]['hold_flg_'.$value['data_no']] = 1; // hold_flgは必ずTRUE
					//	$this->result_manager->record_tmp_tenpo_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb030'){
						$data[$key]['hold_flg_'.$value['data_no']] = 1; // hold_flgは必ずTRUE
					//	$this->result_manager->record_tmp_dairi_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb040'){
						$data[$key]['hold_flg_'.$value['data_no']] = 1; // hold_flgは必ずTRUE
					//	$this->result_manager->record_tmp_office_data($shbn,$data[$key],$value['data_no']);
					}else if($value[$action_name] === 'srntb060'){
						$data[$key]['hold_flg_'.$value['data_no']] = 1; // hold_flgは必ずTRUE
					//	$this->result_manager->record_tmp_gyousya_data($shbn,$data[$key],$value['data_no']);
					}
				}
			}

			// セッションにテンプテーブル仕様フラグを保存
			$session_data = array('tmp_flg' => TRUE);
			$this->session->set_userdata($session_data);
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
			// 移動日取得
//			$select_day = $post['select_day'];
			foreach ($post as $key => $value) {
				if($key === 'select_day'
					 OR $key === 'kakninshbn'
					 OR $key === 'kakninshnm'
					 OR $key === 'move_day'
					 OR $key === 'action'
					 OR $key === 'copy_day'
					 OR $key === 'check_hold'){
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
			// 確認者情報登録処理
			if($post['check_hold'] != '1'){
				$confirmer_data['shbn'] = $shbn;
				$confirmer_data['select_day'] = $post['select_day'];
				$confirmer_data['kakninshbn'] = $kakninshbn;
				$this->result_manager->record_confirmer_data($confirmer_data);
				$hold_flg = '0';
			}

			// 登録処理
			foreach ($data as $key => $value) {
				if(!empty($value['data_no'])){
					$tmp_data = NULL;
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
					// 一時保存フラグ
					$variable_name = 'hold_flg_'.$value['data_no'];
					$data[$key][$variable_name] = $hold_flg;
					if($value[$action_name] === 'srntb010'){
						
						$tmp_data = $this->result_manager->record_honbu_data($shbn,$data[$key],$value['data_no']);
						$this->result_manager->record_honbu_rireki($shbn,$data[$key],$value['data_no'],$tmp_data);
						
					}else if($value[$action_name] === 'srntb020'){
						
						$tmp_data = $this->result_manager->record_tenpo_data($shbn,$data[$key],$value['data_no']);
						
					}else if($value[$action_name] === 'srntb030'){
						
						$tmp_data = $this->result_manager->record_dairi_data($shbn,$data[$key],$value['data_no']);
						$this->result_manager->record_dairi_rireki($shbn,$data[$key],$value['data_no'],$tmp_data);
						
					}else if($value[$action_name] === 'srntb040'){
						
						$this->result_manager->record_office_data($shbn,$data[$key],$value['data_no']);
						
					}else if($value[$action_name] === 'srntb060'){
						
						$this->result_manager->record_gyousya_data($shbn,$data[$key],$value['data_no']);
						
					}
				}
			}

			log_message('debug',"========== controllers result action_day_copy end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-action_day_copy'));
			//$this->error_view($e);
		}
	}

	function action_delete(){
		log_message('debug',"========== controllers plan action_delete start ==========");
		// 初期化
//		log_message('debug',$_POST['dbname']);
//		log_message('debug',$_POST['jyohonum']);
//		log_message('debug',$_POST['edbn']);
		$shbn = $this->session->userdata('shbn');
		$dbname = $_POST['dbname'];
		$jyohonum = $_POST['jyohonum'];
		$edbn = $_POST['edbn'];
		$select_day = $_POST['select_day'];
		$this->load->library('result_manager');
		$this->result_manager->delete_action($dbname,$jyohonum,$edbn);
		
		$this->result_manager->delete_confirmer_data($shbn,$select_day);

		log_message('debug',"========== controllers plan action_delete end ==========");
//		return;
	}


	function admin_check_view($select_day = NULL,$shbn = NULL){
		try{
			log_message('debug',"========== controllers result admin_check_view start ==========");
			// 引数チェック
			if(is_null($select_day) OR is_null($shbn)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->model('srwtb010');

			// 自分の社番をセッションより取得
			$my_shbn = $this->session->userdata('shbn');
			// セッションに部下の社番を保存
			$session_data = array('others_shbn' => $shbn);
			$this->session->set_userdata($session_data);
			// セッションにコメント入力有無を保存
//			$session_data = array('write_flg' => TRUE);
//			$this->session->set_userdata($session_data);

			$this->srwtb010->update_etujukyo($my_shbn,$shbn,$select_day);

			header("Location: ".$this->config->item('base_url')."index.php/result_view/index/".$select_day."/admin");

			log_message('debug',"========== controllers result admin_check_view end ==========");
//			$this->index($select_day);
			// セッションにコメント入力有無を保存
//			$session_data = array('write_flg' => FALSE);
//			$this->session->set_userdata($session_data);
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-admin_check_view'));
			//$this->error_view($e);
		}
	}

	function general_check_view($select_day = NULL,$shbn = NULL){
		try{
			log_message('debug',"========== controllers result general_check_view start ==========");
			// 引数チェック
			if(is_null($select_day) OR is_null($shbn)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->model('srwtb010');

			// 自分の社番をセッションより取得
			$my_shbn = $this->session->userdata('shbn');
			// セッションに閲覧相手の社番を保存
			$session_data = array('others_shbn' => $shbn);
			$this->session->set_userdata($session_data);
			// セッションにコメント入力有無を保存
//			$session_data = array('write_flg' => FALSE);
//			$this->session->set_userdata($session_data);

//			$this->srwtb010->update_kakninflg($my_shbn,$shbn,$select_day);
			$this->srwtb010->update_etujukyo($my_shbn,$shbn,$select_day);
			
			header("Location: ".$this->config->item('base_url')."index.php/result_view/index/".$select_day."/general");

			log_message('debug',"========== controllers result general_check_view end ==========");
//			$this->index($select_day);
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'result-general_check_view'));
			//$this->error_view($e);
		}
	}
	
	function delete_file_action(){
		if(isset($_POST['filenum']) && $_POST['filenum']!=""){
			log_message('debug',"========== delete_file_action  ==========");
			//削除処理
			$this->load->model('srntb070');
			$file_data['filenum'] = $_POST['filenum'];
			$res = $this->srntb070->delete_file($file_data);
			
			log_message('debug',"========== delete_file_action  ==========");
		}
		return;
	}

}

/* End of file result.php */
/* Location: ./application/controllers/result.php */
