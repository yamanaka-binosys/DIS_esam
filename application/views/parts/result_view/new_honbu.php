<?php
log_message('debug',"===== Start new_honbu.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
if(isset($write_flg)){
	log_message('debug',"\$write_flg = $write_flg");
}else{
	log_message('debug',"write_flg is nothing");
}
?>

<div id="action_<?php echo $count; ?>" >
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
<td>
<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>" id="action_type_<?php echo $count; ?>">
<option value="srntb010" selected>販売店本部</option>
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
<td colspan="3">
<table style="border:1px #000000 solid">
<tr>
<td>

<table style="padding-top: 0;">
<tr><td colspan="4">開始時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="sth_<?php echo $count; ?>" id="sth_<?php echo $count; ?>" value="<?php echo isset($result['sthm']) ? substr($result['sthm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="stm_<?php echo $count; ?>" id="stm_<?php echo $count; ?>" value="<?php echo isset($result['sthm']) ? substr($result['sthm'], 2, 2) : ''?>" readonly="readonly">分</td>
</tr>
<tr><td colspan="4">終了時刻</td></tr>
<tr>
<td></td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>" value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>" value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 2, 2) : ''?>" readonly="readonly">分</td>
</tr>
</table>

</td>
<td>

<table>
<tr><td colspan="2">販売店名</td></tr>
<tr>
<td></td>
<td>
<input type="hidden" name="aiteskcd_<?php echo $count; ?>" id="aiteskcd_<?php echo $count; ?>" value="<?php echo isset($result['aiteskcd']) ? $result['aiteskcd'] : ''?>">
<input type="text" style="width:148px;" maxlength="256" name="aitesknm_<?php echo $count; ?>" id="aitesknm_<?php echo $count; ?>" value="<?php echo isset($result['aitesknm']) ? $result['aitesknm'] : ''?>" readonly="readonly">
<input type="button" value="選択">
</td>
</tr>
<tr>
<td colspan="2">販売店ランク
<input type="text" style="width: 20px;" name="aiteskrank_<?php echo $count; ?>" id="aiteskrank_<?php echo $count; ?>" value="<?php echo isset($result['aiteskrank']) ? $result['aiteskrank'] : ''?>" readonly="readonly">
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
<td><input type="text" style="width:148px;" maxlength="256" name="mendannm01_<?php echo $count; ?>" id="mendannm01_<?php echo $count; ?>" value="<?php echo isset($result['mendannm01']) ? $result['mendannm01'] : ''?>" readonly="readonly"></td>
</tr>
<tr>
<td></td>
<td><input type="text" style="width:148px;" maxlength="256" name="mendannm02_<?php echo $count; ?>" id="mendannm02_<?php echo $count; ?>" value="<?php echo isset($result['mendannm02']) ? $result['mendannm02'] : ''?>" readonly="readonly"></td>
</tr>
<tr>
<td colspan="2">同行者</td>
</tr>
<tr>
<td></td>
<td>
<input type="text" style="width:290px;" maxlength="256" name="doukounm01_<?php echo $count; ?>" id="doukounm01_<?php echo $count; ?>" value="<?php echo isset($result['doukounm01']) ? $result['doukounm01'] : ''?>" readonly="readonly">
</td>
</tr>
</table>

</td>
<td>

<table>
<tr><td colspan="2">商談場所</td></tr>
<tr><td><input type="text" maxlength="256" style="width: 120px;" name="basyo_<?php echo $count; ?>" id="basyo_<?php echo $count; ?>" value="<?php echo isset($result['basyo']) ? $result['basyo'] : ''?>" readonly="readonly"></td></tr>
</table>
</td>
</tr>
</table>

</td>
</tr>

