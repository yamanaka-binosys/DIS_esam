<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/checker_search_unit.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/checker_search_unit.js"></script>
<script type="text/JavaScript">
function fn_onload(reload_flg){
	if(reload_flg){
		// 親画面リロード
	    window.opener.location.href = window.opener.location.href;
		// ウインドウクローズ
	    self.close();
	}
}

function  setSubmit(){
	// サブミット
	var newEle = document.createElement("INPUT");
	newEle.type="hidden";
	newEle.name="set";
	document.forms[0].appendChild(newEle); 
	document.forms[0].submit();

}
</script>
</head>
<body onload="fn_onload(<?php echo isset($reload_flg) ? $reload_flg: ''; ?>)">
<div id="Header">
<table >
<tr>
<td class="title2" colspan="2">
<img src="<?php echo $base_url; ?>images/search_unit.gif" alt=""/>
</td>
<td rowspan="2" class="right2">
<br><br><br></td>
</tr>
<tr>
<td class="errmsg">
<?php echo $errmsg; ?>
</td>
</tr>
</table>
</div>

<!-- MAIN -->
<div id="Main" style="padding-top:0;border-left:none;margin:0;">
<div id="container">
<form action="<?php echo $form; ?>" method="POST">
<input type="button" id="set_btn" name="set_btn" onclick="setSubmit()" style="margin-left:460px;" value=" 戻る ">
<div id="change_position" style="display:none"></div>
<table>
<tr>
<td>
<div id="busyo_table">
<?php echo $search_result_busyo_table; ?>
</div>
</td>
</tr>
<tr>
<td>
<?php echo $search_b_name_list; ?>
</td>
</tr>
</table>
</form>
</div>
</div>


</body>
</html>
