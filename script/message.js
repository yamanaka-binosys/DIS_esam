function header_load(base_url,header_msg){
	head_url=base_url.concat("index.php/header/index/message/");
	parent.header.location.href=head_url;

	window.parent.document.getElementById('baseset').rows = "117, *";
	
	top.header.document.getElementById("errmsg").innerText = header_msg;
	top.header.document.getElementById("errmsg").textContent = header_msg;
}
