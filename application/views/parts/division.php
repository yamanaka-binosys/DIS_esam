<?php
log_message('debug',"===== Start division.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-frame')."\n"; ?>
<html>
<head>
<?php echo meta($meta); ?>
<?php echo "<title>{$title}</title>\n"; // TITLE宣言 ?>
<link href="<?php echo $base_url; ?>css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/header.js"></script>
</head>

<body>
<?php
if(isset($form))
{
	if ( isset($form_name) && $form_name ) {
		echo "<form action=\"" . $form. "\" method=\"POST\" name=\"" . $form_name. "\">\n";
	} else {
		echo "<form action=\"" . $form . "\" method=\"POST\">\n";
	}
	echo '<input type="hidden" name="set" value="">';
}
?>

<!-- MAIN -->
<div id="">
<div id="container">
<!-- タブ配置 -->
<?php //echo $tab; ?>
<table style="width: 825px">
<tr>
<td>
<!-- 活動区分配置 -->
<?php
	if( ! empty($contents))
	{
		echo $contents;
	}
?>
</td>
</tr>
</table>
</form>
<br>
</div>
</div>

</body>
</html>
