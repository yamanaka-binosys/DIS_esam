<!-- MAIN -->
<div id="Main">
<div id="container">
<br>
<form action="<?php echo $main_form; ?>" method="POST">
<?php echo $tab; ?>
<div id="page1">
<table>
<tr>
<td><?php echo $kari_client_table; ?></td>
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

