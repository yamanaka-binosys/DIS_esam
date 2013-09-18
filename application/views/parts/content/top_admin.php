<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
?>
<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/top.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>script/top.js"></script>
</head>

<!-- MAIN -->
<body>
<form action="update" method="POST">
<div id="Main">
<div id="container">
<table style="width: 825px">
<tr>
<td colspan="3">
<!-- スケジュール配置 -->
<?php echo $calendar ?>
</td>
</tr>
<tr>
<td>
<!-- ToDo配置 -->
<?php echo $todo ?>
</td>
<td>
<!-- 情報メモ配置 -->
<?php echo $memo ?>
</td>
<td>
<!-- リンクバナー配置 -->
<?php echo $banner_link ?>
</td>
</tr>
<tr>
<td>
<!-- ユニット長日報閲覧状況配置 -->
<?php echo $read_report ?>
</td>
<td>
<!-- 受取日報配置 -->
<?php echo $result ?>
</td>
<td rowspan="3">
<!-- 部下スケジュール配置 -->
<?php echo $schedule ?>
</td>
</tr>
<tr>
<td colspan="2">
<!-- Infomation配置 -->
<?php echo $info ?>
</td>
</tr>
</table>
<br>
</div>
</div>
</form>

</body>
</html>