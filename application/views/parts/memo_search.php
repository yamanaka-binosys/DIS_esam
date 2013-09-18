<?php
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>情報メモ</title>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/memo.css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/memo.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>


 <script>
 <!--
  function fn_onload(){
   top.header.document.getElementById("errmsg").innerText = '<?php echo $errmsg; ?>';
	 top.header.document.getElementById("errmsg").textContent = '<?php echo $errmsg; ?>';
   top.header.document.getElementById("errmsg").className = '<?php echo $msg_type; ?>';
  }
    -->
  </script>

</head>
<body onload="fn_onload();">
<!-- MAIN -->
<div id="Main">
<div id="container">

<form action="" method="POST">
<input type="hidden" name="search" value="検索"/>
<div id="change_position" style="display:none"></div>
<div id="search-form">

<?php echo $k_name_table; ?>

<?php echo $kbn_table; ?>
	
	
<?php echo $maker_table; ?>
	
<?php echo $info_table; ?>

<?php if(isset($busyo_flg) && $busyo_flg =="1"){ ?>

<div class="busyo_table" id="busyo_table">
<?php echo $search_result_busyo_table; ?>
</div>

<?php } ?>
<?php echo $date_table; ?>
<?php echo $search_table; ?>


<?php echo $file_table; ?>
<input type="hidden" name="p_memo_title" id="p_memo_title" value="">
</div>
</form>
</div>
</div>
<?php
	// formタグの挿入
	if(! is_null($form))
	{
		echo "</form>";
	}
?>

