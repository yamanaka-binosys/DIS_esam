function search_memo(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}
function data_memo_update_select(jyohonum,edbn,base_url){
	url = "index.php/data_memo/update_select/"+jyohonum+"/"+edbn+"/";
	update_url = base_url.concat(url);
	window.open(update_url, 'data_memo_update_select','width=850,height=600,').focus();
}

function data_memo_delete_select(jyohonum,edbn,base_url){
	var url = "index.php/data_memo/delete_select/" + jyohonum + "/" + edbn + "/";
	var delete_url = base_url.concat(url);
	window.open(delete_url, 'data_memo_delete_select','width=600,height=600,').focus();
}

function data_memo_search_select(jyohonum,edbn,base_url){
	var url = "index.php/data_memo/search_select/" + jyohonum + "/" + edbn + "/";
	var search_url = base_url.concat(url);
	window.open(search_url, 'data_memo_search_select','width=600,height=600,').focus();
}

function check_memo(base_url,head_name){
	var head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";

}

//------------------------------------------
//  �h���b�v�_�E���ύX����
//
function memo_reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('busyo_table');  //�ύX�ӏ����ێ�

  //���M
  $.ajax({
    type: "POST",
    url: app_url+'index.php/data_memo/select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res(data) {

  var change_position = $('#change_position').text(); //�ύX�h���b�v�_�E�����擾

  document.getElementById('busyo_table').innerHTML = '';
  document.getElementById('busyo_table').innerHTML = data;
}

//------------------------------------------
//  �h���b�v�_�E���ύX����(�{��)
//
function memo_reload_dropdown_unit(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('busyo_table');  //�ύX�ӏ����ێ�

	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_memo/select_item_unit_list',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });

}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res_unit(data) {

  var change_position = $('#change_position').text(); //�ύX���擾

  document.getElementById('busyo_table').innerHTML = '';
  document.getElementById('busyo_table').innerHTML = data;
}


//------------------------------------------
//  �h���b�v�_�E���ύX����(�{��)
//
function memo_reload_dropdown_user(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  var val3 = $('#ka_list').children(':selected').val(); //selected�A�C�e���̒l

  $('#change_position').text('busyo_table');  //�ύX�ӏ����ێ�

   
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/data_memo/select_item_user_list',
	    data: {selected_val:{val:val,val2:val2,val3:val3}},
	    datatype: "html",
	    success: reload_dropdown_res_user
	  });
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res_user(data) {

  var change_position = $('#change_position').text(); //�ύX���擾

  document.getElementById('busyo_table').innerHTML = '';
  document.getElementById('busyo_table').innerHTML = data;
}
