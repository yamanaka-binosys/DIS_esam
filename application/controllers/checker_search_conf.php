<?php

class Checker_search_conf extends MY_Controller {
	/**
	 * 確認者検索画面(ポップアップ)
	 *
	 * @access	private
	 * @param	string $edbn 選択画面更新判定用枝番
	 * @return	none
	 */
	function index($edbn = NULL)
	{
		try
		{
			log_message("debug","================ checker_search_conf start ==================");
			$this->load->library('table_manager');
			// 検索者の社番を取得
			$shbn = $this->session->userdata('shbn');
			// 枝番をセッションに保存（更新用）
			if(!is_null($edbn)){
				$this->session->set_userdata(array('edbn' => $edbn));
			}
					log_message('debug',"edbn=".$edbn);
			// 初期化
			$post="";
			$honbu=NULL;
			$bu=NULL;
			if(isset($_POST) && $_POST != array()){
				$post = $_POST;
				$honbu = $_POST['honbucd'];
				$bu = substr($_POST['bucd'],5);
				$unit = substr($_POST['kacd'],10);
			}
			$data = $this->init($post);
			
			// 戻るボタン押下判定
			if(isset($_POST['set']))
			{
				if(isset($_POST['checker_list'])){
					$session_data = array(
					'checker_search_shbn' => $_POST['checker_list']
					);
					$this->session->set_userdata($session_data);
				}
					log_message('debug',"edbn=".$edbn);
				// 確認者選択画面へ遷移
				$data['reload_flg'] = true;
				
				redirect($this->config->item('base_url') . 'index.php/select_checker/index');
				log_message('debug',"====== return ======");
			}else{
			log_message("debug","================ checker_search_conf post check ==================");
				if(isset($_POST['b_search']) OR isset($_POST['n_search']))
				{
				log_message("debug","================ checker_search_conf btn ==================");
				// 検索ボタン処理
					$data = $this->search($shbn,$data,$honbu,$bu);
					$data['search_result_busyo_table'] = $this->table_manager->search_conf_set_b_table($shbn,$post,$honbu,$bu,$unit);     // 部署検索
				}else{
				log_message("debug","================ checker_search_conf load ==================");
					$data['search_result_busyo_table'] = $this->table_manager->search_conf_set_b_table($shbn,$post,$honbu,$bu);     // 部署検索
					$data['search_result_name_table']  = $this->table_manager->search_conf_set_n_table($_POST);    // 氏名検索
					$data['search_name_list']        = $this->table_manager->search_conf_set_list_table($shbn,$_POST); // 確認者氏名リスト
				}
			}
			
				log_message("debug","================ checker_search_conf end ==================");
			// 画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー表示
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-CONF-INDEX'));
		}	
	}
	
	/**
	 * 確認者検索(初期化)
	 *
	 * @access	private
	 * @param	none
	 * @return	array
	 */
	function init($post=NULL)
	{
		try
		{
			// 検索者の社番を取得
			$common_data = $this->config->item('s_checker_search_conf');
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
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-CONF-INIT'));
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
			log_message('debug',"============= checker_search_conf display start ====================");
			log_message('debug',"\"data = " . $data['admin_flg']);
			// 表示処理
			$this->load->view(MY_VIEW_CHECKER_SEARCH_CONF, $data);
			log_message('debug',"============= checker_search_conf display end ====================");
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-CONF-DISPLAY'));
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
	function search($shbn,$data = NULL,$honbu=NULL,$bu=NULL)
	{
		try
		{
			$honbu="";
			$bu="";
			// 呼び出し元画面への送信処理
			if(isset($_POST['b_search']))
			{
				$post = $_POST;
				//$honbu = $post['honbucd'];
				//echo $honbu." ";
				//$bu = substr($post['bucd'],5);
				//echo $bu;
				// 名前検索無効
				$post['name'] = NULL;
			}else{
				$post = $_POST;
				// 部署検索無効
				$post['honbucd'] = NULL;
				$post['bucd'] = NULL;
				$post['kacd'] = NULL;
			}
			log_message('debug',"============= checker_search_conf search start ====================");
				$data['search_result_busyo_table'] = $this->table_manager->search_conf_set_b_table($shbn,$post,$honbu,$bu); // 部署検索
				$data['search_result_name_table']  = $this->table_manager->search_conf_set_n_table($post);       // 氏名検索
				$data['search_name_list']        = $this->table_manager->search_conf_set_list_table($shbn,$post);    // 確認者氏名リスト
			log_message('debug',"============= checker_search_conf search end ====================");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-CONF-SEARCH'));
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
      $data['search_result_busyo_table'] = $this->table_manager->search_conf_set_b_table($shbn,NULL,$honbu); // 部署検索
      
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
     // $this->error_view($e);
      $this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-CONF-SELECT-ITEM-LIST'));
    }
  }
    /**
   *  ドロップダウンリスト変更
   */
  function select_item_unit_list()
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
      
      $data['search_result_busyo_table'] = $this->table_manager->search_conf_set_b_table($shbn,NULL,$honbu,$bu); // 部署検索
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
     // $this->error_view($e);
      $this->load->view('/parts/error/error.php',array('errcode' => 'CHECKER-SEARCH-CONF-SELECT-ITEM-LIST'));
    }
  }

	
}
/* End of file login.php */
/* Location: ./application/controllers/login.php */
