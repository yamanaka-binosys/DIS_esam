<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Table_set {
	/**
	 * トップ画面で使用するカレンダーHTMLを作成する
	 *
	 * @access	public
	 * @param	array_string
	 * @return	string
	 */
	public function set_week_calendar()
	{
		$calendar_data = $this->_get_calendar_data(); // カレンダー情報取得
		$calendar_table = $this->_set_calendar_table($calendar_data); // カレンダーテーブル作成
		return $calendar_table;
	}

	/**
	 * 選択月のカレンダーHTMLを作成
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function set_month_calendar($shbn = NULL,$select_month = NULL)
	{
		// 社番有無を確認
		if(is_null($shbn))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 選択月の取得　初期値は当月
		if(is_null($select_month))
		{
			$select_month = date("Ym");
		}
		$select_calendar_date = $this->_get_select_calendar($shbn,$select_month);
		$select_calendar_table = $this->_set_select_calendar($select_calendar_date);
		return $select_calendar_table;
	}

	/**
	 * カレンダー画面上部に表示する前月・選択月・翌月の3か月分の
	 * 年月を表示するHTMLを作成
	 *
	 * @access	public
	 * @param	string
	 * @return	string
	 */
	public function set_select_month($select_month = NULL)
	{
		// 選択月の取得　初期値は当月
		if(is_null($select_month))
		{
			$select_month = date("Ym");
		}
		$select_month_date = $this->_get_three_month($select_month);
		$select_month_table = $this->_set_three_month_table($select_month_date);
		return $select_month_table;
	}

	/**
	 * ToDoテーブルのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_todo($admin_flg = FALSE)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		if($admin_flg === TRUE){
			$table = $CI->config->item('c_admin_todo_table'); // テーブル設定情報取得
		}else{
			$table = $CI->config->item('c_general_todo_table'); // テーブル設定情報取得
		}
		$title = $CI->config->item('c_todo_title'); // タイトル情報取得
		$link  = $CI->config->item('c_todo_link'); // リンク情報取得
		$other = $CI->config->item('c_todo_button'); // 追加情報取得
		$data  = $this->_get_todo_data(); // ToDo情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // ToDoテーブル作成
		return $string_table;
	}

	/**
	 * 情報メモテーブルのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_memo($admin_flg = FALSE)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		if($admin_flg === TRUE){
			$table = $CI->config->item('c_admin_memo_table'); // テーブル設定情報取得
		}else{
			$table = $CI->config->item('c_general_memo_table'); // テーブル設定情報取得
		}
		$title = $CI->config->item('c_memo_title'); // タイトル情報取得
		$link  = $CI->config->item('c_memo_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_memo_data(); // 情報メモ情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 情報メモテーブル作成
		return $string_table;
	}

	/**
	 * ユニット長日報閲覧状況のHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_read_report()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_read_report_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_read_report_title'); // タイトル情報取得
		$link  = $CI->config->item('c_read_report_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_read_report_data(); // ユニット長日報閲覧状況情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // ユニット長日報閲覧状況テーブル作成
		return $string_table;
	}

	/**
	 * 受取日報のHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_result_report()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_result_report_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_result_report_title'); // タイトル情報取得
		$link  = $CI->config->item('c_result_report_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_result_report_data(); // 受取日報情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 受取日報テーブル作成
		return $string_table;
	}

	/**
	 * 部下のスケジュールのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_schedule()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_schedule_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_schedule_title'); // タイトル情報取得
		$link  = $CI->config->item('c_schedule_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_schedule_data(); // 部下のスケジュール情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 部下のスケジュールテーブル作成
		return $string_table;
	}

	/**
	 * InfomaionのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_info()
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->library('top_table_manager');
		// 設定値取得
		$table = $CI->config->item('c_info_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_info_title'); // タイトル情報取得
		$link  = $CI->config->item('c_info_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_info_data(); // Infomaion情報取得
		$string_table = $CI->top_table_manager->tab_list_set($data);
//		$string_table = $this->_set_table($table,$title,$link,$other,$data); // Infomaionテーブル作成
		return $string_table;
	}

	// Table_manager.phpに移動
//	/**
//	 * 商談履歴検索結果情報取得 asakura
//	 *
//	 * @access	public
//	 * @param	nothing
//	 * @return	array_string
//	 */
//	public function set_nego_history_data($form_data,$pagination=null)
//	{
//		// 初期化
//		$CI =& get_instance();
//
//		// 設定値取得
//		$table = $CI->config->item('c_nego_history_table'); // テーブル設定情報取得
//		$title = $CI->config->item('c_nego_history_title'); // タイトル情報取得
//		$link  = $CI->config->item('c_nego_history_link');  // リンク情報取得
//
//		if($pagination !== null){
//
//			$other = array(
//					"td_style" => "text-align:right;",
//					"type" => null,
//					"pagination" => $pagination
//				); // 追加情報取得
//		}
//
//		$data  = $form_data; // 商談履歴検索結果情報取得
//		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 商談履歴検索結果テーブル作成
//		return $string_table;
//	}

	/**
	 * 確認者選択の項目確認者のHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_checker($post = NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_checker_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_checker_title'); // タイトル情報取得
		$link  = $CI->config->item('c_checker_link');  // リンク情報取得
		$other = $CI->config->item('c_select_checker_button');  // 追加情報取得
		$data  = $post; // 確認者情報結果取得
		$string_table = $this->_checker_table($table,$title,$other,$data); // 確認者テーブル作成
		return $string_table;
	}

	public function set_todo_data($data, $type){
		return 1;
	}

	/**
	 * 仮相手先のHTMLを作成 asakura
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_kari_client()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_kari_client_table'); // テーブル設定情報取得

		$title = $CI->config->item('c_kari_client_title'); // タイトル情報取得
		$link  = $CI->config->item('c_kari_client_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_kari_client_data();          // 仮相手先情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 仮相手先情報取得テーブル作成
		return $string_table;
	}

	/**
	 * 仮相手テーブル情報設定asakura
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_kari_client_data($post,$type)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_kari_client_data');                   // テーブル設定情報取得
		$title  = $CI->config->item('c_kari_client_title');                  // 表示項目名
		$other = NULL;

		// 画面の種類毎の読み込み設定
		if($type === "add")
		{
			$td_data[] = $CI->config->item('s_kari_cname_add');           // 仮相手先名・仮相手先コード設定

		}else{
			$td_data[] = $CI->config->item('s_kari_cname_updel');           // 仮相手先名・仮相手先コード設定
			$other['button'] = $CI->config->item('s_kari_client_button'); // ボタン設定情報
		}
		$td_data[] = $CI->config->item('s_kari_kana');            // 仮相手先名設定
		$td_data[] = $CI->config->item('s_edit_no');              // 登録者番設定
		$td_data[] = $CI->config->item('s_address');              // 住所設定
		$td_data[] = $CI->config->item('s_tel');                  // 電話設定
		$td_data[] = $CI->config->item('s_fax');                  // FAX設定
		$td_data[] = $CI->config->item('s_client_busyo');         // 相手先担当部署設定
		$td_data[] = $CI->config->item('s_client_name');          // 相手先担当者名設定
		$td_data[] = $CI->config->item('s_kbn');                  // 区分設定
		$kbn_data[MY_KARI_KBN_NO] = $CI->config->item('s_drop_kari_kbn');       // 区分ドロップダウンメニュー設定
		$td_data[] = $CI->config->item('s_important');            // 重要度設定

		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other,$kbn_data); // 仮相手先情報テーブル作成

		return $string_table;
	}

	/**
	 * メッセージasakura
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_allmessage_data($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_allmessage_data');                   // テーブル設定情報取得
		$title  = $CI->config->item('c_allmessage_title');                  // 表示項目名
		$other = NULL;

		// 画面の種類毎の読み込み設定
		$td_data[] = $CI->config->item('c_s_date');             // 通知開始日 当システム全員
		$td_data[] = $CI->config->item('c_e_date');             // 通知終了日
		$td_data[] = $CI->config->item('c_comment');          // コメント
		$td_data[] = $CI->config->item('c_file');          // 添付ファイル

		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); // 仮相手先情報テーブル作成

		return $string_table;
	}

	/**
	 * 情報メモテーブル(添付ファイル)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
/*
	public function set_data_memo_file($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		//$table  = $CI->config->item('c_user_data');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_allmessage_file_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_allmessage_file');                             // 添付ファイルメニュー設定
		$string_table = $this->_set_type_files($title,$td_data); // 情報メモ添付ファイルテーブル作成

		return $string_table;
	}
*/
	/****
	 * INPUT【TYPE=FILE】の作成
	 */
/*
	function _set_type_files($title,$td_data)
	{
		$table_string = "<table>";
		$table_string .= "<tr><td>".$title[0]."</td><td>";
		$table_string .= "<input type='".$td_data[0]["td_form_type"][0]."' name='".$td_data[0]["td_form_name"][0]."' />";
		$table_string .= "</td></tr>";
		$table_string .= "</table>";
		return $table_string;
	}
*/

	/**
	 * 情報メモテーブル(添付ファイル)情報設定 asakura 3
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_allmessage_check($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		//$table  = $CI->config->item('c_user_data');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_allmessage_sys_all_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_sys_all');                             // 添付ファイルメニュー設定
		$string_table = $this->_set_type_check($title,$td_data,$post); // 情報メモ添付ファイルテーブル作成

		return $string_table;

	}

		/****
	 * INPUT【TYPE=FILE】の作成 asakura 3
	 */
	function _set_type_check($title,$td_data,$post)
	{
		if(isset($post["sys_all"]) && $post["sys_all"] == "on"){
			$checked = "checked";
		} else {
			$checked = "";
		}
		$table_string = "<table>";
		$table_string .= "<tr><td>";
		$table_string .= "<input type='".$td_data[0]["td_form_type"][0]."' name='".$td_data[0]["td_form_name"][0]."' $checked />";
		$table_string .= $title[0]."</td></tr>";
		$table_string .= "</table>";
		return $table_string;
	}

	/**
	 * 情報メモテーブル(添付ファイル)情報設定 asakura 3
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_allmessage_file($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		//$table  = $CI->config->item('c_user_data');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_allmessage_file_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_allmessage_file');                             // 添付ファイルメニュー設定
		$string_table = $this->_set_type_allmessage_files($title,$td_data); // 情報メモ添付ファイルテーブル作成

		return $string_table;
	}

	/****
	 * INPUT【TYPE=FILE】の作成 asakura 3
	 */
	function _set_type_allmessage_files($title,$td_data)
	{
		$table_string = "<table>";
		$table_string .= "<tr><td style=\"width:90px;\">".$title[0]."</td><td>";
		$table_string .= "<input type=\"".$td_data[0]["td_form_type"][0]."\" name=\"".$td_data[0]["td_form_name"][0]."\" size=\"50\" maxlength=\"256\" />";
		$table_string .= "</td></tr>";
		$table_string .= "</table>";
		return $table_string;
	}

	/**
	 * カレンダー情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array_string
	 */
	private function _get_calendar_data()
	{
		// 初期化
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		$week_ja = $CI->config->item('c_day_week_ja'); // 曜日フォーマット取得

		// 表示日数分の情報を取得し、格納
		for($i=0; $i < $CI->config->item('c_top_calendar_day'); $i++)
		{
			// 月日、曜日を取得し設定
			$day_week[$i]['0'] = date("n月j日", mktime(0, 0, 0, date("m") , date("d")+$i, date("Y"))); // 日付取得
			$day_week[$i]['1'] = $week_ja[date("w", mktime(0, 0, 0, date("m") , date("d")+$i, date("Y")))]; // 曜日取得（日本語）
			$day_week[$i]['2'] = strtolower(date("l", mktime(0, 0, 0, date("m") , date("d")+$i, date("Y")))); // 曜日取得（英語）

			// 行動予定から対象日付の情報を取得し、配列に時間・タイトルを設定
			// DBから取得した値の設定（未完成）
			$day_week[$i]['3'] = "HH:MM&emsp;GGGGGGGG";
			$day_week[$i]['4'] = "HH:MM&emsp;GGGGGGGG";
			$day_week[$i]['5'] = "HH:MM&emsp;GGGGGGGG";
			$day_week[$i]['6'] = "";
			$day_week[$i]['7'] = "";
		}
		return $day_week;
	}

	/**
	 * 前月・選択月・翌月の3ヶ月分の年月を取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_three_month($select_month)
	{
		log_message('debug',"========== Table_set _get_three_month start ==========");
		log_message('debug',"\$select_month = $select_month");
		$year = substr($select_month,0,4);
		log_message('debug',"\$year = $year");
		$month = substr($select_month,4,2);
		log_message('debug',"\$month = $month");

		// 前月の年と月を取得
		$select_month_date['previous_year'] = date("Y", mktime(0, 0, 0, $month -1 , 1, $year));
		$select_month_date['previous_month'] = date("m", mktime(0, 0, 0, $month -1 , 1, $year));
		log_message('debug',"previous_year = " . $select_month_date['previous_year']);
		log_message('debug',"previous_month = " . $select_month_date['previous_month']);
		// 選択月の年と月を取得
		$select_month_date['select_year'] = date("Y", mktime(0, 0, 0, (int)$month, 1, (int)$year));
		$select_month_date['select_month'] = date("m", mktime(0, 0, 0, (int)$month, 1, (int)$year));
		log_message('debug',"select_year = " . $select_month_date['select_year']);
		log_message('debug',"select_month = " . $select_month_date['select_month']);
		// 翌月の年と月を取得
		$select_month_date['next_year'] = date("Y", mktime(0, 0, 0, $month +1 , 1, $year));
		$select_month_date['next_month'] = date("m", mktime(0, 0, 0, $month +1 , 1, $year));
		log_message('debug',"next_year = " . $select_month_date['next_year']);
		log_message('debug',"next_month = " . $select_month_date['next_month']);
		log_message('debug',"========== Table_set _get_three_month end ==========");

		return $select_month_date;
	}

	/**
	 * 選択された月の情報（日付・曜日・最終日等）を取得
	 *
	 * @access	private
	 * @param	string
	 * @return	array
	 */
	function _get_select_calendar($shbn,$select_month)
	{
		log_message('debug',"========== Table_set _get_select_calendar start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_month = $select_month");
		// 初期化
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		//$week_ja = $CI->config->item('c_day_week_ja'); // 曜日フォーマット取得

		// 選択年月を年、月に分ける
		$year = substr($select_month,0,4);
		log_message('debug',"\$year = $year");
		$month = substr($select_month,4,2);
		log_message('debug',"\$month = $month");
		// 選択年月の最終日（その月の日数）を取得
		$last_day = date("t", mktime(0, 0, 0, (int)$month, MY_START_DAY, (int)$year));
		log_message('debug',"\$last_day = $last_day");
		// 現在の年月日を取得
		$present_year_month = date("Ym");
		$present_year = date("Y");
		$present_month = date("m"); // ゼロパディングした月
//		$present_day = date("j"); // ゼロサプレスした日付
		$present_day = date("d"); // ゼロパディングした日付
		log_message('debug',"\$present_year_month = $present_year_month");
		log_message('debug',"\$present_year = $present_year");
		log_message('debug',"\$present_month = $present_month");
		log_message('debug',"\$present_day = $present_day");
		// 選択年月が現在の年月と同じかどうか判定
		if($present_year_month === $select_month)
		{
			// 同年同月の場合
			// 現在日付より過去の日付の日報情報を取得
			$this->_get_result_data($year,$month,MY_START_DAY,$present_day);
			// 現在日付より未来の日付の予定情報を取得（同日を含む）
			$this->_get_plan_data($year,$month,$present_day,$last_day);
		}else if($present_year_month > $select_month){
			// 選択年月が過去年月の場合
			// 対象月の日報情報を取得
			$this->_get_result_data($year,$month,MY_START_DAY,$last_day);
		}else if($present_year_month < $select_month){
			// 選択年月が未来年月の場合
			// 対象月の予定を取得
			$this->_get_plan_data($year,$month,MY_START_DAY,$last_day);
		}else{
			// エラー処理
			throw new Exception("Error Processing Request", '');
		}
/*
		$day_week[$i]['0'] = date("n月j日", mktime(0, 0, 0, date("m") , date("d")+$i, date("Y"))); // 日付取得
		$day_week[$i]['1'] = $week_ja[date("w", mktime(0, 0, 0, date("m") , date("d")+$i, date("Y")))]; // 曜日取得（日本語）
		$day_week[$i]['2'] = strtolower(date("l", mktime(0, 0, 0, date("m") , date("d")+$i, date("Y")))); // 曜日取得（英語）
*/


		log_message('debug',"========== Table_set _get_select_calendar end ==========");
	}

	/**
	 * 指定された開始日～終了日までの日報情報を取得
	 * (日付・曜日・日報情報を取得)
	 *
	 * @access	private
	 * @param	string $year 指定年
	 * @param	string $month 指定月
	 * @param	string $start_day 指定開始日
	 * @param	string $end_day 指定終了日
	 * @return	array
	 */
	function _get_result_data($year,$month,$start_day,$end_day)
	{
		// 初期化
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		//$week_ja = $CI->config->item('c_day_week_ja'); // 曜日フォーマット取得


	}

	/**
	 * 指定された開始日～終了日までの予定を取得
	 * (日付・曜日・予定を取得)
	 *
	 * @access	private
	 * @param	string $year 指定年
	 * @param	string $month 指定月
	 * @param	string $start_day 指定開始日
	 * @param	string $end_day 指定終了日
	 * @return	array
	 */
	function _get_plan_data($year,$month,$start_day,$end_day)
	{

	}

	/**
	 * ToDo情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_todo_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		$todo_data = NULL;
		// データ取得
		// 未完成
		/* テストデータ START */
		$todo_data[0][0] = '';
		$todo_data[0][1] = '';
		$todo_data[0][2] = '';
		$todo_data[0][3] = '';

/*
		$todo_data[1][0] = '11/10';
		$todo_data[1][1] = '月次商談資料';
		$todo_data[1][2] = '低';
		$todo_data[1][3] = '';

		$todo_data[2][0] = '11/11';
		$todo_data[2][1] = '商品案内';
		$todo_data[2][2] = '中';
		$todo_data[2][3] = '';

		$todo_data[3][0] = '11/13';
		$todo_data[3][1] = '商品案内';
		$todo_data[3][2] = '中';
		$todo_data[3][3] = '';
*/
		/* テストデータ END */

		return $todo_data;
	}

	/**
	 * 情報メモ情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_memo_data()
	{
		$CI =& get_instance();
//		$CI->load->model('srktb050');

//		$db_data = $CI->srktb050->get_srktb050_data();

//		if (is_null($db_data)) {
//			$memo_data[0][0] = '';
//			$memo_data[0][1] = '';
//			$memo_data[0][2] = '';
//		}else{
//			foreach ($db_data as $key => $value) {
//
//			}
//		}


		// データ取得
		// 未完成
		/* テストデータ START */
		$memo_data[0][0] = '';
		$memo_data[0][1] = '';
		$memo_data[0][2] = '';
