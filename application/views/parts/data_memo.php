<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
?>
<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<title>情報メモ</title>
<link href="<?php echo $base_url; ?>css/user.css" rel="stylesheet" type="text/css" />
 <script>
  function fn_onload(){
   top.header.document.getElementById("errmsg").innerText = '<?php echo $errmsg; ?>';
	 top.header.document.getElementById("errmsg").textContent = '<?php echo $errmsg; ?>';
  }
  </script>
</head>
<body onload="fn_onload();">

<!-- MAIN -->
<div id="Main">
<div id="container">
<br>
<form action="<?php //echo $main_form; ?>" method="POST">
<?php // echo $tab; ?>
<div id="page1">
<table>
<tr>
<td><?php echo $k_name_table; ?></td>
</tr>
<tr>
<td style="border:none;padding:1px;">●入手元</td>
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

