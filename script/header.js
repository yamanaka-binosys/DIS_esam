// 通常初期化
function init_normal(){
	if(parent.content.document.getElementById('kakninshnm')){
		var kakninshnm = parent.content.document.getElementById('kakninshnm').value;
		if(parent.header.document.getElementById('person')){
			parent.header.document.getElementById('person').value = kakninshnm;
		}
	}
}
function submit_top_admin() {
  parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].submit();
}
function submit_top_general() {
  parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].submit();
}
function submit_calendar() {
  parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].submit();
}
// メッセージ登録
function submit_message() {
	var form = $('form', parent.content.document);  //contenのformを指定
	if(formValidation(form)){
		var newEle = parent.content.document.createElement('INPUT');
		newEle.type='hidden';
		newEle.name='action';
		newEle.value='action_submit';
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit(newEle);
	}
}
function submit_division() {
  parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].submit();
}
function submit_item_visibility() {
  //parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].submit();
}
function submit_project_item() {
  parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].action = "register";
  parent.content.document.forms[0].submit();
}
function submit_project_possession() {
  //ヴァリデーション
  var form = $('form', parent.content.document);  //
  var res = formValidation(form);
  if(!res) return;	//ヴァリデーションエラー

  //固有のヴァリデーションチェック
  //大分類情報取得
  var daibunrui_list = new Array();
  form.find(":input[name='daibunrui_list[]']").each(function(i) {
    daibunrui_list[i] = $(this).val();
  });
  //アイテム情報取得
  var item_list = new Array();
  form.find(":input[name='item_list[]']").each(function(i) {
    item_list[i] = $(this).val();
  });

  var bifore_cnt = 0;
  var after_cnt = 0;
  var check_data = new Array();
  for (var i in daibunrui_list){
    check_data[i] = String(daibunrui_list[i])+":"+String(item_list[i]);
  }

  //重複チェック
  var storage = {};
  var uniqueArray = [];
  var value;
  for (var i in check_data){
    value = check_data[i];
    if (!(value in storage)) {
      storage[value] = true;
      uniqueArray.push(value);
    }
  }
  bifore_cnt = check_data.length;
  after_cnt = uniqueArray.length;
  if( bifore_cnt != after_cnt ) {
    parent.header.document.getElementById('errmsg').innerHTML = '';
    parent.header.document.getElementById('errmsg').innerHTML = "重複データがあります。";
    return false;	//ヴァリデーションエラー
  }

  parent.content.document.forms[0].set.value = 1;
  var app_url = parent.content.document.forms[0].app_url.value;
  parent.content.document.forms[0].action = app_url+"/project_possession/register";
  parent.content.document.forms[0].submit();
}
// 相手先選択　戻る
function submit_select_client(){
	  parent.content.document.forms[0].submit();
}

//確認者選択　戻る
function submit_select_checker(){
	parent.content.document.forms[0].submit();
}

// TODO 追加
function submit_todo_add() {
	var form = $('form', parent.content.document);  //contentのformを指定
  	if(formValidation(form)){
		var newEle = parent.content.document.createElement("INPUT");
		newEle.type="hidden";
		newEle.name="add";
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit();
	}
}
//TODO 変更
function submit_todo_update() {
	var form = $('form', parent.content.document);  //contentのformを指定
  	if(formValidation(form, '*[name="select_check[]"]:checked')){
		var newEle = parent.content.document.createElement("INPUT");
		newEle.type="hidden";
		newEle.name="update";
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit();
	}
}
// スケジュールコピーボタン
function submit_plan_copy(){
	if (!has_content()) { showError('スケジュールが登録されていません。'); return; }
	if (has_not_registered_data()) { showError('登録されていない活動区分があるスケジュールはコピーできません。');  return; }
	
	var year = parent.header.document.getElementsByName('year')[0].value;
	var month = "0".concat(parent.header.document.getElementsByName('month')[0].value);
	if(month.length > 2){
		month = month.substr(1,2);
	}
	var day = "0".concat(parent.header.document.getElementsByName('day')[0].value);
	if(day.length > 2){
		day = day.substr(1,2);
	}
	var setDay = year + month + day;
	// 元の日付を取得
	var selectDay = parent.content.document.getElementById('select_day').value;
	// 元日付と移動日が同じ場合には移動処理をしない
	if(setDay != selectDay){
		var copyDay = parent.content.document.createElement("INPUT");
		copyDay.type="hidden";
		copyDay.name="copy_day";
		copyDay.value=setDay;
		parent.content.document.forms[0].appendChild(copyDay);

		var newEle = parent.content.document.createElement('INPUT');
		newEle.type='hidden';
		newEle.name='action';
		newEle.value='action_day_copy';
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit(newEle);
	}
}
// スケジュール登録ボタン
function submit_plan_register(){
	//ヴァリデーション
		keep_val['cal_page'] = 1;
    var form = $('form', parent.content.document);  //
    var res = formValidation(form, "", ".mc_group");
    if(!res) return;	//ヴァリデーションエラー

	var newEle = parent.content.document.createElement('INPUT');
	newEle.type='hidden';
	newEle.name='action';
	newEle.value='action_submit';
	parent.content.document.forms[0].appendChild(newEle);
	parent.content.document.forms[0].submit(newEle);
}




