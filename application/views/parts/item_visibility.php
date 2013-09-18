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
<link href="<?php echo $base_url; ?>css/item_visibility.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php
if(isset($form_url))
{
	if ( isset($form_name) && $form_name ) {
		echo "<form action=\"" . $form_url. "\" method=\"POST\" name=\"" . $form_name. "\">\n";
	} else {
		echo "<form action=\"" . $form_url . "\" method=\"POST\">\n";
	}
}
?>

<!-- MAIN -->
<div id="">
	<div id="container">
		<br />
			<div id="item_area">
				<span>画面名・帳票名</span><?php echo $item_box; ?><input type="submit" name="display" value="表示" class="btndisp"/>
			</div>
			<div id="list_area">
				<?php echo $list_table; ?>
			</div>
	</div>
</div>
<?php
	// formタグの挿入
	if(! is_null($form))
	{
		echo "</form>";
	}
?>

</body>
</html>