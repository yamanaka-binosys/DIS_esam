<?php

class Data_memo extends MY_Controller {

	/**
	 * 情報メモ
	 *
	 * @access public
	 * @param  string  $conf_name　画面種類
	 * @param  array   $search_res 検索結果
	 * @param  nothing
	 * @return array $data ヘッダ情報他
	 */
	function index($conf_name = MY_ADD_DATA_MEMO, $search_res = NULL, $err ='', $search_data=NULL,$msg_type="meg-error",$busyo_list=NULL)
	{
		try
		{
			log_message('debug',"========== data_memo index start ==========");
			$data = $this->init($conf_name); // ヘッダデータ等初期情報取得
			$data['errmsg']	= "";                                                 // エラーメッセージ
			$data["memo_type"] = "";
			switch($conf_name) {
				// 登録
				case MY_ADD_DATA_MEMO:
					// 初期判定
					(empty($_POST)) ? $val = NULL : $val = $_POST;
					// タブデータ取得
					$data['tab'] = $this->_get_tab_data();                         // タブ作成
					$data["memo_type"] = "登録";
					$data["k_name_table"] = $this->_get_k_name($data, $val, TRUE); // 件名HTML作成
					$data["office_table"] = $this->_get_office($data, $val);             // 社名HTML作成
					$data["kbn_table"] = $this->_get_kbn($data, $val);                   // 区分HTML作成
					$data["maker_table"] = NULL;//$this->_get_maker($data);               // メーカー名HTML作成
					$data["file_table"] = $this->_get_file($data, $val);                 // 添付ファイルHTML作成
					$data["date_table"] = $this->_get_date($data, $val);				   // 掲載期限HTML作成
					$data["info_table"] = $this->_get_info($data, $val);                 // 情報メモ内容HTML作成
					if(is_null($err))
					{
					} elseif($err == 1){
					//登録が完了した場合
					 $data["errmsg"] = "登録しました。";
					 $msg_type = "msg-info";
					} elseif($err == 2){
					 //$data["errmsg"] = "件名が重複しています。";
					} else {
						// エラー発生の場合
						$data["errmsg"] = $err;
						$msg_type = "msg-error";
					}
					break;
				// 更新・削除
				case MY_UPDATE_DATA_MEMO:
					$data["memo_type"] = "変更";
				case MY_DELETE_DATA_MEMO:
									// 初期判定
					(empty($_POST)) ? $val = NULL : $val = $_POST;

					// タブデータ取得
					$data['tab'] = $this->_get_tab_data();                          // タブ作成
					$data["memo_type"] = ($data["memo_type"] == NULL) ? "削除": $data["memo_type"];
					$data["k_name_table"] = NULL;//$this->_get_k_name($data, $val, FALSE); // 件名HTML作成
					$data["office_table"] = NULL;//$this->_get_office($data);              // 社名HTML作成
					$data["kbn_table"] = NULL;//$this->_get_kbn($data);                    // 区分HTML作成
					$data["maker_table"] = NULL;//$this->_get_maker($data);                // メーカー名HTML作成
					$data["info_table"] = NULL;//$this->_get_info($data);                  // 情報メモ内容HTML作成
					$data["date_table"] = $this->_get_date_from($data, $val);				// 期間HTML作成
					$data["search_table"] = $this->_get_date_search($data);				// 期間HTML作成
					
					if(is_null($err))
					{
						
						if(!empty($_POST) && (!empty($search_res))) 
						{
							if(!empty($search_data))
							{
								$data["file_table"] = $this->_get_search($data, $search_res,$search_data,$conf_name); // 検索結果HTML作成 ソート後
							}else{
								$data["file_table"] = $this->_get_search($data, $search_res,$_POST,$conf_name); // 検索結果HTML作成
							}
						} else {
							$data["file_table"] = NULL;
						}
					} else {
						// エラー発生の場合
						$data["file_table"] = NULL;
						$data["errmsg"] = $err;
						$msg_type = "msg-error";
					}
					break;
				case MY_SEARCH_DATA_MEMO:
					// 初期判定
					(empty($_POST)) ? $val = NULL : $val = $_POST;
					$this->load->model('sgmtb020'); 
					$this->load->model('sgmtb010'); 
					$this->load->library('table_manager');
					// タブデータ取得
					$data['tab'] = $this->_get_tab_data();                          // タブ作成
					$data["memo_type"] = "検索";
					$data["k_name_table"] = $this->_get_k_name($data, $val, FALSE); // 件名HTML作成
					$data["office_table"] = $this->_get_office($data, $val);              // 社名HTML作成
					$data["kbn_table"] = $this->_get_kbn($data, $val);                    // 区分HTML作成
					$data["maker_table"] = NULL;//$this->_get_maker($data);                // メーカー名HTML作成
					$data["info_table"] = $this->_get_info_search($data, $val);                  // 情報メモ内容HTML作成
					$data["date_table"] = $this->_get_date_from_s($data, $val);				// 期間HTML作成
					$data["search_table"] = $this->_get_date_search($data);				// 期間HTML作成
					$shbn= $this->session->userdata('shbn');
					$data["search_result_busyo_table"] = $this->table_manager->search_unit_set_b_table($shbn,$val,NULL,NULL,"3");     // 部署検索
					
					$data["busyo_flg"] ="1";
					// セッション情報から社番を取得
					$login_data['shbn']= $this->session->userdata('shbn');
					$shbn_data = $this->sgmtb010->get_user_data_pk($data['shbn']);
					$data["shbn"] = $login_data['shbn'];
					$data["select_honbu"] =$shbn_data['honbucd'];
					$data["select_bu"] =$shbn_data['bucd'];
					$data["select_ka"] =$shbn_data['kacd'];
					
					if(isset($busyo_list) && $busyo_list!=""){
						$data["honbu_data"] = $busyo_list["honbu_data"];
						$data["bu_data"] = $busyo_list["bu_data"];
						$data["ka_data"] =  $busyo_list["ka_data"];
						$data["user_data"] = $busyo_list["user_data"];
					}else{
						$data["honbu_data"] = $this->sgmtb020->get_honbu_name_data();
						$data["bu_data"] =$this->sgmtb020->get_bu_name_data_select($shbn_data['honbucd']);
						$data["ka_data"] = $this->sgmtb020->get_unit_name_data_select($shbn_data['honbucd'],$shbn_data['bucd']);
						$data["user_data"] =$this->sgmtb010->get_shin_data($shbn_data['honbucd'],$shbn_data['bucd'],$shbn_data['kacd']);
					}
					if(is_null($err))
					{
						
						if(!empty($_POST) && (!empty($search_res))) 
						{
							if(!empty($search_data))
							{
								$data["file_table"] = $this->_get_search($data, $search_res,$search_data,$conf_name); // 検索結果HTML作成 ソート後
							}else{
								$data["file_table"] = $this->_get_search($data, $search_res,$_POST,$conf_name); // 検索結果HTML作成
							}
						} else {
							$data["file_table"] = NULL;
						}
					} else {
						// エラー発生の場合
						$data["file_table"] = NULL;
						$data["errmsg"] = $err;
						$msg_type = "msg-error";
					}
					//$data["errmsg"] = $err;
					break;
				// 更新・削除の検索
				
				case MY_UPDATE_SELECT_DATA_MEMO:
					//$err = '';
				case MY_DELETE_SELECT_DATA_MEMO:
					
				case MY_SEARCH_SELECT_DATA_MEMO:		
					$this->load->model('sgmtb030'); // 区分情報
					$this->load->model('sgmtb010'); 
					$this->load->model('srktb050'); 
					
					$data = $search_data;
					
					// セッション情報から社番を取得
					$login_data['shbn'] = $this->session->userdata('shbn');
					$data = $data[0];
					if(isset($data['shbn']) && $data['shbn']!=""){
						$unit_shbn = $this->sgmtb010->get_unit_cho_shbn($data['shbn']);
					}
				
					if($conf_name == MY_SEARCH_SELECT_DATA_MEMO ){
						if($login_data['shbn'] == $unit_shbn){
							$this->srktb050->update_flg($data['jyohonum'],$data['edbn']);
						}
					}
					if($err == 1){
					//登録が完了した場合
					 $data["errmsg"] = "変更しました。";
					 $msg_type = "msg-info";
					} elseif($err == '変更が完了しました。' || $err == '削除が完了しました。'){
						$data["errmsg"] = $err;
					 	//$data["errmsg"] = "件名が重複しています。";
						$msg_type = "msg-info";
					}else{
						$data["errmsg"] = $err;
						$msg_type = "msg-error";
					}
					
					if($conf_name == MY_UPDATE_SELECT_DATA_MEMO){
						$data["memo_type"] = "変更";
					}else if($conf_name == MY_DELETE_SELECT_DATA_MEMO){
						if($data["jyohokbnm"] != ""){ $data["jyohokbnm"] = $this->sgmtb030->get_ichiran_name('001',$data["jyohokbnm"]); }
						if($data["hinsyukbnm"] != ""){ $data["hinsyukbnm"] = $data["hinsyukbnm"] = $this->sgmtb030->get_ichiran_name('002',$data["hinsyukbnm"]); }
						if($data["tishokbnm"] != ""){ $data["tishokbnm"] = $data["tishokbnm"] = $this->sgmtb030->get_ichiran_name('003',$data["tishokbnm"]); }
						if($data["maker"] != ""){ $data["maker"] = $data["maker"] = $this->sgmtb030->get_ichiran_name('013',$data["maker"]); }
					}else{
						$data["memo_type"] = "検索";
						$this->load->model('sgmtb010'); 
						$this->load->model('sgmtb020'); 
						$userdata =  $this->sgmtb010->get_user_data_pk($data['shbn']);
						$data['unit'] =  $this->sgmtb020->get_unit_name_data($userdata);
						$data['shainname'] = $userdata['shinnm'] ;
						if($data["jyohokbnm"] != ""){ $data["jyohokbnm"] = $this->sgmtb030->get_ichiran_name('001',$data["jyohokbnm"]); }
						if($data["hinsyukbnm"] != ""){ $data["hinsyukbnm"] = $data["hinsyukbnm"] = $this->sgmtb030->get_ichiran_name('002',$data["hinsyukbnm"]); }
						if($data["tishokbnm"] != ""){ $data["tishokbnm"] = $data["tishokbnm"] = $this->sgmtb030->get_ichiran_name('003',$data["tishokbnm"]); }
						if($data["maker"] != ""){ $data["maker"] = $data["maker"] = $this->sgmtb030->get_ichiran_name('013',$data["maker"]); }
					}
			
					$data["kbn_table"] = $this->_get_kbn($data);                   // 区分HTML作成
					$data["maker_table"] = NULL;//$this->_get_maker($data);               // メーカー名HTML作成
					$data["date_table"] = $this->_get_date($data);				   // 掲載期限HTML作成
					if(isset($data['posteddate']) && $data['posteddate'] != ""){
					$data['s_year']= substr($data['posteddate'],-8,4);
					$data['s_month']=substr($data['posteddate'],-4,2);
					$data['s_day']=substr($data['posteddate'],-2,2);
					}

					if(is_null($err))
					{
						
						if(!empty($_POST) && (!empty($search_res))) 
						{
							if(!empty($search_data))
							{
							//	$data["file_table"] = $this->_get_search($data, $search_res,$search_data); // 検索結果HTML作成 ソート後
							}else{
							//	$data["file_table"] = $this->_get_search($data, $search_res,$_POST); // 検索結果HTML作成
							}
							
						} else {
							$data["file_table"] = NULL;
						}
					} else {
						// エラー発生の場合
						$data["file_table"] = NULL;
						$data["errmsg"] = $err;
					}
					//$data["errmsg"] = $err;
					break;
			}
			// Main表示情報取得
			$plan_data['existence'] = FALSE;
			$data["msg_type"] = $msg_type;
			$this->display($data,$conf_name); // 画面表示処理
			log_message('debug',"========== data_memo index end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-INDEX'));
		}
	}

	/**
	 * 共通で使用する初期化処理
	 *
	 * @access public
	 * @param  nothing
	 * @return array $data ヘッダ情報他
	 */
	function init($conf_name)
	{
		try
		{
			log_message('debug',"========== data_memo init start ==========");
			log_message('debug',"\$conf_name = $conf_name");
			// 初期化
//			$common_data = $this->config->item('s_plan');
			$common_data = $this->config->item($conf_name);
			$this->load->library('table_set');
			$this->load->library('common_manager');
			// セッション情報から社番を取得
			$data['shbn'] = $this->session->userdata('shbn');
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($data['shbn']);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			// セッション情報から管理者フラグを取得
			$data['admin_flg'] = $this->session->userdata('admin_flg');

			// 初期共通項目情報
			$data['title']       = $common_data['title']; // タイトルに表示する文字列
			$data['css']         = $common_data['css']; // 個別CSSのアドレス
			$data['image']       = $common_data['image']; // タイトルバナーのアドレス
			$data['errmsg']      = $common_data['errmsg']; // エラーメッセージ
			$data['btn_name']    = $common_data['btn_name']; // ボタン名
			$data['form']        = $common_data['form']; // フォーム名
			$data['main_form']	 = $common_data['form'];
			log_message('debug',"========== data_memo init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-INIT'));
		}
	}

	/**
	 * 共通で使用する表示処理
	 *
	 * @access  public
	 * @param   array $data 各種HTML作成時に必要な値
	 * @param  string  $conf_name　画面種類 
	 * @return  nothing
	 */
	function display($data,$conf_name)
	{
		try
		{
			log_message('debug',"========== data_memo display start ==========");

			// 表示処理
			log_message('debug',"========== data_memo display end ==========");
			switch($conf_name) {
				// 登録
				case MY_ADD_DATA_MEMO:
					$this->load->view(MY_VIEW_MEMO_ADD, $data);
				break;
				// 更新・削除・検索の検索画面
				case MY_UPDATE_DATA_MEMO:
				case MY_DELETE_DATA_MEMO:
				case MY_SEARCH_DATA_MEMO:
				$this->load->view(MY_VIEW_MEMO_UPDATE, $data);
				break;
				// 更新の検索後リスト選択後画面
				case MY_UPDATE_SELECT_DATA_MEMO:
				$this->load->view(MY_VIEW_MEMO_SELECT, $data);
				break;
				// 削除・検索の検索後リスト選択後画面
				case MY_DELETE_SELECT_DATA_MEMO:
				$this->load->view(MY_VIEW_MEMO_SELECT_DELETE, $data);
				break;
				case MY_SEARCH_SELECT_DATA_MEMO;
				$this->load->view(MY_VIEW_MEMO_SELECT_SEARCH, $data);
				break;
			}
			
		}catch(Exception $e){
			// エラー処理
//			$this->error_view($e);
$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-DISPLAY'));
		}
	}

	/**
	 * 登録・変更・削除タブの文字列を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_tab_data()
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('tab_manager');
			$tab_data = NULL;
			$plan_name = MY_DATA_MEMO . "/";
			log_message('debug',"\$plan_name = $plan_name");
			// タブ用HTML-STRING取得
			$tab_data = $this->tab_manager->set_tab_all($plan_name);

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $tab_data;
		}catch(Exception $e){
			//エラー処理
				$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-TAB-DATAE'));
		}
	}

	/**
	 * 件名の作成を取得
	 *
	 * @access private
	 * @param $data ARRAY
	 * @param $mode TRUE=登録　FALSE=更新、削除
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_k_name($data, $value, $mode)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_kname($data, $value, $mode);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-K_NAME'));
		}
	}

	/**
	 * 入手元（社名、役職、氏名）の作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_office($data, $value=NULL)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_office($data, $value);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-OFFICE'));
		}
	}

	/**
	 * 区分（情報区分、品種区分、対象区分）の作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_kbn($data, $value=NULL)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_kbn($data, $value);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-KBN'));
		}
	}

	/**
	 * メーカーの作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_maker($data)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_maker($data, $value);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-MAKER'));
		}
	}
	
		
	/**
	 * 掲載期限の作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_date($data, $value=NULL)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$diffsec = 60 * 60 * 24 * 7;
			$nowdate=mktime(0, 0, 0, date("m"), date("d"), date("y"));
			$data['memo_posteddate'] = $nowdate + $diffsec;
			$data['s_year'] = date("Y", $data['memo_posteddate']);
			$data['s_month'] = date("m", $data['memo_posteddate']);
			$data['s_day'] = date("d", $data['memo_posteddate']);
			$table_data = $this->table_set->set_data_memo_date($data,$value);         // 社番テーブル
			
			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-DATE'));
		}
	}
	
	/**
	 * 期間の作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_date_from($data, $value=NULL)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data_from start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_date_from($data, $value);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data_from end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-DATE-FROM'));
		}
	}
	
	/**
	 * 期間の作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_date_from_s($data, $value=NULL)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data_from start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_date_from_s($data, $value);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data_from end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-DATE-FROM-S'));
		}
	}
	
	/**
	 * 検索ボタンの作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_date_search($data)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data_from start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_date_search($data);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data_from end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-DATE-SEARCH'));
		}
	}

	/**
	 * 添付ファイルの作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_file($data)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_file($data);         // 情報メモ内容

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-FILE'));
		}
	}

	/**
	 * 情報メモの作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_info($data,$value)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_info($data, $value);         // 情報メモ内容

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-GET-INFO'));
		}
	}
	
	/**
	 * 情報メモの作成を取得(検索用)
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_info_search($data,$value)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_info_search($data, $value);         // 情報メモ内容

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-SET-TAB-DATA'));
		}
	}

	/**
	 * 検索結果の作成
	 *
	 * @access private
	 * @param  array $data       設定データ
	 * @param  array $search_res 検索結果データ
	 * @param  array $post       検索条件データ
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_search($data, $search_res,$post,$conf_name)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$base_url = $this->config->item('base_url');
			
			switch($conf_name) {
				case MY_UPDATE_DATA_MEMO:
				case MY_DELETE_DATA_MEMO:
					$table_data = $this->table_set->set_data_memo_search($data, $search_res,$post,$base_url);         // 検索結果HTML(データ込み)作成
				break;
				case MY_SEARCH_DATA_MEMO:
					$table_data = $this->table_set->set_data_memo_search_s($data, $search_res,$post,$base_url);         // 検索結果HTML(データ込み)作成
				break;
			}
			


			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-SET-TAB-DATA'));
		}
	}

	/**
	 * 情報メモ登録処理
	 *
	 * @access public
	 * @param  nothing
	 * @return nothing
	 */
	function add_select_type()
	{
		try
		{
		
			log_message('debug',"========== data_memo add_select_type start ==========");
			$_POST["knnm"] = $_POST["k_name"];
			$result = '';
			$this->load->library('common_manager');
			$this->load->library('file_uploade_manager');
			$this->load->helper('common');
			
			$invalidFileSize = (
			  startsWith(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/form-data') &&
			  2000000 <= $_SERVER['CONTENT_LENGTH']);
				
			if ($invalidFileSize) {
				log_message('debug', "2M以上のファイルを受信しました。{$_SERVER['CONTENT_LENGTH']}");
			}
			
			// バリデーションチェック
			$v_result = $this->validate_check(MY_ADD_DATA_MEMO);
			if($v_result === TRUE && !$invalidFileSize  )
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srktb050'); // ユーザー情報
				// 情報メモの重複検索
				//$result = $this->srktb050->search_knnm($_POST["k_name"]);
				//if($result)
				//{
					$data['max'] = 0;
					$data = $_POST;
					// 登録№最大値を取得
					$data['max'] = $this->srktb050->max_jouhonum();
					$data['max']++;
					$data['kakninshbn'] = NULL;
					$data["shbn"] = $data['shbn'] = $this->session->userdata('shbn');
					$data['posteddate']= date("Ymd", mktime(0,0,0,$data['s_month'],$data['s_day'], $data['s_year']));
					
					$this->load->library('file_uploade_manager');
					
					$edbn = '01';
					$jyohoNum = "J".sprintf('%08d', $data['max']);
					// 添付ファイル情報
					if(!empty($_FILES['temp_files']['name'])){
					//echo $_FILES['temp_files']['name'];
						$data['temp_files'] = $_FILES['temp_files']['name'];
						log_message('debug',"tmp_file_name = ".$data['temp_files']);
						// ファイルアップロード処理
						$tmp_data = array(
										'tmp_name'  => $_FILES['temp_files']['tmp_name'],
										'file_name' => $_FILES['temp_files']['name'],
										'dir_name'  => 'memo',
										'jyohonum'  => date('YmdHis'),
										'edbn'  => '01'
										);
						$data['temp_files'] = $this->file_uploade_manager->memo_file_upload($tmp_data);
					}else{
						$data['temp_files'] = NULL;
					}
					
					$result = $this->srktb050->insert_datamemo($data);
					
					
				//}else{
				//	$result=2;
				//}
				
				
			}else{
				$result = $v_result;
				if(2000000 <= $_SERVER['CONTENT_LENGTH']){
					$result = "アップロードできるファイル容量上限は2Mです。";
				}

			}
			
			

			$conf_name = MY_ADD_DATA_MEMO;
			$this->index($conf_name, NULL, $result);

			log_message('debug',"========== data_memo add_select_type end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			log_message('debug',"========== 例外 ============");
			log_message('debug',$e->getMessage());
			// エラー処理
			$this->error_view($e->getMessage(),"add");
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-ADD-SELECT-TYPE'));
		}
	}
	
	/**
	 *
	 *
	 *
	 *
	 *
	 */
	public function add()
	{
		try
		{
			log_message('debug',"========== data_memo add start ==========");
			$conf_name = MY_ADD_DATA_MEMO;

			$this->index($conf_name);

			log_message('debug',"========== data_memo add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-ADD'));
		}
	}
	
	public function search()
	{
		try
		{
			log_message('debug',"========== data_memo search start ==========");
			$conf_name = MY_SEARCH_DATA_MEMO;
			$res = NULL;
			$search_data=NULL;
			$v_result = FALSE;
			
			$this->load->library('data_memo_manager');
			if(!empty($_POST)) {
				if($_POST['s_month']!="" && $_POST['s_day']!="" && $_POST['s_year']!=""){
					$_POST['s_date']= date("Ymd", mktime(0,0,0,$_POST['s_month'],$_POST['s_day'], $_POST['s_year']));
				}
				if($_POST['e_month']!="" && $_POST['e_day']!="" && $_POST['e_year']!=""){
					$_POST['e_date']= date("Ymd", mktime(0,0,0,$_POST['e_month'],$_POST['e_day'], $_POST['e_year']));
				}
				if(!empty($_POST['DESC']))
					{
						$_POST['search'] = NULL;
					}else if(!empty($_POST['ASC'])){
						$_POST['search'] = NULL;
				}
				
				if($_POST['bucd'] == "XXXXX"){
				$_POST['bucd']="XXXXX";
				}else{
				$_POST['bucd']=substr($_POST['bucd'],5);
				}
				if($_POST['kacd'] == "XXXXX"){
				$_POST['kacd']="XXXXX";
				}else{
				$_POST['kacd']=substr($_POST['kacd'],10);
				}
				// 検索ボタン押下時のみ、バリデーションチェック実行
				if(! empty($_POST["search"]))
				{
				$v_result = $this->validate_check(MY_SEARCH_DATA_MEMO,"search",$_POST);
				
				}else if(!empty($_POST["ASC"]) || !empty($_POST["DESC"])){
					// ソートボタン押下
					$v_result = TRUE;
					
				}
				// バリデーションチェックOK
				if($v_result === TRUE)
				{
					// バリデーション問題なし
					// モデル呼び出し
					$this->load->model('srktb050'); // ユーザー情報
					if(!empty($_POST['search']))
					{
						$order = "DESC";
						$result = $this->srktb050->get_search_data_memo_s($_POST,$order);
						
					}else{
						$order = "";
						// ソートボタン押下
						
						if(!empty($_POST['DESC']))
						{
							$order = "DESC";
						}else{
							$order = "ASC";
						}
					
						// SQL用に検索条件を生成
						
						$search_data = $this->data_memo_manager->_set_search_data($_POST);
						//検索条件
						$search_data['s_date'] = "";
						$search_data['e_date'] = "";
						if($search_data['s_month']!="" && $search_data['s_day']!="" && $search_data['s_year']!=""){
							$search_data['s_date']= date("Ymd", mktime(0,0,0,$search_data['s_month'],$search_data['s_day'], $search_data['s_year']));
						}
						if($search_data['e_month']!="" && $search_data['e_day']!="" && $search_data['e_year']!=""){
							$search_data['e_date']= date("Ymd", mktime(0,0,0,$search_data['e_month'],$search_data['e_day'], $search_data['e_year']));
						}
						$result = $this->srktb050->get_search_data_memo_s($search_data, $order);
					}
					
					$res = $this->data_memo_manager->_get_data_memo_search_s($result);
					$this->index($conf_name, $res,NULL,$search_data);
				}
				else
				{
					//echo $v_result;
					$this->index($conf_name, $res, $v_result);
				}
			}
			else
			{
				$this->index($conf_name, $res);
			}
			log_message('debug',"========== data_memo search end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-SEARCH'));
		}
	}



	/**
	 * 情報メモ更新処理
	 *
	 * @access public
	 * @param  nothing
	 * @return nothing
	 */
	public function update()
	{
		try
		{
			log_message('debug',"========== data_memo update start ==========");
			$conf_name = MY_UPDATE_DATA_MEMO;
			$res = NULL;
			$order = "";
			$v_result = FALSE;
			$base_url = $this->config->item('base_url');
			$this->load->library('data_memo_manager');

			if(!empty($_POST))
			{
				
			
				// 検索ボタン押下時のみ、バリデーションチェック実行
				if(! empty($_POST["search"]))
				{
					$v_result = $this->validate_check(MY_UPDATE_DATA_MEMO, "search",$_POST);				
				}else if(!empty($_POST["ASC"]) || !empty($_POST["DESC"])){
					// ソートボタン押下
					$v_result = TRUE;
					
					//ソートボタンの場合、保持していた検索条件を使用する
					//$_POST = $_POST
					
				}
				
				// バリデーションチェックOK
				if($v_result === TRUE)
				{
					// バリデーション問題なし
					// モデル呼び出し
					$this->load->model('srktb050'); // ユーザー情報
					
					$search_data=NULL;
					// 並び替えボタン押下(降順)
					if(!empty($_POST['search']))
					{
						//検索条件
						if($_POST['s_year'] != "" && $_POST['s_month'] != "" && $_POST['s_day'] != ""  ){
							$_POST['s_date']= date("Ymd", mktime(0,0,0,$_POST['s_month'],$_POST['s_day'], $_POST['s_year']));
						}
						if($_POST['e_year'] != "" && $_POST['e_month'] != "" && $_POST['e_day'] != ""){
							$_POST['e_date']= date("Ymd", mktime(0,0,0,$_POST['e_month'],$_POST['e_day'], $_POST['e_year']));
						}
						
						$_POST["shbn"] = $this->session->userdata('shbn');
						$_POST["n_date"]= date('Ymd', mktime(0, 0, 0, date('m'), date('d'), date('y')));

						// 検索データの取得
						$result = $this->srktb050->get_search_data_memo_ud($_POST);						
					}else{
						// ソートボタン押下
						if(!empty($_POST['DESC']))
						{
							$order = "DESC";
						}
						// SQL用に検索条件を生成
						$search_data = $this->data_memo_manager->_set_search_data_ud($_POST);
						//検索条件
						if($search_data['s_month']!="" && $search_data['s_day']!="" && $search_data['s_year']!=""){
							$search_data['s_date']= date("Ymd", mktime(0,0,0,$search_data['s_month'],$search_data['s_day'], $search_data['s_year']));
						}
						if($search_data['e_month']!="" && $search_data['e_day']!="" && $search_data['e_year']!=""){
							$search_data['e_date']= date("Ymd", mktime(0,0,0,$search_data['e_month'],$search_data['e_day'], $search_data['e_year']));
						}
						$search_data["shbn"] = $this->session->userdata('shbn');
						$search_data["n_date"]= date('Ymd', mktime(0, 0, 0, date('m'), date('d'), date('y')));
						
						// 検索データの取得
						$result = $this->srktb050->get_search_data_memo_ud($search_data,$order);
					}
					// 検索結果テーブル用生成データ抽出
					$res = $this->data_memo_manager->_get_data_memo_search($result);
					$this->index($conf_name, $res, NULL,$search_data);
				}else{
					$this->index($conf_name, $res, $v_result);
				}
			}else{
				$this->index($conf_name, $res);
			}

			log_message('debug',"========== data_memo update end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-UPDATE'));
		}
	}

	/**
	 *
	 * 更新画面表示処理
	 *
	 *
	 *
	 */
	public function update_select($jyohonum, $edbn, $update_select_type = NULL)
	{
		try
		{
			log_message('debug',"========== data_memo update_select start ==========");
			$errmsg		= NULL;                                                   // エラーメッセージ
			if($update_select_type)
			{
				$conf_name =MY_UPDATE_SELECT_DATA_MEMO;
				$result = $this->update_select_type(); //更新処理
				
				$data[0] = $_POST;
				//$data[0]['posteddate'];
				$this->index($conf_name, NULL,$result,$data);
			}else{
			$conf_name = MY_UPDATE_SELECT_DATA_MEMO;
			
			// モデル呼び出し
			$this->load->model('srktb050'); // ユーザー情報
			
			$_POST["jyohonum"] = $jyohonum;
			$_POST["edbn"] = $edbn;
			$result = $this->srktb050->get_search_data_memo($_POST);

			$this->index($conf_name, NULL,NULL,$result);
			}
			log_message('debug',"========== data_memo update_select end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-UPDATE-SELECT'));
		}
	}

	/**
	 *
	 * 情報メモ更新画面　更新処理
	 * 
	 * @access public
	 * @param  
	 * @return 
	 *
	 */
	function update_select_type()
	{
		try
		{
			log_message('debug',"========== data_memo update_select_type start ==========");
			// バリデーションチェック
			$res = '';
			$this->load->library('common_manager');
			$this->load->library('file_uploade_manager');
			$this->load->helper('common');
			
			$invalidFileSize = (
			  startsWith(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/form-data') &&
			  2000000 <= $_SERVER['CONTENT_LENGTH']);
				
			if ($invalidFileSize) {
				log_message('debug', "2M以上のファイルを受信しました。{$_SERVER['CONTENT_LENGTH']}");
			}
			
			$v_result = $this->validate_check(MY_UPDATE_DATA_MEMO);
			if($v_result === TRUE && !$invalidFileSize )
			{
				
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srktb050'); // ユーザー情報
				//重複チェック
				//$_POST["k_name"] =  $_POST["knnm"];
				//$result = $this->srktb050->search_knnm($_POST["knnm"]);
				$result = TRUE;		
				if($result){
					$data = $_POST;
					$data['kakninshbn'] = NULL;
					$data["shbn"] = $data['shbn'] = $this->session->userdata('shbn');
					$data['posteddate']= date("Ymd", mktime(0,0,0,$data['s_month'],$data['s_day'], $data['s_year']));
					$jyohonum = $data['jyohonum'];
					$data["edbn"] = $this->srktb050->get_maxedbn($jyohonum);
					$data["edbn"] = (int)$data["edbn"] + 1;
					$data["edbn"] = strval($data["edbn"]);
					if(strlen($data["edbn"]) == 1){
					$data["edbn"] = "0".$data["edbn"];
					}
					$edbn = $data["edbn"];
					$this->load->library('file_uploade_manager');
					//echo $data["edbn"];
					//echo $jyohonum;
					if(!empty($_FILES['temp_files']['name'])){
						//echo $_FILES['temp_files']['name'];
						$data['temp_files'] = $_FILES['temp_files']['name'];
						log_message('debug',"tmp_file_name = ".$data['temp_files']);
						// ファイルアップロード処理
						$tmp_data = array(
										'tmp_name'  => $_FILES['temp_files']['tmp_name'],
										'file_name' => $_FILES['temp_files']['name'],
										'dir_name'  => 'memo',
										'jyohonum'  => $jyohonum,
										'edbn'  => $edbn
										);
					$data['temp_files'] = $this->file_uploade_manager->memo_file_upload($tmp_data);
					}else if(isset( $data['old_temp_files']) &&  $data['old_temp_files']!=""){
						$data['temp_files'] = $data['old_temp_files'];
					}else{
						$data['temp_files'] = NULL;
					}

					$res = $this->srktb050->update_datememo($data);
					if($res){
					$res = "変更が完了しました。";
					}else{
					$res = "変更が失敗しました。";
				}
				}
				log_message('debug',"========== data_memo update_select_type end ==========");
			}else{
				if(2000000 <= $_SERVER['CONTENT_LENGTH']){
					$res = "アップロードできるファイル容量上限は2Mです。";
				}
			}
			
			//$base_url = $this->config->item('base_url');
			//header("Location: ".$base_url."index.php/data_memo/update");
			//exit();
 
			
			return $res;
		}catch(Exception $e){
			//エラー処理
			log_message('debug',"========== 例外 ============");
			log_message('debug',$e->getMessage());
			// エラー処理
			$this->error_view($e->getMessage(),"add");
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-UPDATE-SELECT_TYPE'));
		}
	}

	/**
	 *
	 *　削除画面
	 *
	 *
	 *
	 */
	public function delete()
	{
		try
		{
			log_message('debug',"========== data_memo delete start ==========");
			$conf_name = MY_DELETE_DATA_MEMO;
			$res = NULL;
			$order = "";
			$v_result = FALSE;
			$this->load->library('data_memo_manager');
			if(!empty($_POST))
			{
				// 検索ボタン押下時のみ、バリデーションチェック実行
				if(! empty($_POST["search"]))
				{
					$v_result = $this->validate_check(MY_DELETE_DATA_MEMO, "search",$_POST);				
				}else if(!empty($_POST["ASC"]) || !empty($_POST["DESC"])){
					// ソートボタン押下
					$v_result = TRUE;
					
					//ソートボタンの場合、保持していた検索条件を使用する
					//$_POST = $_POST
				}
				
				// バリデーションチェックOK
				if($v_result === TRUE)
				{
					// バリデーション問題なし
					// モデル呼び出し
					$this->load->model('srktb050'); // ユーザー情報
					$search_data=NULL;
					// 並び替えボタン押下(降順)
					if(!empty($_POST['search']))
					{
						//検索条件
						if($_POST['s_month'] != "" && $_POST['s_month'] != "" && $_POST['s_month'] != ""){
							$_POST['s_date']= date("Ymd", mktime(0,0,0,$_POST['s_month'],$_POST['s_day'], $_POST['s_year']));
						}
						if($_POST['e_month'] != "" && $_POST['e_month'] != "" && $_POST['e_month'] != ""){
							$_POST['e_date']= date("Ymd", mktime(0,0,0,$_POST['e_month'],$_POST['e_day'], $_POST['e_year']));
						}
						$_POST["shbn"] = $this->session->userdata('shbn');
						$_POST["n_date"]= date('Ymd', mktime(0, 0, 0, date('m'), date('d'), date('y')));
						
						// 検索データの取得
						$result = $this->srktb050->get_search_data_memo_ud($_POST);
					}else{
						// ソートボタン押下
						if(!empty($_POST['DESC']))
						{
							$order = "DESC";
						}
						// SQL用に検索条件を生成
						$search_data = $this->data_memo_manager->_set_search_data_ud($_POST);
						//検索条件
						if($_POST['s_month'] != "" && $_POST['s_month'] != "" && $_POST['s_month'] != ""){
							$_POST['s_date']= date("Ymd", mktime(0,0,0,$_POST['s_month'],$_POST['s_day'], $_POST['s_year']));
						}
						if($_POST['e_month'] != "" && $_POST['e_month'] != "" && $_POST['e_month'] != ""){
							$_POST['e_date']= date("Ymd", mktime(0,0,0,$_POST['e_month'],$_POST['e_day'], $_POST['e_year']));
						}
						$search_data["shbn"] = $this->session->userdata('shbn');
						$search_data["n_date"]= date('Ymd', mktime(0, 0, 0, date('m'), date('d'), date('y')));
						
						// 検索データの取得
						$result = $this->srktb050->get_search_data_memo_ud($search_data,$order);
						
					}
					// 検索結果テーブル用生成データ抽出
					$res = $this->data_memo_manager->_get_data_memo_search($result);
					$this->index($conf_name, $res, NULL,$search_data);
				}else{
					$this->index($conf_name, $res, $v_result);
				}
			}else{
				$this->index($conf_name, $res);
			}

			log_message('debug',"========== data_memo update end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-DELETE'));
		}
	}
	

	/**
	 * 	削除画面(検索後）
	 *
	 *
	 *
	 *
	 */
	public function delete_select($jyohonum, $edbn,$delete_select_type = NULL)
	{
		try
		{
			log_message('debug',"========== data_memo delete_select start ==========");
			$conf_name = MY_DELETE_SELECT_DATA_MEMO;
			$errmsg = NULL;                                                  // エラーメッセージ
			//削除が押された場合
			
			if($delete_select_type)
			{
				$result = $this->delete_select_type(); //更新処理
				$data[0] = $_POST;
								//$data[0]['posteddate'];
								
				$this->index($conf_name, NULL,$result,$data);
			}else{
						
			// モデル呼び出し
			$this->load->model('srktb050'); // ユーザー情報
				
			$_POST["jyohonum"] = $jyohonum;
			$_POST["edbn"] = $edbn;
			$result = $this->srktb050->get_search_data_memo($_POST);
			
			$this->index($conf_name, NULL,NULL,$result);
			}

			log_message('debug',"========== data_memo delete_select end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-DELETE-SELECT'));
		}
	}

	/**
	 *
	 *
	 *
	 *
	 *
	 */
	public function delete_select_type()
	{
		try
		{ 
			log_message('debug',"========== data_memo update_select_type start ==========");
			///// DB 更新処理の記述 //////////////////////////////////
			$v_result = TRUE; //$this->validate_check(MY_UPDATE_SELECT_DATA_MEMO);
			$res = "";
			if($v_result === TRUE)
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srktb050'); // ユーザー情報			
				$result = $this->srktb050->delete_datamemo($_POST["jyohonum"]);
				
				if($result){
					$res = "削除が完了しました。";
				}else{
					$res = "削除が失敗しました。";
				}
			}
			return $res;
			log_message('debug',"========== data_memo update_select_type end ==========");
			
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-DELETE-SELECT_TYPE'));
		}
	}
	
	/**
	 *
	 * 検索画面
	 *
	 *
	 *
	 */
	public function search_select($jyohonum, $edbn,$search_select_type=NULL)
	{
		try
		{ 			
			log_message('debug',"========== data_memo search_select start ==========");
			///// DB 更新処理の記述 //////////////////////////////////
			
			$errmsg		= NULL;                                                 // エラーメッセージ
			$v_result = TRUE; //$this->validate_check(MY_SEARCH_SELECT_DATA_MEMO);
			if($v_result === TRUE)
			{
			
				//検索が押された場合
				if($search_select_type)
				{
					$this->search_select_type(); //更新処理
				}
				
				// モデル呼び出し				
				$this->load->model('srktb050'); // ユーザー情報
				
				$_POST["jyohonum"] = $jyohonum;
				$_POST["edbn"] = $edbn;
				$result = $this->srktb050->get_search_data_memo($_POST);
				
				
				
			}
			
			if (!$result) {
			
				$this->load->view('/parts/error/notfound.php');
				return;
			}
			
			///////////////////////////////////////////////////////////
			$conf_name = MY_SEARCH_SELECT_DATA_MEMO;//MY_VIEW_MEMO_SELECT_SEARCH;
			$this->index($conf_name, NULL,NULL,$result);

			log_message('debug',"========== data_memo search_select end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATAMEMO-SEARCH-SELECT'));
			
		}
	}
	
		/**
	 *
	 *　検索画面
	 *
	 *
	 *
	 */
	public function search_select_type()
	{
			$base_url = $this->config->item('base_url');
			header("Location: ".$base_url."index.php/data_memo/search");
			exit();
	}
	

	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $type = add:登録 update:更新 delete:削除
	 * @return	boolean TRUE:成功 FALSE:エラー
	 */
     function validate_check($type = NULL,$other = NULL,$search_data = NULL)
	 {
		 try
		 {
			$v_result = FALSE;
			$config2 ="";
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			switch($type)
			{
				// 登録
				case MY_ADD_DATA_MEMO:
					$config = $this->config->item('validation_rules_data_memo_add');
					break;
				// 更新
				case MY_UPDATE_SELECT_DATA_MEMO:
					$config = $this->config->item('validation_rules_data_memo_add');
					break;
				case MY_UPDATE_DATA_MEMO;
					$config = $this->config->item('validation_rules_data_memo_uqdate');
					break;
				// 削除
				case MY_DELETE_SELECT_DATA_MEMO:
					$config = $this->config->item('validation_rules_data_memo_add');
					break;
			}
			// 検索
			if($other === "search")
			{
				switch($type)
				{
					case MY_ADD_DATA_MEMO:
						$config = $this->config->item('validation_rules_data_memo_search');
						break;
					case MY_UPDATE_SELECT_DATA_MEMO:
					case MY_DELETE_SELECT_DATA_MEMO:
					case MY_UPDATE_DATA_MEMO:
					case MY_DELETE_DATA_MEMO:	
					case MY_SEARCH_DATA_MEMO:
						$config = $this->config->item('validation_rules_data_memo_search_e');
						$config2 = $this->config->item('validation_rules_data_memo_search_s');
						break;	
				}
			}
			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			$errStr = '';
			if($search_data){
				// 年月日の相関チェック
				switch($type){
					case MY_UPDATE_DATA_MEMO:					
					case MY_DELETE_DATA_MEMO:
						$errStr = $this->valid_date_up_del($search_data);
						if($errStr != ""){
							return $errStr;
						}else{
							//return TRUE;
						}
					break;
					case MY_SEARCH_DATA_MEMO:
						$errStr = $this->valid_date($search_data);
						if($errStr != ""){
							return $errStr;
						}else{
						}
					break;
				}
				
				
			}

			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				//print $this->form_validation->error_string();
				// 失敗
				//$v_result = FALSE;
				throw new Exception(ERROR_VALI);
				log_message('debug',"==========  validation error ==========");
			}elseif($errStr != ''){
				$v_result = $errStr;
				return $v_result;
				log_message('debug',"========== validation error ==========");
			}else{
				// 成功
				$v_result = TRUE;
				log_message('debug',"==========  validation success ==========");
				return $v_result;
			}
			
		 }catch(Exception $e){
			// エラー処理
			log_message('debug',"message = ".$e->getMessage());
			$v_result = $this->error_view($e->getMessage(), $type, NULL);
			return $v_result;
		}
	 }
	 
	/**
	 * 日付チェック
	 */
	function valid_date($search_data)
	{

		// 開始年月チェック
		if ( $search_data['s_year'] != ''  &&  $search_data['s_month'] != ''  &&  $search_data['s_day'] != ''  && ! $this->org_checkdate($search_data['s_month'], $search_data['s_day'], $search_data['s_year']))
		{
			return '検索期間の日付が適切ではありません。';
		}
		// 終了年月チェック
		if ( $search_data['e_year'] != ''  &&  $search_data['e_month'] != ''  &&  $search_data['e_day'] != ''  && ! $this->org_checkdate($search_data['e_month'], $search_data['e_day'], $search_data['e_year']))
		{
			return '検索期間の日付が適切ではありません。';
		}
		

		// 年月相関チェック
		if ($this->org_checkdate($search_data['s_month'], $search_data['s_day'], $search_data['s_year']) &&
			$this->org_checkdate($search_data['e_month'], $search_data['e_day'], $search_data['e_year']) &&
			strtotime($search_data['s_year'] . '/' . $search_data['s_month'] . '/' . $search_data['s_day']) > strtotime($search_data['e_year'] . '/' . $search_data['e_month'] . '/' . $search_data['e_day']) )
		{
			return '検索期間終了日が開始日より以前の日付です。';
		}
		// 日付（from to　MAX１ヶ月）のチェック
		$fromAdd1month = date('Ymd', strtotime($search_data['s_year'] . '/' . $search_data['s_month'] . '/' . $search_data['s_day'] . '+1 month +1 day'));
		$to = date('Ymd', strtotime($search_data['e_year'] . '/' . $search_data['e_month'] . '/' . $search_data['e_day']));
		if($fromAdd1month <= $to){
			return '検索期間は１ヶ月以内を指定してください。';
		}
		
	}
	/**
	 * 日付チェック
	 */
	function valid_date_up_del($search_data)
	{
		if($_POST['s_year'] == "" && $_POST['s_month'] != "" && $_POST['s_day'] != ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['s_year'] == "" && $_POST['s_month'] == "" && $_POST['s_day'] != ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['s_year'] != "" && $_POST['s_month'] == "" && $_POST['s_day'] != ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['s_year'] != "" && $_POST['s_month'] == "" && $_POST['s_day'] == ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['s_year'] == "" && $_POST['s_month'] != "" && $_POST['s_day'] == ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['s_year'] != "" && $_POST['s_month'] != "" && $_POST['s_day'] == ""){
			return '検索期間の日付が適切ではありません。';
		}
		
		if($_POST['e_year'] == "" && $_POST['e_month'] != "" && $_POST['e_day'] != ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['e_year'] == "" && $_POST['e_month'] == "" && $_POST['e_day'] != ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['e_year'] != "" && $_POST['e_month'] == "" && $_POST['e_day'] != ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['e_year'] != "" && $_POST['e_month'] == "" && $_POST['e_day'] == ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['e_year'] == "" && $_POST['e_month'] != "" && $_POST['e_day'] == ""){
			return '検索期間の日付が適切ではありません。';
		}
		if($_POST['e_year'] != "" && $_POST['e_month'] != "" && $_POST['e_day'] == ""){
			return '検索期間の日付が適切ではありません。';
		}
		
		// 開始年月チェック
		if ( $search_data['s_year'] != ''  &&  $search_data['s_month'] != ''  &&  $search_data['s_day'] != ''  &&  ! $this->org_checkdate($search_data['s_month'], $search_data['s_day'], $search_data['s_year']))
		{
			return '検索期間の日付が適切ではありません。';
		}

		// 終了年月チェック
		if ( $search_data['e_year'] != ''  &&  $search_data['e_month'] != ''  &&  $search_data['e_day'] != ''  && ! $this->org_checkdate($search_data['e_month'], $search_data['e_day'], $search_data['e_year']))
		{
			return '検索期間の日付が適切ではありません。';
		}

		// 年月相関チェック
		if ($this->org_checkdate($search_data['s_month'], $search_data['s_day'], $search_data['s_year']) &&
			$this->org_checkdate($search_data['e_month'], $search_data['e_day'], $search_data['e_year']) &&
			strtotime($search_data['s_year'] . '/' . $search_data['s_month'] . '/' . $search_data['s_day']) > strtotime($search_data['e_year'] . '/' . $search_data['e_month'] . '/' . $search_data['e_day']) )
		{
			return '検索期間終了日が開始日より以前の日付です。';
		}
		
	}

	/**
	 * 日付のチェック
	 *
	 * @param $month
	 * @param $day
	 * @param $year
	 */
	function org_checkdate($month, $day, $year){
		if($year != '' && is_numeric($year) && is_numeric($month) && is_numeric($day) && checkdate($month, $day, $year)){
			return true;

		} else {
			return false;
		}
	}


	/**
	 * エラー発生時処理
	 * @access	public
	 * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
	 * @return	none
	 */
	function error_view($e,$type="add",$item=NULL)
	{
		log_message('debug',"exception : $e");
		//$common_data = $this->init($type);         // ヘッダー設定
		$this->load->library('message_manager');
		// POSTデータ引継ぎ
		$data = $_POST;
		if($e == ERROR_USER_SEARCH)
		{
			$data = $this->init($type);
		}
		return $this->message_manager->get_message($e,$item);
	}

  
    /**
   *  ドロップダウンリスト変更
   */
  function select_item_list()
  {
    try
    {
      log_message('debug',"========== checker_search_conf select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val'];
      $data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,NULL,"3"); // 部署検索
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
  
      /**
   *  ドロップダウンリスト変更
   */
	function select_item_unit_list($type=NULL){
			try
			{
			
			log_message('debug',"========== Select_client_search select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
		    $honbu = $_POST['selected_val']['val'];
		    // 部情報取得
			$bu= $this->sgmtb020->get_bu_name_data_select($honbu);
		   
		    $busyo =  substr($_POST['selected_val']['val2'],5);
		    
			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$busyo);
			
			if($busyo =='XXXXX'){
			$unit = array();
			}
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			$staff_list =array();
			// 都道府県
			$prefecture = $this->config->item('c_pref');
			
			$this->load->library('table_manager');
			  
			//表示用データ作成
	     	$this->load->library('table_manager');
	      	$shbn = $this->session->userdata('shbn');
	      	$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,$busyo,"3"); // 部署検索
			
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
 
  /**
   *  ドロップダウンリスト変更
   */
	function select_item_user_list($type=NULL){

			try
			{
				
			log_message('debug',"========== Select_client_search select_item_user_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
		    $honbu = $_POST['selected_val']['val'];
		    // 部情報取得
			$bu= $this->sgmtb020->get_bu_name_data_select($honbu);
		   
		    $busyo =  substr($_POST['selected_val']['val2'],5);
			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$busyo);
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			
			$ka =  substr($_POST['selected_val']['val3'],10);
		
			$this->load->model('sgmtb010'); // ユーザ別検索情報（相手先）

			$staff_list = $this->sgmtb010->get_shin_data($honbu,$busyo,$ka);
			
			
			if($ka=="XXXXX"){
			 $staff_list = array();
			}
			// 都道府県
			$prefecture = $this->config->item('c_pref');
			
			$this->load->library('table_manager');
			//表示用データ作成
	      	$shbn = $this->session->userdata('shbn');
	      	$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,$busyo,"3",$ka); // 部署検索
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}

}




/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
