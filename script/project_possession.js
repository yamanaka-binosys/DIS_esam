//------------------------------------------
//  行追加処理
//
function add_row(app_url) {
  var val = $('#add_view_no').text();  //変更ドロップダウン名保持

  //送信
  $.ajax({
    type: "POST",
    url: app_url+'/project_possession/ajax_add_table_data',
    async: false,
    data: {add_view_no:val},
    datatype: "html",
    success: add_row_res
  });
}
//add_rowでのデータ送信後呼び出し関数
function add_row_res(data) {
  var add_view_no = $('#add_view_no').text();
  $('#add_view_no').text(add_view_no+1); //次のview_noセット

  $('#project_possession_data').append(data);
}

//------------------------------------------
//行削除処理
//
function dell_row() {
  //確認ダイアログ
  if(confirm('削除してよろしいですか？')){
    $('form').find("input:checked").each(function() {
      $('.row_'+$(this).val()).remove(); //指定行削除
			var errmsg = parent.header.document.getElementById('errmsg');
      errmsg.innerHTML = "削除完了";
			errmsg.setAttribute('class', 'msg-info');
		  errmsg.setAttribute('className', 'msg-info');
    });
  }
}

//------------------------------------------
//  ドロップダウン変更処理
//
function reload_dropdown(app_url,view_no) {
  var val = $('#daibunrui_list'+view_no).children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('item_list'+view_no);  //変更ドロップダウン名保持

  //送信
  $.ajax({
    type: "POST",
    url: app_url+'/project_possession/ajax_select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
}
//reload_dropdownでのデータ送信後呼び出し関数
function reload_dropdown_res(data) {
  var change_position = $('#change_position').text(); //変更ドロップダウン名取得

  document.getElementById(change_position).innerHTML  = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
function target_list_search() {
  //表示ボタンヴァリデーションチェック
  var form = $('form');  //
  var res = formValidation(form, ".s_group");
  if(!res) return;

  document.forms[0].set.value = 1;
  var app_url = parent.content.document.forms[0].app_url.value;
  document.forms[0].action = app_url+"/project_possession/search";
  document.forms[0].submit();
}

//------------------------------------------
//企画獲得(表示)
function show_target_list() {
  //「指定年月のデータ」ボタンヴァリデーションチェック
  var form = $('form');  //
  var res = formValidation(form, ".v_group");
  if(!res) return;

  var app_url = parent.content.document.forms[0].app_url.value;
  parent.content.document.forms[0].action = app_url+"/project_possession_view/show_target_list";
  parent.content.document.forms[0].submit();
}

//------------------------------------------
function select_client(count,kbn,base_url){
	window.name = 'parent';
  	window.open(base_url+'index.php/select_client/index/'+kbn+'/'+count,'select_client','scrollbars=no,width=800,height=650,').focus();
	
/*
	var app_url = $('#app_url').val();  //月
	//var keep_val = "year,month,aitesk_name,aiteskcd,view_year,view_month,view_aitesk_name,view_aiteskcd,target:"+$('#year').val()+","+$('#month').val()+","+$('#aitesk_name').val()+","+$('#aiteskcd').val()+","+$('#view_year').val()+","+$('#view_month').val()+","+$('#view_aitesk_name').val()+","+$('#view_aiteskcd').val()+","+target;
	var keep_val = "year,month,view_year,view_month:"+$('#year').val()+","+$('#month').val()+","+$('#view_year').val()+","+$('#view_month').val();

	// ボタン入れ替え
	$(top.header.document);
	$('#decision').value="確定";
	document.getElementById('referer').value = param;
	//document.getElementById('target').value = target;
	document.getElementById('keep_val').value = keep_val;
	document.project_possession_form.action = app_url + '/select_client/index';
	document.project_possession_form.submit();
	*/
}
//------------------------------------------
function new_action_view(base_url,head_name){
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url+"?errmsg="+encodeURI($('#output').text())+"&errclass="+encodeURI($('#errclass').text());

	window.parent.document.getElementById('baseset').rows = "117, *";
	$('.numInputOnly').keydown(acceptNumOnly);
}

function acceptNumOnly(event) {
  var c = event.keyCode
  return (
  (96 <= c && c <= 105) ||   // テンキー0～9
  (48 <= c && c <= 57 ) ||        // メインキー0～9
  (37 <= c && c <= 40 ) ||  // 矢印キー
  (c == 13 || c == 9  ||  c == 8))  // Enter, Tab, backspace
}
