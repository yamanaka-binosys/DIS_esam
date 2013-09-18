<?php 
	$base_url = $this->config->item('base_url');
?>
<?php $this->load->helper('html'); ?>
<?php echo doctype('html4-strict'); ?>
<html>
<head>
	<script type="text/javascript"></script>
	<script type="text/javascript">
		function proc() {
			if (window.parent.opener  && !window.parent.opener.closed) {
				var login_page = window.parent.opener;
				login_page.document.getElementById('errmsg').innerText = 'セッションがタイムアウトしました。再度ログインして下さい。';
				login_page.document.getElementById('errmsg').textContent = 'セッションがタイムアウトしました。再度ログインして下さい。';
				login_page.focus();
				window.parent.close();
			} else {
				window.open('<?php echo $base_url ?>index.php/login/index?err-msg=1');
				window.parent.close();
			}
		}
	</script>
</head>
<body onload="proc()">
	<p>セッションがタイムアウトしました。</p>
</body>