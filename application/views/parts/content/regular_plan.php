<?php
log_message('debug',"===== Start regular_plan.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
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
</style>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-i18n.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/regular_plan.js"></script>
</head>

<script type="text/javascript">
<!--
function body_onload(){
    <?php 
    if ($error_date != null) {
        echo "alert(\"" . date("Y", $error_date) . "年" . date("m", $error_date) . "月" . date("d", $error_date) . "日 " . date("H", $error_date) . "時" . date("i", $error_date) . "分に登録済みのデータがあるため登録できません" . "\");\n";
    }
    if ($error_msg != null) {
        echo "alert(\"" . $error_msg . "\");\n";
    }
    ?>
    new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_REGULAR_PLAN ?>','<?php echo $tmp_flg; ?>');
}
// -->
</script>



<!--<body onload="new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_REGULAR_PLAN ?>','<?php echo $tmp_flg; ?>')">-->
<body onload="body_onload();">
<?php

if($tmp_flg){
	$tmp_flg = FALSE;
}
if(isset($select_day)){
	echo "<form action=\"".$base_url."index.php/regular_plan/index/".$select_day."\" method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"select_day\" id=\"select_day\" value=\"".$select_day."\" >\n";
}else{
	echo "<form action=\"".$base_url."index.php/regular_plan/index\" method=\"POST\">\n";
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
<?php
// 登録済みデータ出力
if(isset($action_plan)){
	//var_dump($action_plan['count']);
	foreach($action_plan as $plan){
		$count = $plan['count'];

		// 本部
		if($plan['action_type'] == MY_ACTION_TYPE_HONBU){
			include dirname(__FILE__) . '/../regular_plan/new_honbu.php';

		// 店舗
		}elseif($plan['action_type'] == MY_ACTION_TYPE_TENPO){
			include dirname(__FILE__) . '/../regular_plan/new_tenpo.php';

		// 代理店
		}elseif($plan['action_type'] == MY_ACTION_TYPE_DAIRI){
			include dirname(__FILE__) . '/../regular_plan/new_dairi.php';

		// 業者
		}elseif($plan['action_type'] == MY_ACTION_TYPE_GYOUSYA){
			include dirname(__FILE__) . '/../regular_plan/new_gyousya.php';

		// 内勤
		}elseif($plan['action_type'] == MY_ACTION_TYPE_OFFICE){
			include dirname(__FILE__) . '/../regular_plan/new_office.php';
		}

	}
}

?>
<div id="action"></div>

</form>
</div>
</div>
</body>
</html>

<?php
log_message('debug',"===== End regular_plan.php =====");
?>
