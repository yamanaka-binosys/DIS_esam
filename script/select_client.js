
function show_select_client(base_url,head_name){
	// HEADER画面
	head_url=base_url.concat("index.php/header/index/",head_name);
	parent.header.location.href=head_url;
	
	window.parent.document.getElementById('baseset').rows = "117, *";
	
	}

