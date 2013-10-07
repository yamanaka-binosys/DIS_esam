<?php
log_message('debug',"===== Start plan.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
if(isset($count)){
log_message('debug',"\$count = $count");
}
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
	body { background-color: #D1FBFF; }
</style>
<script type="text/javascript">
 /*@cc_on _d=document;eval('var document=_d')@*/
</script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/plan.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-i18n.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
<script>
  function fn_onload(){
      var errmsg = top.header.document.getElementById("errmsg");
      <?php if (!empty($infomsg)) {  ?>
    	errmsg.setAttribute('class', 'msg-info');
      errmsg.setAttribute('className', 'msg-info'); // for ie6
      errmsg.innerText = '<?php echo $infomsg; ?>';
			errmsg.textContent = '<?php echo $infomsg; ?>'; // for not ie
        <?php if($infomsg=="開始時刻と終了時刻の時間帯で重複しているものがあります。"){
            echo 'alert("開始時刻と終了時刻の時間帯で重複しているものがあります。");'; 
        }  ?>
      <?php } else { ?>
      errmsg.setAttribute('class', 'msg-error');
      errmsg.setAttribute('className', 'msg-error'); // for ie6
      errmsg.innerText = '<?php echo $errmsg; ?>';
			errmsg.textContent = '<?php echo $errmsg; ?>'; // for not ie
        <?php if($infomsg=="開始時刻と終了時刻の時間帯で重複しているものがあります。"){
            echo 'alert("開始時刻と終了時刻の時間帯で重複しているものがあります。");'; 
        } ?>
      <?php } ?>
  }
</script>
</head>

<body <?php if(isset($action_flg) && $action_flg =="1"){ ?> onload="action_submit_view('<?php echo $base_url; ?>','<?php echo SHOW_PLAN_A ?>')" <?php } else{ ?> onload="new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_PLAN_A ?>')" <?php } ?> >
<?php
if(isset($select_day)){
//	echo "<form action=\"".$base_url."index.php/plan/index/".$select_day."\" method=\"POST\">\n";
	echo "<form action=\"".$base_url."index.php/plan/index/".$select_day."\" name=\"check_test\" id=\"check_test\" method=\"POST\">\n";
	echo "<input type=\"hidden\" name=\"select_day\" id=\"select_day\" value=\"".$select_day."\" >\n";
}else{
	echo "<form action=\"".$base_url."index.php/plan/index\" name=\"check_test\" id=\"check_test\" method=\"POST\">\n";
//	echo "<form action=\"index\" method=\"POST\">\n";
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

	foreach($action_plan as $plan){
		$count = $plan['count'];

		// 本部
		if($plan['action_type'] == MY_ACTION_TYPE_HONBU){
			include dirname(__FILE__) . '/../plan/new_honbu.php';

		// 店舗
		}elseif($plan['action_type'] == MY_ACTION_TYPE_TENPO){
			include dirname(__FILE__) . '/../plan/new_tenpo.php';

		// 代理店
		}elseif($plan['action_type'] == MY_ACTION_TYPE_DAIRI){
			include dirname(__FILE__) . '/../plan/new_dairi.php';

		// 業者
		}elseif($plan['action_type'] == MY_ACTION_TYPE_GYOUSYA){
			include dirname(__FILE__) . '/../plan/new_gyousya.php';

		// 内勤
		}elseif($plan['action_type'] == MY_ACTION_TYPE_OFFICE){
			include dirname(__FILE__) . '/../plan/new_office.php';
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
log_message('debug',"===== End plan.php =====");
?>
