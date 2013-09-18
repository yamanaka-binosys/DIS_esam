<?php

class Top extends MY_Controller {

	function index() {
		$this->load->model('sgmtb010');
		$this->load->library(array('top_manager'));

		//社員番号
		$shbn = $this->session->userdata('shbn');
		//メニュー区分
		$user_type = $this->sgmtb010->get_user_data($shbn);
		$isUnitLeader = $user_type == '002' || $user_type == '003';

		$data['isUnitLeader'] = $isUnitLeader;
		//トップ画面に表示する各パーツの名前
		$part_names = array(
				'calendar','todo','memo','banner_link','read_report',
				'received_report', 'schedule', 'info');

		$data['read_report_head_item'] = $isUnitLeader ? '担当者名' : '指示者名';

		$parts = array();
		foreach ($part_names as $part_name) {
			if ($part_name == 'schedule' && !$isUnitLeader) {
				continue;
			}
			$get_func_name = 'get_' . $part_name . '_data';
			$data[$part_name . '_data'] = $this->top_manager->$get_func_name($shbn, $isUnitLeader);
			$parts[$part_name] = $this->load->view('/parts/top/_'.$part_name.'.php', $data, true);
		}

		if ($isUnitLeader) {
			$this->load->view('/parts/top/admin_index.php', $parts);
		} else {
			$this->load->view('/parts/top/index.php', $parts);
		}
	}

	function index2()
	{
		try
		{
			// 初期化
			$common_data = $this->config->item('s_top');

			$this->load->library(array('table_set','calendar_manager','common_manager'));
			$this->load->model('sgmtb010');
			$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
			$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
			// 管理者フラグよりトップページ情報取得
			//			if($admin_flg === MY_TYPE_GENERAL){
			//				$data = $this->common_manager->init(SHOW_TOP_GENE);
			//			}else{
			//				$data = $this->common_manager->init(SHOW_TOP_ADMIN);
			//			}
			if($admin_flg === '002' OR $admin_flg === '003'){
				$data = $this->common_manager->init(SHOW_TOP_ADMIN);
			}else{
				$data = $this->common_manager->init(SHOW_TOP_GENE);
			}
			log_message('debug', "admin_flg = " . $admin_flg);
			// 各種テーブル情報作成
			$data['calendar']    = $this->calendar_manager->set_week_calendar($shbn);          // カレンダーテーブル作成
			$data['todo']        = $this->common_manager->set_todo($data['admin_flg'], $shbn); // TODOテーブル作成
			$data['memo']        = $this->common_manager->set_memo($data['admin_flg'], $shbn); // 情報メモテーブル作成
			$data['banner_link'] = $this->common_manager->set_banner_link();                   // バナーリンクテーブル作成
			$data['read_report'] = $this->common_manager->set_read_report($data['admin_flg'], $shbn);            // 日報閲覧状況テーブル作成
			$data['result']      = $this->common_manager->set_result_report($shbn);            // 受取日報テーブル作成
			$data['schedule']    = $this->common_manager->set_schedule($shbn);                 // 部下のスケジュールテーブル作成
			//			$data['info']        = $this->table_set->set_info();                               // インフォメーションテーブル作成
			$data['info']        = $this->common_manager->set_info();                          // インフォメーションテーブル作成

			$this->loadview($data);
			/*

			// ヘッダ部生成 title css image errmsg を使用
			$data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			if($data['admin_flg'] === TRUE)
			{
			// 管理者用トップ画面
			$data['main'] = $this->load->view(MY_VIEW_TOP_ADMIN, $data, TRUE);
			}else{
			// 一般用トップ画面
			$data['main'] = $this->load->view(MY_VIEW_TOP_GENERAL, $data, TRUE);
			}
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu'] = $this->load->view(MY_VIEW_MENU, $data, TRUE);
			// フッダ部生成 必ず指定する事
			$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);

			// 表示処理
			$this->load->view(MY_VIEW_LAYOUT, $data);
			*/
		}catch(Exception $e){
			$this->error_view($e);
			// エラー処理
		}

	}

