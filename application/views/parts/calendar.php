<!-- MAIN -->
<div id="Main">
<br>
<div id="container">
<form action="select" method="POST">
<?php
	echo "<input type=\"hidden\" name=\"select_calendar\" value=\"";
	echo $select_calendar;
	echo "\">\n"
?>
<table style="width: 825px">
<tr>
<td>
<!-- スケジュール配置 -->
<?php echo $month ?>
</td>
</tr>
<tr>
<td>
</table>
<table>
<tr>
<td>
<!-- スケジュール配置 -->
<?php echo $calendar ?>
</td>
</tr>
</table>
</form>
<br>
</div>
</div>
