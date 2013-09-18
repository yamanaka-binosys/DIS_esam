<!-- MAIN -->
<div id="Main">
<div id="container">
<br>
<form action="<?php echo $main_form; ?>" method="POST">
<div id="page1">
<table>
<tr>
<td><?php echo $allmessage_table; ?></td><td style="padding-top:10px;vertical-align:top;"><?php echo $allmessage_check; ?></td>
</tr>
<tr>
<td style="padding-left:15px;"><?php echo $file_table; ?></td>
</tr>
</table>
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