	function loadview($data){
		//		if($data['admin_flg'] === MY_TYPE_GENERAL){
		// 一般用トップ画面
		//			$this->load->view(MY_VIEW_TOP_GENERAL, $data, FALSE);
		//		}else{
		// 管理者用トップ画面
		//			$this->load->view(MY_VIEW_TOP_ADMIN, $data, FALSE);
		//		}
		if($data['admin_flg'] === '002' OR $data['admin_flg'] === '003'){
			// 管理者用トップ画面
			$this->load->view(MY_VIEW_TOP_ADMIN, $data, FALSE);
		}else{
			// 一般用トップ画面
			$this->load->view(MY_VIEW_TOP_GENERAL, $data, FALSE);
		}
	}

	function update(){
		// 初期化
		$this->load->model('srktb040');
		$data = array();

		// ポストデータ取得
		foreach ($_POST as $key => $value) {
			if (strpos($key, 'todo_check', 0) === 0) {
				$splitted = explode('_', $value, 2);
				array_push($data, array('todo_jyoho' => $splitted[0], 'todo_edbn' => $splitted[1]));
			}
		}
		foreach ($data as $value) {
			$this->srktb040->update_flg($value['todo_jyoho'],$value['todo_edbn']);
		}

		$this->index();
	}

	function error_view($error){
		// 初期化
		$common_data = $this->config->item('s_top');
		$this->load->library(array('table_set','calendar_manager','common_manager'));
		$this->load->model('sgmtb010');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$data['admin_flg'] = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		// セッションに管理者フラグ保存
		$session_data = array('admin_flg' => $data['admin_flg']);
		$this->session->set_userdata($session_data);
		log_message('debug', "admin_flg = " . $data['admin_flg']);
		// ログイン者情報取得
		$heder_auth = $this->common_manager->get_auth_name($shbn);
		$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
		$data['ka_name'] = $heder_auth['ka_name'];
		$data['shinnm'] = $heder_auth['shinnm'];

		$this->load->library('error_set');
		$data['errmsg'] = $this->error_set->get_message(ERROR_SYSTEM,NULL);

		// 初期共通項目情報
		$data['title']       = $common_data['title']; // タイトルに表示する文字列
		$data['css']         = $common_data['css']; // 個別CSSのアドレス
		$data['image']       = $common_data['image']; // タイトルバナーのアドレス
		$data['btn_name']    = $common_data['btn_name']; // エラーメッセージ

		// 各種テーブル情報作成
		$data['calendar']    = NULL;
		$data['todo']        = NULL;
		$data['memo']        = NULL;
		$data['banner_link'] = NULL;
		$data['read_report'] = NULL;
		$data['result']      = NULL;
		$data['schedule']    = NULL;
		$data['info']        = NULL;

		// ヘッダ部生成 title css image errmsg を使用
		$data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
		// メインコンテンツ生成 contents を使用
		if($data['admin_flg'] === TRUE)
		{
			// 管理者用トップ画面
			$data['main'] = $this->load->view(MY_VIEW_TOP_ADMIN, $data, TRUE);
		}else{
			// 一般用トップ画面
			$data['main'] = $this->load->view(MY_VIEW_TOP_GENERAL, $data, TRUE);
		}
		// メニュー部生成 表示しない場合はNULL admin_flg を使用
		$data['menu'] = $this->load->view(MY_VIEW_MENU, $data, TRUE);
		// フッダ部生成 必ず指定する事
		$data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);

		// 表示処理
		$this->load->view(MY_VIEW_LAYOUT, $data);

	}

	/**
	 * debug用関数
	 */
	function debug($obj)
	{
		ob_start();
		var_dump($obj);
		$ret = ob_get_contents();
		ob_end_clean();
		log_message('debug', '**********************');
		log_message('debug', $ret);
		log_message('debug', '**********************');
	}

}

/* End of file top.php */
/* Location: ./application/controllers/top.php */
