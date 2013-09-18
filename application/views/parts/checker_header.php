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
		if ( isset($form_name) && $form_name ) {
			echo "<form action=\"" . $form. "\" method=\"POST\" name=\"" . $form_name. "\">\n";
		} else {
			echo "<form action=\"" . $form . "\" method=\"POST\">\n";
		}
	}
	// ヘッダCSS設定
	foreach($this->config->item('c_header') as $key => $value)
	{
		// 「name」フィールド仮設定の為
		// 今後変更する
		if($value['name'] === 'aaa')
		{
			echo "<div id=\"".$value['id']."\">\n";
			echo "<table width=\"".$this->config->item('s_header_table_width')."\">\n";
			echo "<tr>\n";
			echo "<td class=\"".$value['class_title']."\" colspan=\"".$value['span']."\">\n";
			echo img($image)."\n";
			echo "</td>\n";
			echo "<td rowspan=\"".$value['span']."\" class=\"".$value['class_right']."\">\n";
		}
	}

	// 日付・その他設定
	echo mdate($datestring, $time);
	echo "(".$weekday[date("w")].")\n";
	echo "<br>";
	// 部名設定
	if (isset($bu_name)) {
		echo $bu_name;
	}
	echo "<br>";
	// 課名設定
	if (isset($ka_name)) {
		echo $ka_name;
	}
	echo "<br>";
	// 社員名設定
	if (isset($shinnm)) {
		echo $shinnm;
	}
	echo "</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	// エラーメッセージ表示
	if( ! is_null($errmsg))
	{
		echo "<td class=\"errmsg2\">";
		echo $errmsg;
		echo "\n</td>\n";
	}
	/* 一時しのぎ */
	if(isset($btn_name))
	{
		// ボタン表示
		if( ! is_null($btn_name))
		{
			echo "<td class=\"btn2\">\n";
			echo "<input type=\"submit\" id=\"set\" name=\"set\" value=\"$btn_name\">\n";
			echo "</td>\n";
		}
	}
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td colspan=\"2\">";
	echo "確認者 <input type=\"text\" class=\"input\"  style=\"width:148px\ maxlength=\"256\" value=\"\">\n";
	echo "<input type=\"button\" value=\"選択\">\n";
	echo "</td>\n";
	echo "</tr>\n";

	echo "</table>\n";
	echo br(1);
	echo "\n</div>\n";
	
?>
