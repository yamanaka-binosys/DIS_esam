<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");

/*
if(isset($temp_files) && $temp_files != ""){
	$tempfile ="";
	$temp_files = $old_temp_files;
	$filename = end(explode('/',$temp_files));
	$new_file=='1';
}else{
*/
	if(isset($old_temp_files) && $old_temp_files != "" ){
		$tempfile = $old_temp_files;
		$temp_files = $old_temp_files;
		$filename = end(explode('/',$old_temp_files));
	}else{
		if(isset($tempfile) && $tempfile != ""){
		$tempfile = $tempfile;
		$temp_files = $tempfile;
		$filename = end(explode('/',$temp_files));
		}else{
		$temp_files = NULL;
		$filename =NULL;
		}
	}

//}
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>情報メモ</title>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/memo.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/memo.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
<script type="text/javascript">
<!--
$(function(){
  $('form').submit(function (e) {
   return formValidation($(this));  //
  });
});
-->
</script>
<script type="text/javascript">
	function fn_onload() {
	<?php if (isset($errmsg)) { ?>
		if (window.opener) {
			window.opener.document.forms[0].submit();
		}
	<?php } ?>
	}
</script>
</head>
<body  onload="fn_onload();">
<!-- MAIN -->
<div id="Main">
<div id="container">
<div id="errmsg" style="text-align:center; padding: 3px;">
<span style="color:green; font-weight: bold;">
<?php if (isset($errmsg)) { echo $errmsg; } else { echo "&nbsp;"; }  ?>
</span>
</div>
<form action="update_select_type" method="POST"  enctype="multipart/form-data">
<div id="page1">
<table>
<tr>
<td>
<font style="font-size:25px; font-family: sans-serif; margin-right: 10px;">情報メモ</font>
</td>
<td style="vertical-align: bottom;">
<input type="submit" value="変更" />
</td>
</tr>
<tr>
<td><table width="600px" style="border:none;padding:5px;">
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="105px" >
<tr>
<td>
●件名</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:300px;text-align:left;">
<input type="text" name="knnm" size="40"  value="<?php echo $knnm; ?>"  title="件名" class="no_head_required"></td>
<input type="hidden" name="jyohonum" size="40"  value="<?php echo $jyohonum; ?>"></td>
<input type="hidden" name="edbn" size="40"  value="<?php echo $edbn; ?>"></td>
<input type="hidden" name="old_knnm" size="40"  value="<?php echo $knnm; ?>"></td>
<input type="hidden" name="old_temp_files" size="40" value="<?php echo $temp_files; ?>"></td>

</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>&emsp;●入手元</td>
</tr>
<tr>
<td><table width="600px" style="border:none;padding:5px;">
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="150px" >
<tr>
<td>
&emsp;社名</td>
</tr>
</table>
</td>
<td>
<table width="600px" >
<tr>
<td style="width:150px;text-align:left;;">
<input type="text" name="aitesknm" size="20" autocomplete="off" style="width:148px;" value="<?php echo $aitesknm; ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="150px" >
<tr>
<td>
&emsp;役職</td>
</tr>
</table>
</td>
<td>
<table width="600px" >
<tr>
<td style="width:150px;text-align:left;;">
<input type="text" name="yksyoku" size="20" style="width:148px;" value="<?php echo $yksyoku; ?>"></td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="150px" >
<tr>
<td>
&emsp;氏名</td>
</tr>
</table>
</td>
<td>
<table width="600px" >
<tr>
<td style="width:150px;text-align:left;">
<input type="text" name="name" size="20"  style="width:148px;" value="<?php echo $name; ?>"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><?php echo $kbn_table; ?>
</td>
</tr>
<tr>
<td><?php echo $maker_table; ?>
</td>
</tr>
<tr>
<td><table width="600px" style="border:none;padding:1px;">
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="150px" >
<tr>
<td>
●添付ファイル</td>
</tr>
</table>
</td>
<td>
<table width="600px" >
<tr>
<td style="width:170px;text-align:left;;">

<input type='file' name='temp_files' readonly="readonly" value="" /></td><td><a href="<?php if(isset($tempfile) && $tempfile !=""){ echo $tempfile;} ?>"><?php if(isset($filename) && $filename != ""){ echo $filename."</a><br>※添付ファイルが既に登録済みの場合は上書きになります。";} ?></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<td  style="padding-left:2px;;vertical-align:middle;">
<?php echo $date_table; ?>
</td>
<tr>
<td>
</td>
</tr>

<tr>
<td><table width="600px" style="border:none;padding:1px;">
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="150px" >
<tr>
<td>
●情報メモ内容</td>
</tr>
</table>
</td>
<td>
<table width="600px" >
<tr>
<td style="width:300px;text-align:left;">
<?php if(isset($info) && $info!=""){ $jyohoniyo = $info; } ?>
<textarea name='info' rows='6' cols='60' class="no_head_required" title="情報メモ内容" ><?php echo $jyohoniyo; ?></textarea></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
</form>
</div>
</div>
</form>