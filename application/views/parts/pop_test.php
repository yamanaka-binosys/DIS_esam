<!-- MAIN -->
<div id="Main">
<div id="container">
<br>
<form action="select_checker" method="POST">
<div id="page1">
<table>
<tr>
<td><input type="text" name="test" value="<?php echo $testdata; ?>">
	<input type="button" name="test" value="検索" onclick="location.href='http://localhost/elleair/index.php/select_checker/index'">
	<input type="hidden" name="shbn" value="">
</td>
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

