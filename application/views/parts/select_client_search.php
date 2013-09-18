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

$i=0;

?>
<form action="" name="child" method="POST" >
<script type="text/JavaScript">
function set(){
	document.child.action = '<?php echo $return_url; ?>';
	document.child.method = 'POST';
	document.child.submit();
    window.opener.location.href = window.opener.location.href;

    self.close();
}
</script>
<input type="text" name="aiteskcd" id="aiteskcd">


		<div id="container">
			<!-- search_key -->
		    <br>
			<table>
				<tr>
					<th colspan="2" align="left">【担当商談先より検索】</th>
					<td style="width:50px"></td>
					<th colspan="2" align="left">【全相手先名より検索】</th>
				</tr>
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td>業務区分</td>
					<td align="right">
						<select name="honbucd">
							<?php $i=0; ?>
							<?php foreach ( $business_type as $key => $value ) { ?>
							<?php if ( $i ) { ?>
							<option value="">--選択してください--</option>
							<?php $i++; ?>
							<?php } else { ?>
							<option value="<?php echo $value['id']; ?>" <?php if ( $my_info[0]['honbucd'] == $value['id'] ) { echo 'selected'; } ?>><?php echo $value['name']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td></td>
					<td>店舗名称</td>
					<td align="right"><input type="text" name="aitesknm_all" value=""></td>
				</tr>
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td>本部</td>
					<td align="right">
						<select name="honbucd">
							<?php $i=0; ?>
							<?php foreach ( $head_info as $value ) { ?>
							<?php if ( !$i ) { ?>
							<option value="">--選択してください--</option>
							<?php $i++; ?>
							<?php } else {?>
							<option value="<?php echo $value['honbucd']; ?>" <?php if ( $my_info[0]['honbucd'] == $value['honbucd'] ) { echo 'selected'; } ?>><?php echo $value['bunm']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td></td>
					<td>都道府県</td>
					<td align="right">
						<select name="pref">
							<?php $i=0; ?>
							<?php foreach ( $prefecture as $value ) {?>
							<?php if ( !$i ) { ?>
							<option value="">--選択してください--</option>
							<?php $i++; ?>
							<?php } else { ?>
							<option value="<?php echo $value;?>"><?php echo $value; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>部</td>
					<td align="right">
						<select name="bucd">
							<?php $i=0; ?>
							<?php foreach ( $division_info as $value ) {?>
							<?php if ( !$i ) { ?>
							<option value="">--選択してください--</option>
							<?php $i++; ?>
							<?php } else { ?>
							<option value="<?php echo $value['bucd'];?>" <?php if ( $my_info[0]['bucd'] == $value['bucd'] ) { echo 'selected'; } ?>><?php echo $value['bunm']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td></td>
					<td>市区町村</td>
					<td align="right"><input type="text" name="other_address" value=""></td>
				</tr>
				<tr>
					<td>ユニット</td>
					<td align="right">
						<select name="kacd">
							<?php $i=0; ?>
							<?php foreach ( $unit_info as $value ) {?>
							<?php if ( !$i ) { ?>
							<option value="">--選択してください--</option>
							<?php $i++; ?>
							<?php } else { ?>
							<option value="<?php echo $value['kacd'];?> <?php if ( $my_info[0]['kacd'] == $value['kacd'] ) { echo 'selected'; } ?>"><?php echo $value['bunm']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td>担当者名</td>
					<td align="right">
						<select name="shbn">
							<?php $i=0; ?>
							<?php foreach ( $staff_list as $staff ) {?>
							<?php if ( !$i ) { ?>
							<option value="">--選択してください--</option>
							<?php $i++; ?>
							<?php } else { ?>
							<option value="<?php echo $staff['shbn']; ?>" <?php if ( $my_info[0]['shbn'] == $staff['shbn'] ) { echo 'selected'; } ?>><?php echo $staff['shinnm']; ?></option>
							<?php } ?>
							<?php } ?>
						</select>
					</td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td>店舗名称</td>
					<td align="right"><input type="text" name="aitesknm" value=""></td>
					<td colspan="3"></td>
				</tr>
				<tr>
					<td colspan="5"></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="3">
						<input type='hidden' name='search' value='true'>
						&nbsp;<input type="submit" value=" 検索 " style="margin-left:180px"></td>
					<td></td>
				</tr>
			</table>
			<br>
			<table style="width: 447px">
				<tr>
					<th style="width:150px"></th>
					<th align="left">相手先名</th>
					<th></th>
				</tr>
				<tr>
					<td></td>
					<td>
						<select size="5" ondblclick="set();" style="width: 250px" name="hanhoncd" id="field">
							<?php foreach($search_list as $key => $value) {?>
							<option value="<?php echo $value['aiteskcd']; ?>"><?php echo $value['aitesknm']; ?></option>
							<?php } ?>
						</select>
					</td>
					<td></td>
				</tr>
			</table>
			<!-- search_key end -->
			<br>
		</div>
</form>