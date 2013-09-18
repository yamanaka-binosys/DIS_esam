<?php
class Item_visibility extends MY_Controller {

	/***
	 * @param mode TRUE：表有り　FALSE：表無し　
	 */
	function index($conf_name = MY_SELECT_ITEM_VISIBILITY)
	{
		try
		{
			log_message('debug',"========== item_visibility index start ==========");
			$data = $this->init($conf_name); // ヘッダデータ等初期情報取得

			//プルダウンを生成
			$data["item_box"] = $this ->_get_item_box();
			//一覧を取得
			$data["list_table"] = $this->_get_list($data);

			// Main表示情報取得
			$this->display($data); // 画面表示処理
			log_message('debug',"========== item_visibility index end ==========");

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'ITEM_VISIBILITY_INDEX'));
		}
	}

	/**
	 * 登録ボタン押下時
	 */
	function add_item_visibility($conf_name = MY_SELECT_ITEM_VISIBILITY){

		log_message('debug',"========== item_visibility add_item_visibility start ==========");
		$data = $this->init($conf_name); // ヘッダデータ等初期情報取得

		//POSTデータを取得
		$this->session->set_userdata(array("search_data" => $this->input->post()));
		$post_data = $this->input->post();

		//押されたボタンによって処理を分岐
		if($this->input->post("display")){	//表示ボタンが押下された場合

			log_message('debug',"=============== item_visibility add_item_visibility search start ==========");

			//検索条件を取得
			$pid = $post_data['item'];

			log_message('debug',"=============== item_visibility add_item_visibility search end ==========");

		}else{	//登録ボタンが押下された場合

			log_message('debug',"=============== item_visibility add_item_visibility regist start ==========");

			//登録する情報を取得(行単位のデータに変換)
			if(!isset($post_data['item_disp_chk']) || empty($post_data['item_disp_chk'])){
				$post_data['item_disp_chk'] = array();
			}
			$converted_data = $this -> _convertRelationData($post_data['item_disp_key'], $post_data['item_disp_name'], $post_data['item_disp_chk']);

			//登録実行
			$CI =& get_instance();
			$CI->load->model('sgmtb041'); // 画面生成情報
			foreach ($converted_data as $reg_data) {
				//更新処理
				$result = $CI->sgmtb041->update_item($reg_data);

				// 登録結果判定し、完了メッセージ表示
				if(!$result){
					log_message('debug',"========== 例外1 ============");
					// 更新失敗
					throw new Exception(ERROR_ITEM_VISIBILITY_ADD);
				}
			}

			// 更新成功  完了メッセージ表示
			$data['errmsg'] = $this->message_create(USER_UPDATE);

			//検索条件を取得
			$pid = $converted_data[0]['pid'];

			log_message('debug',"=============== item_visibility add_item_visibility regist end ==========");
		}

		//プルダウンを生成
		$data["item_box"] = $this ->_get_item_box($pid);
		//一覧を取得
		$data["list_table"] = $this->_get_list($data, $pid);

		//画面表示
		$this->display($data);

		log_message('debug',"========== item_visibility add_item_visibility end ==========");
	}
	/**
	 * POSTデータを組み換えてDBに登録できるような配列に変換
	 *
	 * @param $key_arr 行データのキーとなるPOSTデータ(配列)
	 * @param $name_arr 項目名に入力されたPOSTデータ(配列)
	 * @param $chk_arr チェックされたPOSTデータ(配列)
	 */
	private function _convertRelationData($key_arr, $name_arr, $chk_arr){

		$ret_arr = array();

		//ループ
		for ($i = 0; $i < count($key_arr); $i++){

			$row = array();

			$key = $key_arr[$i];
			//アンダーバーでキーを分割して、pid、dbname、dbitemに分割して保存
			$key_sep_arr = explode(':', $key_arr[$i]);
			$row['pid'] = $key_sep_arr[0];
			$row['dbname'] = $key_sep_arr[1];
			$row['dbitem'] = $key_sep_arr[2];

			//キーを保存
			$row['key'] = $key;
			//表示名ーを保存
			$row['itemdspname'] = $name_arr[$i];

			//チェックを取得
			if(!empty($chk_arr) && in_array($key, $chk_arr)){
				$row['dispflg'] = "1";
			}else{
				$row['dispflg'] = "0";
			}

			//リターン配列に追加
			$ret_arr[] = $row;
		}

		return $ret_arr;
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
			log_message('debug',"========== item_visibility init start ==========");
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
			/* 変更 kentaro */
			$data['form_url']    = $this->config->item('base_url').'index.php/item_visibility/add_item_visibility';   // 送信先
			//$data['form_url']    = $this->config->item('base_url').'index.php/item_visibility/search';   // 送信先
			$data['btn_name']    = $common_data['btn_name']; // ボタン名
			$data['form']        = $common_data['form']; // フォーム名
			log_message('debug',"========== item_visibility init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'ITEM_VISIBILITY_INIT'));
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
			log_message('debug',"========== item_visibility display start ==========");
			// ヘッダ部生成 title css image errmsg を使用
			$data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']   = $this->load->view(MY_VIEW_ITEM_VISIBILITY, $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			// フッダ部生成 必ず指定する事
			$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			// 表示処理
			log_message('debug',"========== item_visibility display end ==========");

			//$this->load->view(MY_VIEW_LAYOUT, $data);
			$this->load->view(MY_VIEW_ITEM_VISIBILITY, $data);

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'ITEM_VISIBILITY_DISPLAY'));
//			$this->error_view($e);
		}
	}

	/**
	 * 画面名のプルダウンを生成します
	 * @param unknown_type $sel_value
	 */
	private function _get_item_box($sel_value = 'SRN020x'){

		$this->load->helper('form');
		$options = array(
			'SRN020x' => '行動予定',
			'SRN030x' => '日報実績',
			'SRR010x' => '商談履歴',
			'SRS030x' => '情報メモ',
		);

		return form_dropdown('item', $options, $sel_value);

	}
	/**
	 * 画面生成データを取得
	 *
	 * @access private
	 * @param $data ARRAY
	 * @return
	 */
	private function _get_list($data, $pid = 'SRN020x')
	{
		try
		{
			log_message('debug',"========== item_visibility _set_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;

			// $whereに、連想配列で$whereを指定
			$where = array('PID' => $pid);
			$table_data = $this->table_set->get_item_visibility_data($data, $where);
			log_message('debug',"========== item_visibility _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'ITEM_VISIBILITY_GET-LIST'));
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

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
