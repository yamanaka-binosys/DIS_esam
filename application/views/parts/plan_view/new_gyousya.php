<?php
log_message('debug',"===== Start new_gyousya.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
?>

<div id="action_<?php echo $count; ?>" class="action">
<br>
<?php if(isset($plan['recode_flg'])){ ?>
<?php if($plan['recode_flg'] == 1){ ?>
	<table style="border:2px solid #000000; width:830px; background-color: #D1FBFF">
<?php }else{ ?>
	<table style="border:2px solid #000000; width:830px">
<?php } ?>
<?php }else{ ?>
	<table style="border:2px solid #000000; width:830px">
<?php } ?>
<tr>
<td>
<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>" id="action_type_<?php echo $count; ?>">
<option value="srntb160" selected>業者</option>
</select>
</td>
<td>
<input type="button" name="action_delete_<?php echo $count; ?>" id="action_delete_<?php echo $count; ?>" value="削除" onclick="">
<input type="text" style="width: 80px;" name="move_copy_day_<?php echo $count; ?>" id="move_copy_day_<?php echo $count; ?>" value="" onfocus="" readonly="readonly">
<input type="button" name="action_move_<?php echo $count; ?>" id="action_move_<?php echo $count; ?>" value="移動" onclick="">
<input type="button" name="action_copy_<?php echo $count; ?>" id="action_copy_<?php echo $count; ?>" value="コピー" onclick="">
</td>
</tr>
</table>

<input type="hidden" name="jyohonum_<?php echo $count; ?>" id="jyohonum_<?php echo $count; ?>" value="<?php echo isset($plan['jyohonum']) ? $plan['jyohonum'] : ''?>" >
<input type="hidden" name="edbn_<?php echo $count; ?>" id="edbn_<?php echo $count; ?>" value="<?php echo isset($plan['edbn']) ? $plan['edbn'] : ''?>" >
<input type="hidden" name="recode_flg_<?php echo $count; ?>" id="recode_flg_<?php echo $count; ?>" value="<?php echo isset($plan['recode_flg']) ? $plan['recode_flg'] : ''?>" >

<br>

<table style="padding-top: 0;">
<tr><td colspan="4">開始時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width:20px;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width:20px;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 2, 2) : ''?>" readonly="readonly">分</td>
</tr>
<tr><td colspan="4">終了時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width:20px;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width:20px;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 2, 2) : ''?>" readonly="readonly">分</td>
</tr>
</table>

<table>
<tr>
<td>メモ</td>
</tr>
<tr>
<td>
<textarea style="height: 120px;width: 650px;" name="memo_<?php echo $count; ?>" id="memo_<?php echo $count; ?>" readonly="readonly"><?php echo isset($plan['memo']) ? $plan['memo'] : ''?></textarea>
</td>
</tr>

</table>
</td>
</tr>

</table>
</div>
