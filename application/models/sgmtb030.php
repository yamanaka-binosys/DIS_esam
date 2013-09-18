<?php

class Sgmtb030 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 登録
	 *
	 * @access	public
	 * @param	string  $record_data 登録データ
	 * @return	nothing
	 */
	function insert_sgmtb030_data($record_data){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$sqle = "";
		$query = NULL; // SQL実行結果
		
		foreach ($record_data as $key => $value) {
			$sql .= " INSERT INTO sgmtb030(";
			$sql .= "kbnid,kbncd,ichiran,createdate";
			$sql .= ") VALUES (";
			foreach ($value as $key2 => $value2) {
				$sql .= "'" . $value2 . "',";
			}
			$sql .= "'" . date("Ymd") . "'";
			$sql .= ")";
			$sql .= " ;";
			
			log_message('debug',"\$sql = $sql");
			$query = $this->db->query($sql);
			$sql = "";
		}
		
/*
		// 引数をINSERT分の形に生成
		foreach ($record_data as $key => $value) {
			$name_string .= $key . ",";
			$value_string .= "'" . $value . "',";
		}
		// 登録日設定
		$name_string .= "'" . 'createdate' . "'";
		$value_string .= "'" . date("Ymd") . "'";
		// SQL作成
		$sql .= " INSERT INTO sgmtb030(";
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
*/
	}
	
	/**
	 * 更新
	 *
	 * @access	public
	 * @param	string  $record_data 更新データ
	 * @return	nothing
	 */
	function update_sgmtb030_data($record_data){
		try{
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$sql = ""; // sql_regcase文字列
			$sqle = "";
			$query = NULL; // SQL実行結果
			// SQL文作成
			foreach ($record_data as $value) {
				$sql .= " UPDATE sgmtb030 SET ";
				foreach ($value as $key => $up_data) {
					if($key === 'updatedate')
					{
						$sql .= $key." = '".date("Ymd")."'";
						$sql .= " WHERE kbnid = '".$value['kbnid']."' AND kbncd = '".$value['kbncd']."'";
					}else{
						$sql .= $key." = '".$up_data."',";
					}
				}
				$sql .= " ;";

				log_message('debug',"\$sql = $sql");
				$query = $this->db->query($sql);

				$sql = "";
				if($query){
					$result = TRUE;
				}else{
					$result = FALSE;
					// ロールバック
					$this->db->trans_rollback();
				}
			}

			// 全更新問題なし
			if($result)
			{
				// トランザクション終了(コミット)
				$this->db->trans_complete();
			}else{
				// ロールバック
				$this->db->trans_rollback();
			}
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			$result = FALSE;
			return $result;
		}
		
		return $result;
	}

	/**
	 * 論理削除
	 *
	 * @access	public
	 * @param	string  $record_data 更新データ
	 * @return	nothing
	 */
	function delete_sgmtb030_data($record_data){
		try{
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$sql = ""; // sql_regcase文字列
			$sqle = "";
			$query = NULL; // SQL実行結果
			// SQL文作成
			foreach ($record_data as $value) {
				$sql .= " UPDATE sgmtb030 SET ";
				foreach ($value as $key => $up_data) {
					if($key === 'deletedate')
					{
						$sql .= $key." = '".date("Ymd")."'";
						$sql .= " WHERE kbnid = '".$value['kbnid']."' AND kbncd = '".$value['kbncd']."'";
					}else{
						$sql .= $key." = '".$up_data."',";
					}
				}
				$sql .= " ;";

				log_message('debug',"\$sql = $sql");
				$query = $this->db->query($sql);

				$sql = "";
				if($query){
					$result = TRUE;
				}else{
					$result = FALSE;
					// ロールバック
					$this->db->trans_rollback();
				}
			}

			// 全更新問題なし
			if($result)
			{
				// トランザクション終了(コミット)
				$this->db->trans_complete();
			}else{
				// ロールバック
				$this->db->trans_rollback();
			}
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			$result = FALSE;
			return $result;
		}
		
		return $result;
	}

	/**
	 * 区分データの取得
	 *
	 * @access	public
	 * @param	string $id 区分ID
	 * @return	boolean $res = 区分情報 = 成功:FALSE = 失敗
	 */
	function get_kbn_data($id)
	{
		log_message('debug',"========== get_kbn_data start ==========");
		// 初期化
		$sql = ""; 
		$query = NULL; // SQL実行結果
		$res = FALSE;
		$sql = "SELECT kbncd,ichiran FROM sgmtb030 WHERE kbnid = ? ORDER BY kbncd";
		log_message('debug',"\$sql = $sql");
		$query = $this->db->query($sql,array($id));
		
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_kbn_data end ==========");
		return $res;
	}
	
	/**
	 * 区分データリストの取得
	 * 
	 * @access public
	 * @param  string $kbnid 区分ID      
	 * @return array  $res   区分データ
	 */
	function get_list_name($kbnid){
		log_message('debug',"========== get_list_name start ==========");
		// 初期化
		$sql = ""; 
		$query = NULL; // SQL実行結果
		$res = NULL;
		
		$sql .= " SELECT kbncd,ichiran FROM sgmtb030";
		$sql .= " WHERE ichiran <> ''";
		//$sql .= " AND kbnid = '{$kbnid}'";
		$sql .= " AND kbnid = ? ";
		$sql .= " ORDER BY kbnid,kbncd";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($kbnid));
		
		if($query->num_rows() > 0){
			$res = $query->result_array();
		}
		
		log_message('debug',"========== get_list_name end ==========");
		return $res;
	}
	/**
	 * 区分データの取得
	 * 
	 * @access public
	 * @param  string $kbnid 区分ID      
	 * @param  string $kbncd 区分コード      
	 * @return array  $res   区分データ
	 */
	function get_ichiran_name($kbnid,$kbncd){
		log_message('debug',"========== get_ichiran_name start ==========");
		// 初期化
		$sql = ""; 
		$query = NULL; // SQL実行結果
		$res = NULL;
		
		$sql .= " SELECT ichiran FROM sgmtb030";
		$sql .= " WHERE ichiran <> ''";
		//$sql .= " AND kbnid = '{$kbnid}'";
		//$sql .= " AND kbncd = '{$kbncd}'";
		$sql .= " AND kbnid = ?";
		$sql .= " AND kbncd = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($kbnid,$kbncd));
		
		if($query->num_rows() > 0){
			$res = $query->row_array();
		}
		
		log_message('debug',"========== get_ichiran_name end ==========");
		return $res['ichiran'];
	}
		
		

}

?>
