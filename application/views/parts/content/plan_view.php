<?php
log_message('debug',"===== Start plan_view.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
?>

<?php echo doctype('html4-frame'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/plan.css" rel="stylesheet" type="text/css">
<link href="<?php echo $base_url; ?>css/jquery-ui-1.8.18.custom.css" rel="stylesheet" media="screen">
<style>
	.ui-datepicker {font-size: 85%;}
	input[type=text] {width: 6em;}
	body { background-color: #D1FBFF; }
</style>
<script type="text/javascript" src="<?php echo $base_url; ?>script/plan_view.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-i18n.js"></script>
</head>

<body onload="new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_PLAN_VIEW ?>')">
<?php
if(isset($select_day)){
	echo "<form action=\"".$base_url."index.php/plan_view/index/".$select_day."\" name=\"check_test\" id=\"check_test\" method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"select_day\" id=\"select_day\" value=\"".$select_day."\" >\n";
}else{
	echo "<form action=\"".$base_url."index.php/plan_view/index\" name=\"check_test\" id=\"check_test\" method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"select_day\" id=\"select_day\" value=\"\" >\n";
}
if(isset($kakninshbn)){
	echo "<input type=\"hidden\" name=\"kakninshbn\" id=\"kakninshbn\" value=\"".$kakninshbn."\" >\n";
	echo "<input type=\"hidden\" name=\"kakninshnm\" id=\"kakninshnm\" value=\"".$kakninshnm."\" >\n";
}else{
	echo "<input type=\"hidden\" name=\"kakninshbn\" id=\"kakninshbn\" value=\"\" >\n";
	echo "<input type=\"hidden\" name=\"kakninshnm\" id=\"kakninshnm\" value=\"\" >\n";
}
?>
<div id="Main">
<div id="container">
<p><?php echo $target['shinnm'].' '.date('Y年m月d日',strtotime($select_day)).'スケジュール' ?></p>

<?php
// 登録済みデータ出力
if(isset($action_plan)){

	foreach($action_plan as $plan){
		$count = $plan['count'];
		
		// 本部
		if($plan['action_type'] == MY_ACTION_TYPE_HONBU){
			include dirname(__FILE__) . '/../plan_view/new_honbu.php';
			
		// 店舗
		}elseif($plan['action_type'] == MY_ACTION_TYPE_TENPO){
			include dirname(__FILE__) . '/../plan_view/new_tenpo.php';
		
		// 代理店
		}elseif($plan['action_type'] == MY_ACTION_TYPE_DAIRI){
			include dirname(__FILE__) . '/../plan_view/new_dairi.php';
		
		// 業者
		}elseif($plan['action_type'] == MY_ACTION_TYPE_GYOUSYA){
			include dirname(__FILE__) . '/../plan_view/new_gyousya.php';
		
		// 内勤
		}elseif($plan['action_type'] == MY_ACTION_TYPE_OFFICE){
			include dirname(__FILE__) . '/../plan_view/new_office.php';
		}
		
	}
}

?>

<div id="action"></div>

</div>
</div>

</form>
</body>
</html>

<?php
log_message('debug',"===== End plan_view.php =====");
?>
