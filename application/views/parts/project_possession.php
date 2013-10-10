<?php
log_message('debug',"===== Start division.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
/*
if(!isset($year) || !$year) $year = date("Y");
if(!isset($month) || !$month) $month = (int)date("m");
if(!isset($view_year) || !$view_year) $view_year = date("Y");
if(!isset($view_month) || !$view_month) $view_month = (int)date("m");
*/
?>
<?php echo doctype('html4-frame')."\n"; ?>
<html>
<head>
<?php echo meta($meta); ?>
<?php echo "<title>{$title}</title>\n"; // TITLE宣言 ?>
<link href="<?php echo $base_url; ?>css/main.css" rel="stylesheet" type="text/css">
<link href="<?php echo $base_url; ?>css/project_possession.css" rel="stylesheet" type="text/css">
<script type="text/javascript">
 /*@cc_on _d=document;eval('var document=_d')@*/
</script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/project_possession.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
</head>

<body onload="new_action_view('<?php echo $base_url; ?>','<?php echo SHOW_PROJECT_P ?>');">
<?php
if(isset($form)) {
  echo '<form action="'.$app_url.$form.'" method="POST" name="'.$form_name.'" id="'.$form_name.'">'."\n";
}
?>
  <input type="hidden" name="set" value="">
  <input type="hidden" name="app_url" id="app_url" value="<?php echo $app_url; ?>">

<div id="change_position" style="display:none"></div>
<div id="add_view_no" style="display:none"><?php echo $add_view_no; ?></div>
<div id="output" style="display:none"><?php echo $output; ?></div>
<div id="errclass" style="display:none"><?php if (isset($errclass)) { echo $errclass; }; ?></div>
<div id="message"></div>

  <!-- MAIN -->
  <div id="">
    <div id="container">
      <br>
      <?php if(!$disabled) {?>
      <table class="top_block">
        <tr>
          <td style="width: 72px">販売店名</td>
          <td style="width: 130px">
            <input type="text" name="aitesk_name" id="aitesk_name" readonly="readonly" class="" style="width:148px" maxlength="256" value="<?php echo $aitesk_name;?>" <?php echo $disabled;?> >
            <input type="hidden" name="aiteskcd" id="aiteskcd" class="required" title="販売店名" value="<?php echo $aiteskcd;?>14402000">
            <input type="hidden" value="" name="referer" id="referer">
            <input type="hidden" value="" name="keep_val" id="keep_val">
            <input type="hidden" name="gkubun" value="<?php echo MY_SELECT_CLIENT_HEAD;?>">
          </td>
          <td><input type="button" value="選択" name="search" id="search" onclick="select_client('pp','<?php echo MY_SELECT_CLIENT_HEAD ?>','<?php echo $base_url ?>')" <?php echo $disabled;?>></td>
        </tr>
        <tr>
          <td style="">
            <select name="year" id="year" class="s_group"  title="年" <?php echo $disabled;?>>
							<?php foreach ( $base_year as $key => $value ) { ?>
							<option value="<?php echo $value; ?>" <?php if ( $value == $year ) { echo 'selected'; } ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>年
          </td><td>
             <select name="month" id="month" class="s_group"  title="月" <?php echo $disabled;?>>
							<?php foreach ( $base_month as $key => $value ) { ?>
							<option value="<?php echo $value; ?>" <?php if ( $value == $month ) { echo 'selected'; } ?>><?php echo $value; ?></option>
							<?php } ?>
						</select>月
          </td>
          <td><input type="button" value="表示" onclick="target_list_search();" <?php echo $disabled;?>></td>
        </tr>
      </table>
      <?php } ?>
	    <?php if($table_data) {?>
<div style="clear: both;"></div><br>
	    <div class="button_block">
        <input type="button" value="追加" onclick="add_row('<?php echo $app_url; ?>');" <?php echo $disabled;?>>　
        <input type="button" value="削除" onclick="dell_row('<?php echo $app_url; ?>');" <?php echo $disabled;?>><br>
      </div>
      <?php } ?>
<div style="clear: both;"></div><br>
      <table class="list_block" id="project_possession_data" width="800px">
        <tr class="border_row">
          <td class="border_row">削除</td>
          <td class="border_row">大分類</td>
          <td class="border_row">アイテム</td>
          <td class="border_row">区分</td>
          <td class="border_row">&nbsp;</td>
          <td class="border_row">日付</td>
          <td class="border_row">店舗数</td>
          <td class="border_row">売価</td>
        </tr>
        <?php echo $table_data;?>
      </table>
      <br>
    </div>
  </div>
  </form>

</body>
</html>
