
//------------------------------------------
//  �h���b�v�_�E���ύX����
//
function reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�
  if(val != ""){
  //���M
  $.ajax({
    type: "POST",
    url: app_url+'index.php/select_client_search/select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res(data) {

  var change_position = $('#change_position').text(); //�ύX�ӏ����擾

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
  
}
//------------------------------------------
//  �h���b�v�_�E���ύX����(�{��)
//
function reload_dropdown_unit(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val2 != ""){
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/select_client_search/select_item_unit_list',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });
  }
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res_unit(data) {

  var change_position = $('#change_position').text(); //�ύX���擾

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}


//------------------------------------------
//  �h���b�v�_�E���ύX����(�{��)
//
function reload_dropdown_user(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  var val3 = $('#unit_list').children(':selected').val(); //selected�A�C�e���̒l

  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val3 != ""){
   
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/select_client_search/select_item_user_list',
	    data: {selected_val:{val:val,val2:val2,val3:val3}},
	    datatype: "html",
	    success: reload_dropdown_res_user
	  });
  }
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res_user(data) {

  var change_position = $('#change_position').text(); //�ύX���擾

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}


//------------------------------------------
//  �h���b�v�_�E���ύX����(�X��)
//
function reload_dropdown_maker(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val != ""){
  //���M
  $.ajax({
    type: "POST",
    url: app_url+'index.php/select_client_search/select_item_list/maker',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res(data) {

  var change_position = $('#change_position').text(); //�ύX�ӏ����擾

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
  
}
//------------------------------------------
//  �h���b�v�_�E���ύX����(�X��)
//
function reload_dropdown_maker_unit(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val2 != ""){
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/select_client_search/select_item_unit_list/maker',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });
  }
}


//------------------------------------------
//  �h���b�v�_�E���ύX����(�X��)
//
function reload_dropdown_maker_user(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  var val3 = $('#unit_list').children(':selected').val(); //selected�A�C�e���̒l

  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val3 != ""){
   
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/select_client_search/select_item_user_list/maker',
	    data: {selected_val:{val:val,val2:val2,val3:val3}},
	    datatype: "html",
	    success: reload_dropdown_res_user
	  });
  }
}




//------------------------------------------
//  �h���b�v�_�E���ύX����(�㗝�X)
//
function reload_dropdown_agency(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val != ""){
  //���M
  $.ajax({
    type: "POST",
    url: app_url+'index.php/select_client_search/select_item_list/agency',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}

//------------------------------------------
//  �h���b�v�_�E���ύX����(�㗝�X)
//
function reload_dropdown_agency_unit(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val2 != ""){
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/select_client_search/select_item_unit_list/agency',
	    data: {selected_val:{val:val,val2:val2}},
	    datatype: "html",
	    success: reload_dropdown_res_unit
	  });
  }
}


//------------------------------------------
//  �h���b�v�_�E���ύX����(�㗝�X)
//
function reload_dropdown_agency_user(app_url) {

  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  var val2 = $('#bu_list').children(':selected').val(); //selected�A�C�e���̒l
  var val3 = $('#unit_list').children(':selected').val(); //selected�A�C�e���̒l

  $('#change_position').text('bu_unit_table');  //�ύX�ӏ����ێ�

  if( val3 != ""){
   
	  //���M
	  $.ajax({
	    type: "POST",
	    url: app_url+'index.php/select_client_search/select_item_user_list/agency',
	    data: {selected_val:{val:val,val2:val2,val3:val3}},
	    datatype: "html",
	    success: reload_dropdown_res_user
	  });
  }
}
