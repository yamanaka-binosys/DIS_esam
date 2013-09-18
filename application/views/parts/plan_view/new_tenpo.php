<?php
log_message('debug',"===== Start new_tenpo.php =====");
log_message('debug',"\$count = $count");
$base_url = $this->config->item('base_url');
?>

<div id="action_<?php echo $count; ?>"  class="action">
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
<td>
<label for="katsudo_kubun">活動区分</label>
<select name="action_type_<?php echo $count; ?>" id="action_type_<?php echo $count; ?>">
<option value="srntb120" selected>店舗</option>
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

</td>
<td>

<table>
<tr><td colspan="2">販売店 店舗名</td></tr>
<tr>
<td></td>
<td>
<input type="hidden" name="aiteskcd_<?php echo $count; ?>" id="aiteskcd_<?php echo $count; ?>" value="<?php echo isset($plan['aiteskcd']) ? $plan['aiteskcd'] : ''?>">
<input type="text" style="width:148px;" maxlength="256" name="aitesknm_<?php echo $count; ?>" id="aitesknm_<?php echo $count; ?>" value="<?php echo isset($plan['aitesknm']) ? $plan['aitesknm'] : ''?>" readonly="readonly">
<input type="button" value="選択" onclick="">
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
<td><input type="text" style="width:148px;" maxlength="256" name="mendannm01_<?php echo $count; ?>" id="mendannm01_<?php echo $count; ?>" value="<?php echo isset($plan['mendannm01']) ? $plan['mendannm01'] : ''?>" readonly="readonly"></td>
</tr>
<tr>
<td></td>
<td><input type="text" style="width:148px;" maxlength="256" name="mendannm02_<?php echo $count; ?>" id="mendannm02_<?php echo $count; ?>" value="<?php echo isset($plan['mendannm02']) ? $plan['mendannm02'] : ''?>" readonly="readonly"></td>
</tr>
<tr>
<td colspan="2">同行者</td>
</tr>
<tr>
<td></td>
<td>
<input type="text" style="width:290px;" maxlength="256" name="doukounm01_<?php echo $count; ?>" id="doukounm01_<?php echo $count; ?>" value="<?php echo isset($plan['doukounm01']) ? $plan['doukounm01'] : ''?>" readonly="readonly">
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
 readonly="readonly"></td>
