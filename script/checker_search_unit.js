//------------------------------------------
//  �h���b�v�_�E���ύX����
//
function reload_dropdown(app_url) {
  var val = $('#daibunrui_list').children(':selected').val(); //selected�A�C�e���̒l
  $('#change_position').text('busyo_table');  //�ύX�ӏ����ێ�

  if(val != ""){
  //���M
  $.ajax({
    type: "POST",
    url: app_url+'index.php/checker_search_unit/select_item_list',
    data: {selected_val:val},
    datatype: "html",
    success: reload_dropdown_res
  });
  }
}
//reload_dropdown�ł̃f�[�^���M��Ăяo���֐�
function reload_dropdown_res(data) {

  var change_position = $('#change_position').text(); //�ύX�h���b�v�_�E�����擾

  document.getElementById(change_position).innerHTML = '';
  document.getElementById(change_position).innerHTML = data;
}

//------------------------------------------
//  �h���b�v�_�E���ύX����
//
function reload_dropdown_unit(app_url) {
 
}