/*
		$memo_data[0][0] = '11/09';
		$memo_data[0][1] = '月次商談資料';
		$memo_data[0][2] = 'Ａドラッグ';

		$memo_data[1][0] = '11/10';
		$memo_data[1][1] = '月次商談資料';
		$memo_data[1][2] = 'Ｂドラッグ';

		$memo_data[2][0] = '11/11';
		$memo_data[2][1] = '商品案内';
		$memo_data[2][2] = 'スーパーＣ';

		$memo_data[3][0] = '11/13';
		$memo_data[3][1] = '商品案内';
		$memo_data[3][2] = 'ホームセンターＥ';

		$memo_data[4][0] = '11/15';
		$memo_data[4][1] = '商品案内';
		$memo_data[4][2] = 'ホームセンターＤ';
*/
		/* テストデータ END */

		return $memo_data;
	}

	/**
	 * ユニット長日報閲覧状況情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_read_report_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');

		// データ取得
		// 未完成
		/* テストデータ START */
		$read_report_data[0][0] = '';
		$read_report_data[0][1] = '';
		$read_report_data[0][2] = '';
		$read_report_data[0][3] = '';

/*
		$read_report_data[0][0] = '11/09';
		$read_report_data[0][1] = '●●課長';
		$read_report_data[0][2] = '既読';
		$read_report_data[0][3] = 'あり';

		$read_report_data[1][0] = '11/10';
		$read_report_data[1][1] = '●●課長';
		$read_report_data[1][2] = '未読';
		$read_report_data[1][3] = 'あり';

		$read_report_data[2][0] = '11/11';
		$read_report_data[2][1] = '●●課長';
		$read_report_data[2][2] = '未読';
		$read_report_data[2][3] = 'なし';
*/
		/* テストデータ END */

		return $read_report_data;
	}

	/**
	 * 受取日報情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_result_report_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');

		// データ取得
		// 未完成
		/* テストデータ START */
		$result_report_data[0][0] = '';
		$result_report_data[0][1] = '';
		$result_report_data[0][2] = '';
		$result_report_data[0][3] = '';
/*
		$result_report_data[0][0] = '11/09';
		$result_report_data[0][1] = '○○ ○○';
		$result_report_data[0][2] = '既読';
		$result_report_data[0][3] = 'あり';

		$result_report_data[1][0] = '11/19';
		$result_report_data[1][1] = '●● ○○';
		$result_report_data[1][2] = '未読';
		$result_report_data[1][3] = 'あり';
*/
		/* テストデータ END */

		return $result_report_data;
	}

	/**
	 * 部下のスケジュール情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_schedule_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');

		// データ取得
		// 未完成
		/* テストデータ START */
		$schedule_data[0][0] = '';
		$schedule_data[0][1] = '';
		$schedule_data[0][2] = '';
