<?php
$base_url = $this->config->item('base_url');

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link href="<?php echo $base_url; ?>css/select_checker.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>script/select_checker.js"></script>
  <script type="text/javascript" >
  function fn_onload(){
  	<?php if(isset($check) && $check=='1'){ ?>
	window.opener.parent.content.document.getElementById('kakninshbn').value = '<?php echo $kakunin; ?>';
	window.opener.parent.content.document.getElementById('kakninshnm').value = '<?php echo $kakninshnm; ?>';
	window.opener.top.header.document.getElementById('checker').readOnly = false;
	window.opener.top.header.document.getElementById('checker').value = '<?php echo $kakninshnm; ?>';
	window.opener.top.header.document.getElementById('checker').readOnly = true;
	self.close();
	<?php } ?>
	
	
  
  }
  function submit_select_checker_res(){
	document.forms[0].submit();
}
  </script>
</head>

<body <?php if(isset($check) && $check=='1'){ ?> onload="fn_onload();" <?php } ?> style="margin-left: 0px;">
<!-- MAIN -->
<div id="Main">
<div id="container">
<table style="margin-left: 350px;">
	<tr>
		<td >
			<input type="button" name="select" id="select" value="決定" onClick="submit_select_checker_res();"> 
		</td>
		<td>
		</td>
		<td>
			<input type="button" name="close" id="close" value="閉じる" onClick="window.close();">
		</td>
	</tr>
</table>
<br>
<form action="<?php echo $form; ?>" method="POST">
<div id="page1">
<table>
<tr>
<td>
<table>
<tr>
<td><?php echo $c_checker_table; ?></td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table>
<tr>
<td><?php echo $c_busyo_table; ?></td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table>
<tr>
<td><?php echo $c_ka_table; ?></td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table>
<tr>
<td><?php echo $c_group_table; ?></td>
</tr>
</table>
</td>
</tr>
</table>

</div>
</form>
</div>
</div>

</body>
</html>
