//0埋め
function fill0(str) {
	return ('0' + str).slice(-2);
}
//0取り
function zeroShift(txt){
    var rep = new RegExp("^0+0?");
    return txt.replace(rep,"");
}

var keep_val = {};

function formValidation(form, group, exc_group) {
	
	var start= new Date();

  var res=false;
  var message="";
  var msg = [];
  
  var radio_check_title="";

  var check_target = ":input";	//初期値input全て
  //チェックグループclassが指定された場合
  if(group) {
	  check_target = group;
  }
  //チェック除外グループclassが指定された場合
  if(exc_group) {
	  check_target = ":input:not("+exc_group+")";
  }

  //メイン処理
  $(check_target, form).each(function() {
	  for (var i in msg){ if(i) return; }	//既にエラーメッセージがセットされていた場合リターン
		
		var $target = $(this);
	  //ヴァリデートメイン処理 ※複数個所から呼び出したいためfunctionに切り出し
	  var res_meg = "";
	  res_meg = validMain($target, form, false);
	
	  if(res_meg) {
			msg[$target.attr("name")] = { msg: res_meg, target: $target };
		}
  });
  //メッセージセット　最初に引っかかったエラーを表示する
  for (var i in msg){
    message = msg[i];
    if( msg[i]){ break; }
  }

	var end = new Date();
	// console.log(end.getTime() - start.getTime());
	return innerShowError(message);

}

function removeError() {
  var errmsg = keep_val["no_head"] == '1' ? document.getElementById('errmsg')
			  : parent.header.document.getElementById('errmsg');
	errmsg.innerHTML = '&nbsp;';
}

function innerShowError(ret) {
	var message = ret['msg'];	
  var errmsg = keep_val["no_head"] == '1' ? document.getElementById('errmsg')
			  : parent.header.document.getElementById('errmsg');
	if (!message) {
		errmsg.innerHTML = '&nbsp;';
		return true;
	} else {
		  //ヘッダーにメッセージを出すように対応
		errmsg.textContent = message; // for ff
		errmsg.innerHTML = message;
		errmsg.setAttribute('class', 'msg-error');
		errmsg.setAttribute('className', 'msg-error'); // for ie6
		if (keep_val['cal_page'] == 1) {
			window.parent.content.location.hash = ret['target'].parents('.action_container').find('.anchor').attr('name');
		}
		ret['target'].focus();
		return false;
	}
}

function showError(message) {
  var errmsg = keep_val["no_head"] == '1' ? document.getElementById('errmsg')
			  : parent.header.document.getElementById('errmsg');
			
  if (message == "") {
		errmsg.innerHTML = '&nbsp;';
		return true;
	} else {
	  //ヘッダーにメッセージを出すように対応
	  errmsg.textContent = message; // for ff
	  errmsg.innerHTML = message;
	  errmsg.setAttribute('class', 'msg-error');
	  errmsg.setAttribute('className', 'msg-error'); // for ie6
		return false;
	}
}

//---------------------------------------------------
/**
 * ヴァリデートメインの処理(切り出し版)
 */
