<?php
$base_url = $this->config->item('base_url');
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="Content-Style-Type" content="text/css">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link href="<?php echo $base_url; ?>css/select_client.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $base_url; ?>script/select_client.js"></script>
  <script type="text/javascript" >
  function fn_onload(){
	  top.header.document.getElementById("errmsg").innerHTML = '<?php echo $errmsg; ?>';
	  show_select_client('<?php echo $base_url; ?>', '<?php echo SHOW_SELECT_CLIENT ?>' );
  }
  function window_open(){
  	window.name = 'parent';
  	window.open('<?php echo $form_url; ?>','select_client','scrollbars=no,width=600,height=600,').focus();
  }
  function set_cd(){
	  if(window.document.forms[0].aiteskcd.length) { 
	    var i;
	    for(i = 1; i < document.forms[0].aiteskcd.length; i++){
	      if(document.forms[0].aiteskcd[i].checked){
	        var aitesk = document.forms[0].aiteskcd[i].value;
	        aitesk_val = aitesk.split('|');
	       <?php if($count=="pp"){ ?>
	       		window.parent.opener.document.getElementById('aiteskcd').value = aitesk_val[0];
		        window.parent.opener.document.getElementById('aitesk_name').value = aitesk_val[1];
	        <?php }else if($count=="ppw"){ ?>
	        	window.parent.opener.document.getElementById('view_aiteskcd').value = aitesk_val[0];
		        window.parent.opener.document.getElementById('view_aitesk_name').value = aitesk_val[1];
	        <?php }else{ ?>
		        window.parent.opener.document.getElementById('aiteskcd_<?php echo $count; ?>').value = aitesk_val[0];
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = false;
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').value = aitesk_val[1];
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = true;
		         <?php if($conf!=MY_SELECT_CLIENT_AGENCY){ ?>
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = false;
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').value = aitesk_val[3];
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = true;
	        	<?php } ?>
	        <?php } ?>
	        break;
	      }else{
	      	<?php if($count=='pp'){ ?>
	       		window.parent.opener.document.getElementById('aiteskcd').value = '';
		        window.parent.opener.document.getElementById('aitesk_name').value = '';
	        <?php }else if($count=='ppw'){ ?>
	        	window.parent.opener.document.getElementById('view_aiteskcd').value = '';
		        window.parent.opener.document.getElementById('view_aitesk_name').value = '';
	        <?php }else{ ?>
		      	window.parent.opener.document.getElementById('aiteskcd_<?php echo $count; ?>').value = '';
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = '';
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').value = '';
		        window.parent.opener.document.getElementById('aitesknm_<?php echo $count; ?>').readOnly = '';
		        <?php if($conf!=MY_SELECT_CLIENT_AGENCY){ ?>
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = '';
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').value = '';
		        window.parent.opener.document.getElementById('aiteskrank_<?php echo $count; ?>').readOnly = '';
		        <?php } ?>
		    <?php } ?>
	      }
	    }
	    
	  }
	  // 画面閉じる
	  self.close();
  
  }
  </script>

</head>

<body onload="fn_onload();">
<form action="<?php echo $form ?>"  method="POST">
<input type="hidden" name="set" value="set">
<input type="hidden" name="aiteskcd" id="aiteskcd">
<div id="Main">
<br>
<div id="container">
<table style="margin-left: 350px;">
	<tr>
		<td >
			<input type="button" name="select" id="select" value="決定" onClick="set_cd();"> 
		</td>
		<td>
		</td>
		<td>
			<input type="button" name="close" id="close" value="閉じる" onClick="window.close();">
		</td>
	</tr>
</table>
<br>
<table style="margin-left: 108px; border-collapse:collapse;">
<tr>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;width:200px;">相手先名</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
	<th style="border:1px solid #000000;padding:5px;width:200px;">相手先名</th>
	<th style="border:1px solid #000000;padding:5px;">&nbsp;</th>
</tr>
<?php for ( $i=1; $i <= 30; ) { ?>
	<tr>
	<?php for ( $col=1; $col <= 2; $col++ ) { ?>
		
		<?php if(isset($list_table)){ ?>
			<?php $dispFlg = false;
				$rank = NULL;
				$j=0;
			foreach ( $list_table as $key => $val ){
				if ($val['hyojun'] == $i){
				$j++;
				if($j==2){
				
				}else{
				$dispFlg = true;?>
				<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="radio" name="aiteskcd" id="aiteskcd" value="<?php echo $val['aiteskcd'].'|'.$val['aitesknm'].'|'.$val['hanhoncd'].'|'.$val['aiteskrank']; ?>"></td>
				<td style="border:1px solid #000000;padding:5px;background-color:white;"><?php echo $val['aitesknm']; ?></td>
				<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索" onClick="location.href='<?php echo $form_url.$i.'/'.$count.'/'; ?>'"></td>
			<?php	} 
				 } 
				} 
				if (! $dispFlg ){?>
				<td style="border:1px solid #000000;padding:5px;background-color:white;">&nbsp;</td>
				<td style="border:1px solid #000000;padding:5px;background-color:white;">&nbsp;</td>
				<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索"  onClick="location.href='<?php echo $form_url.$i.'/'.$count.'/'; ?>'"></td>
			<?php } ?>
			
		<?php } else { ?>
			<td style="border:1px solid #000000;padding:5px;background-color:white;">&nbsp;</td>
			<td style="border:1px solid #000000;padding:5px;background-color:white;">&nbsp;</td>
			<td style="border:1px solid #000000;padding:5px;background-color:white;"><input type="button" name="search" id="search" value="検索" onClick="location.href='<?php echo $form_url.$i.'/'.$count.'/'; ?>'"></td>
		<?php }?>
		
		
		<?php $i++; ?>
	<?php } ?>
	</tr>
<?php } ?>

</table>

</div>

</div>
</form>
</body>
</html>
