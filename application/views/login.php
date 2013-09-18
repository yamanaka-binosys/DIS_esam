<?php
	log_message('debug',"===== Start login.php =====");
	$this->load->helper('html');
	$base_url = $this->config->item('base_url');
	
	log_message('debug',"\$base_url = $base_url");
	
	echo doctype('html4-frame');
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
	echo "<title>\n";
	echo "</title>\n";
	echo "</head>\n";
	echo "<frameset rows=\"100,*\">\n";
	echo "<frame src=\"".$base_url."index.php/test/header\" name=\"header\" id=\"header\" scrolling=\"no\">\n";
	echo "<frame src=\"".$base_url."index.php/login/index\" name\"content\" id=\"content\" scrolling=\"auto\">\n";
	echo "</frameset>\n";
	echo "</frameset>\n";
	echo "</html>\n";
	log_message('debug',"===== End login.php =====");
?>