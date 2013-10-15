<?php
log_message('debug',"===== Start header_result.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
$header_gif_name = $base_url.$gif_name;
$header_day = $day."(".$week_day.")";
$header_bu = $bu_name;
$header_ka = $ka_name;
$header_name = $shinnm;

log_message('debug',"\$base_url = $base_url");
log_message('debug',"\$meta = $meta");
log_message('debug',"\$header_gif_name = $header_gif_name");
log_message('debug',"\$header_day = $header_day");
log_message('debug',"\$header_bu = $header_bu");
log_message('debug',"\$header_ka = $header_ka");
log_message('debug',"\$header_name = $header_name");
?>

<?php echo doctype('html4-frame'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/header.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/header.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js" ></script>
</head>
<body onload="init_result()">
<div id="Header">
<form id="result_head" name="result_head">
<table style="width:1024px;">
<tr>
<td style="padding: 0 0 0 220px" colspan="3">
<img src="<?php echo $header_gif_name ?>" alt="">
</td>
<td colspan="3" align="right" valign="top" style="padding: 0px;">
<?php
if(!is_null($header_day)){
	echo $header_day."<br>\n";
}
//if(!is_null($header_bu)){
//	echo $header_bu."<br>\n";
//}
if(!is_null($header_ka)){
	echo $header_ka."<br>\n";
}
if(!is_null($header_name)){
	echo $header_name."<br>\n";
}
?>
</td>
</tr>
<tr>
<td colspan="6">
<div id="errmsg" class="<?php if(isset($errclass) && $errclass!=""){ echo $errclass;  } ?>">
	<?php if( ! is_null($errmsg)) {
		echo $errmsg;
	}
	?>
</div>
</td>
<td>
<input type="button" style="width: 50px;" class="input" name="back_calendar" id="back_calendar" size="1" value="戻る" onclick="submit_back_result_calendar('<?php echo $base_url; ?>');">    
</td>
</tr>
<!--
</table>
php //if(  == date("Ymd")){ } ?>
<table style="width: 1024px;">
-->
<tr>
<td align="left">確認者 <input type="text" style="width: 120px" class="input" name="checker" id="checker" size="20" maxlength="256" value="" readonly="readonly">
<input type="button" style="width: 60px" name="select_checker" id="select_checker" value="選択" onclick="submit_result_select_checker('<?php echo $base_url; ?>')">
</td>
<td align="left">日付
<?php echo $head_year; ?>年
<?php echo $head_month; ?>月
<?php echo $head_day; ?>日
<label>（</label>
<input type="text" style="width: 20px;border: none;background-color: #FFFFBB;text-align: center;" class="input" name="weekday" id="weekday" size="1" value="" readonly="readonly">
<label>）</label>
<input type="button" style="width: 50px;" class="input" name="before_day" id="before_day" size="1" value="<<前日" onclick="before_result_day('<?php echo $base_url; ?>')">
<input type="button" style="width: 50px;" class="input" name="next_day" id="next_day" size="1" value="翌日>>" onclick="next_result_day('<?php echo $base_url; ?>') ">
<input type="button" style="width: 50px;" class="input" name="copy_day" id="copy_day" size="1" value="コピー" onclick="submit_result_copy()">
</td>
<td align="right"><input type="button" style="width: 60px;" class="setbtn" name="get_plan" id="get_plan" value="予定取得" onclick="submit_get_plan_data()"></td>
<td align="right">一時保存<input type="checkbox" id="check_hold" name="check_hold"></td>
<td align="right"><input type="button" style="width: 40px;" class="setbtn" name="set" id="set" value="登録" onclick="submit_result_register()"></td>
<td align="right"><input type="button" style="width: 60px;" name="submit_day" id="submit_day" value="入力確認" onclick="submit_result_check('<?php echo $base_url; ?>')"></td>
<td align="right"><input type="button" style="width: 40px;" class="setbtn" name="set" id="set" value="削除" onclick="submit_result_delete()"></td>
</tr>
</table>
</form>

</div>


</body>
</html>
