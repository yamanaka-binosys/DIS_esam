<?php

class User extends MY_Controller {
	/**
	 * ユーザー管理(登録)
	 *
	 * @access	private
	 * @param	none
	 * @return	none
	 */
	function index()
	{
		try
		{
			log_message('debug',"========== start ==========");
			// 登録ページの読み込み
			$this->add_select_type();
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'User-index'));
			//$this->error_view(ERROR_SYSTEM,"add");
			
		}
	}
	
	/**
	 * ユーザー管理(初期化)
	 *
	 * @access	private
	 * @param	string 画面種別
	 * @return	array 画面の各ステータス
	 */
	function init($type)
	{
		try
		{
			log_message('debug',"========== user init start ==========");
			$this->load->library('common_manager');
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($this->session->userdata('shbn'));
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			// ユーザー管理画面独自項目
			$data['shbn']      = NULL; // 社番
			$data['user_name'] = NULL; // 社員名
			$data['hb_code']   = NULL; // 本部コード
			$data['hb_name']   = NULL; // 本部名
			$data['b_code']    = NULL; // 部コード
			$data['b_name']    = NULL; // 部名
			$data['k_code']    = NULL; // 課コード
			$data['k_name']    = NULL; // 課名
			$data['authority'] = NULL; // 閲覧権限
			$data['password']  = NULL; // パスワード
			$data['menu_kbn']  = NULL; // メニュー区分
			
			
			log_message('debug',"========== user init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-init'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}
	
	/**
	 * ユーザー管理(表示)
	 *
	 * @access	public
	 * @param	array
	 * @return	none
	 */
	function display($data,$type="add",$msg_type="msg-error")
	{
		try
		{
			log_message('debug',"========== user display start ==========");
			log_message('debug',$type);
			$this->load->library('table_set');
			$this->load->library('tab_set');			
			$this->load->library('tab_manager');                  
			$data['tab'] = $this->tab_manager->set_tab_a("user");   // タブ作成
			$data['shbn_table'] = $this->table_set->set_user_shbn($data);         // 社番テーブル
			$data['name_table'] = $this->table_set->set_user_name($data,$type);   // 氏名・カナテーブル
			$data['busyo_table'] = $this->table_set->set_user_busyo($data,$type); // 部署情報テーブル
			$data['kbn_table'] = $this->table_set->set_user_kbn($data);           // 区分メニュー・閲覧権限テーブル
			$data['pass_table'] = $this->table_set->set_user_pass($data);         // パスワードテーブル
			$data['msg_type'] = $msg_type;
			/*
			// ヘッダ部生成 title css image errmsg を使用
			$data['header']    = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']      = $this->load->view(MY_VIEW_USER, $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']      = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			// フッダ部生成 必ず指定する事
			$data['footer']    = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			*/
			// 表示処理
			log_message('debug',"========== user display end ==========");
			$this->load->view(MY_VIEW_USER, $data,FALSE);
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-display'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}
	
	/**
	 * ユーザー管理画面ヘッダー設定
	 *
	 * @access	public
	 * @param	string
	 * @return	nothing
	 */
	function header($type)
	{
		try
		{
			log_message('debug',"========== user header start ==========");
			log_message('debug',"type = ".$type);
			// 登録ページの場合
			if($type === "add")
			{
				$header_data = $this->config->item('s_user_add');
			// 更新ページの場合
			}else if($type === "update"){
				$header_data = $this->config->item('s_user_update');
			// 削除ページの場合
			}else if($type === "delete"){
				$header_data = $this->config->item('s_user_delete');
			}
			log_message('debug',"========== user header end ==========");
			return $header_data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-header'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}

	/**
	 * ボタン種類判別(登録)
	 *
	 * @access	public
	 * @param	none
	 * @return	array data
	 */
	function add_select_type()
	{
		try
		{
			log_message('debug',"========== user add_select_type start ==========");
			$data = NULL;
			
			
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['search']))
				{
					$data = $this->add_search($_POST); // 登録画面内検索実行
				}else{
					//パスワード長さチェック
					if(mb_strlen($_POST['passwd']) < 6)
					{
					
						$data = $_POST;
						$data['errmsg'] = "パスワードは6文字以上入力してください。";
						$common_data = $this->header("add");           // ヘッダー設定
						// 共通項目情報
						$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
						$data['title']     = $common_data['title'];    // タイトルに表示する文字列
						$data['css']       = $common_data['css'];      // 個別CSSのアドレス
						$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
						$data['btn_name']  = $common_data['btn_name']; // ボタン名
						$data['form']      = $common_data['form'];     // formタグのあり・なし
						$data['main_form'] = $common_data['form'];     // メイン内formの送信先
						
						// ユーザ管理画面表示
						$this->display($data,"add");
					}else{
					// ヘッダー付属ボタンの場合
						$data = $this->add($_POST); // 登録実行
					}
					
				}

				return $data;
			}else{
				// 初期画面設定
				$data = $this->init("add");
				$common_data = $this->header("add");           // ヘッダー設定
				// 共通項目情報
				$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
				$data['title']     = $common_data['title'];    // タイトルに表示する文字列
				$data['css']       = $common_data['css'];      // 個別CSSのアドレス
				$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
				$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
				$data['btn_name']  = $common_data['btn_name']; // ボタン名
				$data['form']      = $common_data['form'];     // formタグのあり・なし
				$data['main_form'] = $common_data['form'];     // メイン内formの送信先
				$data['msg_type']  = "msg-error";
				// ユーザ管理画面表示
				$this->display($data,"add");
			}
			log_message('debug',"========== user add_select_type end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-add_select_type'));
			//$this->error_view(ERROR_SYSTEM,"add");			
		}
	}
	
	/**
	 * ユーザー管理(登録画面の検索)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	nothing
	 */
	function add_search()
	{
		try
		{
			log_message('debug',"========== add_search start ==========");
			$result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// modelの読み込み
			 $this->load->model('sgmtb010'); // ユーザ情報
			 $this->load->model('sgmtb020'); // 部署情報
			// バリデーションチェック
			$v_result = $this->validate_check("add","search");
			// バリデーション結果チェック
			if($v_result)
			{
				// バリデーション問題なし

				$data = $this->sgmtb010->get_search_user_data($_POST['shbn']); // ユーザ情報登録
				
//				var_dump($data);
				// 結果判定
				if(!$data)
				{
					// 検索失敗
					// メッセージ作成
					$this->message_create(ERROR_USER_SEARCH);
				}else{
					$data['user_name'] = $data['shinnm'];
					$honbu = $this->sgmtb020->get_honbu_name_data($data); // 本部名
					$bu    = $this->sgmtb020->get_bu_name_data($data);    // 部名
					$unit  = $this->sgmtb020->get_unit_name_data($data);  // 課・ユニット名
					$data['honbunm'] = $honbu['bunm'];
					$data['bunm'] = $bu['bunm'];
					$data['kanm'] = $unit['bunm'];
				}
				log_message('debug',"========== validation success ==========");				
			}else{
				// バリデーション失敗
				// POSTデータの受け渡し
				$data = $_POST;
				// メッセージ設定処理
				$this->form_validation->set_message('required');
				log_message('debug',"========== validation error ==========");
			}
			log_message('debug',"========== add_search end ==========");
			$common_data = $this->header("add");           // ヘッダー設定
			
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			$data['main_form'] = $common_data['form'];     // メイン内formの送信先
			// バリデーション問題なし
			if($v_result)
			{
				// ユーザ管理画面表示
				$this->display($data,"add");
			}
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-add_search'));
			//s$this->error_view(ERROR_SYSTEM,"add");
		}
	}
	
	/**
	 * ユーザー管理(登録画面)
	 *
	 * @access	public
	 * @param	array string
	 * @return	nothing
	 */
	function add($post=NULL)
	{
		try
		{
			log_message('debug',"========== user add start ==========");
			// 初期化
			$add_result = FALSE;
			$error = "";
			// バリデーションチェック
			$v_result = $this->validate_check("add",NULL.$_POST);
			//echo $v_result;
			
			log_message('debug',"v_result=".$v_result);
			
			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			$data = $post;
			
			$common_data = $this->header("add");           // ヘッダー設定
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			$data['main_form'] = $common_data['form'];     // メイン内formの送信先
			
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
			
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('sgmtb010'); // ユーザー情報
				$this->load->model('sgmtb070'); // 組織情報
				// ユーザ登録
				$user_result = $this->sgmtb010->update_adduser_data($post);
				// 組織情報登録
				$ssk_result = $this->sgmtb070->update_addssk_data($post);
				
				// 登録結果判定し、完了メッセージ表示
				if($user_result AND $ssk_result)
				{
					// 登録成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_ADD);
				}else{
				log_message('debug',"========== 例外1 ============");
					// 登録失敗
					throw new Exception(ERROR_USER_ADD);
				}
				// ユーザ管理画面表示
				$this->display($data,"add","msg-info");
			}else{
				$data['errmsg'] = $v_result;
				$this->display($data,"add","msg-error");
		
			}
				
			
		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'user-add'));
			log_message('debug',"========== 例外 ============");
			log_message('debug',$e->getMessage());
			// エラー処理
			$this->error_view($e->getMessage(),"add");
		}
	}

	/**
	 * ボタン種類判別(更新)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	array data
	 */
	function update_select_type()
	{
		try
		{
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['search']))
				{
					$data = $this->update_search($_POST); // 更新画面内検索実行
				}else if(isset($_POST['set'])){
				// ヘッダー付属ボタンの場合
					$data = $this->update($_POST); // 更新実行
				}			
				return $data;
			}else{
				// 初期画面設定
				$data = $this->init("update");
				$common_data = $this->header("update");        // ヘッダー設定
				// 共通項目情報
				$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
				$data['title']     = $common_data['title'];    // タイトルに表示する文字列
				$data['css']       = $common_data['css'];      // 個別CSSのアドレス
				$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
				$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
				$data['btn_name']  = $common_data['btn_name']; // ボタン名
				$data['form']      = $common_data['form'];     // formタグのあり・なし
				$data['main_form'] = $common_data['form'];     // メイン内formの送信先
				// ユーザ管理画面表示
				$this->display($data,"update");
			}
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,"update");			
		}
	}
	/**
	 * ユーザー管理(更新画面の検索)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	nothing
	 */
	function update_search()
	{
		try
		{
			log_message('debug',"========== user update_search start ==========");			
			$result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// バリデーションチェック
			$v_result = $this->validate_check("update","search");
			// バリデーション結果チェック
			if($v_result)
			{
				log_message('debug',"========== update_search validation success ==========");
				// バリデーション問題なし
				// modelの読み込み
				$this->load->model('sgmtb010'); // ユーザ情報
				$this->load->model('sgmtb020'); // 部署情報
				$result = $this->sgmtb010->get_search_user_data($_POST['shbn']); // 検索データ取得
				
				// 取得結果
				if($result)
				{
					// 取得成功
					$data = $result;
					$honbu = $this->sgmtb020->get_honbu_name_data($result); // 本部名
					$bu    = $this->sgmtb020->get_bu_name_data($result);    // 部名
					$unit  = $this->sgmtb020->get_unit_name_data($result);  // 課・ユニット名
					$data['honbunm'] = $honbu['bunm'];
					$data['bunm'] = $bu['bunm'];
					$data['kanm'] = $unit['bunm'];
				}else{
				log_message('debug',"========== update_search error ==========");
					// 取得なし
					// POSTデータの受け渡し
					$data = $_POST;
					// メッセージ設定
					throw new Exception(ERROR_USER_SEARCH);					
				}
			}else{
				// バリデーション失敗
				// POSTデータの受け渡し
				$data = $_POST;
				// メッセージ設定
				log_message('debug',"========== update_search validation error ==========");
			}
			$common_data = $this->header("update");           // ヘッダー設定
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			$data['main_form'] = $common_data['form'];     // メイン内formの送信先
			// バリデーション問題なし
			if($v_result)
			{
				// ユーザ管理画面表示
				$this->display($data,"update");
			}
		log_message('debug',"========== user update_search end ==========");			
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-update_search'));
			//$this->error_view(ERROR_USER_SEARCH,"update");
		}
	}	

	/**
	 * ユーザー管理(変更画面)
	 *
	 * @access	public
	 * @param	array string
	 * @param	array string
	 * @return	nothing
	 */
	function update($data=NULL)
	{
		try
		{
			log_message("debug","================ update_user start ====================");
			$update_result = FALSE;
			// バリデーションチェック
			$v_result = $this->validate_check("update");
			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			$data = $_POST;
			$common_data = $this->header("update");         // ヘッダー設定
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし			
			$data['main_form'] = $common_data['form'];    // メイン内formの送信先
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('sgmtb010'); // ユーザー情報
				$this->load->model('sgmtb070'); // 組織情報
				// バリデーション結果チェック
				$update_result = $this->sgmtb010->update_user($data); // ユーザー更新実行
				$ssk_result = $this->sgmtb070->update_addssk_data($data);
				// 更新結果判定し、完了メッセージ表示
				if($update_result AND $ssk_result)
				{
					// 更新成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_UPDATE);
				}else{
				log_message('debug',"========== 例外1 ============");				
					// 登録失敗
					throw new Exception(ERROR_USER_UPDATE);
				}
				// ユーザ管理画面表示
				$this->display($data,"update");
			}
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-update'));
			//$this->error_view($e->getMessage(),"update");
		}
	}
	
	/**
	 * ボタン種類判別(削除)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	array data
	 */
	function delete_select_type()
	{
		try
		{
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['search']))
				{
					$data = $this->delete_search($_POST); // 削除画面内検索実行
				}else if(isset($_POST['set'])){
				// ヘッダー付属ボタンの場合
					$data = $this->delete($_POST); // 削除実行
				}
				return $data;
			}else{
				// 初期画面設定
				$data = $this->init("delete");
				$common_data = $this->header("delete");           // ヘッダー設定
				// 共通項目情報
				$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
				$data['title']     = $common_data['title'];    // タイトルに表示する文字列
				$data['css']       = $common_data['css'];      // 個別CSSのアドレス
				$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
				$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
				$data['btn_name']  = $common_data['btn_name']; // ボタン名
				$data['form']      = $common_data['form'];     // formタグのあり・なし
				$data['main_form'] = $common_data['form'];     // メイン内formの送信先
				// ユーザ管理画面表示
				$this->display($data,"delete");
			}
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-delete_select_type'));
			//$this->error_view(ERROR_SYSTEM,"delete");			
		}
	}

	/**
	 * ユーザー管理(削除画面の検索)
	 *
	 * @access	private
	 * @param	array string
	 * @return	nothing
	 */
	function delete_search()
	{
		try
		{
			$result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// バリデーションチェック
			$v_result = $this->validate_check("delete","search");
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// バリデーション問題なし
				// modelの読み込み
				// データ取得先のDBが不明なため仮データを以下で設定
				 $this->load->model('sgmtb010'); // ユーザ情報
				 $this->load->model('sgmtb020'); // 部署情報
				$result = $this->sgmtb010->get_search_user_data($_POST['shbn']); // 検索データ取得
				// 取得結果
				if($result)
				{
					// 該当データあり
					$data = $result;
					$honbu = $this->sgmtb020->get_honbu_name_data($result); // 本部名
					$bu    = $this->sgmtb020->get_bu_name_data($result);    // 部名
					$unit  = $this->sgmtb020->get_unit_name_data($result);  // 課・ユニット名
					$data['honbunm'] = $honbu['bunm'];
					$data['bunm'] = $bu['bunm'];
					$data['kanm'] = $unit['bunm'];
				}else{
					// 該当データなし
					// POSTデータの受け渡し
					$data = $_POST;
					// メッセージ設定
					throw new Exception(ERROR_USER_SEARCH);
				}
			}else{
				// バリデーションエラー
				// POSTデータの受け渡し
				$data = $_POST;
				// メッセージ設定
				log_message('debug',"========== delete_search validation error ==========");				
			}
			$common_data = $this->header("delete");           // ヘッダー設定
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			$data['main_form'] = $common_data['form'];     // メイン内formの送信先
			// バリデーション問題なし
			if($v_result)
			{
				// ユーザ管理画面表示
				$this->display($data,"delete");
			}
				log_message('debug',"========== delete_search validation success ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-delete_search'));
			//$this->error_view(ERROR_USER_SEARCH,"delete");
		}
	}

	/**
	 * ユーザー管理(削除画面)
	 *
	 * @access	public
	 * @param	array string
	 * @param	array string
	 * @return	nothing
	 */
	function delete($data=NULL)
	{
		try
		{
			log_message("debug","================ update_user start ====================");
			$update_result = FALSE;
			// バリデーションチェック
			$v_result = $this->validate_check("delete");
			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			$data = $_POST;

			$common_data = $this->header("delete");         // ヘッダー設定
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし			
			$data['main_form'] = $common_data['form'];     // メイン内formの送信先
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// モデル呼び出し
				$this->load->model('sgmtb010'); // ユーザー情報
				//$this->load->model('sgmtb020'); // 部署情報
				//$this->load->model('sgmtb030'); // 区分情報
				//$this->load->model('sgmtb070'); // 組織情報
				// バリデーション結果チェック
				$delete_result = $this->sgmtb010->delete_user($data['shbn']); // ユーザー更新実行
				// 削除結果判定し、完了メッセージ表示
				if($delete_result)
				{
					// 削除成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_DELETE);
				}else{
				log_message('debug',"========== 例外1 ============");				
					// 削除失敗
					throw new Exception(ERROR_USER_DELETE);
				}
				// ユーザ管理画面表示
				$this->display($data,"delete");
			}
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-delete'));
			//$this->error_view($e->getMessage(),"delete");
		}
	}

	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $type = add:登録 update:更新 delete:削除
	 * @return	boolean TRUE:成功 FALSE:エラー
	 */
     function validate_check($type = NULL,$other = NULL,$post=NULL)
	 {
		 try
		 {
			$v_result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			switch($type)
			{
				// 登録
				case "add":
					$config = $this->config->item('validation_rules_add');
					break;
				// 更新
				case "update":
					$config = $this->config->item('validation_rules_update');
					break;
				// 削除
				case "delete":
					$config = $this->config->item('validation_rules_delete');
					break;
			}
			// 検索
			if($other === "search")
			{
				$config = $this->config->item('validation_rules_search');
			}
			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			
			//パスワードチェック

			//if(
			
			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				// 失敗
				//$v_result = FALSE;

				if($other === "search"){	
					throw new Exception(ERROR_SHBN);
				}else if($post['shbn'] ==""){
					throw new Exception(ERROR_SHBN);
				}else if($post['menuhyjikbn'] =="000"){
					throw new Exception(ERROR_KBN);
				}else{
					throw new Exception(ERROR_LOGIN_NEW_PW_LENGTH);
				}
				log_message('debug',"========== delete_user validation error ==========");
			}else{

				if($post['shbn'] ==""){
					$v_result ="社番を入力してください。";
				}else if($post['menuhyjikbn'] =="000"){
					$v_result ="区分を選択してください。";
				}else if(mb_strlen($post['menuhyjikbn'] < 6)){
					$v_result ="パスワードは6文字以上入力してください。";
				}else{
				// 成功
				$v_result = TRUE;
				}
				log_message('debug',"========== delete_user validation success ==========");
				return $v_result;
			}
		 }catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'user-validation'));
			log_message('debug',"message = ".$e->getMessage());
			$this->error_view($e->getMessage(),$type,NULL);
		}
	 }
	
	/**
	 * エラー発生時処理
	 * @access	public
	 * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
	 * @return	none
	 */
	function error_view($e,$type="add",$item=NULL,$msg_type="msg-error")
	{
		log_message('debug',"exception : $e");
		$common_data = $this->header($type);         // ヘッダー設定
		$this->load->library('message_manager');
		// POSTデータ引継ぎ
		$data = $_POST;
		if($e == ERROR_USER_SEARCH)
		{
			$data = $this->init($type);
		}
		$data['shbn']      = $_POST['shbn'];
		$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
		$data['title']     = $common_data['title'];    // タイトルに表示する文字列
		$data['css']       = $common_data['css'];      // 個別CSSのアドレス
		$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
		$data['btn_name']  = $common_data['btn_name']; // ボタン名
		$data['form']      = $common_data['form'];     // formタグのあり・なし
		$data['main_form'] = $common_data['form'];     // メイン内formの送信先
		$data['errmsg'] = $this->message_manager->get_message($e,$item);
		$this->display($data,$type,$msg_type);
	}

	/**
	 * 通常メッセージ設定
	 * @access	public
	 * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
	 * @return	string メッセージ
	 */
	function message_create($m,$item=NULL)
	{
		$this->load->library('message_manager');
		// メッセージ作成
		$message = $this->message_manager->get_message($m,$item);		
		return $message;
	}
}
/* End of file user.php */
/* Location: ./application/controllers/user.php */
