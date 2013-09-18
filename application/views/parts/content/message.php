<?php
log_message('debug',"===== Start division.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
if(isset($errmsg) && $errmsg !=""){
	$errmsg = $errmsg;
}else{
	$errmsg = "";
}

?>

<?php echo doctype('html4-frame')."\n"; ?>
<html>
<head>
<?php echo meta($meta); ?>
<?php echo "<title>{$title}</title>\n"; // TITLE宣言 ?>
<link href="<?php echo $base_url; ?>css/message.css" rel="stylesheet"
	type="text/css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/message.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script>
  function fn_onload(){
    
    //select_checker/index
    if (parent.content.document.referrer &&
      parent.content.document.referrer.slice(-20) == 'select_checker/index') {
      var head_url = "<?php echo $base_url ?>".concat("index.php/header/index/message/");
      parent.header.location.href = head_url;
     window.parent.document.getElementById('baseset').rows = "117, *";
    } else {
      var errmsg = top.header.document.getElementById("errmsg");
      <?php if (!empty($infomsg)) {  ?>
    	errmsg.setAttribute('class', 'msg-info');
      errmsg.setAttribute('className', 'msg-info'); // for ie6
      errmsg.innerText = '<?php echo $infomsg; ?>';
			errmsg.textContent = '<?php echo $infomsg; ?>';
      <?php } else { ?>
      errmsg.setAttribute('class', 'msg-error');
      errmsg.setAttribute('className', 'msg-error'); // for ie6
      errmsg.innerText = '<?php echo $errmsg; ?>';
			errmsg.textContent = '<?php echo $errmsg; ?>';
      <?php } ?>
      

    }
    


  }
  </script>
 
</head>

<body onload="fn_onload();">
	<?php
	echo "<form action=\"".$base_url."index.php/message/add_select_type\" name=\"message_form\" id=\"message_form\" method=\"POST\" enctype=\"multipart/form-data\">\n";
	if(isset($kakninshbn)){
		echo "<input type=\"hidden\" name=\"kakninshbn\" id=\"kakninshbn\" value=\"".$kakninshbn."\" >\n";
		echo "<input type=\"hidden\" name=\"kakninshnm\" id=\"kakninshnm\" value=\"".$kakninshnm."\" >\n";
	}else{
		echo "<input type=\"hidden\" name=\"kakninshbn\" id=\"kakninshbn\" value=\"\" >\n";
		echo "<input type=\"hidden\" name=\"kakninshnm\" id=\"kakninshnm\" value=\"\" >\n";
	}
	?>
	<!-- MAIN -->
	<div id="">
		<div id="container">
			<br>
			<?php echo '<form action="message/add_select_type" method="POST" name="message_form" id="message_form"  enctype="multipart/form-data">'."\n"; ?>
			<div id="page1">
				<table>
					<tr>
						<td><?php echo $allmessage_table; ?></td>
						<td style="padding-top: 10px; vertical-align: top;"><?php echo $allmessage_check; ?>
						</td>
					</tr>
					<tr>
						<td style="padding-left: 10px;"><?php echo $file_table; ?></td>
					</tr>
					<tr>
						<td style="padding-left: 10px;"><table><tr><td style="width:90px;">重要</td><td>
						<input type="checkbox" name="is_bold" value="on" <?php if (isset($is_bold) && $is_bold === 't') { echo 'checked="checked"'; } ?> />
						</td></tr></table></td>
					</tr>
				</table>
			</div>
			</form>
		</div>
	</div>

</body>
</html>

