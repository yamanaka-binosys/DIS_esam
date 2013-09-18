<?php
log_message('debug',"===== Start new_tenpo.php =====");
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
<option value="srntb120" selected>店舗</option>
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
<td><label> スケジュール終了日 </label>
<input type="text" style="width: 80px;ime-mode: disabled;" name="deadline_day_<?php echo $count; ?>" id="deadline_day_<?php echo $count; ?>" value="<?php echo isset($plan['deadline_day']) ? date_format(date_create($plan['deadline_day']), 'Y/m/d'):'';?>" class="cal required" title="スケジュール終了日">
</td>
<td style="padding-left: 10px">
<input type="radio" name="hkubun_<?php echo $count; ?>" id="hkubun_<?php echo $count; ?>" value="1" <?php if(isset($plan['hkubun']) && $plan['hkubun'] === '1') echo " checked "; ?> onclick="check_radio('<?php echo $count; ?>')" class="checked_item1<?php echo $count; ?>"> 毎月
<input type="text" style="width: 20px;ime-mode: disabled;" name="designated_day_<?php echo $count; ?>" id="designated_day_<?php echo $count; ?>" value="<?php echo isset($plan['designated_day']) ? $plan['designated_day'] : ''?>" maxlength="2" class="checked_item1<?php echo $count; ?> required c_group"  title="毎月の予定日" />日
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

<table>
<tr>
<td colspan="3">
<table style="border:1px #000000 solid">
<tr>
<td>

<table style="padding-top: 0;">
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
<tr><td colspan="2">販売店 店舗名</td></tr>
<tr>
<td></td>
<td>
<input type="hidden" name="aiteskcd_<?php echo $count; ?>" id="aiteskcd_<?php echo $count; ?>" value="<?php echo isset($plan['aiteskcd']) ? $plan['aiteskcd'] : ''?>" class="required" title="販売店 店舗名">
<input type="text" style="width:148px;" maxlength="256" name="aitesknm_<?php echo $count; ?>" id="aitesknm_<?php echo $count; ?>" value="<?php echo isset($plan['aitesknm']) ? $plan['aitesknm'] : ''?>" readonly="readonly">
<input type="button" value="選択" onclick="select_client('<?php echo $count; ?>','<?php echo MY_SELECT_CLIENT_MAKER ?>','<?php echo $base_url ?>')">
</td>
</tr>
<tr>
<td colspan="2">販売店ランク
<input type="text" style="width: 20px;" name="aiteskrank_<?php echo $count; ?>" id="aiteskrank_<?php echo $count; ?>" value="<?php echo isset($plan['aiteskrank']) ? $plan['aiteskrank'] : ''?>" readonly="readonly">
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
<input type="text" style="width:290px;"  maxlength="256" name="doukounm01_<?php echo $count; ?>" id="doukounm01_<?php echo $count; ?>" value="<?php echo isset($plan['doukounm01']) ? $plan['doukounm01'] : ''?>">
</td>
</tr>
</table>

</td>
</tr>

</table>

</td>
</tr>

<tr>
<td></td>
</tr>
<tr>
<td colspan="3">
<table style="height:200px; width:800px">
<tr>
<td>
<table style="border:1px #000000 solid; width:300px">
<tr>
<td colspan="5" style="height:20px">店舗商談</td>
</tr>
<tr>
<td style="height:24px"></td>

