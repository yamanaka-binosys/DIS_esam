<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Calendar_manager {
	
	/**
	 * トップ画面で使用するカレンダーHTMLを作成する
	 * 
	 * @access	public
	 * @param	array_string
	 * @return	string
	 */
	public function set_week_calendar($shbn = NULL)
	{
		log_message('debug',"========== Calendar_manager set_week_calendar start ==========");
		log_message('debug',"\$shbn = $shbn");
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');
		
		// 社番チェック
		if(is_null($shbn))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		
		$calendar_data = $this->_get_calendar_data($shbn); // カレンダー情報取得
		$calendar_table = $CI->table_manager->set_calendar_table($calendar_data); // カレンダーテーブル作成
		log_message('debug',"========== Calendar_manager set_week_calendar end ==========");
		return $calendar_table;
//		return NULL;
	}
	
	/**
	 * 選択月のカレンダーHTMLを作成
	 * 
	 * @access	public
	 * @param	string  $shbn          社番
	 * @param	string  $select_month  選択月
	 * @param	string  $calendar_mode MY_CALENDAR_MIX    :混合タイプ
	 *                                 MY_CALENDAR_ALLPLAN:予定のみ
	 * @return	string
	 */
	public function set_month_calendar($shbn = NULL,$select_month = NULL,$calendar_mode = MY_CALENDAR_MIX)
	{
		log_message('debug',"========== Calendar_manager set_month_calendar start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_month = $select_month");
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');
		// 選択年月を確認
		if(is_null($select_month))
		{
			$year = date("Y");
			$month = date("m");
		}else{
			$year = substr($select_month,0,4);
			$month = substr($select_month,4,2);
		}
		log_message('debug',"\$year = $year");
		log_message('debug',"\$month = $month");
		
		// 社番有無を確認
		if(is_null($shbn))
		{
			log_message('debug',"\$shbn check is NULL");
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 選択月の取得　初期値は当月
		if(is_null($select_month))
		{
			log_message('debug',"\$select_month check is NULL");
			$select_month = date("Ym");
		}
		$select_calendar_date = $this->_get_select_calendar($shbn,$select_month,$calendar_mode); // 月の情報（日付・曜日・最終日等）取得
		$select_calendar_table = $CI->table_manager->set_select_calendar($year,$month,$select_calendar_date); // カレンダー作成
		log_message('debug',"========== Calendar_manager set_month_calendar end ==========");
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
		log_message('debug',"========== Calendar_manager set_select_month start ==========");
		log_message('debug',"\$select_month = $select_month");
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');
		
		// 選択月の取得　初期値は当月
		if(is_null($select_month))
		{
			log_message('debug',"\$select_month check is NULL");
			$select_month = date("Ym");
		}
		$select_month_date = $this->_get_three_month($select_month);
		$select_month_table = $CI->table_manager->set_three_month_table($select_month_date);
		log_message('debug',"========== Calendar_manager set_select_month end ==========");
		return $select_month_table;
	}

	/**
	 * カレンダー画面上部に表示する予定・実績/予定のみのモード
	 * 切り替えをするボタンテーブルのHTMLを作成
	 * 
	 * @access	public
	 * @param	string $select_mode 
	 * @return	string
	 */
	public function set_select_mode($select_mode = MY_CALENDAR_MIX)
	{
		log_message('debug',"========== Calendar_manager set_select_mode start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');
		
		$select_mode_table = $CI->table_manager->set_mode_table($select_mode);
		log_message('debug',"========== Calendar_manager set_select_mode end ==========");
		return $select_mode_table;
	}
	
	/**
	 * カレンダー情報取得
	 * 
	 * @access	private
	 * @param	string $shbn 社番
	 * @return	array_string
	 */
	function _get_calendar_data($shbn)
	{
		log_message('debug',"========== Calendar_manager _get_calendar_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		
		// 初期化
		$CI =& get_instance();
		$CI->load->model('common');
		$CI->load->model('sgmtb030');
		$CI->load->model('sgmtb150');
		$week_ja = $CI->config->item('c_day_week_ja'); // 曜日フォーマット取得
		$sagyo = NULL;
		$year = date("Y");
		$month = date("m");
		$day = date("d");
		$start_date = date("Ymd", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day, $year)); // 予定取得開始日（YYYYMMDD）
		$end_date = date("Ymd", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+MY_WEEK_COUNT_DAY, $year)); // 予定取得終了日（YYYYMMDD）
		
		// 予定情報取得
		$calendar_data = $CI->common->get_calendar_plan_data($shbn,$start_date,$end_date);
		
		// 表示日数分の情報を取得し、格納
//		for($i=0; $i < $CI->config->item('c_top_calendar_day'); $i++)
		for($i=0; $i < MY_WEEK_DAY; $i++)
		{
			// 比較日付設定
			$compare_day = date("Ymd", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+$i, $year));
			// 月日、曜日を取得し設定
			$day_week[$i]['link_day'] = date("Ymd", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+$i, $year)); // リンクURL用日付
			$day_week[$i]['0'] = date("n月j日", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+$i, $year)); // 日付取得
			$day_week[$i]['1'] = $week_ja[date("w", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+$i, $year))]; // 曜日取得（日本語）
			$day_week[$i]['2'] = strtolower(date("l", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+$i, $year))); // 曜日取得（英語）
            // 祝日チェック
            $syuk = $CI->sgmtb150->check_holiday(date("Y-n-j", mktime(MY_ZERO, MY_ZERO, MY_ZERO, $month , $day+$i, $year)));
            if(!isset($day_week[$i]['holiday'])){
                $day_week[$i]['holiday'] = ''; // 初期化
            }
            // 振替チェック
            if($syuk){  // この日が祝日だった場合
                if($day_week[$i]['1']=='日'){           // 日曜の場合、振替
                    if(($i + 1) < MY_WEEK_DAY){
                        $day_week[$i+1]['holiday'] = '祝';

                    }
                }else{
                    if($day_week[$i]['holiday']=='祝'){  // 既に祝日になっているか
                        if(($i + 1) < MY_WEEK_DAY){     // 範囲内の
                            $day_week[$i+1]['holiday'] = '祝'; // 祝日なら振替の振替
                        }
                    }else{
                        $day_week[$i]['holiday'] = '祝'; // 祝日
                    }
                }
            }
			// 取得した予定情報を基に配列に時間・タイトルを設定
			$data_count = MY_THREE; // 取得データ数カウンター初期化
			$count = MY_ZERO; // データ数カウンター
			
			if( ! is_null($calendar_data))
			{
				foreach($calendar_data as $key => $value)
				{
					if($value['ymd'] === $compare_day)
					{
						// データ生成
						$time_string = "";  // 時刻
						$title_string = ""; // 表示名
						$time_string .= substr($value['sthm'],0,2);
						$time_string .= ":";
						$time_string .= substr($value['sthm'],2,2);
						log_message('debug',"action_type = ".$value['action_type']);
						if($value['action_type'] === "内勤"){
							$sagyo = $CI->sgmtb030->get_ichiran_name(MY_PLAN_SAGYONAIYO_KBN,$value['viewnm']);
						log_message('debug',"sagyo = ".$sagyo);
							if(isset($sagyo)){
								$title_string .= $sagyo; // 相手先を取得
							}else{
								$title_string .= NULL;
							}
						}else{
							$title_string .= $value['viewnm']; // 相手先を取得
						}
						// データ格納
						$day_week[$i][$data_count]['time'] = $time_string;
						$day_week[$i][$data_count]['title'] = $title_string;
						$data_count++;
					}
				}
			}else{
				log_message('debug',"calendar_data is null");
			}
		}
		log_message('debug',"========== Calendar_manager _get_calendar_data end ==========");
		return $day_week;
	}
	
	/**
	 * 選択された月の情報（日付・曜日・最終日等）を取得
	 *
	 * @access	private
	 * @param	string  $shbn         社番
	 * @param	string  $select_month 選択月
	 * @param	string  $calendar_mode MY_CALENDAR_MIX    :混合タイプ
	 *                                 MY_CALENDAR_ALLPLAN:予定のみ
	 * @return	array
	 */
	function _get_select_calendar($shbn,$select_month,$calendar_mode = MY_CALENDAR_MIX)
	{
		log_message('debug',"========== Calendar_manager _get_select_calendar start ==========");
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
		$interval_day = date("j"); // ゼロサプレスした日付
		$present_day = date("d"); // ゼロパディングした日付
		log_message('debug',"\$present_year_month = $present_year_month");
		log_message('debug',"\$present_year = $present_year");
		log_message('debug',"\$present_month = $present_month");
		log_message('debug',"\$present_day = $present_day");
		log_message('debug',"\$interval_day = $interval_day");
		// カレンダーの表示タイプを判定
		if($calendar_mode == MY_CALENDAR_MIX)
		{
			// 選択年月が現在の年月と同じかどうか判定
			if((int)$present_year_month == (int)$select_month)
			{
				// 同年同月の場合
				// 現在日付より過去の日付の日報情報を取得
				$res_calendar_data = $this->_get_result_data($shbn,$year,$month,MY_START_DAY,$present_day);
				// 現在日付より未来の日付の予定情報を取得（同日を含む）
				$pre_calendar_data = $this->_get_plan_data($shbn,$year,$month,$present_day,$last_day);
				// 過去と未来の情報を１つに合わせる
				$calendar_data = $this->_match_calendar_data($pre_calendar_data,$res_calendar_data,$interval_day,$last_day);
			}else if((int)$present_year_month > (int)$select_month){
				// 選択年月が過去年月の場合
				// 対象月の日報情報を取得
				$calendar_data = $this->_get_result_data($shbn,$year,$month,MY_START_DAY,$last_day);
			}else if((int)$present_year_month < (int)$select_month){
				// 選択年月が未来年月の場合
				// 対象月の予定を取得
				$calendar_data = $this->_get_plan_data($shbn,$year,$month,MY_START_DAY,$last_day);
			}else{
				// エラー処理
				throw new Exception("Error Processing Request", '');
			}
		}else if($calendar_mode === MY_CALENDAR_ALLPLAN){
			// 予定のみ表示モード
			// 対象月の予定を取得
			$calendar_data = $this->_get_plan_data($shbn,$year,$month,MY_START_DAY,$last_day);
		}else{
			// エラー処理
			throw new Exception("Error Processing Request", '');			
		}
		log_message('debug',"========== Calendar_manager _get_select_calendar end ==========");
		return $calendar_data;
	}
	
	/**
	 *　表示する年月を設定
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_year_month($select_month,$post = NULL)
	{
		log_message('debug',"========== Calendar_manager _get_three_month start ==========");
		log_message('debug',"\$select_month = $select_month");
		$year = substr($select_month,0,4);
		log_message('debug',"\$year = $year");
		$month = substr($select_month,4,2);
		log_message('debug',"\$month = $month");
		
		// 表示月判定
		if(isset($post['previous_month'])){
			// 前月の年と月を取得
			$select_month_date['select_year'] = date("Y", mktime(0, 0, 0, $month -1 , 1, $year));
			$select_month_date['select_month'] = date("m", mktime(0, 0, 0, $month -1 , 1, $year));
			log_message('debug',"select_year = " . $select_month_date['select_year']);
			log_message('debug',"select_month = " . $select_month_date['select_month']);		
		}else if(isset($post['next_month'])){
			// 翌月の年と月を取得
			$select_month_date['select_year'] = date("Y", mktime(0, 0, 0, $month +1 , 1, $year));
			$select_month_date['select_month'] = date("m", mktime(0, 0, 0, $month +1 , 1, $year));
			log_message('debug',"select_year = " . $select_month_date['select_year']);
			log_message('debug',"select_month = " . $select_month_date['select_month']);
			log_message('debug',"========== Calendar_manager _get_three_month end ==========");				
		}else{
			// 選択月の年と月を取得
			$select_month_date['select_year'] = date("Y", mktime(0, 0, 0, (int)$month, 1, (int)$year));
			$select_month_date['select_month'] = date("m", mktime(0, 0, 0, (int)$month, 1, (int)$year));
			log_message('debug',"select_year = " . $select_month_date['select_year']);
			log_message('debug',"select_month = " . $select_month_date['select_month']);
		}
		// 年月（YYYYMM）の形で生成
		$year_month = $select_month_date['select_year'].$select_month_date['select_month'];
		return $year_month;
	}
	
	/**
	 * 選択年月の開始日～終了日までの曜日情報・日報情報を取得
	 * 
	 * @access	private
	 * @param	string $shbn 社番
	 * @param	string $year 年
	 * @param	string $month 月
	 * @param	string $start_day 開始日
	 * @param	string $end_day 終了日
	 * @return	array
	 */
	function _get_result_data($shbn,$year,$month,$start_day,$end_day)
	{
		log_message('debug',"========== Calendar_manager _get_result_data start ==========");
		log_message('debug',"\$year = $year");
		log_message('debug',"\$month = $month");
		log_message('debug',"\$start_day = $start_day");
		log_message('debug',"\$end_day = $end_day");
		// 初期化
		$CI =& get_instance();
		$CI->load->model('common');
		$CI->load->model('sgmtb030');
		$calendar_data = array(); // 日付・曜日・日報を格納
		$calendar_result_data = array(); // DataBaseより取得した日報情報を格納
		$sagyo = NULL;                   // 内勤作業
		// 日付情報生成
		$select_start_date = $year . $month . $start_day; // 開始日（YYYYMMDD）
		log_message('debug',"\$select_start_date = $select_start_date");
		$select_end_date = $year . $month . $end_day; // 終了日（YYYYMMDD）
		log_message('debug',"\$select_end_date = $select_end_date");
		// DataBaseより情報取得
		$calendar_result_data = $CI->common->get_calendar_result_data($shbn,$select_start_date,$select_end_date);
		if(is_null($calendar_result_data))
		{
			// データ取得出来なかった場合には日付のみ設定
			for($i=(int)$start_day; $i < ((int)$end_day)+1; $i++)
			{
//				log_message('debug',"\$day = $i");
				$result_day = date("Ymd", mktime(0, 0, 0, $month , $i, $year)); // リンクURLに渡す日付
				$calendar_data[$i]['day'] = date("d", mktime(0, 0, 0, $month , $i, $year)); // 日付を取得（ゼロパディング）
				$calendar_data[$i]['week'] = strtolower(date("l", mktime(0, 0, 0, $month , $i, $year))); // 曜日取得（英語）
				// 遷移先URL設定
//				$calendar_data[$i]['link_url'] = $CI->config->item('base_url') . "index.php/result";
				$calendar_data[$i]['link_url'] = $CI->config->item('base_url') . "index.php/result/index/" . $result_day;
				// 日報情報取得
				$calendar_data[$i]['date_time'][] = NULL; // 開始時刻を取得
				$calendar_data[$i]['result_data'][] = NULL; // 相手先を取得
			}
		}else{
			$day_count = 0; // 経過日数カウンター初期化
			$data_count = 0; // 取得データ数カウンター初期化
			for($i=(int)$start_day; $i < ((int)$end_day)+1; $i++)
			{
				$day = (int)$start_day + $day_count; // 開始日に経過日数を加算
//				log_message('debug',"\$day = $day");
				// 取得したデータの日付情報を数値で取得
				if( ! empty($calendar_result_data[$data_count]['ymd']))
				{
					$date_day = (int)substr($calendar_result_data[$data_count]['ymd'],6,2);
				}else{
					$date_day = NULL;
				}
				log_message('debug',"\$day = $day \$date_day = $date_day");
				if($day === $date_day)
				{
					while($day === $date_day)
					{
						$result_day = date("Ymd", mktime(0, 0, 0, $month , $date_day, $year)); // リンクURLに渡す日付
						$calendar_data[$day]['day'] = substr($calendar_result_data[$data_count]['ymd'],6,2); // 日付を取得（ゼロサプレス）
						$calendar_data[$day]['week'] = strtolower(date("l", mktime(0, 0, 0, $month , $date_day, $year))); // 曜日取得（英語）
						// 遷移先URL設定
//						$calendar_data[$day]['link_url'] = $CI->config->item('base_url') . "index.php/result";
						$calendar_data[$day]['link_url'] = $CI->config->item('base_url') . "index.php/result/index/" . $result_day;
						// 日報情報取得
						$calendar_data[$day]['date_time'][] = $calendar_result_data[$data_count]['sthm']; // 開始時刻を取得
						if($calendar_result_data[$data_count]['action_type'] === "内勤"){
							$sagyo = $CI->sgmtb030->get_ichiran_name(MY_RESULT_SAGYONAIYO_KBN,$calendar_result_data[$data_count]['viewnm']);
							$sagyo = $sagyo == 'その他' ? $calendar_result_data[$data_count]['opt'] : $sagyo;
							if(isset($sagyo)){
								$calendar_data[$day]['result_data'][] = $sagyo; // 相手先を取得
							}else{
								$calendar_data[$day]['result_data'][] = NULL;
							}
						}else{
							$calendar_data[$day]['result_data'][] = $calendar_result_data[$data_count]['viewnm']; // 相手先を取得
						}
						// 次のデータの有無確認
						$data_count++;
						log_message('debug',"\$data_count = $data_count");
						if( ! empty($calendar_result_data[$data_count]['ymd']))
						{
							$date_day = (int)substr($calendar_result_data[$data_count]['ymd'],6,2);
						}else{
							$date_day = NULL;
						}
						log_message('debug',"\$date_day = $date_day");
					}
				}else{
					$result_day = date("Ymd", mktime(0, 0, 0, $month , $day, $year)); // リンクURLに渡す日付
					$calendar_data[$day]['day'] = date("d", mktime(0, 0, 0, $month , $day, $year)); // 日付を取得（ゼロパディング）
					$calendar_data[$day]['week'] = strtolower(date("l", mktime(0, 0, 0, $month , $day, $year))); // 曜日取得（英語）
					// 遷移先URL設定
//					$calendar_data[$day]['link_url'] = $CI->config->item('base_url') . "index.php/result";
					$calendar_data[$day]['link_url'] = $CI->config->item('base_url') . "index.php/result/index/" . $result_day;
					// 日報情報取得
					$calendar_data[$day]['date_time'][] = NULL; // 開始時刻を取得
					$calendar_data[$day]['result_data'][] = NULL; // 相手先を取得
				}
				$day_count++;
			}
		}
		log_message('debug',"========== Calendar_manager _get_result_data end ==========");
		return $calendar_data;
	}
	
	/**
	 * 選択年月の開始日～終了日までの曜日情報・日報情報を取得
	 * 
	 * @access	private
	 * @param	string $shbn 社番
	 * @param	string $year 年
	 * @param	string $month 月
	 * @param	string $start_day 開始日
	 * @param	string $end_day 終了日
	 * @return	array
	 */
	function _get_plan_data($shbn,$year,$month,$start_day,$end_day)
	{
		log_message('debug',"========== Calendar_manager _get_plan_data start ==========");
		log_message('debug',"\$year = $year");
		log_message('debug',"\$month = $month");
		log_message('debug',"\$start_day = $start_day");
		log_message('debug',"\$end_day = $end_day");
		// 初期化
		$CI =& get_instance();
		$CI->load->model('common');
		$CI->load->model('sgmtb030');
		$calendar_data = array(); // 日付・曜日・日報を格納
		$calendar_plan_data = array(); // DataBaseより取得した日報情報を格納
		$sagyo = NULL;                 // 内勤作業
		// 日付情報生成
		$select_start_date = $year . $month . $start_day; // 開始日（YYYYMMDD）
		log_message('debug',"\$select_start_date = $select_start_date");
		$select_end_date = $year . $month . $end_day; // 終了日（YYYYMMDD）
		log_message('debug',"\$select_end_date = $select_end_date");
		// DataBaseより情報取得
		$calendar_plan_data = $CI->common->get_calendar_plan_data($shbn,$select_start_date,$select_end_date);
		if(is_null($calendar_plan_data))
		{
			// データ取得出来なかった場合には日付のみ設定
			for($i=(int)$start_day; $i < ((int)$end_day)+1; $i++)
			{
//				log_message('debug',"\$day = $i");
				$plan_day = date("Ymd", mktime(0, 0, 0, $month , $i, $year)); // リンクURLに渡す日付
				$calendar_data[$i]['day'] = date("d", mktime(0, 0, 0, $month , $i, $year)); // 日付を取得（ゼロパディング）
				$calendar_data[$i]['week'] = strtolower(date("l", mktime(0, 0, 0, $month , $i, $year))); // 曜日取得（英語）
				// 遷移先URL設定
//				$calendar_data[$i]['link_url'] = $CI->config->item('base_url') . "index.php/plan";
					$calendar_data[$i]['link_url'] = $CI->config->item('base_url') . "index.php/plan/index/" . $plan_day;
				// 日報情報取得
				$calendar_data[$i]['date_time'][] = NULL; // 開始時刻を取得
				$calendar_data[$i]['result_data'][] = NULL; // 相手先を取得
			}
		}else{
			$day_count = 0; // 経過日数カウンター初期化
			$data_count = 0; // 取得データ数カウンター初期化
			for($i=(int)$start_day; $i < ((int)$end_day)+1; $i++)
			{
				$day = (int)$start_day + $day_count; // 開始日に経過日数を加算
//				log_message('debug',"\$day = $day");
				// 取得したデータの日付情報を数値で取得
				if( ! empty($calendar_plan_data[$data_count]['ymd']))
				{
					$date_day = (int)substr($calendar_plan_data[$data_count]['ymd'],6,2);
				}else{
					$date_day = NULL;
				}
				// カレンダー日付とデータ日付の一致チェック
				if($day === $date_day)
				{
					while($day === $date_day)
					{
						$plan_day = date("Ymd", mktime(0, 0, 0, $month , $date_day, $year)); // リンクURLに渡す日付
						$calendar_data[$date_day]['day'] = substr($calendar_plan_data[$data_count]['ymd'],6,2); // 日付を取得（ゼロサプレス）
						$calendar_data[$date_day]['week'] = strtolower(date("l", mktime(0, 0, 0, $month , $date_day, $year))); // 曜日取得（英語）
						// 遷移先URL設定
//						$calendar_data[$date_day]['link_url'] = $CI->config->item('base_url') . "index.php/plan";
						$calendar_data[$date_day]['link_url'] = $CI->config->item('base_url') . "index.php/plan/index/" . $plan_day;
						// 日報情報取得
						$calendar_data[$date_day]['date_time'][] = $calendar_plan_data[$data_count]['sthm']; // 開始時刻を取得
						if($calendar_plan_data[$data_count]['action_type'] === "内勤"){
							$sagyo = $CI->sgmtb030->get_ichiran_name(MY_PLAN_SAGYONAIYO_KBN,$calendar_plan_data[$data_count]['viewnm']);
							$sagyo = $sagyo == 'その他' ? $calendar_plan_data[$data_count]['opt'] : $sagyo;
							if(isset($sagyo)){
								log_message('debug',"sagyo = ".$sagyo);
								$calendar_data[$day]['result_data'][] = $sagyo; // 相手先を取得
							}else{
								$calendar_data[$day]['result_data'][] = NULL;
							}
						}else{
							$calendar_data[$day]['result_data'][] = $calendar_plan_data[$data_count]['viewnm']; // 相手先を取得
						}
						//$calendar_data[$date_day]['result_data'][] = $calendar_plan_data[$data_count]['viewnm']; // 相手先を取得
						// 次のデータの有無確認
						$data_count++;
						log_message('debug',"\$data_count = $data_count");
						if( ! empty($calendar_plan_data[$data_count]['ymd']))
						{
							$date_day = (int)substr($calendar_plan_data[$data_count]['ymd'],6,2);
						}else{
							$date_day = NULL;
						}
						log_message('debug',"\$date_day = $date_day");
					}
				}else{
					$plan_day = date("Ymd", mktime(0, 0, 0, $month , $day, $year)); // リンクURLに渡す日付
					$calendar_data[$day]['day'] = date("d", mktime(0, 0, 0, $month , $day, $year)); // 日付を取得（ゼロパディング）
					$calendar_data[$day]['week'] = strtolower(date("l", mktime(0, 0, 0, $month , $day, $year))); // 曜日取得（英語）
					// 遷移先URL設定
//					$calendar_data[$day]['link_url'] = $CI->config->item('base_url') . "index.php/plan";
					$calendar_data[$day]['link_url'] = $CI->config->item('base_url') . "index.php/plan/index/" . $plan_day;
					// 日報情報取得
					$calendar_data[$day]['date_time'][] = NULL; // 開始時刻を取得
					$calendar_data[$day]['result_data'][] = NULL; // 相手先を取得
				}
				$day_count++;
			}
		}
		log_message('debug',"========== Calendar_manager _get_plan_data end ==========");
		return $calendar_data;
	}
	
	/**
	 * 取得した過去日・未来日のデータを合わせて
	 * １つの配列にして戻す
	 * 
	 * @access	private
	 * @param	string $pre_calendar_data 予定
	 * @param	string $res_calendar_data 日報
	 * @param	string $interval_day 現在日
	 * @param	string $last_day その月の最後の日
	 * @return	array
	 */
	function _match_calendar_data($pre_calendar_data,$res_calendar_data,$interval_day,$last_day)
	{
		log_message('debug',"========== Calendar_manager _match_calendar_data start ==========");
		log_message('debug',"\$interval_day = $interval_day");
		log_message('debug',"\$last_day = $last_day");
		// 初期化
		$calendar_data = array();
		// 過去日情報を設定
//		for($i=1; $i < (int)$interval_day; $i++)
		for($i=1; $i <= (int)$interval_day; $i++)
		{
			// 当日
			// 初期化
			$data_count = 0;
			// 日付・曜日情報取得
			$calendar_data[$i]['day'] = $res_calendar_data[$i]['day'];
			$calendar_data[$i]['week'] = $res_calendar_data[$i]['week'];
			// リンクURL情報取得
			$calendar_data[$i]['link_url'] = $res_calendar_data[$i]['link_url'];
			// 日報情報のデータ数を取得
			$data_count = count($res_calendar_data[$i]['result_data']);
			log_message('debug',"\$data_count = $data_count");
			// データ数が"0"の場合は"NULL"を設定
			if($data_count === MY_ZERO)
			{
				$calendar_data[$i]['date_time'][$data_count] = NULL;
				$calendar_data[$i]['result_data'][$data_count] = NULL;
			}else{
				// 当日の日報が1件もない場合は処理しない
				if($i != (int)$interval_day OR !empty($res_calendar_data[$interval_day]['date_time'][0])){
					foreach($res_calendar_data[$i]['date_time'] as $key => $value)
					{
						$calendar_data[$i]['date_time'][] = $value;
					}
					foreach($res_calendar_data[$i]['result_data'] as $key => $value)
					{
						$calendar_data[$i]['result_data'][] = $value;
					}
				}
			}
		}
		// 未来日情報を設定
		for($i=$interval_day; $i <= $last_day; $i++)
		{
			// 日報データがない
			if(empty($calendar_data[$i]['date_time'])){
				// 初期化
				$data_count = 0;
				// 日付・曜日情報取得
				$calendar_data[$i]['day'] = $pre_calendar_data[$i]['day'];
				$calendar_data[$i]['week'] = $pre_calendar_data[$i]['week'];
				// リンクURL情報取得
	//			$calendar_data[$i]['link_url'] = $pre_calendar_data[$i]['link_url'];
				if (empty($calendar_data[$i]['link_url'])) {
					$calendar_data[$i]['link_url'] = $pre_calendar_data[$i]['link_url'];
				}
				// 日報情報のデータ数を取得
				$data_count = count($pre_calendar_data[$i]['result_data']);
				// データ数が"0"の場合は"NULL"を設定
				if($data_count === MY_ZERO)
				{
					$calendar_data[$i]['date_time'][$data_count] = NULL;
					$calendar_data[$i]['result_data'][$data_count] = NULL;
				}else{
					foreach($pre_calendar_data[$i]['date_time'] as $key => $value)
					{
						$calendar_data[$i]['date_time'][] = $value;
					}
					foreach($pre_calendar_data[$i]['result_data'] as $key => $value)
					{
						$calendar_data[$i]['result_data'][] = $value;
					}
				}
			}
		}
		log_message('debug',"========== Calendar_manager _match_calendar_data end ==========");
		return $calendar_data;
	}
	
