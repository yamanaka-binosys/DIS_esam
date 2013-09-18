<?php

class Client extends MY_Controller {

	/***
	 * @param mode TRUE：表有り　FALSE：表無し　
	 */
	function index($conf_name = MY_CLIENT_KARI)
	{
		try
		{
			log_message('debug',"========== data_memo index start ==========");
			$data = $this->init($conf_name); // ヘッダデータ等初期情報取得
			// タブデータ取得
			$data['tab'] = $this->_get_tab_data();

			$data["list_table"] = $this->_get_list($data);

			// Main表示情報取得
			$plan_data['existence'] = FALSE;
			$this->display($data); // 画面表示処理
			log_message('debug',"========== data_memo index end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-INDEX'));
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
			$this->load->library('common_manager');
			$this->load->library('table_set');
			// セッション情報から社番を取得
			$data['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data['admin_flg'] = $this->session->userdata('admin_flg');
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($data['shbn']);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];

			// 初期共通項目情報
			$data['title']       = $common_data['title']; // タイトルに表示する文字列
			$data['css']         = $common_data['css']; // 個別CSSのアドレス
			$data['image']       = $common_data['image']; // タイトルバナーのアドレス
			$data['errmsg']      = $common_data['errmsg']; // エラーメッセージ
			$data['btn_name']    = $common_data['btn_name']; // ボタン名
			$data['form']        = $common_data['form']; // フォーム名
			log_message('debug',"========== data_memo init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-INIT'));
			$this->error_view($e);
		}
	}

	/**
	 * 共通で使用する表示処理
	 *
	 * @access  public
	 * @param   array $data 各種HTML作成時に必要な値
	 * @return  nothing
	 */
	function display($data)
	{
		try
		{
			log_message('debug',"========== data_memo display start ==========");
			// ヘッダ部生成 title css image errmsg を使用
			$data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']   = $this->load->view(MY_VIEW_CLIENT, $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			// フッダ部生成 必ず指定する事
			$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			// 表示処理
			log_message('debug',"========== data_memo display end ==========");
			$this->load->view(MY_VIEW_LAYOUT, $data);
		}catch(Exception $e){
			// エラー処理
//			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-DISPLAY'));
		}
	}

	/**
	 *
	 *
	 *
	 *
	 *
	 */
	public function kari()
	{
		try
		{
			$base_url = $this->config->item('base_url');
//			if(!empty($_POST) && !empty($_POST["search"])) header("Location: http://localhost/elleair/select_client");
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== data_memo add start ==========");
			$conf_name = MY_CLIENT_KARI;

			if(!empty($_POST) && $_POST["set"]) {
				//// DB 更新処理　/////////////////////////////////////

				//////////////////////////////////////////////////////
			}
			$this->index($conf_name);

			log_message('debug',"========== data_memo add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-KARI'));
		}
	}

	/**
	 *
	 *
	 *
	 *
	 *
	 */
	public function seishiki()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
//			if(!empty($_POST) && !empty($_POST["search"])) header("Location: http://localhost/elleair/select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== data_memo add start ==========");
			$conf_name = MY_CLIENT_SEISHIKI;

			if(!empty($_POST) && $_POST["set"]) {
				//// DB 更新処理　/////////////////////////////////////

				//////////////////////////////////////////////////////
			}
			$this->index($conf_name);

			log_message('debug',"========== data_memo add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-SEISHIKI'));
		}
	}

	/**
	 *
	 *
	 *
	 *
	 *
	 */
	public function mt_kari()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
//			if(!empty($_POST) && !empty($_POST["search"])) header("Location: http://localhost/elleair/select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== data_memo add start ==========");
			$conf_name = MY_CLIENT_MT_KARI;

			if(!empty($_POST) && $_POST["set"]) {
				//// DB 更新処理　/////////////////////////////////////

				//////////////////////////////////////////////////////
			}
			$this->index($conf_name);

			log_message('debug',"========== data_memo add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-MT-KARI'));
		}
	}

		/**
	 *
	 *
	 *
	 *
	 *
	 */
	public function mt_seishiki()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
//			if(!empty($_POST) && !empty($_POST["search"])) header("Location: http://localhost/elleair/select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== data_memo add start ==========");
			$conf_name = MY_CLIENT_MT_SEISHIKI;

			if(!empty($_POST) && $_POST["set"]) {
				//// DB 更新処理　/////////////////////////////////////

				//////////////////////////////////////////////////////
			}
			$this->index($conf_name);

			log_message('debug',"========== data_memo add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-MT-SEISHIKI'));
		}
	}

	/**
	 * 仮⇒正式変更・正式⇒正式変更・正式⇒仮復帰・正式⇒元正式復帰タブの文字列を取得
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
			$plan_name = MY_CLIENT . "/";
			log_message('debug',"\$plan_name = $plan_name");
			// タブ用HTML-STRING取得
			$tab_data = $this->tab_manager->set_tab_client($plan_name);

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $tab_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-GET-TAB-DATA'));
		}
	}

	/**
	 * 表（仮⇒正式変更）の作成を取得
	 *
	 * @access private
	 * @param $data ARRAY
	 * @param $mode TRUE=登録　FALSE=更新、削除
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_list($data)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			switch($data["form"]) {
				case "kari"			: $table_data = $this->table_set->set_client_list($data, MY_CLIENT_KARI); break;
				case "seishiki"		: $table_data = $this->table_set->set_client_list($data, MY_CLIENT_SEISHIKI); break;
				case "mt_kari"		: $table_data = $this->table_set->set_client_list($data, MY_CLIENT_MT_KARI); break;
				case "mt_seishiki"	: $table_data = $this->table_set->set_client_list($data, MY_CLIENT_MT_SEISHIKI); break;
			}

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-GET-LIST'));
		}
	}

	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $type = add:登録 update:更新 delete:削除
	 * @return	boolean TRUE:成功 FALSE:エラー
	 */
     function validate_check()
	 {
		 try
		 {
			$v_result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			// 更新
			$config = $this->config->item('validation_rules_client');

			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				// 失敗
				//$v_result = FALSE;
				throw new Exception(ERROR_VALI);
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
			$this->error_view($e->getMessage(),$type,NULL);this->load->view('/parts/error/error.php',array('errcode' => 'CLIENT-SEISHIKI'));
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
		$this->display($data,$type);
	}
}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
