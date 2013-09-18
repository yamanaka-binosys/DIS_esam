<?php
//	 * @author goto 20120219

class Select_client_search extends MY_Controller {

	/***
	 * @param mode TRUE：表有り　FALSE：表無し　
	*/
	function index($conf_name = 'search_maker',$count)
	{
		try
		{
			log_message('debug',"========== Select_client_search index start ==========");
			$data = $this->init($conf_name); // ヘッダデータ等初期情報取得
			$data['count']=$count;
			// DBから読み込んだデータをここにセットする
			// view側では、$list_tableで利用可能
			$data["list_table"]['search_data'] = $this->_get_list($data);

			// Main表示情報取得
			$plan_data['existence'] = FALSE;
			$this->display($data,$conf_name); // 画面表示処理
			log_message('debug',"========== Select_client_search index end ==========");

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-index'));
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
			log_message('debug',"========== Select_client_search init start ==========");
			log_message('debug',"\$conf_name = $conf_name");

			// 初期化
			$common_data = $this->config->item($conf_name);

			$this->load->library('table_set');

			// セッション情報から社番を取得
			$data['shbn'] = $this->session->userdata('shbn');

			// セッション情報から管理者フラグを取得
			$data['admin_flg'] = $this->session->userdata('admin_flg');

			// 表示に必要な情報をセットする
			$data["list_table"]['init_data'] = $this->_get_init_data($data);

			// 初期共通項目情報
			$data['title']       = $common_data['title']; // タイトルに表示する文字列
			$data['css']         = $common_data['css']; // 個別CSSのアドレス
			$data['image']       = $common_data['image']; // タイトルバナーのアドレス
			$data['errmsg']      = $common_data['errmsg']; // エラーメッセージ
			$data['form_url']	 = $this->config->item('base_url').'index.php/select_client_search/'.$common_data['form'].'/';
			$data['form_config']		 = $common_data['form'];   // 送信先
			$data["list_table"]['return_url']  = $this->config->item('base_url').'index.php/select_client_search/update/'.$common_data['form'];

			log_message('debug',"========== Select_client_search init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-init'));
			//$this->error_view($e);
		}
	}

	function search_maker($hyojun='1',$count='00',$update=NULL)
	{
		if($update!=""){
			//店舗検索は区分2
			$this->update('MY_SELECT_SEARCH_HEAD',$_POST,'002',$hyojun);
		}
		$this->index('search_maker',$count);
	}
	function search_agency($hyojun='1',$count='00',$update=NULL)
	{

		if($update!=""){
			//本部検索は区分2
			$this->update(MY_SELECT_SEARCH_AGENCY,$_POST,'003',$hyojun);
		}
		$this->index('search_agency',$count);  //代理店検索
	}
	function search_head($hyojun='1',$count='00',$update=NULL)
	{
		if($update!=""){
			//本部検索は区分1
			$this->update('MY_SELECT_SEARCH_HEAD',$_POST,'001',$hyojun);
		}
		$this->index('search_head',$count);
	}
	function search_shop()
	{
		$this->index('search_shop');
	}

	/**
	 * 共通で使用する表示処理
	 *
	 * @access  public
	 * @param   array $data 各種HTML作成時に必要な値
	 * @return  nothing
	 */
	function display($data,$conf_name)
	{
		try
		{
			log_message('debug',"========== Select_client_search display start ==========");
			// 表示処理
			if($conf_name=="search_head"){
				$this->load->view(MY_VIEW_SELECT_CLIENT_SEARCH_HEAD, $data);
			}elseif($conf_name=="search_agency"){
			  $this->load->view(MY_VIEW_SELECT_CLIENT_SEARCH_AGENCY, $data);
			}elseif($conf_name=="search_maker"){
				$this->load->view(MY_VIEW_SELECT_CLIENT_SEARCH_MAKER, $data);
			}else{
				$this->load->view(MY_VIEW_SELECT_CLIENT_SEARCH, $data);
			}

			log_message('debug',"========== Select_client_search display end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-display'));
		}
	}

	/**
	 *
	 * 本部検索
	 * @author goto 20120219
	 */
	public function head()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client_search head start ==========");
			$conf_name = MY_SELECT_CLIENT_HEAD;

			$this->index($conf_name);

			log_message('debug',"========== Select_client_search head end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-head'));
		}
	}

	/**
	 *
	 * 店舗（メーカー）
	 * @author goto 20120219
	 */
	public function maker()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client_search maker start ==========");
			$conf_name = MY_SELECT_CLIENT_MAKER;

			$this->index($conf_name);

			log_message('debug',"========== Select_client_search maker end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-maker'));
		}
	}


