<?php

class Division extends MY_Controller {

  /**
   *
   */
  function index(){
    try{
      log_message('debug',"========== controllers division index start ==========");
      // 初期化
      $this->load->library('division_manager');
      // 共通初期処理
      $data = $this->init();
      // メイン画面情報取得
      $data['contents'] = $this->division_manager->set_add_view();

      log_message('debug',"========== controllers division index end ==========");
      $this->display($data);
    }catch(Exception $e){
      //$this->error_view($e);
      $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION-INDEX'));
    }
  }

  /**
   * 共通で使用する表示処理
  *
  * @access public
  * @param  array $data 各種HTML作成時に必要な値
  * @return nothing
  */
  function display($data = NULL){
    try{
      log_message('debug',"========== controllers division display start ==========");
      // 引数チェック
      if(is_null($data)){
        log_message('debug',"argument data is NULL");
        throw new Exception("Error Processing Request", ERROR_SYSTEM);
      }
      $data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);   // ヘッダ部生成 title css image errmsg を使用
      $data['main']   = $this->load->view(MY_VIEW_DIVISION, $data, TRUE); // メインコンテンツ生成 contents を使用
      $data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);     // メニュー部生成 表示しない場合はNULL admin_flg を使用
      $data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);      // フッダ部生成 必ず指定する事
      log_message('debug',"========== controllers division display end ==========");
      // 表示処理
      //$this->load->view(MY_VIEW_LAYOUT, $data);
      $this->load->view(MY_VIEW_DIVISION,$data);
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
      $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION-DISPLAY'));
    }
  }

  /**
   * 登録タブ画面
   *
   * @access public
   * @param  nothing
   * @return nothing
   */
  function add(){
    try{
      $this->index();
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION-ADD'));
    }
  }

  /**
   * 更新タブ画面
   *
   * @access public
   * @param  nothing
   * @return nothing
   */
  function update(){
    try{
      log_message('debug',"========== controllers division update start ==========");
      // 初期化
      $this->load->library('division_manager');
      // 共通初期処理
      $data = $this->init();
      $data['btn_name'] = '更新'; // ボタン名
      $data['form'] = 'u_register';
      // メイン画面情報取得
      $data['contents'] = $this->division_manager->set_up_del_view(FALSE);
      log_message('debug',"========== controllers division update end ==========");
      $this->display($data);
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION-UPDATE'));
    }
  }

  function delete(){
    try{
      log_message('debug',"========== controllers division delete start ==========");
      // 初期化
      $this->load->library('division_manager');
      // 共通初期処理
      $data = $this->init();
      $data['btn_name'] = '削除'; // ボタン名
      $data['form'] = 'd_register';
      // メイン画面情報取得
      $data['contents'] = $this->division_manager->set_up_del_view(FALSE);
      log_message('debug',"========== controllers division delete end ==========");
      $this->display($data);
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION-DELETE'));
    }
  }

  /**
   * 共通項目初期化処理
   *
   * @access public
   * @param  nothing
   * @return array   $data 設定データ
   */
  function init(){
    try{
      log_message('debug',"========== controllers division init start ==========");
      $this->load->library('tab_manager');
      $this->load->library('common_manager');
      $common_data = $this->config->item(MY_DIVISION_CONF);

      // セッション情報取得
      $data['shbn']      = $this->session->userdata('shbn'); // 社番
      $data['admin_flg'] = $this->session->userdata('admin_flg'); // 管理者フラグ
      // ログイン者情報取得
      $heder_auth = $this->common_manager->get_auth_name($data['shbn']);
      $data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
      $data['ka_name'] = $heder_auth['ka_name'];
      $data['shinnm'] = $heder_auth['shinnm'];
      // 各種初期値取得
      $data['title']    = $common_data['title'];    // タイトルに表示する文字列
      $data['css']      = $common_data['css'];      // 個別CSSのアドレス
      $data['image']    = $common_data['image'];    // タイトルバナーのアドレス
      $data['errmsg']   = $common_data['errmsg'];   // エラーメッセージ
      $data['btn_name'] = $common_data['btn_name']; // ボタン名
      //$data['form']     = $common_data['form'];     // フォーム名
      $data['form']     = "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]."/division/".$common_data['form'];  // フォーム名

      // タブデータ取得
      $data['tab'] = $this->tab_manager->set_tab_all('division/');

      log_message('debug',"========== controllers division init end ==========");
      return $data;
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION-INIT'));
    }
  }

  /**
   * 個別初期化処理
   *
   * @access public
   * @param  nothing
   * @return array   $data 設定データ
   */
  function custom_init($data)
  {
    try{
      log_message('debug',"========== controllers division custom_init start ==========");
      $no_o = 0;
      $no_t = 0;
      // 一覧内容部分初期化
      for($i = 0; $i < MY_KBN_LINE_MAX; $i++)
      {
        $no_o = $i + MY_KBN_LINE_MAX; // 11～20
        $no_t = $i + (MY_KBN_LINE_MAX * 2); // 21～30
        if($i == (MY_KBN_LINE_MAX - 1))
        {
          $data['ichiran'.($i + 1)] = NULL;
        }else{
          $data['ichiran0'.($i + 1)] = NULL;
        }
        $data['ichiran'.($no_o + 1)] = NULL;
        $data['ichiran'.($no_t + 1)] = NULL;
      }
      $data['kbnid'] = NULL; // 区分ID
      $data['ktype'] = "000";

      log_message('debug',"========== controllers division custom_init end ==========");
      return $data;
    }catch(Exception $e){
      // エラー処理
      //$this->error_view($e);
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION_CUSTOM_INIT'));
    }
  }

  /**
   * 登録処理
   *
   * @access public
   * @param  nothing
   * @return nothing
   */
  function register(){
    try{
      log_message('debug',"========== controllers division register start ==========");
      $this->load->library('division_manager');
      $this->load->library('message_manager');

      $data = $this->init();

      if(isset($_POST['set']) AND ($_POST['gamennm'] != "" OR $_POST['koumoknm'] != ""))
      {
        // 登録データ生成
        $ins_data = $this->division_manager->insert_data_set($_POST);

        $this->division_manager->set_db_insert_data($ins_data['sgmtb030'],$ins_data['sgmtb031']);
        $data['errmsg'] = $this->message_manager->get_message(DIVISION_ADD_COMP);
      }
      // メイン画面情報取得
      $data['contents'] = $this->division_manager->set_add_view($_POST);
      $this->display($data);

      log_message('debug',"========== controllers division register end ==========");
    }catch(Exception $e){
      // エラー処理
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION_REGISTER'));
      $this->error_view($e);
      
    }
  }

  /**
   * 更新処理
   *
   * @access public
   * @param  nothing
   * @return nothing
   */
  function u_register(){
    try{
      log_message('debug',"========== controllers division u_register start ==========");
      $this->load->library('division_manager');
      $this->load->library('message_manager');

      $data = $this->init();
      $data['btn_name'] = '更新'; // ボタン名
      $data['form'] = 'u_register';

      // メイン画面情報取得
      // 画面名検索押下
      if(isset($_POST['g_search']) AND $_POST['gamennm'] != "")
      {
        $post = $_POST;

        // 初期化
        $post = $this->custom_init($post);
        // 検索実行
        $data['contents'] = $this->division_manager->set_up_del_view(TRUE,$post);
      }else if(isset($_POST['k_search']) AND $_POST['koumoknm'] != ""){
        // 項目名検索ボタン押下
        $kbnid = $_POST['koumoknm'];
       
        // 項目名検索
        $kbn_k_data = $this->division_manager->get_search_kbn_data($_POST);
        // 区分情報取得
        $sgmtb031_data = $this->division_manager->get_sgmtb031_data($kbnid);
        // 削除済みデータ判定
        if(!is_null($sgmtb031_data['deletedate']))
        {
          $kbn_k_data['k_delete'] = TRUE;
        }
        $kbn_k_data['kbnid'] = $sgmtb031_data['kbnid'];       // 区分ID設定
        $kbn_k_data['ktype'] = $sgmtb031_data['ktype'];       // 管理タイプ設定
        $kbn_k_data['koumoknm'] = $sgmtb031_data['koumoknm']; // 項目名
        // 表示HTML
        $data['contents'] = $this->division_manager->set_up_del_view(FALSE,$kbn_k_data);
      }else if(isset($_POST['set']) AND $_POST['gamennm'] != "" AND $_POST['kbnid'] !== ""){
        // 更新ボタン実行///////////////////////////
        // 更新データ生成
        $up_data = $this->division_manager->up_del_data_set($_POST);
        // DB更新処理
        $result = $this->division_manager->set_db_update_data($up_data['sgmtb030'],$up_data['sgmtb031']);

        // 結果判定
        if($result)
        {
          // 成功
          $data['errmsg'] = $this->message_manager->get_message(DIVISION_UPDATE_COMP);
          $data['contents'] = $this->division_manager->set_up_del_view(FALSE,$_POST);
        }else{
          // 失敗
          $data['errmsg'] = $this->message_manager->get_message(ERROR_DIVISION_UPDATE);
        }
      }else{
        // 初回
        $data['contents'] = $this->division_manager->set_up_del_view(FALSE);
      }
      $this->display($data);

      log_message('debug',"========== controllers division u_register end ==========");
    }catch(Exception $e){
      // エラー処理
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION_U_REGISTER'));
      $this->error_view($e);
    }
  }

  /**
   * 削除処理
   *
   * @access public
   * @param  nothing
   * @return nothing
   */
  function d_register(){
    try{
      log_message('debug',"========== controllers division d_register start ==========");
      $this->load->library('division_manager');
      $this->load->library('message_manager');

      $data = $this->init();
      $data['btn_name'] = '削除'; // ボタン名
      $data['form'] = 'd_register';

      // メイン画面情報取得
      // 画面名検索押下
      if(isset($_POST['g_search']) AND $_POST['gamennm'] != "")
      {
        $post = $_POST;
        // 初期化
        $post = $this->custom_init($post);
        $post['del'] = TRUE;
        // 検索実行
        $data['contents'] = $this->division_manager->set_up_del_view(TRUE,$post);
      }else if(isset($_POST['k_search']) AND $_POST['koumoknm'] != ""){
        // 項目名検索ボタン押下
        $kbnid = $_POST['koumoknm'];
        // 区分情報取得
        $sgmtb031_data = $this->division_manager->get_sgmtb031_data($kbnid);
        // 削除済みデータ判定
        if(is_null($sgmtb031_data['deletedate']))
        {
          // 削除済み
          // 項目名検索
          $kbn_k_data = $this->division_manager->get_search_kbn_data($_POST);
          $kbn_k_data['kbnid'] = $sgmtb031_data['kbnid'];       // 区分ID設定
          $kbn_k_data['ktype'] = $sgmtb031_data['ktype'];       // 管理タイプ設定
          $kbn_k_data['koumoknm'] = $sgmtb031_data['koumoknm']; // 項目名
          $kbn_k_data['del'] = TRUE;                            // 管理者タイプ disabled設定フラグ
        }else{
          // 未削除
          $kbn_k_data = $_POST;
          $kbn_k_data['del'] = TRUE;                            // 管理者タイプ disabled設定フラグ
        }
        // 表示HTML
        $data['contents'] = $this->division_manager->set_up_del_view(FALSE,$kbn_k_data);
      }else if(isset($_POST['set']) AND $_POST['gamennm'] != ""){
        // 削除ボタン押下
        $post = $_POST;
        // 管理タイプがシステムの場合、削除しない
        if(!isset($post['ktype']) AND $post['h_ktype'] === "1")
        {
          // エラーメッセージ設定
          $data['errmsg'] = $this->message_manager->get_message(ERROR_DIVISION_NOT_DELETE);
        }else{
          if($post['kbnid'] != "")
          {
            // 削除データ生成
            $del_data = $this->division_manager->up_del_data_set($post,TRUE);
            // DB論理削除処理
            $result = $this->division_manager->set_db_delete_data($del_data['sgmtb030'],$del_data['sgmtb031']);
            // 結果判定
            if($result)
            {
              // 成功
              $data['errmsg'] = $this->message_manager->get_message(DIVISION_DELETE_COMP);
            }else{
              // 失敗
              // エラーメッセージ設定
              $data['errmsg'] = $this->message_manager->get_message(ERROR_DIVISION_DELETE);
            }
          }
        }
        // 管理者タイプ表示設定
        $post['del'] = TRUE;
        // 表示HTML
        $data['contents'] = $this->division_manager->set_up_del_view(FALSE,$post);
      }else{
        // 初回
        $data['contents'] = $this->division_manager->set_up_del_view(FALSE);
      }
      $this->display($data);

      log_message('debug',"========== controllers division d_register end ==========");
    }catch(Exception $e){
      // エラー処理
       $this->load->view('/parts/error/error.php',array('errcode' => 'DIVISION_D_REGISTER'));
      $this->error_view($e->getMessage());
    }
  }

  /**
   * エラー処理
   *
   * @access public
   * @param  nothing
   * @return nothing
   */
  function error_view($error_code = NULL){
    try{
      // エラーメッセージ取得
      $this->load->library('message_manager');
      if(!is_null($error_code)){
        // エラー文言取得
        $data['errmsg'] = $this->message_manager->get_message($error_code);
      }else{
        // エラー文言取得
        $data['errmsg'] = $this->message_manager->get_message(ERROR_SYSTEM);
      }
    }catch(Exception $e){
      // エラー処理
      
      $this->error_view($e);
    }
  }


}

/* End of file Division.php */
/* Location: ./application/controllers/division.php */