function validMain(item_obj, form, checked_item_check_flg ) {
	//inputアイテム情報取得
	var name = item_obj.attr('name');
    var title = item_obj.attr('title');
    var input_data = item_obj.val();
    var class_txt = item_obj.attr('class');  //

    if(class_txt === undefined) return;	//classが設定されていない場合リターン
	var msg = [];
    var res_meg = "";
    var radio_check = '';
    var check_data = '';

    //入力必須チェック
    if(class_txt.match(/required(\d*)/)) {
      res_meg = validRequired(input_data);
      if(res_meg != "") return title+'を'+res_meg;
    }
    //数字のみチェック
    if(class_txt.match(/numonly/)) {
      res_meg = validNumonly(input_data);
      if(res_meg != "") return title+'を'+res_meg;
    }
		/*
    //英数字のみチェック
    if(class_txt.match(/nochar/)) {
      res_meg = validNochar(input_data);
      if(res_meg != "") return title+'を'+res_meg;
    }
    //半角チェック
    if(class_txt.match(/hankaku/)) {
      res_meg = validHankaku(input_data);
      if(res_meg != "") return title+'を'+res_meg;
    }
    //全角チェック
    if(class_txt.match(/zenkaku/)) {
      res_meg = validZenkaku(input_data);
      if(res_meg != "") return title+'を'+res_meg;
    }
    //ひらがな
    if(class_txt.match(/hiragana/)) {
      res_meg = validHiragana(input_data);
      if(res_meg != "") return title+'：「'+res_meg+'」';
    }
    //カタカナ
    if(class_txt.match(/katakana/)) {
      res_meg = validKatakana(input_data);
      if(res_meg != "") return title+'：「'+res_meg+'」';
    }
    //再入力チェック（未実装）
    if(class_txt.match(/retype/)) {
      //msg += validRetype(); //なし
    }
		*/

    //チェックボックスチェック
    if(class_txt.match(/checkbox/)) {
      res_meg = validCheckbox(form);
      if(res_meg != "") return title+'を'+res_meg;
    }
    
     //入力必須チェック(ヘッダーなし
    if(class_txt.match(/no_head_required(\d*)/)) {
    	keep_val["no_head"] = '1';
      res_meg = validRequired(input_data);
      if(res_meg != "") return title+'を'+res_meg;
    }
     //ラジオボックスチェック(ヘッダーなし
    if(class_txt.match(/radio_box_check/)) {
    	keep_val["no_head"] = '1';
   		res_meg = validRaidobox(input_data,name);
   		if(res_meg == '1'){
   			radio_check_title = title;
   		}
    }
    //パスワードチェック
    if(class_txt.match(/pw_length/)) {
      msg = validlength_pass(input_data);
      if(res_meg != "") return title+'は'+res_meg;
    }
     //ラジオボックスチェック テキスト
    if(class_txt.match(/radio_box_value/)) {
      if(radio_check_title == title){
	      res_meg = validRequired(input_data);
	      if(res_meg != "") return title+'を'+res_meg;
      }
    }
    //セレクトチェック（未実装）
    if(class_txt.match(/selected/)) {
    	if(item_obj.val() == "" || item_obj.val() == "000") {
    		return title+"を"+"選択して下さい";
    	}
    }
    //日付チェック
    if(class_txt.match(/checkdate_year(\d*)|checkdate_month(\d*)|checkdate_day(\d*)|checkdate_ymd(\d*)/)) {
    	var target = class_txt.match(/checkdate_(.+)/) ? RegExp.$1 : "";
    	var number = "";
    	var year  = "";
    	var month = "";
    	var day = "";

    	if( target.match(/ymd(\d*)/) ) {
    		//データが纏まってる場合のYmd
    		var ymd = [];
    		if (input_data.match(/^\d{2,4}\/\d{1,2}\/\d{1,2}$/)){
    	        ymd = input_data.split("/");
    	    }else{
    	    	ymd[0] = input_data.substring(0,4);
    	    	ymd[1] = input_data.substring(4,6);
    	    	ymd[2] = input_data.substring(6,8);
    	    }
    		year = ymd[0];
    		month = ymd[1];
    		day = ymd[2];
    	} else {
    		//データが分かれてる場合のYmd
    		number = target.match(/year(\d+)|month(\d+)|day(\d+)/) ? RegExp.$1 : "";
        	year  = form.find('[class*="checkdate_year'+number+'"]').val();
        	month = form.find('[class*="checkdate_month'+number+'"]').val();
        	day   = form.find('[class*="checkdate_daycheck'+number+'"]').val();
    	}

    	res_meg = checkDate(year,month,day);
		if(res_meg) return title + "の" +res_meg;
    }
    //上下関係チェック(数字)
    if(class_txt.match(/low(\d+)/)) {
      var number = class_txt.match(/low(\d+)/) ? RegExp.$1 : null;	//番号が同じものが比較対象となるので、番号を抜き出し
      res_meg = validLowHigh(form, number);
	  if(res_meg != "") return title+'の'+res_meg;
    }
    //数字の範囲チェック
    if(class_txt.match(/min(\d+)|max(\d+)/)) {
    	var min = class_txt.match(/min(\d+)/) ? RegExp.$1 : null;
    	var max = class_txt.match(/max(\d+)/) ? RegExp.$1 : null;

        res_meg = validRange(input_data, min, max);
        if(res_meg != "") return title+'の'+res_meg;
    }
    //日付関連性チェック(checkdate_fromとcheckdate_toを使用して下さい)
    if(class_txt.match(/checkdate_to(\d*)/)) {
    	var number = RegExp.$1;
    	res_meg = validDateRelation(form, number);
      if(res_meg != "") return res_meg;
    }
    //過去日チェック
    if (class_txt.match(/check_notpastdate_(.+)/)) {
    	var number = class_txt.match(/check_notpastdate_(\d+)/) ? RegExp.$1 : null;
    	var targets = form.find('[class*="check_notpastdate_'+number+'"]').parent();
    	var y = targets.find('[class*="check_year"]').val();
    	var m = targets.find('[class*="check_month"]').val();
    	var d = targets.find('[class*="check_date"]').val();

    	if (y && m && d) {
    		if (msg['checkdate'] === undefined) {
    			res_meg = validateNotPastDate(y, m, d);
    			if (res_meg) {
    				var title = targets.find('[title]').attr('title');
    				var m = title ? title + 'は' + res_meg : res_meg;
    				return m;
    			}
    		}
    	}
    }
    //チェックボックスチェックされたアイテムチェック
    if(checked_item_check_flg == false) {
	    if(class_txt.match(/checked_item(\d+)/) && item_obj.attr("checked")) {
		    var number = class_txt.match(/checked_item(\d+)/) ? RegExp.$1 : null;
		    res_meg = validCheckedItemCheck(form, "checked_item"+number);
		    if(res_meg != "") return res_meg;
	    }
    }
}


