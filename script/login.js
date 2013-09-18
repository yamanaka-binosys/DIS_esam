function focus(){
	document.getElementById('login').shbn.focus();
}

function loginCheck(shbn, pass)
{
	myshbn = document.getElementById(shbn).value;
	mypass = document.getElementById(pass).value;
	parent.header.location.href = "http://localhost/sample/application/view/header/btn_header.php";
	parent.content.location.href = "http://localhost/sample/application/view/content/top.php";
}

function login(){
	top.location.href="http://localhost/elleair/views/base.html";
}

function pass_change(id,base_url){
	if(document.getElementById(id).style.display=="none"){
		var login_val = $('#shbn').val();
		var pass_val = $('#pw').val(); 

		
		  if(login_val != "" && pass_val != ""){
		  		document.getElementById('errmsg').innerHTML = '';
			  //送信
			  $.ajax({
			    type: "POST",
			    url: base_url+'index.php/login/check_shbn_pw/'+login_val+'/'+pass_val,
			    data: {login_val:login_val},
			    datatype: "html",
			    success: pass_change_view
			  });
		  }else{
		  	  document.getElementById('errmsg').innerHTML = '';
		  	  if(login_val == ""){
		  	  	document.getElementById('errmsg').innerHTML = '社員番号を入力してください。';
		  	  }else{
		  	  	document.getElementById('errmsg').innerHTML = 'パスワードを入力してください。';
		  	  }
		  }
	  }else{
	  
		document.getElementById(id).style.display="none";
		document.getElementById('change_btn').setAttribute('value', '変更');

//		document.getElementById('Box').style.display="block";
	}
}

function pass_change_view(result){

	if(result=='TRUE'){
		if(document.getElementById('change_pass').style.display=="none"){
			document.getElementById('errmsg').innerHTML = '';
			document.getElementById('change_pass').style.display="block";
			document.getElementById('change_btn').setAttribute('value', '変更取り消し');
	//		document.getElementById('Box').style.display="none";
		}else{
			document.getElementById('errmsg').innerHTML = '';
			document.getElementById('change_pass').style.display="none";
			document.getElementById('change_btn').setAttribute('value', '変更');
	//		document.getElementById('Box').style.display="block";
		}
	}else{
		document.getElementById('errmsg').innerHTML = '';
		document.getElementById('errmsg').innerHTML = '社番またはパスワードが違います。';
		document.getElementById('change_btn').setAttribute('value', '変更');
	}
}
	