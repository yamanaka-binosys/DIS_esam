<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_memo_manager {

	/**
	 *　検索用テーブル生成データ抽出
	 * @param <type> $sql_val　SRKTB050 テーブルデータ
	 * @return <type> 検索LIST作成
	 */
	public function _get_data_memo_search($sql_val)
	{
		// データ取得
		// 未完成
		if($sql_val)
		{
			foreach($sql_val as $key => $val) 
			{				
				$search_data[$key][0] = substr($val["createdate"], 0, 4)."/".substr($val["createdate"], 4, 2)."/".substr($val["createdate"], 6, 2);
				$search_data[$key][1] = $val["knnm"];
				$search_data[$key][2] = $val["aitesknm"];
				$search_data[$key][3] = ($val["etujukyo"] == 1) ? '既読' : '未読';
				$search_data[$key][4] = $val["jyohonum"];
				$search_data[$key][5] = $val["edbn"];
			}
		}else{
			// 検索該当なし
			$search_data = NULL;
		}


		return $search_data;
	}
	
	public function _get_data_memo_search_s($sql_val)
	{
		// データ取得
		// 検索該当有り
		if($sql_val)
		{
			foreach($sql_val as $key => $val) 
			{				
				$search_data[$key][0] = substr($val["createdate"], 0, 4)."/".substr($val["createdate"], 4, 2)."/".substr($val["createdate"], 6, 2);
				$search_data[$key][1] = $val["knnm"];
				$search_data[$key][2] = $val["bunm"];
				$search_data[$key][3] = $val["shinnm"];
				$search_data[$key][4] = $val["aitesknm"];
				$search_data[$key][5] = ($val["etujukyo"] == 1) ? '既読' : '未読';
				$search_data[$key][6] = $val["jyohonum"];
				$search_data[$key][7] = $val["edbn"];
			}
		}else{
			// 検索該当なし
			$search_data = NULL;
		}


		return $search_data;
	}
	
	/**
	 *　ソート用検索条件生成
	 * @param  array $post_data　 POSTデータ
	 * @return array $search_data 検索条件
	 */
	public function _set_search_data($post_data = NULL)
	{
		$search_data = NULL;
		// POSTデータの変換処理
		
		if($post_data['s_knnm']!=""){
		$search_data['knnm'] = $post_data['s_knnm'];
		}else{
		$search_data['knnm'] ="";
		}
		if($post_data['s_info']!=""){
		$search_data['info'] = $post_data['s_info'];
		}else{
		$search_data['info'] ="";
		}
		if($post_data['s_maker']!=""){
		$search_data['maker'] = $post_data['s_maker'];
		}else{
		$search_data['maker'] ="";
		}

		if($post_data['s_jyohokbnm']!=""){
		$search_data['jyohokbnm'] = $post_data['s_jyohokbnm'];
		}else{
		$search_data['jyohokbnm'] ="000";
		}
		if($post_data['s_hinsyukbnm']!=""){
		$search_data['hinsyukbnm'] = $post_data['s_hinsyukbnm'];
		}else{
		$search_data['hinsyukbnm'] ="000";
		}
		if($post_data['s_tishokbnm']!=""){
		$search_data['tishokbnm'] = $post_data['s_tishokbnm'];
		}else{
		$search_data['tishokbnm'] ="000";
		}

		if($post_data['s_s_year']!=""){
		$search_data['s_year'] = $post_data['s_s_year'];
		}else{
		$search_data['s_year'] ="";
		}
		if($post_data['s_s_month']!=""){
		$search_data['s_month'] = $post_data['s_s_month'];
		}else{
		$search_data['s_month'] ="";
		}
		if($post_data['s_s_day']!=""){
		$search_data['s_day'] = $post_data['s_s_day'];
		}else{
		$search_data['s_day'] ="";
		}
		if($post_data['s_e_year']!=""){
		$search_data['e_year'] = $post_data['s_e_year'];
		}else{
		$search_data['e_year'] ="";
		}
		if($post_data['s_e_month']!=""){
		$search_data['e_month'] = $post_data['s_e_month'];
		}else{
		$search_data['e_month'] ="";
		}
		if($post_data['s_e_day']!=""){
		$search_data['e_day'] = $post_data['s_e_day'];
		}else{
		$search_data['e_day'] ="";
		}
		
		if($post_data['s_honbucd']!=""){
		$search_data['honbucd'] = $post_data['s_honbucd'];
		}else{
		$search_data['honbucd'] ="";
		}
		
		if($post_data['s_bucd']!=""){
		$search_data['bucd'] = $post_data['s_bucd'];
		}else{
		$search_data['bucd'] ="";
		}
		
		if($post_data['s_kacd']!=""){
		$search_data['kacd'] = $post_data['s_kacd'];
		}else{
		$search_data['kacd'] ="";
		}
		
		if($post_data['s_user']!=""){
		$search_data['user'] = $post_data['s_user'];
		}else{
		$search_data['user'] ="";
		}
		
		
		
		//$search_data['aitesknm'] = $post_data['s_aitesknm'];
		//$search_data['yksyoku'] = $post_data['s_yksyoku'];
		//$search_data['name'] = $post_data['s_name'];
		return $search_data;
	}
	
	/**
	 *　ソート用検索条件生成(更新・削除画面）
	 * @param  array $post_data　 POSTデータ
	 * @return array $search_data 検索条件
	 */
	public function _set_search_data_ud($post_data = NULL)
	{
		$search_data = NULL;
		// POSTデータの変換処理
		$search_data['s_year'] = $post_data['s_s_year'];
		$search_data['s_month'] = $post_data['s_s_month'];
		$search_data['s_day'] = $post_data['s_s_day'];
		$search_data['e_year'] = $post_data['s_e_year'];
		$search_data['e_month'] = $post_data['s_e_month'];
		$search_data['e_day'] = $post_data['s_e_day'];
		return $search_data;
	}

}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
