<?php $this->load->helper('url'); ?>
<!-- parts/top/_calendar.php start -->
<?php $buka_flg = FALSE; ?>
<div class="preview-position">
	<table class="preview-layout">
		<tr class="preview-title">
			<th class="left"><a class="menu-link"
				href="javascript:calendar('<?php echo base_url(); ?>','calendar')">スケジュール</a>
			</th>
            <!-- TODO ここに部下のセレクトボックスを置く -->
            <?php if($buka!=NULL): ?>
			<th class="left" >
                <select name="buka_shbn" style="font-size: 9pt;" onchange="get_buka_calender_top('<?php echo base_url(); ?>');">
                    <option></option>
                    <?php
                        log_message('debug', '-----' . __FILE__ . ':' . __LINE__ . ', $shbn = ' . $shbn);
                        foreach($buka as $buka_value){
                            echo '<option value="' . $buka_value['shbn'] . '" ';
                            if ($shbn == $buka_value['shbn']){
                                echo ' selected ';
                                $buka_flg = TRUE;
                            }
                            echo '>' . $buka_value['shinnm'] . '</option>';
                        }
                    ?>
                </select>
			</th>
            <?php endif; // $buka!=NULL ?>
		</tr>
		<tr class="calendar-preview-head">
			<?php foreach($calendar_data as $d) { ?>
            <th class="center"><a style="color: <?php $clr = '#000'; switch($d[1]) {case '土': $clr = '#0000FF'; break; case '日': $clr = '#FF0000'; break; default: $clr = '#000'; } 
            if ($d['holiday']=='祝') $clr = '#FF0000'; echo $clr; ?>;"
				href="<?php 
                    if($buka_flg) { 
                        echo base_url('index.php/plan_view/index/' . $d['link_day']) . '/' . $shbn;
                    }else{
                        echo base_url('index.php/plan/index/' . $d['link_day']); 
                    }
                    ?>"><?php echo $d[0] ?>(<?php echo $d[1] ?>)</a>
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
