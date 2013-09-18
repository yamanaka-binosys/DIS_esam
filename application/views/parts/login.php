<!-- MAIN -->
<script type="text/javascript">
	window.onload = afocus;
 
	function afocus() {
		document.getElementById('login').Shbn.focus();
	}
</script>
<div id="Box">
<form action="check" id="login" method="POST">
<table>
<tr>
<td>社番</td>
<td><input type="text" name="Shbn" id="Shbn" value="<?php echo $shbn; ?>">
</tr>
<tr>
<td>パスワード</td>
<td><input type="password" name="pw" id="pw" value="<?php echo $pw; ?>"></td>
</tr>
<tr>
<td colspan="2" id="button">
<input type="submit" name="ipass" id="ipass" value="ログイン">
</td>
</tr>
</table>
</form>
</div>
