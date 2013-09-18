<?php
	// ヘルパー読込
	$this->load->helper('html');
	$this->load->helper('date');
	// 個別CSS設定
	$individual_css = array('href' => $css, 'rel' => 'stylesheet', 'type' => 'text/css');
	// 曜日設定変数設定
	$time       = time(); // 日付取得
	$datestring = "%Y/%m/%d "; // 日付フォーマット
	$weekday    = $this->config->item('c_day_week_ja'); // 日本語曜日フォーマット
	
	// DOCTYPE宣言
	echo doctype($this->config->item('c_doctype'));
	echo "\n<html>\n<head>\n";
	// META宣言
	echo meta($this->config->item('c_meta'));
	// TITLE宣言
	echo "<title>";
	echo $title;
	echo "</title>\n";
	// 共通CSS宣言
	echo link_tag($this->config->item('c_main_css'));
	echo "\n";
	// 個別CSS宣言
	if( ! is_null($css))
	{
		echo link_tag($individual_css);
	}
	echo "\n</head>\n";
	// ヘッダ生成
	echo "<body>\n";
	// formタグの挿入
	echo "<form action=\"" . $day . "\" method=\"POST\">";
	echo $hidden_day;
	echo $hidden_func;
	// ヘッダCSS設定
	echo "<div id=\"Header\">\n";
	echo "<table width=\"990px\">\n";
	echo "<tr>\n";
	echo "<td class=\"title1\">\n";
//	echo "<img src=\"http://localhost/elleair/images/result.gif\" alt=\"\"/>\n";
	echo "<img src=\"".$this->config->item('base_url')."images/result.gif\" alt=\"\"/>\n";
	echo "</td>\n";
	echo "<td rowspan=\"2\" class=\"right1\">\n";

	// 日付・その他設定
	echo mdate($datestring, $time);
	echo "(";
	echo $weekday[date("w")];
	echo ")\n";
	
	/* 
	 * 表示項目
	 * 部署・課・ユニット・氏名
	 * 未決定の為未着手
	 */
	
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	// エラーメッセージ表示
	if( ! is_null($errmsg))
	{
		echo "<td class=\"errmsg1\">" . $errmsg . "\n</td>\n";
	}
	echo "</tr>\n";
	echo "<tr>\n";
	// 確認者選択
	echo "<td colspan=\"2\">\n";
	echo "<table id=\"s_input\">\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">確認者<input type=\"text\" name=\"do_action\" size=\"20\" maxlength=\"256\" value=\"\" >\n";
	echo "<input type=\"button\" name=\"select_checker\" value=\"選択\" onclick=\"location.href='" . $this->config->item('base_url') . "index.php/" . "select_checker/index'\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">日付";
	echo "<input type=\"text\" name=\"head_year\" size=\"4\" maxlength=\"4\" value=\"" . $head_date['year'] . "\" >年";
	echo "<input type=\"text\" name=\"head_month\" size=\"2\" maxlength=\"2\" value=\"" . $head_date['month'] . "\" >月";
	echo "<input type=\"text\" name=\"head_day\" size=\"2\" maxlength=\"2\" value=\"" . $head_date['day'] . "\" >日";
	echo "(<input type=\"text\" name=\"head_weekday\" size=\"2\" maxlength=\"2\" value=\"" . $head_date['weekday'] . "\" disabled>)";
	echo "<input type=\"button\" name=\"head_before_day\" size=\"1\" value=\"<<前日\" onclick=\"location.href='" . $day_before_link . "'\">";
	echo "<input type=\"button\" name=\"head_next_day\" size=\"1\" value=\"翌日>>\" onclick=\"location.href='" . $next_day_link . "'\">";
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td>\n";
	echo "<button name=\"get_plan\" type=\"submit\" value=\"get_plan\" >予定取得</button>\n";
	echo nbs(1);
	echo "<button name=\"check_result\" type=\"button\">入力確認</button>\n";
	echo nbs(1);
	echo "<input type=\"submit\" name=\"register\" value=\"登録\">\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	// ヘッダ部
	echo "<table id=\"pl_title\" width=\"502\">\n";
	echo "<tr>\n";
	echo "<th class=\"l_left\" width=\"92\">時刻</th>\n";
	echo "<th class=\"l_center\" width=\"48\">区分</th>\n";
	echo "<th class=\"l_right\" width=\"352\">相手先</th>\n";
	echo "</tr>\n";
	echo "</table>\n";
	
	echo "<div id=\"p_list\" width=\"500\">\n";
	echo "<table>\n";
	foreach ($summary as $key => $value) {
		echo "<tr>\n";
		echo "<td class=\"l_left\" width=\"92\">" . $value['time'] . "</td>\n";
		echo "<td class=\"center\" width=\"50\">" . $value['kubun'] . "</td>\n";
		echo "<td width=\"352\">" . $value['aitesknm'] . "</td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
	echo "</div>\n";
	echo "</td>\n";
	echo "</tr>\n";
	echo "</table>\n";
	echo br(1);
	echo "\n</div>\n";
	
?>
