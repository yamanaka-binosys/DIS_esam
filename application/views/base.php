<?php
log_message('debug',"===== Start base.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');

echo doctype('html4-frame');
echo "<html>\n";
echo "<head>\n";
echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
echo "<title>\n";
echo "</title>\n";
echo "</head>\n";
echo "<frameset id=\"baseset\" name=\"baseset\" rows=\"117,*\">\n";
echo "<frame src=\"".$base_url."index.php/system/header\" name=\"header\" id=\"header\" scrolling=\"no\">\n";
echo "<frameset cols=\"140,*\">\n";
echo "<frame src=\"".$base_url."index.php/system/menu\" name=\"menu\" id=\"menu\" scrolling=\"auto\">\n";
echo "<frame src=\"".$base_url."index.php/top/index\" name=\"content\" id=\"content\" scrolling=\"auto\">\n";
//echo "<frame src=\"".$base_url."index.php/system/content\" name=\"content\" id=\"content\" scrolling=\"auto\">\n";
echo "</frameset>\n";
echo "</frameset>\n";
echo "</html>\n";
log_message('debug',"===== End base.php =====");
?>