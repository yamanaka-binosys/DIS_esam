<?php

class todo extends MY_Controller {
	/**
	 * todo(登録)
	 *
	 * @access	private
	 * @param	none
	 * @return	none
	 */
	function index()
	{
		try
		{
			log_message('debug',"========== todo start ==========");
			// 初期化
			$this->load->library('common_manager');
			$this->load->library('item_manager');
			$year_data = $this->config->item('s_todo_select_year');
			$month_data = $this->config->item('s_todo_select_month');
			$day_data = $this->config->item('s_todo_select_day');
			$data = NULL;
			$data = $this->common_manager->init(SHOW_TODO_A);

			if(!empty($_POST)){
				// 登録ボタン押下
				if(isset($_POST['add'])){
					$data = $this->add($data,$_POST);
				}
			}else{
				// 初期表示(登録)
				// 重要度
				$data['impkbn'] = "3";
				// 内容
				$data['todo'] = NULL;
				// 期限（年）
				$data['year'] = date("Y");
				$data['year'] = $this->item_manager->set_variable_dropdown_string($year_data);
				// 期限（月）
				$month_data['check'] = date("m");
				$data['month'] = $this->item_manager->set_variable_dropdown_string($month_data);
				// 期限（日）
				$day_data['check'] = date("d");
				$data['day'] = $this->item_manager->set_variable_dropdown_string($day_data);
			}
			// 画面表示
			$this->display($data,"add");

			// 登録ページの読み込み
			//$this->add_select_type();
			log_message('debug',"========== todo end ==========");
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-index'));
			//$this->error_view(ERROR_SYSTEM,"add");
		}
	}

