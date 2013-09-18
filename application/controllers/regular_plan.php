<?php

class Regular_plan extends MY_Controller {

	function index($select_day = NULL){
		try{
			// 登録ボタン押下判定を入れる
			if($_POST){
				// 一日分の予定のコミット
				if($_POST['action'] === 'action_submit'){
					$this->action_submit($_POST);
					$this->session->unset_userdata('checker_ednm');
				// 確認者選択
				}else if($_POST['action'] === 'action_select_checker'){
					$this->action_select_checker($_POST);
					return;
				// 相手先選択
				}else if($_POST['action'] === 'action_select_client'){
					$this->action_select_client($_POST);
					return;
				}
			}

			log_message('debug',"========== controllers regular_plan index start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$this->load->library('common_manager');
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

			// セッションに戻り先URLを保存
			$from_url = $this->config->item('base_url')."index.php/regular_plan/confirmer/".$select_day;
			$session_data = array('from_url' => $from_url);
			$this->session->set_userdata($session_data);
//			log_message('debug',"\$from_url = $from_url");

			// 共通初期化処理
			$data = $this->common_manager->init(SHOW_REGULAR_PLAN);
			// 選択日取得
			$data['select_day'] = $select_day;

			// DBデータ取得
			$shbn = $this->session->userdata('shbn');
			$tmp_flg=FALSE;
			$data['tmp_flg']=$tmp_flg;

			// セッション情報より社番を取得
			$kakninshbn = $this->session->userdata('kakninshbn');
			$this->session->unset_userdata('kakninshbn');
			$session_data = array('k_shbn' => $kakninshbn);
			$this->session->set_userdata($session_data);
			// セッションに確認者社番がある場合
			if($kakninshbn){
				$data['kakninshbn'] = $kakninshbn;
				if(strpos($data['kakninshbn']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					$data['kakninshnm'] = $this->common_manager->create_name($data['kakninshbn']);
				}
			}else if(isset($data['action_plan'][0]['kakninshbn']) && $data['action_plan'][0]['kakninshbn'] != ''){
				$data['kakninshbn'] = $data['action_plan'][0]['kakninshbn'];
				if(strpos($data['kakninshbn']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					$data['kakninshnm'] = $this->common_manager->create_name($data['kakninshbn']);
				}
			}else{
				$data['kakninshbn'] = "";
				$data['kakninshnm'] = "";
			}
			// 画面表示
			log_message('debug',"========== controllers plan index end ==========");
			$this->display($data);
		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-index'));
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
//			echo "<pre>";
//			echo "<pre>";
			if(is_null($data)){
				log_message('debug',"argument data is NULL");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			log_message('debug',"========== controllers plan display end ==========");
			// 表示処理
			$this->load->view(MY_VIEW_REGULAR_PLAN, $data, FALSE);

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-display'));
			//$this->error_view($e);
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

	//			log_message('debug',"\$kakunin = $kakunin");
				$session_data = array('kakninshbn' => $kakunin);
				$this->session->set_userdata($session_data);
			}
			log_message('debug',"========== controllers plan confirmer end ==========");
			$this->index($select_day);
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-confirmer'));
			//$this->error_view($e);
		}
	}



	function new_action_view(){
		try{
			log_message('debug',"========== controllers regular_plan new_action_view start ==========");
			// データカウンターセット（"00"固定）
			$data['count'] = "00";
			log_message('debug',"\$count = ".$data['count']);

			$this->load->view(MY_NEW_VIEW_REGULAR_PLAN_ACTION, $data, FALSE);

			log_message('debug',"========== controllers regular_plan new_action_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-new_action_view'));
			//$this->error_view($e);
		}
	}

	function new_honbu_view(){
		try{
			log_message('debug',"========== controllers regular_plan new_honbu_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB110';
			$data = NULL;

			$data['plan'] = $this->plan_manager->get_view_item($use_db);
			$data['plan']['hkubun'] = '1';

			// セッションよりデータ件数取得
			$count = 1;
			/*$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}*/
			$data['count'] = sprintf('%02d',$count);
			//$data['count'] = '01';
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);

			log_message('debug',"\$count = $count");

			$this->load->view(MY_NEW_VIEW_REGULAR_PLAN_HONBU, $data, FALSE);
			log_message('debug',"========== controllers regular_plan new_honbu_view end ==========");

			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-new_honbu_view'));
			//$this->error_view($e);
		}
	}

	function new_tenpo_view(){
		try{
			log_message('debug',"========== controllers regular_plan new_tenpo_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB120';
			$data = NULL;

			$data['plan'] = $this->plan_manager->get_view_item($use_db);
			$data['plan']['hkubun'] = '1';

			// セッションよりデータ件数取得
			$count = 1;
/*			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
 * */
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);

			log_message('debug',"\$count = $count");

			$this->load->view(MY_NEW_VIEW_REGULAR_PLAN_TENPO, $data, FALSE);
			log_message('debug',"========== controllers regular_plan new_tenpo_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-new_tenpo_view'));
			//$this->error_view($e);
		}
	}

	function new_dairi_view(){
		try{
			log_message('debug',"========== controllers regular_plan new_dairi_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$use_db = 'SRNTB130';
			$data = NULL;

			$data['plan'] = $this->plan_manager->get_view_item($use_db);
			$data['plan']['hkubun'] = '1';

			// セッションよりデータ件数取得
			$count = 1;
/*			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
 * */
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);

			log_message('debug',"\$count = $count");

			$this->load->view(MY_NEW_VIEW_REGULAR_PLAN_DAIRI, $data, FALSE);
			log_message('debug',"========== controllers regular_plan new_dairi_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-new_dairi_view'));
			//$this->error_view($e);
		}
	}

	function new_gyousya_view(){
		try{
			log_message('debug',"========== controllers regular_plan new_gyousya_view start ==========");
			// 初期化
			$this->load->library('plan_manager');
			$data = NULL;
			$data['plan']['hkubun'] = '1';

			// セッションよりデータ件数取得
			$count = 1;
/*			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
 * */
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);

			log_message('debug',"\$count = $count");

			$this->load->view(MY_NEW_VIEW_REGULAR_PLAN_GYOUSYA, $data, FALSE);
			log_message('debug',"========== controllers regular_plan new_gyousya_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-new_gyousya_view'));
			//$this->error_view($e);
		}
	}

	function new_office_view(){
		try{
			log_message('debug',"========== controllers regular_plan new_office_view start ==========");
			$this->load->library('item_manager');
			$tag_name = NULL;
			$check = NULL;

			$this->load->model('sgmtb041');
			$data['PID'] = PLAN_VIEW_ITEM;
			$res = $this->sgmtb041->get_item_visibility_data($data);
			log_message('debug',"\$res = $res");
			if($res === FALSE){
				log_message('debug',"Exception res = NULL");
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			$data['plan']['hkubun'] = '1';
			// セッションよりデータ件数取得
			$count = 1;
/*			$count = $this->session->userdata('plan_count');
			if($count === FALSE){
				$count = 1;
			}else{
				$count++;
			}
 * */
			$data['count'] = sprintf('%02d',$count);
			// セッションにデータ件数を保存
			$session_data = array('plan_count' => $count);
			$this->session->set_userdata($session_data);

			log_message('debug',"\$count = $count");

			// 作業内容
			$tag_name = 'sagyoniyo_'.$data['count'];
			$extra = 'class="selected" title="作業内容" ';
			$data['plan']['sagyoniyo'] = $this->item_manager->set_dropdown_in_db_string(MY_PLAN_SAGYONAIYO_KBN,$tag_name,$check,$tag_name,$extra);


			$this->load->view(MY_NEW_VIEW_REGULAR_PLAN_OFFICE, $data, FALSE);
			log_message('debug',"========== controllers regular_plan new_office_view end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-new_office_view'));
			//$this->error_view($e);
		}
	}

	function action_submit($post){
		try{
			log_message('debug',"========== controllers regular_plan action_submit start ==========");
			// 引数チェック
			if(is_null($post)){
				throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}
			// 初期化
			$this->load->library('plan_manager');
			$this->load->model('common');
			$shbn = $this->session->userdata('shbn');
//			$data_no = "";
			$data_no = "01";
			$group_count = 0;
			$regular_day = array();
			$flg = FALSE;

			// 現在日
			$select_year = substr($post['select_day'],0,4);
			$select_month = substr($post['select_day'],4,2);
			$select_day = substr($post['select_day'],6,2);
			$to_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
			// 定期期限
			if(!empty($post['deadline_day_01'])){
				$deadline_year = substr($post['deadline_day_01'],0,4);
				$deadline_month = substr($post['deadline_day_01'],5,2);
				$deadline_day = substr($post['deadline_day_01'],8,2);
				log_message('debug',"\$deadline_year = $deadline_year");
				log_message('debug',"\$deadline_month = $deadline_month");
				log_message('debug',"\$deadline_day = $deadline_day");
			}else{
				return;
			}
			// 定期区分
			// 1 = 選択日,2 = 月末,3 = 曜日
			if($post['hkubun_01'] == 1){
//				$day = $post['designated_day_01'];
				$day = sprintf('%02d',$post['designated_day_01']);
				log_message('debug',"\$day = $day");
				while ($flg === FALSE) {
					$u_deadline_date = strtotime(date("Ymd",mktime(0,0,0,$deadline_month,$deadline_day,$deadline_year)));
					$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$day,$select_year)));
//					log_message('debug',"\$u_deadline_date = $u_deadline_date");
//					log_message('debug',"\$u_select_date = $u_select_date");
					log_message('debug',"========== u_deadline_date ".$u_deadline_date." ==========");
					log_message('debug',"========== u_select_date ".$u_select_date." ==========");
					if($u_deadline_date >= $u_select_date){
						if($to_select_date <= $u_select_date){
							$regular_day_check = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),$day,sprintf('%02d',$select_year)));
							if(substr($regular_day_check,4,2)===sprintf('%02d',$select_month)){
							log_message('debug',"========== if(substr($regular_day_check,4,2)==$select_month)  ".substr($regular_day_check,4,2)."::".sprintf('%02d',$select_month)." ==========");
								$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),$day,sprintf('%02d',$select_year)));
							}else{
							log_message('debug',"========== if(substr($regular_day_check,4,2)==$select_month)　月末に変換 ".substr($regular_day_check,4,2)."::".sprintf('%02d',$select_month)." ==========");
								$select_month_p = $select_month + 1;
								log_message('debug',"==========select_month ".$select_month." ==========");
								$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month_p),0,sprintf('%02d',$select_year)));
							}
						}
					}else{
						$flg = TRUE;
					}
					log_message('debug',"========== regular_day ".date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),$day,sprintf('%02d',$select_year)))." ==========");
					// 月を移動