// スケジュール移動ボタン
function submit_plan_move(){
	if (!has_content()) { showError('スケジュールが登録されていません。'); return; }
	if (has_not_registered_data()) { showError('登録されていない活動区分があるスケジュールは移動できません。');  return; }
	// 移動日取得
	var year = parent.header.document.getElementsByName('year')[0].value;
//	var month = "0".concat(parent.header.document.getElementById('month').value).substr(-2,2);
//	var day = "0".concat(parent.header.document.getElementById('day').value).substr(-2,2);
	var month = "0".concat(parent.header.document.getElementsByName('month')[0].value);
	if(month.length > 2){
		month = month.substr(1,2);
	}
	var day = "0".concat(parent.header.document.getElementsByName('day')[0].value);
	if(day.length > 2){
		day = day.substr(1,2);
	}
	var setDay = year + month + day;
	// 元の日付を取得
	var selectDay = parent.content.document.getElementById('select_day').value;
	// 元日付と移動日が同じ場合には移動処理をしない
	if(setDay != selectDay){
		var moveDay = parent.content.document.createElement("INPUT");
		moveDay.type="hidden";
		moveDay.name="move_day";
		moveDay.value=setDay;
		parent.content.document.forms[0].appendChild(moveDay);

		var newEle = parent.content.document.createElement("INPUT");
		newEle.type="hidden";
		newEle.name="action";
		newEle.value="action_day_move";
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit();
	}
}
// スケジュール削除ボタン
function submit_plan_delete(){
	if(confirm("削除しますか？")){
		var newEle = parent.content.document.createElement("INPUT");
		newEle.type="hidden";
		newEle.name="action";
		newEle.value="action_day_delete";
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit();
	}
}
// スケジュール入力確認ボタン
function submit_plan_check(base_url){
	parent.content.window.open("","PLAN_CHECK",'width=600,height=600,menubar=no,toolbar=no,status=no,resizable=no,scrollbars=yes');
	var url = base_url.concat("index.php/plan_input_check/index");
	var origin_url = parent.content.document.forms[0].action;
	var origin_target = parent.content.document.forms[0].target;
	var origin_method = parent.content.document.forms[0].method;

	parent.content.document.forms[0].action = url;
	parent.content.document.forms[0].target = "PLAN_CHECK";
	parent.content.document.forms[0].method = "POST";
	parent.content.document.forms[0].submit();

	parent.content.document.forms[0].action = origin_url;
	parent.content.document.forms[0].target = origin_target;
	parent.content.document.forms[0].method = origin_method;
}

