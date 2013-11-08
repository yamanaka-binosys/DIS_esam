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
function get_buka_calender_top(base_url){
    //alert(base_url);
    url=base_url.concat("index.php/top/set_buka_shbn/");
    var index = parent.content.buka_shbn.selectedIndex;
    //要素が持つ値
    var str = parent.content.buka_shbn.options[index].value;
    url=url.concat(str);
    //alert(url);
    location.replace(url);
    
}