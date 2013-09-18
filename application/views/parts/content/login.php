<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
header("Content-type: text/html; charset=utf-8");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/login.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/login.js"></script>
<?php if (isset($open_window) && $open_window) { ?>
<script type="text/javascript">
<!--
win=window.open('<?php echo base_url('index.php/system'); ?>',
	"esammain",
	"toolbar=0,location=0,menubar=0,directories=0,resizable=1,left=0,top=0,height=740,width=1013");
  window.blur();
  win.focus();
-->
</script>
<?php } ?>
</head>
<body>
<div id="Header">
<table style="width:1024px;">
<tr style="height:25px;">
</tr>
<tr>
<td class="title">
<img src="<?php echo $base_url; ?>images/login.gif" alt="">
</td>
<td rowspan="2" class="right">
</td>
</tr>
<tr>
<td class="errmsg" id="errmsg">
<?php
if(!is_null($errmsg)){
	echo $errmsg;
}
?>
</td>
</tr>
</table>
</div>
<table align="center" style="padding-top: 40px;">
<tr style="height: 90px;">
<td>
<label style="font-size: 40px; font-weight: 700; color: #3300FF;font-family: monospace;">Elleair-Sales staff Action Management</label>
</td>
</tr>
</table>
<form action="check" id="login" method="POST">
<div id="Box">
<table style="margin:30px auto;">
<tr>
<td style="width: 120px;">社員番号</td>
<td style="width: 160px;">
<?php
if($shbn === ''){
	echo "<input type=\"text\" name=\"shbn\" id=\"shbn\" value=\"\" size=\"5\" maxlength=\"5\" style=\"width: 38px;ime-mode: disabled;\">\n";
}else{
	echo "<input type=\"text\" name=\"shbn\" id=\"shbn\" value=".$shbn." size=\"5\" maxlength=\"5\" style=\"width: 38px;ime-mode: disabled;\">\n";
}
?>
</tr>
<tr>
<td style="width: 120px;">パスワード</td>
<td style="width: 160px;">
<?php if($pw === ''){
	echo "<input type=\"password\" name=\"pw\" id=\"pw\" value=\"\" size=\"10\" maxlength=\"10\" style=\"width: 83px;ime-mode: disabled;\">\n";
}else{
	echo "<input type=\"password\" name=\"pw\" id=\"pw\" value=".$pw." size=\"10\" maxlength=\"10\" style=\"width: 83px;ime-mode: disabled;\">\n";
} ?>
</td>
<td>
<input id="change_btn" type="button" name="up_pw" style="width: 85px;" value="変更" onClick="pass_change('change_pass','<?php echo $base_url ?>');">
</td>
</tr>
</table>
<div id="login" style="display:block; margin-left: 20px;">
<table>
<tr>
<td colspan="2" style="text-align: center;">
<input type="submit" name="ipass" id="ipass" value=" ログイン ">
</td>
</tr>
</table>
</div>
<?php if(isset($change_pass) && $change_pass === TRUE){  
echo "<div id=\"change_pass\" style=\"display:block\">";
}else{
echo "<div id=\"change_pass\" style=\"display:none\">";
}  ?>
<table style="margin:30px auto;">
<tr>
<td style="width: 120px;">新しいパスワード</td>
<td style="width: 250px;">
<?php if(isset($new_pw1) && $new_pw1 != ''){
	echo "<input type=\"password\" name=\"new_pw1\" id=\"pw\" value=".$new_pw1." size=\"10\" maxlength=\"10\" style=\"width: 83px;ime-mode: disabled;\">\n";
}else{
	echo "<input type=\"password\" name=\"new_pw1\" id=\"pw\" value=\"\" size=\"10\" maxlength=\"10\" style=\"width: 83px;ime-mode: disabled;\">\n";
} ?>
</td>
</tr>
<tr>
<td style="width: 120px;">確認パスワード</td>
<td style="width: 250px;">
<?php if(isset($new_pw2) && $new_pw2 != ''){
	echo "<input type=\"password\" name=\"new_pw2\" id=\"pw\" value=".$new_pw2." size=\"10\" maxlength=\"10\" style=\"width: 83px;ime-mode: disabled;\">\n";
}else{
	echo "<input type=\"password\" name=\"new_pw2\" id=\"pw\" value=\"\" size=\"10\" maxlength=\"10\" style=\"width: 83px;ime-mode: disabled;\">\n";
} ?>
</td>
</tr>
</table>
<table>
<tr>
<td colspan="2" style="text-align: center;">
<input type="submit" name="pw_update" value="パスワード変更 " style=" margin-left: 20px;";>
</td>
</tr>
<table>
</div>

</div>
</form>
</body>
</html>
