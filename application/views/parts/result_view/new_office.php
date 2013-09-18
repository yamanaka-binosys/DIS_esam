<?php
log_message('debug',"===== Start new_office.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
?>

<div id="action_<?php echo $count; ?>">
<br>
<?php if(isset($result['recode_flg'])){ ?>
<?php if($result['recode_flg'] == 1){ ?>
	<table style="border:2px solid #000000; width:830px; background-color: #FFE0B2">
<?php }else{ ?>
	<table style="border:2px solid #000000; width:830px">
<?php } ?>
<?php }else{ ?>
	<table style="border:2px solid #000000; width:830px">
<?php } ?>
<tr>
<td colspan="3">

<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>" id="action_type_<?php echo $count; ?>">
<option value="srntb040" selected>内勤</option>
</select>
</td>
</tr>
</table>

<input type="hidden" name="jyohonum_<?php echo $count; ?>" id="jyohonum_<?php echo $count; ?>" value="<?php echo isset($result['jyohonum']) ? $result['jyohonum'] : ''?>" >
<input type="hidden" name="edbn_<?php echo $count; ?>" id="edbn_<?php echo $count; ?>" value="<?php echo isset($result['edbn']) ? $result['edbn'] : ''?>" >
<input type="hidden" name="recode_flg_<?php echo $count; ?>" id="recode_flg_<?php echo $count; ?>" value="<?php echo isset($result['recode_flg']) ? $result['recode_flg'] : ''?>" >

<br>
<table>
<tr>
<td>

<table>
<tr>
<td>

<table>
<tr>
<td colspan="4">開始時刻</td>
</tr>
<tr>
<td></td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($result['sthm']) ? substr($result['sthm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($result['sthm']) ? substr($result['sthm'], 2, 2) : ''?>" readonly="readonly">分</td>
</tr>
<tr>
<td colspan="4">終了時刻</td>
</tr>
<tr>
<td></td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 2, 2) : ''?>" readonly="readonly">分</td>
</tr>
</table>

</td>
<td>
<table>
<tr>
<td>作業内容</td>
</tr>
<tr>
<td>
<?php
if(isset($result['sagyoniyo'])){
echo $result['sagyoniyo'];
}else{
echo $sagyoniyo;
}
 ?>
</td>
</tr>
<tr>
<td style="padding-left:10px">
その他<input type="text" style="width:120px;" maxlength="256" name="sntsagyo_<?php echo $count; ?>" id="sntsagyo_<?php echo $count; ?>" value="<?php echo isset($result['sntsagyo']) ? $result['sntsagyo'] : ''?>" readonly="readonly">
</td>
</tr>
</table>
</td>
<td>
</td>
</tr>
<tr>
<td colspan="3">
<table>
<tr>
<td>結果情報</td>
</tr>
<tr>
<td>
<textarea style="height:100px; width:600px" name="kekka_<?php echo $count; ?>" id="kekka_<?php echo $count; ?>" value="" readonly="readonly">
<?php echo isset($result['kekka']) ? $result['kekka'] : ''?>
</textarea>
</td>
</tr>
</table>
<table width="800px">
<tr>
<td>【指示コメント】</td>
</tr>
<tr>
<td>
<?php if($write_flg == 0){
	echo "<textarea cols=\"34\" style=\"height:35px;width:600px;\" name=\"sijicmt01_".$count."\" readonly=\"readonly\">";
	echo isset($result['sijicmt01']) ? $result['sijicmt01'] : '';
	echo "</textarea>";
}else{
	echo "<textarea cols=\"34\" style=\"height:35px;width:600px;\" name=\"sijicmt01_".$count."\">";
	echo isset($result['sijicmt01']) ? $result['sijicmt01'] : '';
	echo "</textarea>";
}?>
</td>
</tr>
</table>
</td>

</tr>
</table>

</td>
</tr>
</table>

</td>
</tr>
</table>
</div>
<?php
log_message('debug',"===== End new_office.php =====");
?>
