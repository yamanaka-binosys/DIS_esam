<?php

class Srntb070 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * fileナンバーの最大値取得
	 *
	 * @access	public
	 * @return	string $res = 最大値 = 成功:FALSE = 失敗
	 */
	function max_filenum()
	{
		log_message('debug',"========== max_filenum start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT MAX(CAST((filenum) AS int)) FROM SRNTB070";
		// クエリ実行
		$query = $this->db->query($sql);

		// 結果判定
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
			$res = $result['max'] + 1;
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== max_filenum end ==========");	
		return $res;
	}
	
	
	/**
	 * 添付ファイル情報の取得
	 *
	 * @access	public
	 * @return	string $res = 最大値 = 成功:FALSE = 失敗
	 */
	function select_file()
	{
		log_message('debug',"========== max_filenum start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT * FROM SRNTB070 WHERE filenum = ? and delflg='0' ";
		// クエリ実行
		$query = $this->db->query($sql);

		// 結果判定
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();

		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== max_filenum end ==========");	
		return $res;
	}
	/**
	 * 添付ファイル情報の取得(tempfile)
	 *
	 * @access	public
	 * @return	string $res = 最大値 = 成功:FALSE = 失敗
	 */
	function search_file($ymd,$shbn)
	{
		log_message('debug',"========== search_file start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT * FROM SRNTB070 WHERE ymd = ? and shbn = ? and delflg='0' ";
		// クエリ実行
		$query = $this->db->query($sql,array($ymd,$shbn));

		// 結果判定
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();

		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== search_file end ==========");	
		return $res;
	}
	
	
	/**
	 * 添付ファイルインサート
	 *
	 * @access	public
	 * @return	string $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_file($data)
	{	
		try{
			log_message('debug',"========== insert_file start ==========");
			
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$res = NULL;	
			// sql文作成
			
			$insert_sql = "INSERT INTO SRNTB070
					(filenum , ymd ,shbn ,tempfile,uploadtime,delflg)
					VALUES
					(? ,? ,? ,?,?,'0');";
				// クエリ実行
				// POSTデータ、日付、情報№、使用テーブル
				$query = $this->db->query($insert_sql, array($data['filenum'],$data['ymd'],$data['shbn'],$data['tempfile'],$data['uploadtime']));
				// 結果判定
				if($query)
				{
					$res = TRUE;
				}else{
					$res = FALSE;
					
				}
		//		log_message('debug',"sql=".$sql);
			
			if($res)
			{
				// トランザクション終了(コミット)
				$this->db->trans_complete();
			}
			else
			{
				// ロールバック
				$this->db->trans_rollback();
			}
			log_message('debug',"========== insert_file end ==========");
			return $res;
		} catch(Exception $e) {
			log_message('debug',"========== insert_file catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== insert_file catch trans_rollback end ==========");
			return FALSE;
		}
	}
	
	/**
	 * 添付ファイルアップデート
	 *
	 * @access	public
	 * @return	string $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_file($data)
	{
	//(filenum , ymd ,shbn ,tempfile)
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= " UPDATE srntb070";
		$sql .= " SET tempfile = ? ,";
		$sql .= " uploadtime = ?, ";
		$sql .= " delflg = '0' ";
		$sql .= " WHERE filenum =?";
		$sql .= " AND   ymd = ?";
		$sql .= " AND   shbn = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($data['tempfile'],$data['uploadtime'],$data['filenum'],$data['ymd'],$data['shbn']));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/**
	 * 添付ファイルデリート
	 *
	 * @access	public
	 * @return	string $res = TRUE = 成功:FALSE = 失敗
	 */
	function delete_file($data)
	{
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "UPDATE srntb070";
		$sql .= " SET delflg = '1' ";
		$sql .= " WHERE filenum = ?";
		$sql .= " ;";
		
		log_message('debug',"del_file_sql=".$sql);
		// SQL実行
		$query = $this->db->query($sql,array($data['filenum']));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
}

?>
