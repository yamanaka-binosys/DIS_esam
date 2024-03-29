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
<script type="text/javascript" src="<?php echo $base_url; ?>script/project_item.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/header.js"></script>
<script type="text/javascript">
  function c_check(obj){
      if(parent.content.document.getElementById(obj.name).value ==0){
          parent.content.document.getElementById(obj.name).value = 1;
      }else{
          parent.content.document.getElementById(obj.name).value = 0;
      }
  }
</script>
</head>

<body>
<?php
if(isset($form)) {
  echo '<form action="'.$app_url.$form.'" method="POST" name="'.$form_name.'" id="'.$form_name.'">'."\n";
}
?>


<!-- hidden -->
<input type="hidden" name="set" value="">
<input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
<input type="hidden" name="start_no" id="start_no" value="<?php echo $start_no; ?>">
<div id="app_url" style="display:none"><?php echo $app_url; ?></div>
<div id="add_view_no" style="display:none"></div>


<!-- MAIN -->
<div id="">
<div id="container">
<br>
<div id="page1">
<table>
<tr>
<td><?php echo $page_tabel; ?></td>
</tr>
<tr>
<td>
  <table id="project_item_data">
    <tr>
      <td style="width: 85px">&nbsp;</td>
      <td align="center" style="width: 30px">行</td>
      <td style="width: 85px">&nbsp;</td>
      <td align="left" style="width: 200px">大分類名</td>
      <td align="left" style="width: 250px">アイテム名</td>
      <td align="left" >表示</td>
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
