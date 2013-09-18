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
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/result_input_check.css">
<link rel="stylesheet" type="text/css" HREF="<?php echo base_url('css/result_input_check_print.css') ?>" media="print"> 
<script type="text/javascript" src="<?php echo $base_url; ?>script/result_input_check.js"></script>
<script type="text/JavaScript">

</script>
</head>

<div id="Header">
	<table>
	<tr>
	<td class="center">
	<img src="<?php echo $base_url; ?>images/result_check.gif" alt=""/><br/>
	<a class="print-link" href="#" onclick="window.print();return false;">
	印刷
	</a>
	</td>
	</tr>
	</table>
</div>

<body>

<div id="Main">
<div id="container">
<br>
<div id="check">
<table>
<tr>
<td class="content-title">【実績入力内容】</td>
</tr>
</table>

<?php echo $check_data; ?>

</body>
</html>
