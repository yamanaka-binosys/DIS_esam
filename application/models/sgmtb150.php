<?php

class Sgmtb150 extends CI_Model {
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * ユーザ情報の登録
	 *
	 * @access	public
	 * @param	string $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_dbnri_item($data = NULL)
	{
		log_message('debug',"========== insert_project_data start ==========");
			foreach($data as $key => $up)
			{
				log_message('debug',$key."=" .$up);
			}

		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "INSERT INTO SGMTB080
					(DbnriCd ,
					 DbnriNm ,
					 ItemCd ,
					 ItemNm ,
					 CreateDate ,
					 UpdateDate ,
					 view_no)
				values(?,?,?,?,?,?,?)";
		// クエリ実行
		$query = $this->db->query($sql,array(
											$data['dbnricd'],
											$data['dbnrinm'],
											$data['itemcd'],
											$data['itemnm'],
											$data['createdate'],
											$data['updatedate'],
											$data['view_no']
											)
								);
		log_message('debug',"sql=".$sql);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}

		log_message('debug',"========== insert_project_data end ==========");
		return $res;
	}

	/**
	 * 企画情報アイテムの更新
	 *
	 * @access	public
	 * @param	string $data = 更新情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_dbnri_item($data = NULL)
	{
		try
		{
			log_message('debug',"========== update_dbnri_item start ==========");
			foreach($data as $key => $up)
			{
				log_message('debug',$key."=" .$up);
			}
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$res = NULL;
			// sql文作成
			$sql = "UPDATE SGMTB080
					SET
						DbnriCd = ?,
						DbnriNm = ?,
						ItemCd = ?,
						ItemNm = ?,
						CreateDate = ?,
						UpdateDate = ?
					WHERE
						DbnriCd = ? AND ItemCd = ?
					";
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query($sql,array(
												$data['dbnricd'],
												$data['dbnrinm'],
												$data['itemcd'],
												$data['itemnm'],
												$data['createdate'],
												$data['updatedate'],
												$data['update_dbnricd'],
												$data['update_itemcd']
												)
									);
			// 結果判定
			if($query)
			{
				$res = TRUE;
				// トランザクション終了(コミット)
				$this->db->trans_complete();
			}else{
				$res = FALSE;
				// ロールバック
				$this->db->trans_rollback();
			}

			log_message('debug',"========== update_dbnri_item end ==========");
			return $res;
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			$res = FALSE;
			return $res;
		}
	}

	/**
	 * 企画情報相手の取得
	 *
	 * @access	public
	 * @param	int $page = 表示ページ№
	 * @param	int $add = 追加したデータ数
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_holiday_data($page = 1,$add = 0,$add_end = 0)
	{
		log_message('debug',"========== get_project_data start ==========");
		// 初期化
		$res = NULL;
		$limit = MY_PROJECT_MAX_VIEW;  //ページ表示件数

		$getPage = ($page - 1) * $limit;
		// sql文作成
		/*
		$sql = "SELECT
					DbnriCd ,
					DbnriNm ,
					ItemCd ,
					ItemNm ,
					CreateDate ,
					UpdateDate ,
					DeleteDate ,
					view_no
				FROM
					SGMTB080
				WHERE
					deletedate IS NULL
				ORDER BY view_no,DbnriCd,ItemCd
				LIMIT $limit + ".$add." OFFSET ".$getPage ." +".$add_end;
		*/
		$sql = "SELECT
					syukid,
                    syukdate,
                    syukmemo,
                    createdate,
                    updatedate,
                    deletedate,
                    syuksyaban
				FROM
					SGMTB150
				WHERE
					deletedate IS NULL AND
                    extract(year from syukdate) = extract(year from now())
				ORDER BY syukdate 
				LIMIT $limit + ? OFFSET ? + ?";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($add,$getPage,$add_end));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_project_data end ==========");
		return $res;
	}

	/**
	 * 大分類名チェック
	 *
	 * @access	public
	 * @param	int $page = 表示ページ№
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_dname_check($name)
	{
		log_message('debug',"========== get_dname_check start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT
					dbnricd
				FROM
					SGMTB080
				WHERE
					dbnrinm = ?
				";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($name));
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
			$res = $result['dbnricd'];
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_dname_check end ==========");
		return $res;
	}

	/**
	 * アイテム名チェック
	 *
	 * @access	public
	 * @param	int $page = 表示ページ№
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_item_check($name)
	{
		log_message('debug',"========== get_dname_check start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT
					itemcd
				FROM
					SGMTB080
				WHERE
					itemnm = ?
				";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($name));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_dname_check end ==========");
		return $res;
	}

	/**
	 * 大分類数の取得
	 *
	 * @access	public
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_project_dbnri_cnt()
	{
		log_message('debug',"========== get_project_dbnri_cnt start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		// sql文作成
//		$sql = "SELECT MAX(CAST (dbnricd as int)) FROM sgmtb080;";
		$sql = "SELECT MAX(CAST (dbnricd AS int)) AS dbnri_cnt FROM sgmtb080;";
		//$sql = "SELECT COUNT(distinct dbnrinm) as dbnri_cnt FROM sgmtb080";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_project_dbnri_cnt end ==========");
		return $res;
	}

	/**
	 * アイテム数の取得
	 *
	 * @access	public
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_project_item_cnt($name)
	{
		log_message('debug',"========== get_project_item_cnt start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT MAX(CAST (itemcd as int)) FROM sgmtb080 WHERE dbnrinm = ?";
//		$sql = "SELECT COUNT(distinct itemnm) as item_cnt FROM sgmtb080 WHERE dbnrinm = ?";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($name));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_project_item_cnt end ==========");
		return $res;
	}
		/**
	 * アイテム数の取得
	 *
	 * @access	public
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_project_item_cnt_cd($cd)
	{
		log_message('debug',"========== get_project_item_cnt start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT MAX(CAST (itemcd as int)) AS item_cnt FROM sgmtb080 WHERE dbnricd = ?";
//		$sql = "SELECT COUNT(distinct itemnm) as item_cnt FROM sgmtb080 WHERE dbnrinm = ?";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($cd));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_project_item_cnt end ==========");
		return $res;
	}
	/**
	 * 表示番号の取得
	 *
	 * @access	public
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_project_view_no($name)
	{
		log_message('debug',"========== get_project_view_no start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT MAX(CAST (view_no as int)) FROM sgmtb080";
//		$sql = "SELECT COUNT(distinct itemnm) as item_cnt FROM sgmtb080 WHERE dbnrinm = ?";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($name));
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
			$res = $result['max'];
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_project_view_no end ==========");
		return $res;
	}


	/**
	 * 企画情報アイテム追加・更新判定(名前)
	 *
	 * @access	public
	 * @param	string $dbnrinm = 大分類名
	 * @param	string $itemnm = アイテム名
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_project_name_check($dbnrinm = NULL,$itemnm = NULL)
	{
		log_message('debug',"========== get_project_name_check start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT dbnri.dbcnt,item.icnt
				FROM (SELECT COUNT(dbnrinm) AS dbcnt
						FROM SGMTB080
						WHERE dbnrinm = ?
						) dbnri,
					(SELECT COUNT(itemnm) AS icnt
						FROM SGMTB080
						WHERE dbnrinm = ?
						AND itemnm = ?
						) item";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($dbnrinm,$dbnrinm,$itemnm));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}

		log_message('debug',"========== get_project_name_check end ==========");
		return $res;
	}

		/**
	 * 企画情報アイテム追加・更新判定(コード)
	 *
	 * @access	public
	 * @param	string $dbnrinm = 大分類名
	 * @param	string $itemnm = アイテム名
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_project_code_check($dbnricd = NULL,$itemcd = NULL)
	{
		log_message('debug',"========== get_project_code_check start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT dbnri.dbcnt,item.icnt
				FROM (SELECT COUNT(dbnricd) AS dbcnt
						FROM SGMTB080
						WHERE dbnricd = ?
						) dbnri,
					(SELECT COUNT(itemcd) AS icnt
						FROM SGMTB080
						WHERE dbnricd = ?
						AND itemcd = ?
						) item";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($dbnricd,$dbnricd,$itemcd));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}

		log_message('debug',"========== get_project_code_check end ==========");
		return $res;
	}

	/**
	 * 祝日総数の取得
	 *
	 * @access	public
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_holiday_all_cnt()
	{
		log_message('debug',"========== get_holidayt_all_cnt start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT COUNT(syukid) AS all_cnt
				FROM SGMTB150;";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$result = $query->row();
			$res = $result->all_cnt;
		}else{
			$res = FALSE;
		}

		log_message('debug',"========== get_holiday_all_cnt end ==========");
		return $res;
	}

	/**
	 * 企画情報アイテムの削除
	 *
	 * @access	public
	 * @param  string  $view_no 削除するデータ番号
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function delete_project_item($view_no)
	{
		try
		{
			log_message('debug',"========== delete_user start ==========");
			log_message('debug',"========== delete trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== delete trans_start end ==========");
			// 初期化
			$res = NULL;
			$date = date("Ymd");
			// sql文作成
			$sql = "UPDATE sgmtb080
					SET
						deletedate = ?
					WHERE
						view_no = ?;";
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query($sql,array($date,$view_no));
			// 結果判定
			if($query)
			{
				// 成功
				log_message('debug',"========== delete trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				$res = TRUE;
				log_message('debug',"========== delete trans_complete end ==========");
			}else{
				// 失敗
				log_message('debug',"========== delete trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				$res = FALSE;
				log_message('debug',"========== delete trans_rollback end ==========");
			}
			log_message('debug',"========== delete_user end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== delete catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== delete catch trans_rollback end ==========");
			return FALSE;
		}
	}

  /**
   * 企画情報の取得
   *
   * @access public
   * @param  string $select = 取得フィールド
   * @param string $where = 条件
   * @return mix
  */
	function get_kikaku_item_data($select="*", $where="", $order_by="", $offset="")
	{
    log_message('debug',"========== get_kikaku_item_data start ==========");
    $limit = MY_PROJECT_MAX_VIEW;  //ページ表示件数

    // sql文作成
    $sql =  "SELECT $select ";
    $sql .= "FROM SGMTB080 ";
    if($where)    $sql .= "WHERE $where ";
    if($order_by) $sql .= "ORDER BY $order_by ";
    if($offset) $sql .= "LIMIT $limit OFFSET ".$offset;
    log_message('debug',"sql=".$sql);

    // クエリ実行
    $query = $this->db->query($sql);
    if($query->num_rows() > 0) {
      $res = $query->result_array();
    }else{
      $res = FALSE;
    }
    log_message('debug',"========== get_kikaku_item_data end ==========");
    return $res;
  }

  /**
   * 企画情報アイテムの登録(all 削除、登録)
   *
   * @access	public
   * @param	string $data = 更新情報
   * @return	boolean $res = TRUE = 成功:FALSE = 失敗
  */
  public function insert_sgmtb080_data($data = NULL)
  {
    try
    {
      log_message('debug',"========== insert_sgmtb080_data start ==========");

      // トランザクション開始
      $this->db->trans_begin();

      //////////////////////////////
      //  削除処理
      //
      $sql = "DELETE FROM SGMTB080";
      log_message('debug',"sql=".$sql);
      // クエリ実行
      $query = $this->db->query($sql);
      /*
      if($query)
      {
        $res = TRUE;
        // トランザクション終了(コミット)
        $this->db->trans_complete();
      }else{
        $res = FALSE;
        // ロールバック
        $this->db->trans_rollback();
      }
      */

      //////////////////////////////
      //  登録処理
      //
      // 初期化
      $res = NULL;
      $view_no = 1;

      // sql文作成
      $sql = "INSERT INTO SGMTB080
        (DbnriCd ,
         DbnriNm ,
         ItemCd ,
         ItemNm ,
         CreateDate ,
         UpdateDate ,
         view_no)
      values(?,?,?,?,?,?,?)";

      foreach($data as $key => $d) {
        // クエリ実行
        $query = $this->db->query($sql,array(
          $d['dbnricd'],
          $d['dbnrinm'],
          $d['itemcd'],
          $d['itemnm'],
          $d['createdate'],
          $d['updatedate'],
          $view_no
        ));
        // 結果判定
        if(!$query) {
          $res = FALSE;
          // ロールバック
          $this->db->trans_rollback();
        }
        $view_no++;
      }

      $res = TRUE;
      // トランザクション終了(コミット)
      $this->db->trans_complete();

      log_message('debug',"========== insert_sgmtb080_data end ==========");
      return $res;
    }catch(Exception $e){
      // ロールバック
      $this->db->trans_rollback();
      $res = FALSE;
      return $res;
    }
  }

	function get_all_item_name(){
		log_message('debug',"========== sgmtb080 get_all_item_name start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値

		// SQL文作成
		$sql .= " SELECT dbnricd,itemcd,itemnm FROM sgmtb080";
		$sql .= " WHERE deletedate is null";
		$sql .= " ORDER BY dbnricd,itemcd";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}

		log_message('debug',"========== sgmtb080 get_all_item_name end ==========");
		return $result_data;
	}

}

?>
