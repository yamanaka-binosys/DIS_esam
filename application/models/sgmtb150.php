<?php

class Sgmtb150 extends CI_Model {

    function __construct() {
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
    function insert_dbnri_item($data = NULL) {
        log_message('debug', "----- " . __METHOD__ . " start -----");
        foreach ($data as $key => $up) {
            log_message('debug', $key . "=" . $up);
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
        $query = $this->db->query($sql, array(
            $data['dbnricd'],
            $data['dbnrinm'],
            $data['itemcd'],
            $data['itemnm'],
            $data['createdate'],
            $data['updatedate'],
            $data['view_no']
                )
        );
        log_message('debug', "sql=" . $sql);
        // 結果判定
        if ($query) {
            $res = TRUE;
        } else {
            $res = FALSE;
        }

        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $res;
    }

    /**
     * 企画情報アイテムの更新
     *
     * @access	public
     * @param	string $data = 更新情報
     * @return	boolean $res = TRUE = 成功:FALSE = 失敗
     */
    function update_dbnri_item($data = NULL) {
        try {
            log_message('debug', "----- " . __METHOD__ . " start -----");
            foreach ($data as $key => $up) {
                log_message('debug', $key . "=" . $up);
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
            log_message('debug', "sql=" . $sql);
            // クエリ実行
            $query = $this->db->query($sql, array(
                $data['dbnricd'],
                $data['dbnrinm'],
                $data['itemcd'],
                $data['itemnm'],
                $data['createdate'],
                $data['updatedate'],
                $data['update_dbnricd'],
                $data['update_itemcd'])
            );
            // 結果判定
            if ($query) {
                $res = TRUE;
                // トランザクション終了(コミット)
                $this->db->trans_complete();
            } else {
                $res = FALSE;
                // ロールバック
                $this->db->trans_rollback();
            }

            log_message('debug', "----- " . __METHOD__ . " end -----");
            return $res;
        } catch (Exception $e) {
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
    function get_holiday_data($holiday_year = 0) {
        log_message('debug', "----- " . __METHOD__ . " start -----");
        // 初期化
        $res = NULL;
        //$limit = MY_PROJECT_MAX_VIEW;  //ページ表示件数
        //$getPage = ($page - 1) * $limit;
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
					*
				FROM
					SGMTB150
				WHERE
					deletedate IS NULL AND
                    extract(year from syukdate) = ?
				ORDER BY syukdate";

        //log_message('debug',"sql=".$sql);
        // クエリ実行
        $query = $this->db->query($sql, array($holiday_year));
        if ($query->num_rows() > 0) {
            $res = $query->result_array();
        } else {
            $res = FALSE;
        }
        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $res;
    }

    /**
     * 大分類名チェック
     *
     * @access	public
     * @param	int $page = 表示ページ№
     * @return	boolean $res = TRUE = 成功:FALSE = 失敗
     */
    function get_dname_check($name) {
        log_message('debug', "----- " . __METHOD__ . " start -----");
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
        log_message('debug', "sql=" . $sql);
        // クエリ実行
        $query = $this->db->query($sql, array($name));
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            $res = $result['dbnricd'];
        } else {
            $res = FALSE;
        }
        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $res;
    }

    /**
     * 大分類数の取得
     *
     * @access	public
     * @return	boolean $res = TRUE = 成功:FALSE = 失敗
     */
    function get_holiday_syukid_cnt() {
        log_message('debug', "----- " . __METHOD__ . " start -----");
        //log_message('debug',"shbn=".$shbn);
        // 初期化
        $res = NULL;
        // sql文作成
        $sql = "SELECT MAX(CAST (syukid AS int)) AS syukid_cnt FROM sgmtb150;";
        //log_message('debug',"sql=".$sql);
        // クエリ実行
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $res = $row->syukid_cnt;
        } else {
            $res = FALSE;
        }
        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $res;
    }

    /**
     * 祝日総数の取得
     *
     * @access	public
     * @return	boolean $res = TRUE = 成功:FALSE = 失敗
     */
    function get_holiday_all_cnt() {
        log_message('debug', "----- " . __METHOD__ . " start -----");
        // 初期化
        $res = NULL;
        // sql文作成
        $sql = "SELECT COUNT(syukid) AS all_cnt
				FROM SGMTB150;";

        log_message('debug', "sql=" . $sql);
        // クエリ実行
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $result = $query->row();
            $res = $result->all_cnt;
        } else {
            $res = FALSE;
        }

        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $res;
    }

    /**
     * 祝日の登録(all 削除、登録)
     *
     * @access	public
     * @param	string $data = 更新情報
     * @return	boolean $res = TRUE = 成功:FALSE = 失敗
     */
    public function insert_sgmtb150_data($data = NULL, $i_year = "0") {
        try {
            log_message('debug', "----- " . __METHOD__ . " start -----");

            // トランザクション開始
            $this->db->trans_begin();

            //////////////////////////////
            //  削除処理
            //
            
            $sql = "DELETE FROM SGMTB150 WHERE EXTRACT( year from syukdate) = ?";
            // クエリ実行
            $query = $this->db->query($sql, $i_year);
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

            // sql文作成 deletedateのみを除いてある
            $sql = "INSERT INTO SGMTB150
                    (syukid ,
                    syukdate ,
                    syukmemo ,
                    createdate ,
                    updatedate ,
                    syuksyaban)
                    values(?,?,?,?,?,?)";

            foreach ($data as $key => $d) {
                // クエリ実行
                $query = $this->db->query($sql, array(
                    $d['syukid'],
                    $d['syukdate'],
                    $d['syukmemo'],
                    $d['createdate'],
                    $d['updatedate'],
                    //$d['deletedate'] ←これは入ることがあるか？
                    $d['syaban']
                ));
                // 結果判定
                if (!$query) {
                    $res = FALSE;
                    // ロールバック
                    $this->db->trans_rollback();
                }
                $view_no++;
            }

            $res = TRUE;
            // トランザクション終了(コミット)
            $this->db->trans_complete();

            log_message('debug', "----- " . __METHOD__ . " end -----");
            return $res;
        } catch (Exception $e) {
            // ロールバック
            $this->db->trans_rollback();
            $res = FALSE;
            return $res;
        }
    }

}

?>
