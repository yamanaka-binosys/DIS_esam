<?php

class Message extends MY_Controller {
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
			return;
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'MESSEAGE-INDEX'));
			$this->error_view(ERROR_SYSTEM,"add");
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
			log_message('debug',"========== message display start ==========");
			log_message('debug',$type);
			$this->load->library('table_set');
			$data['allmessage_table'] = $this->table_set->set_allmessage_data($data,$type);   // メッセージ情報
			$data['file_table'] = $this->table_set->set_allmessage_file($data,$type);         // メッセージ情報
			$data['allmessage_check'] = $this->table_set->set_allmessage_check($data);
			// 表示処理
			log_message('debug',"========== message display end ==========");
      if (isset($data['errmsg'])) { log_message('debug', 'errmsg: '. $data['errmsg']); }
      if (isset($data['infomsg'])) { log_message('debug', 'infomsg: '. $data['infomsg']); }
				
			$this->load->view(MY_VIEW_MESSAGE, $data);
			return;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'MESSEAGE-DISPLAY'));
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
			log_message('debug',"========== message add_select_type start ==========");
			$data = NULL;
			$kakninshbn = NULL;
			$this->load->library('common_manager');
			// セッションに戻り先URLを保存
			$from_url = $this->config->item('base_url')."index.php/message/add_select_type/";
			$session_data = array('from_url' => $from_url);
			$this->session->set_userdata($session_data);
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 確認者選択ボタン押下
				if($_POST['action'] === 'action_select_checker'){
					$this->select_checker();
				}else if($_POST['action'] === 'action_submit'){
					// 登録ボタン押下
					log_message('debug',"========== add start ==========");
					// ヘッダー付属ボタンの場合
					$this->add($_POST); // 登録実行
					//return;
				}
			}else{
				// 初期画面設定
				$common_data = $this->config->item(MY_MESSAGE_DATA);
				// 共通初期化処理
				$data = $this->common_manager->init(SHOW_MESSAGE);
				// 通知開始日は本日をセット
				$data['s_year'] = date('Y');
				$data['s_month'] = date('n');
				$data['s_day'] = date('j');
				// 通知終了日は本日をセット
				$data['e_year'] = date('Y');
				$data['e_month'] = date('n');
				$data['e_day'] = date('j');
				// 送付先設定
				$data = $this->create_send_shbn($data);

				// メッセージ画面表示
				$this->display($data,"add");
			}
			log_message('debug',"========== message add_select_type end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'MESSEAGE-ADD-SELECT-TYPE'));
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
			log_message('debug',"========== message add start ==========");
			// 初期化
				
			$this->load->library('common_manager');
			$this->load->library('file_uploade_manager');
			$this->load->helper('common');
			$add_result = FALSE;
			$error = "";
			$up_res = FALSE;

			$shbn = $this->session->userdata('shbn');
			// セッションチェック
			if(empty($shbn)){
				// エラー表示
				throw new Exception(ERROR_SYSTEM);
			}
			

			$invalidFileSize = (
			  startsWith(strtolower($_SERVER['CONTENT_TYPE']), 'multipart/form-data') &&
			  2000000 <= $_SERVER['CONTENT_LENGTH']);
				
			if ($invalidFileSize) {
				log_message('debug', "2M以上のファイルを受信しました。{$_SERVER['CONTENT_LENGTH']}");
			}
			
			
			// バリデーションチェック
			$v_result = $this->validate_check("add");
			log_message('debug',"v_result=".$v_result);

			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			// 共通初期化処理
			$data = $this->common_manager->init(SHOW_MESSAGE);
				
			// バリデーションが問題なければ登録実行
			if($v_result && !$invalidFileSize )
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
				if(isset($post['sys_all']) && $post['sys_all'] == 'on'){
					$post['allflg'] = '1';
				} else {
					$post['allflg'] = '0';
				}
				if(isset($post['is_bold']) && $post['is_bold'] == 'on'){
					$post['is_bold'] = 't';
				} else {
					$post['is_bold'] = 'f';
				}
				// 添付ファイル情報
				if(!empty($_FILES['file']['name'])){
					$post['file'] = $_FILES['file']['name'];
					log_message('debug',"tmp_file_name = ".$post['file']);
					// ファイルアップロード処理
					$tmp_data = array(
							'tmp_name'  => $_FILES['file']['tmp_name'],
							'file_name' => $_FILES['file']['name'],
							'dir_name'  => 'message',
							'jyohonum'  => $jyohoNum
					);
					$post['file'] = $this->file_uploade_manager->file_upload($tmp_data);
				}else{
					$post['file'] = NULL;
				}
				$add_result = $this->srktb060->insert_message_data($post,$jyohoNum,$shbn,$shinNm);	// 情報ナンバー取得

				// 通知開始日は本日をセット
				$data['s_year'] = $post['s_year'];
				$data['s_month'] = $post['s_month'];
				$data['s_day'] = $post['s_day'];
				// 通知終了日は本日をセット
				$data['e_year'] = $post['e_year'];
				$data['e_month'] = $post['e_month'];
				$data['e_day'] = $post['e_day'];
				// コメント
				$data['comment'] = $post['comment'];
				// システム全員通知
				if(!empty($post['sys_all'])){
					$data['sys_all'] = $post['sys_all'];
				}
				if(!empty($post['is_bold'])){
					$data['is_bold'] = $post['is_bold'];
				}
				// 送付先
				$data = $this->create_send_shbn($data,$post);

				// 登録結果判定し、完了メッセージ表示
				if($add_result)
				{
					// 登録成功
					// 完了メッセージ表示
					$data['infomsg'] = $this->message_create(USER_ADD);
					log_message('debug',$data['infomsg']);
				}else{
					log_message('debug',"========== 例外1 ============");
					// 登録失敗
					throw new Exception(ERROR_ALLMESSAGE_ADD);
				}
				log_message('debug',"===========".$data['kakninshbn']);
				// メッセージ画面表示
				//return;
			}else{
				if ($invalidFileSize) {
					$data['errmsg'] = 'アップロードできるファイル容量上限は2Mです。';
				}

				// 通知開始日は本日をセット
				$data['s_year'] = $post['s_year'];
				$data['s_month'] = $post['s_month'];
				$data['s_day'] = $post['s_day'];
				// 通知終了日は本日をセット
				$data['e_year'] = $post['e_year'];
				$data['e_month'] = $post['e_month'];
				$data['e_day'] = $post['e_day'];
				// コメント
				$data['comment'] = $post['comment'];
				// システム全員通知
				if(!empty($post['sys_all'])){
					$data['sys_all'] = $post['sys_all'];
				}
				if(!empty($post['is_bold'])){
					$data['is_bold'] = $post['is_bold'];
				}
			}
			// メッセージ画面表示
			$this->display($data,"add");

		}catch(Exception $e){
			log_message('debug',"========== 例外 ============");
			$this->load->view('/parts/error/error.php',array('errcode' => 'MESSEAGE-ADD'));
			log_message('debug',$e->getMessage());
			// エラー処理
			$this->error_view($e->getMessage(),"add");
		}
	}

	/**
	 * 送付先情報生成
	 *
	 * @access	public
	 * @param	string
	 * @return	array
	 */
	function select_checker()
	{
		try{
			log_message('debug',"========== message create_send_shbn start ==========");
			$base_url = $this->config->item('base_url');
			if($_POST){
				$session_data = array(
						's_date'  => $_POST['s_year'].','.$_POST['s_month'].','.$_POST['s_day'],
						'e_date'  => $_POST['e_year'].','.$_POST['e_month'].','.$_POST['e_day'],
						'comment' => isset($_POST['comment']) ? $_POST['comment'] : '',
						'file' => isset($_POST['file']) ?  $_POST['file'] : '',
						'sys_all' => isset($_POST['sys_all']) ? $_POST['sys_all'] : '1'
				);
				$this->session->set_userdata($session_data);
				$check = $this->session->userdata('s_date');
				log_message('debug',"session_s_date = ".$check);
				$check = $this->session->userdata('e_date');
				log_message('debug',"session_e_date = ".$check);
				$check = $this->session->userdata('comment');
				log_message('debug',"session_comment = ".$check);
				$check = $this->session->userdata('sys_all');
				log_message('debug',"session_sys_all = ".$check);

				// セッションにテンプテーブル仕様フラグを保存
				$session_data = array('tmp_flg' => TRUE);
				$this->session->set_userdata($session_data);
			}
			log_message('debug',"========== message create_send_shbn end ==========");
			header("Location: ".$base_url."index.php/select_checker/index");
			return;
		}catch(Exception $e){
		$this->load->view('/parts/error/error.php',array('errcode' => 'MESSEAGE-SELECT-CHECKER'));
			log_message('debug',"error");
		}
	}

	/**
	 * 送付先情報生成
	 *
	 * @access	public
	 * @param	array  $data = view設定データ
	 * @return	array  $data = view設定データ
	 */
	function create_send_shbn($data = NULL,$post = NULL)
	{
		try{
			log_message('debug',"========== message create_send_shbn start ==========");
			$this->load->library('common_manager');
			$kakunin = "";
			$cnt = 0;
			$tmp_flg = FALSE;
			// 確認者社番をセッションから取得
			$kakunin_shbn = $this->session->userdata('checker_shbn');
			// セッションから情報取得後セッション破棄
			$this->session->unset_userdata('checker_shbn');
			$this->session->unset_userdata('busyo_shbn');
			$this->session->unset_userdata('group_shbn');
			// 社番をスペース区切りにする
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
				$session_data = array('kakninshbn' => $kakunin);
				$this->session->set_userdata($session_data);
				log_message('debug',"kakunin = ".$kakunin);
			}
				
			// TMPフラグ取得
			$tmp_flg = $this->session->userdata('tmp_flg');
				
			if($tmp_flg){
				// 入力途中だったものを取得
				$s_date = $this->session->userdata('s_date');
				if($s_date){
					$s_ymd = explode(',', $s_date);
					$data['s_year'] = $s_ymd[0];
					$data['s_month'] = $s_ymd[1];
					$data['s_day'] = $s_ymd[2];
					$this->session->unset_userdata('s_date');
				}
				$e_date = $this->session->userdata('e_date');
				if($e_date){
					$e_ymd = explode(',', $e_date);
					$data['e_year'] = $e_ymd[0];
					$data['e_month'] = $e_ymd[1];
					$data['e_day'] = $e_ymd[2];
					$this->session->unset_userdata('e_date');
				}
				$comment = $this->session->userdata('comment');
				if($comment){
					$data['comment'] = $comment;
					$this->session->unset_userdata('comment');
				}
				$file = $this->session->userdata('file');
				if ($file) {
					$data['file'] = $file;
					$this->session->unset_data('comment');
				}
				$sys_all = $this->session->userdata('sys_all');
				if($sys_all){
					$data['sys_all'] = $sys_all;
					$this->session->unset_userdata('sys_all');
				}
				$tmp_flg = FALSE;
			}
				
			// セッション情報より社番を取得
			$kakninshbn = $this->session->userdata('kakninshbn');
			$this->session->unset_userdata('kakninshbn');
			log_message('debug',"kakninshbn = ".$kakninshbn);

			// セッションに確認者社番がある場合
			if($kakninshbn){
				$data['kakninshbn'] = $kakninshbn;
				if(strpos($data['kakninshbn']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					$data['kakninshnm'] = $this->common_manager->create_name($data['kakninshbn']);
				}
			}else if($post['action'] === 'action_submit'){
				$data['kakninshbn'] = $post['kakninshbn'];
				$data['kakninshnm'] = $post['kakninshnm'];
			}else{
				$data['kakninshbn'] = "";
				$data['kakninshnm'] = "";
			}
			log_message('debug',"kakninshbn = ".$data['kakninshbn']);
			log_message('debug',"kakninshnm = ".$data['kakninshnm']);
			log_message('debug',"========== message create_send_shbn end ==========");
			return $data;

		}catch(Exception $e){
				$this->load->view('/parts/error/error.php',array('errcode' => 'MESSEAGE-CHREATE-SEND-SHBN'));
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
			$config = $this->config->item('validation_rules_allmessage_add');
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
		/*log_message('debug',"exception : $e");
		 $common_data = $this->config->item(MY_MESSAGE_DATA);
		$this->load->library('message_manager');
		// POSTデータ引継ぎ
		$data = $_POST;
		// 共通初期化処理
		$data = $this->common_manager->init(SHOW_MESSAGE);
		// 通知開始日は本日をセット
		$data['s_year'] = date('Y');
		$data['s_month'] = date('n');
		$data['s_day'] = date('j');
		// 通知終了日は本日をセット
		$data['e_year'] = date('Y');
		$data['e_month'] = date('n');
		$data['e_day'] = date('j');
		$data['errmsg'] = $this->message_manager->get_message($e,$item);
		$this->display($data,$type);*/
	}

	function del($jyohonum)
	{
		log_message('info',"jyohonum : ".$jyohonum);

		$this->load->model('srktb060');
		$this->load->helper('url');

		$this->srktb060->db->delete('srktb060', array('jyohonum' => $jyohonum));
		$this->srktb060->db->trans_commit();
		redirect(base_url('index.php/top/index'));
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
/* End of file message.php */
/* Location: ./application/controllers/message.php */
