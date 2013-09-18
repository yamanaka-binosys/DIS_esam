<?php
log_message('debug',"===== Start new_honbu.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');

?>
<div id="action_<?php echo $count; ?>" class="action_container">
<br><a class="anchor" name="<?php echo isset($plan['jyohonum']) &&  $plan['jyohonum'] != 'XXXXXXXXX' ? $plan['jyohonum'] : (isset($plan['uid']) ? $plan['uid'] : $uid) ?>" href="#"></a>
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
<option value="srntb110" selected>販売店本部</option>
</select>
</td>
<td>
<input type="button" name="action_delete_<?php echo $count; ?>" id="action_delete_<?php echo $count; ?>" value="削除" onclick="javascript=delete_action('<?php echo $base_url; ?>','<?php echo $count; ?>')">
<input type="text" style="width: 80px;" name="move_copy_day_<?php echo $count; ?>" id="move_copy_day_<?php echo $count; ?>" value="<?php echo isset($plan['ymd'])?date_format(date_create($plan['ymd']), 'Y/m/d'):'';?>" class="cal required<?php echo $count; ?> mc_group " title="移動・コピー日付">
<input type="button" name="action_move_<?php echo $count; ?>" id="action_move_<?php echo $count; ?>" value="移動" onclick="javascript=move_action('<?php echo $count; ?>')">
<input type="button" name="action_copy_<?php echo $count; ?>" id="action_copy_<?php echo $count; ?>" value="コピー" onclick="javascript=copy_action('<?php echo $count; ?>')">
</td>
</tr>
</table>
<input type="hidden" name="uid_<?php echo $count; ?>" id="uid_<?php echo $count; ?>" value="<?php echo isset($plan['jyohonum']) &&  $plan['jyohonum'] != 'XXXXXXXXX' ? $plan['jyohonum'] : (isset($plan['uid']) ? $plan['uid'] : $uid) ?>"/>
<input type="hidden" name="jyohonum_<?php echo $count; ?>" id="jyohonum_<?php echo $count; ?>" value="<?php echo isset($plan['jyohonum']) ? $plan['jyohonum'] : ''?>" >
<input type="hidden" name="edbn_<?php echo $count; ?>" id="edbn_<?php echo $count; ?>" value="<?php echo isset($plan['edbn']) ? $plan['edbn'] : ''?>" >
<input type="hidden" class="recode_flg" name="recode_flg_<?php echo $count; ?>" id="recode_flg_<?php echo $count; ?>" value="<?php echo isset($plan['recode_flg']) ? $plan['recode_flg'] : ''?>" >

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
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 0, 2) : ''?>" class="required numInputOnly" title="開始時刻">時</td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($plan['sthm']) ? substr($plan['sthm'], 2, 2) : ''?>" class="required numInputOnly" title="開始時刻">分</td>
</tr>
<tr><td colspan="4">終了時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 0, 2) : ''?>" class="required numInputOnly" title="終了時刻">時</td>
<td><input type="text" style="width:20px;ime-mode: disabled;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($plan['edhm']) ? substr($plan['edhm'], 2, 2) : ''?>" class="required numInputOnly" title="終了時刻">分</td>
</tr>
</table>

</td>
<td>

<table>
<tr><td colspan="2">販売店名</td></tr>
<tr>
<td></td>
<td>
<input type="hidden" name="aiteskcd_<?php echo $count; ?>" id="aiteskcd_<?php echo $count; ?>" value="<?php echo isset($plan['aiteskcd']) ? $plan['aiteskcd'] : ''?>" class="required" title="販売店名">
<input type="text" style="width:148px;" maxlength="256" name="aitesknm_<?php echo $count; ?>" id="aitesknm_<?php echo $count; ?>" value="<?php echo isset($plan['aitesknm']) ? $plan['aitesknm'] : ''?>" readonly="readonly">
<input type="button" value="選択" onclick="select_client('<?php echo $count; ?>','<?php echo MY_SELECT_CLIENT_HEAD ?>','<?php echo $base_url ?>')">
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
<input type="text" style="width:290px;" maxlength="256" name="doukounm01_<?php echo $count; ?>" id="doukounm01_<?php echo $count; ?>" value="<?php echo isset($plan['doukounm01']) ? $plan['doukounm01'] : ''?>">
</td>
</tr>
</table>

