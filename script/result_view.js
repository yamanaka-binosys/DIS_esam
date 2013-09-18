function select_action(id,base_url){
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
	$.post(url,function(data, status){
		$("#action").before(data);
		id.selectedIndex=0;
	},"html" );
	var btn_delete = parent.content.document.getElementById('action_delete_00');
	btn_delete.focus();
}

function select_calender(count){
	var action_name = "#move_copy_day_".concat(count);
	$(action_name).datepicker($.datepicker.regional['ja']);
}

function delete_action(base_url,count){
	if(confirm("削除しますか？")){
		var dbname_id = "action_type_".concat(count);
		var jyohonum_id = "jyohonum_".concat(count);
		var edbn_id = "edbn_".concat(count);
		var select_day_id = "select_day";
		var jyohonum_val = parent.content.document.getElementById(jyohonum_id).value;
		if(jyohonum_val != "XXXXXXXXX" && jyohonum_val != ""){
			var dbname_val = parent.content.document.getElementById(dbname_id).value;
			var edbn_val = parent.content.document.getElementById(edbn_id).value;
			var select_day_val = parent.content.document.getElementById(select_day_id).value;
			
			var sendUrl = base_url.concat("index.php/result/action_delete");
			var xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
			xmlHttp.open("POST",sendUrl);
			xmlHttp.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
			xmlHttp.send('dbname='+dbname_val+'&jyohonum='+jyohonum_val+'&edbn='+edbn_val+'&select_day='+select_day_val);
		}
		var action_name = "#action_".concat(count);
		$(action_name).remove();
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
//	head_url=base_url.concat("index.php/header/result/",head_name);
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
	
//	var url = base_url.concat("index.php/result/new_action_view");
//	$.post(url,function(data, status){
//		$("#action").append(data);
//	},"html" );
}

function select_client(count){
	var newAct = document.createElement("INPUT");
	newAct.type="hidden";
	newAct.name="action";
	newAct.value="action_select_client";
	parent.content.document.forms[0].appendChild(newAct); 
	var newEle = document.createElement("INPUT");
	newEle.type="hidden";
	newEle.name="data_count";
	newEle.value=count;
	parent.content.document.forms[0].appendChild(newEle); 
	parent.content.document.forms[0].submit();
}


