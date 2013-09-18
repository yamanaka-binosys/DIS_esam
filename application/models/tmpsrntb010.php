<?php

class Tmpsrntb010 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 
	 * 
	 * 
	 * 
	 */
	function get_tmpsrntb010_data($condition_data){
		log_message('debug',"========== tmpsrntb010 get_tmpsrntb010_data start ==========");
		log_message('debug',"\$shbn = " . $condition_data['shbn']);
		log_message('debug',"\$jyohonum = " . $condition_data['jyohonum']);
		log_message('debug',"\$edbn = " . $condition_data['edbn']);
		// 引数より検索条件を取得
		// 情報ナンバー、枝番、社番
		$shbn = $condition_data['shbn'];
		$jyohonum = $condition_data['jyohonum'];
		$edbn = sprintf('%02d', $condition_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT *";
		$sql .= " FROM tmpsrntb010";
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
		
		log_message('debug',"========== tmpsrntb010 get_tmpsrntb010_data end ==========");
		return $result_data;
	}
	
	function insert_tmpsrntb010_data($record_data){
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
		
		$sql .= " INSERT INTO tmpsrntb010(";
		$sql .= $name_string;
		$sql .= "createdate";
		$sql .= ") VALUES (";
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
	
	function update_tmpsrntb010_data($jyohonum,$edbn,$aiteskcd,$aitesk_name,$aiteskrank){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= " UPDATE tmpsrntb010";
		$sql .= "  SET aiteskcd = ?,";
		$sql .= "      aitesknm = ?,";
		$sql .= "      aiteskrank = ?,";
		$sql .= "      updatedate = '".date("Ymd")."'";
		$sql .= " WHERE jyohonum =?";
		$sql .= " AND   edbn = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($aiteskcd,$aitesk_name,$aiteskrank,$jyohonum,$edbn));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function delete_tmpsrntb010_data($jyohonum,$edbn,$shbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM tmpsrntb010";
		$sql .= " WHERE jyohonum = ? and shbn = ? ";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$shbn));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_max_jyohonum()
	{
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT MAX(jyohonum) AS jyohonum FROM tmpsrntb010;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		return $result_data;
	}
	
	function get_max_edbn($jyohonum)
	{
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT MAX(edbn) AS edbn ";
		$sql .= " FROM tmpsrntb010";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		return $result_data;
	}
	
	
	
}

?>
