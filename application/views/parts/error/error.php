<?php $this->load->helper('html'); ?>
<?php echo doctype('html4-strict') ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<style type="text/css">
	body { text-align: center; }
	h1 { font-size: 15px; text-align: center;  width: auto; margin-top: 30px; color: red;}
	.errcode { color: black;}
</style>
</head>
<body>
	
	<h1>エラーが発生しました。</h1>
	<?php if (isset($errcode)) { echo "<span class=\"errcode\">エラーコード:&nbsp;<b>".$errcode."</b></span>"; } ?>

</body>