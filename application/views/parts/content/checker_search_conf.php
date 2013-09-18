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
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/checker_search_conf.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/checker_search_conf.js"></script>
<script type="text/JavaScript">
function fn_onload(reload_flg){

}

function  setSubmit(){
	// サブミット
	document.forms[1].submit();

}
</script>
</head>
<body onload="fn_onload(<?php echo isset($reload_flg) ? $reload_flg: ''; ?>)" >

<div id="Header">
<table >
<tr>
<td class="title2" colspan="2">
<img src="<?php echo $base_url; ?>images/search_conf.gif" style="text-align:center;margin-left:80px;" alt=""/>
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
<div id="container" style="text-align:center">
<br>
<form action="<?php echo $base_url; ?>index.php/checker_search_conf/index" name="set" method="POST">

<input type="button" id="set_btn" name="set_btn" onclick="setSubmit()"  style="margin-left:335px;" value=" 決定 ">

<input type="button" value="戻る" align="center" style="width:50px;"  style="margin-left:10px;" onclick="history.back();">	
<input type="button" value="閉じる" align="center" style="width:50px;" style="margin-left:10px;" onclick="window.close();">	
<br>

<div id="page1">
<div id="change_position" style="display:none"></div>
<table>
<tr>
<td>
<div id="busyo_table">
<?php echo $search_result_busyo_table; ?>
</div>
</td>
<td><?php echo $search_result_name_table; ?></td>
</tr>
</table>
</form>

<form action="<?php echo $base_url; ?>index.php/select_checker/index" name="set" method="POST">
<input type="hidden" value="conf" id="checker" name="checker">
<table>
<tr>
<td><?php echo $search_name_list; ?></td>
</tr>
</table>
</div>
</form>
</div>
</div>

</body>
</html>
