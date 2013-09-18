function login(){
	parent.content.location.href="http://localhost/sample/application/views/content/login.html";
}

function top_admin(){
	parent.menu.location.href="http://localhost/sample/application/views/menu/menu_admin.html";
	parent.content.location.href="http://localhost/sample/application/views/content/top.html";
}

function top_general(){
	parent.menu.location.href="http://localhost/sample/application/views/menu/menu_general.html";
	parent.content.location.href="http://localhost/sample/application/views/content/top.html";
}

function top(){
	parent.menu.location.href="http://localhost/sample/application/views/menu/menu.html";
	parent.content.location.href="http://localhost/sample/application/views/content/top.html";
}

function calendar(){
	parent.content.location.href="http://localhost/sample/application/views/content/calendar.html";
}

function scheduling(){
	parent.content.location.href="http://localhost/sample/application/views/content/scheduling.html";
}

function s_rireki(){
	parent.content.location.href="http://localhost/sample/application/views/content/s_rireki.html";
}

function todo(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}

function todo_add(base_url,head_name){
	// CONTENT画面
	url=base_url.concat("index.php/todo");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function todo_update(){
	// CONTENT画面
	url=base_url.concat("index.php/todo/update_select_type?mode=update");
	parent.content.location.href=url;
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	window.parent.document.getElementById('baseset').rows = "117, *";
}

function todo_delete(){
	parent.content.location.href="http://localhost/sample/application/views/content/todo_delete.html";
}

function data_memo(){
	parent.content.location.href="http://localhost/sample/application/views/content/data_memo.html";
}

function message(){
	parent.content.location.href="http://localhost/sample/application/views/content/message.html";
}

function project_possession(){
	parent.content.location.href="http://localhost/sample/application/views/content/project_possession.html";
}

function data_output(){
	parent.content.location.href="http://localhost/sample/application/views/content/data_output.html";
}

function user(){
	parent.content.location.href="http://localhost/sample/application/views/content/user.html";
}

function project_item(){
	parent.content.location.href="http://localhost/sample/application/views/content/project_item.html";
}

function division(){
	parent.content.location.href="http://localhost/sample/application/views/content/division.html";
}

function item_visibility(){
	parent.content.location.href="http://localhost/sample/application/views/content/item_visibility.html";
}

function help(){
	parent.content.location.href="http://localhost/sample/application/views/content/help.html";
}

function logout(){
	top.location.href="http://localhost/sample/application/views/login.html";
}
