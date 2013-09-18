<?php
log_message('debug',"===== Start header.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
$header_gif_name = $base_url.$gif_name;
$header_day = $day."(".$week_day.")";
$header_bu = $bu_name;
$header_ka = $ka_name;
$header_name = $shinnm;

log_message('debug',"\$base_url = $base_url");
log_message('debug',"\$meta = $meta");
log_message('debug',"\$header_gif_name = $header_gif_name");
log_message('debug',"\$header_day = $header_day");
log_message('debug',"\$header_bu = $header_bu");
log_message('debug',"\$header_ka = $header_ka");
log_message('debug',"\$header_name = $header_name");
?>

<?php echo doctype('html4-frame'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/header.css" rel="stylesheet"
	type="text/css">
<script type="text/javascript"
	src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript"
	src="<?php echo $base_url; ?>script/form_validation.js"></script>
<script type="text/javascript"
	src="<?php echo $base_url; ?>script/header.js"></script>
<script type="text/javascript">
//相手先選択　jsから読み込み用変数
  MY_AITESK_CD_IPPAN = '<?php echo MY_AITESK_CD_IPPAN ?>';
  </script>
<script type="text/javascript">
<!--
$(function(){
  $('form').submit(function (e) {
    var form = $('form', parent.content.document);  //contenのformを指定
    return formValidation(form);
  });
});
-->
</script>
</head>
<body onload="init_normal()">
	<div id="Header">
		<table style="width: 1000px;">
			<tr>
				<td class="title"><img src="<?php echo $header_gif_name ?>" alt="" >
				</td>
				<td class="right" style="margin-right:50px;"><?php
				if(!is_null($header_day)){
					echo $header_day."<br>\n";
				}
//				if(!is_null($header_bu)){
//					echo $header_bu."<br>\n";
//				}
				if(!is_null($header_ka)){
					echo $header_ka."<br>\n";
				}
				if(!is_null($header_name)){
					echo $header_name;
				}
				?>
				</td>
			</tr>
		</table>
		<div id="errmsg" width="1024px" class="<?php if(isset($errclass) && $errclass!=""){ echo $errclass;  } ?>">
			<?php if( ! is_null($errmsg)) {
				echo $errmsg;
			}
			?>
		</div>
		<?php
		if(!is_null($btn_confirmer) OR !is_null($btn_name)){
			echo "<table style=\"width:1024px;\">\n";
			echo "<tr>\n";
			$td_width = "width:50%;";
			$td_left = "<td class=\"left\" style=\"{$td_width}\">&nbsp;</td>\n";	//初期left
			$td_right = "<td class=\"left\" style=\"{$td_width}\">&nbsp;</td>\n";	//初期right
			if(!is_null($btn_confirmer)){
				//LEFT
				if(!is_null($confirmer_text)){
	    $td_left = "<td class=\"left\" style=\"width:20%;\">{$confirmer_text}&nbsp;<input type=\"text\" name=\"checker\" id=\"checker\" value=\"\" readonly=\"readonly\"></td>\n";
				} else {
					$td_left = "<td class=\"left\" style=\"width:20%;\"><input type=\"text\" name=\"checker\" id=\"checker\" value=\"\" readonly=\"readonly\"></td>\n";
				}
				$td_left .= "<td class=\"left\" style=\"width:30%;\"><input type=\"button\" name=\"confirmer\" id=\"confirmer\" value=\"".$btn_confirmer."\" onclick=\"".$confirmer_js_name."\"></td>\n";
			}
			if(!is_null($btn_name)){
				$td_right = "<td class=\"right\" style=\"{$td_width}padding-right:20px;\"><input type=\"submit\" name=\"decision\" id=\"decision\" value=\"".$btn_name."\" onclick=\"".$js_name."\"></td>\n";
			}
			echo $td_left.$td_right;
			echo "</tr>\n";
			echo "</table>\n";
		}
		?>
	</div>
</body>
</html>
<?php
log_message('debug',"===== End header.php =====");
?>