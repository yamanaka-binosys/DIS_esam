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
<select name="action_type_00" onChange="select_action(this,'<?php echo $base_url; ?>')">
<option value="non"></option>
<option value="srntb010">販売店本部</option>
<option value="srntb020">店舗</option>
<option value="srntb030">代理店</option>
<option value="srntb040">業者</option>
<option value="srntb060">内勤</option>
</select>
</td>
<td>
<input type="button" name="action_delete_00" id="action_delete_00" value="削除" >
</td>
</tr>
</table>
</div>
<?php
log_message('debug',"===== End new_action.php =====");
?>
