<?php
log_message('debug',"===== Start new_office.php =====");
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
<option value="srntb130" selected>代理店</option>
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
<input type="text" style="width: 80px;ime-mode: disabled;" name="deadline_day_<?php echo $count; ?>" id="deadline_day_<?php echo $count; ?>" value="<?php echo isset($plan['deadline_day']) ? date_format(date_create($plan['deadline_day']), 'Y/m/d'):'';?>" class="cal required " title="スケジュール終了日">
</td>
</tr>
</table>
    
<table>
<tr>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="1" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '1') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class="checked_item1<?php echo $count; ?>"> 毎月
<input type="text" style="width: 20px;ime-mode: disabled;" name="designated_day_<?php echo $count; ?>" id="designated_day_<?php echo $count; ?>" value="<?php echo isset($plan['designated_day']) ? $plan['designated_day'] : ''?>"  maxlength="2" onkeypress='if(event.keyCode<"0".charCodeAt(0) || "9".charCodeAt(0)<event.keyCode)return false;' class="checked_item1<?php echo $count; ?> required c_group" title="毎月の予定日"  />日
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="2" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '2') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')"> 月末
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="3" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '3') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class="checked_item2<?php echo $count; ?>"> 毎週
</td>
<td><input type="checkbox" name="designated_sun_<?php echo $count; ?>" id="designated_sun_<?php echo $count; ?>" <?php if(isset($plan['designated_sun']) && $plan['designated_sun'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group" title="曜日">日</td>
<td><input type="checkbox" name="designated_mon_<?php echo $count; ?>" id="designated_mon_<?php echo $count; ?>" <?php if(isset($plan['designated_mon']) && $plan['designated_mon'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group">月</td>
<td><input type="checkbox" name="designated_tues_<?php echo $count; ?>" id="designated_tues_<?php echo $count; ?>" <?php if(isset($plan['designated_tues']) && $plan['designated_tues'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group">火</td>
<td><input type="checkbox" name="designated_wed_<?php echo $count; ?>" id="designated_wed_<?php echo $count; ?>" <?php if(isset($plan['designated_wed']) && $plan['designated_wed'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group">水</td>
<td><input type="checkbox" name="designated_thurs_<?php echo $count; ?>" id="designated_thurs_<?php echo $count; ?>" <?php if(isset($plan['designated_thurs']) && $plan['designated_thurs'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group">木</td>
<td><input type="checkbox" name="designated_fri_<?php echo $count; ?>" id="designated_fri_<?php echo $count; ?>" <?php if(isset($plan['designated_fri']) && $plan['designated_fri'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group">金</td>
<td><input type="checkbox" name="designated_sat_<?php echo $count; ?>" id="designated_sat_<?php echo $count; ?>" <?php if(isset($plan['designated_sat']) && $plan['designated_sat'] === '1') echo " checked "; ?> disabled="disabled" class="checked_item2<?php echo $count; ?> checkbox c_group">土</td>
</tr>
</table>
</td>
</tr>
</table>


<br>
<table>
<tr>
<td colspan="3">

<table style="border:1px #000000 solid">
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
<td colspan="2">代理店本支店名</td>
</tr>
<tr>
<td></td>
<td>
<input type="hidden" name="aiteskcd_<?php echo $count; ?>" id="aiteskcd_<?php echo $count; ?>" value="<?php echo isset($plan['aiteskcd']) ? $plan['aiteskcd'] : ''?>" class="required" title="代理店本支店名">
<input type="text" style="width:148px;" maxlength="256" name="aitesknm_<?php echo $count; ?>" id="aitesknm_<?php echo $count; ?>" value="<?php echo isset($plan['aitesknm']) ? $plan['aitesknm'] : ''?>" readonly="readonly">
<input type="button" value="選択" onclick="select_client('<?php echo $count; ?>','<?php echo MY_SELECT_CLIENT_AGENCY ?>','<?php echo $base_url ?>')"></td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td colspan="2">ランク
<input type="text" style="width: 20px;" size="6" name="rnkcd_<?php echo $count; ?>" value="<?php echo isset($plan['aiteskrank']) ? $plan['aiteskrank'] : ''?>" readonly="readonly" />
</td>
</tr>
</table>

</td>
<td>

<table>
<tr>
<td colspan="2">面談者</td>
</tr>
<tr>
<td></td>
<td><input type="text" style="width:148px;" maxlength="256" name="mendannm01_<?php echo $count; ?>" id="mendannm01_<?php echo $count; ?>" value="<?php echo isset($plan['mendannm01']) ? $plan['mendannm01'] : ''?>"></td>
</tr>
<tr>
<td></td>
<td><input type="text" style="width:148px;" maxlength="256" name="mendannm02_<?php echo $count; ?>" id="mendannm02_<?php echo $count; ?>" value="<?php echo isset($plan['mendannm02']) ? $plan['mendannm02'] : ''?>"></td>
</tr>
<tr>
<td colspan="2">同行者</td>
</tr>
<tr>
<td></td>
<td>
<input type="text" style="width: 200px;" style="width:290px;" maxlength="256" name="doukounm01_<?php echo $count; ?>" id="doukounm01_<?php echo $count; ?>" value="<?php echo isset($plan['doukounm01']) ? $plan['doukounm01'] : ''?>">
</td>
</tr>
</table>

</td>
</tr>
</table>

<table>
<tr>
<td>
<table style="height:200px; width:800px">
<tr>
<td>【商談内容】</td>
</tr>
<tr>
<td>

<table style="border:1px #000000 solid; width:300px">
<tr>
<td colspan="5" style="height:20px">対代理店・対一般店</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_mitsumorifollow_<?php echo $count; ?>" id="sdn_mitsumorifollow_<?php echo $count; ?>"
<?php if(isset($plan['sdn_mitsumorifollow']) && $plan['sdn_mitsumorifollow'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">一般店見積り提示・商談フォロー</td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<!--
<?php
if($plan['vi_sdn_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi02_".$count."\" id=\"sdn_yobi02_".$count."\" ";
	if(isset($plan['sdn_yobi02']) && $plan['sdn_yobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
-->
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_syouhin_<?php echo $count; ?>" id="sdn_syouhin_<?php echo $count; ?>"
<?php if(isset($plan['sdn_syouhin']) && $plan['sdn_syouhin'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">商品案内</td>
<?php
if($plan['vi_sdn_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi03_".$count."\" id=\"sdn_yobi03_".$count."\" ";
	if(isset($plan['sdn_yobi03']) && $plan['sdn_yobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_yobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_kikaku_<?php echo $count; ?>" id="sdn_kikaku_<?php echo $count; ?>"
<?php if(isset($plan['sdn_kikaku']) && $plan['sdn_kikaku'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">企画案内</td>
<?php
if($plan['vi_sdn_yobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi04_".$count."\" id=\"sdn_yobi04_".$count."\" ";
	if(isset($plan['sdn_yobi04']) && $plan['sdn_yobi04'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_yobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_jiseki_<?php echo $count; ?>" id="sdn_jiseki_<?php echo $count; ?>"
<?php if(isset($plan['sdn_jiseki']) && $plan['sdn_jiseki'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">実績報告（経理・配荷）</td>
<?php
if($plan['vi_sdn_yobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi05_".$count."\" id=\"sdn_yobi05_".$count."\" ";
	if(isset($plan['sdn_yobi05']) && $plan['sdn_yobi05'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_yobi05']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<?php
if($plan['vi_sdn_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi01_".$count."\" id=\"sdn_yobi01_".$count."\" ";
	if(isset($plan['sdn_yobi01']) && $plan['sdn_yobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_yobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<?php
if($plan['vi_sdn_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi02_".$count."\" id=\"sdn_yobi02_".$count."\" ";
	if(isset($plan['sdn_yobi02']) && $plan['sdn_yobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
</table>

</td>
<td>

<table style="border:1px #000000 solid; width:300px">
<tr>
<td colspan="5" style="height:20px">対管理販売店</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_mitsumori_<?php echo $count; ?>" id="sdn_mitsumori_<?php echo $count; ?>"
<?php if(isset($plan['sdn_mitsumori']) && $plan['sdn_mitsumori'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">見積り提示</td>
<?php
if($plan['vi_sdn_kanriyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi02_".$count."\" id=\"sdn_kanriyobi02_".$count."\" ";
	if(isset($plan['sdn_kanriyobi02']) && $plan['sdn_kanriyobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_kanriyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_jizenutiawase_<?php echo $count; ?>" id="sdn_jizenutiawase_<?php echo $count; ?>"
<?php if(isset($plan['sdn_jizenutiawase']) && $plan['sdn_jizenutiawase'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">販売店商談事前打合せ</td>
<?php
if($plan['vi_sdn_kanriyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi03_".$count."\" id=\"sdn_kanriyobi03_".$count."\" ";
	if(isset($plan['sdn_kanriyobi03']) && $plan['sdn_kanriyobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_kanriyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_kikakujyoukyou_<?php echo $count; ?>" id="sdn_kikakujyoukyou_<?php echo $count; ?>"
<?php if(isset($plan['sdn_kikakujyoukyou']) && $plan['sdn_kikakujyoukyou'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">情報収集・企画導入状況確認</td>
<?php
if($plan['vi_sdn_kanriyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi04_".$count."\" id=\"sdn_kanriyobi04_".$count."\" ";
		if(isset($plan['sdn_kanriyobi04']) && $plan['sdn_kanriyobi04'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_kanriyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<?php
if($plan['vi_sdn_kanriyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi01_".$count."\" id=\"sdn_kanriyobi01_".$count."\" ";
		if(isset($plan['sdn_kanriyobi01']) && $plan['sdn_kanriyobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_kanriyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
<?php
if($plan['vi_sdn_kanriyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi05_".$count."\" id=\"sdn_kanriyobi05_".$count."\" ";
		if(isset($plan['sdn_kanriyobi05']) && $plan['sdn_kanriyobi05'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_kanriyobi05']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
</table>

</td>
<td>

<table style="border:1px #000000 solid; width:150px">
<tr>
<td colspan="5" style="height:20px">その他</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:20px"><input type="checkbox" name="sdn_logistics_<?php echo $count; ?>" id="sdn_logistics_<?php echo $count; ?>"
<?php if(isset($plan['sdn_logistics']) && $plan['sdn_logistics'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">受発注・物流関連</td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:20px"><input type="checkbox" name="sdn_torikmi_<?php echo $count; ?>" id="sdn_torikmi_<?php echo $count; ?>"
<?php if(isset($plan['sdn_torikmi']) && $plan['sdn_torikmi'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">取組会議</td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
</table>

</td>
</tr>
</table>

<table>
<tr><td></td></tr>
<tr>
<td colspan="2">【商談予定】</td>
</tr>
<tr>
<td colspan="5">
<textarea style="ime-mode: active;" rows="3" cols="80" name="sdn_yotei_<?php echo $count; ?>" id="sdn_yotei_<?php echo $count; ?>"><?php echo isset($plan['sdn_yotei']) ? $plan['sdn_yotei'] : ''?></textarea>
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
log_message('debug',"===== End new_dairi.php =====");
?>
