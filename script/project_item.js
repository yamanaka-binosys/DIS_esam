//------------------------------------------
//  行追加処理
//

$(document).ready(function(){
  //追加ボタンがクリックされた時の処理
  $("input[name=addbtn]").live('click', function(event) {
    var btn_id  = $(this).attr("id");
    add_row(btn_id);
  });
  //削除ボタンがクリックされた時の処理
  $("input[name=dellbtn]").live('click', function(event) {
    var btn_id  = $(this).attr("id");
    dell_row(btn_id);
  });
});

//-----------------------------------------
function add_row(btn_id) {
  var app_url = $('#app_url').text();  //
  var view_no = btn_id.split("_");
  view_no = view_no[1];
  var num = $('#addline_'+view_no).val();  //挿入数
  $('#add_view_no').text(view_no); //view_noセット

  //送信
  var res = $.ajax({
    type: "POST",
    url: app_url+'/project_item/ajax_add_row',
    async: true,
    data: {add_num:num,view_no:view_no},
    datatype: "html",
    success: add_row_res
  });
}
//add_rowでのデータ送信後呼び出し関数
function add_row_res(data) {
  //テーブルに行挿入
  var add_view_no = $('#add_view_no').text();
  $('#no_'+add_view_no).after(data);

  //id番号振りなおし
  $("[name=no]").attr("id",function(i){ return "no_" + (parseInt($('#start_no').val()) + ++i); });
  //$("[name=view_no]").attr("val",function(i){ return (parseInt($('#start_no').val()) + ++i); });
  $("[name=addbtn]").attr("id",function(i){ return "addbtn_" + (parseInt($('#start_no').val()) + ++i); });
  $("[name=addline]").attr("id",function(i){ return "addline_" + (parseInt($('#start_no').val()) + ++i); });
  $("[name=dellbtn]").attr("id",function(i){ return "dellbtn_" + (parseInt($('#start_no').val()) + ++i); });
}

//-----------------------------------------
function dell_row(btn_id) {
  var view_no = btn_id.split("_");  //no_1 をアンダーバーでスプリット
  view_no = view_no[1]; //行番号
  $('#no_'+view_no).remove(); //指定行削除
}