<?php

class Project_item extends MY_Controller{

  /***
   * @param mode TRUE：表有り　FALSE：表無し　
   */
  function index($conf_name = MY_PROJECT_DATA)
  {
    try
    {
      log_message('debug',"========== Project_item index start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      // モデル呼び出し
      $this->load->model('sgmtb080'); //

      // 初期表示
      $project_data = $this->sgmtb080->get_project_data($data["page"]);   //データ取得
      $data["page_tabel"] = $this->_get_page_button($data["page"]);            //ボタン
      $data["list_tabel"] = $this->_get_page_list($project_data); //表示

      // Main表示情報取得
      $this->display($data); // 画面表示処理
      log_message('debug',"========== Project_item index end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-index'));
      $this->error_view($e);
    }
  }

  /**
   * 登録,更新,削除(内部的には、all削除、登録)
   * @param
   */
  function register($conf_name = MY_PROJECT_DATA)
  {
    try
    {
      log_message('debug',"========== Project_item register start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

            // モデル呼び出し
      $this->load->model('sgmtb080'); //
      $this->load->library('project_item_manager');
      $this->load->library('message_manager');

      $page = $data['page'];

      //登録処理
      if(isset($_POST['set']))
      {
        $regist_data = array();

        // 登録データ生成
        $regist_data = $this->project_item_manager->insert_data_set($_POST);
        
        //登録処理
        if($regist_data) {
          $res = $this->project_item_manager->set_db_insert_data($regist_data);
          $page_max = ceil(count($regist_data) / MY_PROJECT_MAX_VIEW);
          if($page > $page_max) $page = $page_max;  //表示ページ調整
        }
      }

      // 表示
      $project_data = $this->sgmtb080->get_project_data($page);   //データ取得
      $data["page_tabel"] = $this->_get_page_button($page);            //ボタン
      $data["list_tabel"] = $this->_get_page_list($project_data); //表示

      // Main表示情報取得
      $this->display($data); // 画面表示処理
      log_message('debug',"========== Project_item register end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-index'));
      //$this->error_view($e);
    }
  }

  /**
   * 行追加
   * @param unknown_type $conf_name
   */
  function ajax_add_row()
  {
    try
    {
      log_message('debug',"========== Project_item add_row start ==========");
      //$data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      //挿入数
      $add_num = (int)$this->input->post('add_num');
      if(!$add_num) $add_num = 1;
      if($add_num >= 10) $add_num = 9;

      //追加位置
      $view_no = (int)$this->input->post('view_no');

      // 行追加
      $add_data = $this->_get_list_add_data($add_num, $view_no);

      // 追加表示
      header("Content-type: text/html; charset=utf-8");
      echo $add_data;
      log_message('debug',"========== Project_item add_row end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-add_row'));
      //$this->error_view($e);
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
			log_message('debug',"========== Project_item init start ==========");
			log_message('debug',"\$conf_name = $conf_name");
			// 初期化
			$common_data = $this->config->item($conf_name);
			$this->load->library('table_set');
			$this->load->library('common_manager');
			// セッション情報から社番を取得
			$data['shbn'] = $this->session->userdata('shbn');
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($data['shbn']);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			// セッション情報から管理者フラグを取得
			$data['admin_flg'] = $this->session->userdata('admin_flg');

			// 初期共通項目情報
			$data['title']    = $common_data['title']; // タイトルに表示する文字列
			$data['css']      = $common_data['css']; // 個別CSSのアドレス
			$data['image']    = $common_data['image']; // タイトルバナーのアドレス
			$data['errmsg']   = $common_data['errmsg']; // エラーメッセージ
			$data['btn_name'] = $common_data['btn_name']; // ボタン名
			$data['app_url']  = "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]; // 実行アプリurl
			$data['form']     = "/project_item/".$common_data['form'];  // フォームアクション
			$data['form_name']   = "project_item_form";  // フォーム名

			//ページ情報
			$page = (int)$this->input->post('page');
			if( $this->input->post('prev') ) $page = (int)$this->input->post('prev_page');
			if( $this->input->post('next') ) $page = (int)$this->input->post('next_page');
			if(!$page || $page < 0) $page=1;

			$data["page"] = $page;  //現在のページ数
			$data["max_view"] = MY_PROJECT_MAX_VIEW;  //一ページの表示数
			$data["start_no"] = MY_PROJECT_MAX_VIEW * ($page-1);  //データ番号(javascriptでの採番に使用)

			log_message('debug',"========== Project_item init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-init'));
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
			log_message('debug',"========== Project_item display start ==========");
			// ヘッダ部生成 title css image errmsg を使用
			$data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']   = $this->load->view(MY_VIEW_PROJECT_DATA, $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			// フッダ部生成 必ず指定する事
			$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			// 表示処理
			log_message('debug',"========== Project_item display end ==========");
			//$this->load->view(MY_VIEW_LAYOUT, $data);
			$this->load->view(MY_VIEW_PROJECT_DATA, $data);
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-display'));
//			$this->error_view($e);
		}
	}

	/**
	 * ページングボタンの作成を取得
	 *
	 * @access private
	 * @param $page int
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_page_button($page = 1)
	{
		try
		{
			log_message('debug',"========== Project_item _get_page_button start ==========");
			// 初期化
			$this->load->library('project_item_manager');
			$table_data = NULL;
			$table_data = $this->project_item_manager->set_project_data_page($page);

			log_message('debug',"========== Project_item _get_page_button end ==========");
			return $table_data;
		}catch(Exception $e){
			$this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-_get_page_button'));
			//エラー処理
		}
	}

	/**
	 * 表の作成
	 *
	 * @access private
	 * @param $project_data ARRAY
	 * @param $mode TRUE=登録　FALSE=更新、削除
	 * @return string $table_data HTML-STRING文字列
	 */
  private function _get_page_list($project_data)
  {
    try
    {
      log_message('debug',"========== Project_item _get_page_list start ==========");
      // 初期化
      $this->load->library('project_item_manager');
      $table_data = "";

      if(!$project_data) {
        //データなし
        $table_data = $this->project_item_manager->get_project_data_list();
      } else {
        //データあり
        foreach($project_data as $key => $val) {
          $table_data .= $this->project_item_manager->get_project_data_list( $val["view_no"], $val['dbnrinm'], $val['dbnricd'], $val['itemnm'], $val['itemcd'], $val['view_flg']);
        }
      }

      log_message('debug',"========== Project_item _get_page_list end ==========");
      return $table_data;
    }catch(Exception $e){
      //エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'Project_item-_get_page_list'));
    }
	}

  /**
   * リストの行追加
   *
   * @access private
   * @param $add_point int
   * @param $mode TRUE=登録　FALSE=更新、削除
   * @return string $table_data HTML-STRING文字列
   */
  private function _get_list_add_data($add_num, $view_no)
  {
    try
    {
      log_message('debug',"========== Project_item _get_list_add_data start ==========");

      // 初期化
      $this->load->library('project_item_manager');
      $table_data="";

      //追加行取得
      for($i=0; $i < $add_num; $i++) {
        $table_data .= $this->project_item_manager->get_project_data_list();
      }

      log_message('debug',"========== Project_item _get_list_add_data end ==========");
      return $table_data;
    }catch(Exception $e){
      //エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'Project_item_get_list_add_data'));
    }
  }



  /**
   * バリデーションチェック
   *
   * @access	public
   * @param	string $post POSTデータ
   * @return	boolean TRUE:成功 FALSE:エラー
   */
  function validate_check($post)
	{
		try
		{
			$v_result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			$config = $this->config->item('validation_rules_project_item');
			// バリデーションルールセット
			//$this->form_validation->set_rules($config);
			// バリデーション結果チェック
			foreach($post['add_line'] as $line)
			{
				if(is_numeric($line) OR $line == "")
				{
					// 成功
					$v_result = TRUE;
					log_message('debug',"========== delete_user validation success ==========");
				}else{
					// 失敗
					$v_result = FALSE;
					throw new Exception(ERROR_VALI);
					log_message('debug',"========== delete_user validation error ==========");
				}
			}
			return $v_result;

		 }catch(Exception $e){
			// エラー処理
			log_message('debug',"message = ".$e->getMessage());
			$v_result = $this->error_view($e->getMessage(),"update",NULL);
			return $v_result;
		}
	}

	 /**
	 * エラー発生時処理
	 * @access	public
	 * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
	 * @return	none
	 */
	function error_view($e,$type="update",$item=NULL)
	{
		log_message('debug',"exception : $e");
		$this->load->library('message_manager');
		// POSTデータ引継ぎ
		$data = $_POST;
		if($e == ERROR_USER_SEARCH)
		{
			$data = $this->init($type);
		}

		return $this->message_manager->get_message($e,$item);
	}

	/**
   * アイテム削除
   *
   */
   function del_item()
  {
    try
    {
      //データ取得
      $view_no = $this->input->post('view_no');

      $this->load->model('sgmtb080'); 

      //削除処理
      if($this->sgmtb080->delete_project_item($view_no)){
      	echo $view_no;
      }else{
      	echo "error";
      }

    }catch(Exception $e){
      //エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'Project_item_del_item'));
    }
  }

}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
