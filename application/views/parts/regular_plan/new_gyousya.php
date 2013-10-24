<?php
log_message('debug',"===== Start regular_plan/new_gyousya.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
?>

<div id="action_<?php echo $count; ?>">
<br>
<table style="border:2px solid #000000; width:830px">
<tr>
<td>
<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>">
<option value="srntb160" selected>業者</option>
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
<td><label> スケジュール期間 </label>
<input type="text" style="width: 80px;ime-mode: disabled;" name="schedule_start_day_<?php echo $count; ?>" id="schdule_start_day_<?php echo $count; ?>" value="<?php echo isset($plan['schdule_start_day']) ? date_format(date_create($plan['schdule_start_day']), 'Y/m/d'):'';?>" class="cal required " title="スケジュール開始日">
</td>
<td><label> ～ </label>
<input type="text" style="width: 80px;ime-mode: disabled;" name="deadline_day_<?php echo $count; ?>" id="deadline_day_<?php echo $count; ?>" value="<?php echo isset($plan['deadline_day']) ? date_format(date_create($plan['deadline_day']), 'Y/m/d'):'';?>" class="cal required" title="スケジュール終了日">
</td>
</tr>
</table>
<table>
<tr>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="1" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '1') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class=""> 毎月
<input type="text" style="width: 20px;ime-mode: disabled;" name="designated_day_<?php echo $count; ?>" id="designated_day_<?php echo $count; ?>" value="<?php echo isset($plan['designated_day']) ? $plan['designated_day'] : ''?>" maxlength="2" class="checked_item1<?php echo $count; ?> required c_group"  title="毎月の予定日">日
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="2" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '2') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')"> 月末
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="3" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '3') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class="checked_item2<?php echo $count; ?>"> 毎週
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

<table style="padding-top: 0;">
<tr><td colspan="4">開始時刻</td>        
    <td style="padding-left:30px;">企業名</td>
</tr>

<tr>
<td></td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 0, 2) : ''?>" class="required numInputOnly min1 max48 checkdate_from<?php echo $count; ?>" title="開始時刻" onKeyDown="return checkNum()" >時</td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 2, 2) : ''?>" class="required numInputOnly min0 max59 checkdate_from<?php echo $count; ?>" title="開始時刻" onKeyDown="return checkNum()">分</td>
<td></td>
<td ><input name="gyoshanm_<?php echo $count; ?>" id="gyoshanm_<?php echo $count; ?>" type="text" style="margin-left:30px; width:200px;" title="企業名称" value="<?php echo isset($plan['gyoshanm']) ? $plan['gyoshanm'] : ""; ?>"></td>
</tr>
<tr><td colspan="4">終了時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 0, 2) : ''?>" class="required numInputOnly min1 max48 checkdate_to<?php echo $count; ?>" title="終了時刻" onKeyDown="return checkNum()">時</td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 2, 2) : ''?>" class="required numInputOnly min0 max59 checkdate_to<?php echo $count; ?>" title="終了時刻" onKeyDown="return checkNum()">分</td>
</tr>
</table>

<table>
<tr>
<td>メモ</td>
</tr>
<tr>
<td>
<textarea style="height: 120px;width: 650px;ime-mode: active;" name="memo_<?php echo $count; ?>" id="memo_<?php echo $count; ?>"><?php echo isset($plan['memo']) ? $plan['memo'] : ''?></textarea>
</td>
</tr>

</table>
</td>
</tr>

</table>

</div>
