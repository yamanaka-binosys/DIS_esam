<?php
log_message('debug',"===== Start new_action.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
log_message('debug',"\$base_url = $base_url");
log_message('debug',"\$count = $count");
?>

<div id="action_<?php echo $count; ?>">
<br>
<table style="border:2px solid #000000; width:830px">
<tr>
<td>
<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>" onChange="select_action(this,'<?php echo $base_url; ?>')">
<option value="non"></option>
<option value="srntb110">販売店本部</option>
<option value="srntb120">店舗</option>
<option value="srntb130">代理店</option>
<option value="srntb140">業者</option>
<option value="srntb160">内勤</option>
</select>
</td>
<td>
<input type="button" name="action_delete_<?php echo $count; ?>" id="action_delete_<?php echo $count; ?>" value="削除" >
<input type="button" name="action_move_<?php echo $count; ?>" id="action_move_<?php echo $count; ?>" value="移動" >
<input type="button" name="action_copy_<?php echo $count; ?>" id="action_copy_<?php echo $count; ?>" value="コピー" >
</td>
</tr>
</table>
</div>
<?php
log_message('debug',"===== End new_action.php =====");
?>
