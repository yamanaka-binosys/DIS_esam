<?php $this->load->helper('url'); ?>
<!-- parts/top/_info.php start -->


<script type="text/javascript">
	$(function() {
		$('.msg-del').click(function() {
			return confirm('メッセージを削除してもよろしいですか？');
		});
	});
</script>

<table class="info-preview-layout">
	<tr>
		<td class="preview-container">
			<table class="info-head" >
				<tr>
					<th width="548px">Information</th>
				</tr>
			</table>
			<div class="preview-content-overflow info-preview-content-overflow"  style="width:548px">
				<table class="preview-content">
					<?php foreach($info_data as $d) { ?>
					<tr>
						<td align="left">
							<?php if ($this->session->userdata('menuhyjikbn') === '003') { ?>
								<a class="msg-del" href="<?php echo base_url('index.php/message/del/'.$d['jyohonum']); ?>">削除</a>
							<?php } ?>
							<span class="cell-text" style="<?php echo $d['is_bold'] === 't' ? 'font-weight:bold;' : ''  ?>">
							<?php if (empty($d['link'])) {
								echo $d['jyohoniyo'];
							} else {
								echo '<a href="'. $d['link'] . '">' . $d['jyohoniyo'] . '</a>';
							} ?>
							</span>
						<td>
					</tr>
					<?php }?>
				</table>
			</div>
		</td>
	</tr>
</table>
<!-- parts/top/_info.php end -->
