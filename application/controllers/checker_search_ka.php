<?php

class Checker_search_ka extends MY_Controller {
	/**
	 * 課検索画面
	 *
	 * @access	private
	 * @param	none
	 * @return	none
	 */
	function index($edbn = NULL)
	{
		try
		{
			log_message("debug","================ checker_search_unit start ==================");
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
				$session_data = array(
					'ka_search_shbn' => $_POST['busyo_list']
				);
				$this->session->set_userdata($session_data);
				// 確認者選択画面へ遷移
				$data['reload_flg'] = true;
				redirect($this->config->item('base_url') . 'index.php/select_checker/index');
				log_message('debug',"====== return ======");
			}else{
				if(isset($_POST['b_search']) OR isset($_POST['n_search']))
				{
					// 検索ボタン処理
					$data = $this->search($shbn,$data);
					$post = $_POST;
					$post['name'] = NULL;
					$bu = substr($_POST['bucd'],5);
					$ka = substr($_POST['kacd'],10);
					$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$post['honbucd'],substr($_POST['bucd'],5),"5",substr($_POST['kacd'],10));     // 部署検索
				}else{
					$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,NULL,NULL,"5");     // 部署検索
					$data['search_b_name_list']        = $this->table_manager->search_unit_set_list_table_($shbn,$_POST); // 部署名リスト
				}
			}
			// 画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー表示
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-UNIT-INDEX'));
		}	
	}
	
	/**
	 * 確認者検索(初期化)
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
			$common_data = $this->config->item('s_busyo_search_unit');
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
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-UNIT-INIT'));
		}
	}
	
	/**
	 * 確認者検索(表示)
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
			$this->load->view(MY_VIEW_CHECKER_SEARCH_KA, $data);
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-UNIT-DISPLAY'));
			//$this->error_view($e);
			
		}
	}

	/**
	 * 確認者検索(検索)
	 *
	 * @access	private
	 * @param	string $shbn 社番 
	 * @param	string $shbn 設定データ
	 * @return	string $data 設定データ
	 */
	function search($shbn,$data = NULL)
	{
		try
		{
			// 呼び出し元画面への送信処理
			if(isset($_POST['b_search']))
			{
				$post = $_POST;
			}
			$post['name'] = NULL;
			
			$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,$post,NULL,NULL,"5"); // 部署検索
			$data['search_b_name_list']        = $this->table_manager->search_unit_set_list_table_($shbn,$post);    // 部署名リスト
			return $data;
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-UNIT-SEARCH'));
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
   *  ドロップダウンリスト変更
   */
  function select_item_list()
  {
    try
    {
      log_message('debug',"========== checker_search_conf select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val'];
      $data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,NULL,"5"); // 部署検索
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
     $this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-KA-SELECT-ITEM-LIST'));
      //$this->error_view($e);
    }
  }
	
  /**
   *  ドロップダウンリスト変更
   */
  function select_unit_item_list()
  {
    try
    {
      log_message('debug',"========== checker_search_conf select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val']['val'];
      $bu = substr($_POST['selected_val']['val2'],5);

      $data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,$bu,"5"); // 部署検索
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
     $this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-KA-SELECT-UNIT-ITEM-LIST'));
      //$this->error_view($e);
    }
  }
}
/* End of file login.php */
/* Location: ./application/controllers/login.php */
