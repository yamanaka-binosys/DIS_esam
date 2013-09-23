function login(){
	parent.content.location.href="http://localhost/elleair/index.php/login/index";
}

function top_admin(base_url,head_name){
	url=base_url.concat("index.php/top");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function top_general(base_url,head_name){
	url=base_url.concat("index.php/top");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function calendar(base_url,head_name){
//	url=base_url.concat("index.php/calendar");
	url=base_url.concat("index.php/calendar/index");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function scheduling(base_url,head_name){
//	url=base_url.concat("index.php/regular_plan/index",head_name);
	url=base_url.concat("index.php/regular_plan/index");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function s_rireki(base_url,head_name){
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
	url=base_url.concat("index.php/s_rireki/index");
	parent.content.location.href=url;
}

function todo(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}

function todo_add(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/todo/index");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function todo_update(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/todo/update_select_type?mode=update");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function todo_delete(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/todo/delete_select_type");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function data_memo(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}

function memo_add(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/data_memo/add");
	parent.content.location.href=url;

	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function memo_update(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/data_memo/update");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function memo_delete(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/data_memo/delete");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function memo_search(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/data_memo/search");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function message(base_url,head_name){
  //CONTENT画面
  url=base_url.concat("index.php/message");
  parent.content.location.href=url;
  // HEADER画面
  head_url=base_url.concat("index.php/header/index/",head_name);
  parent.header.location.href=head_url;

  window.parent.document.getElementById('baseset').rows = "117, *";
}

function project_possession(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}
function project_possession_add(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/project_possession");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}
function project_possession_view(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/project_possession_view");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function data_output(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/data_output");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function user(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/user/add_select_type");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function project_item(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/project_item/index");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function division(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}

function division_add(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/division");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function division_update(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/division/update");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function division_delete(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/division/delete");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function item_visibility(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/item_visibility");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function holiday_item(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/holiday_item/index");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
}

function help(base_url){
	// CONTENT画面
	url=base_url.concat("help.html");
	parent.content.location.href=url;
}

function logout(base_url) {
	if (!window.parent.opener || window.parent.opener.closed) {
		window.parent.close();
	} else {
		window.parent.opener.focus();
		window.parent.opener.location.href = base_url+"index.php/login/index";
		window.parent.close();
	}
	
}
