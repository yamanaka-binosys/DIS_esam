<?php
	// ヘルパー読込
	$this->load->helper('html');
	// メニュー項目読込
	$menu_name = $this->config->item('c_menu_name');
	// メニューリンク読込
	$menu_link = $this->config->item('c_menu_link');
	
	echo "<div id=\"";
	echo $menu_name['id'];
	echo "\">\n";
	echo br(1);
	echo "\n<b>";
	echo $menu_name['name'];
	echo "</b>\n";
//	echo "<ul class=\"";
//	echo $menu_name['class_ul'];
//	echo "\">\n";
//	// 別ウィンドウが開くメニュー生成
//	for($i=0; $i < 5; $i++)
//	{
//		if($i == 0)
//		{
//			echo "<li>";
//			echo $menu_name['window']['other_window'][$i];
//			echo "</li>\n";
//		}else{
//			echo "<li><a href=\"";
//			echo $this->config->item('base_url');
//			echo $menu_link['other_window'][$i];
//			echo "\">";
//			echo $menu_name['window']['other_window'][$i];
//			echo "</a></li>\n";
//		}
//	}
//	echo "</ul>\n";
//	echo br(1);
	echo "\n<ul class=\"";
	echo $menu_name['class_ul'];
	echo "\">\n";
	// メインメニュー生成
	for($i=0; $i < 12; $i++)
	{
		// 管理者メニュー生成
		if($i == $menu_name['admin_no'] && $admin_flg === TRUE)
		{
			for($j=0; $j < 5; $j++)
			{
				if($j == 0)
				{
					echo "<li><strong>";
					echo $menu_name['window']['admin_window'][$j];
					echo "</strong>\n";
					echo "<ul class=\"";
					echo $menu_name['class_sub'];
					echo "\">\n";
				}else{
					echo "<li><a href=\"";
					echo $this->config->item('base_url');
					echo $menu_link['admin_window'][$j];
					echo "\">";
					echo $menu_name['window']['admin_window'][$j];
					echo "</a></li>\n";
				}
			}
			echo "</ul>\n";
			echo "</li>\n";
		}
		echo "<li><a href=\"";
		echo $this->config->item('base_url');
		echo $menu_link['main_window'][$i];
		echo "\">";
		echo $menu_name['window']['main_window'][$i];
		echo "</a></li>\n";
	}
	echo "</ul>\n";
	echo br(1);
	echo "<input type=\"button\" value=\"ログアウト\" onclick=\"location.href='" . $this->config->item('base_url') . "index.php/" . "login/index'\">\n";
	echo br(2);
	echo "</div>\n";
?>
