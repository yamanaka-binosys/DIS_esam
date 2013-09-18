<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/data_output.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/data_output.js"></script>
</head>
<body >
<!-- MAIN -->


		
<form action="<?php echo $form_url; ?>" method="POST">
	<!-- MAIN -->
	<div id="Main">
    <br>
		<div id="container">


            <!-- search_key -->
            <table class="search_key">
                <tr>
                    <td style="width: 400px" class="auto-style1">営業日報</td>
                    <td>
					<input type="button" name="sample12" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/6').focus();return false;"value="　サンプル　" class="auto-style1"></td>
	                <td>
					<input type="button" name="output12" value="　出力　" onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/6','data_output_popup','scrollbars=no,width=640,height=560,').focus();" class="auto-style1"></td>
                </tr>
                <tr>
                    <td style="width: 400px" class="auto-style1">情報メモ</td>
                    <td>
					<input type="button" name="sample13" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/7');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
				 	<input type="button" name="output13" value="　出力　" onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/7','data_output_popup','scrollbars=no,width=640,height=600').focus();" class="auto-style1"></td>
                </tr>
                <tr>
                    <td style="width: 400px" class="auto-style1">カレンダーA4</td>
                    <td>
					<input type="button" name="sample14" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/8');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input type="button" name="output14" value="　出力　"  onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/8','data_output_popup','scrollbars=no,width=640,height=560,').focus();" class="auto-style1"></td>
                </tr>
                <tr>
                    <td style="width: 400px" class="auto-style1">カレンダーA3</td>
                    <td>
					<input type="button" name="sample14" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/9');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input type="button" name="output14" value="　出力　"  onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/9','data_output_popup','scrollbars=no,width=640,height=560,').focus();" class="auto-style1"></td>
                </tr>
                <tr>
                    <td style="width: 400px" class="auto-style1">日報実績詳細CSV</td>
                    <td>
					<input type="button" name="sample17" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/10');return false;"value="　サンプル　" class="auto-style1"></td>
	                <td>
					<input type="button" name="output17" value="　出力　" onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/10','data_output_popup','scrollbars=no,width=640,height=560,').focus();" class="auto-style1"></td>
                </tr>
                <!--tr>
                    <td style="width: 400px" class="auto-style1">商談履歴</td>
                    <td>
					<input type="button" name="sample16" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/11');return false;"value="　サンプル　" class="auto-style1"></td>
	                <td>
					<input type="button" name="output16" value="　出力　" onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/11','data_output_popup','scrollbars=no,width=640,height=560,').focus();" class="auto-style1"></td>
                </tr-->
                <tr>
                    <td style="width: 400px" class="auto-style1">企画獲得状況出力CSV</td>
                    <td>
					<!--input type="button" name="sample16" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/11');return false;"value="　サンプル　" class="auto-style1"--></td>
	                <td>
					<input type="button" name="output16" value="　出力　" onClick="window.open('<?php $this->config->item('base_url') ?>data_output_csv/index/12','data_output_popup','scrollbars=no,width=640,height=560,').focus();" class="auto-style1"></td>
                </tr>
            </table><span class="auto-style1"><!-- kokyaku_table -->
            <br>
            
            <!-- master -->
            <table>
                <tr>
                    <td class="report_name" style="width: 400px">訪問件数推移資料-月間活動件数縦推移表</td>
                    <td>
					<input type="button" name="sample1" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/1');return false;" value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input  type="button" name="output1" onclick="javascript:location.href='<?php $this->config->item('base_url') ?>data_output/output/1';return false;" value="　出力　" class="auto-style1"></td>
                </tr>
                <tr>
                    <td class="auto-style1" style="width: 400px">訪問件数推移資料-週間活動件数縦推移表</td>
                    <td>
					<input type="button" name="sample2" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/2');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input  type="button" name="output2" onclick="javascript:location.href='<?php $this->config->item('base_url') ?>data_output/output/2';return false;" value="　出力　" class="auto-style1"></td>
                </tr>
                <tr>
                    <td class="auto-style1" style="width: 400px">月間活動区分別行動時間縦推移表</td>
                    <td>
					<input type="button" name="sample3" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/3');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input  type="button" name="output3" onclick="javascript:location.href='<?php $this->config->item('base_url') ?>data_output/output/3';return false;" value="　出力　" class="auto-style1"></td>
                </tr>
                <tr>
                    <td class="auto-style1" style="width: 400px">半期提案進捗状況縦推移表</td>
                    <td>
					<input type="button" name="sample4" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/4');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input  type="button" name="output4" onclick="javascript:location.href='<?php $this->config->item('base_url') ?>data_output/output/4';return false;" value="　出力　" class="auto-style1"></td>
                </tr>
                <tr>
                    <td class="auto-style1" style="width: 400px">企画獲得状況表縦推移表</td>
                    <td>
					<input type="button" name="sample5" onclick="javascript:window.open('<?php $this->config->item('base_url') ?>data_output/sample_preview/5');return false;"value="　サンプル　" class="auto-style1"></td>
                    <td>
					<input  type="button" name="output5" onclick="javascript:location.href='<?php $this->config->item('base_url') ?>data_output/output/5';return false;" value="　出力　" value="　出力　" onClick="window.open('./key/c_client_key.html','','scrollbars=no,width=450,height=400,');" class="auto-style1"></td>
                </tr>
            </table><span class="auto-style1"><!-- master end -->
            <br>

		</div><!-- container end -->
	</div><!-- Main end -->
</form>

</body>

