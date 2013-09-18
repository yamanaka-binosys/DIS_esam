<?php $this->load->helper('url'); ?>
<!-- parts/top/_schedule.php start -->
<table id="schedule-preview" class="preview-layout">
	<tr class="preview-title">
		<th class="left"><a href="#" style="cursor:default;" class="menu-link">部下のスケジュール</a>
		</th>
	</tr>
	<tr>
		<td class="preview-container">
			<table class="preview-head">
				<tr>
					<th width="34px">時刻</th>
					<th width="105px">担当者名</th>
					<th width="110px">訪問先/作業内容</th>
				</tr>
			</table>
			<div class="preview-content-overflow" style="height: 219px;">
				<table class="preview-content">
					<?php foreach ($schedule_data as $d) { ?>
					<tr>
						<td class="center cell" width="30px"><?php echo substr($d['sthm'],0,2).":".substr($d['sthm'],2,2) ?>
						</td>
						<td class="left cell" width="101px"><a
							href="<?php echo base_url("index.php/plan_view/index/{$d['ymd']}/{$d['shbn']}") ?>">
								<span class="cell-text"><?php echo mb_strimwidth($d['shinnm'], 0, 22, '…','UTF-8') ?></span>
						</a></td>
						<td class="left""><?php echo mb_strimwidth($d['doing'], 0, 18, '…','UTF-8') ?>
						</td>
					</tr>
					<?php } ?>
				</table>
			</div>
		</td>
	</tr>
</table>
<!-- parts/top/_schedule.php end -->