// スケジュール確認者検索ボタン
function submit_plan_select_checker(base_url){
	var newAct = parent.content.document.createElement("INPUT");
	newAct.type="hidden";
	newAct.name="action";
	newAct.value="action_select_checker";
	parent.content.document.forms[0].appendChild(newAct);
	parent.content.document.forms[0].submit();
	//parent.content.location.href = base_url + 'index.php/select_checker/index';
}
// スケジュール初期化
function init_plan(){
	if (parent.content['fn_onload']) { parent.content['fn_onload'](); }
	var select_day = parent.content.document.getElementById('select_day');
	parent.header.document.getElementsByName('year')[0].value = select_day.value.substring(0, 4);
	parent.header.document.getElementsByName('month')[0].value = parseInt(select_day.value.substring(4, 6),10);
	parent.header.document.getElementsByName('day')[0].value = parseInt(select_day.value.substring(6, 8),10);
	var weekDay = new Array("日","月","火","水","木","金","土");
	var myYmd = new Date(select_day.value.substring(0, 4),select_day.value.substring(4, 6) - 1,select_day.value.substring(6, 8));
	var myDay = myYmd.getDay();
	parent.header.document.getElementById('weekday').value = weekDay[myDay];
	var kakninshnm = parent.content.document.getElementById('kakninshnm').value;
	parent.header.document.getElementById('checker').value = kakninshnm;
}
// スケジュール翌日
function next_plan_day(base_url){
	var select_day = parent.content.document.getElementById('select_day');
	var baseDay = new Date(select_day.value.substring(0, 4),select_day.value.substring(4, 6) - 1,select_day.value.substring(6, 8));
	baseDay.setTime(baseDay.getTime() + 86400000);// 1日のミリ秒数
	var year = baseDay.getFullYear();
	var tmp_m = baseDay.getMonth() + 1;
	var month = ("0" + tmp_m).slice(-2);
	var tmp_d = baseDay.getDate();
	var day = ("0" + tmp_d).slice(-2);
//	alert(year+month+day);
	parent.content.location.href = base_url + 'index.php/plan/index/'+year+month+day;
}
// スケジュール前日
function before_plan_day(base_url){
	var select_day = parent.content.document.getElementById('select_day');
	var baseDay = new Date(select_day.value.substring(0, 4),select_day.value.substring(4, 6) - 1,select_day.value.substring(6, 8));
	baseDay.setTime(baseDay.getTime() - 86400000);// 1日のミリ秒数
	var year = baseDay.getFullYear();
	var tmp_m = baseDay.getMonth() + 1;
	var month = ("0" + tmp_m).slice(-2);
	var tmp_d = baseDay.getDate();
	var day = ("0" + tmp_d).slice(-2);
//	alert(year+month+day);
	parent.content.location.href = base_url + 'index.php/plan/index/'+year+month+day;
}
// 定期予定設定　確認者選択ボタン
function submit_regular_plan(base_url){
	var form = $('form', parent.content.document);  //contentのformを指定
  	if(formValidation(form, "", '.c_group')){
  		//if(formValidation(form, 'input:radio:checked')){
  			var newEle = parent.content.document.createElement('INPUT');
			newEle.type='hidden';
			newEle.name='action';
			newEle.value='action_submit';
			parent.content.document.forms[0].appendChild(newEle);
			parent.content.document.forms[0].submit(newEle);
  		//}
  	}
}
//確認者検索ボタン押下処理
function show_select_checker(baseUrl){
	parent.content.location.href = baseUrl + 'index.php/select_checker/index';
}

