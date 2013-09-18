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
    url: app_url+'index.php/checker_search_ka/select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}
//reload_dropdownでのデータ送信後呼び出し関数
function reload_dropdown_res(data) {

  var change_position = $('#change_position').text(); //変更ドロップダウン名取得

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
//  ドロップダウン変更処理
//
function memo_reload_dropdown_unit(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selectedアイテムの値
  var val2 = $('#bu_list').children(':selected').val(); //selectedアイテムの値
  $('#change_position').text('busyo_table');  //変更箇所名保持

  if( val2 != ""){
	  //送信
	  $.ajax({
	    type: "POST",
	     url: app_url+'index.php/checker_search_ka/select_unit_item_list',
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
