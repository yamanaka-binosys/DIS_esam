<?php $this->load->helper('url'); ?>
<?php $base_url = base_url("")?>
<!-- parts/top/_memo.php start -->
<table class="preview-layout">
	<tr class="preview-title">
		<th class="left"><a class="menu-link" href="javascript:memo_search('<?php echo base_url(); ?>','data_memo_search')">情報メモ</a>
		</th>
	</tr>
	<tr>
		<td class="preview-container">
			<table class="preview-head">
				<tr>
					<th width="34px">日付</th>
					<th width="139px">件名</th>
					<th width="95px">入力者</th>
				</tr>
			</table>
			<div class="preview-content-overflow" style="height: <?php if($isUnitLeader) { echo "100px"; } else { echo "219px"; } ?>">
				<table class="preview-content">
					<?php foreach($memo_data as $d) {?>
					<tr>
						<td class="center cell" width="30px"><?php echo $d['createdate']->format('m/d') ?>
						</td>
						<td class="left cell" width="135px"><a
							href="<?php echo "javascript:data_memo_search_select_top('{$d['jyohonum']}','{$d['edbn']}', '{$base_url}')" ?>">
								<span class="cell-text"><?php echo mb_strimwidth($d['knnm'], 0, 24, '…','UTF-8') ?></span>
						</a></td>
						<td class="left" width="70px">
						<span class="cell-text"><?php echo mb_strimwidth($d['shinnm'],0, 12 ,'…','UTF-8') ?></span></td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</td>
	</tr>
</table>
<!-- parts/top/_memo.php end -->

