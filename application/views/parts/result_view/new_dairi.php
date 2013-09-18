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
<td>
	
<table>
<tr>
<td>活動区分
<select name="action_type_<?php echo $count; ?>" id="action_type_<?php echo $count; ?>">
<option value="srntb030" selected>代理店</option>
</select>
</td>
</tr>
</table>

<input type="hidden" name="jyohonum_<?php echo $count; ?>" id="jyohonum_<?php echo $count; ?>" value="<?php echo isset($result['jyohonum']) ? $result['jyohonum'] : ''?>">
<input type="hidden" name="edbn_<?php echo $count; ?>" id="edbn_<?php echo $count; ?>" value="<?php echo isset($result['edbn']) ? $result['edbn'] : ''?>">
<input type="hidden" name="recode_flg_<?php echo $count; ?>" id="recode_flg_<?php echo $count; ?>" value="<?php echo isset($result['recode_flg']) ? $result['recode_flg'] : ''?>">

<br>
<table>
<tr>
<td colspan="3">
	
<table style="border:1px #000000 solid">
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
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="edh_<?php echo $count; ?>" id="edh_<?php echo $count; ?>"  value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 0, 2) : ''?>" readonly="readonly">時</td>
<td><input type="text" style="width: 20px;" size="2" maxlength="2" name="edm_<?php echo $count; ?>" id="edm_<?php echo $count; ?>"  value="<?php echo isset($result['edhm']) ? substr($result['edhm'], 2, 2) : ''?>" readonly="readonly">分</td>
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
<input type="hidden" name="aiteskcd_<?php echo $count; ?>" id="aiteskcd_<?php echo $count; ?>" value="<?php echo isset($result['aiteskcd']) ? $result['aiteskcd'] : ''?>">
<input type="text" style="width:148px;" maxlength="256" name="aitesknm_<?php echo $count; ?>" id="aitesknm_<?php echo $count; ?>" value="<?php echo isset($result['aitesknm']) ? $result['aitesknm'] : ''?>" readonly="readonly">
<input type="button" value="選択"></td>
</tr>
<tr>
<td></td>
</tr>
<tr>
<td colspan="2">ランク
<input type="text" style="width: 20px;" size="6" name="aiteskrank_<?php echo $count; ?>" value="<?php echo isset($result['aiteskrank']) ? $result['aiteskrank'] : ''?>" readonly="readonly" />
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
<input type="text" style="width: 200px;"  maxlength="256" name="doukounm01_<?php echo $count; ?>" id="doukounm01_<?php echo $count; ?>" value="<?php echo isset($result['doukounm01']) ? $result['doukounm01'] : ''?>" readonly="readonly">
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
<?php if(isset($result['sdn_mitsumorifollow']) && $result['sdn_mitsumorifollow'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">一般店見積り提示・商談フォロー</td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_syouhin_<?php echo $count; ?>" id="sdn_syouhin_<?php echo $count; ?>"
<?php if(isset($result['sdn_syouhin']) && $result['sdn_syouhin'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">商品案内</td>
<?php
if($result['vi_sdn_yobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi03_".$count."\" id=\"sdn_yobi03_".$count."\" ";
	if(isset($result['sdn_yobi03']) && $result['sdn_yobi03'] === '1') echo " checked "; 	
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_yobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_kikaku_<?php echo $count; ?>" id="sdn_kikaku_<?php echo $count; ?>"
<?php if(isset($result['sdn_kikaku']) && $result['sdn_kikaku'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">企画案内</td>
<?php
if($result['vi_sdn_yobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi04_".$count."\" id=\"sdn_yobi04_".$count."\" ";
	if(isset($result['sdn_yobi04']) && $result['sdn_yobi04'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_yobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_jiseki_<?php echo $count; ?>" id="sdn_jiseki_<?php echo $count; ?>"
<?php if(isset($result['sdn_jiseki']) && $result['sdn_jiseki'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">実績報告（経理・配荷）</td>
<?php
if($result['vi_sdn_yobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi05_".$count."\" id=\"sdn_yobi05_".$count."\" ";
	if(isset($result['sdn_yobi05']) && $result['sdn_yobi05'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_yobi05']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<?php
if($result['vi_sdn_yobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi01_".$count."\" id=\"sdn_yobi01_".$count."\" ";
	if(isset($result['sdn_yobi01']) && $result['sdn_yobi01'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_yobi01']['itemdspname']."</td>\n";
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
if($result['vi_sdn_yobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_yobi02_".$count."\" id=\"sdn_yobi02_".$count."\" ";
	if(isset($result['sdn_yobi02']) && $result['sdn_yobi02'] === '1') echo " checked "; 	
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_yobi02']['itemdspname']."</td>\n";
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
<?php if(isset($result['sdn_mitsumori']) && $result['sdn_mitsumori'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">見積り提示</td>
<?php
if($result['vi_sdn_kanriyobi02']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi02_".$count."\" id=\"sdn_kanriyobi02_".$count."\" ";
	if(isset($result['sdn_kanriyobi02']) && $result['sdn_kanriyobi02'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_kanriyobi02']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_jizenutiawase_<?php echo $count; ?>" id="sdn_jizenutiawase_<?php echo $count; ?>"
<?php if(isset($result['sdn_jizenutiawase']) && $result['sdn_jizenutiawase'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">販売店商談事前打合せ</td>
<?php
if($result['vi_sdn_kanriyobi03']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi03_".$count."\" id=\"sdn_kanriyobi03_".$count."\" ";
	if(isset($result['sdn_kanriyobi03']) && $result['sdn_kanriyobi03'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_kanriyobi03']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:24px"><input type="checkbox" name="sdn_kikakujyoukyou_<?php echo $count; ?>" id="sdn_kikakujyoukyou_<?php echo $count; ?>"
<?php if(isset($result['sdn_kikakujyoukyou']) && $result['sdn_kikakujyoukyou'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">情報収集・企画導入状況確認</td>
<?php
if($result['vi_sdn_kanriyobi04']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi04_".$count."\" id=\"sdn_kanriyobi04_".$count."\" ";
		if(isset($result['sdn_kanriyobi04']) && $result['sdn_kanriyobi04'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_kanriyobi04']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
</tr>
<tr>
<td style="height:24px"></td>
<?php
if($result['vi_sdn_kanriyobi01']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi01_".$count."\" id=\"sdn_kanriyobi01_".$count."\" ";
		if(isset($result['sdn_kanriyobi01']) && $result['sdn_kanriyobi01'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_kanriyobi01']['itemdspname']."</td>\n";
}else{
	echo "<td style=\"height:24px\"></td>\n";
	echo "<td style=\"height:24px\"></td>\n";
}
?>
<?php
if($result['vi_sdn_kanriyobi05']['dispflg'] === '1'){
	echo "<td style=\"height:24px\"><input type=\"checkbox\" name=\"sdn_kanriyobi05_".$count."\" id=\"sdn_kanriyobi05_".$count."\" ";
		if(isset($result['sdn_kanriyobi05']) && $result['sdn_kanriyobi05'] === '1') echo " checked "; 
	echo " disabled=\"true\"></td>\n";
	echo "<td style=\"height:24px\">".$result['vi_sdn_kanriyobi05']['itemdspname']."</td>\n";
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
<?php if(isset($result['sdn_logistics']) && $result['sdn_logistics'] === '1') echo " checked "; ?>
 disabled="true"></td>
<td style="height:24px">受発注・物流関連</td>
<td style="height:24px"></td>
<td style="height:24px"></td>
</tr>
<tr>
<td style="height:24px"></td>
<td style="height:20px"><input type="checkbox" name="sdn_torikmi_<?php echo $count; ?>" id="sdn_torikmi_<?php echo $count; ?>"
<?php if(isset($result['sdn_torikmi']) && $result['sdn_torikmi'] === '1') echo " checked "; ?>
 disabled="true"></td>
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
<tr>
<td>【商談結果】</td>
</tr>
<tr>
<td style="padding-left:10px">商談目的と結果詳細</td>
</tr>
<tr>
<td style="padding-left:10px">
<textarea style="height:100px;width:600px;" name="sdn_niyo_<?php echo $count; ?>" readonly="readonly"><?php echo isset($result['sdn_niyo']) ? $result['sdn_niyo'] : ''?></textarea>
</td>
</tr>
<br>
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
