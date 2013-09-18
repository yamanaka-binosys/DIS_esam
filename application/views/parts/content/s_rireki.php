<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
$msg_type = 'msg-error';
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/s_rireki.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>script/s_rireki.js"></script>
  <script>
  function fn_onload(){
	  top.header.document.getElementById("errmsg").innerHTML = '<?php echo $errmsg; ?>';
      top.header.document.getElementById("errmsg").className = '<?php echo $msg_type; ?>';	
  }
  </script>

</head>

<body onload="fn_onload();">
<!-- MAIN -->
<form action="<?php echo $form_url; ?>" method="POST">
<div id="Main">
	<div id="container">
	<!-- メインレイアウト -->
	<table style="width: 800px">
		<tr>
			<td>
				<?php  echo $client_box; ?> 
			</td
		</tr>
		
		<tr>
			<td></td>
		</tr>
		<?php if($search_flg){ ?>
		<tr>
			<td><?php echo $result_list; ?></td>
		</tr>
		<?php } ?>
	</table>
	</div>
</div>
</form>
</body>
</html>
