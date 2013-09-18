<?php
	echo $header; // ヘッダ部表示
	if (!is_null($menu)){ // メニュー表示判定
		echo $menu; // メニュー部表示
	}
	echo $main; // メインコンテンツ表示
	echo $footer; // フッダ部表示
?>
