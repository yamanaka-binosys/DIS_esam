<?php

class Srntb150 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 
	 */
	function get_subordinate_data($shbn){
		log_message('debug',"========== srntb150 get_subordinate_data start ==========");
		// 初期化
		$sql = "";           // sql_regcase文字列
		$query = NULL;       // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  shbn,";
		$sql .= "  kubun,";
		$sql .= "  jyohonum,";
		$sql .= "  edbn";
		$sql .= " FROM srntb150";
		$sql .= " WHERE kakninshbn = ?";
		$sql .= " ORDER BY ymd,sthm";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn));
		// 取得確認
		if($query->num_rows() > MY_ZERO){
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srntb150 get_subordinate_data end ==========");
		return $result_data;
	}
	
	
	
	
	
	
	
}

?>
