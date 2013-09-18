/**
 * @author h-kana
 */
function read_comp(){
	
}
function data_memo_search_select_top(jyohonum,edbn,base_url){
	url = "index.php/data_memo/search_select/" + jyohonum + "/" + edbn + "/";
  search_url = base_url.concat(url);
	window.open(search_url, 'data_memo_search_select','width=600,height=600,').focus();
}