	/**
	 *
	 * 店舗（一般）
	 * @author goto 20120219
	 */
	public function shop()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client_search shop start ==========");
			$conf_name = MY_SELECT_CLIENT_SHOP;

			$this->index($conf_name);

			log_message('debug',"========== Select_client_search shop end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-shop'));
		}
	}

	/**
	 *
	 * 代理店
	 * @author goto 20120219
	 */
	public function agency()
	{
		try
		{
			$base_url = $this->config->item('base_url');
			if(!empty($_POST) && !empty($_POST["search"])) header("Location: ".$base_url."select_client");
			if(!empty($_POST) && !empty($_POST["set"])) $v_result = $this->validate_check();
			log_message('debug',"========== Select_client_search add start ==========");
			$conf_name = MY_SELECT_CLIENT_AGENCY;

			$this->index($conf_name);

			log_message('debug',"========== Select_client_search agency end ==========");
			return;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-agency'));
		}
	}

	/**
	 * 初期表示時に利用するデータ
	 * Enter description here ...
	 * @author goto 20120219
	 */
	private function _get_init_data($data)
	{
		$array = array();
		
		$CI =& get_instance();

		// DB名と同じものを指定
		$this->load->model('sgmtb020');
		
		$this->load->library('table_set');
		$table_data = NULL;

		// 日報の業務区分
		$array['business_type'] = $this->config->item('s_business_type');

		// 自分の情報
		$array['my_info'] = $this->table_set->get_user_Info($data['shbn']);

		// 本部
		if(isset($_POST['honbucd'])){
			$array['my_info'][0]['honbucd'] = $_POST['honbucd'];
		}
		//$array['head'] = $this->table_set->get_head_info($data, BUSINESS_UNIT_HEAD);
		$array['head'] = $this->sgmtb020->get_honbu_name_data();
		
		if($array['my_info'][0]['honbucd']!="XXXXX"){
		// 部
		if(isset($_POST['bucd'])){
			$array['my_info'][0]['bucd'] = $_POST['bucd'];
		}
		//$array['division'] = $this->table_set->get_head_info($data, BUSINESS_UNIT_DIVISION);
			$array['division'] = $this->sgmtb020->get_bu_name_data_select($array['my_info'][0]['honbucd']);
		}else{
			$array['division'] = array();
		}

		// ユニット
		if(isset($_POST['kacd'])){
			$array['my_info'][0]['kacd'] = $_POST['kacd'];
		}
		//$array['unit'] = $this->table_set->get_head_info($data, BUSINESS_UNIT_UNIT);
		if($array['my_info'][0]['bucd']!="XXXXX"){
			$array['unit'] = $this->sgmtb020->get_unit_name_data_select($array['my_info'][0]['honbucd'],$array['my_info'][0]['bucd']);
		}else{
			$array['unit'] = array();
		}

		if(isset($_POST['shbn'])){
			$array['my_info'][0]['shbn'] = $_POST['shbn'];
		}
		if($array['my_info'][0]['kacd']!="XXXXX"){
			// 担当者
			$array['staff_list'] = $this->sgmtb010->get_shin_data($array['my_info'][0]['honbucd'],$array['my_info'][0]['bucd'],$array['my_info'][0]['kacd']);
		}else{
			$array['staff_list'] = array();
		}
		
		if(isset($_POST['pref'])){
			$array['my_info'][0]['pref'] = $_POST['pref'];
		}else{
			$array['my_info'][0]['pref'] = NULL;
		}	
		// 都道府県
		$array['prefecture'] = $this->config->item('c_pref');
		
		// 店舗検索のラジオボックス設定
		if(isset($_POST['shop_type'])){
			if($_POST['shop_type'] === "shop"){
				$array['select_s_type'] = "shop";
			}else if($_POST['shop_type'] === "maker"){
				$array['select_s_type'] = "maker";
			}
		}else{
			// 初期表示
			$array['select_s_type'] = "maker";
		}

		return $array;
	}

	/**
	 * 検索条件を取得
	 *
	 * @access private
	 * @param $data ARRAY
	 * @param $mode TRUE=登録　FALSE=更新、削除
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_list($data)
	{
		try
		{
			log_message('debug',"========== Select_client_search _get_list start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;

			$post_data = $_POST;
			
			if(isset($post_data['honbucd']) && $post_data['honbucd']=='XXXXX'){
				$post_data['honbucd'] = '';
			}
			if(isset($post_data['bucd']) && $post_data['bucd']=='XXXXX'){
				$post_data['bucd'] = '';
			}
			if(isset($post_data['kacd']) && $post_data['kacd']=='XXXXX'){
				$post_data['kacd'] = '';
			}
			
			if ( isset($post_data['search']) && $post_data['search'] ) {
				// 検索ボタンを押されたときは、POST値の社員番号に従う
				if ( isset($_POST['shbn']) && $_POST['shbn'] ) {
					$post_data['shbn'] = $_POST['shbn'];
			log_message('debug',"========== Select_client_search _get_list start ==========");
				}
			} else {
				// 初期表示時は、Session値の社員番号に従う
				$post_data['shbn'] = $data['shbn'];
			}

			$where = array('column_name' => 'value');
			switch($data["form_config"]) {
				case 'search_head':
					// 本部
			log_message('debug',"========== _get_list honbu ==========");

					if ( isset($post_data['search']) ) {
						$post_data['my_info'] = array('honbucd' => $post_data['honbucd'],
														'bucd' => $post_data['bucd'],
														'kacd' => $post_data['kacd']
						);
					} else {
						$post_data['my_info'] = $data['list_table']['init_data']['my_info'];
					}
					$table_data = $this->table_set->get_head_data($post_data);
					break;
				case 'search_maker':
				// 店舗メーカー
			log_message('debug',"========== _get_list maker ==========");
					if ( isset($post_data['search']) ) {
						$post_data['my_info'] = array('hon' => $post_data['honbucd'],
														'bucd' => $post_data['bucd'],
														'kacd' => $post_data['kacd']
						);
					} else {
						$post_data['my_info'] = $data['list_table']['init_data']['my_info'];
					}
					
					$table_data = $this->table_set->get_maker_data($post_data);
					break;
				case 'search_shop':
					//店舗一般店
			log_message('debug',"========== _get_list search_shop ==========");
					$table_data = $this->table_set->get_shop_data($post_data);
					break;
				case 'search_agency':
				// 代理
			log_message('debug',"========== _get_list search_agency ==========");
					if ( isset($post_data['search']) ) {
						$post_data['my_info'] = array(
    							'hon' => $post_data['honbucd'],  //本部
    							'bucd' => $post_data['bucd'],    //部
    							'kacd' => $post_data['kacd']     //課
						);
					} else {
						$post_data['my_info'] = $data['list_table']['init_data']['my_info'];
					}
					$table_data = $this->table_set->get_agency_data($post_data);
					break;
			}

			log_message('debug',"========== Select_client_search _get_list end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client-_get_list'));
		}
	}

	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $type = add:登録 update:更新 delete:削除
	 * @return	boolean TRUE:成功 FALSE:エラー
	 */
	function validate_check()
	{
		try
		{
			$v_result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			// 更新
			$config = $this->config->item('validation_rules_client');

			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				// 失敗
				//$v_result = FALSE;
				throw new Exception(ERROR_VALI);
				log_message('debug',"========== Select_client_search validate_check error ==========");
			}else{
				// 成功
				$v_result = TRUE;
				log_message('debug',"========== Select_client_search validate_check success ==========");
				return $v_result;
			}
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client_search-validate_check'));
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


	function update($type,$post,$kbn,$hyojun='1')
	{
		log_message('debug',"========== Select_client_search update start ==========");	
		$CI =& get_instance();
		$CI->load->model('srwtb021'); // ユーザ別検索情報（相手先）

		//保存するデータを設定する
		//shbnはセッションのものを使用
		$post['shbn'] = $this->session->userdata('shbn');

		$hanhon = NULL;
		//raidoboxでメーカーと一般店を判定
		if(isset($_POST['shop_type']) && $_POST['shop_type'] === "shop"){
			// 一般店の場合
			$hanhon[0] = MY_AITESK_CD_IPPAN;
			$hanhon[1] = $_POST['shop_name'];
		log_message('debug',"==========  ==========");	
		}else{
			// メーカーの場合
			//postのhanhoncdをsplit
			if(!isset($post['hanhoncd']) || !$post['hanhoncd']) return;
			$hanhon= explode("|",$post['hanhoncd']);
			foreach ($hanhon as $key => $value) {
		log_message('debug',"hanhon[".$key."] = ".$hanhon[$key]);	
			}
		}
		//この$dataは区分：本部の場合  区分によって相手先のデータが違う
		$data = array(
			'shbn'     => $post['shbn'] , 
			'kbn'      => $kbn , 
			'aiteskcd' => $hanhon[0] , 
			'hyojun'   => $hyojun , 
			'aitesknm' => $hanhon[1],
			'hanhoncd' => $hanhon[2]
			);

		//データ（表示順で判断）がある場合は、UPDATEする
		$res = $CI->srwtb021->date_insert_update($data);
		log_message('debug',"========== Select_client_search update end ==========");	
	}
	
   /**
   *  ドロップダウンリスト変更
   */
	function select_item_list($type=NULL)
		{
			try
			{
			log_message('debug',"========== Select_client_search select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
			$honbu = $_POST['selected_val'];

			// 部情報取得
			$bu = $this->sgmtb020->get_bu_name_data_select($honbu);

			if($honbu =='XXXXX'){
			$bu=array();
			}
			
			// 課・ユニット情報取得
			//$unit = $this->sgmtb020->get_unit_name_data_select($honbu);
			$unit=array();
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			$staff_list = array();
			// 都道府県
			$prefecture = $this->config->item('c_pref');
			
			$this->load->library('table_manager');
			//表示用データ作成
			if(isset($type) && $type == "maker"){
				$data['division'] = $this->table_manager->set_select_client_search_list_string_maker($head,$bu,$unit,$staff_list,$honbu,$prefecture);
			}else if(isset($type) && $type == "agency"){
				$data['division'] = $this->table_manager->set_select_client_search_list_string_agency($head,$bu,$unit,$staff_list,$honbu,$prefecture);
			}else{
			$data['division'] = $this->table_manager->set_select_client_search_list_string($head,$bu,$unit,$staff_list,$honbu);
			}
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['division'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client_search-select_item_list'));
			//$this->error_view($e);
		}
	}
 
    /**
   *  ドロップダウンリスト変更
   */
	function select_item_unit_list($type=NULL){
			try
			{
			
			log_message('debug',"========== Select_client_search select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
		    $honbu = $_POST['selected_val']['val'];
		    // 部情報取得
			$bu= $this->sgmtb020->get_bu_name_data_select($honbu);
		   
		    $busyo = $_POST['selected_val']['val2'];
		    
			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$busyo);
			
			if($busyo =='XXXXX'){
			$unit = array();
			}
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			$staff_list =array();
			// 都道府県
			$prefecture = $this->config->item('c_pref');
			
			$this->load->library('table_manager');
			//表示用データ作成
			if(isset($type) && $type == "maker"){
				$data['division'] = $this->table_manager->set_select_client_search_list_string_maker($head,$bu,$unit,$staff_list,$honbu,$prefecture,$busyo);
			}else if(isset($type) && $type == "agency"){
				$data['division'] = $this->table_manager->set_select_client_search_list_string_agency($head,$bu,$unit,$staff_list,$honbu,$prefecture,$busyo);
			}else{
			$data['division'] = $this->table_manager->set_select_client_search_list_string($head,$bu,$unit,$staff_list,$honbu,$busyo);
			}
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['division'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client_search-select_item_unit_list'));
			//$this->error_view($e);
		}
	}
 
  /**
   *  ドロップダウンリスト変更
   */
	function select_item_user_list($type=NULL){

			try
			{
				
			log_message('debug',"========== Select_client_search select_item_user_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
		    $honbu = $_POST['selected_val']['val'];
		    // 部情報取得
			$bu= $this->sgmtb020->get_bu_name_data_select($honbu);
		   
		    $busyo = $_POST['selected_val']['val2'];
			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$busyo);
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			
			$ka = $_POST['selected_val']['val3'];
			
			
			$this->load->model('sgmtb010'); // ユーザ別検索情報（相手先）

			$staff_list = $this->sgmtb010->get_shin_data($honbu,$busyo,$ka);
			
			
			if($ka=="XXXXX"){
			 $staff_list = array();
			}
			// 都道府県
			$prefecture = $this->config->item('c_pref');
			
			$this->load->library('table_manager');
			//表示用データ作成
			if(isset($type) && $type == "maker"){
				$data['division'] = $this->table_manager->set_select_client_search_list_string_maker($head,$bu,$unit,$staff_list,$honbu,$prefecture,$busyo,$ka);
			}else if(isset($type) && $type == "agency"){
				$data['division'] = $this->table_manager->set_select_client_search_list_string_agency($head,$bu,$unit,$staff_list,$honbu,$prefecture,$busyo,$ka);
			}else{
			$data['division'] = $this->table_manager->set_select_client_search_list_string($head,$bu,$unit,$staff_list,$honbu,$busyo,$ka);
			}
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['division'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_client_search-select_item_user_list'));
			//$this->error_view($e);
		}
	}
 
}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
