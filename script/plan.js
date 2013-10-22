function select_action(id,base_url){
	removeError();
//	alert(id.selectedIndex);
	if (id.selectedIndex==1) {
		var url = base_url.concat("index.php/plan/new_honbu_view/");
	}else if(id.selectedIndex==2){
		var url = base_url.concat("index.php/plan/new_tenpo_view");
	}else if(id.selectedIndex==3){
		var url = base_url.concat("index.php/plan/new_dairi_view");
	}else if(id.selectedIndex==4){
		var url = base_url.concat("index.php/plan/new_gyousya_view");
	}else if(id.selectedIndex==5){
		var url = base_url.concat("index.php/plan/new_office_view");
	}else{
		return;
	}
	$.post(url,function(data, status){   
		var $data = $(data);
		var anchor = $data.find('.anchor').attr('name');
		$data.find('.numInputOnly').keydown(acceptNumOnly);
		$data.find('input.cal').datepicker($.datepicker.regional['ja']);  
		$("#action").before($data);
		document.location.hash = anchor;
		id.selectedIndex=0;
	},"html" );
	var btn_delete = parent.content.document.getElementById('action_delete_00');
	btn_delete.focus();
}

function delete_action(base_url,count){
	removeError();
    //alert('delete_action');
    var jyohonum_id = "jyohonum_".concat(count);    // dbのレコードのユニークキーの事前情報
    var dbname_id = "action_type_".concat(count);   // db名を確定するための事前情報
    var jyohonum_val = parent.content.document.getElementById(jyohonum_id).value;   // dbのレコードのユニークキーを確定する
    if(jyohonum_val != "XXXXXXXXX" && jyohonum_val != ""){
        var dbname_val = parent.content.document.getElementById(dbname_id).value;   // db名を確定する
        var sendUrl = base_url.concat("index.php/plan/check_delete");
        //alert(sendUrl + " & post[jyohonum]=" + jyohonum_val + ", post[dbname]=" + dbname_val);
        $.ajax({
            type: 'post',
            url: sendUrl,
            data: {
                dbname:dbname_val, 
                jyohonum:jyohonum_val
            },
            success: function(ret) {
                //alert('ret=' + ret);
                if(ret !== '0'){
                    regular_delete_action(base_url, count, ret);
                }else{
                    single_delete_action(base_url, count);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                alert('サーバとの通信に問題が発生し削除に失敗しました。\n\
' + XMLHttpRequest.status + ', ' + textStatus + ', ' + errorThrown.message);
            }
        });
    }
    
    // Ajax request sent to the CodeIgniter controller "ajax" method "username_taken"
    // post the username field's value
    /*
     * http://www.ibm.com/developerworks/jp/web/library/wa-aj-codeigniter/
     * 
     $.post('/index.php/plan/check_delete',
      { 'username':username },

      // when the Web server responds to the request
      function(result) {
        // clear any message that may have already been written
        $('#bad_username').replaceWith('');
        
        // if the result is TRUE write a message to the page
        if (result) {
          $('#username').after('<div id="bad_username" style="color:red;">' +
            '<p>(That Username is already taken. Please choose another.)</p></div>');
        }
      }
    );*/
}

// 定期予定全ての削除を行う
function regular_delete_action(base_url, count, ret){
    
	if(confirm("定期予定設定されたスケジュールを全て削除しますか？")){
		var dbname_id = "action_type_".concat(count);
		var jyohonum_id = "jyohonum_".concat(count);
		var edbn_id = "edbn_".concat(count);
		var jyohonum_val = parent.content.document.getElementById(jyohonum_id).value;
		var action_name = "#action_".concat(count);
		if(jyohonum_val != "XXXXXXXXX" && jyohonum_val != ""){
			var edbn_val = parent.content.document.getElementById(edbn_id).value;
			var sendUrl = base_url.concat("index.php/plan/action_regular_delete");
		 	$.ajax({
				type: 'post',
				url: sendUrl,
				data: {
					groupid:ret,
					edbn:edbn_val
				},
				success: function() {
					$(action_name).remove();
				},
				error: function() {
					alert('削除に失敗しました。');
				}
 			});
		} else {
			$(action_name).remove();
		}
	}
    else{
        single_delete_action(base_url, count);
    }
}

// 単独での削除を行う
function single_delete_action(base_url,count){
    
	if(confirm("このスケジュールを削除しますか？")){
		var dbname_id = "action_type_".concat(count);
		var jyohonum_id = "jyohonum_".concat(count);
		var edbn_id = "edbn_".concat(count);
		var jyohonum_val = parent.content.document.getElementById(jyohonum_id).value;
		var action_name = "#action_".concat(count);
		if(jyohonum_val != "XXXXXXXXX" && jyohonum_val != ""){
			var dbname_val = parent.content.document.getElementById(dbname_id).value;
			var edbn_val = parent.content.document.getElementById(edbn_id).value;
			var sendUrl = base_url.concat("index.php/plan/action_delete");
		 	$.ajax({
				type: 'post',
				url: sendUrl,
				data: {
					dbname:dbname_val, 
					jyohonum:jyohonum_val, 
					edbn:edbn_val
				},
				success: function() {
					$(action_name).remove();
				},
				error: function() {
					alert('削除に失敗しました。');
				}
 			});
		} else {
			$(action_name).remove();
		}
	}
}


// 元の delete_action()
function delete_action_original(base_url,count){
    
	if(confirm("削除しますか？")){
		var dbname_id = "action_type_".concat(count);
		var jyohonum_id = "jyohonum_".concat(count);
		var edbn_id = "edbn_".concat(count);
		var jyohonum_val = parent.content.document.getElementById(jyohonum_id).value;
		var action_name = "#action_".concat(count);
		if(jyohonum_val != "XXXXXXXXX" && jyohonum_val != ""){
			var dbname_val = parent.content.document.getElementById(dbname_id).value;
			var edbn_val = parent.content.document.getElementById(edbn_id).value;
			var sendUrl = base_url.concat("index.php/plan/action_delete");
		 	$.ajax({
				type: 'post',
				url: sendUrl,
				data: {
					dbname:dbname_val, 
					jyohonum:jyohonum_val, 
					edbn:edbn_val
				},
				success: function() {
					$(action_name).remove();
				},
				error: function() {
					alert('削除に失敗しました。');
				}
 			});
		} else {
			$(action_name).remove();
		}
	}
}

function move_action(count){
	var recode_flg = $('#recode_flg_' + count).attr('value');
	if (!recode_flg || recode_flg != 1) {
		showError('保存されていない活動区分は移動できません。');
		return;
	}
	
	//ヴァリデーション
  var form = $('form');  //
  var res = formValidation(form, ".required"+count);
  if(!res) return;	//ヴァリデーションエラー

	var newAct = document.createElement("INPUT");
	newAct.type="hidden";
	newAct.name="action";
	newAct.value="action_move";
	parent.content.document.forms[0].appendChild(newAct);
	var newEle = document.createElement("INPUT");
	newEle.type="hidden";
	newEle.name="data_count";
	newEle.value=count;
	parent.content.document.forms[0].appendChild(newEle);
	parent.content.document.forms[0].submit();
}

function copy_action(count){
	var recode_flg = $('#recode_flg_' + count).attr('value');
	if (!recode_flg || recode_flg != 1) {
		showError('保存されていない活動区分は移動できません。');
		return;
	}
	//ヴァリデーション
    var form = $('form');  //
    var res = formValidation(form, ".required"+count);
    if(!res) return;	//ヴァリデーションエラー

	var newAct = document.createElement("INPUT");
	newAct.type="hidden";
	newAct.name="action";
	newAct.value="action_copy";
	parent.content.document.forms[0].appendChild(newAct);
	var newEle = document.createElement("INPUT");
	newEle.type="hidden";
	newEle.name="data_count";
	newEle.value=count;
	parent.content.document.forms[0].appendChild(newEle);
	parent.content.document.forms[0].submit();
}

function new_action_view(base_url,head_name){
	
	$('input.cal').datepicker($.datepicker.regional['ja']); 
	
	$('.numInputOnly').keydown(acceptNumOnly);
	var url = base_url.concat("index.php/plan/new_action_view");
	$.post(url,function(data, status){
		$("#action").append(data);
	},"html" );

// HEADER画面
	head_url=base_url.concat("index.php/header/plan/",head_name);
	parent.header.location.href=head_url;
	
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function action_submit_view(base_url,head_name){
	
	fn_onload();
	
	$('input.cal').datepicker($.datepicker.regional['ja']); 
	
	$('.numInputOnly').keydown(acceptNumOnly);
	var url = base_url.concat("index.php/plan/new_action_view");
	$.post(url,function(data, status){
		$("#action").append(data);
	},"html" );
}

function acceptNumOnly(event) {
  var c = event.keyCode
  return (
  (96 <= c && c <= 105) ||   // テンキー0～9
  (48 <= c && c <= 57 ) ||        // メインキー0～9
  (37 <= c && c <= 40 ) ||  // 矢印キー
  (c == 13 || c == 9  ||  c == 8))  // Enter, Tab, backspace
}

function select_client(count,kbn,base_url){
	window.name = 'parent';
  	window.open(base_url+'index.php/select_client/index/'+kbn+'/'+count,'select_client','scrollbars=no,width=800,height=650,').focus();
	
}


