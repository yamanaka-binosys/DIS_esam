<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
//$errmsg = (isset($errmsg) && $errmsg!="") ? $errmsg : "";


?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>情報メモ</title>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/memo.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/memo.js"></script>
 <script>
  function fn_onload(){
      top.header.document.getElementById("errmsg").innerText = '<?php echo $errmsg; ?>';
			top.header.document.getElementById("errmsg").textContent = '<?php echo $errmsg; ?>';
      top.header.document.getElementById("errmsg").className = '<?php echo $msg_type; ?>';
  }
  </script>
<script type="text/javascript">
<!--
$(function(){
  $('form').submit(function (e) {
    return formValidation($(this));  //
  });
});
-->
</script>

</head>
<body onload="fn_onload();">
<!-- MAIN -->
<div id="Main">
<div id="container">

<form action="<?php echo $main_form; ?>" method="POST"  enctype="multipart/form-data">

<?php // echo $tab; ?>
<div id="page1">
<table>
<tr>
<td style="font-size:25px; font-family: sans-serif;"><?php echo $memo_type; ?></td>
</tr>
<tr>
<td><?php echo $k_name_table; ?></td>
</tr>
<tr>
<td style="border:none;padding-left:12px">●入手元</td>
</tr>
<tr>
<td><?php echo $office_table; ?></td>
</tr>
<tr>
<td><?php echo $kbn_table; ?></td>
</tr>
<tr>
<td><?php echo $maker_table; ?></td>
</tr>
<tr>
<td><?php echo $file_table; ?></td>
</tr>
<tr>
<td><?php echo $date_table; ?></td>
</tr>
<tr>
<td><?php echo $info_table; ?></td>
</tr>
</table>
</div>
</form>
</div>
</div>
<?php
	// formタグの挿入
	if(! is_null($form))
	{
		echo "</form>";
	}
?>