/*
		$schedule_data[0][0] = '12:00';
		$schedule_data[0][1] = '○○ ○○';
		$schedule_data[0][2] = 'Ａドラッグ';

		$schedule_data[1][0] = '12:00';
		$schedule_data[1][1] = '○○ ○○';
		$schedule_data[1][2] = 'Ａドラッグ';

		$schedule_data[2][0] = '12:00';
		$schedule_data[2][1] = '○○ ○○';
		$schedule_data[2][2] = 'Ａドラッグ';

		$schedule_data[3][0] = '12:00';
		$schedule_data[3][1] = '○○ ○○';
		$schedule_data[3][2] = 'Ａドラッグ';

		$schedule_data[4][0] = '12:00';
		$schedule_data[4][1] = '○○ ○○';
		$schedule_data[4][2] = 'Ａドラッグ';

		$schedule_data[5][0] = '12:00';
		$schedule_data[5][1] = '○○ ○○';
		$schedule_data[5][2] = 'Ａドラッグ';

		$schedule_data[6][0] = '12:00';
		$schedule_data[6][1] = '○○ ○○';
		$schedule_data[6][2] = 'Ａドラッグ';

		$schedule_data[7][0] = '12:00';
		$schedule_data[7][1] = '○○ ○○';
		$schedule_data[7][2] = 'Ａドラッグ';

		$schedule_data[8][0] = '12:00';
		$schedule_data[8][1] = '○○ ○○';
		$schedule_data[8][2] = 'Ａドラッグ';
*/
		/* テストデータ END */

		return $schedule_data;
	}

	/**
	 * Infomaion情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_info_data()
	{
		$CI =& get_instance();
		$CI->load->model('srktb060');
		$today = date("Ymd");
		$count = 0;
		$info_data[0][0] = ''; // 初期化
		$get_data = $CI->srktb060->get_info_data();

		if (is_null($get_data)) {
			$info_data[0]['jyohoniyo'] = '';
			$info_data[0]['link'] = '';
		}else{
			foreach ($get_data as $key => $value) {
				if ($value['tuchistartdate'] <= $today AND $today <= $value['tuchienddate']) {
//					if ($value['allflg'] === TRUE) {
						$info_data[$count]['jyohoniyo'] = $value['jyohoniyo'];
						$info_data[$count]['link'] = $value['tempfile'];
						$count++;
//					}
				}
			}
		}

		return $info_data;
	}

	/**
	 * 商談履歴検索結果情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
//	function _get_nego_history_data($data)
	function _get_nego_history_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');

		$search_data = array ();

		// 商談日（開始）
		if( isset($data['s_year']) && $data['s_year'] != ''){
			$search_data['start_date'] = $data['s_year'] . '-' . $data['s_month'] . '-' . $data['s_day'];
		} else {
			// 年がない場合は、月日無視
			if(isset($data['s_month'])) unset($data['s_month']);
			if(isset($data['s_day'])) unset($data['s_day']);
		}

		// 商談日（終了）
		if( isset($data['e_year']) && $data['e_year'] != ''){
			$search_data['end_date'] = $data['e_year'] . '-' . $data['e_month'] . '-' . $data['e_day'];
		} else {
			// 年がない場合は、月日無視
			if(isset($data['e_month'])) unset($data['e_month']);
			if(isset($data['e_day'])) unset($data['e_day']);
		}

		// 販売店本部
		if( isset($data['shop_main']) && $data['shop_main'] != ''){
			$search_data['shop_main'] = $data['shop_main'];
		}

		// 代理店
		if( isset($data['agency']) && $data['agency'] != ''){
			$search_data['agency'] = $data['agency'];
		}

		// 商談内容
		if( isset($data['nego_contents']) && $data['nego_contents'] != ''){
			$search_data['nego_contents'] = $data['nego_contents'];
		}

		// 検索実行 searchという関数は作っていません TODO
		//$nego_history_data = search($search_data);

		// データ取得
		// 未完成
		/* テストデータ START */
		$nego_history_data[0][0] = '2011/12/16';
		$nego_history_data[0][1] = 'S';
		$nego_history_data[0][2] = '002';
		$nego_history_data[0][3] = '取引先ＡＡＡ';
		$nego_history_data[0][4] = '○○○　○○';
		$nego_history_data[0][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[1][0] = '2011/12/16';
		$nego_history_data[1][1] = 'S';
		$nego_history_data[1][2] = '002';
		$nego_history_data[1][3] = '取引先ＡＡＡ';
		$nego_history_data[1][4] = '○○○　○○';
		$nego_history_data[1][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[2][0] = '2011/12/16';
		$nego_history_data[2][1] = 'S';
		$nego_history_data[2][2] = '002';
		$nego_history_data[2][3] = '取引先ＡＡＡ';
		$nego_history_data[2][4] = '○○○　○○';
		$nego_history_data[2][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[3][0] = '2011/12/16';
		$nego_history_data[3][1] = 'S';
		$nego_history_data[3][2] = '002';
		$nego_history_data[3][3] = '取引先ＡＡＡ';
		$nego_history_data[3][4] = '○○○　○○';
		$nego_history_data[3][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[4][0] = '2011/12/16';
		$nego_history_data[4][1] = 'S';
		$nego_history_data[4][2] = '002';
		$nego_history_data[4][3] = '取引先ＡＡＡ';
		$nego_history_data[4][4] = '○○○　○○';
		$nego_history_data[4][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[5][0] = '2011/12/16';
		$nego_history_data[5][1] = 'S';
		$nego_history_data[5][2] = '002';
		$nego_history_data[5][3] = '取引先ＡＡＡ';
		$nego_history_data[5][4] = '○○○　○○';
		$nego_history_data[5][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[6][0] = '2011/12/16';
		$nego_history_data[6][1] = 'S';
		$nego_history_data[6][2] = '002';
		$nego_history_data[6][3] = '取引先ＡＡＡ';
		$nego_history_data[6][4] = '○○○　○○';
		$nego_history_data[6][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[7][0] = '2011/12/16';
		$nego_history_data[7][1] = 'S';
		$nego_history_data[7][2] = '002';
		$nego_history_data[7][3] = '取引先ＡＡＡ';
		$nego_history_data[7][4] = '○○○　○○';
		$nego_history_data[7][5] = '△△△△△△△△△△△△△△△△△△△△';

		$nego_history_data[8][0] = '2011/12/16';
		$nego_history_data[8][1] = 'S';
		$nego_history_data[8][2] = '002';
		$nego_history_data[8][3] = '取引先ＡＡＡ';
		$nego_history_data[8][4] = '○○○　○○';
		$nego_history_data[8][5] = '△△△△△△△△△△△△△△△△△△△△';
		/* テストデータ END */

		return $nego_history_data;
	}

	/**
	 * カレンダーテーブルのHTMLを作成
	 *
	 * @access	private
	 * @param	array_string
	 * @return	string
	 */
	private function _set_calendar_table($calendar_data)
	{
		// 初期化
		$CI =& get_instance();
		$table_set = $CI->config->item('s_top_calendar');
		$calendar_color = $CI->config->item('s_calendar_color');

		// カレンダーHTML作成
		$day_table = "";
		$day_table .= "<table style=\"border-collapse:" . $table_set['collapse'] . "\">\n";
		$day_table .= "<tr style=\"height:" . $table_set['font-size'] . "\">\n";
		$day_table .= "<th style=\"" . $table_set['th_align'] . "\">\n";
		$day_table .= "<a href=\"" . $CI->config->item('base_url') . $table_set['a_href'] . "\"";
		$day_table .= " style=\"border:" . $table_set['border'] . ";";
		$day_table .= " font-size:" . $table_set['font-size'] . ";";
		$day_table .= " text-decoration:" . $table_set['decoration'] . ";";
		$day_table .= " color:" . $table_set['a_color'] . ";\">";
		$day_table .= $table_set['title'] . "</a>\n</th>\n</tr>\n";
		$day_table .= "<tr style=\"background-color:" . $table_set['tr_bak_color'] . "\">\n";
		// カレンダーヘッダ作成
		for($i=0; $i < $CI->config->item('c_top_calendar_day'); $i++)
		{
			$day_table .= "<th style=\"border-collapse:" . $table_set['collapse'] . ";";
			$day_table .= " border:" . $table_set['border'] . ";";
			$day_table .= " width:" . $table_set['th_width'] . "\">\n";
			$day_table .= "<a href=\"" . $CI->config->item('base_url') . $table_set['a_href'] . "\"";
			$day_table .= " style=\"color:" . $calendar_color[$calendar_data[$i]['2']] . ";";
			$day_table .= " text-decoration:" . $table_set['decoration'] . "\">";
			$day_table .= $calendar_data[$i]['0'] . "(" . $calendar_data[$i]['1'] . ")</a>\n</th>\n";
		}
		$day_table .= "</tr>\n<tr>\n";
		// カレンダー内容作成
		for($i=0; $i < $CI->config->item('c_top_calendar_day'); $i++)
		{
			$day_table .= "<td style=\"border-collapse:" . $table_set['collapse'] . ";";
			$day_table .= " border:" . $table_set['border'] . ";";
			$day_table .= " background-color:" . $table_set['td_bak_color'] . "\">\n";
			// 未完成
			$day_table .= $calendar_data[$i]['3'] . "<br>\n";
			$day_table .= $calendar_data[$i]['4'] . "<br>\n";
			$day_table .= $calendar_data[$i]['5'] . "<br>\n";
			$day_table .= $calendar_data[$i]['6'] . "<br>\n";
			$day_table .= $calendar_data[$i]['7'] . "<br>\n";
		}
		$day_table .= "</td>\n</tr>\n</table>\n";

		return $day_table;
	}

	/**
	 * 選択月・翌月・先月のリンクHTMLを作成
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	private function _set_three_month_table($select_month_date)
	{
		// 初期化
		$CI =& get_instance();
		$select_month_status = $CI->config->item('s_select_month');

		$select_month_table = "";
		$select_month_table .= "<table>\n";
		$select_month_table .= "<tr>\n";
		$select_month_table .= "<td>\n";
		$select_month_table .= "<input type=\"submit\" name=\"year_month\"";
		$select_month_table .= " style=\"height:" . $select_month_status['button_height'] . "; width:" . $select_month_status['button_width'] . "\"";
		$select_month_table .= " value=\"" . $select_month_date['previous_year'] . "年 " . $select_month_date['previous_month'] . "月";
		$select_month_table .= "\">\n";
		$select_month_table .= "</td>\n";
		$select_month_table .= "<td>\n";
		$select_month_table .= "<input type=\"submit\" name=\"year_month\"";
		$select_month_table .= " style=\"height:" . $select_month_status['button_height'] . "; width:" . $select_month_status['button_width'] . "\"";
		$select_month_table .= " value=\"" . $select_month_date['select_year'] . "年 " . $select_month_date['select_month'] . "月";
		$select_month_table .= "\">\n";
		$select_month_table .= "</td>\n";
		$select_month_table .= "<td>\n";
		$select_month_table .= "<input type=\"submit\" name=\"year_month\"";
		$select_month_table .= " style=\"height:" . $select_month_status['button_height'] . "; width:" . $select_month_status['button_width'] . "\"";
		$select_month_table .= " value=\"" . $select_month_date['next_year'] . "年 " . $select_month_date['next_month'] . "月";
		$select_month_table .= "\">\n";
		$select_month_table .= "</td>\n";
		$select_month_table .= "</tr>\n";

		return $select_month_table;
	}

	/**
	 * バナーリンクのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_banner_link()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$CI->load->helper('html');
		$data = $CI->config->item('c_banner_link_data'); // バナーリンク設定情報取得

		// テーブル作成
		$string_table = "";
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($data['table_width']))
		{
			$string_table .= " width=\"" . $data['table_width'] . "\";";
		}
		// テーブル高さ設定
		if( ! is_null($data['table_height']))
		{
			$string_table .= " height=\"" . $data['table_height'] . "\";";
		}
		$string_table .= ">\n";
		// 見出し行高さ設定
		if( ! is_null($data['heading_tr_height']))
		{
			$string_table .= "<tr style=\"height:" . $data['heading_tr_height'] . "\">\n";
		}else{
			$string_table .= "<tr>\n";
		}
		// 見出し行横位置設定
		if( ! is_null($data['heading_th_style']))
		{
			$string_table .= "<th style=\"" . $data['heading_th_style'] . "\">\n";
		}else{
			$string_table .= "<th>\n";
		}
		// 見出し表示内容設定
		$string_table .= "<a>" . $data['heading'] . "</a>\n";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		$string_table .= "<tr>\n";
		// リンクスタイル設定
		$string_style = NULL;
		if( ! is_null($data['td_padding_left']))
		{
			$string_style .= " padding-left:" . $data['td_padding_left'] . ";";
		}
		if( ! is_null($data['td_border_collapse']))
		{
			$string_style .= " border-collapse:" . $data['td_border_collapse'] . ";";
		}
		if( ! is_null($string_style))
		{
			$string_table .= "<td style=\"" . $string_style . "\">\n";
		}else{
			$string_table .= "<td>\n";
		}
		// バナーリンク設定
		$string_table .= "<table>\n";
		// DC-NET
		$string_table .= "<tr style=\"" . $data['link_tr_height'] . "\">\n";
		$string_table .= "<th><a href=\"" . $data['link_a_dcnet'] . "\">\n";
		$string_table .= img($data['link_img_dcnet']);
		$string_table .= "</a>";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		// OUTLOOK
		$string_table .= "<tr style=\"" . $data['link_tr_height'] . "\">\n";
		$string_table .= "<th><a href=\"" . $data['link_a_outlook'] . "\">\n";
		$string_table .= img($data['link_img_outlook']);
		$string_table .= "</a>";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		// SFA
		$string_table .= "<tr style=\"" . $data['link_tr_height'] . "\">\n";
		$string_table .= "<th><a href=\"" . $data['link_a_sfa'] . "\">\n";
		$string_table .= img($data['link_img_sfa']);
		$string_table .= "</a>";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";
		$string_table .= "</td>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";

		return $string_table;
	}

	/**
	 * テーブル情報設定
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
//	function _set_table($table_data)
	function _set_table($table,$title,$link,$other,$data)
	{

		// 初期化
		$CI =& get_instance();

		// テーブル作成
		$string_table = "";
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($table['table_width']))
		{
			$string_table .= " width=\"" . $table['table_width'] . "\";";
		}
		// テーブル高さ設定
		if( ! is_null($table['table_height']))
		{
			$string_table .= " height=\"" . $table['table_height'] . "\";";
		}
		$string_table .= ">\n";

		// 見出し行有無判定
		if( ! is_null($table['heading']))
		{

			// 見出し行高さ設定
			if( ! is_null($table['heading_tr_height']))
			{
				$string_table .= "<tr style=\"height:" . $table['heading_tr_height'] . "\">\n";
			}else{
				$string_table .= "<tr>\n";
			}
			// 見出し行横位置設定
			if( ! is_null($table['heading_th_style']))
			{
				$string_table .= "<th style=\"" . $table['heading_th_style'] . "\">\n";
			}else{
				$string_table .= "<th>\n";
			}
			// 見出し行リンク設定
			$string_table .= "<a";
			if( ! is_null($link['heading_link']))
			{
				$string_table .= " href=\"" . $link['heading_link'] . "\"";
			}

			// 見出し行スタイル設定
			$string_style = "";
			if( ! is_null($table['a_style_border']))
			{
				$string_style .= "border:" . $table['a_style_border'] . ";";
			}
			if( ! is_null($table['a_style_font_size']))
			{
				$string_style .= "font-size:" . $table['a_style_font_size'] . ";";
			}
			if( ! is_null($table['a_style_decoration']))
			{
				$string_style .= "text-decoration:" . $table['a_style_decoration'] . ";";
			}
			if( ! is_null($table['a_style_color']))
			{
				$string_style .= "color:" . $table['a_style_color'] . ";";
			}
			// style設定判定
			if( ! is_null($string_style))
			{
				$string_table .= " style=\"" . $string_style . "\">";
			}else{
				$string_table .= ">";
			}
			// 見出し表示内容設定
			if( ! is_null($table['heading']))
			{
				$string_table .= $table['heading'] . "</a>\n";
			}else{
				/* エラー処理 今後追加 */
				$string_table .= "</a>\n";
			}
			$string_table .= "</th>\n";
			// 追加情報有無判定
			if( ! is_null($other))
			{
				// スタイル設定
				if( ! is_null($other['td_style']))
				{
					$string_table .= "<td style=\"" . $other['td_style'] . "\">\n";
				}else{
					$string_table .= "<td>\n";
				}
				// type判定
				if( ! is_null($other['type']))
				{
					$string_table .= "<input type=\"" . $other['type'] . "\"";
					// style判定
					$string_style = "";
					if( ! is_null($other['style_height']))
					{
						$string_style .= " height:" . $other['style_height'] . ";";
					}
					if( ! is_null($other['style_font_size']))
					{
						$string_style .= " font-size:" . $other['style_font_size'] . ";";
					}
					if( ! is_null($string_style))
					{
						$string_table .= " style=\"" . $string_style . "\"";
					}
					// value判定
					if( ! is_null($other['value']))
					{
						$string_table .= " value=\"" . $other['value'] . "\"";
					}
					$string_table .= ">\n";
				}

				// ページネーション追加 2012/02/01　asakura
				if( isset($other['pagination'])) {
					$string_table .= $other['pagination'];
				}


				$string_table .= "</td>\n";
			}
			$string_table .= "</tr>\n";
		}
		// title設定
		$string_table .= "<tr>\n";
		$string_table .= "<td style=\"padding-left:" . $table['td_padding_left'] . ";";
		// collapse設定
		if( ! is_null($table['td_border_collapse']))
		{
			$string_table .= " border-collapse:" . $table['td_border_collapse'] . ";";
		}
		// border設定
		if( ! is_null($table['td_border']))
		{
			$string_table .= " border:" . $table['td_border'] . ";";
		}
		$string_table .= "\"";
		// 追加情報有無判定
		if($table['td_colspan'] === MY_COLSPAN_EXISTENCE)
		{
			$string_table .= ">\n";
		}else{
			$string_table .= " colspan=\"" . $table['td_colspan'] . "\">\n";
		}
		$string_table .= "<table>\n";
		$string_table .= "<tr>\n";

		for($i=0; $i < $table['span']; $i++)
		{
			$string_table .= "<th";
			// 下線設定
			$string_style = "";
			if( ! is_null($table['title_th_border_bottom']))
			{
				$string_style .= " border-bottom:" . $table['title_th_border_bottom'] . ";";
			}
			// 高さ設定
			if( ! is_null($table['title_th_height']))
			{
				$string_style .= " height:" . $table['title_th_height'] . ";";
			}
			// 幅設定
			if( ! is_null($table['title_th_width']))
			{
				$string_style .= " width:" . $table['title_th_width'][$i] . ";";
			}
			// 背景色設定
			if( ! is_null($table['title_th_bakcolor']))
			{
				$string_style .= " background-color:" . $table['title_th_bakcolor'] . ";";
			}
			// 上部余白設定
			if( ! is_null($table['title_th_padding_top']))
			{
				$string_style .= " padding-top:" . $table['title_th_padding_top'] . ";";
			}
			if( ! is_null($string_table))
			{
				$string_table .= " style=\"" . $string_style . "\"";
			}
			$string_table .= ">";
			$string_table .= $title[$i];
			$string_table .= "</th>\n";
		}
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";
		// divタグ設定有無判定
		if($table['div_existence'] === TRUE)
		{
			$string_table .= "<div style=\"margin:" . $table['div_style_margin'] . ";";
			$string_table .= " height:" . $table['div_style_height'] . ";";
			$string_table .= " width:" . $table['div_style_width'] . ";";
			$string_table .= " overflow:" . $table['div_style_overflow'] . ";";
			$string_table .= " background-color:" . $table['div_style_bakcolor'] . ";";
			$string_table .= "\">\n";
		}
		// テーブル内容設定
		$string_table .= "<table>\n";
		foreach($data as $key => $value)
		{
			$string_table .= "<tr>\n";
			for($i=0; $i < $table['span']; $i++)
			{
				$string_style = "";
				// 高さ設定
				if( ! is_null($table['div_td_height']))
				{
					$string_style .= " height:" . $table['div_td_height'] . ";";
				}
				// 幅設定
				if( ! is_null($table['div_td_width'][$i]))
				{
					$string_style .= " width:" . $table['div_td_width'][$i] . ";";
				}

				if(! is_null($string_style))
				{
					$string_style .= "\"";
				}

				// 位置設定
				if( ! is_null($table['div_td_align'][$i]))
				{
					$string_style .= " align=\"" . $table['div_td_align'][$i];
				}
				// style設定有無
				if( ! is_null($string_style))
				{
					$string_table .= "<td style=\"" . $string_style . "\">";
				}else{
					$string_table .= "<td>";
				}
				// "a"タグ判定
				if($table['div_td_a_existence'][$i] === TRUE)
				{
					$href = '';
					if(array_key_exists('link_id', $link)){
						$href = $CI->config->item('base_url') . $link['link'] . $value[$link['link_id']];
					} else {
						$href = $CI->config->item('base_url') . $link['link'];
					}

					$string_table .= "<a href=\"" . $href . "\">";
					$string_table .= $value[$i];
					$string_table .= "</a></td>\n";
				}else{
					// inputtype判定
					if( ! is_null($table['div_td_input_type'][$i]))
					{
						$string_table .= "<input type=\"" . $table['div_td_input_type'][$i] . "\">";
					}
					$string_table .= $value[$i];
					$string_table .= "</td>\n";
				}
			}
			$string_table .= "</tr>\n";
		}
		$string_table .= "</table>\n";
		$string_table .= "</div>\n";
		$string_table .= "</td>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";

		return $string_table;
	}

	/**
	 * 商談履歴商談日テーブル情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_nego_date($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_nego_search_data');                          // テーブル設定情報取得
		$title  = $CI->config->item('c_nego_search_title');                         // 表示項目名
		$td_data[] = $CI->config->item('c_nego_search_date');                       // 商談日設定
		$td_data[] = $CI->config->item('c_nego_search_client');                     // 相手先設定
		$td_data[] = $CI->config->item('c_nego_search_contents');                   // 商談内容設定
		$td_data[] = $CI->config->item('c_nego_search_contents_info');              // 
		$other['check'] = $CI->config->item('c_nego_search_check');                 // チェックボックス設定情報
		$other['button'] = $CI->config->item('c_nego_search_button');               // ボタン設定情報
		if(!$post){
		$post['e_year'] = date('Y');
		$post['e_month'] = date('m');
		$post['e_day'] = date('d');
		$post['s_year'] = date('Y',strtotime("-1 month"));
		$post['s_month'] = date('m',strtotime("-1 month"));
		$post['s_day'] = date('d',strtotime("-1 month"));
		$post['shop_main'] = 'on';
		$post['agency'] = 'on';
		}
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); // 商談履歴検索テーブル作成
		return $string_table;
	}

	/**
	 * ユーザー管理テーブル(社番検索)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_user_shbn($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_user_data');                                 // テーブル設定情報取得
		$title  = $CI->config->item('c_user_shbn_title');                           // 表示項目名
		$td_data[] = $CI->config->item('c_user_shbn');                              // 社番設定
		$other['button'] = $CI->config->item('c_user_search_button');               // ボタン設定情報
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); // ユーザー管理社番テーブル作成

		return $string_table;
	}

	/**
	 * ユーザー管理テーブル(氏名)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_user_name($post,$type)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_user_data');                        // テーブル設定情報取得
		$title  = $CI->config->item('c_user_name_title');                  // 表示項目名
		// 画面の種類毎の読み込み設定
		if($type === "add")
		{
			$td_data[] = $CI->config->item('c_user_name_add');             // 氏名設定
		}else if($type === "update"){
			$td_data[] = $CI->config->item('c_user_name_update');          // 氏名設定
		}else if($type === "delete"){
			$td_data[] = $CI->config->item('c_user_name_delete');          // 氏名設定
		}
			$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL); // ユーザー管理氏名テーブル作成

		return $string_table;
	}

	/**
	 * ユーザー管理テーブル(本部・部・課)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_user_busyo($post,$type)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_user_data');                 // テーブル設定情報取得
		$title  = $CI->config->item('c_user_busyo_title');          // 表示項目名
		// 画面の種類毎の読み込み設定
		if($type === "add")
		{
			$td_data[] = $CI->config->item('c_user_honbu_add');     // 本部設定
			$td_data[] = $CI->config->item('c_user_bu_add');        // 部設定
			$td_data[] = $CI->config->item('c_user_ka_add');        // 課設定
		}else if($type === "update"){
			$td_data[] = $CI->config->item('c_user_honbu_update');  // 本部設定
			$td_data[] = $CI->config->item('c_user_bu_update');     // 部設定
			$td_data[] = $CI->config->item('c_user_ka_update');     // 課設定
		}else if($type === "delete"){
			$td_data[] = $CI->config->item('c_user_honbu_delete');  // 本部設定
			$td_data[] = $CI->config->item('c_user_bu_delete');     // 部設定
			$td_data[] = $CI->config->item('c_user_ka_delete');     // 課設定
		}

		$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL); // ユーザー管理氏名テーブル作成

		return $string_table;
	}

	/**
	 * ユーザー管理テーブル(区分メニュー・閲覧権限)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_user_kbn($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_user_data');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_user_kbn_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_user_kbn');                             // 区分メニュー設定
		$kbn_data[] = array('title_name' => '', 'kbnid' => '009','tag_name' => 'menuhyjikbn','check' => '001','extra' => ' class="required" title="区分メニュー" ');
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL,$kbn_data); // ユーザー管理氏名テーブル作成

		return $string_table;
	}

	/**
	 * ユーザー管理テーブル(パスワード)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_user_pass($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_user_data');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_user_pass_title');                         // 表示項目名
		$td_data[] = $CI->config->item('c_user_pass');                            // 区分メニュー設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL); // ユーザー管理氏名テーブル作成

		return $string_table;
	}

	/**
	 * 非リスト型テーブル情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	function _set_box_table($table,$title,$post,$td_data,$other=NULL,$data = NULL,$data2 = NULL,$value=NULL)
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->library('select_box_set');
		$CI->load->library('item_manager');

		// テーブル作成
		$string_table = "";
		if(! is_null($table['heading']))
		{
			$string_table = "<table";
			// テーブル幅設定
			if( ! is_null($table['table_width']))
			{
				$string_table .= " width=\"" . $table['table_width'] . "\"";
			}
			// テーブル高さ設定
			if( ! is_null($table['table_height']))
			{
				$string_table .= " height=\"" . $table['table_height'] . "\"";
			}
			$string_table .= ">\n";

			// 見出し行高さ設定
			if( ! is_null($table['heading_tr_height']))
			{
				$string_table .= "<tr style=\"height:" . $table['heading_tr_height'] . "\">\n";
			}else{
				$string_table .= "<tr>\n";
			}
			// 見出し行横位置設定
			if( ! is_null($table['heading_td_style']))
			{
				$string_table .= "<th style=\"" . $table['heading_td_style'] . "\">\n";
			}else{
				$string_table .= "<th>\n";
			}

			$string_table .= $table['heading'];
			$string_table .= "</th>\n";
			$string_table .= "</tr>\n";
			$string_table .= "</table>\n";
		}
		// 見出しend

		// テーブル内容設定
		$string_table .= "<table";
				// テーブル幅設定
		if( ! is_null($table['table_width']))
		{
			$string_table .= " width=\"" . $table['table_width'] . "\"";
		}
		$string_table .= " style=\"border:".$table['table_border'].";"; // 枠線
		$string_table .= $table['table_padding'];
		$string_table .= "\">\n";
		// 項目名のHTML作成
		foreach($title as $num => $value)
		{
			$string_table .= "<tr>\n";
			//td開始
			$string_table .= "<td";
			// 項目名CSS設定
			if(! is_null($table['td_style']))
			{
				$string_table .= " style=\"".$table['td_style'].";";
				$string_table .= "vertical-align:".$td_data[$num]['vertical-align'];
				$string_table .= ";\"";
			}

			$string_table .= ">\n";
			// 項目パーツテーブル
			$string_table .= "<table";
			$string_table .= " width=\"".$table['td_title_width']."\" ";
			$string_table .= ">\n";
			$string_table .= "<tr>\n";
			$string_table .= "<td>\n";
			$string_table .= $value;
			$string_table .= "</td>\n";
			$string_table .= "</tr>\n";
			$string_table .= "</table>\n";
			// 項目パーツテーブルend
			$string_table .= "</td>\n";
			// td終了-----------------------------------------------------
			$string_table .= "<td>\n";
			// 項目の内容テーブル作成
			$string_table .= "<table";
			$string_table .= " width=\"".$table['td_table_width']."\" ";
			$string_table .= ">\n";
			// 列
			for($c = 0; $c < $td_data[$num]['row']; $c++)
			{
				$string_table .= "<tr>\n";
				// 行
				for($n = 0; $n < $td_data[$num]['span']; $n++)
				{
					// 内容セルの設定
					// 内容なしの場合
					if($c != 0 && $td_data[$num]['td_rowspan_flg'][$n])
					{
						$string_table .= "<td>\n</td>";
					}else{
						// 内容ありの場合
						$string_table .= "<td";
						// rowspan設定あり
						if($td_data[$num]['td_rowspan_flg'][$n])
						{
							$string_table .= " rowspan=\"".$td_data[$num]['td_rowspan']."\"";
							$string_table .= " style=\"".$table['td_style'].";";
							$string_table .= "vertical-align:".$td_data[$num]['vertical-align'].";";
						}else{
							// rowspan設定なし
							$string_table .= " style=\"";
						}
						// 各フォームのセル幅
						$string_table .= "width:".$td_data[$num]['contents_td_width'][$n].";";
						// セルの表示位置
						if(! is_null($td_data[$num]['td_text_align'][$n]))
						{
							$string_table .= "text-align:".$td_data[$num]['td_text_align'][$n].";";
						}
						$string_table .= ";\">\n";
						// フォームの設定
						if(! is_null($td_data[$num]['td_form_type'][$n]))
						{
							$attribute = $td_data[$num]['td_form_attribute'][$n];
							$type = $td_data[$num]['td_form_type'][$n];
							$name = $td_data[$num]['td_form_name'][$n];
							if(isset($td_data[$num]['td_form_required'][$n]) && $td_data[$num]['td_form_required'][$n]!=''){
								$required = $td_data[$num]['td_form_required'][$n];
							}else{
								$required = FALSE;
							}
							if(isset($td_data[$num]['extra'][$n]) && $td_data[$num]['extra'][$n]!=""){
							}else{
								$td_data[$num]['extra'][$n]="";
							}
							
							switch($td_data[$num]['td_form_type'][$n])
							{
								case 'select': // セレクトボックス設定
									//echo $data[$num]['extra'];
									$string_table .= $CI->select_box_set->_set_select_type($attribute,$name,$post,$required,$td_data[$num]['extra'][$n]); // セレクトボックス作成
									break;
								case 'drop': // ドロップダウンメニュー設定
									//$string_table .= $CI->item_manager->set_variable_dropdown_string($data[$num]);
									// 区分POSTの受け取り
									if(isset($post[$data[$num]['tag_name']]))
									{
										$data[$num]['check'] = $post[$data[$num]['tag_name']];
									}
									if(isset($data[$num]['extra']) && $data[$num]['extra']!=""){
									}else{
										$data[$num]['extra']="";
									}
									$string_table .= $CI->item_manager->set_dropdown_in_db_string($data[$num]['kbnid'],$data[$num]['tag_name'],$data[$num]['check'],NULL,$data[$num]['extra']) . "\n";
									break;
								case 'drop2': // ドロップダウンメニュー設定
									//$string_table .= $CI->item_manager->set_variable_dropdown_string($data[$num]);
									// 区分POSTの受け取り
									if(isset($post[$data[$num]['tag_name']]))
									{
										$data2[$num]['check'] = $post[$data2[$num]['tag_name']];
									}
									if(!isset($data[$num]['extra']) && $data[$num]['extra']!=""){
										
									}else{
										$data[$num]['extra']="";
									}
									$string_table .= $CI->item_manager->set_dropdown_in_db_string($data2[$num]['kbnid'],$data2[$num]['tag_name'],$data2[$num]['check'],NULL,$data2[$num]['extra']) . "\n";
									break;
								case 'from': // 期間
									$string_table .= "～";
									break;
								case 'type_kbn': //品種区分
									$string_table .= $CI->config->item('c_data_memo_kbn_type_title');
									break;
								case 'maker_kbn': //メーカー区分
									$string_table .= $CI->config->item('c_data_memo_kbn_maker_title');
									break;	
								case 'checkbox': // チェックボックス設定
									$string_table .= "<input type=\"".$type."\" name=\"".$other['check'][$c]['name']."\"";
									// 選択処理
									if(!empty($post[$other['check'][$c]['name']]))
									{
										$string_table .= " checked";
									}
									$string_table .= ">";
									// 表示項目名
									$string_table .= $other['check'][$c]['title'];
									break;
								case 'text': // テキストボックス設定
								case 'readonly':
									$string_table .= "<input type=\"".$attribute."\" name=\"".$name."\"";
									// size設定
									if(! is_null($td_data[$num]['textbox_size'][$n]))
									{
										$string_table .= " size=\"".$td_data[$num]['textbox_size'][$n]."\"";
									}
									// maxlength設定
									if(! is_null($td_data[$num]['text_maxlength'][$n]))
									{
										$string_table .= " maxlength=\"".$td_data[$num]['text_maxlength'][$n]."\"";
									}
									// value値設定
									$string_table .= (!empty($post[$name])) ? " value=\"".$post[$name]."\"" : "";
									
									//id,class値等の設定
									if(isset($td_data[$num]['extra']) && $td_data[$num]['extra']!=""){
										$string_table .= $td_data[$num]['extra'];
									}else{
										$td_data[$num]['extra']="";
									}
									
									if($td_data[$num]['td_form_type'][$n] === "readonly")
									{
										$string_table .= " readonly";
									}
									$string_table .= ">";
									// 年入力用
									if($td_data[$num]['td_form_name'][$n] === "s_year" OR
											$td_data[$num]['td_form_name'][$n] === "e_year")
									{
										$string_table .= " 年";
									}
									// 月入力用
									if($td_data[$num]['td_form_name'][$n] === "s_month" OR
											$td_data[$num]['td_form_name'][$n] === "e_month")
									{
										$string_table .= " 月";
									}
									// 日入力用
									if($td_data[$num]['td_form_name'][$n] === "s_day" OR
											$td_data[$num]['td_form_name'][$n] === "e_day")
									{
										$string_table .= " 日";
									}
									break;
								case 'label':
									$string_table .= "<label name=\"".$name."\"";
									$string_table .= ">";
									$string_table .= (!empty($post[$name])) ? $post[$name] : "";
									$string_table .= "</label>";
									// hidden HTML作成
									$string_table .= "<input type=\"hidden\" name=\"".$name."\"";
									$string_table .= (!empty($post[$name])) ? " value=\"".$post[$name]."\"" : "";
									$string_table .= ">";
									break;
								case 'button': // ボタン設定
									// 設定数だけボタン設置
									foreach($other['button'] as $b_data)
									{
										$string_table .= "<input type=\"".$b_data['type']."\" name=\"".$b_data['name']."\"";
										$string_table .= " value=\"".$b_data['value']."\"";
										$string_table .= "> ";
									}
									break;
								case 'file':
									if(isset($td_data[$num]['extra']) && $td_data[$num]['extra']!=""){
										$extra = $td_data[$num]['extra'];
									}
									$string_table .= "<input type=\"".$td_data[0]["td_form_type"][0]."\" name=\"".$td_data[0]["td_form_name"][0]."\" ".$extra." />";
									break;
								case 'textarea':
									if(isset($post[$name]) && $post[$name] !=""){}else{$post[$name] = '';}
									$string_table .= "<textarea name='".$td_data[0]["td_form_name"][0]."' rows='6' cols='60'";
									//id,class値等の設定
									if(isset($td_data[$num]['extra']) && $td_data[$num]['extra']!=""){
										$string_table .= $td_data[$num]['extra'];
									}
									$string_table .= ">".$post[$name]."</textarea>";
									break;
								default :
									$string_table .= $td_data[$num]['td_form_type'][$n];
									break;
							}
						}
						$string_table .= "</td>\n";
					}
				}// for end
				$string_table .= "</tr>\n";
			}
			$string_table .= "</table>\n";
			$string_table .= "</td>\n";
			$string_table .= "</tr>\n";
		}
		$string_table .= "</table>\n";

		return $string_table;
	}


	/// aiba add 20110124 ///////////////////////////////////////////////////////////////////////////////////////////
	/**
	 * 情報メモテーブル(件名)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @param   boolean
	 * @return	string
	 */
	public function set_data_memo_kname($post, $val=NULL, $mode)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                                 // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_kname_title');                     // 表示項目名
		if($mode) {
			$title  = $CI->config->item('c_data_memo_kname_title');                     // 表示項目名
			$td_data[] = $CI->config->item('c_data_memo_kname');					// 件名設定
			$other = NULL;
		} else {
		$title  = $CI->config->item('c_data_memo_kname_title_s');                     // 表示項目名
			$td_data[] = $CI->config->item('c_data_memo_kname_ud');					// 件名設定
			$other = NULL;
			//$other['button'] = $CI->config->item('c_data_memo_search_button');      // ボタン設定情報
		}
		
		if(isset($val['k_name']) && $val['k_name'] != ""){
			$post['k_name'] = $val['k_name'];
		}else if(isset($val['knnm']) && $val['knnm'] != ""){
			$post['knnm'] = $val['knnm'];
		}
		$string_table = $this->_set_box_table($table,$title,$post,$td_data); // 情報メモ件名テーブル作成

		return $string_table;
	}

	/**
	 * 情報メモテーブル(入手元)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_office($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                                 // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_office_title');                           // 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_office_name');                             // 社名
		$td_data[] = $CI->config->item('c_data_memo_office_job');                              // 役職
		$td_data[] = $CI->config->item('c_data_memo_office_u_name');                           // 氏名
		
		//データの取得

		if(isset($val['aitesknm']) && $val['aitesknm'] != ""){
			$post['aitesknm'] = $val['aitesknm'];
		}
		if(isset($val['yksyoku']) && $val['yksyoku'] != ""){
			$post['yksyoku'] = $val['yksyoku'];
		}
		if(isset($val['name']) && $val['name'] != ""){
			$post['name'] = $val['name'];
		}
		
		$string_table = $this->_set_box_table($table,$title,$post,$td_data); // 情報メモ入手元テーブル作成

		return $string_table;
	}

	/**
	 * 情報メモテーブル(区分)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_kbn($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_kbn_title');                     // 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_kbn_data');                   // 区分メニュー設定
		//$td_data[] = $CI->config->item('c_data_memo_kbn_type');                   // 区分メニュー設定
		$td_data[] = $CI->config->item('c_data_memo_kbn_target');                 // 区分メニュー設定
		$kbnid_data = array('001','003');
		$kbnid_data2 = array('002','013');
		$data = $this->_get_kbn_data($kbnid_data);
		$data2 = $this->_get_kbn_data2($kbnid_data2);
		
		//値がある場合
		if(isset($val['jyohokbnm']) && $val['jyohokbnm'] != ""){
			$post['jyohokbnm'] = $val['jyohokbnm'];
		}
		if(isset($val['hinsyukbnm']) && $val['hinsyukbnm'] != ""){
			$post['hinsyukbnm'] = $val['hinsyukbnm'];
		}
		if(isset($val['tishokbnm']) && $val['tishokbnm'] != ""){
			$post['tishokbnm'] = $val['tishokbnm'];
		}
		if(isset($val['maker']) && $val['maker'] != ""){
			$post['maker'] = $val['maker'];
		}
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL,$data,$data2); // 情報メモ氏名テーブル作成

		return $string_table;
	}

	/**
	 * 区分メニュー情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	public function _get_kbn_data($kbnid)
	{
		$CI =& get_instance();
		$CI->load->model('sgmtb030');
		$data_kbn_data = NULL;

		
		for($i = 0; $i < count($kbnid); $i++)
		{
			$kbn_data = $CI->sgmtb030->get_kbn_data($kbnid[$i]);
			foreach($kbn_data as $data)
			{
				foreach($data as $key => $value)
				{
					if($key === "ichiran")
					{
						$data_kbn_data[$i][$data['kbncd']] = $value;
					}
				}
			}
		}
		// データ取得
		// 未完成
		/* テストデータ START */
		/*$data_kbn_data[0] = array(
			'001' => '担当者',
			'002' => '管理者'
			);
		$data_kbn_data[1] = array(
			'001' => 'ティッシュ',
			'002' => 'トイレット',
			'003' => 'ワイプ',
			'004' => 'ベビー',
			'005' => 'フェミニン',
			'006' => 'シルバー',
			'007' => 'マスク',
			'008' => 'ペット',
			'009' => 'その他'
			);
		$data_kbn_data[2] = array(
			'001' => '月次商談',
			'002' => '商品情報案内',
			'003' => 'MD提案',
			'004' => '棚割提案',
			'005' => '実績報告',
			'006' => 'その他'
			);*/
		$rank_data[0] = array(
			'title_name' => 'data_kbn',
			'tag_name' => 'jyohokbnm',
			'kbnid' => '001',
			'name' => 'jyohokbnm',
			'data' => $data_kbn_data[0],
			'check' => '000',
			'extra' => 'style="width:100px"'
		);
		/*
		$rank_data[1] = array(
			'title_name' => '',
			'tag_name' => 'hinsyukbnm',
			'kbnid' => '002',
			'name' => 'hinsyukbnm',
			'data' => $data_kbn_data[1],
			'check' => '000'
		); 
		*/
		$rank_data[1] = array(
			'title_name' => '',
			'tag_name' => 'tishokbnm',
			'kbnid' => '003',
			'name' => 'tishokbnm',
			'data' => $data_kbn_data[1],
			'check' => '000',
			'extra' => 'style="width:100px"'
		);
		return $rank_data;
	}
		
	/**
	 * 区分メニュー情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	public function _get_kbn_data2($kbnid, $val=NULL)
	{
		$CI =& get_instance();
		$CI->load->model('sgmtb030');
		$data_kbn_data = NULL;
		for($i = 0; $i < count($kbnid); $i++)
		{
			$kbn_data = $CI->sgmtb030->get_kbn_data($kbnid[$i]);
			foreach($kbn_data as $data)
			{
				foreach($data as $key => $value)
				{
					if($key === "ichiran")
					{
						$data_kbn_data[$i][$data['kbncd']] = $value;
					}
				}
			}
		}
		$rank_data[0] = array(
			'title_name' => '',
			'tag_name' => 'hinsyukbnm',
			'kbnid' => '002',
			'name' => 'hinsyukbnm',
			'data' => $data_kbn_data[0],
			'check' => '000',
			'extra' => 'style="width:100px"'
		); 
		$rank_data[1] = array(
			'title_name' => '',
			'tag_name' => 'maker',
			'kbnid' => '013',
			'name' => 'maker',
			'data' => $data_kbn_data[1],
			'check' => '000',
			'extra' => 'style="width:100px"'
		); 
		return $rank_data;
	}

	/**
	 * 情報メモテーブル(メーカー)情報設定 区分処理とまとめた為現在は使っていない。
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_maker($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_maker_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_maker');
		$kbn_data[] = array('title_name' => '', 'kbnid' => '013','tag_name' => 'maker','check' => '000');  // メーカーメニュー設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL,$kbn_data); // 情報メモメーカーテーブル作成

		return $string_table;
	}

	/**
	 * 情報メモテーブル(添付ファイル)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_file($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_file_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_file');                             // 添付ファイルメニュー設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data); // 情報メモ添付ファイルテーブル作成

		return $string_table;
	}

	/**
	 * 情報メモテーブル(掲載期限)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_date($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		
		if(isset($val['s_year']) && $val['s_year'] != ""){
			$post['s_year'] = $val['s_year'];
		}
		if(isset($val['s_month']) && $val['s_month'] != ""){
			$post['s_month'] = $val['s_month'];
		}
		if(isset($val['s_day']) && $val['s_day'] != ""){
			$post['s_day'] = $val['s_day'];
		}
		
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                             	// テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_date_title');                   	// 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_date');                   		// 掲載期限メニュー設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data); 		// 情報メモ掲載期限テーブル作成
		return $string_table;
	}

	/**
	 * 情報メモテーブル(期間)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_date_from($post, $val=NULL)
	{
		if(isset($val['s_year']) && $val['s_year'] != ""){
			$post['s_year'] = $val['s_year'];
		}
		if(isset($val['s_month']) && $val['s_month'] != ""){
			$post['s_month'] = $val['s_month'];
		}
		if(isset($val['s_day']) && $val['s_day'] != ""){
			$post['s_day'] = $val['s_day'];
		}
		if(isset($val['e_year']) && $val['e_year'] != ""){
			$post['e_year'] = $val['e_year'];
		}
		if(isset($val['e_month']) && $val['e_month'] != ""){
			$post['e_month'] = $val['e_month'];
		}
		if(isset($val['e_day']) && $val['e_day'] != ""){
			$post['e_day'] = $val['e_day'];
		}
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                             	// テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_date_from_title');                   	// 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_date_from');                   		// 掲載期限メニュー設定
		$other['button'] = $CI->config->item('c_data_memo_search_button');      // ボタン設定情報
		
		
		
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); 		// 情報メモ掲載期限テーブル作成

		return $string_table;
	}
	
	/**
	 * 情報メモテーブル(期間)情報設定 検索画面
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_date_from_s($post, $val=NULL)
	{
		if(isset($val['s_year']) && $val['s_year'] != ""){
			$post['s_year'] = $val['s_year'];
		}else{
			$post['s_year'] = date('Y',strtotime("-1 month"));
		}
		if(isset($val['s_month']) && $val['s_month'] != ""){
			$post['s_month'] = $val['s_month'];
		}else{
			$post['s_month'] =  date('m',strtotime("-1 month"));
		}
		if(isset($val['s_day']) && $val['s_day'] != ""){
			$post['s_day'] = $val['s_day'];
		}else{
			$post['s_day'] = date('d',strtotime("-1 month"));
		}
		if(isset($val['e_year']) && $val['e_year'] != ""){
			$post['e_year'] = $val['e_year'];
		}else{
			$post['e_year'] = date('Y');
		}
		if(isset($val['e_month']) && $val['e_month'] != ""){
			$post['e_month'] = $val['e_month'];
		}else{
			$post['e_month'] = date('m');
		}
		if(isset($val['e_day']) && $val['e_day'] != ""){
			$post['e_day'] = $val['e_day'];
		}else{
			$post['e_day'] = date('d');
		}
	
	
		
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                             	// テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_date_from_title_s');                   	// 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_date_from_s');                   		// 掲載期限メニュー設定
		$other['button'] = $CI->config->item('c_data_memo_search_button');      // ボタン設定情報
		
		
		
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); 		// 情報メモ掲載期限テーブル作成

		return $string_table;
	}
	
	/**
	 * 情報メモテーブル 検索ボタン 設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_date_search($post)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_data_memo');                             	// テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_date_search_title');             	// 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_date_search');                   		// 掲載期限メニュー設定
		$other['button'] = $CI->config->item('c_data_memo_search_button');      // ボタン設定情報
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); 		// 情報メモ掲載期限テーブル作成

		return $string_table;
	}

	/****
	 * INPUT【TYPE=FILE】の作成
	 */
	function _set_type_files($title,$td_data)
	{
		$table_string = "<table>";
		$table_string .= "<tr><td>".$title[0]."</td><td>";
		$table_string .= "<input type='".$td_data[0]["td_form_type"][0]."' name='".$td_data[0]["td_form_name"][0]."' />";
		$table_string .= "</td></tr>";
		$table_string .= "</table>";
		return $table_string;
	}

	/**
	 * 情報メモテーブル(情報メモ内容)情報設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_data_memo_info($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		if(isset($val['info']) && $val['info'] != ""){
			$post['info'] = $val['info'];
		}
		$table  = $CI->config->item('c_data_memo');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_info_title');                          // 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_info');                             // 区分メニュー設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data); // 情報メモテーブル作成
		return $string_table;
	}
	
	public function set_data_memo_info_search($post, $val=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		if(isset($val['info']) && $val['info'] != ""){
			$post['info'] = $val['info'];
		}
		$table  = $CI->config->item('c_data_memo_search');                               // テーブル設定情報取得
		$title  = $CI->config->item('c_data_memo_info_title_s');                          // 表示項目名
		$td_data[] = $CI->config->item('c_data_memo_info_search');                             // 区分メニュー設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data); // 情報メモテーブル作成
		return $string_table;
	}


	/****
	 * TEXTAREA CREATE
	 */
	function _set_type_textarea($title,$td_data)
	{
		$table_string = "<table>";
		$table_string .= "<tr><td>".$title[0]."</td></tr><tr><td>";
		$table_string .= "<textarea name='".$td_data[0]["td_form_name"][0]."' rows='6' cols='200'></textarea>";
		$table_string .= "</td></tr>";
		$table_string .= "</table>";
		return $table_string;
	}

	/**
	 * 検索結果HTML作成(更新・削除画面)
	 *
	 * @access	public
	 * @param	array $td_data 設定データ
	 * @param	array $search_res 検索結果
	 * @param	array $post       検索条件
	 * @return	string HTML-STRING文字列
	 */
	public function set_data_memo_search($td_data, $search_res, $post = NULL,$base_url = NULL)
	{
		$table_string = NULL;

		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$title = $CI->config->item('c_data_memo_search_title');

		// 仮データ取得
//		$td_data = $CI->_get_data_memo_search();
		$table_string = "<table style='border-collapse:collapse;width:620px;margin-left:70px;'>\n<thead>\n<tr>\n";
		// 項目名の設定
		foreach($title as $vals)
		{
			if($vals == "日付")
			{
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:100px;padding-bottom:0px;padding-left:1px;'>\n";
				$table_string .= $vals."<input type='submit' value='▲' name='ASC' />\n<input type='submit' value='▼' name='DESC' />";
				$table_string .= "</td>\n";
			} else if($vals == "件名"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:300px;'>".$vals."</td>\n";
			} else if($vals == "ユニット長閲覧状況"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:80px;'>".$vals."</td>\n";
			} else if($vals == "入手先"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:200px;'>".$vals."</td>\n";
			}
		}
		$table_string .= "<td style='text-align: left;border: 0px solid black;width:20px;'></td></tr>\n</thead>\n<TBODY><TR><TD colspan=\"5\" style=\"padding-left: 0px; padding-top: 0px;padding-right: 0px;table-layout:fixed;word-break:break-all\">";

		$table_string .= "<div style='height:270px; width:640px; overflow-y:scroll;'><table style='border-collapse:collapse;width:620px;'><tbody>\n\n";
		// 内容の設定
		foreach($search_res as $data)
		{

			$table_string .= "<tr>\n";
			foreach($data as $key => $t_val)
			{
				$url = ($td_data["form"] == "update/search") ? "javascript:data_memo_update_select(\"".$data[4]."\",\"".$data[5]."\",\"".$base_url."\")" : "javascript:data_memo_delete_select(\"".$data[4]."\",\"".$data[5]."\",\"".$base_url."\")";
				if($key != 4 && $key != 5){
					
					
					if($key == 0)
					{
						$table_string .= "<td style='text-align: left;border: 1px solid black;background-color: #FFFFFF;width:100px;'>\n";
						$table_string .= "<a href='".$url."'>".$t_val."</a>\n";
					}else if($key == 2){
						$table_string .= "<td style='text-align: left;border: 1px solid black;background-color: #FFFFFF;width:200px;'>\n";
						$table_string .= $t_val;	
					}else if($key == 1){
						$table_string .= "<td style='text-align: left;border: 1px solid black;background-color: #FFFFFF;width:300px;'>\n";
						$table_string .= $t_val;
					}else{
						$table_string .= "<td style='text-align: left;border: 1px solid black;background-color: #FFFFFF;width:80px;'>\n";
						$table_string .= $t_val;
					}
					$table_string .= "</td>\n";
				}
			}
			$table_string .= "</tr>\n";
		}
		$table_string .= "</tbody></table></div></table>\n";
		// 検索条件保持
		if(! is_null($post))
		{
			foreach($post as $snm => $s_data)
			{
				if($snm === 's_year' OR $snm === 's_month' OR $snm === 's_day' OR $snm === 'e_year' OR $snm === 'e_month' OR $snm === 'e_day')
				{
					$table_string .= "<input type='hidden' name='s_".$snm."' value='".$s_data."'>\n";
				}
			}
		}
		return $table_string;
	}


	/**
	 * 検索結果HTML作成(検索画面)
	 *
	 * @access	public
	 * @param	array $td_data 設定データ
	 * @param	array $search_res 検索結果
	 * @param	array $post       検索条件
	 * @return	string HTML-STRING文字列
	 */
	public function set_data_memo_search_s($td_data, $search_res, $post = NULL,$base_url = NULL)
	{
		$table_string = NULL;
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$title = $CI->config->item('c_data_memo_search_s_title');

		// 仮データ取得
//		$td_data = $CI->_get_data_memo_search();
		$table_string = "<table style='border-collapse:collapse;margin-left:25px;'>\n<thead>\n<tr>\n";
		// 項目名の設定
		foreach($title as $vals)
		{
			if($vals == "日付")
			{
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:100px;padding-bottom:0px;padding-left:1px;'>\n";
				$table_string .= $vals."<input type='submit' value='▲' name='ASC' />\n<input type='submit' value='▼' name='DESC' />";
				$table_string .= "</td>\n";
			} else if($vals == "件名"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:200px;'>".$vals."</td>\n";
			} else if($vals == "ユニット長閲覧状況"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:75px;'>".$vals."</td>\n";
			} else if($vals == "入手先"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:150px;'>".$vals."</td>\n";
			} else if($vals == "入力者"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:100px;'>".$vals."</td>\n";
			} else if($vals == "ユニット"){
				$table_string .= "<td style='text-align: left; border-top: 1px solid black;border-left: 1px solid black;border-top: 1px solid black;border-right: 1px solid black;width:100px;'>".$vals."</td>\n";
			}
		}
		$table_string .= "<td style='text-align: left;border: 0px solid black;width:20px;'></td></tr>\n</thead>\n<TBODY><TR><TD colspan=\"7\" style=\"padding-left: 0px; padding-top: 0px;padding-right: 0px;\">";

		$table_string .= "<div style='height:245px; width:750px; overflow-y:auto;'><table style='border-collapse:collapse;table-layout:fixed;word-break:break-all'><tbody>\n\n";

		// 内容の設定
		foreach($search_res as $data)
		{
			$table_string .= "<tr>\n";
			foreach($data as $key => $t_val)
			{
				if($key != 6 && $key != 7){
					$url = "javascript:data_memo_search_select(\"".$data[6]."\",\"".$data[7]."\",\"".$base_url."\")";
					//$url = ($td_data["form"] == "update") ? "http://localhost/elleair/index.php/data_memo/update_select/" : "http://localhost/elleair/index.php/data_memo/delete_select/";
					if($key == 0)
					{
						$table_string .= "<td style='text-align: left;border: 1px solid black; background-color: #FFFFFF;width:100px;'>\n";
					}else if($key == 5){
						$table_string .= "<td style='border: 1px solid black; background-color: #FFFFFF;width:75px;'>\n";
					}else if($key == 4){
						$table_string .= "<td style='border: 1px solid black; background-color: #FFFFFF;width:150px;'>\n";
					}else if($key == 3){
						$table_string .= "<td style='border: 1px solid black; background-color: #FFFFFF;width:100px;'>\n";
					}else if($key == 2){
						$table_string .= "<td style='border: 1px solid black; background-color: #FFFFFF;width:100px;'>\n";
					}else if($key == 1){
						$table_string .= "<td style='border: 1px solid black; background-color: #FFFFFF;width:200px;'>\n";
					}
					if($key == 0)
					{
						$table_string .= "<a href='".$url."'>".$t_val."</a>\n";
					}else{
						$table_string .= $t_val;
					}
					$table_string .= "</td>\n";
				}
			}
			$table_string .= "</tr>\n";
		}
		$table_string .= "</tbody></table></div></table>\n";
		// 検索条件保持
		if(! is_null($post))
		{
			foreach($post as $snm => $s_data)
			{
				if($snm === 'knnm' OR $snm === 'aitesknm' OR $snm === 'yksyoku' OR $snm === 'name' OR $snm === 'data_kbn' OR $snm === 'type_kbn' OR $snm === 'target_kbn' OR $snm === 'info' OR $snm === 'maker' OR $snm === 's_year' OR $snm === 's_month' OR $snm === 's_day' OR $snm === 'e_year' OR $snm === 'e_month' OR $snm === 'e_day' OR $snm === 'jyohokbnm' OR $snm === 'hinsyukbnm' OR $snm === 'tishokbnm' OR $snm === 'honbucd' OR $snm === 'bucd' OR $snm === 'kacd' OR $snm === 'user')
				{
					$table_string .= "<input type='hidden' name='s_".$snm."' value='".$s_data."'>\n";
				}
			}
		}
		return $table_string;
	}


	/*
	 * 相手選択用のデータ取得をする
	 */
	public function set_select_client_list($data, $kbn)
	{
	log_message('debug',"========== table_set set_select_client_list start ==========");
		$string_table = NULL;
		$CI =& get_instance();
		$CI->load->model('srwtb021'); // ユーザ別検索情報（相手先）
		if($kbn === MY_USER_ACTIVITY_AGENCY){
			// 代理店
			$all_cnt = $CI->srwtb021->get_select_client_list_d($data, $kbn);
		}else{
			$all_cnt = $CI->srwtb021->get_select_client_list($data, $kbn);
		}
	log_message('debug',"========== table_set set_select_client_list end ==========");

		return $all_cnt;
	}

	public function set_client_list($post, $mode)
	{
		// 初期化
		/*
		$CI =& get_instance();
		// 設定値取得
		$table  = $CI->config->item('c_user_data');                                 // テーブル設定情報取得
		$title  = array(""); //$CI->config->item('c_data_memo_kname_title');                           // 表示項目名
		$td_data[] = $CI->config->item('c_project_data');						// 件名設定
		$other['button'] = $CI->config->item('c_project_data_button');               // ボタン設定情報
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); // 情報メモ件名テーブル作成
		*/
		$string_table = NULL;
		if($mode == MY_CLIENT_KARI) {
			$string_table = '<table style="border-collapse:collapse;">
						<tr>
							<th>&ensp;</th>
							<th>【元データ】</th>
							<th>【新データ】</th>
							<th>&ensp;</th>
							<th>&ensp;</th>
						</tr>
						<tr>
							<td style="border: 1px solid black;">更</td>
							<td style="border: 1px solid black;">仮相手先名</td>
							<td style="border: 1px solid black;">正式相手先コード</td>
							<td style="border: 1px solid black;">正式相手先名</td>
							<td style="border: 1px solid black;">検索</td>
						</tr>';
			$string_table .= $this->_get_client_list($mode);
		} else {
			$string_table = '<table style="border-collapse:collapse;">
					<tr>
						<th>&ensp;</th>
						<th>【元データ】</th>
						<th colspan="2">&ensp;</th>
						<th>【新データ】</th>
						<th colspan="2">&ensp;</th>
					</tr>
					<tr>
						<td style="border: 1px solid black;">更</td>
						<td style="border: 1px solid black;">正式相手先コード</td>
						<td style="border: 1px solid black;">正式相手先名</td>
						<td style="border: 1px solid black;">検索</td>
						<td style="border: 1px solid black;">正式相手先コード</td>
						<td style="border: 1px solid black;">正式相手先名</td>
						<td style="border: 1px solid black;">検索</td>
					</tr>';
			$string_table .= $this->_get_client_list($mode);
		}
		$string_table .= "</table>";
		return $string_table;
	}

	private function _get_client_list($mode) {
		$string_table = NULL;

		if($mode == MY_CLIENT_KARI) {
			for($i = 0; $i < 15; $i++) {
				$string_table .= '
						<tr>
							<td style="border: 1px solid black;"><input type="checkbox" name="check" id="check"></td>
							<td style="border: 1px solid black;"><input type="text" name="kari" id="kari" size="30" value="ＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧ"></td>
							<td style="border: 1px solid black;"><input type="text" name="s_code" id="s_code" size="16" value="ＧＧＧＧＧＧＧＧ"></td>
							<td style="border: 1px solid black;"><input type="text" name="s_name" id="s_name" size="30" value="ＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧ"></td>
							<td style="border: 1px solid black;"><input type="submit" name="search" id="search" value="参照" ></td>
						</tr>
				';
			}
		} else {
			for($i = 0; $i < 15; $i++) {
				$string_table .= '
					<tr>
						<td style="border: 1px solid black;"><input type="checkbox" name="check" id="check"></td>
						<td style="border: 1px solid black;"><input type="text" name="s_code" id="s_code" size="16" value="ＧＧＧＧＧＧＧＧ"></td>
						<td style="border: 1px solid black;"><input type="text" name="s_name" id="s_name" size="30" value="ＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧ"></td>
						<td style="border: 1px solid black;"><input type="submit" name="search" id="search" value="参照"></td>
						<td style="border: 1px solid black;"><input type="text" name="s_code" id="s_code" size="16" value="ＧＧＧＧＧＧＧＧ"></td>
						<td style="border: 1px solid black;"><input type="text" name="s_name" id="s_name" size="30" value="ＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧＧ"></td>
						<td style="border: 1px solid black;"><input type="submit" name="search" id="search" value="参照"></td>
					</tr>
				';
			}
		}

		return $string_table;
	}

	/**
	 * 本部用
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function get_head_data($post)
	{
			log_message('debug',"========== table_set get_head_data start ==========");
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();

		// DB名と同じものを指定
		$CI->load->model('sgmtb050'); // ユーザ別検索情報（相手先）
		$kbn = array('1','2');
//		$kbn = '1';
		$all_cnt = $CI->sgmtb050->search_select_client_data_head($post, $kbn);
			log_message('debug',"========== table_set get_head_data start ==========");

		return $all_cnt;
	}

	/**
	 * 販売店用
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function get_maker_data($post)
	{
			log_message('debug',"========== table_set get_maker_data start ==========");
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();

		// DB名と同じものを指定
		$CI->load->model('sgmtb050'); // ユーザ別検索情報（相手先）
		$kbn = array('3');
//		$kbn = '3';
		$all_cnt = $CI->sgmtb050->search_select_client_data($post, $kbn);
			log_message('debug',"========== table_set get_maker_data start ==========");

		return $all_cnt;
	}

	/**
	 * 一般店用
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function get_shop_data($post)
	{
			log_message('debug',"========== table_set get_shop_data start ==========");
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();

		// DB名と同じものを指定
		$CI->load->model('sgmtb051'); // ユーザ別検索情報（相手先）
		$all_cnt = $CI->sgmtb051->search_select_client_data($post);
			log_message('debug',"========== table_set get_shop_data end ==========");

		return $all_cnt;
	}

	/**
	 * 代理店用
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	public function get_agency_data($post)
	{
			log_message('debug',"========== table_set get_agency_data start ==========");
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();

		// DB名と同じものを指定
		$CI->load->model('sgmtb060'); // ユーザ別検索情報（相手先）
		$all_cnt = $CI->sgmtb060->search_select_client_data($post);
			log_message('debug',"========== table_set get_agency_data start ==========");
		
		return $all_cnt;

	}

	/**
	 * ユーザーテーブルからユーザ情報を取得する
	 * Enter description here ...
	 * @param unknown_type $post
	 */
	public function get_user_Info($shbn = NULL)
	{
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();
		// DB名と同じものを指定
		$CI->load->model('sgmtb010'); // ユーザ別検索情報（相手先）
		$all_cnt = $CI->sgmtb010->get_staff_info($shbn);

		return $all_cnt;
	}

	/**
	 * 本部情報をすべて取得
	 * Enter description here ...
	 */
	public function get_head_info($data, $business_unit)
	{
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();

		// DB名と同じものを指定
		$CI->load->model('sgmtb070');
		$all_cnt = $CI->sgmtb070->get_ssk_data_all($data, $business_unit);

		return $all_cnt;
	}

	public function get_item_visibility_data($data, $where)
	{
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();

		// DB名と同じものを指定
		$CI->load->model('sgmtb041'); // 画面生成情報
		$get_data = $CI->sgmtb041->get_item_visibility_data($where);
		$string_table = '<table id="edit_list">
        		            <tr>
                		        <th>項目名</th>
                        		<th>表示名</th>
                        		<th>入力タイプ</th>
                        		<th>表示</th>
                    		</tr>';
		if($get_data) {
			foreach ($get_data as $row) {
				 // 入力タイプ
				 $input_type = "";
	             if($row['inputtype'] == '1'){
					$input_type = '文字列';
				 } else if($row['inputtype'] == '2') {
					$input_type = '数値';
	             }
				 // 表示・非表示
				 $checked = '';
				 if($row['dispflg'] == '1'){
					$checked = 'checked';
				 }

				 //チェックボックスチェック時渡す値
				 $key = $row['pid'] . ":" . $row['dbname'] . ":" . $row['dbitem'];
				 $string_table .= '<tr>'
	                 		   .      '<td class="name">'
	                           .         $row['itemname']
	                           .      '</td>'
	                           .      '<td class="name"><input type="text" name="item_disp_name[]" value="' . $row["itemdspname"] . '" maxlength="256" style="width:250px;"/></td>'
	                           .      '<td>'
	                           .         $input_type
	                           .      '</td>'
	                           .      '<td>'
	                           .      '<input type="checkbox" name="item_disp_chk[]" value="' . $key . '" ' . $checked . ' />'
	                           .      '<input type="hidden" name="item_disp_key[]" value="' . $key . '" ' . $checked . ' />'
	                           .      '</td>'
	                           .  '</tr>';
			}
		}

		$string_table .= '</table>';

		return $string_table;

	}

	/**
	 * todo画面に出すデータ表示
	 */
	public function get_todo_info($data)
	{
		// この記述でデータベースからデータを取得するための、modelを呼ぶ
		$string_table = NULL;
		$CI =& get_instance();
		// DB名と同じものを指定
		$CI->load->model('srktb040');
		$all_cnt = $CI->srktb040->get_todo_data($data);
		return $all_cnt;
	}

}

// END Table_set class

/* End of file Table_set.php */
/* Location: ./application/libraries/Table_set.php */
