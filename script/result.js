function select_action(id,base_url){
	removeError();
//	alert(id.selectedIndex);
	if (id.selectedIndex==1) {
		var url = base_url.concat("index.php/result/new_honbu_view/");
	}else if(id.selectedIndex==2){
		var url = base_url.concat("index.php/result/new_tenpo_view");
	}else if(id.selectedIndex==3){
		var url = base_url.concat("index.php/result/new_dairi_view");
	}else if(id.selectedIndex==4){
		var url = base_url.concat("index.php/result/new_gyousya_view");
	}else if(id.selectedIndex==5){
		var url = base_url.concat("index.php/result/new_office_view");
	}else{
		return;
	}
	$.post(url,function(data, status) {
		var $data = $(data);
		$data.find('.numInputOnly').keydown(acceptNumOnly);
		var anchor = $data.find('.anchor').attr('name');
		$("#action").before($data);
		document.location.hash = anchor;
		id.selectedIndex=0;
	},"html" );
	var btn_delete = parent.content.document.getElementById('action_delete_00');
	btn_delete.focus();
}

function delete_action(base_url,count){
	removeError();
	if(confirm("削除しますか？")){
		var dbname_id = "action_type_".concat(count);
		var jyohonum_id = "jyohonum_".concat(count);
		var edbn_id = "edbn_".concat(count);
		var select_day_id = "select_day";
		
		var jyohonum_val = parent.content.document.getElementById(jyohonum_id).value;
		var dbname_val = parent.content.document.getElementById(dbname_id).value;
		var edbn_val = parent.content.document.getElementById(edbn_id).value;
		var select_day_val = parent.content.document.getElementById(select_day_id).value;
		var action_name = "#action_".concat(count);
			
		if(jyohonum_val != "XXXXXXXXX" && jyohonum_val != ""){
			var sendUrl = base_url.concat("index.php/result/action_delete");
			$.ajax({
				type: 'post',
				url: sendUrl,
				data: {
					dbname:dbname_val, 
					jyohonum:jyohonum_val, 
					edbn:edbn_val,
					select_day:select_day_val
				},
				success: function() {
					$(action_name).remove();
				},
				error: function() {
					alert('削除に失敗しました。');
				}
 			});
		}　else {
			$(action_name).remove();
		}	
	}
}

function move_action(count){
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
	// HEADER画面
	head_url=base_url.concat("index.php/header/result/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
	$('.numInputOnly').keydown(acceptNumOnly);
	var url = base_url.concat("index.php/result/new_action_view");
	$.post(url,function(data, status){
		$("#action").append(data);
	},"html" );
}

function action_submit_view(base_url,head_name){

	fn_onload();

	window.parent.document.getElementById('baseset').rows = "117, *";
	$('.numInputOnly').keydown(acceptNumOnly);
	var url = base_url.concat("index.php/result/new_action_view");
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

function del_file(base_url,filenum_val){
	
	if(confirm("削除しますか？")){
		var action_name = "#action";
		
		var sendUrl = base_url.concat("index.php/result/delete_file_action");
		parent.content.document.getElementById('mitumori_file_div').innerHTML ='';
		parent.content.document.getElementById('mitumori_file_div').innerHTML ='見積もりファイル<input type="file" name="tempfile" size="30" maxlength="256" />';
		$.ajax({
			type: 'post',
			url: sendUrl,
			data: {
				filenum:filenum_val
			},
			success: reload_file,
			error: function() {
				alert('削除に失敗しました。');
			}
		});
		
	}　else {
			
	}	
}



function reload_file(){
		parent.content.document.getElementById('mitumori_file_div').innerHTML ='';
		parent.content.document.getElementById('mitumori_file_div').innerHTML ='見積もりファイル<input type="file" name="tempfile" size="30" maxlength="256" />';
		parent.header.document.getElementById('errmsg').innerHTML ='';
		parent.header.document.getElementById('errmsg').innerHTML ='添付ファイルを削除しました。';
		parent.header.document.getElementById('errmsg').setAttribute('class', 'msg-info');
}
