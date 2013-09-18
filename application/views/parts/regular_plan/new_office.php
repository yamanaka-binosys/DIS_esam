<?php
log_message('debug',"===== Start new_office.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
?>

<div id="action_<?php echo $count; ?>">
<br>
<table style="border:2px solid #000000; width:830px">
<tr>
<td colspan="3">
<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>">
<option value="srntb140" selected>内勤</option>
</select>
</td>
<td>
<input type="button" name="action_delete_<?php echo $count; ?>" id="action_delete_<?php echo $count; ?>" value="削除" onclick="javascript=delete_action('<?php echo $base_url; ?>','<?php echo $count; ?>')">
</td>
</tr>
</table>

<input type="hidden" name="jyohonum_<?php echo $count; ?>" id="jyohonum_<?php echo $count; ?>" value="<?php echo isset($plan['jyohonum']) ? $plan['jyohonum'] : ''?>" >
<input type="hidden" name="edbn_<?php echo $count; ?>" id="edbn_<?php echo $count; ?>" value="<?php echo isset($plan['edbn']) ? $plan['edbn'] : ''?>" >

<br>

<table style="border:1px solid #000000; width:800px">
<tr>
<td>
<table>
<tr>
<td>
<td><label> スケジュール終了日 </label>
<input type="text" style="width: 80px;ime-mode: disabled;" name="deadline_day_<?php echo $count; ?>" id="deadline_day_<?php echo $count; ?>" value="<?php echo isset($plan['deadline_day']) ? date_format(date_create($plan['deadline_day']), 'Y/m/d'):'';?>" class="cal required " title="スケジュール終了日">
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="1" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '1') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class="checked_item1<?php echo $count; ?>"> 毎月
<input type="text" style="width: 20px;ime-mode: disabled;" name="designated_day_<?php echo $count; ?>" id="designated_day_<?php echo $count; ?>" value="<?php echo isset($plan['designated_day']) ? $plan['designated_day'] : ''?>" maxlength="2" class="checked_item1<?php echo $count; ?> required c_group"  title="毎月の予定日" />日
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="2" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '2') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')"> 月末
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="3" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '3') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class=""> 毎週
</td>
<td><input type="checkbox" name="designated_sun_<?php echo $count; ?>" id="designated_sun_<?php echo $count; ?>" <?php if(isset($plan['designated_sun']) && $plan['designated_sun'] === '1') echo " checked "; ?> disabled="disabled" class="" title="曜日">日</td>
<td><input type="checkbox" name="designated_mon_<?php echo $count; ?>" id="designated_mon_<?php echo $count; ?>" <?php if(isset($plan['designated_mon']) && $plan['designated_mon'] === '1') echo " checked "; ?> disabled="disabled" class="">月</td>
<td><input type="checkbox" name="designated_tues_<?php echo $count; ?>" id="designated_tues_<?php echo $count; ?>" <?php if(isset($plan['designated_tues']) && $plan['designated_tues'] === '1') echo " checked "; ?> disabled="disabled" class="">火</td>
<td><input type="checkbox" name="designated_wed_<?php echo $count; ?>" id="designated_wed_<?php echo $count; ?>" <?php if(isset($plan['designated_wed']) && $plan['designated_wed'] === '1') echo " checked "; ?> disabled="disabled" class="">水</td>
<td><input type="checkbox" name="designated_thurs_<?php echo $count; ?>" id="designated_thurs_<?php echo $count; ?>" <?php if(isset($plan['designated_thurs']) && $plan['designated_thurs'] === '1') echo " checked "; ?> disabled="disabled" class="">木</td>
<td><input type="checkbox" name="designated_fri_<?php echo $count; ?>" id="designated_fri_<?php echo $count; ?>" <?php if(isset($plan['designated_fri']) && $plan['designated_fri'] === '1') echo " checked "; ?> disabled="disabled" class="">金</td>
<td><input type="checkbox" name="designated_sat_<?php echo $count; ?>" id="designated_sat_<?php echo $count; ?>" <?php if(isset($plan['designated_sat']) && $plan['designated_sat'] === '1') echo " checked "; ?> disabled="disabled" class="">土</td>
</tr>
</table>
</td>
</tr>
</table>

<br>
<table>
<tr>
<td>

<table>
<tr>
<td>
<table>
<tr><td colspan="4">開始時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 0, 2) : ''?>" class="required numInputOnly min1 max48 checkdate_from<?php echo $count; ?>" title="開始時刻" onKeyDown="return checkNum()" >時</td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 2, 2) : ''?>" class="required numInputOnly min0 max59 checkdate_from<?php echo $count; ?>" title="開始時刻" onKeyDown="return checkNum()">分</td>
</tr>
<tr><td colspan="4">終了時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 0, 2) : ''?>" class="required numInputOnly min1 max48 checkdate_to<?php echo $count; ?>" title="終了時刻" onKeyDown="return checkNum()">時</td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 2, 2) : ''?>" class="required numInputOnly min0 max59 checkdate_to<?php echo $count; ?>" title="終了時刻" onKeyDown="return checkNum()">分</td>
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
if(isset($plan['sagyoniyo'])){
echo $plan['sagyoniyo'];
}else{
echo $sagyoniyo;
}
 ?>

</td>
</tr>
<tr>
<td style="padding-left:10px">
その他<input type="text" style="width:120px;" maxlength="256" name="sntsagyo_<?php echo $count; ?>" id="sntsagyo_<?php echo $count; ?>" value="<?php echo isset($plan['sntsagyo']) ? $plan['sntsagyo'] : ''?>">
</td>
</tr>
</table>
</td>
<td>
<table>
<tr>
<td>結果情報</td>
</tr>
<tr>
<td rowspan="2">
<textarea style="height:100px; width:500px" name="kekka_<?php echo $count; ?>" id="kekka_<?php echo $count; ?>" value="">
<?php echo isset($plan['kekka']) ? $plan['kekka'] : ''?>
</textarea>
</td>
</tr>
</table>
</td>

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
