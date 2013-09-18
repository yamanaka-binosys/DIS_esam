function search(id)
{
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"";
}

function init_data_output_csv() {
	$('#date_from').datepicker($.datepicker.regional['ja']);
	$('#date_to').datepicker($.datepicker.regional['ja']);
}


//------------------------------------------
//  ドロップダウン変更処理
//
function reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持
  
  if(val != ""){
  //送信
  $.ajax({
    type: "POST",
    url: app_url+'index.php/data_output_csv/select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}
//reload_dropdownでのデータ送信後呼び出し関数
function reload_dropdown_res(data) {

  var change_position = $('#change_position').text(); //変更名取得

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
//  ドロップダウン変更処理
//
function reload_dropdown_unit(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  var val2 = $('#bu_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持

  if( val != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_unit_list',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });
  }
}
//reload_dropdownでのデータ送信後呼び出し関数
function reload_dropdown_res_unit(data) {

  var change_position = $('#change_position').text(); //変更名取得

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
//  ドロップダウン変更処理
//
function n_reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持
  $('#change_position2').text('aitesk_table');  //変更箇所名保持
  
  if(val != ""){
  //送信
  $.ajax({
    type: "POST",
    url: app_url+'index.php/data_output_csv/select_item_n_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  $.ajax({
    type: "POST",
    url: app_url+'index.php/data_output_csv/select_item_n_aitesk_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res2
  });
  }
}

//reload_dropdownでのデータ送信後呼び出し関数
function reload_dropdown_res2(data) {

  var change_position = $('#change_position2').text(); //変更名取得
  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
//  ドロップダウン変更処理
//
function n_reload_dropdown_unit(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  var val2 = $('#bu_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持
  $('#change_position2').text('aitesk_table');  //変更箇所名保持

  if( val != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_n_unit_list',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });
	  
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_n_aitesk_list',
	    data: {selected_val:val},
	    datatype: "html",
	    success: reload_dropdown_res2
	  });
  }
}

//------------------------------------------
//  ドロップダウン変更処理
//
function n_reload_dropdown_user(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  var val2 = $('#bu_list').children(':selected').val(); //selectedアイテムの値
   var val3 = $('#ka_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持
  $('#change_position2').text('aitesk_table');  //変更箇所名保持

  if( val != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_n_user_list',
	    data: {selected_val:{val:val,val2:val2,val3:val3}},
	    datatype: "html",
	    success: reload_dropdown_res_user
	  });
	  
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_n_aitesk_list',
	    data: {selected_val:val},
	    datatype: "html",
	    success: reload_dropdown_res2
	  });
  }
}
//reload_dropdownでのデータ送信後呼び出し関数
function reload_dropdown_res_user(data) {

  var change_position = $('#change_position').text(); //変更名取得

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
//  ドロップダウン変更処理
//
function n_reload_dropdown_aitesk(app_url) {
  var val = $('#shbn_list').children(':selected').val(); //selectedアイテムの値

  $('#change_position2').text('aitesk_table');  //変更箇所名保持

  if( val != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_n_aitesk_change_list',
	    data: {selected_val:val},
	    datatype: "html",
	    success: reload_dropdown_res2
	  });
  }
}
//------------------------------------------
//  ドロップダウン変更処理
//
function memo_reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持
  
  if(val != ""){
  //送信
  $.ajax({
    type: "POST",
    url: app_url+'index.php/data_output_csv/memo_select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}

//------------------------------------------
//  ドロップダウン変更処理
//
function memo_n_reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持
  
  if(val != ""){
  //送信
  $.ajax({
    type: "POST",
    url: app_url+'index.php/data_output_csv/select_item_memo_n_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}


//------------------------------------------
//  ドロップダウン変更処理
//
function memo_n_reload_dropdown_unit(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  var val2 = $('#bu_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持

  if( val != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_memo_n_unit_list',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });
  }
}

//------------------------------------------
//  ドロップダウン変更処理
//
function memo_n_reload_dropdown_user(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  var val2 = $('#bu_list').children(':selected').val(); //selectedアイテムの値
   var val3 = $('#ka_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持

  if( val != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_output_csv/select_item_memo_n_user_list',
	    data: {selected_val:{val:val,val2:val2,val3:val3}},
	    datatype: "html",
	    success: reload_dropdown_res_user
	  });
  }
}
