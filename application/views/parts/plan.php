<!-- MAIN -->
<div id="Main">
<br>
<div id="container">
<!-- タブ配置 -->
<?php echo $tab; ?>
<table style="width: 825px">
<tr>
<td>
<!-- 活動区分配置 -->
<?php
	if( ! is_null($action_plan))
	{
		echo $action_plan;
	}
?>
</td>
</tr>
<tr>
<td>
<!-- New活動区分配置 -->
<?php
	if( ! is_null($action_new_plan))
	{
		echo $action_new_plan;
	}
?>
</td>
</tr>
</table>
</form>
<br>
</div>
</div>
