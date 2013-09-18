<!--application/views/parts/content/select_client.phpに移動-->
<div id="Main">
<br>
<div id="container">

<?php
if ( $list_table && count($list_table) > 0 ) {
?>

<table  style="border-collapse:collapse;">
<tr>
<th class="none" colspan="6">【相手先選択】</th>
</tr>

	<?php for ( $i=0; $i <= count($list_table); $i++ ) { ?>
	<?php if ( isset($list_table[$i]) ) { ?>
	<tr>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;width:200px;">相手先名</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;width:200px;">相手先名</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	</tr>
	<tr>
	<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="radio" name="hanhoncd" id="hanhoncd" value="<?php echo $list_table[$i]['hanhoncd'].'|'.$list_table[$i]['aitesknm']; ?>"></td>
	<td style="border:1px solid #000000;padding:5px;background-color:white;"><?php echo $list_table[$i]['aitesknm']; ?></td>
	<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索" onClick="window.open('<?php echo $form_url; ?>','aitesk_search','scrollbars=no,width=600,height=500,').focus();"></td>
		<?php $i++; ?>
		<?php if ( isset($list_table[$i]) ) { ?>
			<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="radio" name="hanhoncd" id="hanhoncd" value="<?php echo $list_table[$i]['hanhoncd'].'|'.$list_table[$i]['aitesknm']; ?>"></td>
			<td style="border:1px solid #000000;padding:5px;background-color:white;"><?php echo $list_table[$i]['aitesknm']; ?></td>
			<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索" onClick="window.open('<?php echo $form_url; ?>','aitesk_search','scrollbars=no,width=600,height=500,').focus();"></td>
		<?php } else { ?>
			<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="radio" name="hanhoncd" id="hanhoncd" value="<?php echo $list_table[$i]['hanhoncd'].'|'.$list_table[$i]['aitesknm']; ?>"></td>
			<td style="border:1px solid #000000;padding:5px;background-color:white;">&nbsp;</td>
			<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索" onClick="window_open();"></td>
		<?php } ?>
	</tr>
	<?php } ?>
	<?php } ?>

</table>

<?php } else { ?>

<table  style="border-collapse:collapse;">
<tr>
<th class="none" colspan="3">【相手先選択】</th>
</tr>
	<tr>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;width:200px;">相手先名</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	</tr>
	<tr>
	<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="checkbox" name="hanhoncd" id="hanhoncd" value=""></td>
	<td style="border:1px solid #000000;padding:5px;background-color:white;">&nbsp;</td>
	<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索" onClick="window.open('<?php echo $form_url; ?>','','scrollbars=no,width=600,height=500,');"></td>
	</tr>
</table>

<?php } ?>

</div>

</div>

<script>
function window_open(){
	window.name = 'parent';
	window.open('<?php echo $form_url; ?>','aitesk_search','scrollbars=no,width=600,height=500,').focus();
}

</script>