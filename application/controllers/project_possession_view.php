<?php

class Project_possession_view extends MY_Controller {
  function index($conf_name = MY_PROJECT_POSSESSION_VIEW)
  {
    try
    {
      log_message('debug',"========== project_possession_view index start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      //販売店名取得
      $aitesk = $this->input->post('aiteskcd');
      if( isset($aitesk) ) {
        $aitesk = explode("|",$aitesk);
        if(isset($aitesk[0])) $data["view_aiteskcd"] = $aitesk[0];	//相手先コード
        if(isset($aitesk[1])) $data["view_aitesk_name"] = $aitesk[1];	//相手先名(販売店名)
      }


      // モデル呼び出し
      $this->load->model('sgmtb080'); // 企画アイテム情報
      $this->load->model('srktb070'); // 企画獲得情報

      //企画情報アイテムデータ(大分類情報取得, アイテム情報取得)
      $data["kiaku_data"] = "";
      //$data["kiaku_data"] = $this->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, view_no", "", "view_no");

      //登録データ取得
      $data["kakutoku_data"] = "";
      //$data["kakutoku_data"] = $this->srktb070->get_kikaku_kakutoku_data("*", "shbn='{$data['shbn']}'", "year,month");

      //追加列の番号
      if( $data["kakutoku_data"] ) {
        $data["add_view_no"] = count($data["kakutoku_data"]) + 1;
      } else {
        $view_no_max = $this->sgmtb080->get_project_view_no("");  //view_no MAX値取得
        $data["add_view_no"] = $view_no_max + 1;  //次の追加列値
      }

      //表示用データ作成
      $this->_make_view_data($data);

      // Main表示情報取得
      $this->_display($data); // 画面表示処理
      log_message('debug',"========== project_possession_view index end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-index'));
    	//$this->error_view($e);
    }
  }

  /**
   * 表示(検索処理)
   * @param string $conf_name
   */
  function search($conf_name = MY_PROJECT_POSSESSION_VIEW)
  {
  	try
  	{
  		log_message('debug',"========== project_possession_view index start ==========");
  		$data = $this->init($conf_name); // ヘッダデータ等初期情報取得

  		//送信データ取得
  		$set = $this->input->post('set');
  		//if( $set == 1 ) {
  		$data["aiteskcd"] = $this->input->post('aiteskcd');				//相手先コード
  		$data["aitesk_name"] = $this->input->post('aitesk_name');	//相手先名(販売店名)
  		//}
  		$data["year"] = $this->input->post('year');
  		$data["month"] = $this->input->post('month');
  		$data["view_aiteskcd"] = $this->input->post('view_aiteskcd');				//表示用相手先コード
  		$data["view_aitesk_name"] = $this->input->post('view_aitesk_name');	//表示用相手先名(販売店名)
  		$data["view_year"] = $this->input->post('view_year');
  		$data["view_month"] = $this->input->post('view_month');

  		// モデル呼び出し
  		$this->load->model('sgmtb080'); // 企画アイテム情報
  		$this->load->model('srktb070'); // 企画獲得情報

  		//企画情報アイテムデータ(大分類情報取得, アイテム情報取得)
  		$data["kiaku_data"] = $this->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, view_no", "", "view_no");

  		//登録データ取得
  		$data["kakutoku_data"] = "";
  		if($set && $data["aiteskcd"] && $data["year"] && $data["month"] ) {
  			$data["kakutoku_data"] = $this->srktb070->get_kikaku_kakutoku_data("*", "shbn='{$data['shbn']}' AND aiteskcd='{$data['aiteskcd']}' AND year='{$data["year"]}' AND month='{$data["month"]}' ", "year,month");
  			//表示月のデータがない場合、直近月の入力データを取得
  			if(!$data["kakutoku_data"]) {
  				$data["output"] = "新規登録";
  				//直近の入力データを取得
  				$last_regist_ym = $this->srktb070->get_kikaku_kakutoku_data("year, month", "shbn='{$data['shbn']}' AND aiteskcd='{$data['aiteskcd']}'", "year DESC,month DESC");
  				if($last_regist_ym) {
  					$data["kakutoku_data"] = $this->srktb070->get_kikaku_kakutoku_data("*", "shbn='{$data['shbn']}' AND aiteskcd='{$data['aiteskcd']}' AND year='{$last_regist_ym[0]["year"]}' AND month='{$last_regist_ym[0]["month"]}' ", "year DESC,month DESC");
  					//$data["output"] .= "　".$data["kakutoku_data"][0]["year"]."年".$data["kakutoku_data"][0]["month"]."月";
  				}
  			}
  		}

  		//追加列の番号
  		if( $data["kakutoku_data"] ) {
  			$data["add_view_no"] = count($data["kakutoku_data"]) + 1;
  		} else {
  			$view_no_max = $this->sgmtb080->get_project_view_no("");  //view_no MAX値取得
  			$data["add_view_no"] = $view_no_max + 1;  //次の追加列値
  		}

  		//表示用データ作成
  		$this->_make_view_data($data);

  		// Main表示情報取得
  		$this->_display($data); // 画面表示処理
  		log_message('debug',"========== project_possession_view index end ==========");
  	}catch(Exception $e){
  		// エラー処理
  		$this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-search'));
  		//$this->error_view($e);
  	}
  }

  /**
  * 登録,更新
  * @param
  */
  function register($conf_name = MY_PROJECT_POSSESSION_VIEW)
  {
    try
    {
      log_message('debug',"========== project_possession_view register start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      // モデル呼び出し
      $this->load->model('sgmtb080'); // 企画アイテム情報
      $this->load->model('srktb070'); // 企画獲得情報
      $this->load->library('project_possession_manager');
      $this->load->library('message_manager');

      $data["aiteskcd"] = $this->input->post('aiteskcd');				//相手先コード
      $data["aitesk_name"] = $this->input->post('aitesk_name');	//相手先名(販売店名)
      $data["year"] = $this->input->post('year');
      $data["month"] = $this->input->post('month');
      $data["view_aiteskcd"] = $this->input->post('view_aiteskcd');				//表示用相手先コード
      $data["view_aitesk_name"] = $this->input->post('view_aitesk_name');	//表示用相手先名(販売店名)
      $data["view_year"] = $this->input->post('view_year');
      $data["view_month"] = $this->input->post('view_month');

      //登録処理
      if(isset($_POST['set']) && isset($_POST['daibunrui_list']) && $data['aiteskcd'] && $data['year'] && $data['month'] )
      {
        $regist_data = $_POST;
        $regist_data['shbn'] = $data['shbn'];  //社員区分(key)(登録者情報)

        //登録,更新 処理
        $res = $this->project_possession_manager->set_db_regist_data($regist_data);
      }

      /////////////////////////////////////////////////
      //  表示
      //
      //企画情報アイテムデータ(大分類情報取得, アイテム情報取得)
      $data["kiaku_data"] = $this->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, view_no", "", "view_no");

      //登録データ取得
      $data["kakutoku_data"] = $this->srktb070->get_kikaku_kakutoku_data("*", "shbn='{$data['shbn']}' AND aiteskcd='{$data['aiteskcd']}' AND year='{$data["year"]}' AND month='{$data["month"]}' ", "year,month");
      if(!$data["kakutoku_data"] || !isset($_POST['daibunrui_list'])) {
      	$data["kiaku_data"] = "";	//登録をかけて登録データが無かった場合何も表示しない
      } else {
      	$data["output"] = "登録が完了しました。";
      }

      //追加列の番号
      if( $data["kakutoku_data"] ) {
        $data["add_view_no"] = count($data["kakutoku_data"]) + 1;
      } else {
        $view_no_max = $this->sgmtb080->get_project_view_no("");  //view_no MAX値取得
        $data["add_view_no"] = $view_no_max + 1;  //次の追加列値
      }

      //表示用データ作成
      $this->_make_view_data($data);

      // Main表示情報取得
      $this->_display($data); // 画面表示処理
      log_message('debug',"========== project_possession_view register end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-register'));
      //$this->error_view($e);
    }
  }

  /**
   * 検索（指定年月日で検索）
   * @param unknown_type $conf_name
   */
  function show_target_list($conf_name = MY_PROJECT_POSSESSION_VIEW)
  {
    try
    {
      log_message('debug',"========== project_possession_view index start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      // モデル呼び出し
      $this->load->model('sgmtb080'); // 企画アイテム情報
      $this->load->model('srktb070'); // 企画獲得情報

      //企画情報アイテムデータ(大分類情報取得, アイテム情報取得)
      $data["kiaku_data"] = $this->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, view_no", "", "view_no");

      //指定登録データ取得
      $year = (int)$_POST['view_year'];
      $month = (int)$_POST['view_month'];
      $data["view_aitesk_name"] = $_POST['view_aitesk_name'];
      $data["view_aiteskcd"] = $_POST['view_aiteskcd'];
      $data["kakutoku_data"] = $this->srktb070->get_kikaku_kakutoku_data("*", "shbn='{$data['shbn']}' AND aiteskcd='{$data['view_aiteskcd']}' AND year='{$year}' AND month='{$month}'", "year,month");

      $data["view_year"] = $year;
      $data["view_month"] = $month;
      $data["table_data"] = "";
      $data["disabled"] = 'disabled="disabled"';

      //登録データが存在している場合のみ表示
      if( $data["kakutoku_data"] ) {
      	//表示用データ作成
        $this->_make_view_data($data, true);  //
      }

      // Main表示情報取得
      $this->_display($data); // 画面表示処理
      log_message('debug',"========== project_possession_view index end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-register'));
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
      log_message('debug',"========== data_memo init start ==========");
      log_message('debug',"\$conf_name = $conf_name");

      // 初期化
      $common_data = $this->config->item($conf_name);
      $this->load->library('project_possession_manager');
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
      $data['title']       = $common_data['title']; // タイトルに表示する文字列
      $data['css']         = $common_data['css']; // 個別CSSのアドレス
      $data['image']       = $common_data['image']; // タイトルバナーのアドレス
      $data['errmsg']      = $common_data['errmsg']; // エラーメッセージ
      $data['btn_name']    = $common_data['btn_name']; // ボタン名
      $data['app_url']     = "http://".$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"]; // 実行アプリurl
      $data['form']        = "/project_possession_view/".$common_data['form'];  // フォームアクション
      $data['form_name']   = "project_possession_form";  // フォーム名
      $data["disabled"] = "";  //閲覧状態

      $data["aitesk_name"] = "";	//販売店名
      $data["aiteskcd"] = "";			//販売店コード
      $data["year"] = date("Y");
      $data["month"] = date("m");
      $data["view_aitesk_name"] = "";	//販売店名(表示用)
      $data["view_aiteskcd"] = "";		//販売店コード(表示用)
      $data["view_year"] = date("Y");
      $data["view_month"] = date("m");
      $data["output"] = "";			//メッセージ

      //コンボボックス年セット（12～22）
      $start_year = 2012;
      $year_cnt = 10;
      $data["base_year"] = array();
      $data["base_month"] = array(1,2,3,4,5,6,7,8,9,10,11,12);
      for($i=0; $i <= $year_cnt; $i++) {
      	$data["base_year"][$i] = $start_year + $i;
      }

      //keep_val session チェックセット
      $data['keep_val'] = "";
      if($this->session->userdata('keep_val')) {
      	$data['keep_val'] = $this->session->userdata('keep_val');
      	$keep_val = explode(":", $data['keep_val']);
      	$key = explode(",", $keep_val[0]);
      	$val = explode(",", $keep_val[1]);
      	foreach($key as $k=>$d) {
      		$data[$d] = $val[$k];
      	}
      	$_SESSION["keep_val"] = "";	//初期化
      	$this->session->set_userdata(array('keep_val' => ""));	//初期化
      }

      log_message('debug',"========== data_memo init end ==========");
      return $data;
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-init'));
      //$this->error_view($e);
    }
  }

  /**
   * (AJAX)企画アイテム ドロップダウンリスト表示
   */
  function ajax_select_item_list($conf_name = MY_PROJECT_POSSESSION_VIEW)
  {
    try
    {
      log_message('debug',"========== project_possession_view ajax_select_item_list start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      // モデル呼び出し
      $this->load->model('sgmtb080'); // ユーザー情報

      //企画情報アイテムデータ(大分類情報取得, アイテム情報取得)
      $DbnriCd = $this->input->post('selected_val');
      $data["kiaku_data"] = $this->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, view_no", "DbnriCd='{$DbnriCd}'");

      //表示用データ作成
      $this->_make_view_data($data);

      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data["item_list_view"];
      log_message('debug',"========== project_possession_view ajax_select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-ajax_select_item_list'));
      //$this->error_view($e);
    }
  }

  /**
  * (AJAX)企画アイテム表示 追加
  */
  function ajax_add_table_data($conf_name = MY_PROJECT_POSSESSION_VIEW)
  {
    try
    {
      log_message('debug',"========== project_possession_view ajax_add_table_data start ==========");
      $data = $this->init($conf_name); // ヘッダデータ等初期情報取得

      // モデル呼び出し
      $this->load->model('sgmtb080'); // ユーザー情報

      //企画情報アイテムデータ(大分類情報取得, アイテム情報取得)
      $add_view_no = $this->input->post('add_view_no');
      $data["kiaku_data"] = $this->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, view_no", "", "view_no");

      //表示用データ作成
      $this->_add_view_data($data, $add_view_no);

      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data["table_data"];
      log_message('debug',"========== project_possession_view ajax_add_table_data end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-ajax_add_table_data'));
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
  private function _display($data)
  {
    try
    {
      log_message('debug',"========== data_memo display start ==========");
      // ヘッダ部生成 title css image errmsg を使用
      $data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
      // メインコンテンツ生成 contents を使用
      $data['main']   = $this->load->view(MY_VIEW_PROJECT_POSSESSION_VIEW, $data, TRUE);
      // メニュー部生成 表示しない場合はNULL admin_flg を使用
      $data['menu']   = $this->load->view(MY_VIEW_MENU, $data, TRUE);
      // フッダ部生成 必ず指定する事
      $data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);

      // 表示処理
      log_message('debug',"========== data_memo display end ==========");

      //$this->load->view(MY_VIEW_LAYOUT, $data);
      $this->load->view(MY_VIEW_PROJECT_POSSESSION_VIEW, $data);
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-_display'));
      //			$this->error_view($e);
    }
  }

  /**
   * バリデーションチェック
   *
   * @access	public
   * @param	string $type = add:登録 update:更新 delete:削除
   * @return	boolean TRUE:成功 FALSE:エラー
   */
  private function _validate_check()
  {
    try
    {
      $v_result = FALSE;
      // ライブラリ読み込み
      $this->load->library('message_manager');
      $this->load->library('form_validation');
      // ルール読み込み
      $config = $this->config->item('validation_rules_poject_item');

      // バリデーションルールセット
      $this->form_validation->set_rules($config);
      // バリデーション結果チェック
      if($this->form_validation->run() == FALSE)
      {
        // 失敗
        //$v_result = FALSE;
        throw new Exception(ERROR_VALI);
        log_message('debug',"========== delete_user validation error ==========");
      }else{
        // 成功
        $v_result = TRUE;
        log_message('debug',"========== delete_user validation success ==========");
        return $v_result;
      }
    }catch(Exception $e){
      // エラー処理
      $this->load->view('/parts/error/error.php',array('errcode' => 'project_possession_view-validation'));
      //log_message('debug',"message = ".$e->getMessage());
      //$this->error_view($e->getMessage(),$type,NULL);
    }
  }

  /**
   * エラー発生時処理
   * @access	public
   * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
   * @return	none
   */
  private function _error_view($e,$type="add",$item=NULL)
  {
    log_message('debug',"exception : $e");
    $common_data = $this->init($type);         // ヘッダー設定
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
   * 表示用データ作成
   * @param array $data
   * @return none
   */
  private function _make_view_data(&$data, $disable=false) {
    //大分類、アイテム のリスト作成
    $daibunrui_list = array();
    $item_list = array();
    $data["table_data"] = "";
    $data['item_list_view'] = "";

    //データ存在チェック
    if(!$data["kiaku_data"]) return;
    $kiaku_data = $data["kiaku_data"];

    $kakutoku_data = "";
    if(isset($data["kakutoku_data"]) && $data["kakutoku_data"]) $kakutoku_data = $data["kakutoku_data"];

    foreach($kiaku_data as $d) {
      $daibunrui_list[$d["dbnricd"]] = $d["dbnrinm"];  //大分類リスト作成
      $item_list[$d["dbnricd"]][$d["itemcd"]] = $d["itemnm"];  //アイテムリスト作成
    }

    ///////////////////////////
    //  ドロップダウン作成
    //
    $this->load->helper('form');
    $this->load->library('project_possession_manager'); // データ表示部分作成

    //区分
    //区分データ取得
    $kbn_data = array("01"=>"確定","02"=>"予定");  //仮


    $daibunrui_list_view = array();
    $item_list_view = array();
    $table_data = "";
    if($kakutoku_data) {
      //登録データありの場合 企画獲得データで表示作成
      $no = 1;
      foreach( $kakutoku_data as $key=>$d ) {
        //大分類ドロップダウン
        $extra = 'id="daibunrui_list'.$no.'" onChange="reload_dropdown('."'{$data['app_url']}','{$no}'".');"';  //select要素のidとjs
        if($data["disabled"]) $extra .= " ".$data["disabled"];
        $daibunrui_list_view[$key] = form_dropdown('daibunrui_list[]', $daibunrui_list, $d["dbnricd"], $extra);  //

        //アイテムリストドロップダウン
        $extra = "";
        if($data["disabled"]) $extra = $data["disabled"];
        $id = 'id="item_list'.$no.'"';  //
        $item_list_view[$key] = "<td {$id}>".form_dropdown('item_list[]', $item_list[$d["dbnricd"]], $d["itemcd"], $extra).'</td>';

        //区分ドロップダウン作成
        $extra="";
        if($data["disabled"]) $extra = " ".$data["disabled"];
        $kbn_list_view[$key] = form_dropdown('kbn[]', $kbn_data, $d["kbn"], $extra);

        $kakutoku_data[$key]["view_no"] = $no;
        $no++;
      }

      foreach ($kakutoku_data as $key=>$d) {
        $table_data .= $this->project_possession_manager->set_project_possession_data($d, $daibunrui_list_view[$key], $item_list_view[$key], $kbn_list_view[$key], $disable);
      }
    } else {
      //登録データなしの場合 企画アイテムデータで表示作成
      foreach( $kiaku_data as $key=>$d ) {
        //大分類ドロップダウン
        $extra = 'id="daibunrui_list'.$d["view_no"].'" onChange="reload_dropdown('."'{$data['app_url']}','{$d["view_no"]}'".');"';  //select要素のidとjs
        if($data["disabled"]) $extra .= " ".$data["disabled"];
        $daibunrui_list_view[$key] = form_dropdown('daibunrui_list[]', $daibunrui_list, $d["dbnricd"], $extra);  //

        //アイテムリストドロップダウン
        $extra = "";
        if($data["disabled"]) $extra = $data["disabled"];
        $id = 'id="item_list'.$d["view_no"].'"';  //
        $item_list_view[$key] = "<td {$id}>".form_dropdown('item_list[]', $item_list[$d["dbnricd"]], $d["itemcd"], $extra).'</td>';
      }

      //区分ドロップダウン作成
      $extra="";
      if($data["disabled"]) $extra = " ".$data["disabled"];
      $kbn_list_view = form_dropdown('kbn[]', $kbn_data, "01", $extra);

      foreach ($kiaku_data as $key=>$d) {
        $table_data .= $this->project_possession_manager->set_project_possession_data($d, $daibunrui_list_view[$key], $item_list_view[$key], $kbn_list_view, $disable);
      }
    }

    $data['item_list_view'] = $item_list_view[0];
    $data["table_data"] = $table_data;
  }

  /**
  * 表示用データ作成
  * @param array $data
  * @return none
  */
  private function _add_view_data(&$data, $add_view_no) {
  //大分類、アイテム のリスト作成
    $daibunrui_list = array();
    $item_list = array();
    $data["table_data"] = "";

    if(!$data["kiaku_data"]) return;//
    $kiaku_data = $data["kiaku_data"];

    foreach($kiaku_data as $d) {
      $daibunrui_list[$d["dbnricd"]] = $d["dbnrinm"];  //大分類リスト作成
      $item_list[$d["dbnricd"]][$d["itemcd"]] = $d["itemnm"];  //アイテムリスト作成
    }

    //ドロップダウン作成
    //※ドロップダウンのselectedを別ける為、データ数分ドロップダウン生成
    $this->load->helper('form');
    //大分類ドロップダウン
    $extra = 'id="daibunrui_list'.$add_view_no.'" onChange="reload_dropdown('."'{$data['app_url']}','{$add_view_no}'".');"';  //select要素のidとjs
    $daibunrui_list_view = form_dropdown('daibunrui_list[]', $daibunrui_list, "", $extra);  //

    //アイテムリストドロップダウン
    $extra = 'id="item_list'.$add_view_no.'"';  //
    $item_list_view = "<td {$extra}>".form_dropdown('item_list[]', $item_list[$kiaku_data[0]["dbnricd"]]).'</td>';

    //区分
    //区分データ取得
    $kbn_data = array("01"=>"確定","02"=>"予定");  //仮

    //foreach( $kbn_data as $key=>$d ) {
    //区分ドロップダウン作成
    $kbn_list_view = form_dropdown('kbn[]', $kbn_data, "01");
    //}

    $view_no["view_no"] = $add_view_no;

    // データ表示部分を作成
    $this->load->library('project_possession_manager');
    $data["table_data"] = $this->project_possession_manager->set_project_possession_data($view_no, $daibunrui_list_view, $item_list_view, $kbn_list_view);
  }

}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
