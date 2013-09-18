<?php
// データ取得
$my_info = $list_table['init_data']['my_info'];
$head_info = $list_table['init_data']['head'];
$division_info = $list_table['init_data']['division'];
$unit_info = $list_table['init_data']['unit'];
$staff_list = $list_table['init_data']['staff_list'];
$prefecture = $list_table['init_data']['prefecture'];
$business_type = $list_table['init_data']['business_type'];
$search_list = $list_table['search_data'];
$return_url = $list_table['return_url'];
//var_dump($search_list);
?>
<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/select_client_search.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/select_client_search.js"></script>
<script type="text/JavaScript">
function set(){
	
	//保存処理
	document.forms[0].action="updata";
	document.forms[0].submit();	

	// 親画面のhanhoncd設定
	if(!window.parent.opener || window.parent.opener.closed){
		//親ウィンドウが存在しない
		window.parent.close();
	} else{
		var aitesk = document.getElementById('hanhoncd').value
		if(aitesk){
	        aitesk_val = aitesk.split('|');
	        <?php if($count=="pp"){ ?>
	       		window.parent.opener.document.getElementById('aiteskcd').value = aitesk_val[0];
		        window.parent.opener.document.getElementById('aitesk_name').value = aitesk_val[1];
	        <?php }else if($count=="ppw"){ ?>
	        	window.parent.opener.document.getElementById('view_aiteskcd').value = aitesk_val[0];
		        window.parent.opener.document.getElementById('view_aitesk_name').value = aitesk_val[1];
	        <?php }else{ ?>
		        window.parent.opener.document.getElementById('aiteskcd_<?php echo $count; ?>').value = aitesk_val[0];
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = false;
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').value = aitesk_val[1];
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = true;
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = false;
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').value = aitesk_val[3];
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = true;
	        <?php } ?>
	        }else{
	      	<?php if($count=="pp"){ ?>
	       		window.parent.opener.document.getElementById('aiteskcd').value = '';
		        window.parent.opener.document.getElementById('aitesk_name').value = '';
	        <?php }else if($count=="ppw"){ ?>
	        	window.parent.opener.document.getElementById('view_aiteskcd').value = '';
		        window.parent.opener.document.getElementById('view_aitesk_name').value = '';
	        <?php }else{ ?>
		        window.parent.opener.document.getElementById('aiteskcd_<?php echo $count; ?>').value = '';
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = false;
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').value = '';
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = true;
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = false;
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').value = '';
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = true;
	        <?php } ?>
        }
	} 

	// 画面閉じる
    self.close();
}
</script>

<div id="Header">
<table>
<tr>
<td class="title2" colspan="2">
<img src="<?php echo $base_url; ?>images/search_headquarters.gif" alt=""/>
</td>
<td rowspan="2" class="right2">
<br><br><br></td>
</tr>
<tr>
<td class="errmsg">
<?php echo $errmsg; ?>
</td>
</tr>
</table>
</div>

</head>
<body >

<form action="" name="child" method="POST" >
<div id="container">
<div id="change_position" style="display:none"></div>
<!-- search_key -->
<table>
	<tr>
		<td style="text-align: center;">
			<input type="button" value="決定" align="center" style="width:50px;" onclick="set();">			
		</td>
		<td style="text-align: center;">
			<input type="button" value="戻る" align="center" style="width:50px;" onclick="history.back();">			
		</td>
		<td style="text-align: center;">
			<input type="button" value="閉じる" align="center" style="width:50px;" onclick="window.close();">			
		</td>
	</tr>
</table>
<br>
<table>
<tr>
<td>
<div id="bu_unit_table">
<table class="search_list_box">
<tr>
	<th colspan="2" align="left">【担当商談先より検索】</th>
</tr>
<tr>
	<td class="list_name">本部</td>
	<td align="left">
	<select name="honbucd" class="search_list" id="daibunrui_list" onChange="reload_dropdown('<?php echo $base_url; ?>');">
	<option value="XXXXX">--選択してください--</option>
	<?php foreach ( $head_info as $value ) { ?>
	<option value="<?php echo $value['honbucd']; ?>" <?php if ( $my_info[0]['honbucd'] == $value['honbucd'] ) { echo 'selected'; } ?>><?php echo $value['bunm']; ?></option>
	<?php } ?>
	</select>
	</td>
</tr>
<tr>
	<td class="list_name">部</td>
	<td align="left">
	<select name="bucd" class="search_list" id="bu_list" onChange="reload_dropdown_unit('<?php echo $base_url; ?>');">
	<option value="XXXXX">--選択してください--</option>
	<?php foreach ( $division_info as $value ) { ?>
	<option value="<?php echo $value['bucd']; ?>" <?php if ( $my_info[0]['bucd'] == $value['bucd'] ) { echo 'selected'; } ?>><?php echo $value['bunm']; ?></option>
	<?php } ?>
	</select>
	</td>
</tr>
<tr>
	<td class="list_name">ユニット</td>
	<td align="left">
	<select name="kacd" class="search_list" id="unit_list" onChange="reload_dropdown_user('<?php echo $base_url; ?>');">
	<option value="XXXXX">--選択してください--</option>
	<?php foreach ( $unit_info as $value ) {?>
	<option value="<?php echo $value['kacd'];?>" <?php if ( $my_info[0]['kacd'] == $value['kacd'] ) { echo 'selected'; } ?>><?php echo $value['bunm']; ?></option>
	<?php } ?>
	</select>
	</td>
</tr>
<tr>
	<td class="list_name">担当者名</td>
	<td align="left">
	<select name="shbn" class="search_list">
	<option value="">--選択してください--</option>
	<?php foreach ( $staff_list as $staff ) {?>
	<option value="<?php echo $staff['shbn']; ?>" <?php if ( $my_info[0]['shbn'] == $staff['shbn'] ) { echo 'selected'; } ?>><?php echo $staff['shinnm']; ?></option>
	<?php } ?>
	</select>
	</td>
</tr>
<tr>
	<td class="list_name">本部名称</td>
	<td align="left"><input type="text" class="search_list" name="aitesknm" value="<?php echo (isset($_POST['aitesknm'])) ? $_POST['aitesknm']: ""; ?>"></td>
</tr>
			<tr><td style="height:10px;"></td></tr>
</table>
</div>
</td>
</tr>

<tr>
<td>

<table style="width: 380px;margin: 0 auto;">
<tr><td style="height:10px;"></td></tr>
<tr>
	<td colspan="2" style="text-align:center;">
	<input type='hidden' name='search' value='true'>
	&nbsp;<input type="submit" value=" 検索 ">
	</td>
</tr>
<tr>
	<th align="left">相手先名</th>
</tr>
<tr>
	<td>
	<select size="7" style="width: 400px" class="result_list" name="hanhoncd" id="hanhoncd">
	<?php
	if(!is_null($search_list)){
		foreach($search_list as $key => $value) {
			if($value['rank']==""){
				$rank = "なし";
			}else{
				$rank = $value['rank'];
			}
	?>
	<option value="<?php echo $value['hanhoncd']; ?>|<?php echo $value['aitesknm']; ?>|<?php echo $value['aiteskcd']; ?>|<?php echo $value['rank']; ?>">
		<?php echo $value['aitesknm']."&nbsp;/&nbsp;ランク：".$rank; ?></option>
	<?php
		}
	} ?>
	</select>
	</td>
</tr>
</table>

</td>
</tr>
</table>

<br>
<!-- search_key end -->
<br>
</div>
</form>
</body>
</html>