//---------------------------------------------------

//必須チェック関数
function validRequired(txt) {
  var msg = "入力して下さい";
  if ( txt && txt.length>0 ) {
    if ( /^[ 　\r\n\t]+$/.test(txt) == false) {
      msg = "";
    }
  }
  return msg;
}
//セレクトチェック関数（未実装）
function validSelect(txt) {
  var msg = "選択して下さい";
  return msg;
}
//再入力チェック関数（未実装）
function validRetype(txt) {
  var msg = "入力内容が異なります";
  return msg;
}
//数字チェック関数
function validNumonly(txt) {
  var msg = "";
  if ( txt && txt.length>0 ) {
    if ( /^[0-9]+$/.test(txt) == false ) {
      msg = "半角数字で入力してください";
    }
  }
  return msg;
}
//英数字のみチェック
function validNochar(txt) {
  var msg = "";
  if ( txt && txt.length>0 ) {
    if ( /^[a-zA-Z0-9]+$/.test(txt) == false) {
      msg = "英数字で入力してください";
    }
  }
  return msg;
}
//半角チェック
function validHankaku(txt) {
  var msg = "";
  if ( txt && txt.length>0 ) {
    if ( /^[a-zA-Z0-9@\;\:\[\]\{\}\|\^\=\/\!\*\`\"\#\$\+\%\&\'\(\)\,\.\-\_\?\\\s]*$/.test(txt) == false) {
      msg = "半角文字で入力して下さい";
    }
  }
  return msg;
}
//全角チェック
function validZenkaku(txt) {
  var msg = "";
  if ( txt && txt.length>0 ) {
    if ( /^[^a-zA-Z0-9@\;\:\[\]\{\}\|\^\=\/\!\*\"\#\$\+\%\&\'\(\)\,\.\-\_\?\\\s]+$/.test(txt) == false) {
      msg = "全角文字で入力してください";
    }
  }
  return msg;
}
//ひらがな
function validHiragana(txt) {
  var msg = "";
  if ( txt && txt.length>0 ) {
    if ( /^[あ-んー～]+$/.test(txt) == false) {
      msg = "ひらがなで入力してください";
    }
  }
  return msg;
}
//カタカナ
function validKatakana(txt) {
  var msg = "";
  if ( txt && txt.length>0 ) {
    if ( /^[ア-ンー～]+$/.test(txt) == false) {
      msg = "カタカナで入力してください";
    }
  }
  return msg;
}

//パスワードチェック
function validlength_pass(txt){
 var msg = "";
  if ( txt && txt.length >= 6 ) {
      msg = "6文字以上入力してください。";

  }
  return msg;
}
//区分チェック
function kbn_check(txt){
 var msg = "";
  if ( txt && txt =="000" ) {
      msg = "選択してください。";
  }
  return msg;
}

//チェックボックスチェック
function validCheckbox(form) {
  var msg = "選択してください";
  if ( $('input:checkbox:checked', form).length>0 ) {
    msg = "";
  }
  return msg;
}
//上下関係チェック
function validLowHigh(form, number) {
  var msg = "";
  var low = parseInt($('.low'+number, form).val(),10);
  var high = parseInt($('.high'+number, form).val(),10);

  if (low || high) {
    if(low >= high || isNaN(low) || isNaN(high)) msg = "時系列が誤っています";
  }

  return msg;
}
//数字の範囲チェック
function validRange(txt, min, max) {
	var msg = "";
	if(txt == "00") txt = 0;
	if(txt.length > 1) txt = zeroShift(txt);

	txt = parseInt(txt,10);
	min = parseInt(min,10);
	max = parseInt(max,10);

	if(min != null && max != null) {
		if(txt < min || max < txt) msg = "範囲が適切ではありません";
	}else if(min != null) {
		if(txt < min) msg = "範囲が適切ではありません";
	}else if(max != null) {
		if(max < txt) msg = "範囲が適切ではありません";
	}

    return msg;
}
function validRaidobox(txt,name) {
	var str='';
	var radioList = document.getElementsByName(name);
	if (radioList[0].checked==true) {
		str = '1';
	}
	return str;
}
//日付の妥当性チェック
function checkDate(year, month, day) {
	var msg = "日付が適切ではありません";

	//初期値セット
	var now_date = new Date();
    var dt = new Date(year, month - 1, day);
    month = month - 1;
    if(dt.getFullYear() == year && dt.getMonth() == month  && dt.getDate() == day) {
       var msg ="";
    }

    return msg;
}
//日付チェック＆相互関連性チェック
//エラーメッセージの項目名には
//開始・終了それぞれ最後に探索された対象要素のtitle属性が使用される。
//たとえば年月日すべてにtitle属性がついており、この順番で探索された場合は、
//日のtitle属性が使用される。
function validDateRelation(form, number) {
	var msg = "";
	var from_date = [];
	var to_date = [];
	var from_title = '';
	var to_title = '';

	var check_flg=false;
	from_num=0;
	to_num=0;

	//from日付取得、チェック
	$('.checkdate_from'+number, form).each(function(i) {
		var $this = $(this);
		if($this.val() != "" && $this.val() != 0) check_flg = true;
		from_date[i] = $this.val();
		from_title = $this.attr('title') ? $this.attr('title') : '開始日の';
	});
	//from一つでもセットされてたらチェック
	from_num = from_date.length;
	if(check_flg) {
		if(from_num == 3) {
			msg = checkDate(from_date[0], from_date[1], from_date[2]);	//(year,month,day)チェック
		}
	}
	if(msg != "") return from_title+msg;

	check_flg=false;	//check_flg リセット

	//to日付取得、チェック
	$('.checkdate_to'+number, form).each(function(i) {
		var $this = $(this);
		if($this.val() != "" && $this.val() != 0) check_flg = true;
		to_date[i] = $this.val();
		to_title = $this.attr('title') ? $this.attr('title') : '終了日の';
	});
	//to一つでもセットされてたらチェック
	to_num = to_date.length;
	if(check_flg) {
		if(to_num == 3) {
			msg = checkDate(to_date[0], to_date[1], to_date[2]);	//(year,month,day)チェック
		}
	}
	if(msg != "") return to_title+msg;

	//開始日と終了日の時系列関係 比較チェック
	if(from_num > 0 && to_num > 0) {
		var fill0From = 0;
		var fill0To   = 0;

		if(from_num == 5 && to_num == 5) {
			//(year,month,day,time,minute) チェック
			fill0From = from_date[0] + fill0(from_date[1]) + fill0(from_date[2]) + fill0(from_date[3]) + fill0(from_date[4]);
			fill0To   = to_date[0] + fill0(to_date[1]) + fill0(to_date[2]) + fill0(from_date[3]) + fill0(from_date[4]);
		} else if(from_num == 3 && to_num == 3) {
			//(year,month,day) チェック
			fill0From = from_date[0] + fill0(from_date[1]) + fill0(from_date[2]);
			fill0To   = to_date[0] + fill0(to_date[1]) + fill0(to_date[2]);
		} else if(from_num == 2 && to_num == 2) {
			//(time,minute) チェック
			fill0From = from_date[0] + fill0(from_date[1]);
			fill0To   = to_date[0] + fill0(to_date[1]);
		}

		if(parseInt(fill0From,10) > parseInt(fill0To,10)) {
			msg = from_title + "と" + to_title + "の時系列が誤っています";
		}
	}

	return msg;
}
//過去日チェック
function validateNotPastDate(year, month, date) {

	var msg = '';
	var cur = new Date();
	var y = cur.getFullYear().toString();
	var m = (cur.getMonth() + 1).toString();
	var d = cur.getDate().toString();
	var now = y + fill0(m) + fill0(d);
	var input = year + fill0(month) + fill0(date);
	if (input < now) {
		msg = "過去の日付では登録できません。";
	}
	return msg;
}
//チェックアイテムでチェックされたアイテムチェック
function validCheckedItemCheck(form, class_txt) {
  var msg = "";
  $('.'+class_txt, form).each(function() {
	  if(msg) return;

	  var res_meg = "";
	  res_meg = validMain($(this), form, true);	//チェックされたアイテムをヴァリデートに掛ける
	  if(res_meg) msg = res_meg;
  });

  return msg;
}
