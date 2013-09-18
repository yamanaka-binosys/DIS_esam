<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
?>
<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/todo.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/todo.js"></script>
</head>
<body onload="check_todo('<?php echo $base_url; ?>','<?php echo SHOW_TODO; ?>');">
<!-- MAIN -->
<div id="Main">
<div id="container">

<table style="border-collapse: collapse;">
<tr>
<th style="border:1px #000000 solid; padding:5px; text-align: center; background-color: #FFFF99;">期限日</th>
<th style="border:1px #000000 solid; padding:5px; text-align: center; background-color: #FFFF99;">重要度</th>
<th style="border:1px #000000 solid; padding:5px; text-align: center; background-color: #FFFF99;">内容</th>
</tr>
<tr>
<td style="border:1px #000000 solid; padding:5px; background-color: #FFFFFF;"><?php echo $year; ?>年<?php echo $month; ?>月<?php echo $day; ?>日</td>
<td style="border:1px #000000 solid; padding:5px; text-align: center; background-color: #FFFFFF;"><?php echo $impkbn; ?></td>
<td style="border:1px #000000 solid; padding:5px; background-color: #FFFFFF;"><?php echo $todo; ?></td>
</tr>
</table>

</div>
</div>
</body>
</html>