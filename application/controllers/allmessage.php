<?php

class Allmessage extends MY_Controller {
	/**
	 * メッセージ
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
			$this->error_view(ERROR_SYSTEM,"add");
			$this->load->view('/parts/error/error.php',array('errcode' => 'ALLMESSAGE-INDEX'));
		}
	}
	
	/**
	 * メッセージ(初期化)
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
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			// メッセージ画面独自項目
			$data['s_year']  = NULL; // 通知開始日（年）
			$data['s_month'] = NULL; // 通知開始日（月）
			$data['s_day']   = NULL; // 通知開始日（日）
			$data['e_year']  = NULL; // 通知終了日（年）
			$data['e_month'] = NULL; // 通知終了日（月）
			$data['e_day']   = NULL; // 通知終了日（日）
			$data['comment'] = NULL; // コメント
			$data['sys_all'] = NULL; // 当システム会員
			$data['file']    = NULL; // ファイル
			$data['upload']  = TRUE; // ファイルアップロード対応
			
			log_message('debug',"========== user init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->error_view(ERROR_SYSTEM,$type);
			$this->load->view('/parts/error/error.php',array('errcode' => 'ALLMESSAGE-INIT'));
		}
	}
	
	/**
	 * メッセージ(表示)
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
			$data['allmessage_table'] = $this->table_set->set_allmessage_data($data,$type);   // メッセージ情報
			$data['file_table'] = $this->table_set->set_allmessage_file($data,$type);         // メッセージ情報
			$data['allmessage_check'] = $this->table_set->set_allmessage_check($data);
			
			$data['header'] = $this->load->view(MY_VIEW_CHECKER_HEADER, $data, TRUE);     // ヘッダ部生成 title css image errmsg を使用
			$data['main']   = $this->load->view(MY_VIEW_ALLMESSAGE, $data, TRUE); // メインコンテンツ生成 contents を使用
			$data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);       // メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);        // フッダ部生成 必ず指定する事
			// 表示処理
			log_message('debug',"========== user display end ==========");
			$this->load->view(MY_VIEW_LAYOUT, $data);
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,$type);
			$this->load->view('/parts/error/error.php',array('errcode' => 'DISPLAY-INIT'));
		}
	}
	
	/**
	 * メッセージ画面ヘッダー設定
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
				$header_data = $this->config->item('s_allmessage_add');
			}
			log_message('debug',"========== user header end ==========");
			return $header_data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,$type);
			$this->load->view('/parts/error/error.php',array('errcode' => 'ALLMESSAGE-HEADER'));
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
				log_message('debug',"========== add start ==========");
				// ヘッダー付属ボタンの場合
				$data = $this->add($_POST); // 登録実行	
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
				// ログイン者情報取得
				$this->load->library('common_manager');
				$heder_auth = $this->common_manager->get_auth_name($this->session->userdata('shbn'));
				$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
				$data['ka_name'] = $heder_auth['ka_name'];
				$data['shinnm'] = $heder_auth['shinnm'];
				
				// 通知開始日は本日をセット
				$data['s_year'] = date('Y');
				$data['s_month'] = date('n');
				$data['s_day'] = date('j');
				// 通知終了日は本日をセット
				$data['e_year'] = date('Y');
				$data['e_month'] = date('n');
				$data['e_day'] = date('j');
				
				// メッセージ画面表示
				$this->display($data,"add");
			}
			log_message('debug',"========== user add_select_type end ==========");
		}catch(Exception $e){
			// エラー処理		
			$this->load->view('/parts/error/error.php',array('errcode' => 'ALLMESSAGE-ADD-SELECT-TYPE'));
		}
	}

	
	/**
	 * メッセージ情報(登録画面)
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
			$this->load->library('common_manager');
			$add_result = FALSE;
			$error = "";
			
			$shbn = $this->session->userdata('shbn');
			// セッションチェック
			if(empty($shbn)){
				// エラー表示
				throw new Exception(ERROR_SYSTEM);
			}
			
			// バリデーションチェック
			$v_result = $this->validate_check("add");
			log_message('debug',"v_result=".$v_result);
			
			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			$data = $post;
			
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
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($shbn);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			
			// バリデーションが問題なければ登録実行
			if($v_result)
			{
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('sgmtb010');
				$this->load->model('srktb060'); // メッセージ情報
				$shinNm = $this->sgmtb010->get_shin_nm($shbn); //社員名
				$jyohoNum = $this->srktb060->get_jyoho_num_data();	// 情報ナンバー取得
				
				// 月、日付のゼロパディング
				$post['s_month'] = sprintf('%02d', $post['s_month']);
				$post['s_day']   = sprintf('%02d', $post['s_day']);
				$post['e_month'] = sprintf('%02d', $post['e_month']);
				$post['e_day']   = sprintf('%02d', $post['e_day']);
				$add_result = $this->srktb060->insert_message_data($post,$jyohoNum,$shbn,$shinNm);	// 情報ナンバー取得
				
				// 登録結果判定し、完了メッセージ表示
				if($add_result)
				{
					// 登録成功
					// 完了メッセージ表示
					$data['errmsg'] = $this->message_create(USER_ADD);
				}else{
					log_message('debug',"========== 例外1 ============");
					// 登録失敗
					throw new Exception(ERROR_ALLMESSAGE_ADD);
				}
				// メッセージ画面表示
				$this->display($data,"add");
			}
		}catch(Exception $e){
			log_message('debug',"========== 例外 ============");
			log_message('debug',$e->getMessage());
			// エラー処理
			$this->error_view($e->getMessage(),"add");
			$this->load->view('/parts/error/error.php',array('errcode' => 'ALLMESSAGE-ADD'));
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
			log_message('debug',"-------------------------start----------------------------");
			foreach($_POST as $key => $value)
			{
				log_message('debug',$key." = ".$value);
			}
			log_message('debug',"------------------------end-----------------------------");
			$v_result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			switch($type)
			{
				// 登録
				case "add":
					$config = $this->config->item('validation_rules_allmessage_add');
					break;
			}
			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				log_message('debug',"========== delete_user validation error ==========");
				// 失敗
				//$v_result = FALSE;
				throw new Exception(ERROR_ALLMESSAGE_REQUIRE);
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
			$this->load->view('/parts/error/error.php',array('errcode' => 'ALLMESSAGE-VALIDATION-CHECK'));
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
