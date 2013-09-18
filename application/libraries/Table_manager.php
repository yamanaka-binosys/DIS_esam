<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Table_manager {

	/**
	 * カレンダーテーブルのHTMLを作成
	 *
	 * @access	public
	 * @param	array_string
	 * @return	string
	 */

	public function set_calendar_table($calendar_data)
	{
		// 初期化
		$CI =& get_instance();
		$table_set = $CI->config->item('s_top_calendar');
		$calendar_color = $CI->config->item('s_calendar_color');
		$data_max = 0;

		// カレンダーHTML作成
		$day_table = "";
		$day_table .= "<table style=\"border-collapse:" . $table_set['collapse'] . "\">\n";
		$day_table .= "<tr style=\"height:18px;\">\n";
//		$day_table .= "<tr style=\"height:" . $table_set['font-size'] . "\">\n";
		$day_table .= "<th style=\"" . $table_set['th_align'] . ";\">\n";
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
			$day_table .= " text-align:center;";
			$day_table .= " width:" . $table_set['th_width'] . "\">\n";
			// リンク先が直接スケジュール画面の時に使用
			// $table_set['a_href']の値を「plan/index/」に変更する必要有
//			$day_table .= "<a href=\"" . $CI->config->item('base_url') . $table_set['a_href'] . "\"";
			$day_table .= "<a href=\"" . $CI->config->item('base_url') . $table_set['a_href'] . $calendar_data[$i]['link_day'] . "\"";
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
			$day_table .= " width:" . $table_set['th_width'] . ";";
			$day_table .= " height:" . $table_set['td_height'] . ";";
			$data_max = count($calendar_data[$i]) - MY_TOP_CALENDAR_DIF;
			// 6つ以上データがある場合、スクロール表示
/*			if($data_max > MY_CALENDAR_SCROLL)
			{
				$day_table .= " overflow:scroll;";
			}
 * */
			$day_table .= " background-color:" . $table_set['td_bak_color'] . "\">\n";
			$day_table .= " <div style=\"";
			$char_cnt = MY_CALENDAR_CHAR_CNT;
			// 領域に収まらないデータ数の場合、スクロール表示
			if($data_max > MY_TOP_CALENDAR_SCROLL)
			{
				$day_table .= " overflow-y:scroll;";
				$char_cnt = MY_CALENDAR_SCR_CHAR_CNT;
			}
			$day_table .= "width:118px;height:70px;\">";
			$day_table .= "<table>\n";
			// データが存在すれば格納
			if(! empty($calendar_data[$i][MY_THREE]))
			{
				for ($j=MY_THREE; ! empty($calendar_data[$i][$j]); $j++)
				{
					$day_table .= "<tr>\n<td style=\"width:36px;text-align:left;\">";
					$day_table .= $calendar_data[$i][$j]['time']."</td>\n";
					$day_table .= "<td style=\"width:72px;text-align:left;\">";
					$day_table .= mb_strimwidth($calendar_data[$i][$j]['title'],0,$char_cnt,'…','UTF-8');
					$day_table .= "</td>\n</tr>\n";
				}
			}else{
				$day_table .= "<tr>\n<td style=\"width:118px;\"></td>\n</tr>";
			}
			$day_table .= "</table>\n";
		}
		$day_table .= "</td>\n</tr>\n</table>\n";

		return $day_table;
	}

	/**
	 * 選択した月のスケジュールHTMLを作成
	 *
	 * @access  public
	 * @param   string $year 年（YYYY）
	 * @param   string $month 月（MM）
	 * @param   array $calendar_data カレンダー情報
	 * @return  string
	 */
	public function set_select_calendar($year,$month,$calendar_data)
	{
		log_message('debug',"========== Table_manager set_select_calendar start ==========");
		log_message('debug',"\$year = $year");
		log_message('debug',"\$month = $month");

		// 初期化
		$CI =& get_instance();
		$table_set = $CI->config->item('s_select_calendar');        
		$calendar_color = $CI->config->item('s_calendar_color');    // 曜日に適用する色
		$day_week_ja = $CI->config->item('c_day_week_ja');          // 曜日（日本語）
		$day_week_en = $CI->config->item('c_day_week_en');          // 曜日（英語）
		$calendar_mode = $CI->session->userdata('calendar_mode');
		$str_calendar = "";                                         // カレンダーHTML文字列
		$disabled = "";                                             // 前月ボタンdisabled設定
		$day_count = MY_INT_START_DAY;                              // 日付
		$next_start_day = MY_INT_START_DAY;                         // 週の始まりの日
		$count = MY_ZERO;                                           // 週に何日データが存在したか計測
		$appointed_day = date("Ymd");                               // システム日付の年月日
		$char_cnt = MY_CALENDAR_CHAR_CNT;
		// 表示中の年月
		$str_calendar .= "<input type=\"hidden\" name=\"year_month\" value=\"".$year.$month."\">\n";
		// 表題設定
		$str_calendar .= "<table style=\"width:826px;border-collapse;border-collapse:collapse;font-size:9px;\">\n";
		$str_calendar .= "<tr style=\"height:" . $table_set['title_tr_height'] . "\">\n";
		$str_calendar .= "<td style=\"width:118px;text-align:center;\">\n";
		$str_calendar .= "<input type=\"submit\" style=\"height: 21px; width: 110px;\" name=\"previous_month\" value=\"<<前月\" id=\"previous_month\" />\n";
		$str_calendar .= "</td>\n";
		$str_calendar .= "<th";
		$str_calendar .= " colspan=\"" . $table_set['title_th_span'] . "\"";
		$str_calendar .= " style=\"border:" . $table_set['title_th_border'] . ";";
		$str_calendar .= " font-size:" . $table_set['title_th_font_size'] . ";";
		$str_calendar .= " text-align:" . $table_set['title_th_text_align'] . ";";
		$str_calendar .= " width:590px; ";
		$str_calendar .= " background-color:" . $table_set['title_th_back_color'] . ";\">\n";
		$str_calendar .= $year . "年&ensp;" . $month . "月";
		$str_calendar .= "<input type=\"hidden\" name=\"select_month\" value=\"".$year.$month."\">\n";
		$str_calendar .= "</th>\n";
		$str_calendar .= "<td style=\"width:118px;text-align:center;\">\n";
		$str_calendar .= "<input type=\"submit\" style=\"height: 21px; width: 110px;\" name=\"next_month\" value=\"翌月>>\" id=\"next_month\"/>\n";
		$str_calendar .= "</td>\n";
		$str_calendar .= "<tr height=\"3px\"><td colspan=\"3\"> </td></tr>";
		$str_calendar .= "</tr>";
		// 曜日（日本語）設定
		$str_calendar .= "<tr>\n";
		for($i=MY_ZERO; $i < MY_WEEK_DAY; $i++)
		{
			$str_calendar .= "<th style=\"color:" . $calendar_color[$day_week_en[$i]] . ";";
			$str_calendar .= " border-collapse:" . $table_set['week_th_border_col'] . ";";
			$str_calendar .= " border:" . $table_set['week_th_border'] . ";";
			$str_calendar .= " width:" . $table_set['week_th_width'] . ";";
			$str_calendar .= " text-align:center;";
			$str_calendar .= " background-color:" . $table_set['week_th_back_color'] . ";";
			$str_calendar .= "\">" . $day_week_ja[$i] . "</th>\n";
		}
		$str_calendar .= "</tr>\n";
		// カレンダーデータ設定
		for($week_count=MY_ZERO; $week_count < MY_MONTH_WEEK; $week_count++)
		{
			$str_calendar .= "<tr>\n";
			// 開始日付初期化・カウント初期化
			$day_count = $next_start_day + $count;
			$next_start_day = $day_count;
			$count = MY_ZERO; // データ数カウンター
			// 日曜～土曜までの日付を設定
			foreach($day_week_en as $key => $value)
			{
				if(isset($calendar_data[$day_count]['week']))
				{
					if($value === $calendar_data[$day_count]['week'])
					{
						$str_calendar .= "<th style=\"border-collapse:" . $table_set['day_th_border_col'] . ";";
						$str_calendar .= " border:" . $table_set['day_th_border'] . ";";
						$str_calendar .= " width:" . $table_set['day_th_width'] . ";";
						$str_calendar .= " text-align:center;";
						$str_calendar .= " background-color:" . $table_set['day_th_back_color'] . "\">\n";
						$str_calendar .= "<a href=\"" . $calendar_data[$day_count]['link_url'] . "\"";
						$str_calendar .= " style=\"color:" . $calendar_color[$value] . ";";
						$str_calendar .= " text-decoration:" . $table_set['day_a_text_decoration'] . "\">";
						$str_calendar .= $calendar_data[$day_count]['day'];
						$str_calendar .= "</a>\n";
						$str_calendar .= "</th>\n";
						$day_count++; // 日付に１追加
						$count++; // カウント数に１追加
					}else{
						$str_calendar .= "<th></th>\n";
					}
				}else{
					$str_calendar .= "<th></th>\n";
				}
			}
			$str_calendar .= "</tr>\n";
			// カレンダーデータ設定
			$day_count = $next_start_day; // 開始日初期化
			$str_calendar .= "<tr>\n";
			foreach($day_week_en as $key => $value)
			{
				$data_max = 0;
				if(isset($calendar_data[$day_count]['week']))
				{
					if($value === $calendar_data[$day_count]['week'])
					{
						$data_max = count($calendar_data[$day_count]['date_time']);
						$select_day = $year . $month . $calendar_data[$day_count]['day']; // 選択月の年月
						$str_calendar .= "<td align=\"" . $table_set['data_td_align'] . "\"";
						$str_calendar .= " valign=\"" . $table_set['data_td_valign'] . "\"";
						$str_calendar .= " style=\"border-collapse:" . $table_set['data_td_border_col'] . ";";
						$str_calendar .= " border:" . $table_set['data_td_border'] . ";";
						// 当日と日付が同じであれば背景色を変更
						if($appointed_day === $select_day)
						{
							$str_calendar .= " background-color:" . $table_set['data_td_today_back_color'] . ";";
						}else{
							$str_calendar .= " background-color:" . $table_set['data_td_back_color'] . ";";
						}
						//$str_calendar .= " width:" . $table_set['day_th_width'] . ";";
						//$str_calendar .= " width:116px;";
						$str_calendar .= " height:" . $table_set['data_td_height'] . ";";
						$str_calendar .= " white-space:nowrap;";
						$str_calendar .= " \">\n";
						$str_calendar .= " <div style=\"overflow:auto;height:" . $table_set['data_td_height'] . ";\">";
						
						
						$data_count = 0; // データ数カウンター
						// 内容作成
						foreach($calendar_data[$day_count]['date_time'] as $key => $value)
						{
							if(!empty($value)){
								$time = substr($value,0,2) . ":" . substr($value,2,2);
								$subject = mb_strimwidth($calendar_data[$day_count]['result_data'][$data_count],0,12,'…','UTF-8');
								$data_count++;
								$str_calendar .= "<span class=\"cal-time\"/>{$time}</span><span class=\"cal-subject\">{$subject}</span><br />";
							}
						}
						$day_count++;
					}else{
						$str_calendar .= "<td>\n";
						$str_calendar .= "<br>\n";
					}
				}else{
					$str_calendar .= "<td>\n";
					$str_calendar .= "<br>\n";
				}
				$str_calendar .= "</div>\n";
				$str_calendar .= "</td>\n";
			}
			$str_calendar .= "</tr>\n";
		}
		$str_calendar .= "</table>\n";
		log_message('debug',"========== Table_manager set_select_calendar end ==========");
		return $str_calendar;
	}

	/**
	 * 選択月・翌月・先月のリンクHTMLを作成
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_three_month_table($select_month_date)
	{
		// 初期化
		$CI =& get_instance();
		$select_month_status = $CI->config->item('s_select_calendar_month');

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
		$select_month_table .= "</table>\n";

		return $select_month_table;
	}

	/**
	 * カレンダー表示モードボタンのHTMLを作成
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function set_mode_table($calendar_mode = MY_CALENDAR_MIX)
	{
		// 初期化
		$CI =& get_instance();
		$select_month_status = $CI->config->item('s_select_calendar_month');
		$mix_disabled = "disabled";
		$plan_disabled = "";
		// 選択モードのボタン無効化処理
		if($calendar_mode === MY_CALENDAR_ALLPLAN){
			$mix_disabled = "";
			$plan_disabled = "disabled";
		}
		$select_mode_table = "";
		$select_mode_table .= "<table>\n";
		$select_mode_table .= "<tr>\n";
		$select_mode_table .= "<td>\n";
		$select_mode_table .= "<input type=\"submit\" name=\"calendar_mix\" style=\"height:30px; width:120px; font-weight:bold;\" value=\"予定・実績へ切替\" " .$mix_disabled.">";
		$select_mode_table .= "</td>\n";
		$select_mode_table .= "<td>\n";
		$select_mode_table .= "<input type=\"submit\" name=\"calendar_allplan\" style=\"height:30px; width:100px; font-weight:bold;\" value=\"予定へ切替\" " .$plan_disabled.">";
		$select_mode_table .= "</td>\n";
		$select_mode_table .= "</tr>\n";
		$select_mode_table .= "</table>\n";
		
		return $select_mode_table;
	}
	
	/**
	 * テーブル情報設定
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
//	function _set_table($table_data)
	function set_table($table,$title,$link,$other,$data)
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
					$string_table .= "<a href=\"" . $CI->config->item('base_url') . $link['link'] . "\">";
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
	 * 整形された予定情報を元に表示するHTML文字列を作成し返す
	 *
	 * @access public
	 * @param  array $plan_data 予定情報、他
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_plan_table($plan_data = NULL)
	{
		// 引数チェック
		if(is_null($plan_data))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		return NULL;
	}

	/**
	 * 確認者検索の部署からの検索HTMLを作成
	 *
	 * @access public
	 * @param  array $shbn 社番
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function search_conf_set_b_table($shbn,$post=NULL,$honbu=NULL,$bu=NULL,$unit=NULL)
	{
		log_message('debug',"================= search_conf_set_b_table start =====================");
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_checker_search_b_data');                      // テーブル設定情報取得
		$title = $CI->config->item('s_checker_search_b_title');                     // タイトル情報取得
		$other['button'] = $CI->config->item('s_checker_b_search_button');          // 追加情報取得
		$td_data[] = $CI->config->item('s_checker_search_honbu');                   // ドロップダウン項目本部の設定
		$td_data[] = $CI->config->item('s_checker_search_bu');                      // ドロップダウン項目部の設定
		$td_data[] = $CI->config->item('s_checker_search_unit');                    // ドロップダウン項目ユニットの設定
		$td_data[] = $CI->config->item('s_checker_search_btn');                     // 部検索ボタンの設定
		//$data      = $this->_get_search_busyo_data($shbn,$post,TRUE);               // 部署情報取得
		if(isset($unit) && $unit !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu,$bu,$unit);		//部選択時
		}else if(isset($bu) && $bu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu,$bu);		//部選択時
		}else if(isset($honbu) && $honbu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu);		//本部選択時
		}else{
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE);               // 部署情報取得
		}
		//var_dump($data);
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other,$data); // 部署コード選択テーブル作成
		log_message('debug',"================= search_conf_set_b_table end =====================");
		return $string_table;
	}

	/**
	 * 確認者検索の部署情報取得
	 *
	 * @access	public
	 * @param	array  $shbn 社番
	 * @return	string $data
	 */
	public function _get_search_busyo_data($shbn,$post = NULL,$u_search_flg = FLASE,$select_honbu=NULL,$select_bu=NULL,$select_unit=NULL,$select_user=NULL,$user_flg=FALSE,$memo_flg=FALSE)
	{
		$CI =& get_instance();

		$user_data = FALSE;
		$CI->load->model('sgmtb010'); // ユーザー情報
		$CI->load->model('sgmtb020'); // 部署情報
		// ユーザー情報取得
		log_message('debug',"================= _get_search_busyo_data =====================");
		$user_data = $CI->sgmtb010->get_search_user_data($shbn);
		

		
		log_message('debug',"user_data = ". array($user_data));
		// 全本部情報取得
		$honbu = $CI->sgmtb020->get_honbu_name_data();
		if(isset($select_unit) && $select_unit !=""){
			// 部情報取得
			$bu = $CI->sgmtb020->get_bu_name_data_select($select_honbu,$select_bu);
			if($select_honbu=='XXXXX'){
				$bu =  array();
			}
			
			
			// 課・ユニット情報取得
			$unit = $CI->sgmtb020->get_unit_name_data_select($select_honbu,$select_bu);
			if($select_bu=='XXXXX'){
				$unit =  array();
			}
			
			if($unit==NULL){
				$unit = array();
			}
			
			
			$user = $CI->sgmtb010->get_shin_data($select_honbu,$select_bu,$select_unit);
			if($select_unit=='XXXXX'){
				$user = array();
			}
			// 全部署データの生成
			if($user_flg=='1' || $user_flg=='2' || $memo_flg=='2'){
			$all_data = array($honbu,$bu,$unit,$user);
			}else{
			$all_data = array($honbu,$bu,$unit);
			}
		}else if(isset($select_bu) && $select_bu !=""){
			// 部情報取得
			$bu = $CI->sgmtb020->get_bu_name_data_select($select_honbu,$select_bu);
			if($select_honbu=='XXXXX'){
				$bu =  array();
			}
			log_message('debug','判定');
			// 課・ユニット情報取得
			$unit = $CI->sgmtb020->get_unit_name_data_select($select_honbu,$select_bu);
			
			
			if($unit==NULL){
				$unit = array();
			}
			
		
			$user = array();
			// 全部署データの生成
			if($user_flg=='1' || $user_flg=='2' || $memo_flg=='2'){
			$all_data = array($honbu,$bu,$unit,$user);
			}else{
			$all_data = array($honbu,$bu,$unit);
			}
		}else if(isset($select_honbu) && $select_honbu !=""){
			// 部情報取得
			$bu = $CI->sgmtb020->get_bu_name_data_select($select_honbu);
			if($select_honbu=='XXXXX'){
				$bu =  array();
			}
			
			// 課・ユニット情報取得
			//$unit = $CI->sgmtb020->get_unit_name_data_select($select_honbu);
			$unit = array();
			
			$user = array();
			// 全部署データの生成
			if($user_flg=='1' || $user_flg=='2' || $memo_flg=='2'){
			$all_data = array($honbu,$bu,$unit,$user);
			}else{
			$all_data = array($honbu,$bu,$unit);
			}
			
		}else{
			
			// 全部情報取得
			if($user_data['honbucd']=='XXXXX'){
				$bu =  array();
			}else{

				$bu = $CI->sgmtb020->get_bu_name_data_select($user_data['honbucd']);
				//$bu = $CI->sgmtb020->get_bu_name_data_select($honbu);
				if($bu ==""){

				$bu=array();
				}
			} 
			
			
			if($user_data['bucd']=='XXXXX'){
				$unit =  array();
			}else{
				// 全課・全ユニット情報取得
				$unit = $CI->sgmtb020->get_unit_name_data_select($user_data['honbucd'],$user_data['bucd']);
				
			}
			
			$user = $CI->sgmtb010->get_shin_data($user_data['honbucd'],$user_data['bucd'],$user_data['kacd']);
			
			// 全部署データの生成
			if($user_flg=='1' || $user_flg=='2' || $memo_flg=='2'){
			$all_data = array($honbu,$bu,$unit,$user);
			}else{
			$all_data = array($honbu,$bu,$unit);
			}
		}
		 // HTML渡す用に生成
		 $honbu_data = array(MY_DB_BU_ESC => "");
		 $bu_data = array(MY_DB_BU_ESC => ""); // 本部付け社員判別
		 $unit_data = array(MY_DB_BU_ESC => ""); // 部署付け社員判別
		 $u_data = array('' => "--選択してください--");
		 foreach($all_data as $busyo_data)
		 {
			 foreach($busyo_data as $b_data)
			 {
			 	 if(isset($b_data['shinnm'])){
			 	 	$u_data[$b_data['shbn']] = $b_data['shinnm'];
			 	 
				 // 課・ユニット名
				 }else if(isset($b_data['kacd']))
				 {
					 $unit_data[$b_data['honbucd'].$b_data['bucd'].$b_data['kacd']] = $b_data['bunm'];
				 }
				 // 部名
				 else if(isset($b_data['bucd']) AND !isset($b_data['kacd']))
				 {
					 $bu_data[$b_data['honbucd'].$b_data['bucd']] = $b_data['bunm'];
				 }
				 // 本部名
				 else if(!isset($b_data['bucd']) AND !isset($b_data['kacd']))
				 {
					 $honbu_data[$b_data['honbucd']] = $b_data['bunm'];
				 }
				 
			 }
		 }




		// 部署名選択項目判定		
		if(!empty($post) && isset($post['name']) && is_null($post['name'])){
			log_message('debug',"============== post===============");
			// 検索条件を選択
			//$select_data = $post;
			$select_data['honbucd'] = $user_data['honbucd'];
			$select_data['bucd'] = $user_data['honbucd'].$user_data['bucd'];
			$select_data['kacd'] = $user_data['honbucd'].$user_data['bucd'].$user_data['kacd'];
			log_message('debug',"bucd = ".$user_data['bucd']);
	/*	}else if($memo_flg=='2' && isset($post['user']) &&  $post['user'] !=""){
			$select_data['honbucd'] = $post['honbucd'];
			$select_data['bucd'] = $post['honbucd'].$post['bucd'];
			$select_data['kacd'] = $post['honbucd'].$post['bucd'].$post['kacd'];
			$select_data['shbn'] = $post['user'];
		}else if($memo_flg=='2' && isset($post['kacd']) &&  $post['kacd'] !=""){
			$select_data['honbucd'] = $post['honbucd'];
			$select_data['bucd'] = $post['honbucd'].$post['bucd'];
			$select_data['kacd'] = $post['honbucd'].$post['bucd'].$post['kacd'];
			$select_data['shbn'] = $post['user'];	
		}else if($memo_flg=='2' && isset($post['bucd']) &&  $post['bucd'] !=""){
			$select_data['honbucd'] = $post['honbucd'];
			$select_data['bucd'] = $post['honbucd'].$post['bucd'];
			$select_data['kacd'] = $post['honbucd'].$post['bucd'].$post['kacd'];
			$select_data['shbn'] = $post['user'];
		}else if($memo_flg=='2' && isset($post['honbucd']) &&  $post['honbucd'] !=""){
			$select_data['honbucd'] = $post['honbucd'];
			$select_data['bucd'] = $post['honbucd'].$post['bucd'];
			$select_data['kacd'] = $post['honbucd'].$post['bucd'].$post['kacd'];*/
			$select_data['shbn'] = $post['user'];
		}else if(isset($select_user) && $select_user!=""){
			$select_data['honbucd'] = $select_honbu;
			$select_data['bucd'] = $select_honbu.$select_bu;
			$select_data['kacd'] = $select_honbu.$select_bu.$select_unit;
			$select_data['shbn']= $select_user;
		}else if(isset($select_unit) && $select_unit!=""){
			$select_data['honbucd'] = $select_honbu;
			$select_data['bucd'] = $select_honbu.$select_bu;
			$select_data['kacd'] = $select_honbu.$select_bu.$select_unit;
			$select_data['shbn']= NULL;
		}else if(isset($select_bu) && $select_bu!=""){
			$select_data['honbucd'] = $select_honbu;
			$select_data['bucd'] = $select_honbu.$select_bu;
			$select_data['kacd'] = NULL;	
			$select_data['shbn']= NULL;
		}else if(isset($select_honbu) && $select_honbu!=""){
			$select_data['honbucd'] = $select_honbu;
			$select_data['bucd'] = NULL;
			$select_data['kacd'] = NULL;
			$select_data['shbn']= NULL;
		}else{
			log_message('debug',"============== non post===============");
			
			// 初回時、自分の所属部署コードを選択
			$select_data['honbucd'] = $user_data['honbucd'];
			$select_data['bucd'] = $user_data['honbucd'].$user_data['bucd'];
			$select_data['kacd'] = $user_data['honbucd'].$user_data['bucd'].$user_data['kacd'];
			$select_data['shbn'] = $user_data['shbn'];
		}
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');
		
		if($memo_flg=='1'){
		
		
			$data[0] = array(
			'title_name' => '',
			'name' => 'honbucd',
			'data' => $honbu_data,
			'check' => $select_data['honbucd'],
			'extra' => ' id="daibunrui_list" onChange="memo_reload_dropdown('."'$base_url'".');"'
		);
		// 部
		$data[1] = array(
			'title_name' => '',
			'name' => 'bucd',
			'data' => $bu_data,
			'check' => $select_data['bucd'],
			
		);
		}else if($memo_flg=='2'){
			$data[0] = array(
			'title_name' => '',
			'name' => 'honbucd',
			'data' => $honbu_data,
			'check' => $select_data['honbucd'],
			'extra' => ' id="daibunrui_list" onChange="memo_reload_dropdown('."'$base_url'".');"'
		);
		// 部
		$data[1] = array(
			'title_name' => '',
			'name' => 'bucd',
			'data' => $bu_data,
			'check' => $select_data['bucd'],
			'extra' => ' id="bu_list" onChange="memo_reload_dropdown_unit('."'$base_url'".');"'
		);
		// 課・ユニット
		$data[2] = array(
			'title_name' => '',
			'name' => 'kacd',
			'data' => $unit_data,
			'check' => $select_data['kacd'],
			'extra' => ' id="ka_list" onChange="memo_reload_dropdown_user('."'$base_url'".');"'
			
		);
						
		$data[3] = array(
			'title_name' => '',
			'name' => 'user',
			'data' => $u_data,
			'check' =>$select_data['shbn'],
			'extra' => NULL
		);
		
		}else if($user_flg=='3'){
		
		
			$data[0] = array(
			'title_name' => '',
			'name' => 'honbucd',
			'data' => $honbu_data,
			'check' => $select_data['honbucd'],
			'extra' => ' id="daibunrui_list" onChange="memo_reload_dropdown('."'$base_url'".');"'
		);
		// 部
		$data[1] = array(
			'title_name' => '',
			'name' => 'bucd',
			'data' => $bu_data,
			'check' => $select_data['bucd'],
			'extra' => ' id="bu_list" onChange="memo_reload_dropdown_unit('."'$base_url'".');"'
		);
		// 課・ユニット
		$data[2] = array(
			'title_name' => '',
			'name' => 'kacd',
			'data' => $unit_data,
			'check' => $select_data['kacd'],
			'extra' => ' '
		);
		
		}else if($user_flg=='1'){
		
			$data[0] = array(
			'title_name' => '',
			'name' => 'honbucd',
			'data' => $honbu_data,
			'check' => $select_data['honbucd'],
			'extra' => ' id="daibunrui_list" onChange="n_reload_dropdown('."'$base_url'".');"'
		);
		// 部
		$data[1] = array(
			'title_name' => '',
			'name' => 'bucd',
			'data' => $bu_data,
			'check' => $select_data['bucd'],
			'extra' => ' id="bu_list" onChange="n_reload_dropdown_unit('."'$base_url'".');"'
		);
		// 課・ユニット
		$data[2] = array(
			'title_name' => '',
			'name' => 'kacd',
			'data' => $unit_data,
			'check' => $select_data['kacd'],
			'extra' => ' id="ka_list" onChange="n_reload_dropdown_user('."'$base_url'".');"'
		);
						
		$data[3] = array(
			'title_name' => '',
			'name' => 'user',
			'data' => $u_data,
			'check' =>$select_data['shbn'],
			'extra' => ' id="shbn_list" onChange="n_reload_dropdown_aitesk('."'$base_url'".');"'
		);
		
		}else if($user_flg=='2'){
		
			$data[0] = array(
			'title_name' => '',
			'name' => 'honbucd',
			'data' => $honbu_data,
			'check' => $select_data['honbucd'],
			'extra' => ' id="daibunrui_list" onChange="memo_n_reload_dropdown('."'$base_url'".');"'
		);
		// 部
		$data[1] = array(
			'title_name' => '',
			'name' => 'bucd',
			'data' => $bu_data,
			'check' => $select_data['bucd'],
			'extra' => ' id="bu_list" onChange="memo_n_reload_dropdown_unit('."'$base_url'".');"'
		);
		// 課・ユニット
		$data[2] = array(
			'title_name' => '',
			'name' => 'kacd',
			'data' => $unit_data,
			'check' => $select_data['kacd'],
			'extra' => ' id="ka_list" onChange="memo_n_reload_dropdown_user('."'$base_url'".');"'
			
		);
						
		$data[3] = array(
			'title_name' => '',
			'name' => 'user',
			'data' => $u_data,
			'check' =>$select_data['shbn'],
			'extra' => NULL
		);
		
		}else{
		
		// 部署
		$data[0] = array(
			'title_name' => '',
			'name' => 'honbucd',
			'data' => $honbu_data,
			'check' => $select_data['honbucd'],
			'extra' => ' id="daibunrui_list" onChange="reload_dropdown('."'$base_url'".');"'
		);
		// 部
		$data[1] = array(
			'title_name' => '',
			'name' => 'bucd',
			'data' => $bu_data,
			'check' => $select_data['bucd'],
			'extra' => ' id="bu_list" onChange="reload_dropdown_unit('."'$base_url'".');"'
		);
		// 確認者検索画面時のみ
		if($u_search_flg)
		{
			// 課・ユニット
			$data[2] = array(
				'title_name' => '',
				'name' => 'kacd',
				'data' => $unit_data,
				'check' => $select_data['kacd'],
				'extra' => NULL
				
			);
		}
		}

		log_message('debug',"honbucd = ".$select_data['honbucd']);
		log_message('debug',"bucd = ".$select_data['bucd']);

		return $data;
	}

	/**
	 * 確認者検索の氏名検索のHTMLを作成
	 *
	 * @access public
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function search_conf_set_n_table($post=NULL)
	{
		log_message('debug',"====================== search_conf_set_n_table start =========================");
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_checker_search_n_data');                      // テーブル設定情報取得
		$title = $CI->config->item('s_checker_search_n_title');                     // タイトル情報取得
		$other['button'] = $CI->config->item('s_checker_n_search_button');          // 追加情報取得
		$td_data[] = $CI->config->item('s_checker_search_name');                    // 項目氏名の設定
		$td_data[] = $CI->config->item('s_checker_search_btn');                     // 項目ボタンの設定
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other); // 部署コード選択テーブル作成
		log_message('debug',"====================== search_conf_set_n_table end =========================");
		return $string_table;
	}
	/**
	 * 確認者検索の氏名情報取得
	 *
	 * @access	public
	 * @param	array  $post 入力データ
	 * @return	string $data
	 */
	public function _get_search_name_data($post = NULL)
	{
		$CI =& get_instance();
		$CI->load->model('sgmtb010'); // ユーザー情報
		$user_data = $CI->sgmtb010->get_name_search_data($post['name']);
		// postを使ってデータ取得
		// 未完成
		/* テストデータ START */
		// 初回
		if(empty($post))
		{
			$data = NULL;
		}else{
			$data = $post;
		}
		/* テストデータ END */
		return $data;
	}
	/**
	 * 確認者検索の確認者氏名リストのHTMLを作成
	 *
	 * @access public
	 * @param  array $busyo 検索条件になる部署名
	 * @param  array $post  POSTデータ
	 * @return string $string_table
	 */
	public function search_conf_set_list_table($shbn,$post=NULL)
	{
		log_message('debug',"================ search_conf_set_list_tablel start ==================-");
		log_message('debug',$post);
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_checker_name_data');                      // テーブル設定情報取得
		$title = $CI->config->item('s_checker_list_title');                     // タイトル情報取得
		$td_data[] = $CI->config->item('s_checker_search_select');              // 項目本部の設定
		$data  = $this->_get_search_namelist_data($shbn,$post);                 // 確認者氏名情報取得
		$option = NULL;                                                         // 追加情報
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$option,$data);    // 部署コード選択テーブル作成
		log_message('debug',"================ search_conf_set_list_tablel end ==================-");
		return $string_table;
	}

	/**
	 * 確認者検索の確認者氏名情報取得
	 *
	 * @access	public
	 * @param	array $post 検索条件
	 * @return	string $data
	 */
	public function _get_search_namelist_data($shbn,$post = NULL)
	{
		$CI =& get_instance();
		$CI->load->model('sgmtb010'); // ユーザー情報
		$data = NULL;
		// ポスト通信有無判定
		if(count($post) > 0)
		{
			// コードを分ける
			// 名前検索ではない場合実行
			if(is_null($post['name']))
			{
				if($post['honbucd'] != MY_DB_BU_ESC)
				{
					foreach($post as $key => $p_data)
					{
						log_message('debug',$key."=".$p_data);
						// 本部コード抜き出し
						if($key === 'honbucd')
						{
							$user['honbu']['honbucd'] = $p_data;
							$user['honbu']['bucd'] = MY_DB_BU_ESC;
							$user['honbu']['kacd'] = MY_DB_BU_ESC;
						}else if($key === 'bucd'){
							if($p_data !== MY_DB_BU_ESC)
							{
								// 本部コード抜き出し
								$user['bu']['honbucd'] = substr($p_data,0,MY_CODE_LENGTH);
								// 部コード抜き出し
								$user['bu']['bucd'] = substr($p_data,MY_CODE_LENGTH,MY_CODE_LENGTH);
								$user['bu']['kacd'] = MY_DB_BU_ESC;
							}
						}else if($key === 'kacd'){
							if($p_data !== MY_DB_BU_ESC)
							{
								// 本部コード抜き出し
								$user['ka']['honbucd'] = substr($p_data,0,MY_CODE_LENGTH);
								// 部コード抜き出し
								$user['ka']['bucd'] = substr($p_data,MY_CODE_LENGTH,MY_CODE_LENGTH);
								// 課・ユニットコード抜き出し
								$user['ka']['kacd'] = substr($p_data,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
							}
						}
					}
					$name_data = $CI->sgmtb010->get_search_name_data($user);
						//確認者氏名リスト
						if($name_data)
						{
							foreach($name_data as $n_data)
							{
								foreach($n_data as $value)
								{
									$data[$n_data['shbn']] = $value;
								}
							}
						}else{
							$data = NULL;
						}
				}else{
					$data = NULL;
				}
			}else{
				// 名前検索で空白以外の場合
				if($post['name'] != "")
				{
					$name_data = $CI->sgmtb010->get_name_search_data($post['name']);
					if (empty($name_data)) {  $name_data = array(); }
					// 取得データを個人別に分ける
					foreach($name_data as $user_data)
					{
						// 本部名
						//$user_honbu[] = $CI->sgmtb010->get_user_honbu_data($user_data);
						// 部名
						$user_bu[] = $CI->sgmtb010->get_user_bu_data($user_data);
						// 課・ユニット名
						$user_unit[] = $CI->sgmtb010->get_user_unit_data($user_data);
					}
					if(!$name_data)
					{
						$data = NULL;
					}else{
						//var_dump($name_data);
						$no = 0; // 何人目か識別用
						foreach($name_data as $n_data)
						{
							foreach($n_data as $key => $value)
							{
								// 社員名保存
								if($key === 'shinnm')
								{
									//$data[$n_data['shbn']] = $user_honbu[$no]['bunm'].$user_bu[$no]['bunm'].$user_unit[$no]['bunm'].$value;
									$data[$n_data['shbn']] = $user_bu[$no]['bunm'].$user_unit[$no]['bunm'].$value;
								}
							}
							$no++;
						}
					}
				}else{
					$data = NULL;
				}
			}
		}else{
			// 初回読み込み
			// ユーザ情報取得
			$user = $CI->sgmtb010->get_search_user_data($shbn,TRUE); // 自分の部署情報取得
			$honbu = $user['honbucd'];
			$bu = $user['bucd'];
			$unit = $user['kacd'];
			// 自分の部署データを渡して、自分の所属部署内のユーザー名取得
			// 本部情報
				$user_busyo['honbu'] = array('honbucd' => $user['honbucd'],'bucd' => MY_DB_BU_ESC,'kacd' => MY_DB_BU_ESC);
			// 部情報
				$user_busyo['bu'] = array('honbucd' => $user['honbucd'],'bucd' => $user['bucd'],'kacd' => MY_DB_BU_ESC);
			// 課・ユニット情報
			$user_busyo['ka'] = array('honbucd' => $user['honbucd'],'bucd' => $user['bucd'],'kacd' => $user['kacd']);
			$name_data = $CI->sgmtb010->get_search_name_data($user_busyo); // 本部用
			$data = array();
			// 表示データの作成
			//確認者氏名リスト
			foreach($name_data as $n_data)
			{
				foreach($n_data as $value)
				{
					$data[$n_data['shbn']] = $value;
				}
			}
		}

		return $data;
	}

	/**
	 * 部署検索の部署検索のHTMLを作成
	 *
	 * @access public
	 * @param  array $shbn 検索条件
	 * @return string $string_table
	 */
	public function search_unit_set_b_table($shbn,$post=NULL,$honbu=NULL,$bu=NULL,$memo_flg=NULL,$unit=NULL,$user=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		if(isset($memo_flg) && $memo_flg == "1"){
			$table = $CI->config->item('c_data_memo');                      // テーブル設定情報取得
			$title = $CI->config->item('s_busyo_search_title_memo');                     // タイトル情報取得
			
			$td_data[] = $CI->config->item('s_checker_search_honbu');               // 項目本部の設定
			$td_data[] = $CI->config->item('s_checker_search_bu');                  // 項目部の設定
			
			if(isset($bu) && $bu !=""){
				
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu);		//本部選択時
			}else if(isset($honbu) && $honbu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu);		//本部選択時
			}else{
				$data   = $this->_get_search_busyo_data($shbn,$post,FALSE);                // 情報取得
			}
		
		}else if(isset($memo_flg) && $memo_flg == "2"){
			$table = $CI->config->item('c_data_memo_scv');                      // テーブル設定情報取得
			
			
			$title = $CI->config->item('s_busyo_search_title_memo_csv');                     // タイトル情報取得
			
			$td_data[] = $CI->config->item('s_checker_search_honbu');               // 項目本部の設定
			$td_data[] = $CI->config->item('s_checker_search_bu');                  // 項目部の設定
			
			if(isset($bu) && $bu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,NULL,NULL,NULL,'1');		
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu);		//本部選択時
			}else if(isset($honbu) && $honbu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,NULL,NULL,NULL,NULL,'1');	
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu);		//本部選択時
			}else{
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,NULL,NULL,NULL,NULL,NULL,'1');	
				//$data   = $this->_get_search_busyo_data($shbn,$post,FALSE);                // 情報取得
			}
			
		}else if(isset($memo_flg) && $memo_flg == "3"){
			$table = $CI->config->item('c_data_memo');                      // テーブル設定情報取得
			
			$title = $CI->config->item('s_busyo_search_title_memo');                     // タイトル情報取得
			
			$td_data[] = $CI->config->item('s_checker_search_honbu');               // 項目本部の設定
			$td_data[] = $CI->config->item('s_checker_search_bu');                  // 項目部の設定
			$td_data[] = $CI->config->item('s_checker_search_unit'); 
			$td_data[] = $CI->config->item('s_checker_search_user'); 
			
			if($post){
				if(isset($post['honbucd']) && $post['honbucd'] != ""){
					$honbu = $post['honbucd'];
				}
				if(isset($post['bucd'])  && $post['bucd'] != ""){
					$bu = $post['bucd'];
				}
				if(isset($post['kacd'])  && $post['kacd'] != ""){
					$unit = $post['kacd'];
				}
				if(isset($post['user'])  && $post['user'] != ""){
					$user = $post['user'];
				}
			}
			
			if(isset($user) && $user !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,$unit,$user,NULL,'2');		
			}else if(isset($unit) && $unit !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,$unit,NULL,NULL,'2');		
			}else if(isset($bu) && $bu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,NULL,NULL,NULL,'2');		
			}else if(isset($honbu) && $honbu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,NULL,NULL,NULL,NULL,'2');	
			}else{
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,NULL,NULL,NULL,NULL,NULL,'2');	
			}
		}else if(isset($memo_flg) && $memo_flg == "4"){
			$table = $CI->config->item('c_data_memo_scv');                      // テーブル設定情報取得
			
			
			$title = $CI->config->item('s_busyo_search_title_memo_csv_2');                     // タイトル情報取得
			
			$td_data[] = $CI->config->item('s_checker_search_honbu');               // 項目本部の設定
			$td_data[] = $CI->config->item('s_checker_search_bu');                  // 項目部の設定
			$td_data[] = $CI->config->item('s_checker_search_unit'); 
			$td_data[] = $CI->config->item('s_checker_search_user'); 
			if(isset($unit) && $unit !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,$unit,NULL,'2');		
			}else if(isset($bu) && $bu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,NULL,NULL,'2');		
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu);		//本部選択時
			}else if(isset($honbu) && $honbu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,NULL,NULL,NULL,'2');	
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu);		//本部選択時
			}else{
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,NULL,NULL,NULL,NULL,'2');	
				//$data   = $this->_get_search_busyo_data($shbn,$post,FALSE);                // 情報取得
			}
			
		}else if(isset($memo_flg) && $memo_flg == "5"){
			$table = $CI->config->item('s_busyo_search_data');                      // テーブル設定情報取得
			
			
			$title = $CI->config->item('s_ka_search_title');                     // タイトル情報取得
			$other['button'] = $CI->config->item('s_checker_b_search_button');      // 追加情報取得
			$td_data[] = $CI->config->item('s_checker_search_honbu');               // 項目本部の設定
			$td_data[] = $CI->config->item('s_checker_search_bu');                  // 項目部の設定
			$td_data[] = $CI->config->item('s_checker_search_unit'); 
			$td_data[] = $CI->config->item('s_checker_search_btn');                 // ボタンの設定
			if(isset($unit) && $unit !=""){
			
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,$unit,NULL,'3');		
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu);		//本部選択時
			
			}else if(isset($bu) && $bu !=""){
			
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu,NULL,NULL,'3');		
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu);		//本部選択時
			}else if(isset($honbu) && $honbu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,NULL,NULL,NULL,'3');	
				//$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu);		//本部選択時
			}else{
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,NULL,NULL,NULL,NULL,'3');	
				//$data   = $this->_get_search_busyo_data($shbn,$post,FALSE);                // 情報取得
			}
		}else{
			$table = $CI->config->item('s_busyo_search_data');                      // テーブル設定情報取得
			$title = $CI->config->item('s_busyo_search_title');                     // タイトル情報取得
			$other['button'] = $CI->config->item('s_checker_b_search_button');      // 追加情報取得
			$td_data[] = $CI->config->item('s_checker_search_honbu');               // 項目本部の設定
			$td_data[] = $CI->config->item('s_checker_search_bu');                  // 項目部の設定
			$td_data[] = $CI->config->item('s_checker_search_btn');                 // ボタンの設定
			if(isset($bu) && $bu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu,$bu);		//本部選択時
			}else if(isset($honbu) && $honbu !=""){
				$data	= $this->_get_search_busyo_data($shbn,$post,FALSE,$honbu);		//本部選択時
			}else{
				$data   = $this->_get_search_busyo_data($shbn,$post,FALSE);                // 情報取得
			}
		}
		if(isset($memo_flg) && $memo_flg == "5"){
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other,$data); // 部署コード選択テーブル作成
		}else if(isset($memo_flg) && $memo_flg != ""){
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,NULL,$data); // 部署コード選択テーブル作成
		}else{
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$other,$data); // 部署コード選択テーブル作成
		}
		return $string_table;
	}

	/**
	 * 確認者検索の確認者氏名リストのHTMLを作成
	 *
	 * @access public
	 * @param  array $shbn ユーザの社番
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function search_unit_set_list_table($shbn,$post = NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_busyo_name_data');                        // テーブル設定情報取得
		$title = $CI->config->item('s_checker_list_title');                     // タイトル情報取得
		$td_data[] = $CI->config->item('s_busyo_search_select');                // 項目本部の設定
		$data  = $this->_get_search_busyo_name_data($shbn,$post);                     // 確認者氏名情報取得
		$option = NULL;                                                         // 追加情報
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$option,$data);    // 部署コード選択テーブル作成
		return $string_table;
	}

	/**
	 * 確認者検索の確認者氏名リストのHTMLを作成
	 *
	 * @access public
	 * @param  array $shbn ユーザの社番
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function search_unit_set_list_table_($shbn,$post = NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_unit_name_data');                        // テーブル設定情報取得
		$title = $CI->config->item('s_checker_list_title');                     // タイトル情報取得
		$td_data[] = $CI->config->item('s_ka_search_select');                // 項目本部の設定
		$data  = $this->_get_search_ka_name_data($shbn,$post);                     // 確認者氏名情報取得
		$option = NULL;                                                         // 追加情報
		$string_table = $this->_set_box_table($table,$title,$post,$td_data,$option,$data);    // 部署コード選択テーブル作成
		return $string_table;
	}


	/**
	 * 部署検索の部署名情報取得
	 *
	 * @access	public
	 * @param	array $post 検索条件
	 * @return	string $data
	 */
	public function _get_search_busyo_name_data($shbn,$post = NULL)
	{
		$CI =& get_instance();

		$CI->load->model('sgmtb010'); // ユーザー情報
		$CI->load->model('sgmtb020'); // 部署情報
		// 初回判定
		if(count($post) > 0)
		{
			if($post['honbucd'] !== MY_DB_BU_ESC)
			{
				$no = 0;
				// 検索部署をコードごとに保存

				foreach($post as $key => $p_data)
				{
					// 本部ドロップダウンリスト
					if($key === 'honbucd')
					{
						$honbunm = $CI->sgmtb020->get_honbu_name_data($post);
						// 本部名保存
						$data[$p_data.MY_DB_BU_ESC.MY_DB_BU_ESC] = $honbunm['bunm'];
					}else if($key === 'bucd'){
						// 部ドロップダウンリスト
						if($p_data !== MY_DB_BU_ESC)
						{
							// 本部コード抜き出し
							$busyo['honbucd'] = substr($p_data,0,MY_CODE_LENGTH);
							if($post['honbucd'] !== $busyo['honbucd'])
							{
								$honbunm = $CI->sgmtb020->get_honbu_name_data($busyo);
								// 本部名保存
								$data[$busyo['honbucd'].MY_DB_BU_ESC.MY_DB_BU_ESC] = $honbunm['bunm'];
							}
							// 部コード抜き出し
							$busyo['bucd'] = substr($p_data,MY_CODE_LENGTH,MY_CODE_LENGTH);
							$bunm = $CI->sgmtb020->get_bu_name_data($busyo);
							// 部名保存
							$data[$busyo['honbucd'].$busyo['bucd'].MY_DB_BU_ESC] = $bunm['bunm'];
						}
					}
				}
				
			}else{
				$data = NULL;
			}
		}else{
			// 初回
			// ユーザ情報取得
			$user = $CI->sgmtb010->get_search_user_data($shbn,TRUE); // 自分の部署情報取得
			// 本部名取得*/
			$honbunm = $CI->sgmtb020->get_honbu_name_data($user);
			// 部名取得
			$bunm = $CI->sgmtb020->get_bu_name_data($user);
			// 課・ユニット名取得
			$kanm = $CI->sgmtb020->get_unit_name_data($user);
			// HTML作成テーブル用データ
			$data = array(
				$user['honbucd'].MY_DB_BU_ESC.MY_DB_BU_ESC    => $honbunm['bunm'],
				$user['honbucd'].$user['bucd'].MY_DB_BU_ESC   => $bunm['bunm']
				);
		}

		return $data;
	}
	
		/**
	 * 部署検索の部署名情報取得
	 *
	 * @access	public
	 * @param	array $post 検索条件
	 * @return	string $data
	 */
	public function _get_search_ka_name_data($shbn,$post = NULL)
	{
		$CI =& get_instance();

		$CI->load->model('sgmtb010'); // ユーザー情報
		$CI->load->model('sgmtb020'); // 部署情報
		// 初回判定
		
		if(count($post) > 0)
		{
			if($post['kacd'] != 'XXXXX'){
				// 本部コード抜き出し
				$unit['honbucd'] = substr($post['kacd'],0,MY_CODE_LENGTH);
				// 部コード抜き出し
				$unit['bucd'] = substr($post['kacd'],5,MY_CODE_LENGTH);
				// 課コード抜き出し
				$unit['kacd'] = substr($post['kacd'],10,MY_CODE_LENGTH);
				
				$kanm = $CI->sgmtb020->get_unit_name_data($unit);
				// ユニット名保存
				$data[$unit['honbucd'].$unit['bucd'].$unit['kacd']] = $kanm['bunm'];
			}else if($post['bucd'] != 'XXXXX' && $post['kacd']== 'XXXXX'){
				// 本部コード抜き出し
				$unit['honbucd'] = substr($post['bucd'],0,5);
				// 部コード抜き出し
				$unit['bucd'] = substr($post['bucd'],5,5);
				
				$kanm = $CI->sgmtb020->get_unit_name_data_busyo($unit);
				
				foreach($kanm as $key => $name_data){
					// ユニット名保存
					$data[$unit['honbucd'].$unit['bucd'].$name_data['kacd']] = $name_data['bunm'];
				}
			}else if($post['honbucd'] != 'XXXXX'){
				// 本部コード抜き出し
				$unit['honbucd'] = $post['honbucd'];
				
				$kanm = $CI->sgmtb020->get_unit_name_data_honbu($unit);
				foreach($kanm as $key => $name_data){
					// ユニット名保存
					$data[$unit['honbucd'].$name_data['bucd'].$name_data['kacd']] = $name_data['bunm'];
				}
			}else{
				
				$kanm = $CI->sgmtb020->get_unit_name_data_all();
				foreach($kanm as $key => $name_data){
					// ユニット名保存
					$data[$name_data['honbucd'].$name_data['bucd'].$name_data['kacd']] = $name_data['bunm'];
				}
			}
			
			
		/*
			if($post['honbucd'] !== MY_DB_BU_ESC)
			{
				$no = 0;
				// 検索部署をコードごとに保存

				foreach($post as $key => $p_data)
				{
					// 本部ドロップダウンリスト
					if($key === 'honbucd')
					{
						$honbunm = $CI->sgmtb020->get_honbu_name_data($post);
						// 本部名保存
						$data[$p_data.MY_DB_BU_ESC.MY_DB_BU_ESC] = $honbunm['bunm'];
					}else if($key === 'bucd'){
						// 部ドロップダウンリスト
						if($p_data !== MY_DB_BU_ESC)
						{
							// 本部コード抜き出し
							$busyo['honbucd'] = substr($p_data,0,MY_CODE_LENGTH);
							if($post['honbucd'] !== $busyo['honbucd'])
							{
								$honbunm = $CI->sgmtb020->get_honbu_name_data($busyo);
								// 本部名保存
								$data[$busyo['honbucd'].MY_DB_BU_ESC.MY_DB_BU_ESC] = $honbunm['bunm'];
							}
							// 部コード抜き出し
							$busyo['bucd'] = substr($p_data,MY_CODE_LENGTH,MY_CODE_LENGTH);
							$bunm = $CI->sgmtb020->get_bu_name_data($busyo);
							// 部名保存
							$data[$busyo['honbucd'].$busyo['bucd'].MY_DB_BU_ESC] = $bunm['bunm'];
						}
					}else if($key === 'kacd'){
						// 部ドロップダウンリスト
						if($p_data !== MY_DB_BU_ESC)
						{
							// 本部コード抜き出し
							$busyo['honbucd'] = substr($p_data,0,MY_CODE_LENGTH);
							if($post['honbucd'] !== $busyo['honbucd'])
							{
								$honbunm = $CI->sgmtb020->get_honbu_name_data($busyo);
								// 本部名保存
								$data[$busyo['honbucd'].MY_DB_BU_ESC.MY_DB_BU_ESC] = $honbunm['bunm'];
							}
							// 部コード抜き出し
							$busyo['bucd'] = substr($p_data,MY_CODE_LENGTH,MY_CODE_LENGTH);
							$bunm = $CI->sgmtb020->get_bu_name_data($busyo);
							// 部名保存
							$data[$busyo['honbucd'].$busyo['bucd'].MY_DB_BU_ESC] = $bunm['bunm'];
						}
					}
				}
				
			}else{
				$data = NULL;
			}
			*/
		}else{
			// 初回
			// ユーザ情報取得
			$user = $CI->sgmtb010->get_search_user_data($shbn,TRUE); // 自分の部署情報取得
			// 本部名取得*/
			$honbunm = $CI->sgmtb020->get_honbu_name_data($user);
			// 部名取得
			$bunm = $CI->sgmtb020->get_bu_name_data($user);
			// 課・ユニット名取得
			$kanm = $CI->sgmtb020->get_unit_name_data($user);
			// HTML作成テーブル用データ
			$data = array(

				$user['honbucd'].$user['bucd'].$user['kacd']  => $kanm['bunm']
				
				);
		}

		return $data;
	}

	/**
	 * グループ検索のグループ名リストのHTMLを作成
	 *
	 * @access public
	 * @param  array $busyo 検索条件になる部署名
	 * @param  array $post  POSTデータ
	 * @return string $string_table
	 */
	public function search_group_set_list_table()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_group_name_data');                        // テーブル設定情報取得
		$title = $CI->config->item('s_checker_list_title');                     // タイトル情報取得
		$td_data[] = $CI->config->item('s_group_search_select');                // 項目本部の設定
		$data  = $this->_get_search_group_name_data();                     // 確認者氏名情報取得
		$option = NULL;                                                         // 追加情報
		$string_table = $this->_set_box_table($table,$title,NULL,$td_data,$option,$data);    // 部署コード選択テーブル作成
		return $string_table;
	}

	/**
	 * グループ検索のグループ名情報取得
	 *
	 * @access	public
	 * @param	array $post 検索条件
	 * @return	string $data
	 */
	public function _get_search_group_name_data()
	{
		$CI =& get_instance();

		$CI->load->model('sgmtb110'); // グループ情報
		// グループ情報取得
		$group = $CI->sgmtb110->get_group_data();
		// HTML用にデータ生成
		foreach($group as $g_data)
		{
			foreach($g_data as $key => $value)
			{
				if($key === 'htngrpnm')
				{
					$data[$g_data['htngrpcd']] = $value;
				}
			}
		}

		return $data;
	}

	/**
	 * 非リスト型テーブル情報設定
	 *
	 * @access	public
	 * @param	array $table         テーブル設定情報
	 * @param	array  $title        項目名設定情報
	 * @param	array  $post         POST情報
	 * @param	array  $td_data      項目内容設定情報
	 * @param	array  $other        追加情報
	 * @param	array  $data         DB取得情報
	 * @return	string $string_table テーブル情報
	 */
	function _set_box_table($table,$title,$post,$td_data,$other=NULL,$data=NULL,$onchange=NULL)
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->library('select_box_set');
		$CI->load->library('item_manager');
		log_message("debug","============ start _set_box_table ================");

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
			// 項目名有りの場合
			if($value !== MY_TITLE_SPACE)
			{
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
				$string_table .= "<td style=\"border:none;\">\n";
				$string_table .= $value;
				$string_table .= "</td>\n";
				$string_table .= "</tr>\n";
				$string_table .= "</table>\n";
				// 項目パーツテーブルend
				$string_table .= "</td>\n";
			}
			// td終了-----------------------------------------------------
			$string_table .= "<td>\n";
			// 項目の内容テーブル作成
			$string_table .= "<table";
			if(! is_null($table['td_table_width']))
			{
				$string_table .= " width=\"".$table['td_table_width']."\" ";
			}
			if(! is_null($table['td_table_height']))
			{
				$string_table .= " height=\"".$table['td_table_height']."\" ";
			}
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
							switch($td_data[$num]['td_form_type'][$n])
							{
								case 'select': // セレクトボックス設定
									$string_table .= $CI->item_manager->set_listbox_string($attribute,$data);
									break;
								case 'drop': // ドロップダウンリスト設定
									//$string_table .= $CI->select_box_set->_set_select_type($attribute,$name,$post); // セレクトボックス作成

									$string_table .= $CI->item_manager->set_variable_dropdown_string($data[$num]);
									break;
								case 'from': // 期間
									$string_table .= "～";
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

	/**
	 * 活動区分選択用HTML-STRINGを作成
	 *
	 * @access public
	 * @param  string $item 実績・予定判定
	 * @param  string $chek 活動区分選択判定
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_new_kubun_table($item = NULL,$check = NULL)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
//		$CI->load->helper('form');
		$CI->load->library('item_manager');

		$string_data = NULL;
		// HTML-STRING作成
		$string_data .= $CI->item_manager->set_start_field_string(MY_FIELD_PLAN);
		$string_data .= "<table>\n<tr>\n<td>\n";
		$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
		$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_ACTION_TYPE,$check) . "\n";
		$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_VIEW) . "\n";
		$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_DEL) . "\n";
		if($item === MY_PLAN){
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_MOVE) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_COPY) . "\n";
		}
		$string_data .= "</td>\n</tr>\n</table>\n";
		$string_data .= $CI->item_manager->set_end_field_string() . "\n";

		return $string_data;
	}

	/**
	 * 活動区分選択用HTML-STRINGを作成
	 *
	 * @access public
	 * @param  string $item 実績・予定判定
	 * @param  string $chek 活動区分選択判定
	 * @return string $string_data HTML-STRING文字列
	 */
	public function new_set_kubun_table($conf_item = NULL)
	{
		// 引数チェック
		if(is_null($conf_item))
		{
			throw new Exception("Error Processing Request", SYSTEM_ERROR);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
//		$base_url = $CI->config->item('base_url');
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];

		$string_data = NULL;
		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		}else if($item === MY_RESULT){
			// 仕掛
		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 * 活動区分（本部）の新規HTML-STRINGを作成
	 *
	 * @access public
	 * @param  string $item 実績・予定判定
	 * @param  string $chek 活動区分選択判定
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_new_honbu_table($item = NULL,$check = MY_ACTION_TYPE_HONBU)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');
//		$field_setting = $CI->config->item(MY_FIELD_PLAN);
//		$field_setting['id'] = "xxxxxxx";
//		$field_setting['class'] = "xxxxxxx";

		$link = '/xxxxx/xxxxx';
//		$table_status = $CI->config->item('s_kubun_table');

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_field_string(MY_FIELD_PLAN);
//			$string_data .= $CI->item_manager->set_start_variable_field_string(MY_FIELD_PLAN);

			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_ACTION_TYPE,$check) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_VIEW) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_DEL) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_MOVE) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_COPY) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
			$string_data .= "<td>\n";
			// 日付設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"year\" value=\"\">年</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"month\" value=\"\">月</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"day\" value=\"\">日</td>\n";
			$string_data .= "</tr>\n";
			// 開始時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_hour\" value=\"\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_minute\" value=\"\">分</td>\n";
			$string_data .= "</tr>\n";
			// 終了時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_hour\" value=\"\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_minute\" value=\"\">分</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 販売店名・地区本部設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"shop_name\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 区分設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_HONBU_KUBUN) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 面談者設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"mendan_name1\" value=\"\"></td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"mendan_name2\" value=\"\"></td>\n";
			$string_data .= "</tr>\n";
			// 同行者設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">同行者</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"doukou_name1\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"doukou_name2\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 場所設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">場所</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_HONBU_LOCATION) . "\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談内容設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"height:200px; width:800px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>【商談内容】</td>\n";
			$string_data .= "</tr>\n";
			// 月次商談設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">月次商談</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$getsuji_syoudan_data = array(
				'0' => '見積り提示',
				'1' => '採用企画の確認',
				'2' => '販売計画',
				'3' => 'クレーム対応',
				'4' => '売場提案',
				'5' => '予備１',
				'6' => '予備２',
				'7' => '予備３',
				'8' => '予備４',
				'9' => '予備５'
			);
			$s_td_string = "<td style=\"height:24px\">";
			$e_td_string = "</td>\n";
			$data_count = count($getsuji_syoudan_data);
			$data_count = $data_count / 2;
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($getsuji_syoudan_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji_syoudan" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $getsuji_syoudan_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				// 右側表示
				if( ! empty($getsuji_syoudan_data[$i + $data_count])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji_syoudan" . ($i + $data_count) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $getsuji_syoudan_data[$i + $data_count] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// 半期提案設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">半期提案</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$hanki_teian_data = array(
				'0' => '商品案内',
				'1' => '導入提案',
				'2' => 'MD提案',
				'3' => '棚割提案',
				'4' => '販売店の棚割日情報',
				'5' => '導入日の詰め',
				'6' => '棚割結果確認',
				'7' => '予備１',
				'8' => '予備２',
				'9' => '予備３',
				'10' => '予備４',
				'11' => '予備５'
			);
			$data_count = count($hanki_teian_data);
			$data_count = $data_count / 2;
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($hanki_teian_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki_teian" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $hanki_teian_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				// 右側表示
				if( ! empty($hanki_teian_data[$i + $data_count])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki_teian" . ($i + $data_count) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $hanki_teian_data[$i + $data_count] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// その他設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" naem=\"other\">その他</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// カテゴリー設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>【カテゴリー】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"category1\">紙中分類</td>\n";
			$string_data .= "<td><input type=\"checkbox\" name=\"category2\">加工品大分類</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 商談予定設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\">\n";
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"shodan_plan\" id=\"shodan_plan\"></textarea>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 不明領域設定
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_FREE) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_FREE) . "\n";
			$string_data .= "</td>\n</tr>\n";
			// フィールドセット終了
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";
			$string_data .= "<br>\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 *
	 *
	 * @access public
	 * @param  string $item 実績・予定判定
	 * @param  string $conf_item HTML設定ファイル
	 * @return string $string_data HTML-STRING文字列
	 */
	public function set_honbu_table($item = NULL,$conf_item = NULL,$pran_data)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
//		$base_url = $CI->config->item('base_url');
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（本部）
		$check = MY_ACTION_TYPE_HONBU;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（本部）
		$drop_honbu_kubun = $CI->config->item(MY_SELECT_HONBU_KUBUN);
		$drop_honbu_kubun['name'] = $conf_item['drop_honbu_kubun_name'];
		// ドロップダウン項目設定（本部場所）
		$drop_honbu_location = $CI->config->item(MY_SELECT_HONBU_LOCATION);
		$drop_honbu_location['name'] = $conf_item['drop_honbu_location'];
		// 月次商談設定項目設定
		$getsuji_syoudan_data = $CI->config->item(MY_GETSUJI_NEGO);
		$getsuji_data = $getsuji_syoudan_data['data'];
		$getsuji_right = $getsuji_syoudan_data['count_right'];
		$getsuji_left = $getsuji_syoudan_data['count_left'];
		// 半期提案項目設定
		$hanki_teian_data = $CI->config->item(MY_HANKI_TEIAN);
		$hanki_data = $hanki_teian_data['data'];
		$hanki_right = $hanki_teian_data['count_right'];
		$hanki_left = $hanki_teian_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
			$string_data .= "<td>\n";
			// 日付設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
			$string_data .= "</tr>\n";
			// 開始時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['start_hour'] . "\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['start_minute'] . "\">分</td>\n";
			$string_data .= "</tr>\n";
			// 終了時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['end_hour'] . "\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['end_minute'] . "\">分</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 販売店名・地区本部設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 区分設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_honbu_kubun) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 面談者設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan_name1'] . "\"></td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan_name2'] . "\"></td>\n";
			$string_data .= "</tr>\n";
			// 同行者設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">同行者</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou_name1'] . "\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou_name2'] . "\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 場所設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">場所</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_honbu_location) . "\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";

			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談内容設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"height:200px; width:800px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>【商談内容】</td>\n";
			$string_data .= "</tr>\n";
			// 月次商談設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">月次商談</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$s_td_string = "<td style=\"height:24px\">";
			$e_td_string = "</td>\n";
			for($i=MY_ZERO; $i < 7; $i++)
			{
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $getsuji_left + $i;
				// 左側表示
				if( ! empty($getsuji_data[$leftcount]) AND $leftcount < $getsuji_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji_syoudan" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $getsuji_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$righcount = $getsuji_right + $i;
				// 右側表示
				if( ! empty($getsuji_data[$righcount])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"getsuji_syoudan" . ($righcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $getsuji_data[$righcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 半期提案設定
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">半期提案</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $hanki_left + $i;
				// 左側表示
				if( ! empty($hanki_data[$leftcount]) AND $leftcount < $hanki_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki_teian" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $hanki_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$righcount = $hanki_right + $i;
				// 右側表示
				if( ! empty($hanki_data[$righcount])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"hanki_teian" . ($righcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $hanki_data[$righcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// その他設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" naem=\"" . $conf_item['other_name'] . "\">その他</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// カテゴリー設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>【カテゴリー】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\">紙中分類</td>\n";
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\">加工品大分類</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 商談予定設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\">\n";
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['shodan_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 不明領域設定
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "</table>\n";
			// フィールドセット終了
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";

		}
		return $string_data;
	}

	/**
	 * 活動区分（店舗）の新規HTML-STRINGを作成
	 *
	 *
	 */
	public function set_new_tenpo_table($item = NULL,$check = MY_ACTION_TYPE_TENPO)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');
		$link = '/xxxxx/xxxxx';

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_field_string(MY_FIELD_PLAN);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_ACTION_TYPE,$check) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_VIEW) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_DEL) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_MOVE) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_COPY) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
			$string_data .= "<td>\n";
			// 日付設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"year\" value=\"\">年</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"month\" value=\"\">月</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"day\" value=\"\">日</td>\n";
			$string_data .= "</tr>\n";
			// 開始時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_hour\" value=\"\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_minute\" value=\"\">分</td>\n";
			$string_data .= "</tr>\n";
			// 終了時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_hour\" value=\"\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_minute\" value=\"\">分</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 販売店名・地区本部設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"shop_name\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 区分設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_TENPO_KUBUN) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 面談者設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"mendan_name1\" value=\"\"></td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"mendan_name2\" value=\"\"></td>\n";
			$string_data .= "</tr>\n";
			// 同行者設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">同行者</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"doukou_name1\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"doukou_name2\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談内容設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"height:200px; width:800px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>【商談内容】</td>\n";
			$string_data .= "</tr>\n";
			// 店舗商談設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">店舗商談</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$tenpo_syoudan_data = array(
				'0' => '情報収集',
				'1' => '商品情報案内',
				'2' => '展開場所・ｱｳﾄ展開交渉',
				'3' => '推奨販売交渉',
				'4' => '受注促進',
				'5' => '予備１',
				'6' => '予備２',
				'7' => '予備３',
				'8' => '予備４',
				'9' => '予備５'
			);
			$s_td_string = "<td style=\"height:24px\">";
			$e_td_string = "</td>\n";
			$data_count = count($tenpo_syoudan_data);
			$data_count = $data_count / 2;
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($tenpo_syoudan_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo_syoudan" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tenpo_syoudan_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				// 右側表示
				if( ! empty($tenpo_syoudan_data[$i + $data_count])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo_syoudan" . ($i + $data_count) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tenpo_syoudan_data[$i + $data_count] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// 店内作業設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">店内作業</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$tennai_data = array(
				'0' => '売場撮影',
				'1' => '売場メンテナンス',
				'2' => '在庫確認',
				'3' => '商品補充',
				'4' => '販促物の設置',
				'5' => '山積み',
				'6' => 'ベタ付け',
				'7' => '予備１',
				'8' => '予備２',
				'9' => '予備３',
				'10' => '予備４',
				'11' => '予備５'
			);
			$data_count = count($tennai_data);
			$data_count = $data_count / 2;
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($tennai_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai_action" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tennai_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				// 右側表示
				if( ! empty($tennai_data[$i + $data_count])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai_action" . ($i + $data_count) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tennai_data[$i + $data_count] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// その他設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"other\">その他</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other1\">" . $e_td_string;
			$string_data .= $s_td_string . "競合店調査（MR）" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 6; $i++)
			{
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// カテゴリー設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>【カテゴリー】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"category1\">紙中分類</td>\n";
			$string_data .= "<td><input type=\"checkbox\" name=\"category2\">加工品大分類</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 商談予定設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">【作業予定】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\">\n";
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"work_plan\" id=\"work_plan\"></textarea>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 不明領域設定
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_FREE) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_FREE) . "\n";
			$string_data .= "</td>\n</tr>\n";
			// フィールドセット終了
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";
			$string_data .= "<br>\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 * 活動区分（店舗）の新規HTML-STRINGを作成
	 *
	 *
	 */
	public function set_tenpo_table($item = NULL,$conf_item = NULL,$pran_data)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
//		$base_url = $CI->config->item('base_url');
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_TENPO;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（店舗）
		$drop_tenpo_kubun = $CI->config->item(MY_SELECT_TENPO_KUBUN);
		$drop_tenpo_kubun['name'] = $conf_item['drop_tenpo_kubun_name'];
		// 月次商談設定項目設定
		$tenpo_syoudan_data = $CI->config->item(MY_TENPO_SHOUDAN);
		$tenpo_data = $tenpo_syoudan_data['data'];
		$tenpo_right = $tenpo_syoudan_data['count_right'];
		$tenpo_left = $tenpo_syoudan_data['count_left'];
		// 半期提案項目設定
		$tennai_work_data = $CI->config->item(MY_TENNAI_WORK);
		$tennai_data = $tennai_work_data['data'];
		$tennai_right = $tennai_work_data['count_right'];
		$tennai_left = $tennai_work_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
			$string_data .= "<td>\n";
			// 日付設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
			$string_data .= "</tr>\n";
			// 開始時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['start_hour'] . "\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['start_minute'] . "\">分</td>\n";
			$string_data .= "</tr>\n";
			// 終了時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['end_hour'] . "\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['end_minute'] . "\">分</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 販売店名・地区本部設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">販売店名</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 区分設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_tenpo_kubun) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 面談者設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan_name1'] . "\"></td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan_name2'] . "\"></td>\n";
			$string_data .= "</tr>\n";
			// 同行者設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">同行者</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou_name1'] . "\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou_name2'] . "\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談内容設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"height:200px; width:800px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>【商談内容】</td>\n";
			$string_data .= "</tr>\n";
			// 店舗商談設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">店舗商談</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$s_td_string = "<td style=\"height:24px\">";
			$e_td_string = "</td>\n";
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $tenpo_left + $i;
				// 左側表示
				if( ! empty($tenpo_data[$leftcount]) AND $i < $tenpo_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo_syoudan" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tenpo_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$righcount = $tenpo_right + $i;
				// 右側表示
				if( ! empty($tenpo_data[$righcount])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tenpo_syoudan" . ($righcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tenpo_data[$righcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// 店内作業設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">店内作業</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $tennai_left + $i;
				// 左側表示
				if( ! empty($tennai_data[$leftcount]) AND $leftcount < $tennai_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai_action" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tennai_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$righcount = $tennai_right + $i;
				// 右側表示
				if( ! empty($tennai_data[$righcount])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"tennai_action" . ($tennai_right + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $tennai_data[$righcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// その他設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" naem=\"" . $conf_item['other_name'] . "\">その他</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"" . $conf_item['other_name1'] . "\">" . $e_td_string;
			$string_data .= $s_td_string . "競合店調査（MR）" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= $s_td_string . "&ensp;" . $e_td_string;
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 6; $i++)
			{
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "<table>\n";
//			$string_data .= "</td>\n";
//			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// カテゴリー設定
			$string_data .= "<tr>\n";
			$string_data .= "<td>【カテゴリー】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td style=\"padding-left:10px\"><input type=\"checkbox\" name=\"" . $conf_item['category_name1'] . "\">紙中分類</td>\n";
			$string_data .= "<td><input type=\"checkbox\" name=\"" . $conf_item['category_name2'] . "\">加工品大分類</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 商談予定設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">【作業予定】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\">\n";
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 不明領域設定
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			// フィールドセット終了
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";
			$string_data .= "<br>\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 * 活動区分（代理店）の新規HTML-STRINGを作成
	 *
	 *
	 */
	public function set_new_dairi_table($item = NULL,$check = MY_ACTION_TYPE_DAIRI)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');
		$link = '/xxxxx/xxxxx';

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_field_string(MY_FIELD_PLAN);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_ACTION_TYPE,$check) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_VIEW) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_DEL) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_MOVE) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_COPY) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
			$string_data .= "<td>\n";
			// 日付設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"year\" value=\"\">年</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"month\" value=\"\">月</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"day\" value=\"\">日</td>\n";
			$string_data .= "</tr>\n";
			// 開始時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_hour\" value=\"\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_minute\" value=\"\">分</td>\n";
			$string_data .= "</tr>\n";
			// 終了時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_hour\" value=\"\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_minute\" value=\"\">分</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 卸名
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">卸名</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"shop_name\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 区分設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_DAIRI_KUBUN) . "\n";
			$string_data .= "</td>\n</tr>\n";
			// ランク設定
			$string_data .= "<tr>\n<td colspan=\"2\">ランク</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_DAIRI_RANK) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 面談者設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"mendan_name1\" value=\"\"></td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"mendan_name2\" value=\"\"></td>\n";
			$string_data .= "</tr>\n";
			// 同行者設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">同行者</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"doukou_name1\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"doukou_name2\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $base_url . $link . "';\">\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談内容設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"height:200px; width:800px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>【商談内容】</td>\n";
			$string_data .= "</tr>\n";
			// 対代理店・対一般店
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">対代理店・対一般店</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$dairi_ippan_data = array(
				'0' => '一般店見積り提示・商談フォロー',
				'1' => '商品案内',
				'2' => '企画案内',
				'3' => '実績報告（経理・配荷）',
				'4' => '予備１',
				'5' => '予備２',
				'6' => '予備３',
				'7' => '予備４',
				'8' => '予備５'
			);
			$s_td_string = "<td style=\"height:24px\">";
			$e_td_string = "</td>\n";
			$data_count = count($dairi_ippan_data);
			$data_count = $data_count / 2;
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($dairi_ippan_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi_ippan" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $dairi_ippan_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				// 右側表示
				if( ! empty($dairi_ippan_data[$i + $data_count])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi_ippan" . ($i + $data_count) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $dairi_ippan_data[$i + $data_count] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// 対管理販売店
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">対管理販売店</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$kanri_hanbai_data = array(
				'0' => '見積り提示',
				'1' => '販売店商談事前打合せ',
				'2' => '情報収集・企画導入状況確認',
				'3' => '予備１',
				'4' => '予備２',
				'5' => '予備３',
				'6' => '予備４',
				'7' => '予備５'
			);
			$data_count = 3;
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($kanri_hanbai_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri_hanbai" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $kanri_hanbai_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				// 右側表示
				if( ! empty($kanri_hanbai_data[$i + $data_count])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri_hanbai" . ($i + $data_count) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $kanri_hanbai_data[$i + $data_count] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// その他設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" name=\"other\">その他</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			$kanri_hanbai_data = array(
				'0' => '受発注・物流関連',
				'1' => '取組会議'
			);
			$data_count = 2;
			for ($i=MY_ZERO; $i < 7; $i++)
			{
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				// 左側表示
				if( ! empty($kanri_hanbai_data[$i]) AND $i < $data_count)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other" . $i .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $kanri_hanbai_data[$i] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談予定設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\">\n";
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"shoudan_plan\" id=\"shoudan_plan\"></textarea>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 不明領域設定
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_FREE) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_FREE) . "\n";
			$string_data .= "</td>\n</tr>\n";
			// フィールドセット終了
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";
			$string_data .= "<br>\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 * 活動区分（代理店）の新規HTML-STRINGを作成
	 *
	 *
	 */
	public function set_dairi_table($item = NULL,$conf_item = NULL,$pran_data)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_DAIRI;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（代理店区分）
		$drop_dairi_kubun = $CI->config->item(MY_SELECT_DAIRI_KUBUN);
		$drop_dairi_kubun['name'] = $conf_item['drop_dairi_kubun_name'];
		// ドロップダウン項目設定（代理店ランク）
		$drop_dairi_rank = $CI->config->item(MY_SELECT_DAIRI_RANK);
		$drop_dairi_rank['name'] = $conf_item['drop_dairi_rank_name'];
		// 代理店・一般設定項目設定
		$dairi_ippan_data = $CI->config->item(MY_DAIRI_IPPAN);
		$ippan_data = $dairi_ippan_data['data'];
		$ippan_right = $dairi_ippan_data['count_right'];
		$ippan_left = $dairi_ippan_data['count_left'];
		// 管理販売項目設定
		$kanri_hanbai_data = $CI->config->item(MY_KANRI_HANBAI);
		$kanri_data = $kanri_hanbai_data['data'];
		$kanri_right = $kanri_hanbai_data['count_right'];
		$kanri_left = $kanri_hanbai_data['count_left'];
		// 管理その他項目設定
		$kanri_other_data = $CI->config->item(MY_KANRI_OTHER);
		$other_data = $kanri_other_data['data'];
		$other_right = $kanri_other_data['count_right'];
		$other_left = $kanri_other_data['count_left'];
		// フリーラベル項目設定１
		$free_label1 = $CI->config->item('s_label_free');
		$free_label1['value'] = $conf_item['label_value1'];
		$free_label1['for_name'] = $conf_item['label_for_name1'];
		// フリーラベル項目設定２
		$free_label2 = $CI->config->item('s_label_free');
		$free_label2['value'] = $conf_item['label_value2'];
		$free_label2['for_name'] = $conf_item['label_for_name2'];

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			$string_data .= "<table style=\"border:1px #000000 solid\">\n<tr>\n";
			$string_data .= "<td>\n";
			// 日付設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"4\">日付</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year_name'] . "\" value=\"" . $pran_data['year'] . "\">年</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['month_name'] . "\" value=\"" . $pran_data['month'] . "\">月</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['day_name'] . "\" value=\"" . $pran_data['day'] . "\">日</td>\n";
			$string_data .= "</tr>\n";
			// 開始時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">開始時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour_name'] . "\" value=\"" . $pran_data['start_hour'] . "\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute_name'] . "\" value=\"" . $pran_data['start_minute'] . "\">分</td>\n";
			$string_data .= "</tr>\n";
			// 終了時刻設定
			$string_data .= "<tr>\n<td colspan=\"4\">終了時刻</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour_name'] . "\" value=\"" . $pran_data['end_hour'] . "\">時</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute_name'] . "\" value=\"" . $pran_data['end_minute'] . "\">分</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 卸名
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">卸名</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['shop_name'] . "\" value=\"\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['shop_name_link'] . "';\">";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 区分設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">区分</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_dairi_kubun) . "\n";
			$string_data .= "</td>\n</tr>\n";
			// ランク設定
			$string_data .= "<tr>\n<td colspan=\"2\">ランク</td>\n</tr>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($drop_dairi_rank) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			// 面談者設定
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td colspan=\"2\">面談者</td>\n</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name1'] . "\" value=\"" . $pran_data['mendan_name1'] . "\"></td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td><input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['mendan_name2'] . "\" value=\"" . $pran_data['mendan_name2'] . "\"></td>\n";
			$string_data .= "</tr>\n";
			// 同行者設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">同行者</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name1'] . "\" value=\"" . $pran_data['doukou_name1'] . "\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name1_link'] . "';\">\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n<tr>\n";
			$string_data .= "<td>&ensp;</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "<input type=\"text\" size=\"20\" maxlength=\"256\" name=\"" . $conf_item['doukou_name2'] . "\" value=\"" . $pran_data['doukou_name2'] . "\">";
			$string_data .= "<input type=\"button\" value=\"選択\" onclick=\"location.href='" . $conf_item['doukou_name2_link'] . "';\">\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "<td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談内容設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"3\">\n";
			$string_data .= "<table style=\"height:200px; width:800px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>【商談内容】</td>\n";
			$string_data .= "</tr>\n";
			// 対代理店・対一般店
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">対代理店・対一般店</td>\n";
			$string_data .= "</tr>\n";
			$s_td_string = "<td style=\"height:24px\">";
			$e_td_string = "</td>\n";
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $ippan_left + $i;
				// 左側表示
				if( ! empty($ippan_data[$leftcount]) AND $leftcount < $ippan_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi_ippan" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $ippan_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$righcount = $ippan_right + $i;
				// 右側表示
				if( ! empty($ippan_data[$righcount])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"dairi_ippan" . ($righcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $ippan_data[$righcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// 対管理販売店
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:300px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\">対管理販売店</td>\n";
			$string_data .= "</tr>\n";
			for ($i=MY_ZERO; $i < 7; $i++) {
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $kanri_left + $i;
				// 左側表示
				if( ! empty($kanri_data[$leftcount]) AND $leftcount < $kanri_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri_hanbai" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $kanri_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$righcount = $kanri_right + $i;
				// 右側表示
				if( ! empty($kanri_data[$righcount])){
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"kanri_hanbai" . ($righcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $kanri_data[$righcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			// その他設定
			$string_data .= "<td>\n";
			$string_data .= "<table style=\"border:1px #000000 solid; width:150px\">\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\" style=\"height:20px\"><input type=\"checkbox\" naem=\"" . $conf_item['other_name'] . "\">その他</td>\n";
			$string_data .= "</tr>\n";
			// 表示データ設定
			for ($i=MY_ZERO; $i < 7; $i++)
			{
				$string_data .= "<tr>\n";
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$leftcount = $other_left + $i;
				// 左側表示
				if( ! empty($other_data[$leftcount]) AND $leftcount < $other_right)
				{
					$string_data .= $s_td_string . "<input type=\"checkbox\" name=\"other_no" . ($leftcount + 1) .  "\">" . $e_td_string;
					$string_data .= $s_td_string . $other_data[$leftcount] . $e_td_string;
				}else{
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
					$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				}
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= $s_td_string . "&ensp;" . $e_td_string;
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td>\n";
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n<td>&ensp;</td>\n</tr>\n";
			// 商談予定設定
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"2\">【商談予定】</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"5\">\n";
			$string_data .= "<textarea rows=\"3\" cols=\"80\" name=\"" . $conf_item['work_plan_name'] . "\" id=\"shodan_plan\"></textarea>\n";
			$string_data .= "</td>\n";
			$string_data .= "</tr>\n";
			$string_data .= "<tr>\n<td>&emsp;</td>\n</tr>\n";
			// 不明領域設定
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_variable_label_string($free_label1) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "<br>\n";
			$string_data .= "<tr>\n<td colspan=\"5\">\n";
			$string_data .= $CI->item_manager->set_variable_label_string($free_label2) . "\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "</td>\n</tr>\n";
			$string_data .= "</table>\n";
			$string_data .= "<br>\n";
			// フィールドセット終了
			$string_data .= $CI->item_manager->set_end_field_string() . "\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 * 活動区分（代理店）の新規HTML-STRINGを作成
	 *
	 *
	 */
	public function set_new_office_table($item = NULL,$check = MY_ACTION_TYPE_OFFICE)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');
		$link = '/xxxxx/xxxxx';

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_field_string(MY_FIELD_PLAN);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_ACTION_TYPE,$check) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_VIEW) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_DEL) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_MOVE) . "\n";
			$string_data .= $CI->item_manager->set_button_string(MY_BUTTON_COPY) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			//
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"7\">日付</td>\n";
			$string_data .= "<td colspan=\"6\">時刻</td>\n";
			$string_data .= "<td colspan=\"2\">作業内容</td>\n";
			$string_data .= "<td>結果情報</td>";
			$string_data .= "</tr>\n";
			for($i=MY_ZERO; $i < 6; $i++)
			{
				// 日付設定
				$string_data .= "<tr>\n";
				$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"year" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>年</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"month" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>月</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"day" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>日</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				// 開始時刻設定
				$string_data .= "<td>開始</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_hour" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>時</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"start_minute" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>分</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				// 内勤作業設定
				$string_data .= "<td>\n";
				$string_data .= $CI->item_manager->set_dropdown_string(MY_SELECT_OFFICE_WORK) . "\n";
				$string_data .= "</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				$string_data .= "<td rowspan=\"2\"><textarea style=\"height:40px; width:300px\" name=\"action_result" . $i . "\"></textarea></td>\n";
				$string_data .= "</tr>\n";
				// 終了時刻設定
				$string_data .= "<tr>\n";
				$string_data .= "<td colspan=\"7\">&ensp;</td>\n";
				$string_data .= "<td>終了</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_hour" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>時</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"end_minute" . $i . "\" value=\"\"></td>\n";
				$string_data .= "<td>分</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				$string_data .= "<td style=\"padding-left:10px\">その他<input type=\"text\" size=\"15\" maxlength=\"15\" name=\"other_action" . $i . "\" value=\"\"></td>\n";
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "<br>\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	/**
	 * 活動区分（代理店）の新規HTML-STRINGを作成
	 *
	 *
	 */
	public function set_office_table($item = NULL,$conf_item = NULL,$pran_data)
	{
		// 引数チェック
		if(is_null($item))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		// フィールドリスト設定
		$field_setting = $CI->config->item(MY_FIELD_PLAN);
		$field_setting['id'] = $conf_item['field_id'];
		$field_setting['class'] = $conf_item['field_class'];
		// ドロップダウンリスト表示値（店舗）
		$check = MY_ACTION_TYPE_OFFICE;
		// 活動区分リスト設定
		$action_conf = $CI->config->item(MY_SELECT_ACTION_TYPE);
		$action_conf['name'] = $conf_item['action_type_name'];
		$action_conf['check'] = $conf_item['action_type_check'];
		// ボタン項目設定(表示)
		$button_view = $CI->config->item(MY_BUTTON_VIEW);
		$button_view['name'] = $conf_item['button_view_name'];
		// ボタン項目設定(削除)
		$button_del = $CI->config->item(MY_BUTTON_DEL);
		$button_del['name'] = $conf_item['button_del_name'];
		// ボタン項目設定(移動)
		$button_move = $CI->config->item(MY_BUTTON_MOVE);
		$button_move['name'] = $conf_item['button_move_name'];
		// ボタン項目設定(コピー)
		$button_copy = $CI->config->item(MY_BUTTON_COPY);
		$button_copy['name'] = $conf_item['button_copy_name'];
		// ドロップダウン項目設定（内勤作業）
		$office_work_data = $CI->config->item(MY_SELECT_OFFICE_WORK);

		$string_data = NULL;

		if($item === MY_PLAN)
		{
			// 活動区分設定
			$string_data .= $CI->item_manager->set_start_variable_field_string($field_setting);
			$string_data .= "<table>\n<tr>\n<td>\n";
			$string_data .= $CI->item_manager->set_label_string(MY_LABEL_KUBUN) . "\n";
			$string_data .= $CI->item_manager->set_variable_dropdown_string($action_conf) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_view) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_del) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_move) . "\n";
			$string_data .= $CI->item_manager->set_variable_button_string($button_copy) . "\n";
			$string_data .= "</td>\n</tr>\n</table>\n";
			//
			$string_data .= "<table>\n";
			$string_data .= "<tr>\n";
			$string_data .= "<td colspan=\"7\">日付</td>\n";
			$string_data .= "<td colspan=\"6\">時刻</td>\n";
			$string_data .= "<td colspan=\"2\">作業内容</td>\n";
			$string_data .= "<td>結果情報</td>";
			$string_data .= "</tr>\n";
			for($i=1; $i < 6; $i++)
			{
				// 日付設定
				$string_data .= "<tr>\n";
				$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['year'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>年</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['month'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>月</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"4\" maxlength=\"4\" name=\"" . $conf_item['day'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>日</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				// 開始時刻設定
				$string_data .= "<td>開始</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_hour'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>時</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['start_minute'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>分</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				// 内勤作業設定
				$string_data .= "<td>\n";
				$office_work_data['name'] = $conf_item['office_work_name'][$i];
				$string_data .= $CI->item_manager->set_variable_dropdown_string($office_work_data) . "\n";
				$string_data .= "</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				$string_data .= "<td rowspan=\"2\"><textarea style=\"height:40px; width:300px\" name=\"" . $conf_item['action_result'][$i] . "\"></textarea></td>\n";
				$string_data .= "</tr>\n";
				// 終了時刻設定
				$string_data .= "<tr>\n";
				$string_data .= "<td colspan=\"7\">&ensp;</td>\n";
				$string_data .= "<td>終了</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_hour'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>時</td>\n";
				$string_data .= "<td><input type=\"text\" size=\"2\" maxlength=\"2\" name=\"" . $conf_item['end_minute'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "<td>分</td>\n";
				$string_data .= "<td>&ensp;</td>\n";
				$string_data .= "<td style=\"padding-left:10px\">その他<input type=\"text\" size=\"15\" maxlength=\"15\" name=\"" . $conf_item['other_action'][$i] . "\" value=\"\"></td>\n";
				$string_data .= "</tr>\n";
			}
			$string_data .= "</table>\n";
			$string_data .= "<br>\n";
		}else if($item === MY_RESULT){

		}else{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		return $string_data;
	}

	function set_top_todo_string($todo_data = NULL,$admin_flg){
		// 引数チェック
		if (is_null($todo_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$base_url = $CI->config->item('base_url');
		$string_data = NULL;
		$impkbn = NULL;

		$string_data .= "<table width=\"275px\">\n";
		$string_data .= "<tr style=\"height:18px\">\n";
		$string_data .= "<th style=\"text-align:left\">\n";
		$string_data .= "<a href=\"".$base_url."index.php/todo/index\" style=\"border:1px #000000 solid;font-size:10pt;text-decoration:none;color:#000000;\">TO DO</a>\n";
		$string_data .= "</th>\n";
		$string_data .= "<td style=\"text-align:right\">\n";
		$string_data .= "<input type=\"submit\" style=\" height:18px; font-size:10px;\" value=\"更新\">\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<td style=\"padding-left:0px; border-collapse:collapse; border:1px solid #000000;\" colspan=\"2\">\n";
		$string_data .= "<table>\n";
		$string_data .= "<tr>\n";
		$string_data .= "<th style=\" border-bottom:1px #000000 solid; height:20px; width:40px; background-color:#FFFF99; padding-top:5px; text-align:center;\">期限</th>\n";
		$string_data .= "<th style=\" border-bottom:1px #000000 solid; height:20px; width:100px; background-color:#FFFF99; padding-top:5px; text-align:center;\">内容</th>\n";
		$string_data .= "<th style=\" border-bottom:1px #000000 solid; height:20px; width:50px; background-color:#FFFF99; padding-top:5px; text-align:center;\">重要度</th>\n";
		$string_data .= "<th style=\" border-bottom:1px #000000 solid; height:20px; width:75px; background-color:#FFFF99; padding-top:5px; text-align:center;\">終了確認</th>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";
//		if ($admin_flg === MY_TYPE_GENERAL) {
		if ($admin_flg === '003' OR $admin_flg === '002') {
			$string_data .= "<div style=\"margin:0; height:80px; width:270px; overflow:auto; background-color:#FFFFFF;\">\n";
		}else{
			$string_data .= "<div style=\"margin:0; height:230px; width:270px; overflow:auto; background-color:#FFFFFF;\">\n";
		}
		$string_data .= "<table>\n";
		foreach ($todo_data as $key => $value) {
			$string_data .= "<tr>\n";
			$string_data .= "<td style=\"height:12px; width:40px;\" align=\"left\">";
			$string_data .= $value['day'];
			$string_data .= "<input type=\"hidden\" name=\"todo_jyoho".$key."\" value=\"".$value['jyohonum']."\">\n";
			$string_data .= "<input type=\"hidden\" name=\"todo_edbn".$key."\" value=\"".$value['edbn']."\">\n";
			$string_data .= "</td>\n";
//			$string_data .= "<td style=\"height:12px; width:100px;\" align=\"left\"><a href=\"http://localhost/elleair/plan\">あああああああ</a></td>\n";
			$string_data .= "<td style=\"height:12px; width:90px;\" align=\"left\"><a href=\"".$base_url."index.php/todo/check_view/".$value['jyohonum']."/".$value['edbn']."\">".$value['todo']."</a></td>\n";
			if(!empty($value['impkbn'])){
				if($value['impkbn'] === '1'){
					$impkbn = "低";
				}else if($value['impkbn'] === '2'){
					$impkbn = "中";
				}else if($value['impkbn'] === '3'){
					$impkbn = "高";
				}
			}
			$string_data .= "<td style=\"height:12px; width:50px;\" align=\"center\">".$impkbn."</td>\n";
			if ($value['check'] === '') {
				$string_data .= "<td style=\"height:12px; width:50px;\" align=\"center\"></td>\n";
			}else{
				$string_data .= "<td style=\"height:12px; width:50px;\" align=\"center\"><input type=\"checkbox\" name=\"todo_check".$key."\"></td>\n";
			}
			$string_data .= "</tr>\n";
		}
		$string_data .= "</table>\n";
		$string_data .= "</div>\n";
		$string_data .= "</td>\n";
		$string_data .= "</tr>\n";
		$string_data .= "</table>\n";

		return $string_data;
	}

	/**
	 * 商談履歴テーブル情報設定
	 *
	 * @param	array
	 * @return	string
	 */
	function set_nego_history_data($data, $pagination)
	{

		// 初期化
		$CI =& get_instance();

		// 設定値取得
		$table = $CI->config->item('c_nego_history_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_nego_history_title'); // タイトル情報取得
		$link  = $CI->config->item('c_nego_history_link');  // リンク情報取得

		if($pagination !== null){

			$other = array(
					"td_style" => "text-align:right;",
					"type" => null,
					"pagination" => $pagination
				); // 追加情報取得
		}

		// 商談履歴検索結果テーブル作成
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
		// テーブル内容設定
		foreach($data as $key => $value)
		{
			$string_table .= "<tr style=\"background-color:#FFFFFF; \">\n";
			$i = 0;
			foreach($table['data_key'] as $data_key)
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

				// style設定有無
				if( ! is_null($string_style))
				{
					$string_table .= "<td style=\"" . $string_style . "\">";
				}else{
					$string_table .= "<td>";
				}

				// 位置設定
				if( ! is_null($table['div_td_align'][$i]))
				{
					$string_style .= " align=\"" . $table['div_td_align'][$i];
				}

				// "a"タグ判定
				if($table['div_td_a_existence'][$i] === TRUE)
				{
					$href = $CI->config->item('base_url') . $link['link'] . '/'. $value['ymd'];
					$string_table .= "<a href=\"" . $href . "\">";
					$string_table .= $value[$data_key];
					$string_table .= "</a></td>\n";
				}else{
					// inputtype判定
					if( ! is_null($table['div_td_input_type'][$i]))
					{
						$string_table .= "<input type=\"" . $table['div_td_input_type'][$i] . "\">";
					}
					$string_table .= $value[$data_key];
					$string_table .= "</td>\n";
				}

				$i++;

			}
			$string_table .= "</tr>\n";
		}
		$string_table .= "</table>\n";
		$string_table .= "</td>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";

		return $string_table;
	}

	/**
	 * 情報出力
	 * 日報一覧の検索HTMLを作成
	 *
	 * @access public
	 * @param  array $shbn 社番
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function output_csv_nipo_set_b_table($shbn,$post=NULL,$honbu=NULL,$bu=NULL,$ka=NULL,$type=NULL)
	{
		log_message('debug',"================= output_csv_s_rireki_set_b_table start =====================");
		// 初期化
		$CI =& get_instance();


		// 設定値取得
		$table = $CI->config->item('output_csv_n_rireki_b_data');      // テーブル設定情報取得
		$title = $CI->config->item('output_csv_n_rireki_b_title');     // タイトル情報取得
		$td_data[] = $CI->config->item('output_csv_n_rireki_honbu');   // ドロップダウン項目本部の設定
		$td_data[] = $CI->config->item('output_csv_n_rireki_bu');      // ドロップダウン項目部の設定
		$td_data[] = $CI->config->item('output_csv_n_rireki_unit');    // ドロップダウン項目ユニットの設定
		$td_data[] = $CI->config->item('output_csv_n_rireki_user');    // ドロップダウン項目ユニットの設定
	//	echo $shbn;
		/*if(isset($ka) && $ka !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu,$bu,$ka);		//本部選択時
		}else */
		if(isset($ka) && $ka !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,$honbu,$bu,$ka,NULL,'1');		//本部選択時
		}else if(isset($bu) && $bu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,$honbu,$bu,NULL,NULL,'1');		//本部選択時
		}else if(isset($honbu) && $honbu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,$honbu,NULL,NULL,NULL,'1');		//本部選択時
		}else{
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,NULL,NULL,NULL,NULL,'1');               // 部署情報取得
		}

		$string_table = $this->_set_box_table($table, $title, $post, $td_data, NULL, $data); // 部署コード選択テーブル作成
		log_message('debug',"================= output_csv_s_rireki_set_b_table end =====================");
		return $string_table;
	}
	
		/**
	 * 情報出力
	 * 情報メモの検索HTMLを作成
	 *
	 * @access public
	 * @param  array $shbn 社番
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function output_csv_memo_set_b_table($shbn,$post=NULL,$honbu=NULL,$bu=NULL,$ka=NULL,$type=NULL)
	{
		log_message('debug',"================= output_csv_s_rireki_set_b_table start =====================");
		// 初期化
		$CI =& get_instance();


		// 設定値取得
		$table = $CI->config->item('output_csv_m_rireki_b_data');      // テーブル設定情報取得
		$title = $CI->config->item('output_csv_m_rireki_b_title');     // タイトル情報取得
		$td_data[] = $CI->config->item('output_csv_m_rireki_honbu');   // ドロップダウン項目本部の設定
		$td_data[] = $CI->config->item('output_csv_m_rireki_bu');      // ドロップダウン項目部の設定
		$td_data[] = $CI->config->item('output_csv_m_rireki_unit');    // ドロップダウン項目ユニットの設定
		$td_data[] = $CI->config->item('output_csv_m_rireki_user');    // ドロップダウン項目ユニットの設定
	//	echo $shbn;
		/*if(isset($ka) && $ka !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu,$bu,$ka);		//本部選択時
		}else */
		if(isset($ka) && $bu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,$honbu,$bu,$ka,NULL,'1');		//本部選択時
		}else if(isset($bu) && $bu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,$honbu,$bu,NULL,NULL,'1');		//本部選択時
		}else if(isset($honbu) && $honbu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,$honbu,NULL,NULL,NULL,'1');		//本部選択時
		}else{
			$data	= $this->_get_search_busyo_data($shbn,$post,NULL,NULL,NULL,NULL,NULL,'1');               // 部署情報取得
		}

		$string_table = $this->_set_box_table($table, $title, $post, $td_data, NULL, $data); // 部署コード選択テーブル作成
		log_message('debug',"================= output_csv_s_rireki_set_b_table end =====================");
		return $string_table;
	}
	
		/**
	 * 情報出力
	 * 商談履歴の検索HTMLを作成
	 *
	 * @access public
	 * @param  array $shbn 社番
	 * @param  array $post 検索条件
	 * @return string $string_table
	 */
	public function output_csv_s_rireki_set_b_table($shbn,$post=NULL,$honbu=NULL,$bu=NULL,$ka=NULL,$user_flg=NULL)
	{
		log_message('debug',"================= output_csv_s_rireki_set_b_table start =====================");
		// 初期化
		$CI =& get_instance();

		// 設定値取得
		$table = $CI->config->item('output_csv_s_rireki_b_data');      // テーブル設定情報取得
		$title = $CI->config->item('output_csv_s_rireki_b_title');     // タイトル情報取得
		$td_data[] = $CI->config->item('output_csv_s_rireki_honbu');   // ドロップダウン項目本部の設定
		$td_data[] = $CI->config->item('output_csv_s_rireki_bu');      // ドロップダウン項目部の設定
		$td_data[] = $CI->config->item('output_csv_s_rireki_unit');    // ドロップダウン項目ユニットの設定

		if(isset($ka) && $ka !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu,$bu,$ka);		//本部選択時
		}else if(isset($bu) && $bu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu,$bu);		//本部選択時
		}else if(isset($honbu) && $honbu !=""){
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE,$honbu);		//本部選択時
		}else{
			$data	= $this->_get_search_busyo_data($shbn,$post,TRUE);               // 部署情報取得
		}
				

		$string_table = $this->_set_box_table($table, $title, $post, $td_data, NULL, $data); // 部署コード選択テーブル作成
		log_message('debug',"================= output_csv_s_rireki_set_b_table end =====================");
		return $string_table;
	}

	
	/**
	 * 相手先検索 部リスト変更HTML作成
	 *
	 * @access public
	 * @param  array $post
	 * @return string $string_table
	 */
	function set_select_client_search_list_string($head_info,$division_info,$unit_info,$staff_list,$honbu,$bu=NULL,$ka=NULL){
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');

		$string_table = "";
		$string_table .= "<table class=\"search_list_box\">";
		$string_table .= "<tr>";
		$string_table .= "<th colspan=\"3\" align=\"left\">【担当商談先より検索】</th>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">本部</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"honbucd\" class=\"search_list\" id=\"daibunrui_list\" onChange=\"reload_dropdown('".$base_url."');\">";
		$i=0;
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $head_info as $value ) {
		$string_table .= "<option value=\"".$value['honbucd']."\"";
		if ( $honbu == $value['honbucd'] ) { 
		$string_table .= 'selected'; 
		}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .= "</select>";
		$string_table .= "</td>";
		$string_table .= "<td></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">部</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"bucd\"  class=\"search_list\" id=\"bu_list\" onChange=\"reload_dropdown_unit('".$base_url."');\">";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $division_info as $value ) {
			$string_table .= "<option value=\"".$value['bucd']."\"";
			if ( $bu == $value['bucd'] ) { 
				$string_table .= 'selected'; 
			}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .="</select>";
		$string_table .="</td>\n";
		$string_table .= "<td></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">ユニット</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"kacd\" class=\"search_list\" id=\"unit_list\" onChange=\"reload_dropdown_user('".$base_url."');\">";
		$i=0;
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $unit_info as $value ) {
				$string_table .= "<option value=\"".$value['kacd']."\"";
				if ( $ka == $value['kacd'] ) { 
					$string_table .= 'selected'; 
				}
		$string_table .= ">";
		$string_table .= $value['bunm'];
		$string_table .= "</option>";
		}
		$string_table .="</select>";
		$string_table .="</td>";
		$string_table .= "</td>";
		$string_table .= "<td></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">担当者名</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"shbn\" class=\"search_list\">";
		$string_table .= "<option value=\"\">--選択してください--</option>";
		foreach ( $staff_list as $staff ) {
		$string_table .= "<option value=\"".$staff['shbn']."\">".$staff['shinnm']."</option>";
		}
		$string_table .= "</select>";
		$string_table .= "</td>";
		$string_table .= "<td></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">本部名称</td>";
		$string_table .= "<td align=\"left\"><input type=\"text\" name=\"aitesknm\" class=\"search_list\" value=\"\"></td>";
		$string_table .= "<td></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr><td style=\"height:10px;\"></td></tr>";
		$string_table .= "</table>";
		return $string_table;
	}

	/**
	 * 相手先検索(店舗) リスト変更HTML作成
	 *
	 * @access public
	 * @param  array $post
	 * @return string $string_table
	 */
	function set_select_client_search_list_string_maker($head_info,$division_info,$unit_info,$staff_list,$honbu,$prefecture,$bu=NULL,$ka=NULL){
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');
		
		$string_table = "";
		$string_table .= "<table class=\"search_list_box\">";
		$string_table .= "<tr>";
		$string_table .= "<th colspan=\"2\" align=\"left\">【担当商談先より検索】</th>";
		$string_table .= "<td style=\"width:50px\"></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">本部</td>";
		$string_table .= "<td align=\"right\">";
		$string_table .= "<select name=\"honbucd\" class=\"search_list\" id=\"daibunrui_list\" onChange=\"reload_dropdown_maker('".$base_url."');\">";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $head_info as $value ) {
		$string_table .= "<option value=\"".$value['honbucd']."\"";
		if ( $honbu == $value['honbucd'] ) { 
		$string_table .= 'selected'; 
		}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .= "</select>";
		$string_table .= "</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">部</td>";
		$string_table .= "<td align=\"right\">";
		$string_table .= "<select name=\"bucd\" class=\"search_list\" id=\"bu_list\" onChange=\"reload_dropdown_maker_unit('".$base_url."');\">";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $division_info as $value ) {
			$string_table .= "<option value=\"".$value['bucd']."\"";
			if ( $bu == $value['bucd'] ) { 
				$string_table .= 'selected'; 
			}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .="</select>";
		$string_table .="</td>\n";
		
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">ユニット</td>";
		$string_table .= "<td align=\"right\">";
		$string_table .= "<select name=\"kacd\" class=\"search_list\" id=\"unit_list\" onChange=\"reload_dropdown_maker_user('".$base_url."');\">";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $unit_info as $value ) {
				$string_table .= "<option value=\"".$value['kacd']."\"";
				if ( $ka == $value['kacd'] ) { 
					$string_table .= 'selected'; 
				}
		$string_table .= ">";
		$string_table .= $value['bunm'];
		$string_table .= "</option>";
		}
		$string_table .="</select>";
		$string_table .="</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">担当者名</td>";
		$string_table .= "<td align=\"right\">";
		$string_table .= "<select name=\"shbn\" class=\"search_list\">";
		$string_table .= "<option value=\"\">--選択してください--</option>";
		foreach ( $staff_list as $staff ) {
		$string_table .= "<option value=\"".$staff['shbn']."\">".$staff['shinnm']."</option>";
		}
		$string_table .= "</select>";
		$string_table .= "</td>";
		$string_table .= "</tr>";
		
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">都道府県</td>";
		$string_table .= "<td align=\"right\">";
		$string_table .= "<select name=\"pref\" class=\"search_list\">";
		$string_table .= "<option value=\"\">--選択してください--</option>";
							foreach ( $prefecture as $value ) {
		$string_table .= "<option value=\"".$value."\">".$value."</option>";
							}
		$string_table .="</select>";
		$string_table .="</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">店舗名称</td>";
		$string_table .= "<td align=\"right\"><input type=\"text\" class=\"search_list\" name=\"aitesknm\" value=\"\"></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr><td style=\"height:10px;\"></td></tr>";
		$string_table .= "</table>";
		return $string_table;
	}
	
	/**
	 * 相手先検索(代理店) リスト変更HTML作成
	 *
	 * @access public
	 * @param  array $post
	 * @return string $string_table
	 */
	function set_select_client_search_list_string_agency($head_info,$division_info,$unit_info,$staff_list,$honbu,$prefecture,$bu=NULL,$ka=NULL){
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$base_url = $CI->config->item('base_url');

		$string_table = "";
		$string_table .= "<table class=\"search_list_box_dairi\">";
		$string_table .= "<tr>";
		$string_table .= "<th colspan=\"2\" align=\"left\">【担当商談先より検索】</th>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">本部</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"honbucd\" class=\"search_list\" id=\"daibunrui_list\" onChange=\"reload_dropdown_agency('".$base_url."');\">";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $head_info as $value ) {
		$string_table .= "<option value=\"".$value['honbucd']."\"";
		if ( $honbu == $value['honbucd'] ) { 
		$string_table .= 'selected'; 
		}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .= "</select>";
		$string_table .= "</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">部</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"bucd\" class=\"search_list\" id=\"bu_list\" onChange=\"reload_dropdown_agency_unit('".$base_url."');\">";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $division_info as $value ) {
			$string_table .= "<option value=\"".$value['bucd']."\"";
			if ( $bu == $value['bucd'] ) { 
				$string_table .= 'selected'; 
			}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .="</select>";
		$string_table .="</td>\n";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">ユニット</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"kacd\" class=\"search_list\" id=\"unit_list\" onChange=\"reload_dropdown_agency_user('".$base_url."');\" >";
		$string_table .= "<option value=\"XXXXX\">--選択してください--</option>";
		foreach ( $unit_info as $value ) {
			$string_table .= "<option value=\"".$value['kacd']."\"";
			if ( $ka == $value['kacd'] ) { 
				$string_table .= 'selected'; 
			}
		$string_table .=">";
		$string_table .= $value['bunm'];
		$string_table .="</option>";
		}
		$string_table .="</select>";
		$string_table .="</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">担当者名</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"shbn\" class=\"search_list\" >";
		$string_table .= "<option value=\"\">--選択してください--</option>";
		foreach ( $staff_list as $staff ) {
		$string_table .= "<option value=\"".$staff['shbn']."\">".$staff['shinnm']."</option>";

		}
		$string_table .= "</select>";
		$string_table .= "</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">都道府県</td>";
		$string_table .= "<td align=\"left\">";
		$string_table .= "<select name=\"pref\" class=\"search_list\">";
		$string_table .= "<option value=\"\">--選択してください--</option>";
							foreach ( $prefecture as $value ) {
		$string_table .= "<option value=\"".$value."\">".$value."</option>";
							}
		$string_table .="</select>";
		$string_table .="</td>";
		$string_table .= "</tr>";
		$string_table .= "<tr>";
		$string_table .= "<td class=\"list_name\">代理店本支店名</td>";
		$string_table .= "<td align=\"left\"><input type=\"text\" class=\"search_list\" name=\"aitesknm\" value=\"\"></td>";
		$string_table .= "</tr>";
		$string_table .= "<tr><td style=\"height:10px;\"></td></tr>";
		$string_table .= "</table>";
		return $string_table;
	}

}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