<tr>
<td colspan="3">
<table style="height:200px; width:800px">
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
<?php if(isset($result['sdn_mitumori']) && $result['sdn_mitumori'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">見積り提示</td>
<?php
if($result['vi_sdn_gtjyobi01']['dispflg'] === '1' ){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi01_".$count."\" id=\"sdn_gtjyobi01_".$count."\" ";
	if(isset($result['sdn_gtjyobi01']) && $result['sdn_gtjyobi01'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_gtjyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_siyokaknin_<?php echo $count; ?>" id="sdn_siyokaknin_<?php echo $count; ?>"
<?php if(isset($result['sdn_siyokaknin']) && $result['sdn_siyokaknin'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">採用企画の確認</td>
<?php
if($result['vi_sdn_gtjyobi02']['dispflg'] === '1' ){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi02_".$count."\" id=\"sdn_gtjyobi02_".$count."\" ";
	if(isset($result['sdn_gtjyobi02']) && $result['sdn_gtjyobi02'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_gtjyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_hnbikekaku_<?php echo $count; ?>" id="sdn_hnbikekaku_<?php echo $count; ?>"
<?php if(isset($result['sdn_hnbikekaku']) && $result['sdn_hnbikekaku'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">販売計画</td>
<?php
if($result['vi_sdn_gtjyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi03_".$count."\" id=\"sdn_gtjyobi03_".$count."\"";
	if(isset($result['sdn_gtjyobi03']) && $result['sdn_gtjyobi03'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_gtjyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_claim_<?php echo $count; ?>" id="sdn_hnbikekaku_<?php echo $count; ?>"
<?php if(isset($result['sdn_claim']) && $result['sdn_claim'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">クレーム対応</td>
<?php
if($result['vi_sdn_gtjyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi04_".$count."\" id=\"sdn_gtjyobi04_".$count."\"";
	if(isset($result['sdn_gtjyobi04']) && $result['sdn_gtjyobi04'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_gtjyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_uribatan_<?php echo $count; ?>" id="sdn_uribatan_<?php echo $count; ?>"
<?php if(isset($result['sdn_uribatan']) && $result['sdn_uribatan'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">売場提案</td>
<?php
if($result['vi_sdn_gtjyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_gtjyobi05_".$count."\" id=\"sdn_gtjyobi05_".$count."\"";
	if(isset($result['sdn_gtjyobi05']) && $result['sdn_gtjyobi05'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_gtjyobi05']['itemdspname']."</td>\n";
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
<?php if(isset($result['sdn_cte_tessue']) && $result['sdn_cte_tessue'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ティシュー</td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_pet_<?php echo $count; ?>" id="sdn_cte_pet_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_pet']) && $result['sdn_cte_pet'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ペット</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_toilet_<?php echo $count; ?>" id="sdn_cte_toilet_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_toilet']) && $result['sdn_cte_toilet'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">トイレット</td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_silver_<?php echo $count; ?>" id="sdn_cte_silver_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_silver']) && $result['sdn_cte_silver'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">シルバー</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_kitchen_<?php echo $count; ?>" id="sdn_cte_kitchen_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_kitchen']) && $result['sdn_cte_kitchen'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">キッチン</td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_mask_<?php echo $count; ?>" id="sdn_cte_mask_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_mask']) && $result['sdn_cte_mask'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">マスク</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_wipe_<?php echo $count; ?>" id="sdn_cte_wipe_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_wipe']) && $result['sdn_cte_wipe'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ワイプ</td>
<?php
if($result['vi_sdn_cte_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_cte_yobi01_".$count."\" id=\"sdn_cte_yobi01_".$count."\"";
	if(isset($result['sdn_cte_yobi01']) && $result['sdn_cte_yobi01'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_cte_yobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_baby_<?php echo $count; ?>" id="sdn_cte_baby_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_baby']) && $result['sdn_cte_baby'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ベビー</td>
<?php
if($result['vi_sdn_cte_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_cte_yobi02_".$count."\" id=\"sdn_cte_yobi02_".$count."\"";
	if(isset($result['sdn_cte_yobi02']) && $result['sdn_cte_yobi02'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_cte_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_cte_feminine_<?php echo $count; ?>" id="sdn_cte_feminine_<?php echo $count; ?>"
<?php if(isset($result['sdn_cte_feminine']) && $result['sdn_cte_feminine'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">フェミニン</td>
<?php
if($result['vi_sdn_cte_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_cte_yobi03_".$count."\" id=\"sdn_cte_yobi03_".$count."\"";
	if(isset($result['sdn_cte_yobi03']) && $result['sdn_cte_yobi03'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_cte_yobi03']['itemdspname']."</td>\n";
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
<?php if(isset($result['other']) && $result['other'] === '1') echo " checked "; ?>
 disabled="true">その他</td>
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

<table style="border:1px #000000 solid;">
<tr>
<td>

<table style="border:1px #000000 solid; width:300px">
<tr>
<td colspan="2" style="height:20px">半期提案</td>
<td colspan="3" style="height:20px">
	<input type="radio" name="sdn_hnktan_<?php echo $count; ?>" value="0" 
	<?php if( ! (isset($result['sdn_hnktan']) && $result['sdn_hnktan'] === '1')) echo " checked "; ?>
	 disabled="true">今期
	<input type="radio" name="sdn_hnktan_<?php echo $count; ?>" value="1"
	<?php if(isset($result['sdn_hnktan']) && $result['sdn_hnktan'] === '1') echo " checked "; ?>
	 disabled="true">来期
</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_shohin_<?php echo $count; ?>" id="sdn_shohin_<?php echo $count; ?>"
<?php if(isset($result['sdn_shohin']) && $result['sdn_shohin'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">商品案内</td>
<td style="height:24px"><input type="checkbox" name="sdn_tnwrkeka_<?php echo $count; ?>" id="sdn_tnwrkeka_<?php echo $count; ?>"
<?php if(isset($result['sdn_tnwrkeka']) && $result['sdn_tnwrkeka'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">棚割結果確認</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_donyutan_<?php echo $count; ?>" id="sdn_donyutan_<?php echo $count; ?>"
<?php if(isset($result['sdn_donyutan']) && $result['sdn_donyutan'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">導入提案</td>
<?php
if($result['vi_sdn_hnkyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi01_".$count."\" id=\"sdn_hnkyobi01_".$count."\"";
	if(isset($result['sdn_hnkyobi01']) && $result['sdn_hnkyobi01'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_hnkyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_mdteian_<?php echo $count; ?>" id="sdn_mdteian_<?php echo $count; ?>"
<?php if(isset($result['sdn_mdteian']) && $result['sdn_mdteian'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">MD提案</td>
<?php
if($result['vi_sdn_hnkyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi02_".$count."\" id=\"sdn_hnkyobi02_".$count."\"";
	if(isset($result['sdn_hnkyobi02']) && $result['sdn_hnkyobi02'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_hnkyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_tanawari_<?php echo $count; ?>" id="sdn_tanawari_<?php echo $count; ?>"
<?php if(isset($result['sdn_tanawari']) && $result['sdn_tanawari'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">棚割提案</td>
<?php
if($result['vi_sdn_hnkyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi03_".$count."\" id=\"sdn_hnkyobi03_".$count."\"";
	if(isset($result['sdn_hnkyobi03']) && $result['sdn_hnkyobi03'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_hnkyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_tnwrbi_<?php echo $count; ?>" id="sdn_tnwrbi_<?php echo $count; ?>"
<?php if(isset($result['sdn_tnwrbi']) && $result['sdn_tnwrbi'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">販売店の棚割日情報</td>
<?php
if($result['vi_sdn_hnkyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi04_".$count."\" id=\"sdn_hnkyobi04_".$count."\"";
	if(isset($result['sdn_hnkyobi04']) && $result['sdn_hnkyobi04'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_hnkyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_donyutume_<?php echo $count; ?>" id="sdn_donyutume_<?php echo $count; ?>"
<?php if(isset($result['sdn_donyutume']) && $result['sdn_donyutume'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">導入日の詰め</td>
<?php
if($result['vi_sdn_hnkyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_hnkyobi05_".$count."\" id=\"sdn_hnkyobi05_".$count."\"";
	if(isset($result['sdn_hnkyobi05']) && $result['sdn_hnkyobi05'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_hnkyobi05']['itemdspname']."</td>\n";
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
<?php if(isset($result['hnk_cte_tessue']) && $result['hnk_cte_tessue'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ティシュー</td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_silver_<?php echo $count; ?>" id="hnk_cte_silver_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_silver']) && $result['hnk_cte_silver'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">シルバー</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_toilet_<?php echo $count; ?>" id="hnk_cte_toilet_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_toilet']) && $result['hnk_cte_toilet'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">トイレット</td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_mask_<?php echo $count; ?>" id="hnk_cte_mask_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_mask']) && $result['hnk_cte_mask'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">マスク</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_kitchen_<?php echo $count; ?>" id="hnk_cte_kitchen_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_kitchen']) && $result['hnk_cte_kitchen'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">キッチン</td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_pet_<?php echo $count; ?>" id="hnk_cte_pet_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_pet']) && $result['hnk_cte_pet'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ペット</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_wipe_<?php echo $count; ?>" id="hnk_cte_wipe_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_wipe']) && $result['hnk_cte_wipe'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ワイプ</td>
<?php
if($result['vi_hnk_cte_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"hnk_cte_yobi01_".$count."\" id=\"hnk_cte_yobi01_".$count."\"";
	if(isset($result['hnk_cte_yobi01']) && $result['hnk_cte_yobi01'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_hnk_cte_yobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_baby_<?php echo $count; ?>" id="hnk_cte_baby_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_baby']) && $result['hnk_cte_baby'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">ベビー</td>
<?php
if($result['vi_hnk_cte_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"hnk_cte_yobi02_".$count."\" id=\"hnk_cte_yobi02_".$count."\"";
	if(isset($result['hnk_cte_yobi02']) && $result['hnk_cte_yobi02'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_hnk_cte_yobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="hnk_cte_feminine_<?php echo $count; ?>" id="hnk_cte_feminine_<?php echo $count; ?>"
<?php if(isset($result['hnk_cte_feminine']) && $result['hnk_cte_feminine'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">フェミニン</td>
<?php
if($result['vi_hnk_cte_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"hnk_cte_yobi03_".$count."\" id=\"hnk_cte_yobi03_".$count."\"";
	if(isset($result['hnk_cte_yobi03']) && $result['hnk_cte_yobi03'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_hnk_cte_yobi03']['itemdspname']."</td>\n";
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
<table><!---4番目-->

<tr>
<td>【商談結果】</td>
</tr>
<tr>
<td>
<table>
<tr>
<td style="padding-left:10px">成約内容</td>
<td style="padding-left:170px">契約金額/半期
<td style="padding-left:1px">
<?php
if(isset($result['seiykuriage'])){
	if($result['seiykuriage'] == 0){
		echo "<input type=\"text\" size=\"10\" maxlength=\"10\" name=\"seiykuriage_".$count."\" value=\"\" readonly=\"readonly\"></td>";
	}else{
		echo "<input type=\"text\" size=\"10\" maxlength=\"10\" name=\"seiykuriage_".$count."\" value=\"".$result['seiykuriage']."\" readonly=\"readonly\"></td>";
	}
}else{
	echo "<input type=\"text\" size=\"10\" maxlength=\"10\" name=\"seiykuriage_".$count."\" value=\"\" readonly=\"readonly\"></td>";
}
?>
</td>
</tr>
<tr>
<td style="padding-left:10px" colspan="3">
<textarea style="height:100px; width:600px; margin-left:0px" name="seiykniyo_<?php echo $count; ?>" readonly="readonly"><?php echo isset($result['seiykniyo']) ? $result['seiykniyo'] : ''?></textarea>
</td>
</tr>
<tr><td></td></tr>
<tr><td style="padding-left:10px">不成約内容</td>
</tr>
<tr>
<td style="padding-left:10px" colspan="3">
<textarea style="height:100px; width:600px; margin-left:0px" name="fseiykniyo_<?php echo $count; ?>" readonly="readonly"><?php echo isset($result['fseiykniyo']) ? $result['fseiykniyo'] : ''?></textarea>
</td>
</tr>
<tr><td></td></tr>
<tr>
<td style="padding-left:10px">保留内容</td>
</tr>
<tr>
<td style="padding-left:10px" colspan="3">
<textarea style="height:100px; width:600px; margin-left:0px" name="horyuniyo_<?php echo $count; ?>" readonly="readonly"><?php echo isset($result['horyuniyo']) ? $result['horyuniyo'] : ''?></textarea>
</td>
</tr>
<tr><td></td></tr>
<tr><td style="padding-left:10px">その他内容</td>
</tr>
<tr>
<td style="padding-left:10px" colspan="3">
<textarea style="height:100px; width:600px; margin-left:0px" name="sonotaniyo_<?php echo $count; ?>" readonly="readonly"><?php echo isset($result['sonotaniyo']) ? $result['sonotaniyo'] : ''?></textarea>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="800px">
<tr>
<td>【指示コメント】</td>
</tr>
<tr>
<td style="padding-left:10px">
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
log_message('debug',"===== End new_honbu.php =====");
?>
