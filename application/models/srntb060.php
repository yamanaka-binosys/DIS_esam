<?php

class Srntb060 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	function get_srntb060_data($condition_data){
		log_message('debug',"========== srntb060 get_srntb060_data start ==========");
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
		$sql .= " FROM srntb060";
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
		
		log_message('debug',"========== srntb060 get_tmpsrntb060_data end ==========");
		return $result_data;
	}
	
	function update_srntb060_data($record_data)
	{
		if (!isset($record_data['jyohonum']))
		{
			log_message('error', 'cannot update without jyohonum');
			return false;
		}
		unset($record_data['edbn']);
		$jyohonum = $record_data['jyohonum'];
		unset($record_data['jyohonum']);
		return $this->db->update('srntb060', $record_data, array('jyohonum' => $jyohonum));
	}
	
	function insert_srntb060_data($record_data){
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
		
		$sql .= " INSERT INTO srntb060(";
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
	
	function delete_srntb060_data($jyohonum,$edbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srntb060";
		$sql .= " WHERE jyohonum = ?";
//		$sql .= " AND   edbn = '{$edbn}'";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}

	
		function update_checkar_data($ymd,$shbn,$checker)
	{
		try
		{
			log_message('debug',"========== update_busyo_data start ==========");
			log_message('debug',"========== update_busyo_data trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== update_busyo_data trans_start end ==========");
			// 初期化
			$res = FALSE;
			// sql文作成
			$sql = "
				UPDATE srntb060 SET 
				checker_edbn = ?
				WHERE  shbn = ? 
				 AND ymd = ? 
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			
			$query = $this->db->query($sql,array($checker,$shbn,$ymd));
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== update_busyo_data trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== update_busyo_data trans_complete end ==========");
			}else{
				log_message('debug',"========== update_busyo_data trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== update_busyo_data trans_rollback end ==========");
			}
			log_message('debug',"========== update_busyo_data end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== update_busyo_data catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== update_busyo_data catch trans_rollback end ==========");
			return $res;
		}
	}
	
	function get_checkar_data($shbn,$ymd)
	{
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT checker_edbn FROM srntb060 WHERE  shbn = ? AND ymd = ? ";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn,$ymd));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		return $result_data;
	}
	
}
?>
