/*function search_todo(id){
	obj=(document.all)?document.all(id):((document.getElementById)?document.getElementById(id):null);
	if(obj)	obj.style.display=(obj.style.display=="none")?"block":"none";
}*/
function search_todo(base_url){
	if( formValidation($('form')) ) {
		document.todo.action = base_url+'index.php/todo/update_select_type?mode=search';
		document.todo.method = 'POST';
		document.todo.submit();
	}
}

function check_todo(base_url,head_name){
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";

}
