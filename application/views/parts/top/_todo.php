<?php $this->load->helper('url'); ?>
<?php $impkbn = config_item('s_todo_impkbn') ?>
<!-- parts/top/_todo.php start -->
<form action="<?php echo base_url('index.php/top/update') ?>" method="POST">
	<table class="preview-layout">
		<tr class="preview-title">
			<th class="left"><a class="menu-link"
				href="javascript:todo_update('<?php echo base_url() ?>','todo_update')">TO DO</a>
			</th>
			<td class="right"><input class="preview-btn" type="submit" value="更新"></td>
		</tr>
		<tr>
			<td colspan="2" class="preview-container">
				<table class="preview-head">
					<tr>
						<th width="34px">期限</th>
						<th width="133px">内容</th>
						<th width="42px">重要度</th>
						<th width="58px">終了確認</th>
					</tr>
				</table>
				<div class="preview-content-overflow" style="height: <?php if($isUnitLeader) { echo "100px"; } else { echo "252px"; } ?>">
				<table class="preview-content">
						<?php foreach($todo_data as $index => $d) { ?>
						<tr>
							<td class="center cell" width="30px" ><?php echo $d['day'] ?>
							</td>
							<td class="left cell" width="129px"><a
								href="<?php echo base_url("index.php/todo/check_view/" . $d['jyohonum'] . "/" . $d['edbn']) ?>">
								<span class="cell-text"><?php echo mb_strimwidth($d['todo'],0,22,'…','UTF-8') ?></span>
							</a>
							</td>
							<td class="center cell" width="38px"><?php if (isset($impkbn[$d['impkbn']])) { 
								echo $impkbn[$d['impkbn']];
							} ?>
							</td>
							<td  class="center" style="padding-left: 14px;"><input type="checkbox" name="todo_check<?php echo $index ?>" value="<?php echo $d['jyohonum'].'_'.$d['edbn'] ?>">
							</td>
						</tr>
						<?php } ?>
					</table>
				</div>
			</td>
		</tr>
	</table>
</form>
<!-- parts/top/_todo.php end -->
