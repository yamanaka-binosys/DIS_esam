<?php
log_message('debug',"===== Start result_view.php =====");
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
<link href="<?php echo $base_url; ?>css/result.css" rel="stylesheet" type="text/css">
<link href="<?php echo $base_url; ?>css/jquery-ui-1.8.18.custom.css" rel="stylesheet" media="screen">
<style>
	.ui-datepicker {font-size: 85%;}
	input[type=text] {width: 6em;}
</style>
<script type="text/javascript" src="<?php echo $base_url; ?>script/result_view.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-i18n.js"></script>
</head>
<?php if($judg_flg === "admin"){ ?>
	<body onload="new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_RESULT_VIEW_ADMIN ?>')">
<?php }else{ ?>
	<body onload="new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_RESULT_VIEW_GENERAL ?>')">
<?php } ?>
<form action="<?php echo $base_url; ?>index.php/result_view/index/<?php echo $select_day; ?>/<?php echo $judg_flg; ?>" method="POST">
<input type="hidden" name="select_day" id="select_day" value="<?php echo $select_day; ?>">
<input type="hidden" name="target_shbn" id="target_shbn" value="<?php echo $target_shbn; ?>">
<input type="hidden" name="kakninshbn" id="kakninshbn" value="<?php echo $kakninshbn; ?>">
<input type="hidden" name="kakninshnm" id="kakninshnm" value="<?php echo $kakninshnm; ?>">
<div id="Main">
<div id="container">
<div id="mitumori_file_div">
<p><?php echo $target['shinnm'].' '.date('Y年m月d日',strtotime($select_day)).'実績' ?></p>
見積もりファイル:
<?php 

 if(isset($mitumori_file['filenum']) && $mitumori_file['filenum'] != ""){
?>

	<a href="<?php echo $base_url; ?>files/result/<?php echo $mitumori_file['ymd'].$mitumori_file['shbn']; ?>/<?php echo $mitumori_file['uploadtime']; ?>/<?php echo $mitumori_file['tempfile']; ?>"><?php echo $mitumori_file['tempfile']; ?></a>

<?php
} else { echo '無し'; }
?>
</div>
<?php
// 登録済みデータ出力
if(isset($action_result)){
	foreach($action_result as $result){
		$count = $result['count'];
				
		// 本部
		if($result['action_type'] == MY_ACTION_TYPE_HONBU){
			include dirname(__FILE__) . '/../result_view/new_honbu.php';
			
		// 店舗
		}elseif($result['action_type'] == MY_ACTION_TYPE_TENPO){
			include dirname(__FILE__) . '/../result_view/new_tenpo.php';
		
		// 代理店
		}elseif($result['action_type'] == MY_ACTION_TYPE_DAIRI){
			include dirname(__FILE__) . '/../result_view/new_dairi.php';
		
		// 業者
		}elseif($result['action_type'] == MY_ACTION_TYPE_GYOUSYA){
			include dirname(__FILE__) . '/../result_view/new_gyousya.php';
		
		// 内勤
		}elseif($result['action_type'] == MY_ACTION_TYPE_OFFICE){
			include dirname(__FILE__) . '/../result_view/new_office.php';
		}
	}
}

?>

<div id="action">
	
</div>

</div>
</div>
<input type="hidden" name="action_type_00" id="action_type_00" value="dummy">
</form>
</body>
</html>

<?php
log_message('debug',"===== End result.php =====");
?>
