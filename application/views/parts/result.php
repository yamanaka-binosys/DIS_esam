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
	if( ! is_null($action_result))
	{
		echo $action_result;
	}
?>
</td>
</tr>
<tr>
<td>
<!-- New活動区分配置 -->
<?php
	if( ! is_null($action_new_result))
	{
		echo $action_new_result;
	}
?>
</td>
</tr>
</table>
</form>
<br>
</div>
</div>
