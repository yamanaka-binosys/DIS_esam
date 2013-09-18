<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/todo.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/todo.js"></script>
<script type="text/JavaScript">
  function fn_onload(){
	  var errmsg = top.header.document.getElementById("errmsg");
   <?php if (!empty($infomsg)) {  ?>
 	errmsg.setAttribute('class', 'msg-info');
   errmsg.setAttribute('className', 'msg-info'); // for ie6
   errmsg.innerText = '<?php echo $infomsg; ?>';
	 errmsg.textContent = '<?php echo $infomsg; ?>';
   <?php } else { ?>
   errmsg.setAttribute('class', 'msg-error');
   errmsg.setAttribute('className', 'msg-error'); // for ie6
   errmsg.innerText = '<?php echo $errmsg; ?>';
	  errmsg.textContent = '<?php echo $errmsg; ?>';
   <?php } ?>
  }
</script>
</head>
<!--body onload="header_load('<?php echo $base_url; ?>','<?php echo $view_name; ?>','<?php echo $errmsg; ?>');"-->
<body onload="fn_onload();">
<!-- MAIN -->

<div id="Main">
	<div id="container">
	<?php
	if ( $type == "update" ) { ?>
	<form action="<?php echo $base_url; ?>index.php/todo/update_select_type" method="POST" name="todo">
		<div id="page2">
			<br>
		<?php if(!empty($naiyo)) {?>
				<table style="margin: 0 auto; width: 800px">
					<tr>
						<td style="margin: 0; padding-left: 0px; border-collapse: collapse; border: 1px solid #000000;">
							<table style="border-collapse: collapse; text-align: left; margin: 0px;">
								<tr>
	                                <th style="border: 1px #000000 solid;border-style: none solid solid none;width: 27px;"></th>
									<th style="padding-left: 5px; font-weight: normal; border: 1px #000000 solid; border-top-style: none; width: 211px;">期限</th>
									<th style="padding-left: 5px; font-weight: normal; border: 1px #000000 solid; border-top-style: none; width: 155px;">入力日</th>
									<th style="padding-left: 5px; font-weight: normal; border: 1px #000000 solid; border-top-style: none; width: 50px;">重要度</th>
									<th style="padding-left: 5px; font-weight: normal; border: 1px #000000 solid; border-top-style: none; width: 270px;">内容</th>
									<th style="padding-left: 5px; font-weight: normal; border: 1px #000000 solid; border-top-style: none; border-right-style: none; width: 63px;">終了確認</th>
								</tr>
							</table>
							<div style="margin: 0; width: 794px; height: <?php echo $list_height; ?>px;overflow: auto; border-collapse: collapse;">
								<table style="border-collapse: collapse; border-spacing: 0;">
								<?php if(!empty($naiyo)) {
									$no = 0;
									foreach($naiyo as $key => $value){
									$no++;
								?>
									<tr>
										<td style="width: 26px; border: 1px #000000 solid; border-top-style: none; border-left-style: none; <?php echo ($no == count($naiyo)) ? "border-bottom-style: none;": ""; ?>">
											<input type="checkbox" name="select_check[]" id="select_check[]" class="checkbox checked_item<?php echo $no;?>" title="変更対象" value="<?php echo $value["jyohonum"];?>">
										</td>
										<td style="width: 210px; border: 1px #000000 solid; border-top-style: none; <?php echo ($no == count($naiyo)) ? "border-bottom-style: none;": ""; ?>">
											<?php echo $value['year']; ?>年
											<?php echo $value['month']; ?>月
											<?php echo $value['day']; ?>日
										</td>
										<td style="width: 155px; border: 1px #000000 solid; border-top-style: none; <?php echo ($no == count($naiyo)) ? "border-bottom-style: none;": ""; ?>">
											<input type="text" name="input_year[]" id="input_year[]" style="width:30px;" size="4" maxlength="4" value="<?php echo substr($value["updatedate"], 0, 4)?>" readonly>年
											<input type="text" name="input_month[]" id="input_month[]" style="width:20px;" size="2" maxlength="2" value="<?php echo substr($value["updatedate"], 4, 2)?>" readonly>月
											<input type="text" name="input_day[]" id="input_day[]" style="width:20px;" size="2" maxlength="2" value="<?php echo substr($value["updatedate"], 6, 2)?>" readonly>日
										</td>
										<td style="width: 53px; border: 1px #000000 solid; text-align: center; border-top-style: none; <?php echo ($no == count($naiyo)) ? "border-bottom-style: none;": ""; ?>">
											<select name="important[]" id="important[]">
											<option value="3" <?php if($value["impkbn"] == "3")echo "selected"?>>高</option>
											<option value="2" <?php if($value["impkbn"] == "2")echo "selected"?>>中</option>
											<option value="1" <?php if($value["impkbn"] == "1")echo "selected"?>>低</option>
											</select>
										</td>
										<td style="width: 267px; border: 1px #000000 solid; border-top-style: none; <?php echo ($no == count($naiyo)) ? "border-bottom-style: none;": ""; ?>">
											<input type="text" name="contents[]" id="contents[]" class="required" title="内容" style="width:250px;ime-mode: active;" size="40"  value=<?php echo $value["todo"]?>>
										</td>
										<td style="width: <?php echo $list_width; ?>px; border: 1px #000000 solid; text-align: center; border-top-style: none; border-right-style: none; <?php echo ($no == count($naiyo)) ? "border-bottom-style: none;": ""; ?>">
											<input type="checkbox" name="comp[]" id="comp[]" value="<?php echo $value["jyohonum"];?>"<?php if($value["finishflg"] == "1") echo "checked"?>>
											<input type="hidden" name="jnum[]" id="jnum[]" value="<?php echo $value["jyohonum"]; ?>">
										</td>
									</tr>
						<?php } ?>
					<?php } ?>
								</table>
							</div>
						</td>
					</tr>
				</table>
		<?php } ?>
		</div>
	<?php }else{?>
		<form action="<?php echo $base_url; ?>index.php/todo" method="POST" name="todo">
			<div id="page1">
				<table style="margin: 0 auto; width: 500px;">
				<tr>
					<th colspan="3" style="font-size: 13pt; padding-bottom: 20px;">ＴｏＤｏ情報入力</th>
				</tr>
				<tr>
					<td style="padding-bottom: 20px;text-align:left;">重要度</td>
					<td style="padding-bottom: 20px;">
						<select name="impkbn">
							<option value="3" <?php echo ($impkbn === '3') ? "selected" : ""; ?>>高</option>
							<option value="2" <?php echo ($impkbn === '2') ? "selected" : ""; ?>>中</option>
							<option value="1" <?php echo ($impkbn === '1') ? "selected" : ""; ?>>低</option>
						</select>
					</td>
				</tr>
				<tr>
					<td style="padding-bottom: 20px;text-align:left;">内容</td>
					<td style="padding-bottom: 20px;"><input type="text" name="todo" id="todo" class="required" title="内容" style="ime-mode: active;" size="70" value="<?php echo (isset($todo)) ? $todo : ""; ?>"></td>
				</tr>
				<tr>
					<td style="padding-bottom: 20px;text-align:left;">期限</td>
					<td style="padding-bottom: 20px;">
						<?php echo $year; ?>年
						<?php echo $month; ?>月
						<?php echo $day; ?>日
					</td>
				</tr>
				</table>
			</div>
		<?php }?>
		</form>
	</div>
</div>
<input type="hidden" value="" name="referer" id="referer">
</body>
</html>

