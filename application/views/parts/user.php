<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
?>
<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/user.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
 <script>
  function fn_onload(){
   top.header.document.getElementById("errmsg").innerText = '<?php echo $errmsg; ?>';
		top.header.document.getElementById("errmsg").textContent = '<?php echo $errmsg; ?>';
   top.header.document.getElementById("errmsg").className = '<?php echo $msg_type; ?>';
  }
  </script>
</head>
<body onload="fn_onload();">
<!-- MAIN -->
<div id="Main">
<div id="container">
<br>
<form action="<?php echo $main_form; ?>" method="POST">
<div id="page1">
<table>
<tr>
<td><?php echo $shbn_table; ?></td>
</tr>
<tr>
<td><?php echo $name_table; ?></td>
</tr>
<tr>
<td><?php echo $busyo_table; ?></td>
</tr>
<tr>
<td><?php echo $kbn_table; ?></td>
</tr>
<tr>
<td><?php echo $pass_table; ?></td>
</tr>
</table>
<input type="hidden" name="set" id="set" value="true">
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