<td style="height:24px">情報収集</td>
<?php
if($plan['vi_ktd_tnpyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi01_".$count."\" id=\"ktd_tnpyobi01_".$count."\" ";
	if(isset($plan['ktd_tnpyobi01']) && $plan['ktd_tnpyobi01'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">商品情報案内</td>
<?php
if($plan['vi_ktd_tnpyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi02_".$count."\" id=\"ktd_tnpyobi02_".$count."\" ";
	if(isset($plan['ktd_tnpyobi02']) && $plan['ktd_tnpyobi02'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">展開場所・ｱｳﾄ展開交渉</td>
<?php
if($plan['vi_ktd_tnpyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi03_".$count."\" id=\"ktd_tnpyobi03_".$count."\" ";
	if(isset($plan['ktd_tnpyobi03']) && $plan['ktd_tnpyobi03'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">推奨販売交渉</td>
<?php
if($plan['vi_ktd_tnpyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi04_".$count."\" id=\"ktd_tnpyobi04_".$count."\" ";
	if(isset($plan['ktd_tnpyobi04']) && $plan['ktd_tnpyobi04'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">受注促進</td>
<?php
if($plan['vi_ktd_tnpyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_tnpyobi05_".$count."\" id=\"ktd_tnpyobi05_".$count."\" ";
	if(isset($plan['ktd_tnpyobi05']) && $plan['ktd_tnpyobi05'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">売場撮影</td>
<td style="height:24px"><input type="checkbox" name="ktd_beta_<?php echo $count; ?>" id="ktd_beta_<?php echo $count; ?>"
<?php if(isset($plan['ktd_beta']) && $plan['ktd_beta'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">ベタ付け</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="ktd_mente_<?php echo $count; ?>" id="ktd_mente_<?php echo $count; ?>"
<?php if(isset($plan['ktd_mente']) && $plan['ktd_mente'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">売場メンテナンス</td>
<?php
if($plan['vi_ktd_sdnigiyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi01_".$count."\" id=\"ktd_sdnigiyobi01_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi01']) && $plan['ktd_sdnigiyobi01'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">在庫確認</td>
<?php
if($plan['vi_ktd_sdnigiyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi02_".$count."\" id=\"ktd_sdnigiyobi02_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi02']) && $plan['ktd_sdnigiyobi02'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">商品補充</td>
<?php
if($plan['vi_ktd_sdnigiyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi03_".$count."\" id=\"ktd_sdnigiyobi03_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi03']) && $plan['ktd_sdnigiyobi03'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">販促物の設置</td>
<?php
if($plan['vi_ktd_sdnigiyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi04_".$count."\" id=\"ktd_sdnigiyobi04_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi04']) && $plan['ktd_sdnigiyobi04'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
	echo "<td style=\"height:24px\">".$plan['vi_ktd_sdnigiyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" nname="ktd_zaiko_<?php echo $count; ?>" id="ktd_zaiko_<?php echo $count; ?>"
<?php if(isset($plan['ktd_zaiko']) && $plan['ktd_zaiko'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">山積み</td>
<?php
if($plan['vi_ktd_sdnigiyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"ktd_sdnigiyobi05_".$count."\" id=\"ktd_sdnigiyobi05_".$count."\" ";
	if(isset($plan['ktd_sdnigiyobi05']) && $plan['ktd_sdnigiyobi05'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
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
 readonly="readonly"></td>
<td style="height:24px">ティシュー</td>
<td style="height:24px"><input type="checkbox" name="cte_silver_<?php echo $count; ?>" id="cte_silver_<?php echo $count; ?>"
<?php if(isset($plan['cte_silver']) && $plan['cte_silver'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">シルバー</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_toilet_<?php echo $count; ?>" id="cte_toilet_<?php echo $count; ?>"
<?php if(isset($plan['cte_toilet']) && $plan['cte_toilet'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">トイレット</td>
<td style="height:24px"><input type="checkbox" name="cte_mask_<?php echo $count; ?>" id="cte_mask_<?php echo $count; ?>"
<?php if(isset($plan['cte_mask']) && $plan['cte_mask'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">マスク</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_kitchen_<?php echo $count; ?>" id="cte_kitchen_<?php echo $count; ?>"
<?php if(isset($plan['cte_kitchen']) && $plan['cte_kitchen'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">キッチン</td>
<td style="height:24px"><input type="checkbox" name="cte_pet_<?php echo $count; ?>" id="cte_pet_<?php echo $count; ?>"
<?php if(isset($plan['cte_pet']) && $plan['cte_pet'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">ペット</td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="cte_wipe_<?php echo $count; ?>" id="cte_wipe_<?php echo $count; ?>"
<?php if(isset($plan['cte_wipe']) && $plan['cte_wipe'] === '1') echo " checked "; ?>
 readonly="readonly"></td>
<td style="height:24px">ワイプ</td>
<?php
if($plan['vi_cte_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"cte_yobi01_".$count."\" id=\"cte_yobi01_".$count."\" ";
	if(isset($plan['cte_yobi01']) && $plan['cte_yobi01'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">ベビー</td>
<?php
if($plan['vi_cte_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"cte_yobi02_".$count."\" id=\"cte_yobi02_".$count."\" ";
	if(isset($plan['cte_yobi02']) && $plan['cte_yobi02'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
 readonly="readonly"></td>
<td style="height:24px">フェミニン</td>
<?php
if($plan['vi_cte_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"cte_yobi03_".$count."\" id=\"cte_yobi03_".$count."\" ";
	if(isset($plan['cte_yobi03']) && $plan['cte_yobi03'] === '1') echo " checked "; 
	echo " readonly=\"readonly\"></td>\n";
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
<textarea rows="3" cols="80"  name="sagyo_yotei_<?php echo $count; ?>" id="sagyo_yotei_<?php echo $count; ?>" readonly="readonly"><?php echo isset($plan['sagyo_yotei']) ? $plan['sagyo_yotei'] : ''?></textarea>
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