<td style="height:24px"><input type="checkbox" name="ktd_johosusu_<?php echo $count; ?>" id="ktd_johosusu_<?php echo $count; ?>"
<?php if(isset($plan['ktd_johosusu']) && $plan['ktd_johosusu'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">情報収集</td>
<?php
if($plan['vi_ktd_tnpyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi01_".$count."\" id=\"ktd_tnpyobi01_".$count."\" ";
	if(isset($plan['ktd_tnpyobi01']) && $plan['ktd_tnpyobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_tnpyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_johoanai_<?php echo $count; ?>" id="ktd_johoanai_<?php echo $count; ?>"
<?php if(isset($plan['ktd_johoanai']) && $plan['ktd_johoanai'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">商品情報案内</td>
<?php
if($plan['vi_ktd_tnpyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi02_".$count."\" id=\"ktd_tnpyobi02_".$count."\" ";
	if(isset($plan['ktd_tnpyobi02']) && $plan['ktd_tnpyobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_tnpyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_tnkikosyo_<?php echo $count; ?>" id="ktd_tnkikosyo_<?php echo $count; ?>"
<?php if(isset($plan['ktd_tnkikosyo']) && $plan['ktd_tnkikosyo'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">展開場所・ｱｳﾄ展開交渉</td>
<?php
if($plan['vi_ktd_tnpyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi03_".$count."\" id=\"ktd_tnpyobi03_".$count."\" ";
	if(isset($plan['ktd_tnpyobi03']) && $plan['ktd_tnpyobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_tnpyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_suisnhanbai_<?php echo $count; ?>" id="ktd_suisnhanbai_<?php echo $count; ?>"
<?php if(isset($plan['ktd_suisnhanbai']) && $plan['ktd_suisnhanbai'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">推奨販売交渉</td>
<?php
if($plan['vi_ktd_tnpyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi04_".$count."\" id=\"ktd_tnpyobi04_".$count."\" ";
	if(isset($plan['ktd_tnpyobi04']) && $plan['ktd_tnpyobi04'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_tnpyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_jyutyu_<?php echo $count; ?>" id="ktd_jyutyu_<?php echo $count; ?>"
<?php if(isset($plan['ktd_jyutyu']) && $plan['ktd_jyutyu'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">受注促進</td>
<?php
if($plan['vi_ktd_tnpyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi05_".$count."\" id=\"ktd_tnpyobi05_".$count."\" ";
	if(isset($plan['ktd_tnpyobi05']) && $plan['ktd_tnpyobi05'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_tnpyobi05']['itemdspname']."</td>\n";
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
</table>

</td>
<td>

<table style="border:1px #000000 solid; width:300px">
<tr>
<td colspan="5" style="height:20px">店内作業</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_satuei_<?php echo $count; ?>" id="ktd_satuei_<?php echo $count; ?>"
<?php if(isset($plan['ktd_satuei']) && $plan['ktd_satuei'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">売場撮影</td>
<td style="height:24px"><input type="checkbox" name="ktd_beta_<?php echo $count; ?>" id="ktd_beta_<?php echo $count; ?>"
<?php if(isset($plan['ktd_beta']) && $plan['ktd_beta'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ベタ付け</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_mente_<?php echo $count; ?>" id="ktd_mente_<?php echo $count; ?>"
<?php if(isset($plan['ktd_mente']) && $plan['ktd_mente'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">売場メンテナンス</td>
<?php
if($plan['vi_ktd_sdnigiyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi01_".$count."\" id=\"ktd_sdnigiyobi01_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi01']) && $plan['ktd_sdnigiyobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_sdnigiyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_zaiko_<?php echo $count; ?>" id="ktd_zaiko_<?php echo $count; ?>"
<?php if(isset($plan['ktd_zaiko']) && $plan['ktd_zaiko'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">在庫確認</td>
<?php
if($plan['vi_ktd_sdnigiyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi02_".$count."\" id=\"ktd_sdnigiyobi02_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi02']) && $plan['ktd_sdnigiyobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_sdnigiyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_hoju_<?php echo $count; ?>" id="ktd_hoju_<?php echo $count; ?>"
<?php if(isset($plan['ktd_hoju']) && $plan['ktd_hoju'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">商品補充</td>
<?php
if($plan['vi_ktd_sdnigiyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi03_".$count."\" id=\"ktd_sdnigiyobi03_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi03']) && $plan['ktd_sdnigiyobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_sdnigiyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_hanskseti_<?php echo $count; ?>" id="ktd_hanskseti_<?php echo $count; ?>"
<?php if(isset($plan['ktd_hanskseti']) && $plan['ktd_hanskseti'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">販促物の設置</td>
<?php
if($plan['vi_ktd_sdnigiyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi04_".$count."\" id=\"ktd_sdnigiyobi04_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi04']) && $plan['ktd_sdnigiyobi04'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_sdnigiyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_yamazumi_<?php echo $count; ?>" id="ktd_yamazumi_<?php echo $count; ?>"
<?php if(isset($plan['ktd_yamazumi']) && $plan['ktd_yamazumi'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">山積み</td>
<?php
if($plan['vi_ktd_sdnigiyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi05_".$count."\" id=\"ktd_sdnigiyobi05_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi05']) && $plan['ktd_sdnigiyobi05'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_sdnigiyobi05']['itemdspname']."</td>\n";
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
</table>

</td>
<td>

<table style="border:1px #000000 solid; width:150px">
<tr>
<td colspan="5" style="height:20px">その他</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="mr_<?php echo $count; ?>" id="mr_<?php echo $count; ?>"
<?php if(isset($plan['mr']) && $plan['mr'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">競合店調査（MR）</td>
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
</td>
</tr>
<tr><td></td></tr>
<tr>
<td colspan="3">
<table style="border:1px #000000 solid; width:300px">
<tr><td colspan="5" style="height:20px">カテゴリー</td></tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_tessue_<?php echo $count; ?>" id="cte_tessue_<?php echo $count; ?>"
<?php if(isset($plan['cte_tessue']) && $plan['cte_tessue'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ティシュー</td>
<td style="height:24px"><input type="checkbox" name="cte_silver_<?php echo $count; ?>" id="cte_silver_<?php echo $count; ?>"
<?php if(isset($plan['cte_silver']) && $plan['cte_silver'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">シルバー</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_toilet_<?php echo $count; ?>" id="cte_toilet_<?php echo $count; ?>"
<?php if(isset($plan['cte_toilet']) && $plan['cte_toilet'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">トイレット</td>
<td style="height:24px"><input type="checkbox" name="cte_mask_<?php echo $count; ?>" id="cte_mask_<?php echo $count; ?>"
<?php if(isset($plan['cte_mask']) && $plan['cte_mask'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">マスク</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_kitchen_<?php echo $count; ?>" id="cte_kitchen_<?php echo $count; ?>"
<?php if(isset($plan['cte_kitchen']) && $plan['cte_kitchen'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">キッチン</td>
<td style="height:24px"><input type="checkbox" name="cte_pet_<?php echo $count; ?>" id="cte_pet_<?php echo $count; ?>"
<?php if(isset($plan['cte_pet']) && $plan['cte_pet'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ペット</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_wipe_<?php echo $count; ?>" id="cte_wipe_<?php echo $count; ?>"
<?php if(isset($plan['cte_wipe']) && $plan['cte_wipe'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ワイプ</td>
<?php
if($plan['vi_cte_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"cte_yobi01_".$count."\" id=\"cte_yobi01_".$count."\" ";
	if(isset($plan['cte_yobi01']) && $plan['cte_yobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_cte_yobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_baby_<?php echo $count; ?>" id="cte_baby_<?php echo $count; ?>"
<?php if(isset($plan['cte_baby']) && $plan['cte_baby'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ベビー</td>
<?php
if($plan['vi_cte_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"cte_yobi02_".$count."\" id=\"cte_yobi02_".$count."\" ";
	if(isset($plan['cte_yobi02']) && $plan['cte_yobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_cte_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_feminine_<?php echo $count; ?>" id="cte_feminine_<?php echo $count; ?>"
<?php if(isset($plan['cte_feminine']) && $plan['cte_feminine'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">フェミニン</td>
<?php
if($plan['vi_cte_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"cte_yobi03_".$count."\" id=\"cte_yobi03_".$count."\" ";
	if(isset($plan['cte_yobi03']) && $plan['cte_yobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_cte_yobi03']['itemdspname']."</td>\n";
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
</table>
</td>
</tr>
<tr><td></td></tr>
<tr>
<td colspan="2">【作業予定】</td>
</tr>
<tr>
<td colspan="5">
<textarea rows="3" cols="80"  name="sagyo_yotei_<?php echo $count; ?>" id="sagyo_yotei_<?php echo $count; ?>"><?php echo isset($plan['sagyo_yotei']) ? $plan['sagyo_yotei'] : ''?></textarea>
</td>
</tr>
<tr><td></td></tr>
<tr>
<td colspan="5">
<label for="lavel1"></label>
</td>
</tr>
<tr>
<td colspan="5">
<label for="lavel1"></label>
</td>
</tr>
</table>
</td>
</tr>
</table>

</div>
