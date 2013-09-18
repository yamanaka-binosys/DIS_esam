<?php

class Interim_client extends MY_Controller {
	/**
	 * 仮相手先(登録)
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
			$this->load->view('/parts/error/error.php',array('errcode' => 'INTERIM_CLIENT_INDEX'));
			$this->error_view(ERROR_SYSTEM,"add");
		}
	}
	
	/**
	 * 仮相手先(初期化)
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
			$common_data = $this->header($type);           // ヘッダー設定
			// 共通項目情報
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			$data['main_form'] = $common_data['form'];     // メイン内formの送信先
			// ログイン者情報取得
			$this->load->library('common_manager');
			$heder_auth = $this->common_manager->get_auth_name($this->session->userdata('shbn'));
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
						
			log_message('debug',"========== user init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'INTERIM_CLIENT_INIT'));
			$this->error_view(ERROR_SYSTEM,$type);
		}
	}
	
	/**
	 * 仮相手先(表示)
	 *
	 * @access	public
	 * @param	array
	 * @return	none
	 */
	function display($data,$type="add")
	{
		try
		{
			log_message('debug',"========== user display start ==========");
			log_message('debug',$type);
			$this->load->library('table_set');
			$this->load->library('tab_set');
			$data['tab'] = $this->tab_set->tab_list_set("interim_client");                  // タブ作成
			//$data['kari_client_table'] = $this->table_set->set_kari_client_data($data,$type);         // 仮相手先情報
			
			// ヘッダ部生成 title css image errmsg を使用
			$data['header']    = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']      = $this->load->view(MY_VIEW_KARI_CLIENT, $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']      = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			// フッダ部生成 必ず指定する事
			$data['footer']    = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			// 表示処理
			log_message('debug',"========== user display end ==========");
			$this->load->view(MY_VIEW_LAYOUT, $data);
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,$type);
		}
	}
	
	/**
	 * 仮相手先画面ヘッダー設定
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
				$header_data = $this->config->item('s_kari_client_add');
			// 更新ページの場合
			}else if($type === "update"){
				$header_data = $this->config->item('s_kari_client_update');
			// 削除ページの場合
			}else if($type === "delete"){
				$header_data = $this->config->item('s_kari_client_delete');
			}
			log_message('debug',"========== user header end ==========");
			return $header_data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,$type);
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
			$this->load->library('interim_client_manager');
			$data = $this->init("add");
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['set'])){
					// ヘッダー付属ボタンの場合
					$data = $this->add($_POST); // 登録実行
				}
			}else{
				// 初期画面設定
				$data['kari_client_table'] = $this->interim_client_manager->set_view();
			}
			// 仮相手先情報画面表示
			$this->display($data,"add");
			log_message('debug',"========== user add_select_type end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,"add");			
		}
	}

	
	/**
	 * 仮相手先情報(登録画面)
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
			$this->load->library('interim_client_manager');
			$data = $this->init("add");
			// バリデーションチェック
			$v_result = $this->validate_check("add");
			log_message('debug',"v_result=".$v_result);
			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srwtb030'); // 仮相手先情報
				// 登録コード最大値の取得
				$cd_max = $this->srwtb030->get_max_code();
				// 登録処理
				$add_result = $this->srwtb030->insert_kari_client($post,$cd_max['max']); // 仮相手先登録実行
				// 登録結果判定し、完了メッセージ表示
				if($add_result)
				{
					// 表示用仮相手先コードの作成
					$cd_max['max']++; 
					$kcode = "K".sprintf('%07d', $cd_max['max']);
					$post['kraiteskcd'] = $kcode;
					$data['kari_client_table'] = $this->interim_client_manager->set_view(TRUE,$post);
					// 登録成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_ADD);
				}else{
				log_message('debug',"========== 例外1 ============");				
					// 登録失敗
					throw new Exception(ERROR_KARI_CLIENT_ADD);
				}
				return $data;
			}
		}catch(Exception $e){
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
			log_message('debug',"========== user add_select_type start ==========");
			$data = NULL;
			// 初期画面設定
			$data = $this->init("update");
			$this->load->library('interim_client_manager');
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['set'])){
				// ヘッダー付属ボタンの場合
				$data = $this->update($_POST); // 更新実行
				$post = $_POST;
				}
			}else{
				// 相手先選択取得
				//$aite_cd = $this->interim_client_manager->get_aite_code();
				$aite_cd = NULL;
				// 受け取った相手先コードから該当データの取得
				$post = $this->interim_client_manager->get_interim_client_data($this->session->userdata('shbn'),$aite_cd);
			}
				$data['kari_client_table'] = $this->interim_client_manager->set_view(TRUE,$post);
				// 仮相手先画面表示
				$this->display($data,"update");
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,"update");			
		}
	}

	/**
	 * 仮相手先情報(変更画面)
	 *
	 * @access	public
	 * @param	array string
	 * @param	array string
	 * @return	nothing
	 */
	function update($post = NULL)
	{
		try
		{
			log_message("debug","================ update_user start ====================");
			$update_result = FALSE;
			$this->load->library('interim_client_manager');
			// バリデーションチェック
			$v_result = $this->validate_check("update");
			// 初期画面設定
			$data = $this->init("update");
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srwtb030'); // 仮相手先情報
				
				// バリデーション結果チェック
				$update_result = $this->srwtb030->update_kari_client($post); // 仮相手先情報更新実行
				// 更新結果判定し、完了メッセージ表示
				if($update_result)
				{
					// 更新成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_UPDATE);
				}else{
				log_message('debug',"========== 例外1 ============");				
					// 更新失敗
					throw new Exception(ERROR_KARI_CLIENT_UPDATE);
				}
			}else{
				$data['errmsg'] = $this->message_create(ERROR_KARI_CLIENT_VALID_REQUIRE);
			}
			
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e->getMessage(),"update");
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
			log_message('debug',"========== interim_client delete_select_type start ==========");
			$data = NULL;
			// 初期画面設定
			$data = $this->init("delete");
			$this->load->library('interim_client_manager');
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['set'])){
				// ヘッダー付属ボタンの場合
				$data = $this->delete($_POST); // 更新実行
				$post = $_POST;
				}
			}else{
				// 相手先選択取得
				//$aite_cd = $this->interim_client_manager->get_aite_code();
				$aite_cd = NULL;
				// 受け取った相手先コードから該当データの取得
				$post = $this->interim_client_manager->get_interim_client_data($this->session->userdata('shbn'),$aite_cd);
			}
				$data['kari_client_table'] = $this->interim_client_manager->set_view(TRUE,$post);
				// 仮相手先画面表示
				$this->display($data,"delete");
			log_message('debug',"========== interim_client delete_select_type end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,"delete");			
		}
	}

	/**
	 * 仮相手先情報(削除画面)
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
			log_message("debug","================ delete start ====================");
			$update_result = FALSE;
			$this->load->library('interim_client_manager');
			// バリデーションチェック
			$v_result = $this->validate_check("delete");
			// 初期画面設定
			$data = $this->init("delete");
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srwtb030'); // 仮相手先情報
				
				// バリデーション結果チェック
				$update_result = $this->srwtb030->delete_kari_client($post); // 仮相手先情報削除実行
				// 削除結果判定し、完了メッセージ表示
				if($update_result)
				{
					// 削除成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_DELETE);
				}else{
				log_message('debug',"========== 例外1 ============");				
					// 削除失敗
					throw new Exception(ERROR_KARI_CLIENT_DELETE);
				}
			}else{
				$data['errmsg'] = $this->message_create(ERROR_KARI_CLIENT_VALID_REQUIRE);
			}
			
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e->getMessage(),"delete");
		}
	}

	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $type = add:登録 update:更新 delete:削除
	 * @return	boolean TRUE:成功 FALSE:エラー
	 */
     function validate_check($type = NULL,$other = NULL)
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
					$config = $this->config->item('validation_rules_kari_client_add');
					break;
				// 更新
				case "update":
					$config = $this->config->item('validation_rules_kari_client_update');
					break;
				// 削除
				case "delete":
					$config = $this->config->item('validation_rules_kari_client_delete');
					break;
			}
			// 検索
			if($other === "select")
			{
				$config = $this->config->item('validation_rules_kari_client_search');
			}
			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				// 失敗
				$v_result = FALSE;
				throw new Exception(ERROR_KARI_CLIENT_VALID_REQUIRE);
				log_message('debug',"========== delete_user validation error ==========");
			}else{
				// 成功
				$v_result = TRUE;
				log_message('debug',"========== delete_user validation success ==========");
				return $v_result;
			}
		 }catch(Exception $e){
			// エラー処理
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
	function error_view($e,$type="add",$item=NULL)
	{
		log_message('debug',"exception : $e");
		$common_data = $this->header($type);         // ヘッダー設定
		$this->load->library('message_manager');
		$this->load->library('interim_client_manager');
		// POSTデータ引継ぎ
		$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
		$data['title']     = $common_data['title'];    // タイトルに表示する文字列
		$data['css']       = $common_data['css'];      // 個別CSSのアドレス
		$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
		$data['btn_name']  = $common_data['btn_name']; // ボタン名
		$data['form']      = $common_data['form'];     // formタグのあり・なし
		$data['main_form'] = $common_data['form'];     // メイン内formの送信先
		$data['errmsg'] = $this->message_manager->get_message($e,$item);
	}

	/**
	 * エラー発生時処理
	 */
	function error_message()
	{
		$this->load->library('message_manager');
		// メッセージ作成
		$data['errmsg'] = $this->message_manager->get_message(ERROR_SYSTEM,NULL);
		log_message('debug',"errmsg : ".$data['errmsg']);		
		
		// メッセージデータを返す
		return $data;
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