//					log_message('debug',"\$select_year = $select_year");
//					log_message('debug',"\$select_month = $select_month");
					if((int)$select_month < 12){
						$select_month = (int)$select_month + 1;
//						log_message('debug',"\$select_month = $select_month");
					}else{
						$select_month = "01";
						$select_year = (int)$select_year + 1;
//						log_message('debug',"\$select_month = $select_month");
//						log_message('debug',"\$select_year = $select_year");
					}
				}
//				foreach ($regular_day as $key => $value) {
//					log_message('debug',"regular_day = $value");
//				}
			}else if($post['hkubun_01'] == 2){
				while ($flg === FALSE) {
					$u_deadline_date = strtotime(date("Ymd",mktime(0,0,0,$deadline_month,$deadline_day,$deadline_year)));
					$u_select_date = strtotime(date("Ymt",mktime(0,0,0,$select_month,1,$select_year)));
					log_message('debug',"select month end = ".date("Ymt",mktime(0,0,0,$select_month,1,$select_year)));
					if($u_deadline_date >= $u_select_date){
						if($to_select_date <= $u_select_date){
							$regular_day[] = date("Ymt",mktime(0,0,0,$select_month,1,$select_year));
						}
					}else{
						$flg = TRUE;
					}
					if((int)$select_month < 12){
						$select_month = (int)$select_month + 1;
//						log_message('debug',"\$select_month = $select_month");
					}else{
						$select_month = "01";
						$select_year = (int)$select_year + 1;
//						log_message('debug',"\$select_month = $select_month");
//						log_message('debug',"\$select_year = $select_year");
					}
				}
//				foreach ($regular_day as $key => $value) {
//					log_message('debug',"regular_day = $value");
//				}
			}else if($post['hkubun_01'] == 3){
				$u_deadline_date = strtotime(date("Ymd",mktime(0,0,0,$deadline_month,$deadline_day,$deadline_year)));
				// 日曜日
				if(isset($post['designated_sun_01'])){
					if($post['designated_sun_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 0){
							$select_day = (int)$select_day -7;
						}else if($select_week_day > 0){
							$select_day = (int)$select_day - (int)$select_week_day;
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
				// 月曜日
				if(isset($post['designated_mon_01'])){
					if($post['designated_mon_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 1){
							$select_day = (int)$select_day -7;
						}else if($select_week_day < 1){
							$select_day = (int)$select_day - 7 + (1 - (int)$select_week_day);
						}else if($select_week_day > 1){
							$select_day = (int)$select_day - ((int)$select_week_day - 1);
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
				// 火曜日
				if(isset($post['designated_tues_01'])){
					if($post['designated_tues_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 2){
							$select_day = (int)$select_day -7;
						}else if($select_week_day < 2){
							$select_day = (int)$select_day - 7 + (2 - (int)$select_week_day);
						}else if($select_week_day > 2){
							$select_day = (int)$select_day - ((int)$select_week_day - 2);
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
				// 水曜日
				if(isset($post['designated_wed_01'])){
					if($post['designated_wed_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 3){
							$select_day = (int)$select_day -7;
						}else if($select_week_day < 3){
							$select_day = (int)$select_day - 7 + (3 - (int)$select_week_day);
						}else if($select_week_day > 3){
							$select_day = (int)$select_day - ((int)$select_week_day - 3);
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
				// 木曜日
				if(isset($post['designated_thurs_01'])){
					if($post['designated_thurs_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 4){
							$select_day = (int)$select_day -7;
						}else if($select_week_day < 4){
							$select_day = (int)$select_day - 7 + (4 - (int)$select_week_day);
						}else if($select_week_day > 4){
							$select_day = (int)$select_day - ((int)$select_week_day - 4);
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
				// 金曜日
				if(isset($post['designated_fri_01'])){
					if($post['designated_fri_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 5){
							$select_day = (int)$select_day -7;
						}else if($select_week_day < 5){
							$select_day = (int)$select_day - 7 + (5 - (int)$select_week_day);
						}else if($select_week_day > 5){
							$select_day = (int)$select_day - ((int)$select_week_day - 5);
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
				// 土曜日
				if(isset($post['designated_sat_01'])){
					if($post['designated_sat_01'] === "on"){
						$select_year = substr($post['select_day'],0,4);
						$select_month = substr($post['select_day'],4,2);
						$select_day = substr($post['select_day'],6,2);
						$select_week_day = date("w",mktime(0,0,0,$select_month,$select_day,$select_year));
						$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
						$flg = FALSE;
						if($select_week_day == 6){
							$select_day = (int)$select_day -7;
						}else if($select_week_day < 6){
							$select_day = (int)$select_day - 7 + (6 - (int)$select_week_day);
						}else if($select_week_day > 6){
							$select_day = (int)$select_day - ((int)$select_week_day - 6);
						}
						while($flg === FALSE) {
							$select_day = (int)$select_day + 7;
							if($select_day > $month_last_day){
								$select_day = (int)$select_day - (int)$month_last_day;
								$select_month = (int)$select_month + 1;
								$month_last_day = date("t",mktime(0,0,0,$select_month,1,$select_year));
								if((int)$select_month > 12){
									$select_month = "01";
									$select_year = (int)$select_year + 1;
								}
							}
							$u_select_date = strtotime(date("Ymd",mktime(0,0,0,$select_month,$select_day,$select_year)));
							if($u_deadline_date >= $u_select_date){
								if($to_select_date <= $u_select_date){
									$regular_day[] = date("Ymd",mktime(0,0,0,sprintf('%02d',$select_month),sprintf('%02d',$select_day),sprintf('%02d',$select_year)));
								}
							}else{
								$flg = TRUE;
							}
						}
					}
				}
//				foreach ($regular_day as $key => $value) {
//					log_message('debug',"regular_day = $value");
//				}
			}

			if(empty($regular_day[0])){
				log_message('debug',"not select regular_day");
				return;
			}

			foreach ($post as $key => $value) {
				if($key === 'select_day' OR $key === 'kakninshbn' OR $key === 'kakninshnm' OR $key === 'move_day' OR $key === 'action'){
					continue;
				}
				$data[$group_count]['data_no'] = $data_no;
				$data[$group_count][("ymd_".$data_no)] = $select_day;
				$data[$group_count][$key] = $value;
				// キーの後ろ2桁を取得して同じ物をグルーピングする
			/*	if($data_no === substr($key, -2)){
					$data[$group_count][$key] = $value;
				}else{
					$data[$group_count]['data_no'] = $data_no;
					$data[$group_count][("ymd_".$data_no)] = $select_day;
					$group_count++;
					$data[$group_count][$key] = $value;
				}
				$data_no = substr($key, -2);*/
			}
			// 登録処理
			foreach ($data as $key => $value) {
				if(!empty($value['data_no'])){
					foreach ($regular_day as $regular_key => $regular_value){
						// エスケープされた情報No、枝番を戻す
						$variable_name = 'jyohonum_'.$value['data_no'];
						$data[$key][$variable_name] = "";
						$variable_name = 'edbn_'.$value['data_no'];
						$data[$key][$variable_name] = "";
						// 登録日
						$variable_name = 'ymd_'.$value['data_no'];
						$data[$key][$variable_name] = $regular_value;
						// 確認者番
						$variable_name = 'kakninshbn_'.$value['data_no'];
						$data[$key][$variable_name] = $post['kakninshbn'];
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
			}

			$this->session->unset_userdata('checker_ednm');
	
			log_message('debug',"========== controllers regular_plan action_submit end ==========");
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Regular_plan-action_submit'));
			//$this->error_view($e);
		}
	}




}

/* End of file Regular_plan.php */
/* Location: ./application/controllers/regular_plan.php */