// 日報実績　確認者検索ボタン
function submit_result_select_checker(base_url){
	window.name = 'select';
  	window.open(base_url+'index.php/select_checker/index','select_checker','scrollbars=yes,width=800,height=650,').focus();

}
// 日報実績　初期化
function init_result(){
	if (parent.content['fn_onload']) { parent.content['fn_onload'](); }
	var select_day = parent.content.document.getElementById('select_day');
	parent.header.document.getElementsByName('year')[0].value = select_day.value.substring(0, 4);
	parent.header.document.getElementsByName('month')[0].value = parseInt(select_day.value.substring(4, 6),10);
	parent.header.document.getElementsByName('day')[0].value = parseInt(select_day.value.substring(6, 8),10);
	var weekDay = new Array("日","月","火","水","木","金","土");
	var myYmd = new Date(select_day.value.substring(0, 4),select_day.value.substring(4, 6) - 1,select_day.value.substring(6, 8));
	var myDay = myYmd.getDay();
	parent.header.document.getElementById('weekday').value = weekDay[myDay];
	var kakninshnm = parent.content.document.getElementById('kakninshnm').value;
	parent.header.document.getElementById('checker').value = kakninshnm;
	var check_hold = parent.content.document.getElementById('check_hold').value;
	if(check_hold == "1"){
		parent.header.document.getElementById('check_hold').checked = true;
	}
}
// 日報実績　翌日
function next_result_day(base_url){
	var today = new Date();
	var to_year = today.getFullYear();
	var to_tmp_m = today.getMonth() + 1;
	var to_month = ("0" + to_tmp_m).slice(-2);
	var to_tmp_d = today.getDate();
	var to_day = ("0" + to_tmp_d).slice(-2);
	var to_Day = to_year + to_month + to_day;
	var select_day = parent.content.document.getElementById('select_day');
	var s_day = select_day.value.substring(0, 4)+select_day.value.substring(4, 6)+select_day.value.substring(6, 8);
	var baseDay = new Date(select_day.value.substring(0, 4),select_day.value.substring(4, 6) - 1,select_day.value.substring(6, 8));
	if(to_Day == s_day){
		alert("本日よりも先の日付に移動する事は出来ません。");
		return;
	}
	baseDay.setTime(baseDay.getTime() + 86400000);// 1日のミリ秒数
	var year = baseDay.getFullYear();
	var tmp_m = baseDay.getMonth() + 1;
	var month = ("0" + tmp_m).slice(-2);
	var tmp_d = baseDay.getDate();
	var day = ("0" + tmp_d).slice(-2);
//	alert(year+month+day);
	parent.content.location.href = base_url + 'index.php/result/index/'+year+month+day;
}
// 日報実績　前日
function before_result_day(base_url){
	var select_day = parent.content.document.getElementById('select_day');
	var baseDay = new Date(select_day.value.substring(0, 4),select_day.value.substring(4, 6) - 1,select_day.value.substring(6, 8));
	baseDay.setTime(baseDay.getTime() - 86400000);// 1日のミリ秒数
	var year = baseDay.getFullYear();
	var tmp_m = baseDay.getMonth() + 1;
	var month = ("0" + tmp_m).slice(-2);
	var tmp_d = baseDay.getDate();
	var day = ("0" + tmp_d).slice(-2);
//	alert(year+month+day);
	parent.content.location.href = base_url + 'index.php/result/index/'+year+month+day;
}
// 日報実績コピーボタン
function submit_result_copy(){
	removeError();
	if (!has_content()) { showError('日報が登録されていません。'); return; }
	if (has_not_registered_data()) { showError('登録または一時保存されていない活動区分がある日報はコピーできません。');  return; }
	// 本日の日付を取得
	var today = new Date();
	var to_year = today.getFullYear();
	var to_tmp_m = today.getMonth() + 1;
	var to_month = ("0" + to_tmp_m).slice(-2);
	var to_tmp_d = today.getDate();
	var to_day = ("0" + to_tmp_d).slice(-2);
	var to_Day = to_year + to_month + to_day;
	// コピーする日付を取得
	var year = parent.header.document.getElementsByName('year')[0].value;
	var month = "0".concat(parent.header.document.getElementsByName('month')[0].value);
	if(month.length > 2){
		month = month.substr(1,2);
	}
	var day = "0".concat(parent.header.document.getElementsByName('day')[0].value);
	if(day.length > 2){
		day = day.substr(1,2);
	}
	var setDay = year + month + day;
	if(to_Day < setDay){
		alert("本日（"+to_year+"年"+to_month+"月"+to_day+"日）よりも先の日付を選択する事は出来ません。");
	}else if(!header_checkDate(year,month,day)){
		alert("選択された日付（"+year+"年"+month+"月"+day+"日）は適切ではありません。");
	}else{
		// 元の日付を取得
		var selectDay = parent.content.document.getElementById('select_day').value;
		// 元日付と移動日が同じ場合には移動処理をしない
		if(setDay != selectDay){
			var copyDay = parent.content.document.createElement("INPUT");
			copyDay.type="hidden";
			copyDay.name="copy_day";
			copyDay.value=setDay;
			parent.content.document.forms[0].appendChild(copyDay);

			if(Boolean(parent.header.document.result_head.check_hold.checked) == true){
				parent.content.document.getElementById('check_hold').value = '1';
			}else{
				parent.content.document.getElementById('check_hold').value = '0';
			}

			var newEle = parent.content.document.createElement('INPUT');
			newEle.type='hidden';
			newEle.name='action';
			newEle.value='action_day_copy';
			parent.content.document.forms[0].appendChild(newEle);
			parent.content.document.forms[0].submit(newEle);
		}
	}
}
// 日報実績登録ボタン
function submit_result_register(){
	//ヴァリデーション
	keep_val['cal_page'] = 1;
	
	/** 高速になるかもな処理
	var ret = true;
	$('.action_container', parent.content.document).each(function() {
		if (!formValidation(this)) {
			var ret = false;
			return false;
		}
	});
	if (!ret) return false;
	*/
	
	var form = $('form', parent.content.document);  //contentのformを指定
  if(!formValidation(form)) return false;

	var newEle = parent.content.document.createElement('INPUT');
	newEle.type='hidden';
	newEle.name='action';
	newEle.value='action_submit';
	parent.content.document.forms[0].appendChild(newEle);

	if(Boolean(parent.header.document.result_head.check_hold.checked) == true){
		parent.content.document.getElementById('check_hold').value = '1';
	}else{
		parent.content.document.getElementById('check_hold').value = '0';
	}
	parent.content.document.forms[0].submit(newEle);
}
// 日報実績削除ボタン
function submit_result_delete(){
	if(confirm("削除しますか？")){
		var newEle = parent.content.document.createElement("INPUT");
		newEle.type="hidden";
		newEle.name="action";
		newEle.value="action_day_delete";
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit();
	}
}
// 日報実績入力確認ボタン
function submit_result_check(base_url){
	parent.content.window.open("","RESULT_CHECK",'width=600,height=600,menubar=no,toolbar=no,status=no,resizable=no,scrollbars=yes');
	var url = base_url.concat("index.php/result_input_check/index");
	var origin_url = parent.content.document.forms[0].action;
	var origin_target = parent.content.document.forms[0].target;
	var origin_method = parent.content.document.forms[0].method;

	parent.content.document.forms[0].action = url;
	parent.content.document.forms[0].target = "RESULT_CHECK";
	parent.content.document.forms[0].method = "POST";
	parent.content.document.forms[0].submit();

	parent.content.document.forms[0].action = origin_url;
	parent.content.document.forms[0].target = origin_target;
	parent.content.document.forms[0].method = origin_method;
}
// 日報実績、スケジュールデータ取得表示ボタン
function submit_get_plan_data(){
	if(confirm("スケジュールデータを表示すると現在入力中のデータが失われますがよろしいですか？")){
		var newEle = parent.content.document.createElement("INPUT");
		newEle.type="hidden";
		newEle.name="action";
		newEle.value="get_plan_data";
		parent.content.document.forms[0].appendChild(newEle);
		parent.content.document.forms[0].submit();
	}
}
// メッセージ確認者検索ボタン
function submit_message_select_checker(base_url){
window.name = 'select';
  	window.open(base_url+'index.php/select_checker/index','select_checker','scrollbars=yes,width=800,height=650,').focus();

}

