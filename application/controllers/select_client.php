<?php
/**
 * 相手先選択
 */
class Select_client extends MY_Controller {

	/***
	 * @param
	 */
	function index($conf_name = MY_SELECT_CLIENT_HEAD,$count=NULL)
	{
		// ヘッダー情報設定
		$this->load->library('common_manager');
		$init_data = $this->common_manager->init(SHOW_SELECT_CLIENT);

		//企画獲得で送信データを保持するためsessionにセット
		if( isset($_POST['keep_val'])) {
			$_SESSION["keep_val"]=$_POST['keep_val'];
			$session_data = array('keep_val' => $_POST['keep_val']);
			$this->session->set_userdata($session_data);
		}

		if ( isset($_POST['referer']) ) {
			$_SESSION["referer"]=$_POST['referer'];
			$session_data = array(
				'referer' => $_POST['referer']
			);
			$this->session->set_userdata($session_data);
		}else if($this->session->userdata('referer')){
			$_SESSION["referer"]=$this->session->userdata('referer');
		}

		if(isset($conf_name)){
			$conf_name = $this->_checkConfName($conf_name);
		}else if ( isset($_POST['gkubun']) ) {
			// refererがTodoの場合
			$conf_name = $this->_checkConfName($_POST['gkubun']);
		}else if($this->session->userdata('gkubun')){
			$conf_name = $this->_checkConfName($this->session->userdata('gkubun'));
		}

		try
		{
			log_message('debug',"========== Select_client index start ==========");
			$data = $this->init($conf_name); // ヘッダデータ等初期情報取得
			$data["count"]= $count;
			$data["conf"]=$conf_name;
			$data["list_table"] = $this->_get_list($data);

			// Main表示情報取得
			$plan_data['existence'] = FALSE;
			$this->display($data); // 画面表示処理
			log_message('debug',"========== Select_client index end ==========");

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-index'));
		}
	}

	/**
	 * conf_nameが正しいかチェック。正しくない場合は本部検索へ
	 * Enter description here ...
	 * @param unknown_type $conf_name
	 */
	private function _checkConfName($conf_name)
	{
		$conf_names = array(MY_SELECT_CLIENT_AGENCY,
							MY_SELECT_CLIENT_HEAD,
							MY_SELECT_CLIENT_MAKER,
							MY_SELECT_CLIENT_SHOP);
		if ( !in_array($conf_name, $conf_names) )
		{
			return MY_SELECT_CLIENT_HEAD;
		} else {
			return $conf_name;
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
			log_message('debug',"========== Select_client init start ==========");
			log_message('debug',"\$conf_name = $conf_name");

			// 初期化
			$common_data = $this->config->item($conf_name);

			$this->load->library('table_set');
			// セッション情報から社番を取得
			$data['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data['admin_flg'] = $this->session->userdata('admin_flg');

			// 初期共通項目情報
			$data['title']       = $common_data['title']; // タイトルに表示する文字列
			$data['css']         = $common_data['css']; // 個別CSSのアドレス
			$data['image']       = $common_data['image']; // タイトルバナーのアドレス
			$data['errmsg']      = $common_data['errmsg']; // エラーメッセージ
			$data['btn_name']    = $common_data['btn_name']; // ボタン名
			$data['form']        = $common_data['form']; // フォーム名
			$data['form_url']	 = $this->config->item('base_url').'index.php/select_client_search/'.$common_data['form'].'/';
			$data['conf_name']   = $conf_name; // 業務区分

			log_message('debug',"========== Select_client init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-init'));
			//$this->error_view($e);
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
//			$referer = $this->session->userdata('referer');
//			$data['form'] = $this->config->item('base_url').'index.php/'.$referer.'?ret=true';
//			log_message('debug',"========== data_memo display start ==========");
//			// ヘッダ部生成 title css image errmsg を使用
//			$data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
//			// メインコンテンツ生成 contents を使用
//			$data['main']   = $this->load->view('parts/select_client', $data, TRUE);
//			// メニュー部生成 表示しない場合はNULL admin_flg を使用
//			$data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);
//			// フッダ部生成 必ず指定する事
//			$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
//			// 表示処理
//			log_message('debug',"========== data_memo display end ==========");
//			$this->load->view(MY_VIEW_LAYOUT, $data);




			$referer = $this->session->userdata('referer');
//			$data['form'] = $this->config->item('base_url').'index.php/'.$referer.'?ret=true';
			$data['form'] = $this->config->item('base_url').'index.php/'.$referer;
			log_message('debug',"========== Select_client display start ==========");

			// 表示処理
			$this->load->view(MY_VIEW_SELECT_CLIENT, $data, FALSE);
			log_message('debug',"========== Select_client display end ==========");

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-display'));
			//$this->error_view($e);
		}
	}

	/**
	 *
	 * 本部検索
	 */
	public function select_head()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client add start ==========");
			$conf_name = MY_SELECT_CLIENT_HEAD;

			$this->index($conf_name);

			log_message('debug',"========== Select_client add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-select_head'));
			
		}
	}

	/**
	*
	* 店舗（メーカー）
	*/
	public function select_maker()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client add start ==========");
			$conf_name = MY_SELECT_CLIENT_MAKER;

			$this->index($conf_name);

			log_message('debug',"========== Select_client add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-select_maker'));
		}
	}


	/**
	*
	* 店舗（一般）
	*/
	public function select_shop()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client add start ==========");
			$conf_name = MY_SELECT_CLIENT_SHOP;

			$this->index($conf_name);

			log_message('debug',"========== Select_client add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-select_shop'));
		}
	}

	/**
	*
	* 代理店
	*/
	public function select_agency()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client add start ==========");
			$conf_name = MY_SELECT_CLIENT_AGENCY;

			$this->index($conf_name);

			log_message('debug',"========== Select_client add end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-select_agency'));
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
			log_message('debug',"========== Select_client _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			switch($data["form"]) {
				case MY_SELECT_SEARCH_HEAD:
					$table_data = $this->table_set->set_select_client_list($data, MY_USER_ACTIVITY_HEAD);
					break;
				case MY_SELECT_SEARCH_MAKER:
					$table_data = $this->table_set->set_select_client_list($data, MY_USER_ACTIVITY_MAKER);
					break;
				case MY_SELECT_SEARCH_AGENCY:
					$table_data = $this->table_set->set_select_client_list($data, MY_USER_ACTIVITY_AGENCY);
					break;
			}
			log_message('debug',"========== Select_client _set_tab_data end ==========");
			return $table_data;

		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-_get_list'));
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
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-validate_check'));
			//log_message('debug',"message = ".$e->getMessage());
			//$this->error_view($e->getMessage(),$type,NULL);
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
