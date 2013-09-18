<?php

class Checker_search_group extends MY_Controller {
	/**
	 * グループ検索画面(ポップアップ)
	 *
	 * @access	private
	 * @param	none
	 * @return	none
	 */
	function index($edbn = NULL)
	{
		try
		{
			log_message("debug","================ checker_search_group start ==================");
			$this->load->library('table_manager');
			// 検索者の社番を取得
			$shbn = $this->session->userdata('shbn');
			// 枝番をセッションに保存（更新用）
			if(!is_null($edbn)){
				$this->session->set_userdata(array('edbn' => $edbn));
			}
			// 初期化
			$data = $this->init();
			// 戻るボタン押下判定
			if(isset($_POST['set']))
			{
				$data['search_g_name_list'] = NULL;
				$session_data = array(
					'group_search_shbn' => $_POST['group_list']
				);
				$this->session->set_userdata($session_data);
				// 確認者選択画面へ遷移
				$data['reload_flg'] = true;
				redirect($this->config->item('base_url') . 'index.php/select_checker/index');
				log_message('debug',"====== return ======");
			}else{
				$data['search_g_name_list'] = $this->table_manager->search_group_set_list_table(); // グループ名リスト
			}
			// 画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー表示
			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-GROUP-INDEX'));
		}	
	}
	
	/**
	 * グループ検索(初期化)
	 *
	 * @access	private
	 * @param	none
	 * @return	array
	 */
	function init()
	{
		try
		{
			// 検索者の社番を取得
			$common_data = $this->config->item('s_busyo_search_group');
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // 管理者フラグ取得
			// ログイン者情報取得
			$this->load->library('common_manager');
			$heder_auth = $this->common_manager->get_auth_name($this->session->userdata('shbn'));
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			// 初期共通項目情報
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-GROUP-INIT'));
		}
	}
	
	/**
	 * グループ検索(表示)
	 *
	 * @access	private
	 * @param	array
	 * @return	none
	 */
	function display($data)
	{
		try
		{
			// 表示処理
			$this->load->view(MY_VIEW_CHECKER_SEARCH_GROUP, $data);
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-GROUP-DISPALY'));
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
	
}
/* End of file login.php */
/* Location: ./application/controllers/login.php */
