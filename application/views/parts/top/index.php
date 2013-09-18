<?php $this->load->helper('html'); ?>
<?php echo doctype('html4-strict') ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<?php echo link_tag(base_url('css/top.css')) ?>
<script type="text/javascript" src="<?php echo base_url('script/top.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url('script/menu.js') ?>"></script>
</head>
<body>
	<div id="Main">
		<div id="container">
			<table>
				<tr>
					<td colspan="3">
						<!-- スケジュール配置 --> <?php echo $calendar ?>
					</td>
				</tr>
				<tr>
					<td rowspan="2" class="preview-area">
						<!-- ToDo配置 --> <?php echo $todo ?>
					</td>
					<td class="preview-area">
						<!-- ユニット長日報閲覧状況配置 --> <?php echo $read_report ?>
					</td>
					<td class="preview-area">
						<!-- リンクバナー配置 --> <?php echo $banner_link ?>
					</td>
				</tr>
				<tr>
					<td class="preview-area">
						<!-- 受取日報配置 --> <?php echo $received_report ?>
					</td>
					<td rowspan="2" class="preview-area">
						<!-- 情報メモ配置 --> <?php echo $memo ?>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="preview-area">
						<!-- Infomation配置 --> <?php echo $info ?>
					</td>
				</tr>
			</table>
			<br>
		</div>
	</div>
</body>
</html>
