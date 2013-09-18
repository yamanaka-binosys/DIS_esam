<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link href="http://localhost/sample/css/top.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://localhost/sample/script/top.js"></script>
</head>

<!-- MAIN -->
<body>
<form action="update" method="POST">
<div id="Main">
<br>
<div id="container">
<table style="width: 825px">
<tr>
<td colspan="3">
<!-- スケジュール配置 -->
<?php echo $calendar ?>
</td>
</tr>
<tr style="height:20px"><th> </th></tr>
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
<tr style="height:20px"><th> </th></tr>
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
<tr style="height:20px"><th> </th></tr>
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