</td>
<td>

<table>
<tr><td colspan="2">商談場所</td></tr>
<tr><td><input type="text" maxlength="256" style="width: 120px;" name="basyo_<?php echo $count; ?>" id="basyo_<?php echo $count; ?>" value="<?php echo isset($plan['basyo']) ? $plan['basyo'] : ''?>" /></td></tr>
</table>
</td>
</tr>
</table>

</td>
</tr>

<tr>
<td colspan="3">
<table style="height:200px; width:800px; margin-left:-3px;">
<tr>
<td>【商談内容】</td>
</tr>
<tr>
<td>

<table style="border:1px #000000 solid;">
<tr>
<td>

<table style="border:1px #000000 solid; width:300px">
<tr><td colspan="5" style="height:20px">月次商談</td></tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_mitumori_<?php echo $count; ?>" id="chk_sdn_mitumori_<?php echo $count; ?>"
<?php if(isset($plan['sdn_mitumori']) && $plan['sdn_mitumori'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">見積り提示</td>
<?php
if($plan['vi_sdn_gtjyobi01']['dispflg'] === '1' ){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi01_".$count."\" id=\"sdn_gtjyobi01_".$count."\" ";
	if(isset($plan['sdn_gtjyobi01']) && $plan['sdn_gtjyobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_gtjyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_siyokaknin_<?php echo $count; ?>" id="sdn_siyokaknin_<?php echo $count; ?>"
<?php if(isset($plan['sdn_siyokaknin']) && $plan['sdn_siyokaknin'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">採用企画の確認</td>
<?php
if($plan['vi_sdn_gtjyobi02']['dispflg'] === '1' ){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi02_".$count."\" id=\"sdn_gtjyobi02_".$count."\" ";
	if(isset($plan['sdn_gtjyobi02']) && $plan['sdn_gtjyobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_gtjyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_hnbikekaku_<?php echo $count; ?>" id="sdn_hnbikekaku_<?php echo $count; ?>"
<?php if(isset($plan['sdn_hnbikekaku']) && $plan['sdn_hnbikekaku'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">販売計画</td>
<?php
if($plan['vi_sdn_gtjyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi03_".$count."\" id=\"sdn_gtjyobi03_".$count."\"";
	if(isset($plan['sdn_gtjyobi03']) && $plan['sdn_gtjyobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_gtjyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_claim_<?php echo $count; ?>" id="sdn_hnbikekaku_<?php echo $count; ?>"
<?php if(isset($plan['sdn_claim']) && $plan['sdn_claim'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">クレーム対応</td>
<?php
if($plan['vi_sdn_gtjyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi04_".$count."\" id=\"sdn_gtjyobi04_".$count."\"";
	if(isset($plan['sdn_gtjyobi04']) && $plan['sdn_gtjyobi04'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_gtjyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_uribatan_<?php echo $count; ?>" id="sdn_uribatan_<?php echo $count; ?>"
<?php if(isset($plan['sdn_uribatan']) && $plan['sdn_uribatan'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">売場提案</td>
<?php
if($plan['vi_sdn_gtjyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi05_".$count."\" id=\"sdn_gtjyobi05_".$count."\"";
	if(isset($plan['sdn_gtjyobi05']) && $plan['sdn_gtjyobi05'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_gtjyobi05']['itemdspname']."</td>\n";
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
<tr><td colspan="5" style="height:20px">カテゴリー</td></tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_tessue_<?php echo $count; ?>" id="sdn_cte_tessue_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_tessue']) && $plan['sdn_cte_tessue'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ティシュー</td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_pet_<?php echo $count; ?>" id="sdn_cte_pet_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_pet']) && $plan['sdn_cte_pet'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ペット</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_toilet_<?php echo $count; ?>" id="sdn_cte_toilet_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_toilet']) && $plan['sdn_cte_toilet'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">トイレット</td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_silver_<?php echo $count; ?>" id="sdn_cte_silver_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_silver']) && $plan['sdn_cte_silver'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">シルバー</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_kitchen_<?php echo $count; ?>" id="sdn_cte_kitchen_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_kitchen']) && $plan['sdn_cte_kitchen'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">キッチン</td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_mask_<?php echo $count; ?>" id="sdn_cte_mask_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_mask']) && $plan['sdn_cte_mask'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">マスク</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_wipe_<?php echo $count; ?>" id="sdn_cte_wipe_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_wipe']) && $plan['sdn_cte_wipe'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ワイプ</td>
<?php
if($plan['vi_sdn_cte_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_cte_yobi01_".$count."\" id=\"sdn_cte_yobi01_".$count."\"";
	if(isset($plan['sdn_cte_yobi01']) && $plan['sdn_cte_yobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_cte_yobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_baby_<?php echo $count; ?>" id="sdn_cte_baby_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_baby']) && $plan['sdn_cte_baby'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ベビー</td>
<?php
if($plan['vi_sdn_cte_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_cte_yobi02_".$count."\" id=\"sdn_cte_yobi02_".$count."\"";
	if(isset($plan['sdn_cte_yobi02']) && $plan['sdn_cte_yobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_cte_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_feminine_<?php echo $count; ?>" id="sdn_cte_feminine_<?php echo $count; ?>"
<?php if(isset($plan['sdn_cte_feminine']) && $plan['sdn_cte_feminine'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">フェミニン</td>
<?php
if($plan['vi_sdn_cte_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_cte_yobi03_".$count."\" id=\"sdn_cte_yobi03_".$count."\"";
	if(isset($plan['sdn_cte_yobi03']) && $plan['sdn_cte_yobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_cte_yobi03']['itemdspname']."</td>\n";
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
</table>


</td>
<td>

<table style="border:1px #000000 solid; width:150px">
<tr>
<td colspan="5" style="height:20px"><input type="checkbox" name="other_<?php echo $count; ?>" id="other_<?php echo $count; ?>"
<?php if(isset($plan['other']) && $plan['other'] === '1') echo " checked "; ?>
>その他</td>
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

<tr>
<td colspan="3">
<table style="height:200px; width:800px">
<tr>
<td>

<table style="border:1px #000000 solid; margin-left:-3px;">
<tr>
<td>

<table style="border:1px #000000 solid; width:300px">
<tr>
<td colspan="2" style="height:20px">半期提案</td>
<td colspan="3" style="height:20px">
	<input type="radio" name="sdn_hnktan_<?php echo $count; ?>" value="0"
	<?php if( ! (isset($plan['sdn_hnktan']) && $plan['sdn_hnktan'] === '1')) echo " checked "; ?>
	>今期
	<input type="radio" name="sdn_hnktan_<?php echo $count; ?>" value="1"
	<?php if(isset($plan['sdn_hnktan']) && $plan['sdn_hnktan'] === '1') echo " checked "; ?>
	>来期
</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_shohin_<?php echo $count; ?>" id="sdn_shohin_<?php echo $count; ?>"
<?php if(isset($plan['sdn_shohin']) && $plan['sdn_shohin'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">商品案内</td>
<td style="height:24px"><input type="checkbox" name="sdn_tnwrkeka_<?php echo $count; ?>" id="sdn_tnwrkeka_<?php echo $count; ?>"
<?php if(isset($plan['sdn_tnwrkeka']) && $plan['sdn_tnwrkeka'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">棚割結果確認</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_donyutan_<?php echo $count; ?>" id="sdn_donyutan_<?php echo $count; ?>"
<?php if(isset($plan['sdn_donyutan']) && $plan['sdn_donyutan'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">導入提案</td>
<?php
if($plan['vi_sdn_hnkyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi01_".$count."\" id=\"sdn_hnkyobi01_".$count."\"";
	if(isset($plan['sdn_hnkyobi01']) && $plan['sdn_hnkyobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_hnkyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_mdteian_<?php echo $count; ?>" id="sdn_mdteian_<?php echo $count; ?>"
<?php if(isset($plan['sdn_mdteian']) && $plan['sdn_mdteian'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">MD提案</td>
<?php
if($plan['vi_sdn_hnkyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi02_".$count."\" id=\"sdn_hnkyobi02_".$count."\"";
	if(isset($plan['sdn_hnkyobi02']) && $plan['sdn_hnkyobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_hnkyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_tanawari_<?php echo $count; ?>" id="sdn_tanawari_<?php echo $count; ?>"
<?php if(isset($plan['sdn_tanawari']) && $plan['sdn_tanawari'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">棚割提案</td>
<?php
if($plan['vi_sdn_hnkyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi03_".$count."\" id=\"sdn_hnkyobi03_".$count."\"";
	if(isset($plan['sdn_hnkyobi03']) && $plan['sdn_hnkyobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_hnkyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_tnwrbi_<?php echo $count; ?>" id="sdn_tnwrbi_<?php echo $count; ?>"
<?php if(isset($plan['sdn_tnwrbi']) && $plan['sdn_tnwrbi'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">販売店の棚割日情報</td>
<?php
if($plan['vi_sdn_hnkyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi04_".$count."\" id=\"sdn_hnkyobi04_".$count."\"";
	if(isset($plan['sdn_hnkyobi04']) && $plan['sdn_hnkyobi04'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_hnkyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_donyutume_<?php echo $count; ?>" id="sdn_donyutume_<?php echo $count; ?>"
<?php if(isset($plan['sdn_donyutume']) && $plan['sdn_donyutume'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">導入日の詰め</td>
<?php
if($plan['vi_sdn_hnkyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi05_".$count."\" id=\"sdn_hnkyobi05_".$count."\"";
	if(isset($plan['sdn_hnkyobi05']) && $plan['sdn_hnkyobi05'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_sdn_hnkyobi05']['itemdspname']."</td>\n";
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

<table style="border:1px #000000 solid; width:300px">
<tr><td colspan="5" style="height:20px">カテゴリー</td></tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_tessue_<?php echo $count; ?>" id="hnk_cte_tessue_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_tessue']) && $plan['hnk_cte_tessue'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ティシュー</td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_silver_<?php echo $count; ?>" id="hnk_cte_silver_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_silver']) && $plan['hnk_cte_silver'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">シルバー</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_toilet_<?php echo $count; ?>" id="hnk_cte_toilet_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_toilet']) && $plan['hnk_cte_toilet'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">トイレット</td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_mask_<?php echo $count; ?>" id="hnk_cte_mask_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_mask']) && $plan['hnk_cte_mask'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">マスク</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_kitchen_<?php echo $count; ?>" id="hnk_cte_kitchen_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_kitchen']) && $plan['hnk_cte_kitchen'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">キッチン</td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_pet_<?php echo $count; ?>" id="hnk_cte_pet_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_pet']) && $plan['hnk_cte_pet'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ペット</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_wipe_<?php echo $count; ?>" id="hnk_cte_wipe_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_wipe']) && $plan['hnk_cte_wipe'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ワイプ</td>
<?php
if($plan['vi_hnk_cte_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"hnk_cte_yobi01_".$count."\" id=\"hnk_cte_yobi01_".$count."\"";
	if(isset($plan['hnk_cte_yobi01']) && $plan['hnk_cte_yobi01'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_hnk_cte_yobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_baby_<?php echo $count; ?>" id="hnk_cte_baby_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_baby']) && $plan['hnk_cte_baby'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">ベビー</td>
<?php
if($plan['vi_hnk_cte_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"hnk_cte_yobi02_".$count."\" id=\"hnk_cte_yobi02_".$count."\"";
	if(isset($plan['hnk_cte_yobi02']) && $plan['hnk_cte_yobi02'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_hnk_cte_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_feminine_<?php echo $count; ?>" id="hnk_cte_feminine_<?php echo $count; ?>"
<?php if(isset($plan['hnk_cte_feminine']) && $plan['hnk_cte_feminine'] === '1') echo " checked "; ?>
></td>
<td style="height:24px">フェミニン</td>
<?php
if($plan['vi_hnk_cte_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"hnk_cte_yobi03_".$count."\" id=\"hnk_cte_yobi03_".$count."\"";
	if(isset($plan['hnk_cte_yobi03']) && $plan['hnk_cte_yobi03'] === '1') echo " checked ";
	echo "></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_hnk_cte_yobi03']['itemdspname']."</td>\n";
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
</table>

</td>
<td>

<table style="border:0px #000000 solid; width:150px">
<tr>
<td colspan="5" style="height:20px"></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>

<tr>
<td colspan="2">【商談予定】</td>
</tr>
<tr>
<td colspan="5">
<textarea rows="3" cols="80" name="sdn_yotei_<?php echo $count; ?>" id="sdn_yotei_<?php echo $count; ?>" ><?php echo isset($plan['sdn_yotei']) ? $plan['sdn_yotei'] : ''?></textarea>
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

<?php
log_message('debug',"===== End new_honbu.php =====");
?>
