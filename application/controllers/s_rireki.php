<?php
class S_rireki extends MY_Controller {
	/**
	 * 商談履歴
	 *
	 * @access	private
	 * @param	nothing
	 * @return	string
	 */
	function index()
	{
		// 初期化
		$this->load->library('table_set');
		$this->load->library('javascript');

		$data = "";
		$data['title']		= '商談履歴';                                            // タイトルに表示する文字列
		$data['css']		= NULL;                                                 // 個別CSSのアドレス
		$data['image']		= 'images/s_rireki.gif';                                // タイトルバナーのアドレス
		$data['errmsg']		= NULL;                                                 // エラーメッセージ
		$data['msg_type']	= NULL;                                                 // メッセージタイプ
		$data['form_url']	= $this->config->item('base_url').'index.php/s_rireki/search/';   // 送信先
		$data['admin_flg']	= TRUE;                                                 // 管理者フラグ（管理者=TRUE、一般=FALSE）
		$data['btn_name']	= NULL;                                                 // ボタン表示名
		$data['search_flg'] = FALSE;                                                // 検索フラグ
		$data['client_box'] = $this->table_set->set_nego_date($_POST);              // 相手先選択ボックス作成

		// コンテンツ生成
		$this->load->view(MY_VIEW_S_RIREKI, $data, FALSE);

	}

	/**
	 * 商談履歴検索結果
	 *
	 * @access	private
	 * @param	nothing
	 * @return	string
	 */
	function search() {

		try {

			// セッション切れはログイン画面へ
			if($this->input->post("out_put")){
				$this->session->set_userdata(array("search_data" => $this->input->post()));
				$search_data = $this->input->post();
			}
			$search_data = $this->session->userdata("search_data");

			if(empty($search_data)){
				$url = $this->load->helper('url');
				redirect(base_url().'index.php/login/index');
			}

			// 初期化
			$this->load->library('table_set');
			$this->load->library('javascript');

			$data = "";
			$data['title']		 = '商談履歴';											// タイトルに表示する文字列
			$data['css']		 = NULL;												// 個別CSSのアドレス
			$data['image']		 = 'images/s_rireki.gif';								// タイトルバナーのアドレス
			$data['errmsg']		 = NULL;												// エラーメッセージ
			$data['form_url']	 = $this->config->item('base_url').'index.php/s_rireki/search/';	// 送信先
			$data['admin_flg']	 = TRUE;												// 管理者フラグ（管理者=TRUE、一般=FALSE）
			$data['btn_name']	 = NULL;												// ボタン表示名
			$data['client_box']	 = $this->table_set->set_nego_date($_POST);	     	    // 相手先選択ボックス作成
			$data['search_flg']	 = FALSE;												// 検索フラグ

			// POST値VALIDATE
			$this->load->library('form_validation');
			$data['errmsg'] = $this->validate_check($search_data);
			$data['msg_type'] = 'msg-error';
			if( $data['errmsg'] != ''){
				// コンテンツ生成
				$this->load->view(MY_VIEW_S_RIREKI, $data, FALSE);
				return;
			}

			$data['search_flg']	 = TRUE;												// 検索フラグ
			$shbn = $this->session->userdata('shbn');
			
			// SRKTB010
			$this->load->model('srktb010');
			$nego_history_data = $this->srktb010->get_nego_history_data($_POST,$shbn);// 検索結果一覧テーブル作成

			if(empty($nego_history_data)){
				throw new Exception("検索結果がありません。");
			}

			// ページネーション
			$this->load->library('pagination');
			$config = array();
			$config['base_url'] = base_url().'index.php/s_rireki/search/page/';
			$config['total_rows'] = count($nego_history_data);
			$config['per_page'] = 20;
			$config['uri_segment'] = 4;
			$config['num_links'] = 2;
			$config['first_link'] = false;
			$config['last_link'] = false;
			$this->pagination->initialize($config);

			// ページネーションがある場合は、offsetによって表示内容を変更
			$offset = $product_id = $this->uri->segment(4);

			if($offset > 0 && count($nego_history_data) > $offset){

				$view_data = array ();
				for($i=0;$i<20;$i++){
					$view_data[] = $nego_history_data[$offset-1+$i];
				}
			} else {
				$view_data = $nego_history_data;
			}

			// 検索結果をテーブル情報に設定
			$this->load->library('table_manager');
			
			$data['result_list'] = $this->table_manager->set_nego_history_data($view_data,$this->pagination->create_links());			// 検索結果一覧テーブル作成

			// コンテンツ生成
			$this->load->view(MY_VIEW_S_RIREKI, $data, FALSE);

		} catch (Exception $e){
			// エラー処理
			//$this->load->view('/parts/error/error.php',array('errcode' => 's_rireki-search'));
			$this->error_view($e);
		}
	}

