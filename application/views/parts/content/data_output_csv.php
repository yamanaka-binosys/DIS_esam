<?php
$this->load->helper(array('html', 'form'));
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-strict'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link rel="stylesheet" type="text/css" href="<?php echo $base_url; ?>css/data_output_csv.css">
<link href="<?php echo $base_url; ?>css/jquery-ui-1.8.18.custom.css" rel="stylesheet" media="screen">
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/data_output_csv.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-1.8.18.custom.min.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/jquery-ui-i18n.js"></script>
<script type="text/javascript" src="<?php echo $base_url; ?>script/form_validation.js"></script>
</head>
<body onload="init_data_output_csv();">
<div id="Header">
	<img src="<?php echo $base_url; ?>images/data_output.gif" alt=""/>
<div id="errmsg" style="color: red;">
<?php echo $errmsg; ?>&nbsp;
</div>
</div>

<!-- MAIN -->
<form id="csv_form" action="<?php echo $form_url; ?><?php echo $type ?>" method="POST">
<div id="change_position" style="display:none"></div>
<div id="change_position2" style="display:none"></div>
	<!-- MAIN -->
	<div id="Main">
		<div id="container">
            <!-- master -->

							<!-- 営業日報 -->
            	<?php if($type == 6){?>
		            <div class="title"><b>営業日報</b></div>
								<div class="search-box">
            		日付：<input id="date_from" title="開始日" class="required" name="date_from" type="text" />&nbsp;～&nbsp;<input id="date_to" name="date_to"  title="終了日" class="required" type="text" /><br>
            		</div>
								<?php }?>

							<!-- 情報メモ -->
            	<?php if($type == 7){?>
		            <div class="title"><b>情報メモ</b></div>
								<div class="search-box">
							<table>
							<tr><td><table>
										<tr>
											<th>日付</th>
											<td><input id="date_from" name="date_from" title="開始日" type="text" class="required" value="<?php echo set_value('date_from'); ?>" maxlength="8" />～<input id="date_to" name="date_to" type="text"  value="<?php echo set_value('date_to'); ?>" class="required" maxlength="8" title="終了日"/>
											</td>
										</tr>
										<tr>
											<th>件名</th>
											<td><input name="title" size="40" type="text" value="<?php echo set_value('title'); ?>" /></td>
										</tr>
										<tr>
											<th>内容</th>
											<td><input name="body" size="40" type="text" value="<?php echo set_value('body'); ?>" /></td>
										</tr>
										<?php foreach($kbn_data as $kbn)  { ?>
										<tr>
											<th><?php echo $kbn['koumoknm'] ?></th>
											<td>
												<select name="<?php echo $kbn_names[$kbn['kbnid']] ?>"　size="30" style="width:150px;">
													<option /></option>
													<?php foreach($kbn['kbn'] as $k) { ?>
													<option value="<?php echo $k['kbncd'] ?>"><?php echo $k['ichiran'] ?></option>
													<?php } ?>
												</select>
											</td>
										</tr>
										
										<?php } ?>
										</table></td></tr>
										<tr>
											<td>
												<div id="busyo_table">
												<?php echo $search_result_busyo_table; ?>
												</div>
											</td>
										</tr>
									</table>
									</div>
            	<?php }?>


							<!-- カレンダーA4、カレンダーA3 -->
            	<?php if($type == 8 || $type == 9){?>
		            <!-- カレンダーA4、カレンダーA3 -->
								<div class="title"><b><?php if($type==8) { echo "カレンダーA4"; } else { echo "カレンダーA3"; } ?></b></div>
								<div class="search-box">
								<table>
									<tr>
									<td>
										<select name="s_year">
											<option value="2012" >2012</option>
											<option value="2013" >2013</option>
											<option value="2014" >2014</option>
											<option value="2015" >2015</option>
											<option value="2016" >2016</option>
											<option value="2017" >2017</option>
											<option value="2018" >2018</option>
											<option value="2019" >2019</option>
											<option value="2020" >2020</option>
											<option value="2021" >2021</option>
											<option value="2022" >2022</option>
										</select> 年
									</td>
									<td>
										<select name="s_month">
											<option value="01" >1</option>
											<option value="02" >2</option>
											<option value="03" >3</option>
											<option value="04" >4</option>
											<option value="05" >5</option>
											<option value="06" >6</option>
											<option value="07" >7</option>
											<option value="08" >8</option>
											<option value="09" >9</option>
											<option value="10" >10</option>
											<option value="11" >11</option>
											<option value="12" >12</option>
										</select> 月
									</td>
									</tr>
								</table>
            		</div>
									<?php }?>

            	<?php if($type == 10){?>
		            <!-- 日報一覧 -->
					<div class="title"><b>日報一覧</b></div>
					<div class="search-box">
					
					<span style="padding: 0 28px 0 14px;">出力期間</span>
					<input id="date_from" name="date_from" title="開始日" type="text" class="required" value="<?php echo set_value('date_from'); ?>" maxlength="8" />&nbsp;～&nbsp;<input id="date_to" name="date_to" type="text"  value="<?php echo set_value('date_to'); ?>" class="required" maxlength="8" title="終了日"/>
					<div id="busyo_table">
            		<?php echo $search_result_busyo_table; ?>
            		</div>
								</div>
            	<?php }?>

							<!-- 商談履歴 -->
            	<?php if($type == 11){?>
		            
		<div class="title"><b>商談履歴</b></div>
		<div class="search-box">
					<table style="padding: 0 0 0 10px">
					<tr>
					<th width="82px">開始日</th>
					<td style="width:100px;;">
						<select name="s_year">
							<option value="2012" >2012</option>
							<option value="2013" >2013</option>
							<option value="2014" >2014</option>
							<option value="2015" >2015</option>
							<option value="2016" >2016</option>
							<option value="2017" >2017</option>
							<option value="2018" >2018</option>
							<option value="2019" >2019</option>
							<option value="2020" >2020</option>
							<option value="2021" >2021</option>
							<option value="2022" >2022</option>
						</select> 年
					</td>
					<td style="width:85px;;">
						<select name="s_month">
							<option value="01" >1</option>
							<option value="02" >2</option>
							<option value="03" >3</option>
							<option value="04" >4</option>
							<option value="05" >5</option>
							<option value="06" >6</option>
							<option value="07" >7</option>
							<option value="08" >8</option>
							<option value="09" >9</option>
							<option value="10" >10</option>
							<option value="11" >11</option>
							<option value="12" >12</option>
						</select> 月
					</td>
					<td style="width:85px;;">
						<select name="s_day">
							<option value="01" >1</option>
							<option value="02" >2</option>
							<option value="03" >3</option>
							<option value="04" >4</option>
							<option value="05" >5</option>
							<option value="06" >6</option>
							<option value="07" >7</option>
							<option value="08" >8</option>
							<option value="09" >9</option>
							<option value="10" >10</option>
							<option value="11" >11</option>
							<option value="12" >12</option>
							<option value="13" >13</option>
							<option value="14" >14</option>
							<option value="15" >15</option>
							<option value="16" >16</option>
							<option value="17" >17</option>
							<option value="18" >18</option>
							<option value="19" >19</option>
							<option value="20" >20</option>
							<option value="21" >21</option>
							<option value="22" >22</option>
							<option value="23" >23</option>
							<option value="24" >24</option>
							<option value="25" >25</option>
							<option value="26" >26</option>
							<option value="27" >27</option>
							<option value="28" >28</option>
							<option value="29" >29</option>
							<option value="30" >30</option>
							<option value="31" >31</option>
						</select> 日
					</td>
					</tr>
					<tr>
					<th>終了日</th>
					<td style="width:95px;;">
						<select name="e_year">
							<option value="2012" >2012</option>
							<option value="2013" >2013</option>
							<option value="2014" >2014</option>
							<option value="2015" >2015</option>
							<option value="2016" >2016</option>
							<option value="2017" >2017</option>
							<option value="2018" >2018</option>
							<option value="2019" >2019</option>
							<option value="2020" >2020</option>
							<option value="2021" >2021</option>
							<option value="2022" >2022</option>
						</select> 年
					</td>
					<td style="width:65px;;">
						<select name="e_month">
							<option value="01" >1</option>
							<option value="02" >2</option>
							<option value="03" >3</option>
							<option value="04" >4</option>
							<option value="05" >5</option>
							<option value="06" >6</option>
							<option value="07" >7</option>
							<option value="08" >8</option>
							<option value="09" >9</option>
							<option value="10" >10</option>
							<option value="11" >11</option>
							<option value="12" >12</option>
						</select> 月
					</td>
					<td style="width:65px;;">
						<select name="e_day">
							<option value="01" >1</option>
							<option value="02" >2</option>
							<option value="03" >3</option>
							<option value="04" >4</option>
							<option value="05" >5</option>
							<option value="06" >6</option>
							<option value="07" >7</option>
							<option value="08" >8</option>
							<option value="09" >9</option>
							<option value="10" >10</option>
							<option value="11" >11</option>
							<option value="12" >12</option>
							<option value="13" >13</option>
							<option value="14" >14</option>
							<option value="15" >15</option>
							<option value="16" >16</option>
							<option value="17" >17</option>
							<option value="18" >18</option>
							<option value="19" >19</option>
							<option value="20" >20</option>
							<option value="21" >21</option>
							<option value="22" >22</option>
							<option value="23" >23</option>
							<option value="24" >24</option>
							<option value="25" >25</option>
							<option value="26" >26</option>
							<option value="27" >27</option>
							<option value="28" >28</option>
							<option value="29" >29</option>
							<option value="30" >30</option>
							<option value="31" >31</option>
						</select> 日
					</td>
					</tr>
					</table>
					<div id="busyo_table">
            		<?php echo $search_result_busyo_table; ?>
            		</div>
</div>
            	<?php }?>
            	<!-- 企画獲得情報 -->
            	<?php if($type == 12){?>
            			            <div class="title" style="width:100px"><b>企画獲得</b></div>
            			            
								<div class="search-box">
							<table>
							<tr><td><table style="margin-left:12px">
										<tr>
											<th>日付</th>
											<td >
												<select name="s_year" style="margin-left:30px">
													<option value="2012" >2012</option>
													<option value="2013" >2013</option>
													<option value="2014" >2014</option>
													<option value="2015" >2015</option>
													<option value="2016" >2016</option>
													<option value="2017" >2017</option>
													<option value="2018" >2018</option>
													<option value="2019" >2019</option>
													<option value="2020" >2020</option>
													<option value="2021" >2021</option>
													<option value="2022" >2022</option>
												</select> 年
												<select name="s_month">
													<option value="01" >1</option>
													<option value="02" >2</option>
													<option value="03" >3</option>
													<option value="04" >4</option>
													<option value="05" >5</option>
													<option value="06" >6</option>
													<option value="07" >7</option>
													<option value="08" >8</option>
													<option value="09" >9</option>
													<option value="10" >10</option>
													<option value="11" >11</option>
													<option value="12" >12</option>
												</select> 月　～
												<select name="e_year">
													<option value="2012" >2012</option>
													<option value="2013" >2013</option>
													<option value="2014" >2014</option>
													<option value="2015" >2015</option>
													<option value="2016" >2016</option>
													<option value="2017" >2017</option>
													<option value="2018" >2018</option>
													<option value="2019" >2019</option>
													<option value="2020" >2020</option>
													<option value="2021" >2021</option>
													<option value="2022" >2022</option>
												</select> 年
												<select name="e_month">
													<option value="01" >1</option>
													<option value="02" >2</option>
													<option value="03" >3</option>
													<option value="04" >4</option>
													<option value="05" >5</option>
													<option value="06" >6</option>
													<option value="07" >7</option>
													<option value="08" >8</option>
													<option value="09" >9</option>
													<option value="10" >10</option>
													<option value="11" >11</option>
													<option value="12" >12</option>
												</select> 月
											</td>

										</tr>
										
										
										</table></td></tr>
										<tr>
											<td>
												<div id="busyo_table">
												<?php echo $search_result_busyo_table; ?>
												</div>
											</td>
										</tr>
										</table>
										<div id="aitesk_table">
										<table style="margin-left:17px">
										<tr>
											<th>販売店名</th>
											<td>
											<select name="aitesknm" style="width:148px;margin-left:30px">
													<option value="" ></option>
													<?php foreach($aitesk_list as $key => $value) { ?>
														<option value="<?php echo $value['aitesknm']; ?>" ><?php echo $value['aitesknm'];  ?></option>
													<? } ?>
											</select>
											</td>
										</tr>
										</table>
										</div>
									</table>
									</div>
            	
            	<?php }?>
		</div><!-- container end -->
			<div class="submit-box">
    	<input id="dl-btn" type="submit" value="ダウンロード" onclick="keep_val['no_head'] = 1; return formValidation(document.getElementById('csv_form'));"/>
			</div>
	</div><!-- Main end -->
</form>


</body>

            		