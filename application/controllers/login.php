<?php

class Login extends CI_Controller {

	function index(){
		try{
			// 初期化
			$data = $this->init();
			
			if(isset($_GET['err-msg']) && $_GET['err-msg']==1){

				$data['errmsg'] = 'セッションがタイムアウトしました。再度ログインして下さい。';
			}
			// トップ画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー表示
			$this->error_view($e);
		}
	}
	
	function init(){
		try{
			// セッション情報削除
			$this->load->library('common_manager');
			$this->common_manager->session_delete();
			$common_data = $this->config->item('s_login');
			// 初期共通項目情報
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // エラーメッセージ
			// ログイン画面独自項目
			$data['shbn']      = '';                       // 社番（初期値は''）
			$data['pw']        = '';                       // パスワード（コントローラ内で必ず削除）
			
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	
	function display($data){
		try{
			// ログイン画面生成
			$this->load->view(MY_VIEW_LOGIN, $data, FALSE);
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	
	function check(){
		try{
			// 初期化
			$this->load->library('error_set');
			$data = $this->init();
			// ログインボタン押下時処理
			// POSTデータの社番・パスワードを基にDBに接続し、判定を行い
			// トップ画面表示またはエラーメッセージを表示する
			if(isset($_POST['ipass'])){
				// 半角英数字チェック
				if(isset($_POST['shbn']) AND preg_match("/^[a-zA-Z0-9]+$/", $_POST['shbn'])){
					if(isset($_POST['pw']) AND $_POST['pw'] !=""){
						$result = $this->confirm($_POST['shbn'], $_POST['pw']); // ログインチェック
						if( ! $result){
							$data['errmsg'] = $this->error_set->get_message(ERROR_AUTH,NULL);
							$data['shbn']   = $_POST['shbn'];
							$data['pw']     = '';
						}else{
							$data['open_window'] = true;
							$data['pw'] = '';
							$this->display($data);
							return;
						}						
					}else{
						$data['errmsg'] = $this->error_set->get_message(ERROR_LOGIN_PW,NULL);
							$data['shbn']   = $_POST['shbn'];
							$data['pw']     = '';
					}
				}else{
					$data['errmsg'] = $this->error_set->get_message(ERROR_LOGIN_ID,NULL);
					$data['shbn']   = $_POST['shbn'];
					$data['pw']     = '';
				}
			}else if(isset($_POST['pw_update'])){
				// 半角英数字チェック
				if(isset($_POST['shbn']) AND preg_match("/^[a-zA-Z0-9]+$/", $_POST['shbn'])){
					$result = $this->confirm($_POST['shbn'], $_POST['pw']); // ログインチェック
					if( ! $result){
						$data['errmsg'] = $this->error_set->get_message(ERROR_AUTH,NULL);
						$data['shbn']   = $_POST['shbn'];
						$data['pw']     = '';
					}else{
						
						// 新規パスワード１および２が入力されていて、同じ値である事
						if(isset($_POST['new_pw1']) && $_POST['new_pw1']!=""){
							if(mb_strlen($_POST['new_pw1']) >= 6){
								if( isset($_POST['new_pw2']) && $_POST['new_pw1'] === $_POST['new_pw2']){
									$this->load->model('sgmtb010');
									$res = $this->sgmtb010->update_pass($_POST['shbn'],$_POST['new_pw1']);
									if($res === TRUE){
										// パスワード変更成功
										$data['errmsg'] = $this->error_set->get_message(ERROR_AUTH_SUCCESS,NULL);
										$data['shbn']   = $_POST['shbn'];
										$data['pw']     = '';
										$data['new_pw1']     = '';
										$data['new_pw2']     = '';
									}else{
										// パスワード変更失敗
										$data['errmsg'] = $this->error_set->get_message(ERROR_AUTH_FAILURE,NULL);
										$data['shbn']   = $_POST['shbn'];
										$data['pw']     = $_POST['pw'];
										$data['new_pw1']     = $_POST['new_pw1'];
										$data['new_pw2']     = $_POST['new_pw2'];
										$data['change_pass'] = TRUE;
									}
								}else{
									// pw1入力チェック
									$data['errmsg'] = $this->error_set->get_message(ERROR_AUTH_NEW,NULL);
									$data['shbn']   = $_POST['shbn'];
									$data['pw']     = $_POST['pw'];
									$data['new_pw1']     = $_POST['new_pw1'];
									$data['new_pw2']     = $_POST['new_pw2'];
									$data['change_pass'] = TRUE;
								}
							}else{
								// pw1長さチェック
								$data['errmsg'] = $this->error_set->get_message(ERROR_LOGIN_NEW_PW_LENGTH,NULL);
								$data['shbn']   = $_POST['shbn'];
								$data['pw']     = $_POST['pw'];
								$data['new_pw1']     = $_POST['new_pw1'];
								$data['new_pw2']     = $_POST['new_pw2'];
								$data['change_pass'] = TRUE;
							}
						}else{
							$data['errmsg'] = $this->error_set->get_message(ERROR_LOGIN_NEW_PW,NULL);
							$data['shbn']   = $_POST['shbn'];
							$data['pw']     = $_POST['pw'];
							$data['new_pw1']     = $_POST['new_pw1'];
							$data['new_pw2']     = $_POST['new_pw2'];
							$data['change_pass'] = TRUE;
						}
					}
				}else{
					$data['errmsg'] = $this->error_set->get_message(ERROR_VALI,NULL);
					$data['shbn']   = $_POST['shbn'];
					$data['pw']     = '';
				}
			}
			
			// トップ画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	
	/*****************************************************************/
	/*                      ログインチェック                         */
	/*****************************************************************/
	function confirm($shbn, $pw)
	{
		try
		{
			log_message('debug',"shbn = $shbn");
			log_message('debug',"pw = $pw");
			// 初期化
			$this->load->model('sgmtb010');
			$result = FALSE;
			// 社番・パスワードチェック
			$res = $this->sgmtb010->check_passwd($shbn, $pw);
			log_message('debug',"check shbn ");
			log_message('debug', $res['shbn']);
			// セッションに情報を保持
			// 社番・氏名・部署？・メニュー表示区分等（未定）
			if($res['shbn'] === $shbn)
			{
				$session_data = array(
					'shbn' => $res['shbn'],
					'shinnm' => $res['shinnm'],
					'menuhyjikbn' => $res['menuhyjikbn']
				);
				$this->session->set_userdata($session_data);
				$result = TRUE;
			}
			return $result;
		}catch(Exception $e){
			// エラー表示
			$this->error_view($e);
		}
	}
	
	/**
	 * システムエラー発生時処理
	 */
	function error_view($e)
	{
		log_message('debug',"exception : $e");
		$this->load->library('error_set');
		$data = $this->init();
		$data['errmsg'] = $this->error_set->get_message(ERROR_SYSTEM,NULL);
		$this->display($data);
	}
	
	/**
	 * 変更ボタンクリック時の　社番のパスワードチェック
	 */
	function check_shbn_pw($shbn=NULL,$pw=NULL)
	{
		try
		{
			if($shbn !="" && $pw != ""){
				log_message('debug',"shbn = $shbn");
				log_message('debug',"pw = $pw");
				// 初期化
				$this->load->model('sgmtb010');
				$result = 'FALSE';
				// 社番・パスワードチェック
				$res = $this->sgmtb010->check_passwd($shbn, $pw);
				if(isset($res['shbn']) && $shbn == $res['shbn']){
					log_message('debug',"check shbn ");
					log_message('debug', $res['shbn']);
					$result = 'TRUE';
					echo $result;
				}else{
					$result = 'FLASE';
					echo $result;
				}
				
			}
		}catch(Exception $e){
			// エラー表示
			$this->error_view($e);
		}
		
	}
}

/* End of file login.php */
/* Location: ./application/controllers/login.php */