	/**
	 * バリデーションチェック
	 *
	 * @access	public
	 * @param	string $data 検索情報
	 * @return	boolean $result TRUE:成功 FALSE:エラー
	 */
     function validate_check($search_data)
	{
		// ルール読み込み
		$config = $this->config->item('validation_rules_s_rireki_search');

		// バリデーションルールセット
		$this->form_validation->set_rules($config);

		// 年月日の相関チェック
		$errStr = $this->valid_date($search_data);

		// バリデーション結果チェック
		if($this->form_validation->run() && $errStr == '')
		{
			// 成功
			log_message('debug',"========== s_rireki validation success ==========");
			return '';

		}else{
			// 失敗
			log_message('debug',"========== s_rireki validation error ==========");
			$this->form_validation->set_error_delimiters('','');
			return str_replace(array("\r\n", "\n", "\r"), "<br>", $this->form_validation->error_string()) . $errStr;
		}
	}

	/**
	 * 日付チェック
	 */
	function valid_date($search_data)
	{
		// 開始年月チェック
		if ( $search_data['s_year'] != '' && ! $this->org_checkdate($search_data['s_month'], $search_data['s_day'], $search_data['s_year']))
		{
			return '商談日（開始）の日付が適切ではありません。';
		}

		// 終了年月チェック
		if ( $search_data['e_year'] != '' && ! $this->org_checkdate($search_data['e_month'], $search_data['e_day'], $search_data['e_year']))
		{
			return '商談日（終了）の日付が適切ではありません。';
		}

		// 年月相関チェック
		if ($this->org_checkdate($search_data['s_month'], $search_data['s_day'], $search_data['s_year']) &&
			$this->org_checkdate($search_data['e_month'], $search_data['e_day'], $search_data['e_year']) &&
			strtotime($search_data['s_year'] . '/' . $search_data['s_month'] . '/' . $search_data['s_day']) > strtotime($search_data['e_year'] . '/' . $search_data['e_month'] . '/' . $search_data['e_day']) )
		{
			return '商談日（終了）の日付が商談日（開始）の日付より以前の日付です。';
		}
	}

	/**
	 * オリジナル
	 * 日付のチェック
	 *
	 * @param $month
	 * @param $day
	 * @param $year
	 */
	function org_checkdate($month, $day, $year){
		if($year != '' && is_numeric($year) && checkdate($month, $day, $year)){
			return true;

		} else {
			return false;
		}
	}

	/**
	 * エラー発生時処理
	 * @access	public
	 * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
	 * @return	none
	 */
	function error_view($e,$type="add",$item=NULL){
		log_message('debug',"exception : $e");

		// 初期化
		$this->load->library('table_set');
		$this->load->library('javascript');

		$data['title']		= '商談履歴';                                            // タイトルに表示する文字列
		$data['css']		= NULL;                                                 // 個別CSSのアドレス
		$data['image']		= 'images/s_rireki.gif';                                // タイトルバナーのアドレス
		$data['errmsg']		= $e->getMessage();                                                 // エラーメッセージ
		$data['msg_type']	= 'msg-error';                                              // メッセージタイプ
		$data['form_url']	= $this->config->item('base_url').'index.php/s_rireki/search/';   // 送信先
		$data['admin_flg']	= TRUE;                                                 // 管理者フラグ（管理者=TRUE、一般=FALSE）
		$data['btn_name']	= NULL;                                                 // ボタン表示名
		$data['search_flg'] = FALSE;                                                // 検索フラグ
		$data['client_box'] = $this->table_set->set_nego_date($_POST);              // 相手先選択ボックス作成

		// コンテンツ生成
		$this->load->view(MY_VIEW_S_RIREKI, $data, FALSE);

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

}
	/* End of file s_rireki.php */
	/* Location: ./application/controllers/s_rireki.php */
