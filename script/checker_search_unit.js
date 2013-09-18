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
    url: app_url+'index.php/checker_search_unit/select_item_list',
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
function reload_dropdown_unit(app_url) {
 
}