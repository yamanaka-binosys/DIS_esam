<?php

class Sgmtb031 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	function insert_sgmtb031_data($record_data){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$name_string = "";
		$value_string = "";
		
		// 引数をINSERT分の形に生成
		foreach ($record_data as $key => $value) {
			$name_string .= $key . ",";
			$value_string .= "'" . $value . "',";
		}
		// 登録日設定
		$name_string .= "createdate";
		$value_string .= "'" . date("Ymd") . "'";
		// SQL作成
		$sql .= " INSERT INTO sgmtb031(";
		$sql .= $name_string;
		$sql .= ") VALUES (";
		$sql .= $value_string;
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

	/**
	 * 更新処理
	 * 
	 * @access public
	 * @param  array $record_data 
	 * @return bool  TRUE:成功 FALSE:失敗
	 */
	function update_sgmtb031_data($record_data){
		log_message('debug',"========== sgmtb031 update_sgmtb031_data start ==========");
		try{
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$sql = ""; // sql_regcase文字列
			$query = NULL; // SQL実行結果
			$result = FALSE;
			// SQL文作成
			$sql .= " UPDATE sgmtb031 SET ";
			foreach($record_data as $key => $value){
				if($key === 'updatedate')
				{
					$sql .= $key ." = '".$value."' WHERE kbnid = '".$record_data['kbnid']."'";
				}else{
					$sql .= $key ." = '".$value."', ";
				}
			}
			$sql .= " ;";

			log_message('debug',"\$sql = $sql");

			// SQL実行
			$query = $this->db->query($sql);

			if($query){
				$result = TRUE;
				// トランザクション終了(コミット)
				$this->db->trans_complete();
			}else{
				$result = FALSE;
				// ロールバック
				$this->db->trans_rollback();
			}
			log_message('debug',"========== sgmtb031 update_sgmtb031_data end ==========");
			return $result;
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			return FALSE;
		}
	}

	/**
	 * 更新処理
	 * 
	 * @access public
	 * @param  array $record_data 
	 * @return bool  TRUE:成功 FALSE:失敗
	 */
	function delete_sgmtb031_data($record_data){
		log_message('debug',"========== sgmtb031 delete_sgmtb031_data start ==========");
		try{
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$sql = ""; // sql_regcase文字列
			$query = NULL; // SQL実行結果
			$result = FALSE;
			// SQL文作成
			$sql .= " UPDATE sgmtb031 SET ";
			foreach($record_data as $key => $value){
				if($key === 'deletedate')
				{
					$sql .= $key ." = '".$value."' WHERE kbnid = '".$record_data['kbnid']."'";
				}else{
					$sql .= $key ." = '".$value."', ";
				}
			}
			$sql .= " ;";

			log_message('debug',"\$sql = $sql");

			// SQL実行
			$query = $this->db->query($sql);

			if($query){
				$result = TRUE;
				// トランザクション終了(コミット)
				$this->db->trans_complete();
			}else{
				$result = FALSE;
				// ロールバック
				$this->db->trans_rollback();
			}
			log_message('debug',"========== sgmtb031 delete_sgmtb031_data end ==========");
			return $result;
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			return FALSE;
		}
	}

	/**
	 * 登録区分ID最大値の取得
	 * 
	 * @access public
	 * @param  noting
	 * @return integer $result_data 区分ID最大値
	 */
	function get_max_id(){
		log_message('debug',"========== sgmtb031 get_max_id start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT MAX(kbnid) AS kbnid FROM sgmtb031;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		log_message('debug',"========== sgmtb031 get_max_id end ==========");
		return $result_data;
	}
	
	/**
	 * 項目名データ取得
	 * 
	 * @access public
	 * @param  array $view_name     画面名
	 * @return array $res_data DB登録用生成データ
	 */
	function get_koumoku_name($view_name){
		log_message('debug',"========== sgmtb031 get_koumoku_name start ==========");
		// 初期化
		$sql = "";           // sql_regcase文字列
		$query = NULL;       // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT kbnid,gamennm,koumoknm,deletedate FROM sgmtb031 WHERE gamennm = ?;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($view_name));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		log_message('debug',"========== sgmtb031 get_koumoku_name end ==========");
		return $result_data;
	}
	
	/**
	 * 区分情報の取得
	 * 
	 * @access public
	 * @param  string $kbnid   区分ID      
	 * @return array $res_data 区分情報
	 */
	function get_kbn_data($kbnid)
	{
		log_message('debug',"========== sgmtb031 get_kbn_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT kbnid,gamennm,koumoknm,ktype,deletedate FROM sgmtb031 WHERE kbnid = ?;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($kbnid));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$res_data = $query->result_array();
			
		}
		log_message('debug',"========== sgmtb031 get_kbn_data end ==========");
		return $res_data[0];
	}
	
		
}

?>
