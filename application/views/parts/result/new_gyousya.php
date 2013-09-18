<?php
log_message('debug',"===== Start new_gyousya.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
?>

<div id="action_<?php echo $count; ?>"　class="action_container">
<br><a class="anchor" name="<?php echo isset($result['jyohonum']) &&  $result['jyohonum'] != 'XXXXXXXXX' ? $result['jyohonum'] : (isset($result['uid']) ? $result['uid'] : $uid) ?>" href="#"></a>
<?php if(isset($result['recode_flg']) ){ ?>
<?php if($result['recode_flg'] == 1  && $check_hold == 0){ ?>
	<table style="border:2px solid #000000; width:830px; background-color: #FFE0B2">
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
<option value="srntb060" selected="selected">業者</option>
</select>
</td>
<td>
<input type="button" name="action_delete_<?php echo $count; ?>" id="action_delete_<?php echo $count; ?>" value="削除" onclick="javascript=delete_action('<?php echo $base_url; ?>','<?php echo $count; ?>')">
</td>
</tr>
</table>
<input type="hidden" name="uid_<?php echo $count; ?>" id="uid_<?php echo $count; ?>" value="<?php echo isset($result['jyohonum']) &&  $result['jyohonum'] != 'XXXXXXXXX' ? $result['jyohonum'] : (isset($result['uid']) ? $result['uid'] : $uid) ?>"/>
<input type="hidden" name="jyohonum_<?php echo $count; ?>" id="jyohonum_<?php echo $count; ?>" value="<?php echo isset($result['jyohonum']) ? $result['jyohonum'] : ''?>" >
<input type="hidden" name="edbn_<?php echo $count; ?>" id="edbn_<?php echo $count; ?>" value="<?php echo isset($result['edbn']) ? $result['edbn'] : ''?>" >
<input type="hidden" class="recode_flg" name="recode_flg_<?php echo $count; ?>" id="recode_flg_<?php echo $count; ?>" value="<?php echo isset($result['recode_flg']) ? $result['recode_flg'] : ''?>" >

<br>

<table style="padding-top: 0;">
<tr><td colspan="4">開始時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width: 20px;ime-mode: disabled;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($result['sthm']) ? substr($result['sthm'], 0, 2) : ''?>" class="required numInputOnly" title="開始時刻">時</td>
<td><input type="text" style="width: 20px;ime-mode: disabled;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($result['sthm']) ? substr($result['sthm'], 2, 2) : ''?>" class="required numInputOnly" title="開始時刻">分</td>
</tr>
<tr><td colspan="4">終了時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width: 20px;ime-mode: disabled;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 0, 2) : ''?>" class="required numInputOnly" title="終了時刻">時</td>
<td><input type="text" style="width: 20px;ime-mode: disabled;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 2, 2) : ''?>" class="required numInputOnly" title="終了時刻">分</td>
</tr>
</table>


<table>
<tr>
<td colspan="4">メモ</td>
</tr>
<tr>
<td>
<textarea style="height: 120px;width: 600px;" name="memo_<?php echo $count; ?>" id="memo_<?php echo $count; ?>"><?php echo isset($result['memo']) ? $result['memo'] : ''?></textarea>
</td>
</tr>
</table>

<table>
<tr><td colspan="4">【指示コメント】</td></tr>
<tr>
<td>
<?php if($write_flg == 0){
	echo "<textarea style=\"height:35px;width:600px;\" name=\"sijicmt_".$count."\" readonly=\"readonly\">";
	echo isset($result['sijicmt']) ? $result['sijicmt'] : '';
	echo "</textarea>";
}else{
	echo "<textarea style=\"height:35px;width:600px;\" name=\"sijicmt_".$count."\">";
	echo isset($result['sijicmt']) ? $result['sijicmt'] : '';
	echo "</textarea>";
}?>
</td>
</tr>
</table>

</td>
</tr>
</table>
</div>
