<!-- MAIN -->
<form action="<?php echo $form_url; ?><?php echo $type ?>" method="POST">
	<!-- MAIN -->
	<div id="Main">
    <br>
		<div id="container">
            <!-- master -->
            	<?php if($type == 6 || $type == 8 || $type == 9){?>
            	社番：<input name="shbn" type="text" /><br />
            	<?php }?>
            	 	<?php if($type == 6){?>
            	日付：<input name="date_from" type="text" />～<input name="date_to" type="text" />
            	<?php }?>
            	<?php if($type == 7){?>
           登録日録日：<input name="date" type="text" />
            	<?php }?>
            	<?php if($type == 8 || $type == 9){?>
            	年月：<input name="date" type="text" />
            	<?php }?>
            	<input type="submit" value="ダウンロード" />
		</div><!-- container end -->
	</div><!-- Main end -->
</form>