	/**
	 * todo(初期化)
	 *
	 * @access	private
	 * @param	string 画面種別
	 * @return	array 画面の各ステータス
	 */
	function init($type)
	{
		try
		{
			log_message('debug',"========== todo init start ==========");
			$common_data = $this->config->item('s_todo_button');
			$select_month = $this->config->item('s_select_month');
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得

			// 初期共通項目情報
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = 'images/todo.gif';    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			$data['form_name'] = 'todo';
			$data['search_url']	 = $this->config->item('base_url').'index.php/select_client/index';

			log_message('debug',"========== todo init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-init'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}

	/**
	 * todo(表示)
	 *
	 * @access	public
	 * @param	array
	 * @return	none
	 */
	function display($data,$type="add")
	{
		try
		{
			log_message('debug',"========== todo display start ==========");
			log_message('debug',$type);
			$data['type'] = $type;

			// 表示処理
			$this->load->view(MY_VIEW_TODO, $data);
			log_message('debug',"========== todo display end ==========");

		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-display'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}

	/**
	 * todo(登録画面)
	 *
	 * @access	public
	 * @param	array string
	 * @return	nothing
	 */
	function add($data = NULL, $post = NULL)
	{
		try
		{
			log_message('debug',"========== todo add start ==========");
			$this->load->library('item_manager');
			$year_data = $this->config->item('s_todo_select_year');
			$month_data = $this->config->item('s_todo_select_month');
			$day_data = $this->config->item('s_todo_select_day');
			$add_result = FALSE;
			$error = "";
			// バリデーションチェック
			$v_result = $this->validate_check("add");
			//$v_result = true;
			// 入力欄に登録したデータ表示を残すためPOSTデータの受け渡し
			//$data = $post;
			// 重要度
			$data['impkbn'] = $post['impkbn'];
			// 内容
			$data['todo'] = $post['todo'];
			// 期限（年）
			$data['year'] = $post['year'];
			$data['year'] = $this->item_manager->set_variable_dropdown_string($year_data);
			// 期限（月）
			$month_data['check'] = $post['month'];
			$data['month'] = $this->item_manager->set_variable_dropdown_string($month_data);
			// 期限（日）
			$day_data['check'] = $post['day'];
			$data['day'] = $this->item_manager->set_variable_dropdown_string($day_data);
			// バリデーションが問題なければ登録実行
			if($v_result) {
				// バリデーション問題なし
				// モデル呼び出し
				$this->load->model('srktb040'); // アクション情報
				$post['shbn'] = $this->session->userdata('shbn');
				$add_result = $this->srktb040->insert_user($post); // todo登録実行

				// 登録結果判定し、完了メッセージ表示
				if($add_result)
				{
					// 登録成功
					// 完了メッセージ表示
					$data['infomsg'] = $this->message_create(USER_ADD);
				}else{
					log_message('debug',"========== 例外1 ============");
					// 登録失敗
					$data['errmsg'] = $this->message_create(ERROR_AUTH_FAILURE);
					//throw new Exception(ERROR_AUTH_FAILURE);
				}
			}else{
				log_message('debug',"========== 例外1 ============");
				$data['errmsg'] = $this->message_create(ERROR_AUTH_FAILURE);
				// バリデーションエラー
				//throw new Exception(ERROR_AUTH_FAILURE);
			}
			return $data;
		}catch(Exception $e){
			log_message('debug',"========== 例外 ============");
			log_message('debug',$e->getMessage());
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-add'));
			// エラー処理
			$this->error_view($e->getMessage(),"add");
		}
	}


	/**
	 * todo画面ヘッダー設定
	 *
	 * @access	public
	 * @param	string
	 * @return	nothing
	 */
	function header($type)
	{
		try
		{
			log_message('debug',"========== todo header start ==========");
			log_message('debug',"type = ".$type);
			// 登録ページの場合
/*			if($type === "add")
			{
				$header_data = $this->config->item('s_todo_add');
			// 更新ページの場合
			}else if($type === "update"){*/
				$header_data = $this->config->item('s_todo_update');
			//}
			log_message('debug',"========== todo header end ==========");
			return $header_data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-header'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}



	/**
	 * ボタン種類判別(更新)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	array data
	 */
	function update_select_type()
	{
		try
		{
			log_message('debug',"========== todo update_select_type start ==========");
			$this->load->library('common_manager');
			$this->load->library('item_manager');
			$data = NULL;
			$data = $this->common_manager->init(SHOW_TODO_U);
			$y_data = $this->config->item('s_year');
			$year_data = $this->config->item('s_todo_select_year');
			$month_data = $this->config->item('s_todo_select_month');
			$day_data = $this->config->item('s_todo_select_day');
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				if(isset($_POST['update'])){
				// ヘッダー付属ボタンの場合
					$data = $this->update($data,$_POST); // 更新実行
				}/*
				 else {
					// 検索ボタンの場合
					$data = $this->update_search($data,$_POST); // 更新画面内検索実行
				}*/
			}else{
				//初期画面で全て表示
				$data = $this->update_search($data);	//全内容表示
/*
				// 初期画面設定
				$none =
				// 開始年
				$year_data['data'] = $y_data;
				$year_data['name'] = "fromyear";
				$year_data['check'] = date("Y");
				$year_data['extra'] = 'class="checkdate_from" title="開始日" ';
				$data['fromyear'] = $this->item_manager->set_variable_dropdown_string($year_data);
				// 開始月
				array_unshift($month_data['data'],'');
				$month_data['name'] = "frommonth";
				$month_data['check'] = date("m");
				$month_data['extra'] = 'class="checkdate_from" title="開始日" ';
				$data['frommonth'] = $this->item_manager->set_variable_dropdown_string($month_data);
				// 開始日
				array_unshift($day_data['data'],'');
				$day_data['name'] = "fromday";
				$day_data['check'] = date("d");
				$day_data['extra'] = 'class="checkdate_from" title="開始日" ';
				$data['fromday'] = $this->item_manager->set_variable_dropdown_string($day_data);
				// 終了年
				$year_data['name'] = "toyear";
				$year_data['extra'] = 'class="checkdate_to" title="終了日" ';
				$data['toyear'] = $this->item_manager->set_variable_dropdown_string($year_data);
				// 終了月
				$month_data['name'] = "tomonth";
				$month_data['extra'] = 'class="checkdate_to" title="終了日" ';
				$data['tomonth'] = $this->item_manager->set_variable_dropdown_string($month_data);
				//　終了日
				$day_data['name'] = "today";
				$day_data['extra'] = 'class="checkdate_to" title="終了日" ';
				$data['today'] = $this->item_manager->set_variable_dropdown_string($day_data);
				log_message('debug',"========== todo update_select_type end ==========");
*/
			}
			// 画面表示
			$this->display($data,"update");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-update_select_type'));
			//$this->error_view(ERROR_SYSTEM,"update");
		}
	}
	/**
	 * todo情報(更新画面の検索)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	nothing
	 */
	function update_search($data = NULL,$post = NULL)
	{
		try
		{
			log_message('debug',"========== todo update_search start ==========");
			$result = FALSE;
			$result_cnt = 0;
			// ライブラリ読み込み
			$this->load->library('common_manager');
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			$this->load->library('item_manager');
			$y_data = $this->config->item('s_year');
			$year_data = $this->config->item('s_todo_select_year');
			$year_data['data'] = $y_data; // 年空白項目
			$month_data = $this->config->item('s_todo_select_month');
			array_unshift($month_data['data'],'');
			$day_data = $this->config->item('s_todo_select_day');
			array_unshift($day_data['data'],'');
			//$data = $this->common_manager->init(SHOW_TODO_U);
			// バリデーションチェック
//			$v_result = $this->validate_check("update","search");
			// バリデーション結果チェック
//			if($v_result)
			if(1==1)
			{
				// バリデーション問題なし
				// modelの読み込み
				$this->load->model('srktb040');
				$post['shbn'] = $this->session->userdata('shbn');
				$result = $this->srktb040->get_search_todo_data($post); // 検索データ取得
				// 取得結果
				if($result)
				{
					$data = $this->set_list_data($data,$result);
					// view側への生成
/*					foreach($result as $line => $line_data){
						$result[$line]['year'] = substr($line_data['act_ymd'], 0, 4);
						// 表示月の設定
						$month_data['check'] = substr($line_data['act_ymd'], 4, 2);
						$month_data['name'] = 'month[]';
						$result[$line]['month'] = $this->item_manager->set_variable_dropdown_string($month_data);
						// 表示日の設定
						$day_data['check'] = substr($line_data['act_ymd'], 6, 2);
						$month_data['name'] = 'day[]';
						$result[$line]['day'] = $this->item_manager->set_variable_dropdown_string($day_data);
						// 分けたものを削除
						unset($result[$line]['act_ymd']);
					}
					//$data = $_POST;
					// 取得成功
					$data["naiyo"] = $result;
					$result_cnt = count($result);
					if($result_cnt > 10){
						$data['list_height'] = 250;
						$data['list_width'] = 55;
					}else{
						$data['list_height'] = $result_cnt * 26;
						$data['list_width'] = 75;
					}*/
				}else{
					log_message('debug',"========== update_search nothing ==========");
					$data['errmsg'] = "該当データがありません。";
//					throw new Exception(ERROR_USER_SEARCH);
				}
			}else{
				// バリデーション失敗
				// POSTデータの受け渡し
				//$data = $_POST;
				$data['list_height'] = 0;
				$data['list_width'] = 0;
				// メッセージ設定
				log_message('debug',"========== update_search validation error ==========");
			}
			/*
			// 開始年
			$year_data['name'] = "fromyear";
			$year_data['check'] = $post['fromyear'];
			$year_data['extra'] = 'class="checkdate_from" title="開始日" ';
			$data['fromyear'] = $this->item_manager->set_variable_dropdown_string($year_data);
			// 開始月
			$month_data['name'] = "frommonth";
			$month_data['check'] = $post['frommonth'];
			$month_data['extra'] = 'class="checkdate_from" title="開始日" ';
			$data['frommonth'] = $this->item_manager->set_variable_dropdown_string($month_data);
			// 開始日
			$day_data['name'] = "fromday";
			$day_data['check'] = $post['fromday'];
			$day_data['extra'] = 'class="checkdate_from" title="開始日" ';
			$data['fromday'] = $this->item_manager->set_variable_dropdown_string($day_data);
			// 終了年
			$year_data['name'] = "toyear";
			$year_data['check'] = $post['toyear'];
			$year_data['extra'] = 'class="checkdate_to" title="終了日" ';
			$data['toyear'] = $this->item_manager->set_variable_dropdown_string($year_data);
			// 終了月
			$month_data['name'] = "tomonth";
			$month_data['check'] = $post['tomonth'];
			$month_data['extra'] = 'class="checkdate_to" title="終了日" ';
			$data['tomonth'] = $this->item_manager->set_variable_dropdown_string($month_data);
			//　終了日
			$day_data['name'] = "today";
			$day_data['check'] = $post['today'];
			$day_data['extra'] = 'class="checkdate_to" title="終了日" ';
			$data['today'] = $this->item_manager->set_variable_dropdown_string($day_data);
			*/
			// バリデーション問題なし
//			if($v_result)
		/*	if(1==1)
			{
				// 画面表示
				$this->display($data,"update");
			}*/
		log_message('debug',"========== todo update_search end ==========");
		return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-update_search'));
			//$this->error_view(ERROR_USER_SEARCH,"update");
		}
	}

	/**
	 * TODO情報(変更画面)
	 *
	 * @access	public
	 * @param	array string
	 * @param	array string
	 * @return	nothing
	 */
	function update($data = NULL, $post = NULL)
	{
		try
		{
			log_message("debug","================ todo update start ====================");
			$this->load->library('form_validation');
			$this->load->library('item_manager');
			$y_data = $this->config->item('s_year');
			$year_data = $this->config->item('s_todo_select_year');
			$month_data = $this->config->item('s_todo_select_month');
			$day_data = $this->config->item('s_todo_select_day');
			$update_result = FALSE;
			// バリデーションチェック
//			$v_result = $this->validate_check("update");
			// バリデーションが問題なければ登録実行
//			if($v_result)
			// 期限とチェックボックス判定
			if( isset($post["year"]) && count($post["year"]) > 0 && isset($post['select_check']))
			{
				// バリデーション問題なし
				// モデル呼び出し
				for( $i = 0; $i < count($post["year"]); $i++)
				{
					// チェックなければ次の行
					if(! in_array($post["jnum"][$i], $post['select_check'])){
						continue;
					}

					// チェックボックスの対応
					$compflg = 0;
					if(isset($post["comp"])){
						foreach($post["comp"] as $key => $value){
							if($value == $post["jnum"][$i]){
								$compflg = 1;
							}
						}
					}

					$update_data = array(
						"designatedday" => $hiduke = sprintf("%04d%02d%02d", $post["year"][$i],$post["month"][$i],$post["day"][$i]),
						"impkbn" => $post["important"][$i],
						"todo" => $post["contents"][$i],
						"finishflg" => $compflg,
						"jyohonum" => $post["jnum"][$i]
					);
					$this->load->model('srktb040'); // TODO情報
					// TODO更新実行
					$update_result = $this->srktb040->update_data($update_data);

					// 更新結果判定し、完了メッセージ表示
					if($update_result)
					{
						// 更新成功
						// modelの読み込み
						$this->load->model('srktb040');
						$post['shbn'] = $this->session->userdata('shbn');
						$result = $this->srktb040->get_search_todo_data($post); // 検索データ取得
						$data = $this->set_list_data($data,$result);
/*						$data["naiyo"][$i]['year'] = $post["year"][$i];
						$data["naiyo"][$i]['month'] = $post["month"][$i];
						$data["naiyo"][$i]['day'] = $post["day"][$i];
						$data["naiyo"][$i]['input_year'] = $post["input_year"][$i];
						$data["naiyo"][$i]['input_month'] = $post["input_month"][$i];
						$data["naiyo"][$i]['input_day'] = $post["input_day"][$i];
						$data["naiyo"][$i]['impkbn'] = $post["important"][$i];
						$data["naiyo"][$i]['todo'] = $post["contents"][$i];
						$data["naiyo"][$i]['finishflg'] = "0";
						$data["naiyo"][$i]['jyohonum'] = $post["jnum"][$i];*/
						// 完了メッセージ表示
						$data['infomsg'] = $this->message_create(USER_UPDATE);
						log_message('debug',"msg = ".$data['errmsg']);
					}else{
						log_message('debug',"========== 例外1 ============");
						// 登録失敗
						throw new Exception(ERROR_KARI_CLIENT_UPDATE);
					}
				}
			}else{
				// modelの読み込み
				$this->load->model('srktb040');
				$post['shbn'] = $this->session->userdata('shbn');
				$result = $this->srktb040->get_search_todo_data($post); // 検索データ取得
				$data = $this->set_list_data($data,$result);
			}
			/*
			// 開始年
			$year_data['name'] = "fromyear";
			$year_data['check'] = $post['fromyear'];
			$year_data['data'] = $y_data;
			$year_data['extra'] = 'class="checkdate_from" title="開始日" ';
			$data['fromyear'] = $this->item_manager->set_variable_dropdown_string($year_data);
			// 開始月
			$month_data['name'] = "frommonth";
			$month_data['check'] = $post['frommonth'];
			array_unshift($month_data['data'],'');
			$month_data['extra'] = 'class="checkdate_from" title="開始日" ';
			$data['frommonth'] = $this->item_manager->set_variable_dropdown_string($month_data);
			// 開始日
			$day_data['name'] = "fromday";
			$day_data['check'] = $post['fromday'];
			array_unshift($day_data['data'],'');
			$day_data['extra'] = 'class="checkdate_from" title="開始日" ';
			$data['fromday'] = $this->item_manager->set_variable_dropdown_string($day_data);
			// 終了年
			$year_data['name'] = "toyear";
			$year_data['check'] = $post['toyear'];
			$year_data['data'] = $y_data;
			$year_data['extra'] = 'class="checkdate_to" title="終了日" ';
			$data['toyear'] = $this->item_manager->set_variable_dropdown_string($year_data);
			// 終了月
			$month_data['name'] = "tomonth";
			$month_data['check'] = $post['tomonth'];
			$month_data['extra'] = 'class="checkdate_to" title="終了日" ';
			$data['tomonth'] = $this->item_manager->set_variable_dropdown_string($month_data);
			//　終了日
			$day_data['name'] = "today";
			$day_data['check'] = $post['today'];
			$day_data['extra'] = 'class="checkdate_to" title="終了日" ';
			$data['today'] = $this->item_manager->set_variable_dropdown_string($day_data);
			*/
			log_message("debug","================ todo update end ====================");
			return $data;
			// 画面表示
			//$this->display($data,"update");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-update'));
			//$this->error_view($e->getMessage(),"update");
		}
	}

	/**
	 * リストの作成
	 *
	 * @access	public
	 * @param	string $data          設定データ
	 * @param	array $search_result  検索結果
	 * @return	array data
	 */
	function set_list_data($data = NULL, $search_result = NULL)
	{
		try
		{
			log_message('debug',"========== todo set_list_data start ==========");
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			$this->load->library('item_manager');

			$year_data = $this->config->item('s_todo_select_year');
			$month_data = $this->config->item('s_todo_select_month');
			$day_data = $this->config->item('s_todo_select_day');

			// view側への生成
			$no=0;
			foreach($search_result as $line => $line_data){
				$no++;
				$year_data['check'] = substr($line_data['act_ymd'], 0, 4);
				$year_data['name'] = 'year[]';
				$year_data['extra'] = 'class="" title="期限" ';
				$search_result[$line]['year'] = $this->item_manager->set_variable_dropdown_string($year_data);
				// 表示月の設定
				$month_data['check'] = substr($line_data['act_ymd'], 4, 2);
				$month_data['name'] = 'month[]';
				$month_data['extra'] = 'class="" title="月" ';
				$search_result[$line]['month'] = $this->item_manager->set_variable_dropdown_string($month_data);
				// 表示日の設定
				$day_data['check'] = substr($line_data['act_ymd'], 6, 2);
				$day_data['name'] = 'day[]';
				$day_data['extra'] = 'class="" title="期限" ';
				$search_result[$line]['day'] = $this->item_manager->set_variable_dropdown_string($day_data);
				// 分けたものを削除
				unset($search_result[$line]['act_ymd']);
			}
			// 取得成功
			$data["naiyo"] = $search_result;
			$result_cnt = count($search_result);
			if($result_cnt > 10){
				$data['list_height'] = 250;
				$data['list_width'] = 49;
			}else{
				$data['list_height'] = $result_cnt * 27;
				$data['list_width'] = 66;
			}
			log_message('debug',"========== todo set_list_data end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-set_list_data'));
			//$this->error_view(ERROR_SYSTEM,$type);
		}
	}

	/**
	 * ボタン種類判別(削除)
	 *
	 * @access	public
	 * @param	nothing
	 * @return	array data
	 */
/*	function delete_select_type()
	{
		try
		{
			// ボタン押下時の処理
			if(!empty($_POST))
			{
				// 検索ボタンの場合
				if(isset($_POST['select']))
				{
					$data = $this->delete_search($_POST); // 削除画面内検索実行
				}else if(isset($_POST['set'])){
				// ヘッダー付属ボタンの場合
					$data = $this->delete($_POST); // 削除実行
				}
				return $data;
			}else{
				// 初期画面設定
				$data = $this->init("delete");
				$common_data = $this->header("delete");           // ヘッダー設定
				// 共通項目情報
				$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
				$data['title']     = $common_data['title'];    // タイトルに表示する文字列
				$data['css']       = $common_data['css'];      // 個別CSSのアドレス
				$data['image']     = 'images/todo.gif';    // タイトルバナーのアドレス
				$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
				$data['btn_name']  = $common_data['btn_name']; // ボタン名
				$data['form']      = $common_data['form'];     // formタグのあり・なし
				$data['form_name'] = 'todo';
				$data['main_form'] = $common_data['form'];     // メイン内formの送信先
				$data['search_url']	 = $this->config->item('base_url').'index.php/select_client/index';
				$data['base_url'] = $this->config->item('base_url');
				// 仮相手先情報画面表示
				$this->display($data,"delete");
			}
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,"delete");
		}
	}
*/
	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $type = add:登録 update:更新 delete:削除
	 * @return	boolean TRUE:成功 FALSE:エラー
	 */
     function validate_check($type = NULL)
	 {
		 try
		 {
			$v_result = FALSE;
			// ライブラリ読み込み
			$this->load->library('message_manager');
			$this->load->library('form_validation');
			// ルール読み込み
			switch($type)
			{
				// 登録
				case "add":
					$config = $this->config->item('validation_rules_todo_add');
					break;
				// 更新
				case "update":
					$config = $this->config->item('validation_rules_todo_update');
					break;
				// 検索
				case "search":
					//$config = $this->config->item('validation_rules_todo_search');
					break;
			}
			// バリデーションルールセット
			$this->form_validation->set_rules($config);
			// バリデーション結果チェック
			if($this->form_validation->run() == FALSE)
			{
				// 失敗
				//$v_result = FALSE;
				$v_result = FALSE;
				/*foreach ( $this->form_validation->_error_array as $value ) {
					$message .= $value.'<br>';

				}*/
				//throw new Exception($message);
				log_message('debug',"========== validate_check validation error ==========");
			}else{
				// 成功
				$v_result = TRUE;
				log_message('debug',"========== validate_check validation success ==========");
			}
				return $v_result;
		 }catch(Exception $e){
			// エラー処理
			log_message('debug',"message = ".$e->getMessage());
			$this->load->view('/parts/error/error.php',array('errcode' => 'todo-validate_check'));
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
		/*$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
		$data['title']     = $common_data['title'];    // タイトルに表示する文字列
		$data['css']       = $common_data['css'];      // 個別CSSのアドレス
		$data['image']     = 'images/todo.gif';    // タイトルバナーのアドレス
		$data['btn_name']  = $common_data['btn_name']; // ボタン名
		$data['form']      = $common_data['form'];     // formタグのあり・なし
		$data['form_name'] = 'todo';
		$data['main_form'] = $common_data['form'];     // メイン内formの送信先
		$data['search_url']	 = $this->config->item('base_url').'index.php/select_client/index';
		$data['base_url'] = $this->config->item('base_url');
		$data['errmsg'] = $e;*/
//		$this->display($data,$type);
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

	/**
	 * トップからの確認画面用
	 * @access	public
	 * @param	string $jyohonum 情報№
	 * @param	string $edbn     枝番
	 * @return	nothing
	 */
	function check_view($jyohonum = NULL,$edbn = NULL)
	{
		log_message('debug',"========== todo check_view start ==========");
		$this->load->model('srktb040');
		// 初期化
		$db_data = NULL;
		$data['year'] = "";
		$data['month'] = "";
		$data['day'] = "";
		$data['impkbn'] = "";
		$data['todo'] = "";

		if(!is_null($jyohonum)){
			$db_data = $this->srktb040->get_check_todo_data($jyohonum,$edbn);
			if($db_data){
				foreach($db_data[0] as $key => $value){
					if($key === 'designatedday'){
						$data['year'] = substr($value,0,4);
						$data['month'] = substr($value,4,2);
						$data['day'] = substr($value,6,2);
					}else if($key === 'impkbn'){
						if($value === '1'){
							$data['impkbn'] = "低";
						}else if($value === '2'){
							$data['impkbn'] = "中";
						}else if($value === '3'){
							$data['impkbn'] = "高";
						}
					}else if($key === 'todo'){
						$data['todo'] = $value;
					}
				}
			}else{
				// 失敗
			}
		}

		// 表示処理
		$this->load->view(MY_VIEW_CHECK_TODO, $data);
		log_message('debug',"========== todo check_view end ==========");
	}
	/**
	 * ボタン種類判別(登録)
	 *
	 * @access	public
	 * @param	none
	 * @return	array data
	 */
/*	function add_select_type()
	{
		try
		{
			log_message('debug',"========== todo add_select_type start ==========");
			$data = NULL;
			// ボタン押下時の処理
			if(!empty($_POST) && !isset($_GET['ret']))
			{
				// 検索ボタンの場合
				if(isset($_POST['search']))
				{
					$data = $this->add_search($_POST); // 登録画面内検索実行
				}else if(isset($_POST['set'])){
				// ヘッダー付属ボタンの場合
					$data = $this->add($_POST); // 登録実行
				}
				return $data;
			}else{
				// 初期画面設定
				$data = $this->init("add");
				$common_data = $this->header("add");           // ヘッダー設定
				// 共通項目情報
				$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
				$data['title']     = $common_data['title'];    // タイトルに表示する文字列
				$data['css']       = $common_data['css'];      // 個別CSSのアドレス
				$data['image']     = 'images/todo.gif';    // タイトルバナーのアドレス
				$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
				$data['btn_name']  = $common_data['btn_name']; // ボタン名
				//$data['form']      = $common_data['form'];     // formタグのあり・なし
				$data['form_name'] = 'todo';
				$data['main_form'] = $this->config->item('base_url').'index.php/todo/add';     // メイン内formの送信先
				$data['search_url']	 = $this->config->item('base_url').'index.php/select_client/index';
				$this->display($data,"add");
			}
			log_message('debug',"========== todo add_select_type end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->error_view(ERROR_SYSTEM,"add");
		}
	}
*/

}
/* End of file user.php */
/* Location: ./application/controllers/user.php */