<?php $this->load->helper('url'); ?>
<!-- parts/top/_calendar.php start -->
<div class="preview-position">
	<table class="preview-layout">
		<tr class="preview-title">
			<th class="left"><a class="menu-link"
				href="javascript:calendar('<?php echo base_url(); ?>','calendar')">スケジュール</a>
			</th>
		</tr>
		<tr class="calendar-preview-head">
			<?php foreach($calendar_data as $d) { ?>
			<th class="center"><a style="color: <?php switch($d[1]) {case '土': echo '#0000FF'; break; case '日': echo '#FF0000'; break; default: echo '#000'; }  ?>;"
				href="<?php echo base_url('index.php/plan/index/' . $d['link_day']) ?>"><?php echo $d[0] ?>(<?php echo $d[1] ?>)</a>
			</th>
			<?php } ?>
		</tr>
		<tr class="calendar-preview-content">
			<?php foreach($calendar_data as $d) {
				?>
			<td>
				<div style="overflow: auto;height:66px;">
				<?php for ($j=MY_THREE; ! empty($d[$j]); $j++) { ?>
							<span class="cal-time"><?php echo $d[$j]['time'] ?></span><span class="cal-title"><?php echo mb_strimwidth($d[$j]['title'],0,12,'…','UTF-8') ?></span><br/>
				<?php } ?>
				</div>
			</td>
			<?php } ?>
		</tr>
	</table>
</div>



<!-- parts/top/_calendar.php end -->