//	function view_calendar(){
//		// 初期化
//		$CI =& get_instance();
//		// テンプレート
//		$prefs['template'] = '
//			{table_open}<table border="1" cellpadding="2" cellspacing="1">{/table_open}
//			{heading_row_start}<tr>{/heading_row_start}
//			{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
//			{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
//			{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
//			{heading_row_end}</tr>{/heading_row_end}
//			{week_row_start}<tr>{/week_row_start}
//			{week_day_cell}<td>{week_day}</td>{/week_day_cell}
//			{week_row_end}</tr>{/week_row_end}
//			{cal_row_start}<tr>{/cal_row_start}
//			{cal_cell_start}<td>{/cal_cell_start}
//			{cal_cell_content}<a href="{content}">{day}</a>{/cal_cell_content}
//			{cal_cell_content_today}<div class="highlight"><a href="{content}">{day}</a></div>{/cal_cell_content_today}
//			{cal_cell_no_content}{day}{/cal_cell_no_content}
//			{cal_cell_no_content_today}<div class="highlight">{day}</div>{/cal_cell_no_content_today}
//			{cal_cell_blank}&nbsp;{/cal_cell_blank}
//			{cal_cell_end}</td>{/cal_cell_end}
//			{cal_row_end}</tr>{/cal_row_end}
//			{table_close}</table>{/table_close}
//		';
//		$CI->load->library('calendar', $prefs);
//		echo $CI->calendar->generate();
//		
//	}
	
	
	
}

// END Calendar_manager class

/* End of file Calendar_manager.php */
/* Location: ./application/libraries/calendar_manager.php */
