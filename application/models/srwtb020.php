<?php

class Srwtb020 extends CI_Model {
	
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
		$sql = "SELECT count(shbn) FROM srwtb020 WHERE shbn = ?";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
		}
		
		return $row['count'];
	}
	
	/**
	 * ユーザー別事前検索情報(確認者)登録取得
	 * 
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	array or NULL
	 */
	function get_r_shbn($shbn=NULL)
	{
       	log_message('debug'," =========== " . __METHOD__ . " start ========== ");

        // 戻り値初期化
        $ret = NULL;

		// sql文作成
		// 登録件数
		$sql = "SELECT kshbnnn FROM srwtb020 WHERE shbn = ? and set_flg = '1'";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$ret = $query->result_array();
		}
		
       	log_message('debug'," ===== \$ret = " . serialize($ret) . " ===== ");
        log_message('debug'," =========== " . __METHOD__ . "  end ========== ");

		return $ret;
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
		$sql = "SELECT shbn,kshbnnn,kbshonn,kgrpnn,honbucd,bucd,kacd,edbn FROM srwtb020 WHERE shbn = ?";
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
		$res = NULL;
		// sql文作成
		$sql = "SELECT kshbnnn,edbn,set_flg 
				FROM srwtb020 
				WHERE shbn = ? 
				AND kshbnnn IS NOT NULL
				ORDER BY edbn";
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
		$res = NULL;
		// sql文作成
		$sql = "SELECT honbucd,bucd,kacd,edbn,set_flg 
				FROM srwtb020 
				WHERE shbn = ? and kacd = 'XXXXX'
				AND honbucd IS NOT NULL
				ORDER BY edbn";
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
	function get_user_ka_data($shbn)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT honbucd,bucd,kacd,edbn,set_flg 
				FROM srwtb020 
				WHERE shbn = ? and kacd <> 'XXXXX'
				AND honbucd IS NOT NULL
				ORDER BY edbn";
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
		$res = NULL;
		// sql文作成
		$sql = "SELECT DISTINCT kgrpnn,edbn,set_flg 
				FROM srwtb020 
				WHERE shbn = ? 
				AND kgrpnn IS NOT NULL
				ORDER BY edbn";
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
	function insert_checker_data($shbn, $edbn, $kshbn)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		//var_dump($kshbn);
		// 初期化
		$res = FALSE;
		$edbn++;
		$edbn = sprintf('%02d', $edbn);
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
	function insert_busyo_data($shbn, $edbn, $code)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		// 初期化
		$res = FALSE;
		$edbn++;
		$edbn = sprintf('%02d', $edbn);
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
	 * 部署コード登録
	 * 
	 * @access	public
	 * @param	string $shbn = 登録実行したユーザの社番
	 * @param	string $edbn = 枝番
	 * @param	string $code = 登録する部署コード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_ka_data($shbn, $edbn, $code)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		// 初期化
		$res = FALSE;
		$edbn++;
		$edbn = sprintf('%02d', $edbn);
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
	function insert_group_data($shbn, $edbn, $kgrpnn)
	{
		log_message('debug',"========== insert_checker_data start ==========");
		// 初期化
		$res = FALSE;
		$edbn++;
		$edbn = sprintf('%02d', $edbn);
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
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $edbn = 更新する枝番
	 * @param	string $kshbn = 更新する確認者の社番
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_checker_data($shbn, $edbn, $kshbn)
	{
		try
		{
			log_message('debug',"========== update_checker_data start ==========");
			log_message('debug',"========== update_checker_data trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== update_checker_data trans_start end ==========");
			// 初期化
			$res = FALSE;
			log_message('debug',"shbn=".$shbn);
			log_message('debug',"edbn=".$edbn);
			log_message('debug',"kshbn=".$kshbn);
			
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				kshbnnn = ?
				WHERE shbn = ? AND edbn = ?
				";
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$kshbn,
						$shbn,
						$edbn
						)
					);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== update_checker_data trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== update_checker_data trans_complete end ==========");
			}else{
				log_message('debug',"========== update_checker_data trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== update_checker_data trans_rollback end ==========");
			}
			log_message('debug',"========== update_checker_data end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== update_checker_data catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== update_checker_data catch trans_rollback end ==========");
			return $res;
		}
	}
	
	/**
	 * ユーザー別検索情報(部署)更新
	 * 
	 * @access	public
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $edbn = 更新する枝番
	 * @param	string $code = 更新する部署コード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_busyo_data($shbn, $edbn, $code)
	{
		try
		{
			log_message('debug',"========== " . __METHOD__ . " start ==========");
			log_message('debug',"========== " . __METHOD__ . " trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== " . __METHOD__ . " trans_start end ==========");
			// 初期化
			$res = FALSE;
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				honbucd = ?,
				bucd = ?,
				kacd = ?
				WHERE shbn = ? AND edbn = ?
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$code['honbu'],
						$code['bu'],
						$code['ka'],
						$shbn,
						$edbn
						)
					);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== " . __METHOD__ . " trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== " . __METHOD__ . " trans_complete end ==========");
			}else{
				log_message('debug',"========== " . __METHOD__ . " trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== " . __METHOD__ . " trans_rollback end ==========");
			}
			log_message('debug',"========== " . __METHOD__ . " end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback end ==========");
			return $res;
		}
	}
		/**
	 * ユーザー別検索情報(部署)更新
	 * 
	 * @access	public
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $edbn = 更新する枝番
	 * @param	string $code = 更新する部署コード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_ka_data($shbn, $edbn, $code)
	{
		try
		{
			log_message('debug',"========== " . __METHOD__ . " start ==========");
			log_message('debug',"========== " . __METHOD__ . " trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== " . __METHOD__ . " trans_start end ==========");
			// 初期化
			$res = FALSE;
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				honbucd = ?,
				bucd = ?,
				kacd = ?
				WHERE shbn = ? AND edbn = ?
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$code['honbu'],
						$code['bu'],
						$code['ka'],
						$shbn,
						$edbn
						)
					);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== " . __METHOD__ . " trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== " . __METHOD__ . " trans_complete end ==========");
			}else{
				log_message('debug',"========== " . __METHOD__ . " trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== " . __METHOD__ . " trans_rollback end ==========");
			}
			log_message('debug',"========== " . __METHOD__ . " end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback end ==========");
			return $res;
		}
	}
	/**
	 * 確認者フラグ初期化
	 * 
	 * @access	public
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $edbn = 更新する枝番
	 * @param	string $code = 更新する部署コード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_clear_checkar_data($shbn)
	{
		try
		{
			log_message('debug',"========== " . __METHOD__ . " start ==========");
			log_message('debug',"========== " . __METHOD__ . " trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== " . __METHOD__ . " trans_start end ==========");
			// 初期化
			$res = FALSE;
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				checker_flg = '0', set_flg = '0' 
				WHERE shbn = ?
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,$shbn
					);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== " . __METHOD__ . " trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== " . __METHOD__ . " trans_complete end ==========");
			}else{
				log_message('debug',"========== " . __METHOD__ . " trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== " . __METHOD__ . " trans_rollback end ==========");
			}
			log_message('debug',"========== " . __METHOD__ . " end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback end ==========");
			return $res;
		}
	}
	
	/**
	 * 確認者フラグ更新
	 * 
	 * @access	public
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $edbn = 更新する枝番
	 * @param	string $code = 更新する部署コード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_checkar_data($edbn,$shbn)
	{
		try
		{
			log_message('debug',"========== " . __METHOD__ . " start ==========");
			log_message('debug',"========== " . __METHOD__ . " trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== " . __METHOD__ . " trans_start end ==========");
			// 初期化
			$res = FALSE;
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				checker_flg = '1'
				WHERE  edbn = ? 
				 AND shbn = ? 
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			
			$query = $this->db->query($sql,array($edbn,$shbn));
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== " . __METHOD__ . " trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== " . __METHOD__ . " trans_complete end ==========");
			}else{
				log_message('debug',"========== " . __METHOD__ . " trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== " . __METHOD__ . " trans_rollback end ==========");
			}
			log_message('debug',"========== " . __METHOD__ . " end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== " . __METHOD__ . " catch trans_rollback end ==========");
			return $res;
		}
	}
	/**
	 * 確認者フラグ更新
	 * 
	 * @access	public
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $set_shbn = チェックしたデータ
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_set_flg_data($shbn, $set_shbn)
	{
		try
		{
			log_message('debug',"========== " . __METHOD__ . " start ==========");
            log_message('debug',"===== " . __LINE__ . ":\$set_shbn = " . $set_shbn . " =====");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== " . __METHOD__ . " trans_start end ==========");
			// 初期化
			$res = FALSE;

            
            foreach($set_shbn as $key => $bumon){
                foreach($bumon as $value){
                    switch ($key){
                        case 'kshbn':
                            // 確認者用 sql文作成
                            $sql = "
                                UPDATE srwtb020 SET 
                                set_flg = '1'
                                WHERE  kshbnnn = '" . $value . "' AND shbn = '" . $shbn . "';";

                            log_message('debug', __LINE__ . " sql=".$sql);

                            // クエリ実行
                            $query = $this->db->query($sql);
                            break;
                        
                        case 'bucd':
                            // 部署（部）用sql文作成
                            $honbucd = "";
                            $bucd = "";
                            $kacd = "";
                            if(strlen($value) > (MY_CODE_LENGTH * 2)){
                                $honbucd = substr($value,0,MY_CODE_LENGTH);
                                $bucd = substr($value,MY_CODE_LENGTH,MY_CODE_LENGTH);
                                $kacd = substr($value,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
                            }else if(strlen($bucd) > MY_CODE_LENGTH){
                                $honbucd = substr($value,0,MY_CODE_LENGTH);
                                $bucd = substr($value,MY_CODE_LENGTH,MY_CODE_LENGTH);
                                $kacd = MY_DB_BU_ESC;
                            }else{
                                $honbucd = substr($value,0,MY_CODE_LENGTH);
                                $bucd = MY_DB_BU_ESC;
                                $kacd = MY_DB_BU_ESC;
                            }

                            $sql = "
                                UPDATE srwtb020 SET 
                                set_flg = '1'
                                WHERE  honbucd = '" . $honbucd . "' AND bucd = '" . $bucd . "' AND kacd = '" . $kacd . "' AND shbn = '" . $shbn . "';";

                            log_message('debug', __LINE__ . " sql=".$sql);

                            // クエリ実行
                            $query = $this->db->query($sql);
                            break;
                            
                        case 'kacd':
                            // ユニット（課）用sql文作成
                            $honbucd = "";
                            $bucd = "";
                            $kacd = "";
                            if(strlen($value) > (MY_CODE_LENGTH * 2)){
                                $honbucd = substr($value,0,MY_CODE_LENGTH);
                                $bucd = substr($value,MY_CODE_LENGTH,MY_CODE_LENGTH);
                                $kacd = substr($value,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
                            }else if(strlen($bucd) > MY_CODE_LENGTH){
                                $honbucd = substr($value,0,MY_CODE_LENGTH);
                                $bucd = substr($value,MY_CODE_LENGTH,MY_CODE_LENGTH);
                                $kacd = MY_DB_BU_ESC;
                            }else{
                                $honbucd = substr($value,0,MY_CODE_LENGTH);
                                $bucd = MY_DB_BU_ESC;
                                $kacd = MY_DB_BU_ESC;
                            }

                            $sql = "
                                UPDATE srwtb020 SET 
                                set_flg = '1'
                                WHERE  honbucd = '" . $honbucd . "' AND bucd = '" . $bucd . "' AND kacd = '" . $kacd . "' AND shbn = '" . $shbn . "';";

                            log_message('debug', __LINE__ . " sql=".$sql);

                            // クエリ実行
                            $query = $this->db->query($sql);
                            break;
                            
                        case 'grpcd':
                            // グループ用sql文作成
                            $sql = "
                                UPDATE srwtb020 SET 
                                set_flg = '1'
                                WHERE  kgrpnn = '" . $value . "' AND shbn = '" . $shbn . "';";

                            log_message('debug', __LINE__ . " sql=".$sql);

                            // クエリ実行
                            $query = $this->db->query($sql);
                            break;
                    }
                }
            }
            
            // 成功
            // トランザクション終了(コミット)
            $this->db->trans_complete();
            $res = TRUE;
            log_message('debug',"========== " . __METHOD__ . " trans_complete end ==========");
			
            return $res;
            
		}catch(Exception $e){
			
            // ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== " . __METHOD__ . ":" . __LINE__ . " catch trans_rollback end ==========");
			
            return $res;
		}
	}
	/**
	 * ユーザー別検索情報(グループ)更新
	 * 
	 * @access	public
	 * @param	string $shbn = 更新実行したユーザの社番
	 * @param	string $edbn = 更新する枝番
	 * @param	string $code = 更新するグループコード
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_group_data($shbn, $edbn, $kgrpnn)
	{
		try
		{
			log_message('debug',"========== update_group_data start ==========");
			log_message('debug',"========== update_group_data trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== update_group_data trans_start end ==========");
			// 初期化
			$res = FALSE;
			// sql文作成
			$sql = "
				UPDATE srwtb020 SET 
				kgrpnn = ?
				WHERE shbn = ? AND edbn = ?
				";

			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$kgrpnn,
						$shbn,
						$edbn
						)
					);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($query)
			{
				log_message('debug',"========== update_group_data trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== update_group_data trans_complete end ==========");
			}else{
				log_message('debug',"========== update_group_data trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== update_group_data trans_rollback end ==========");
			}
			log_message('debug',"========== update_group_data end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== update_group_data catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== update_group_data catch trans_rollback end ==========");
			return $res;
		}
	}
	}

?>
