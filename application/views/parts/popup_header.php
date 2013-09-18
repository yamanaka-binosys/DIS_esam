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
	if(! is_null($css))
	{
		echo link_tag($individual_css);
	}
	echo "\n</head>\n";
	// ヘッダ生成
	echo "<body>\n";
	// formタグの挿入
	if(isset($form))
	{
		echo "<form action=\"";
		echo $form;
		echo "\" method=\"POST\">\n";
	}
	// ヘッダCSS設定
	foreach($this->config->item('c_header') as $key => $value)
	{
		// 「name」フィールド仮設定の為
		// 今後変更する
		if($value['name'] === 'aaa')
		{
			echo "<div id=\"";
			echo $value['id'];
			echo "\">\n";
			echo "<table width=\"width:580px\" style=\"overflow:visible;\">\n";
			echo "<tr>\n";
			echo "<td class=\"";
			echo "title";
			echo "\" style=\"left:165px;\">\n";
			echo img($image);
			echo "\n</td>\n";
			echo "<td";
			echo " class=\"";
			echo "right";
			echo "\" style=\"position: relative;left:130px;\">\n";
		}
	}

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
		echo "<td class=\"errmsg\">";
		echo $errmsg;
		echo "\n</td>\n";
	}
	/* 一時しのぎ */
	if(isset($btn_name))
	{
		// ボタン表示
		if( ! is_null($btn_name))
		{
			echo "<td class=\"btn\" width=\"200px\" style=\"position: relative;left:350px\">";
			echo "<input type=\"submit\" id=\"set\" name=\"set\" value=\"$btn_name\" onClick=\"window.close()\">";
			echo "\n</td>\n";
		}
	}
	
	echo "</tr>\n";
	echo "</table>\n";
	echo br(1);
	echo "\n</div>\n";
	
?>
