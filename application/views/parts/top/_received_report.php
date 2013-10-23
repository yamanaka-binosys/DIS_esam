<?php $this->load->helper('url'); ?>
<!-- parts/top/_received_report.php start -->
<table class="preview-layout">
	<tr class="preview-title">
		<th class="left"><a class="menu-link"  style="cursor:default;" href="#">受取日報</a>
		</th>
	</tr>
	<tr>
		<td class="preview-container">
			<table class="preview-head">
				<tr>
					<th width="34px">日付</th>
					<th width="128px">担当者</th>
					<th width="40px">状態</th>
					<th width="65px">コメント</th>
				</tr>
			</table>
			<div class="preview-content-overflow">
				<table class="preview-content">
					<?php foreach ($received_report_data as $d) { ?>
					<tr>
						<td class="center cell" width="30px"><?php echo $d['ymd_date']->format('m/d') ?>
						</td>
						<td class="left cell" width="124px"><a
							href="<?php echo base_url("index.php/result/general_check_view/{$d['ymd']}/{$d['irshbn']}") ?>">
								<span class="cell-text"><?php echo mb_strimwidth($d['ir_shinnm'], 0, 22, '…','UTF-8') ?></span>
						</a></td>
						<td class="center" width="36px" style="padding-left: 0px;"><?php echo $d['etujukyo'] ?>
						</td>
						<td class="center" style="padding-left: 21px;"><?php //echo $d['comment'] ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</td>
	</tr>
</table>
<!-- parts/top/_receive_report.php end -->

