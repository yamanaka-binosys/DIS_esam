<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Division_manager {
	
	function set_add_view($post = NULL){
		log_message('debug',"========== libraries division_manager set_add_view start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		
		$view_data = "";
		$view_data .= "<table>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>画面名</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<input type=\"text\" name=\"gamennm\" size=\"20\" style=\"width:148px;\" value=\"";
		$view_data .= (empty($post['gamennm'])) ? "": $post['gamennm'] ;
		$view_data .= "\" />\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>項目名</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<input type=\"text\" name=\"koumoknm\" size=\"20\"  style=\"width:148px;\" value=\"";
		$view_data .= (empty($post['koumoknm'])) ? "": $post['koumoknm'] ;
		$view_data .= "\" />\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>区分ID</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<input type=\"text\" name=\"kbnid\" size=\"3\" maxlength=\"3\" value=\"";
		$view_data .= (empty($post['kbnid'])) ? "": $post['kbnid'] ;
		$view_data .= "\" />\n";
		$view_data .= "</td>\n";
		$view_data .= "<td>管理タイプ</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<select name=\"ktype\">";
		$view_data .= "<option value=\"0\"";
		$view_data .= (!empty($post['ktype']) AND $post['ktype'] == "0") ? " selected": "" ;
		$view_data .= "></option>";
		$view_data .= "<option value=\"1\"";
		$view_data .= (!empty($post['ktype']) AND $post['ktype'] == "1") ? " selected": "" ;
		$view_data .= ">システム</option>";
		$view_data .= "<option value=\"2\"";
		$view_data .= (!empty($post['ktype']) AND $post['ktype'] == "2") ? " selected": "" ;
		$view_data .= ">ユーザー</option>";
		$view_data .= "</select>";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "</table>\n";
		$view_data .= "<br>\n";
		$view_data .= "<br>\n";
		// 一覧内容部分のHTML作成
		$view_data .= $this->set_list_view($post);
		log_message('debug',"========== libraries division_manager set_add_view end ==========");
		return $view_data;
	}
	
	/**
	 * メインのHTML作成
	 * 
	 * @access public
	 * @param  array $search_type TRUE:画面名検索済み FALSE:画面名未検索
	 * @param  array $post        POSTデータ
	 * @return array $view_data   HTML-STRINGデータ
	 */
	function set_up_del_view($search_type,$post = NULL){
		log_message('debug',"========== libraries division_manager set_add_view start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		
		// 画面名検索結果チェック
		if($search_type){
			$CI->load->library('item_manager');
			// 項目名の取得
			$k_data = $this->get_koumoku_data($post['gamennm']);
		}
		
		$view_data = "";
		$view_data .= "<table>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>画面名</td>\n";
		$view_data .="<td>";
		$view_data .="<select name=\"gamennm\" style=\"width:150px\">";
		if($post['gamennm']=="スケジュール"){
		$view_data .="<option value=\"スケジュール\" selected=\"selected\">スケジュール</option>";
		}else{
		$view_data .="<option value=\"スケジュール\">スケジュール</option>";
		}
		if($post['gamennm']=="日報実績"){
		$view_data .="<option value=\"日報実績\" selected=\"selected\">日報実績</option>";
		}else{
		$view_data .="<option value=\"日報実績\">日報実績</option>";
		}
		if($post['gamennm']=="TODO"){
		$view_data .="<option value=\"TODO\" selected=\"selected\">TODO</option>";
		}else{
		$view_data .="<option value=\"TODO\">TODO</option>";
		}
		if($post['gamennm']=="情報メモ"){
		$view_data .="<option value=\"情報メモ\" selected=\"selected\">情報メモ</option>";
		}else{
		$view_data .="<option value=\"情報メモ\">情報メモ</option>";
		}
		if($post['gamennm']=="仮相手先"){
		$view_data .="<option value=\"仮相手先\" selected=\"selected\">仮相手先</option>";
		}else{
		$view_data .="<option value=\"仮相手先\">仮相手先</option>";
		}
		if($post['gamennm']=="ユーザー管理"){
		$view_data .="<option value=\"ユーザー管理\" selected=\"selected\">ユーザー管理</option>";
		}else{
		$view_data .="<option value=\"ユーザー管理\">ユーザー管理</option>";
		}
		$view_data .="</select>";
		$view_data .= "<td>\n";
		$view_data .= "<input type=\"submit\" name=\"g_search\" value=\"検索\" />\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>項目名</td>\n";
		$view_data .= "<td>\n";
		// 項目名のinput type判定
		// 画面名検索済み
		if($search_type){
			if($k_data)
			{
				$view_data .= $CI->item_manager->set_variable_dropdown_string($k_data);
			}else{
				$view_data .= "<input type=\"text\" name=\"koumoknm\" size=\"20\" maxlength=\"30\" value=\"";
				$view_data .= (empty($post['koumoknm'])) ? "\"": $post['koumoknm']."\"" ;
				$view_data .= " readonly";
				$view_data .=  "/>\n";
			}
		}else{
			// 画面名未検索
			$view_data .= "<input type=\"text\" name=\"koumoknm\" size=\"20\" maxlength=\"30\" value=\"";
			$view_data .= (empty($post['koumoknm'])) ? "\"": $post['koumoknm']."\"" ;
			$view_data .= " readonly";
			$view_data .=  "/>\n";
		}
		$view_data .= "</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<input type=\"submit\" name=\"k_search\" value=\"検索\" ";
		// ボタン無効化判定
		if(!$search_type OR !$k_data){
			$view_data .= " disabled";
		}
		$view_data .= "/>\n";
		$view_data .= "</td>\n";
		// 削除データの場合、表示
		if(!empty($post['k_delete']))
		{
			$view_data .= "<td>\n";
			$view_data .= "削除データです";
			$view_data .= "</td>\n";
		}
		
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>区分ID</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<input type=\"text\" name=\"kbnid\" size=\"3\" maxlength=\"3\" value=\"";
		$view_data .= (empty($post['kbnid'])) ? "\"": $post['kbnid'] ."\"";
		$view_data .= " readonly";
		$view_data .=  "/>\n";
		$view_data .= "</td>\n";
		// 管理タイプ設定(削除ボタン押下用)
		if(empty($post['ktype']))
		{
			$post['ktype'] = $post['h_ktype'];
		}
		$view_data .= "<td>管理タイプ</td>\n";
		$view_data .= "<td>\n";
		$view_data .= "<select name=\"ktype\"";
		$view_data .= (!empty($post['del'])) ? " disabled": "" ;
		$view_data .= ">";
		$view_data .= "<option value=\"0\"";
		$view_data .= (!empty($post['ktype']) && $post['ktype'] == "0") ? " selected": "" ;
		$view_data .= "></option>";
		$view_data .= "<option value=\"1\"";
		$view_data .= (!empty($post['ktype']) && $post['ktype'] == "1") ? " selected": "" ;
		$view_data .= ">システム</option>";
		$view_data .= "<option value=\"2\"";
		$view_data .= (!empty($post['ktype']) && $post['ktype'] == "2") ? " selected": "" ;
		$view_data .= ">ユーザー</option>";
		$view_data .= "</select>";
		$view_data .= "<input type=\"hidden\" name=\"h_ktype\" value=\"".$post['ktype']."\">";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "</table>\n";
		$view_data .= "<br>\n";
		$view_data .= "<br>\n";
		// 一覧内容部分のHTML作成
		$view_data .= $this->set_list_view($post);
		log_message('debug',"========== libraries division_manager set_add_view end ==========");
		return $view_data;
	}

	/**
	 * 一覧内容部分のHTML作成
	 * 
	 * @access public
	 * @param  array $post POSTデータ
	 * @return array $view_data HTML-STRINGデータ
	 */
	function set_list_view($post = NULL){
		$view_data = "";
		$view_data .= "<table>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td colspan=\"3\">【一覧内容】</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>№</td>\n";
		$view_data .= "<td>№</td>\n";
		$view_data .= "<td>№</td>\n";
		$view_data .= "</tr>\n";
		for($i = 1;$i <= MY_KBN_LINE_MAX; $i++)
		{
			$no_o = $i + MY_KBN_LINE_MAX; // 10～20
			$no_t = $i + (MY_KBN_LINE_MAX * 2); // 20～30
			//////////////空判定///////////////////////////////////////
			// 一桁
			if($i == MY_KBN_LINE_MAX)
			{
				if(empty($post['ichiran'.$i]))
				{
					$post['ichiran'.$i] = "";
				}
			}else{
				if(empty($post['ichiran0'.$i]))
				{
					$post['ichiran0'.$i] = "";
				}
			}
			// 10番台
			if(empty($post['ichiran'.$no_o])){
				$post['ichiran'.$no_o] = "";
			}
			// 20番台
			if(empty($post['ichiran'.$no_t])){
				$post['ichiran'.$no_t] = "";
			}
			///////////////////////////////////////////////////////////
			$view_data .= "<tr>\n";
			$view_data .= "<td>\n";
			if($i == MY_KBN_LINE_MAX)
			{
				$view_data .= $i."　<input type=\"text\" name=\"ichiran".$i."\"  style=\"width:148px\" maxlength=\"256\" value=\"".$post['ichiran'.$i]."\" />\n";
				$view_data .= "</td>\n";
			}else{
				$view_data .= $i." 　<input type=\"text\" name=\"ichiran0".$i."\" style=\"width:148px\" maxlength=\"256\"  value=\"".$post['ichiran0'.$i]."\" />\n";
				$view_data .= "</td>\n";
			}
				$view_data .= "<td>\n";
				$view_data .= $no_o." <input type=\"text\" name=\"ichiran".$no_o."\" style=\"width:148px\" maxlength=\"256\"  value=\"".$post['ichiran'.$no_o]."\" />\n";
				$view_data .= "</td>\n";
				$view_data .= "<td>\n";
				$view_data .= $no_t." <input type=\"text\" name=\"ichiran".$no_t."\" style=\"width:148px\" maxlength=\"256\"  value=\"".$post['ichiran'.$no_t]."\" />\n";
				$view_data .= "</td>\n";
				$view_data .= "</tr>\n";
		}
		$view_data .= "</table>\n";
		
		return $view_data;
	}
	
	/**
	 * 登録処理
	 * 
	 * @access public
	 * @param  array $data30   区分情報
	 * @param  array $data31   区分名称情報
	 * @return array 
	 */
	function set_db_insert_data($data30,$data31){
		log_message('debug',"========== libraries division_manager set_db_insert_data start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('sgmtb030','sgmtb031'));	
		// DB登録処理
		$CI->sgmtb030->insert_sgmtb030_data($data30);
		$CI->sgmtb031->insert_sgmtb031_data($data31);
		
		log_message('debug',"========== libraries division_manager set_db_insert_data end ==========");
	}
	
	/**
	 * 更新処理
	 * 
	 * @access public
	 * @param  array $data30 区分情報
	 * @param  array $data31 区分名称情報
	 * @return bool  $result TRUE:成功 FALSE:失敗
	 */
	function set_db_update_data($data30,$data31){
		log_message('debug',"========== libraries division_manager set_db_update_data start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('sgmtb030','sgmtb031'));	
		$sgmtb030_result = FALSE;
		$sgmtb031_result = FALSE;
		$result = FALSE;
		// DB登録処理
		$sgmtb030_result = $CI->sgmtb030->update_sgmtb030_data($data30);
		$sgmtb031_result = $CI->sgmtb031->update_sgmtb031_data($data31);
		
		if($sgmtb030_result && $sgmtb031_result)
		{
			// 成功
			$result = TRUE;
		}else{
			// 失敗
			$result = FALSE;
		}
		
		log_message('debug',"========== libraries division_manager set_db_update_data end ==========");
		return $result;
	}

	/**
	 * 削除処理
	 * 
	 * @access public
	 * @param  array $data30 区分情報
	 * @param  array $data31 区分名称情報
	 * @return bool  $result TRUE:成功 FALSE:失敗
	 */
	function set_db_delete_data($data30,$data31){
		log_message('debug',"========== libraries division_manager set_db_delete_data start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('sgmtb030','sgmtb031'));	
		$sgmtb030_result = FALSE;
		$sgmtb031_result = FALSE;
		$result = FALSE;
		// DB登録処理
		$sgmtb030_result = $CI->sgmtb030->delete_sgmtb030_data($data30);
		$sgmtb031_result = $CI->sgmtb031->delete_sgmtb031_data($data31);
		
		if($sgmtb030_result && $sgmtb031_result)
		{
			// 成功
			$result = TRUE;
		}else{
			// 失敗
			$result = FALSE;
		}
		
		log_message('debug',"========== libraries division_manager set_db_delete_data end ==========");
		return $result;
	}

	/**
	 * 区分データ(項目名の取得)
	 * 
	 * @access public
	 * @param  array $post POSTデータ
	 * @return array $kbn_data 区分データ(項目名)
	 */
	function get_search_kbn_data($post){
		log_message('debug',"========== libraries division_manager get_search_kbn_data start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('sgmtb030','sgmtb031'));	
		
		// 区分情報の検索
		$kbn_data = $CI->sgmtb030->get_kbn_data($post['koumoknm']);
		$no_o = 0;
		$no_t = 0;
		for($i = 0; $i < MY_KBN_LINE_MAX; $i++)
		{
			$no_o = $i + MY_KBN_LINE_MAX + 1;		// №11～20
			$no_t = $i + (MY_KBN_LINE_MAX * 2) + 1; // №21～30
			
			// №10の場合
			if(isset($kbn_data[$i]['ichiran']))
			{
				if($i == (MY_KBN_LINE_MAX - 1))
				{
					$post['ichiran'.($i + 1)] = $kbn_data[$i]['ichiran'];    //№10の処理
					$post['ichiran'.($i + 2)] = $kbn_data[$i+1]['ichiran'];  //№11の処理
				}else{
				// №1～
					$post['ichiran0'.($i + 1)] = $kbn_data[$i]['ichiran'];
				}
			}
			// №11～
			if(isset($kbn_data[$no_o]['ichiran']))
			{
				$post['ichiran'.($no_o + 1)] = $kbn_data[$no_o]['ichiran'];
			}
			// №21～
			if(isset($kbn_data[$no_t]['ichiran']))
			{
				$post['ichiran'.($no_t + 1)] = $kbn_data[$no_t]['ichiran'];
			}
		}
		log_message('debug',"========== libraries division_manager get_search_kbn_data end ==========");
		return $post;
	}

	/**
	 * 区分データ(区分情報)
	 * 
	 * @access public
	 * @param  array $kbnid 区分ID
	 * @return array $kbn_data 区分データ(項目名)
	 */
	function get_sgmtb031_data($kbnid){
		log_message('debug',"========== libraries division_manager get_search_kbn_data start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model('sgmtb031');	
		
		// 区分IDから区分情報の検索
		$kbn_data = $CI->sgmtb031->get_kbn_data($kbnid);
		log_message('debug',"========== libraries division_manager get_search_kbn_data end ==========");
		return $kbn_data;
	}

	/**
	 * 項目名一覧取得
	 * 
	 * @access public
	 * @param  array $view_name     画面名
	 * @return array $res_data DB登録用生成データ
	 */
	function get_koumoku_data($view_name){
		log_message('debug',"========== libraries division_manager get_koumoku_data start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('sgmtb031'));
		$k_list_data = NULL;
		$res_data = FALSE;
		// 項目名取得
		$k_name_data = $CI->sgmtb031->get_koumoku_name($view_name);
		// ドロップダウン項目データ生成
//		$k_list_data['000'] = "";
		if($k_name_data)
		{
			foreach($k_name_data as $k_data){
				foreach($k_data as $key => $k_value){
					if($key === 'koumoknm')
					{
						$k_list_data[$k_data['kbnid']] = $k_value;
					}					
				}
			}
			$res_data = array(
				'title_name' => '',
				'name'  => 'koumoknm',
				'data'  => $k_list_data,
				'check' => '000'
			);
		}
		
		log_message('debug',"========== libraries division_manager get_koumoku_data end ==========");
		return $res_data;
	}
	
	/**
	 * 登録用データ生成処理
	 * 
	 * @access public
	 * @param  array $data     POSTデータ
	 * @return array $res_data DB登録用生成データ
	 */
	function insert_data_set($data){
		log_message('debug',"========== libraries division_manager insert_data_set start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->model('sgmtb031');
		$new_kid = NULL;
		$res_kid = NULL;
		$res_data['sgmtb030'] = NULL;
		$res_data['sgmtb031'] = NULL;
		
		$res_kid = $CI->sgmtb031->get_max_id();
		if (is_null($res_kid)) {
			$new_kid = '001';
		}else{
			$tmp_id = (int)$res_kid['0']['kbnid'];
			$new_kid = sprintf('%03d',($tmp_id + 1));
		}
		
		$res_data['sgmtb031'] = array(
			'kbnid' => $new_kid,
			'gamennm' => $data['gamennm'],
			'koumoknm' => $data['koumoknm'],
			'ktype' => $data['ktype']
		);
		
		foreach ($data as $key => $value) {
			if (substr($key,0,-2) === 'ichiran') {
				$res_data['sgmtb030'][] = array(
					'kbnid' => $new_kid,
					'kbncd' => sprintf('%03d',substr($key,-2)),
					'ichiran' => $value
				);
			}
		}
		
		log_message('debug',"========== libraries division_manager insert_data_set end ==========");
		return $res_data;
	}
	
	/**
	 * 更新・削除用データ生成処理
	 * 
	 * @access public
	 * @param  array $data     POSTデータ
	 * @param  bool  $del_flg  削除判定フラグ TRUE:削除 FALSE:更新
	 * @return array $res_data DB更新・削除用生成データ
	 */
	function up_del_data_set($data,$del_flg = FALSE){
		log_message('debug',"========== libraries division_manager up_del_data_set start ==========");
		// 初期化
		$CI =& get_instance();
		// 初期化
		$kid = NULL;
		$res_kid = NULL;
		$res_data['sgmtb030'] = NULL;
		$res_data['sgmtb031'] = NULL;
		$date_type = NULL;
		
		$kid = $data['kbnid'];
		$date = date("Ymd");
		
		// 更新・削除判定
		if($del_flg)
		{
			$date_type = 'deletedate';
			$data['ktype'] = $data['h_ktype'];
		}else{
			$date_type = 'updatedate';
		}
		
		$res_data['sgmtb031'] = array(
			'kbnid' => $kid,
			'gamennm' => $data['gamennm'],
			'koumoknm' => $data['koumoknm'],
			'ktype' => $data['ktype'],
			$date_type => $date
		);
		
		foreach ($data as $key => $value) {
			if (substr($key,0,-2) === 'ichiran') {
				$res_data['sgmtb030'][] = array(
					'kbnid' => $kid,
					'kbncd' => sprintf('%03d',substr($key,-2)),
					'ichiran' => $value,
					$date_type => $date					
				);
			}
		}
		log_message('debug',"========== libraries division_manager up_del_data_set end ==========");
		return $res_data;
	}
	
}

// END Division_manager class

/* End of file Division_manager.php */
/* Location: ./application/libraries/Division_manager.php */
