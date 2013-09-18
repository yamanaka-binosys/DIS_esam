<?php

class Srntb120 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	function get_srntb120_data($condition_data){
		log_message('debug',"========== srntb120 get_srntb120_data start ==========");
		log_message('debug',"\$shbn = " . $condition_data['shbn']);
		log_message('debug',"\$jyohonum = " . $condition_data['jyohonum']);
		log_message('debug',"\$edbn = " . $condition_data['edbn']);
		
		// 引数より検索条件を取得
		// 情報ナンバー、枝番、社番
		if( ! isset($condition_data['jyohonum']) OR ! isset($condition_data['edbn']) OR ! isset($condition_data['shbn']))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		$shbn = $condition_data['shbn'];
		$jyohonum = $condition_data['jyohonum'];
//		$edbn = $condition_data['edbn'];
		$edbn = sprintf('%02d', $condition_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT *";                  
		$sql .= " FROM srntb120";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= "        AND edbn = '{$edbn}'";
		//$sql .= "        AND shbn = '{$shbn}'";
		$sql .= " WHERE jyohonum = ?";
		$sql .= "        AND edbn = ?";
		$sql .= "        AND shbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn,$shbn));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srntb120 get_srntb120_data end ==========");
		return $result_data;
	}
	
	function update_srntb120_data($record_data)
	{
		if (!isset($record_data['jyohonum']))
		{
			log_message('error', 'cannot update without jyohonum');
			return false;
		}
		unset($record_data['edbn']);
		$jyohonum = $record_data['jyohonum'];
		unset($record_data['jyohonum']);
		return $this->db->update('srntb120', $record_data, array('jyohonum' => $jyohonum));
	}
	
	function insert_srntb120_data($record_data)
	{
		unset($record_data['jyohonum']);
		unset($record_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		$name_string = "";
		$value_string = "";
		
		foreach ($record_data as $key => $value) {
			$name_string .= $key . ",";
			$value_string .= "'" . $value . "',";
		}
		
		$sql .= " INSERT INTO srntb120(";
//		$sql .= substr($name_string,0,-1);
		$sql .= $name_string;
		$sql .= "createdate";
		$sql .= ") VALUES (";
//		$sql .= substr($value_string,0,-1);
		$sql .= $value_string;
		$sql .= "'".date("Ymd")."'";
		$sql .= ")";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql);
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function delete_srntb120_data($jyohonum,$edbn){
		log_message('debug',"========== srntb120 delete_srntb120_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srntb120";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum));
		
		if($query){
			log_message('debug',"========== srntb120 delete_srntb120_data end ==========");
			return TRUE;
		}else{
			log_message('debug',"========== srntb120 delete_srntb120_data end ==========");
			return FALSE;
		}
	}
	
	function get_schedule_data($jyohonum,$edbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  sthm,";
		$sql .= "  aitesknm";
		$sql .= " FROM srntb120";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= " AND edbn = '{$edbn}'";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " AND edbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		return $result_data;
	}
	
}

?>
