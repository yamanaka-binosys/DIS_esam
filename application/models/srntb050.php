<?php

class Srntb050 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * ユニット長閲覧状況取得
	 *
	 * @access public
	 * @param  string $shbn        社番
	 * @return array  $result_data ユニット長閲覧状況データ
	 */
	function get_read_report_data($shbn){
		log_message('debug',"========== srntb050 get_read_report_data start ==========");
		// 初期化
		$sql = "";           // sql_regcase文字列
		$query = NULL;       // SQL実行結果
		$result_data = NULL; // 戻り値
		// 当日 
		//$date = (int)(date("Ymd"));
		$today = (int)(date("Ymd"));
		// 掲載期間の設定
		$after = MY_WEEK_DAY * MY_READ_PERIOD;
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  ymd,";
		$sql .= "  kakninshbn,";
		$sql .= "  etujukyo,";
		$sql .= "  comment";
		$sql .= " FROM";
		$sql .= "  srntb050";
		$sql .= " WHERE shbn = '{$shbn}'";
		$sql .= " AND (CAST(ymd as int) >= {$today}";
		$sql .= " AND (to_date( createdate, 'YYYYMMDD' ) + {$after}) >= to_date('{$today}','YYYYMMDD')";
		$sql .= " OR etujukyo = '0')";
		$sql .= " ORDER BY Ymd DESC";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > MY_ZERO){
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srntb050 get_read_report_data end ==========");
		return $result_data;
	}
	
	/**
	 * 
	 */
	function get_result_report_data($shbn){
		log_message('debug',"========== srntb050 get_result_report_data start ==========");
		// 初期化
		$sql = "";           // sql_regcase文字列
		$query = NULL;       // SQL実行結果
		$result_data = NULL; // 戻り値
		// 当日 
		//$date = (int)(date("Ymd"));
		$today = (int)(date("Ymd"));
		// 掲載期間の設定
		$after = MY_WEEK_DAY * MY_RESULT_PERIOD;
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  ymd,";
		$sql .= "  shbn,";
		$sql .= "  etujukyo,";
		$sql .= "  comment";
		$sql .= " FROM";
		$sql .= "  srntb050";
		//$sql .= " WHERE kakninshbn = '{$shbn}'";
		//$sql .= " AND (CAST(ymd as int) >= {$today}";
		//$sql .= " AND (to_date( createdate, 'YYYYMMDD' ) + {$after}) >= to_date('{$today}','YYYYMMDD')";
		$sql .= " WHERE kakninshbn = ?";
		$sql .= " AND (CAST(ymd as int) >= ?";
		$sql .= " AND (to_date( createdate, 'YYYYMMDD' ) + ? ) >= to_date('?','YYYYMMDD')";
		$sql .= " OR etujukyo = '0')";
		$sql .= " ORDER BY Ymd DESC";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn,$today,$after,$today));
		// 取得確認
		if($query->num_rows() > MY_ZERO){
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srntb050 get_result_report_data end ==========");
		return $result_data;
	}
	
	function insert_srntb050_data($record_data){
		log_message('debug',"========== srntb050 insert_srntb050_data start ==========");
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
		
		$sql .= " INSERT INTO srntb050(";
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
			log_message('debug',"========== srntb050 insert_srntb050_data end ==========");
			return TRUE;
		}else{
			log_message('debug',"========== srntb050 insert_srntb050_data end ==========");
			return FALSE;
		}
	}
	
	function delete_srntb050_data($shbn,$ymd){
		log_message('debug',"========== srntb050 delete_srntb050_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srntb050";
		$sql .= " WHERE shbn = ?";
		$sql .= " AND ymd = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn,$ymd));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
		log_message('debug',"========== srntb050 delete_srntb050_data end ==========");
	}
	
	
	
}

?>