function submit_memo() {
  //parent.content.document.forms[0].set.value = 1;
  var form = $('form', parent.content.document); 
  if(formValidation(form)){
  	parent.content.document.forms[0].submit();
  }
}



function submit_result_view(){
	var newEle = parent.content.document.createElement('INPUT');
	newEle.type='hidden';
	newEle.name='action';
	newEle.value='action_submit';
	parent.content.document.forms[0].appendChild(newEle);
	parent.content.document.forms[0].submit(newEle);
}

function has_content() {
	return 1 < window.parent.content.$('#container>div').length;
}

function has_not_registered_data() {
	var has_unrecoded = false;
	window.parent.content.$('.recode_flg').each(function(i, elem) {
		 if (!elem.value || elem.value != 1) { has_unrecoded = true; }
	});
	return has_unrecoded
}

function header_checkDate(year, month, day) {
    var dt = new Date(year, month - 1, day);
    if(dt == null || dt.getFullYear() != year || dt.getMonth() + 1 != month || dt.getDate() != day) {
        return false;
    }
    return true;
}

function submit_holiday_item() {
  parent.content.document.forms[0].set.value = 1;
  parent.content.document.forms[0].action = "register";
  parent.content.document.forms[0].submit();
}

function submit_back_result_calender(base_url){
    // 戻るボタン押下時の動作（実績画面）
    var select_day = parent.content.document.getElementById('select_day');
    url=base_url.concat("index.php/calendar/back/",select_day.value.substring(0, 6));
    //alert(url);
    parent.content.location.href=url;

	// HEADER画面
	head_url=base_url.concat("index.php/header/index/","calendar");
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}


function submit_back_plan_calender(){
    // 戻るボタン押下時の動作（予定画面）
}

