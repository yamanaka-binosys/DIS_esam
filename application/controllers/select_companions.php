<?php

class Select_companions extends MY_Controller {
	/**
	 * 同行者選択画面
	 *
	 * @access private
	 * @param  none
	 * @return none
	 */
	function index()
	{
		try
		{
			// 初期化
			$data = $this->init();
			// 画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_companions-index'));
			//$this->error_view($e);
		}
	}
	
	/**
	 * 同行者選択(初期化)
	 *
	 * @access private
	 * @param  none
	 * @return array
	 */
	function init()
	{
		try
		{
			$this->load->library('table_manager_another');
			$common_data = $this->config->item('s_search_companions');
			$this->load->model('srwtb022');
			$this->load->library('common_manager');
			$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
			$checker_shbn = $this->session->userdata('compani_shbn'); // セッション情報から確認者の社番を取得

		// 同行者登録
			if($checker_shbn)
			{
				// 枝番取得
				$edbn = $this->srwtb022->get_edbn_data($shbn);
				$c_result = $this->srwtb022->insert_checker_data($shbn,$edbn,$checker_shbn);
				if($c_result)
				{
					$this->session->unset_userdata('compani_shbn');
				}else{
					throw new Exception("Error Processing Request", ERROR_SYSTEM);
				}
			}
			log_message('debug',"shbn=".$shbn);
			// 結果判定
			$data['c_doko_table'] = $this->table_manager_another->doko_set_table($shbn); // 同行者テーブル

			$data['admin_flg'] = $this->session->userdata('admin_flg'); // 管理者フラグ取得
			// 初期共通項目情報
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($shbn);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			// ユーザー管理画面独自項目
			
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_companions-init'));
			//	$this->error_view($e);
		}
	}
	
	/**
	 * 確認者選択(表示)
	 *
	 * @access private
	 * @param  array
	 * @return none
	 */
	function display($data)
	{
		try
		{
			// ヘッダ部生成 title css image errmsg を使用
			$data['header']    = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']      = $this->load->view(MY_VIEW_COMPANIONS, $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']      = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			log_message('debug',"===================== select_checker =========================");
			// フッダ部生成 必ず指定する事
			$data['footer']    = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			// 表示処理
			$this->load->view(MY_VIEW_LAYOUT, $data);
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_companions-display'));
			//$this->error_view($e);
		}
	}

	/**
	 * 確認者選択(戻る)
	 *
	 * @access private
	 * @param  array post POSTデータ
	 * @return none
	 */
	function select_type()
	{
		if(isset($_POST['kshbn']))
		{
			$checker_shbn = $_POST['kshbn'];
			$r_shbn = $checker_shbn;
		}
		
			foreach($grp_shbn as $g_data)
			{
				foreach($g_data as $gshbn)
				{
					$r_shbn[] = $gshbn['shbn'];
				}
			}
		// 確認者社番をセッションに保存
		$session_data = array('checker_shbn' => $r_shbn);
		$this->session->set_userdata($session_data);
		// 呼び出し元画面へ遷移
		//header("Location: ".$this->config->item('base_url')."index.php//index");				

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
	
}
/* End of file login.php */
/* Location: ./application/controllers/login.php */
