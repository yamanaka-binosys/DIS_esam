<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
?>
<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/calendar.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>script/calendar.js"></script>
<?php if ($this->session->userdata('calendar_mode') == MY_CALENDAR_ALLPLAN) { ?>
<style> body { 
	background-color: #D1FBFF;
	.data_td_today_back_color: #FFEBCD;
	}</style>
<?php } ?>

</head>
<body>
<!-- MAIN -->
<div id="Main">
<div id="container">
<form action="<?php echo base_url(); ?>index.php/calendar/select" method="POST">
<table style="width: 825px">
<tr>
<td>
<!-- スケジュール配置 -->
<?php echo $mode ?>
<?php if (!$this->session->userdata('calendar_mode') || $this->session->userdata('calendar_mode') == MY_CALENDAR_MIX) { ?>
	<div style="margin-left: 5px;">
	<span style="font-weight:bold;">訪問実績</span>
	本部: <span style="font-weight:bold;"><?php echo $summary['honbu'] ?></span>
	店舗: <span style="font-weight:bold;"><?php echo $summary['tenpo'] ?></span>
	代理店: <span style="font-weight:bold;"><?php echo $summary['dairi'] ?></span>
	合計: <span style="font-weight:bold;"><?php echo $summary['honbu'] + $summary['tenpo'] + $summary['dairi'] ?></span>
	</div>
<?php } ?>

</td>
</tr>
</table>
<table style="width:825px;">
<tr>
<td>
<!-- スケジュール配置 -->
<?php echo $calendar ?>
</td>
</tr>
</table>
</form>
</div>
</div>
</body>
</html>