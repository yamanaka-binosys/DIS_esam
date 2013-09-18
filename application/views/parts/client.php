<!-- MAIN -->
<div id="Main">
<div id="container">
<br>
<form action="<?php //echo $main_form; ?>" method="POST">
<?php echo $tab; ?>
<div id="page1">
<table>
<tr>
<td><?php echo $list_table; ?></td>
</tr>
</table>
<p style="text-align: center;">正式相手先コード、もしくは正式相手相手先名を入力して、更新する行にチェックをつけ更新ボタンを押してください。</p>
<p style="text-align: center; color: #FF0000;">この画面は、ハードコピーを取って保管してください。</p>
</div>
</form>
</div>
</div>
<?php
	// formタグの挿入
	if(! is_null($form))
	{
		echo "</form>";
	}
?>

