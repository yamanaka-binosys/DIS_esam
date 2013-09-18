<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
$filename = end(explode('/',$tempfile));
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>情報メモ</title>
<link href="<?php echo $base_url; ?>css/memo.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	function fn_onload() {
	<?php if (isset($errmsg)) { ?>
		if (window.opener) {
			window.opener.document.forms[0].submit();
		}
	<?php } ?>
	}
</script>

</head>
<body onload="fn_onload();">
<!-- MAIN -->
<div id="Main">
<div id="container">
<div id="errmsg" style="text-align:center; padding: 3px;">
<span style="color:green; font-weight: bold;">
<?php if (isset($errmsg)) { echo $errmsg; } else { echo "&nbsp;"; }  ?>
</span>
</div>
<form action="delete_select_type" method="POST">
<div id="page1">
<table>
<tr>
<td>
<font style="font-size:25px; font-family: sans-serif; margin-right: 10px;">情報メモ</font>
</td>
<td style="vertical-align: bottom;">
<input type="submit" value="削除" />
</td>
</tr>
<tr>
<td><table width="400px" style="border:none;padding:5px;">
<tr>
<td style="padding-right:10px;;vertical-align:middle;">
<table width="110px" >
<tr>
<td style="padding-right:5px;;vertical-align:middle;margin-left:-4px;">
●件名</td>
</tr>
</table>
</td>
<td>
<table width="290px" >
<tr>
<td style="width:300px;text-align:left;margin-left:-10px;">
<?php echo $knnm; ?>
<input type="hidden" name="k_name" size="40"  value="<?php echo $knnm; ?>">
<input type="hidden" name="jyohonum" size="40"  value="<?php echo $jyohonum; ?>">
<input type="hidden" name="knnm" size="40"  value="<?php if(isset($knnm) && $knnm!=""){echo $knnm;} ?>">
<input type="hidden" name="aitesknm" size="40"  value="<?php if(isset($aitesknm) && $aitesknm!=""){echo $aitesknm;} ?>">
<input type="hidden" name="yksyoku" size="40"  value="<?php if(isset($yksyoku) && $yksyoku!=""){echo $yksyoku;} ?>">
<input type="hidden" name="name" size="40"  value="<?php if(isset($name) && $name!=""){echo $name;} ?>">
<input type="hidden" name="jyohokbnm" size="40"  value="<?php if(isset($jyohokbnm) && $jyohokbnm!=""){echo $jyohokbnm;} ?>">
<input type="hidden" name="hinsyukbnm" size="40"  value="<?php if(isset($hinsyukbnm) && $hinsyukbnm!=""){echo $hinsyukbnm;} ?>">
<input type="hidden" name="tishokbnm" size="40"  value="<?php if(isset($tishokbnm) && $tishokbnm!=""){echo $tishokbnm;} ?>">
<input type="hidden" name="maker" size="40"  value="<?php if(isset($maker) && $maker!=""){echo $maker;} ?>">
<input type="hidden" name="tempfile" size="40"  value="<?php if(isset($tempfile) && $tempfile!=""){echo $tempfile;} ?>">
<input type="hidden" name="s_year" size="40"  value="<?php if(isset($s_year) && $s_year!=""){echo $s_year;} ?>">
<input type="hidden" name="s_month" size="40"  value="<?php if(isset($s_month) && $s_month!=""){ echo $s_month; } ?>">
<input type="hidden" name="s_day" size="40"  value="<?php if(isset($s_day) && $s_day!=""){ echo $s_day ;} ?>">
<input type="hidden" name="jyohoniyo" size="40"  value="<?php if(isset($jyohoniyo) && $jyohoniyo!=""){ echo $jyohoniyo; } ?>">
<input type="hidden" name="posteddate" size="40"  value="<?php if(isset($posteddate) && $posteddate!=""){ echo $posteddate; } ?>">
<input type="hidden" name="shbn" size="40"  value="<?php if(isset($shbn) && $shbn!=""){ echo $shbn; } ?>">
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>

<tr>
<td >
<table  width="115px">
<tr>
<td  style="padding-left:3px;;vertical-align:middle;">
●入手元</td>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="400px" style="border:none;padding:5px;">
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table  width="115px" >
<tr>
<td>
&emsp;社名</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:150px;text-align:left;;">
<?php echo $aitesknm; ?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table  width="115px" >
<tr>
<td>
&emsp;役職</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:150px;text-align:left;;">
<?php echo $yksyoku; ?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td style="padding-left:5px;;vertical-align:middle;">
<table width="115px" >
<tr>
<td>
&emsp;氏名</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:150px;text-align:left;;">
<?php echo $name; ?></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="400px" style="border:none;padding:5px;">
<tr>
<td >
<table width="120px" >
<tr>
<td>
●情報区分</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:170px;text-align:left;;">
<?php echo $jyohokbnm; ?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table width="120px" >
<tr>
<td>
●品種区分</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:170px;text-align:left;;">
<?php echo $hinsyukbnm; ?>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td >
<table width="120px" >
<tr>
<td>
●対象区分</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:170px;text-align:left;;">
<?php echo $tishokbnm; ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="400px" style="border:none;padding:5px;">
<tr>
<td>
<table width="120px" >
<tr>
<td>
●メーカー</td>
</tr>
</table>
</td>
<td>
<table width="310px" >
<tr>
<td style="width:170px;text-align:left;;">
<?php echo $maker; ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table width="400px" style="border:none;padding:5px;">
<tr>
<td>
<table width="120px" >
<tr>
<td>
●添付ファイル</td>
</tr>
</table>
</td>
<td >
<table width="310px" >
<tr>
<td style="width:170px;text-align:left;;">
<a href="<?php echo $tempfile; ?>"><?php echo $filename; ?></a></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table style="border;">
<tr>
<td>
<table width="120px" >
<tr>
<td>
●掲載期限</td>
</tr>
</table>
</td>
<td>
<table width="" >
<tr>
<td>
<?php echo $s_year; ?> 年 <?php echo $s_month; ?> 月 <?php echo $s_day; ?> 日
</td>
<td>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td><table style="border:;"  >
<tr>
<td>
<table width="120px" >
<tr>
<td>
●情報メモ内容
</td>
</tr>
</table>
</td>
<td>
<table width="400px" >
<tr>
<td width="400px">
<?php echo $jyohoniyo; ?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>
</form>
</div>
</div>
</form>