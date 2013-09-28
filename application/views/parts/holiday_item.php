<?php
log_message('debug',"===== Start division.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-frame')."\n"; ?>
<html>
<head>
<?php echo meta($meta); ?>
<?php echo "<title>{$title}</title>\n"; // TITLE宣言 ?>
<link href="<?php echo $base_url; ?>css/main.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/holiday_item.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/header.js"></script>
</head>

<body>
<?php
if(isset($form)) {
  echo '<form action="'.$app_url.$form.'" method="POST" name="'.$form_name.'" id="'.$form_name.'">'."\n";
}
?>

<!-- hidden -->
<input type="hidden" name="set" value="">
<input type="hidden" name="syaban_now" value="<?php echo $shbn; ?>">
<div id="app_url" style="display:none"><?php echo $app_url; ?></div>
<div id="add_view_no" style="display:none"></div>


<!-- MAIN -->
<div id="">
<div id="container">
<br>
<select name="holiday_year" onChange="holiday_year_chenge(this.options[this.selectedIndex].value)" style="margin-left: 15px">
    <?php for($i=2012;$i<$max_year+1;$i++): ?>
        <option value="<?php echo $i; ?>" <?php if($i==$select_year) echo " selected"; ?>><?php echo $i; ?></option>
    <?php endfor; ?>
</select>        
<div id="page1">
<table>
<tr>
<td><!--<?php echo $page_tabel; ?>--></td>
</tr>
<tr>
<td>
  <table id="holiday_item_data">
    <tr>
      <td style="width: 60px; margin: 5px;">&nbsp;</td>
      <!--<td align="center" style="width: 30px">行</td>-->
      <td style="width: 60px; margin: 5px;">&nbsp;</td>
      <td align="left" style="width: 120px; padding-left: 40px;">設定月日</td>
      <td align="left" style="width: 250px; margin: 5px;">メモ</td>
    </tr>
  <?php echo $list_tabel; ?>
  </table>
</td>
</tr>
</table>
</div>
</div>
</div>
<?php if(isset($form)) echo "</form>"; ?>
</body>
</html>
