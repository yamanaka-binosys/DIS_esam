<?php
log_message('debug',"===== Start menu_admin.php =====");
$this->load->helper('html');
$base_url = $this->config->item('base_url');
$meta = $this->config->item('c_meta');
log_message('debug',"\$base_url = $base_url");
?>

<?php echo doctype('html4-frame'); ?>
<html>
<head>
<?php echo meta($meta); ?>
<link href="<?php echo $base_url; ?>css/menu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo $base_url; ?>script/menu.js"></script>
</head>

<body>
<div id="LMenu">
<br>
<b>メニュー</b>
<br>
<ul class="parent">
<li><a href="javascript:top_general('<?php echo $base_url ?>','<?php echo SHOW_TOP_ADMIN ?>')">トップ</a></li>
<li><a href="javascript:calendar('<?php echo $base_url ?>','<?php echo SHOW_CALENDAR ?>')">カレンダー</a></li>
<li><a href="javascript:scheduling('<?php echo $base_url ?>','<?php echo SHOW_REGULAR_PLAN ?>')">定期予定設定</a></li>
<li><a href="javascript:s_rireki('<?php echo $base_url ?>','<?php echo SHOW_S_RIREKI ?>')">商談履歴</a></li>
<li><a href="javascript:todo('menu_todo')">TO DO</a>
<div id="menu_todo" style="display:none">
<ul class="sub">
<li><a href="javascript:todo_add('<?php echo $base_url ?>','<?php echo SHOW_TODO_A ?>')">∟登録</a></li>
<li><a href="javascript:todo_update('<?php echo $base_url ?>','<?php echo SHOW_TODO_U ?>')">∟更新</a></li>
</ul>
</div>
</li>
<li><a href="javascript:data_memo('menu_memo')">情報メモ</a>
<div id="menu_memo" style="display:none">
<ul class="sub">
<li><a href="javascript:memo_add('<?php echo $base_url ?>','<?php echo SHOW_MEMO_A ?>')">∟登録</a></li>
<li><a href="javascript:memo_update('<?php echo $base_url ?>','<?php echo SHOW_MEMO_SU ?>')">∟変更</a></li>
<li><a href="javascript:memo_delete('<?php echo $base_url ?>','<?php echo SHOW_MEMO_SD ?>')">∟削除</a></li>
<li><a href="javascript:memo_search('<?php echo $base_url ?>','<?php echo SHOW_MEMO_S ?>')">∟検索</a></li>
</div>
<li><a href="javascript:project_possession('menu_project_possession')">企画獲得</a>
<div id="menu_project_possession" style="display:none">
<ul class="sub">
<li><a href="javascript:project_possession_add('<?php echo $base_url ?>','<?php echo SHOW_PROJECT_P ?>')">∟登録</a></li>
<li><a href="javascript:project_possession_view('<?php echo $base_url ?>','<?php echo SHOW_PROJECT_V ?>')">∟表示</a></li>
</ul>
</div>
</li>
<li><a href="javascript:data_output('<?php echo $base_url ?>','<?php echo SHOW_DATA_OUTPUT ?>' )">情報出力</a></li>
<li><a href="javascript:help('<?php echo $base_url ?>')">ヘルプ</a></li>
</ul>
<br />
<input type="button" value="ログアウト" onclick="javascript:logout('<?php echo $base_url ?>')">
<br />
<br />
</div>
</div>
</form>
</body>
</html>
