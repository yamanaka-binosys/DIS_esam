<?php

class Srwtb022 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * ユーザー別検索情報(確認者)登録件数取得
	 * 
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	array  $edbn
	 */
	function get_edbn_data($shbn=NULL)
	{
		$row = '01';
		// 初期化
		$user_search_data = NULL;
		// sql文作成
		// 登録件数
		$sql = "SELECT count(*) FROM srwtb020 WHERE shbn = ?";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
		}
		
		return $row['count'];
	}
	
	/**
	 * ユーザー別検索情報(確認者)取得
	 * 
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	array  $user_search_data
	 */
	function get_user_search_data($shbn)
	{
		// 初期化
		$result_data = NULL;
		$n = 0;
		// sql文作成
		$sql = "SELECT * FROM srwtb020 WHERE shbn = ?";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		return $res;
	}

	/**
	 * ユーザー別検索情報(確認者)取得
	 * 
	 * @access	public
	 * @param  string $shbn = 社番
	 * @return array  $user_shbn_data
	 */
	function get_user_shbn_data($shbn)
	{
		// 初期化
		$result_data = NULL;
		// sql文作成
		$sql = "SELECT kshbnnn FROM srwtb020 WHERE shbn = ? AND kshbnnn IS NOT NULL";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		return $res;
	}
	
	/**
	 * 確認者部署取得
	 * 
	 * @access	public
	 * @param  string $shbn = 社番
	 * @return array  $res 部署情報
	 */
	function get_user_busyo_data($shbn)
	{
		// 初期化
		$res = FALSE;
		// sql文作成
		$sql = "SELECT honbucd,bucd,kacd FROM srwtb020 WHERE shbn = ? AND honbucd IS NOT NULL";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		return $res;
	}

	/**
	 * 確認者グループ取得
	 * 
	 * @access	public
	 * @param  string $shbn = 社番
	 * @return array  $res グループ情報
	 */
	function get_user_group_data($shbn)
	{
		// 初期化
		$res = FALSE;
		// sql文作成
		$sql = "SELECT DISTINCT kgrpnn FROM srwtb020 WHERE shbn = ? AND kgrpnn IS NOT NULL";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		return $res;
	}

	/**
	 * ユーザー別検索情報(確認者)登録
	 * 
	 * @access	public
	 * @param	string $post = 登録する情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_user_search_data($post=NULL)
	{
		log_message('debug',"========== insert_user_search_data start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "INSERT INTO srwtb020 
			(shbn,
			edbn,
			kshbnnn,
			kbshonn,
			kgrpnn
			) 
			VALUES(?,?,?,?,?)";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$post['shbn'],
					$post['edbn'],
					$post['kshbnnn'],
					$post['kbshonn'],
					$post['kgrpnn']
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== insert_user end ==========");
		return $res;
	}

	/**
	 * 確認者(社番)登録
	 * 
	 * @access	public
	 * @param	string $shbn = 登録実行したユーザの社番
	 * @param	string $edbn = 枝番
	 * @param	string $kshbn = 登録する確認者の社番
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_checker_data($shbn, $edbn,$kshbn)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		//var_dump($kshbn);
		// 初期化
		$res = NULL;
		if($edbn < 10)
		{
			$edbn++;
			$edbn = '0'.$edbn;
		}
		// sql文作成
		$sql = "INSERT INTO srwtb020 
			(shbn,
			edbn,
			kshbnnn
			) 
			VALUES(?,?,?)";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$shbn,
					$edbn,
					$kshbn
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== insert_checker_data end ==========");
		return $res;
	}

	/**
	 * 部署コード登録
	 * 
	 * @access	public
	 * @param	string $shbn = 登録実行したユーザの社番
	 * @param	string $edbn = 枝番
	 * @param	string $code = 登録する部署コード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_busyo_data($shbn, $edbn,$code)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		// 初期化
		$res = NULL;
		if($edbn < 10)
		{
			$edbn++;
			$edbn = '0'.$edbn;
		}
		// sql文作成
		$sql = "INSERT INTO srwtb020 
			(shbn,
			edbn,
			honbucd,
			bucd,
			kacd
			) 
			VALUES(?,?,?,?,?)";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$shbn,
					$edbn,
					$code['honbu'],
					$code['bu'],
					$code['ka']
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== insert_checker_data end ==========");
		return $res;
	}

	/**
	 * グループコード登録
	 * 
	 * @access	public
	 * @param	string $shbn = 登録実行したユーザの社番
	 * @param	string $edbn = 枝番
	 * @param	string $kgrpnn = 登録するグループコード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_group_data($shbn, $edbn,$kgrpnn)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		// 初期化
		$res = NULL;
		if($edbn < 10)
		{
			$edbn++;
			$edbn = '0'.$edbn;
		}
		// sql文作成
		$sql = "INSERT INTO srwtb020 
			(shbn,
			edbn,
			kgrpnn
			) 
			VALUES(?,?,?)";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$shbn,
					$edbn,
					$kgrpnn
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== insert_checker_data end ==========");
		return $res;
	}

	/**
	 * ユーザー別検索情報(確認者)更新
	 * 
	 * @access	public
	 * @param	string $post = 更新情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_user_search_data($post=NULL)
	{
		try
		{
			log_message('debug',"========== update_user start ==========");
			log_message('debug',"========== update trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== update trans_start end ==========");
			// 初期化
			$res = NULL;
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				kshbnnn = ?,
				kbshonn = ?,
				kgrpnn = ?
				WHERE shbn = ? AND edbn = ?
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$post['kshbnnn'],
						$post['kbshonn'],
						$post['kgrpnn'],
						$post['shbn'],
						$post['edbn']
						)
					);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($this->db->affected_rows())
			{
				log_message('debug',"========== update trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== update trans_complete end ==========");
			}else{
				log_message('debug',"========== update trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== update trans_rollback end ==========");
			}
			log_message('debug',"========== update_user end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== update catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== update catch trans_rollback end ==========");
			return $res;
		}
	}
	
}

?>
