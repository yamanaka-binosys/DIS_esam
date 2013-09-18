<?php $this->load->helper('url'); ?>
<!-- parts/top/_read_report.php start -->
<table class="preview-layout">
	<tr class="preview-title">
		<th class="left"><a class="menu-link" style="cursor:default;" href="#">ユニット長日報閲覧状況</a>
		</th>
	</tr>
	<tr>
		<td class="preview-container">
			<table class="preview-head">
				<tr>
					<th width="34px">日付</th>
					<th width="128px"><?php echo $read_report_head_item ?></th>
					<th width="40px">状態</th>
					<th width="65px">コメント</th>
				</tr>
			</table>
			<div class="preview-content-overflow">
				<table class="preview-content">
					<?php foreach ($read_report_data as $d) { ?>
					<tr>
						<td class="center cell" width="30px"><?php echo $d['ymd_date']->format('m/d') ?>
						</td>
						<td class="left cell" width="124px">
						<?php if ($isUnitLeader) { ?>
						<a href="<?php echo base_url("/index.php/result/admin_check_view/{$d['ymd']}/{$d['shbn']}") ?>">
						<?php } else { ?>
						<a href="<?php echo base_url("/index.php/result/index/{$d['ymd']}") ?>">
						<?php } ?>
								<span class="cell-text"><?php echo mb_strimwidth($d['view_shinnm'], 0, 16, '…','UTF-8')  ?></span>
						</a>
						</td>
						<td class="center cell" width="36xp"><?php echo $d['etujukyo'] ?>
						</td>
						<td class="center" style="padding-left: 21px;"><?php echo $d['comment'] ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</td>
	</tr>
</table>
<!-- parts/top/_read_report.php end -->
