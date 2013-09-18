function select_action(id,base_url){
//	alert(id.selectedIndex);
	if (id.selectedIndex==1) {
		var url = base_url.concat("index.php/regular_plan/new_honbu_view/");
	}else if(id.selectedIndex==2){
		var url = base_url.concat("index.php/regular_plan/new_tenpo_view");
	}else if(id.selectedIndex==3){
		var url = base_url.concat("index.php/regular_plan/new_dairi_view");
	}else if(id.selectedIndex==4){
		var url = base_url.concat("index.php/regular_plan/new_gyousya_view");
	}else if(id.selectedIndex==5){
		var url = base_url.concat("index.php/regular_plan/new_office_view");
	}else{
		return;
	}
	$.post(url,function(data, status){     
		var $data = $(data);
		$data.find('.numInputOnly').keydown(acceptNumOnly);
		$data.find('input.cal').datepicker($.datepicker.regional['ja']);        
		$("#action").before($data);
		$("#action_00").remove();
	},"html" );
	
}

function delete_action(base_url,count){
	if(confirm("削除しますか？")){
		var action_name = "#action_".concat(count);
		$(action_name).remove();
		var url = base_url.concat("index.php/regular_plan/new_action_view");
		$.post(url,function(data, status){
			$("#action").append(data);

		},"html" );
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

function new_action_view(base_url,head_name,tmp_flg){
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows="117, *";
	
	check_radio('01');
	check_radio('02');
	check_radio('03');
	$('.numInputOnly').keydown(acceptNumOnly);
	$('input.cal').datepicker($.datepicker.regional['ja']); 
	 
	if(!tmp_flg){
		var url = base_url.concat("index.php/regular_plan/new_action_view");
		$.post(url,function(data, status){
			
			$("#action").append(data);
		},"html" );
	}
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

function check_radio(count){
	// ラジオボタン
	var radio_id = "hkubun_".concat(count);
	var radioList = parent.content.document.getElementsByName(radio_id);
	// 日付入力欄
	var designated_id = "designated_day_".concat(count);
	var designated_elm = parent.content.document.getElementById(designated_id);
	// 曜日チェックボックス
	var sun_id = "designated_sun_".concat(count);
	var mon_id = "designated_mon_".concat(count);
	var tue_id = "designated_tues_".concat(count);
	var wed_id = "designated_wed_".concat(count);
	var thu_id = "designated_thurs_".concat(count);
	var fri_id = "designated_fri_".concat(count);
	var sat_id = "designated_sat_".concat(count);
	var sun_elm = parent.content.document.getElementById(sun_id);
	var mon_elm = parent.content.document.getElementById(mon_id);
	var tue_elm = parent.content.document.getElementById(tue_id);
	var wed_elm = parent.content.document.getElementById(wed_id);
	var thu_elm = parent.content.document.getElementById(thu_id);
	var fri_elm = parent.content.document.getElementById(fri_id);
	var sat_elm = parent.content.document.getElementById(sat_id);
	
	for(var i=0; i<radioList.length; i++){
		if (radioList[i].checked) {
//			alert(radioList[i].value);
			// 毎月固定日が選択された場合
			if(radioList[i].value == 1){
				designated_elm.readOnly = false;
				
				sun_elm.checked = false;
				mon_elm.checked = false;
				tue_elm.checked = false;
				wed_elm.checked = false;
				thu_elm.checked = false;
				fri_elm.checked = false;
				sat_elm.checked = false;

				sun_elm.disabled = true;
				mon_elm.disabled = true;
				tue_elm.disabled = true;
				wed_elm.disabled = true;
				thu_elm.disabled = true;
				fri_elm.disabled = true;
				sat_elm.disabled = true;
			// 毎月月末が選択された場合
			}else if(radioList[i].value == 2){
				designated_elm.value = "";
				designated_elm.readOnly = true;
				
				sun_elm.checked = false;
				mon_elm.checked = false;
				tue_elm.checked = false;
				wed_elm.checked = false;
				thu_elm.checked = false;
				fri_elm.checked = false;
				sat_elm.checked = false;

				sun_elm.disabled = true;
				mon_elm.disabled = true;
				tue_elm.disabled = true;
				wed_elm.disabled = true;
				thu_elm.disabled = true;
				fri_elm.disabled = true;
				sat_elm.disabled = true;
			// 毎週が選択された場合
			}else if(radioList[i].value == 3){
				designated_elm.value = "";
				designated_elm.readOnly = true;

				sun_elm.disabled = false;
				mon_elm.disabled = false;
				tue_elm.disabled = false;
				wed_elm.disabled = false;
				thu_elm.disabled = false;
				fri_elm.disabled = false;
				sat_elm.disabled = false;
			}
		}
	}
}

