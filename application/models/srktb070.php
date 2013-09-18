<?php
/**
* 企画獲得情報の登録
*
* @access	public
* @param	string $post = 登録情報
* @return	boolean $res = TRUE = 成功:FALSE = 失敗
*/
class Srktb070 extends CI_Model {

  function __construct()
  {
  	// Model クラスのコンストラクタを呼び出す
  	parent::__construct();
  }

  /**
   * 企画情報の取得
   *
   * @access public
   * @param  string $select = 取得フィールド
   * @param string $where = 条件
   * @return mix
   */
  function get_kikaku_kakutoku_data($select="*", $where="", $order_by="", $offset="")
  {
    log_message('debug',"========== get_kikaku_kakutoku_data start ==========");
    $limit = MY_PROJECT_MAX_VIEW;  //ページ表示件数

    if( $select == "*" || $select == "" ) {
      $select = "shbn,year,month,aiteskcd,dbnricd,itemcd,kbn,edlp_kaisiymd,edlp_shryoymd,edlp_tenponum,edlp_baika,end_kaisiymd,end_shryoymd,end_tenponum,end_baika,trs_kaisu,trs_tenponum,trs_baika";  //全フィールド
    }

    // sql文作成
    $sql =  "SELECT $select ";
    $sql .= "FROM SRKTB070 ";
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
    log_message('debug',"========== get_kikaku_kakutoku_data end ==========");
    return $res;
  }

  /**
  * 企画獲得情報 登録
  *
  * @access	public
  * @param	array $data = 登録情報
  * @return	boolean $res = TRUE = 成功:FALSE = 失敗
  */
  function insert_srktb070_data($list_data)
  {
    try
    {
      log_message('debug',"========== insert_SRKTB070_data start ==========");
      // トランザクション開始
      $this->db->trans_begin();

      $res = NULL;

      //////////////////////////////
      //  削除処理
      //
      $sql = "DELETE FROM SRKTB070 ";
      $sql .= "WHERE shbn='{$list_data[0]['shbn']}' AND aiteskcd='{$list_data[0]['aiteskcd']}' AND year='{$list_data[0]['year']}' AND month='{$list_data[0]['month']}' ";
      log_message('debug',"sql=".$sql);
      // クエリ実行
      $query = $this->db->query($sql);
      /*
      if($query) {
      	$this->db->trans_complete();
      }else{
      	// ロールバック
      	$this->db->trans_rollback();
      	return FALSE;
      }*/

      foreach($list_data as $data) {
        //空欄チェック
        if( trim($data["shbn"])==="" || trim($data["year"])==="" || trim($data["month"])==="" || trim($data["aiteskcd"])==="" || $data["dbnricd"]==="" || $data["itemcd"]==="" ) continue;  //空欄時 登録データに入れない

        //存在チェック
        $where = "shbn='{$data['shbn']}' AND year='{$data['year']}' AND month='{$data['month']}' AND aiteskcd='{$data['aiteskcd']}' AND dbnricd='{$data['dbnricd']}' AND itemcd='{$data['itemcd']}' ";
        $kakutoku_data = $this->get_kikaku_kakutoku_data("shbn", $where);

	      //////////////////////////////
	      //  登録処理
	      //
        if(!$kakutoku_data) {
		      $date = date("Ymd");
		      // sql文作成
		      $sql = "INSERT INTO SRKTB070
		        (shbn,
		         year,
		         month,
		         aiteskcd,
		         dbnricd,
		         itemcd,
		         kbn,
		         edlp_kaisiymd,
		         edlp_shryoymd,
		         edlp_tenponum,
		         edlp_baika,
		         end_kaisiymd,
		         end_shryoymd,
		         end_tenponum,
		         end_baika,
		         trs_kaisu,
		         trs_tenponum,
		         trs_baika)
		      values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		      // クエリ実行
		      $query = $this->db->query(
		      $sql,
		      array(
		        $data['shbn'],
		        $data['year'],
		        $data['month'],
		        $data['aiteskcd'],
		        $data['dbnricd'],
		        $data['itemcd'],
		        $data['kbn'],
		        $data['edlp_kaisiymd'],
		        $data['edlp_shryoymd'],
		        $this->ifEmptyToZero($data['edlp_tenponum']),
		        $this->ifEmptyToZero($data['edlp_baika']),
		        $data['end_kaisiymd'],
		        $data['end_shryoymd'],
		        $this->ifEmptyToZero($data['end_tenponum']),
		        $this->ifEmptyToZero($data['end_baika']),
		        $this->ifEmptyToZero($data['trs_kaisu']),
		        $this->ifEmptyToZero($data['trs_tenponum']),
		        $this->ifEmptyToZero($data['trs_baika']) )
		      );
		      log_message('debug',$this->db->last_query());

		      // 結果判定
		      if ($this->db->trans_status() === false) {
		      	// ロールバック
		      	$this->db->trans_rollback();
		      	// 失敗
		      	return FALSE;
		      }
        }
      }

      // トランザクション終了(コミット)
      $this->db->trans_complete();
      // 成功
      $res = TRUE;

      log_message('debug',"========== insert_SRKTB070_data end ==========");
      return $res;
    }catch(Exception $e){
      // ロールバック
      $this->db->trans_rollback();
      return $res;
    }
  }

	function ifEmptyToZero($s) {
		return $s == '' ? 0 : $s;
	}

  /**
   * 企画獲得情報 更新
   *
   * @access	public
   * @param	array $data = 登録情報
   * @return	boolean $res = TRUE = 成功:FALSE = 失敗
  */
  function update_srktb070_data($data, $where)
  {
    try
    {
      log_message('debug',"========== update_SRKTB070_data start ==========");
      // トランザクション開始
      $this->db->trans_start();
      // 初期化
      $res = NULL;
      $date = date("Ymd");
      // WHERE条件作成
      $where = " WHERE ".$where;
      // sql文作成
      $sql = "UPDATE SRKTB070 SET
        shbn = ?,
        year = ?,
        month = ?,
        aiteskcd = ?,
        dbnricd = ?,
        itemcd = ?,
        kbn = ?,
        edlp_kaisiymd = ?,
        edlp_shryoymd = ?,
        edlp_tenponum = ?,
        edlp_baika = ?,
        end_kaisiymd = ?,
        end_shryoymd = ?,
        end_tenponum = ?,
        end_baika = ?,
        trs_kaisu = ?,
        trs_tenponum = ?,
        trs_baika = ?
        ".$where;
      log_message('debug',"sql=".$sql);
      // クエリ実行
      $query = $this->db->query(
        $sql,
        array(
          $data['shbn'],
          $data['year'],
          $data['month'],
          $data['aiteskcd'],
          $data['dbnricd'],
          $data['itemcd'],
          $data['kbn'],
          $data['edlp_kaisiymd'],
          $data['edlp_shryoymd'],
          $data['edlp_tenponum'],
          $data['edlp_baika'],
          $data['end_kaisiymd'],
          $data['end_shryoymd'],
          $data['end_tenponum'],
          $data['end_baika'],
          $data['trs_kaisu'],
          $data['trs_tenponum'],
          $data['trs_baika'] )
      );
      log_message('debug',$this->db->last_query());
      // 結果判定
      if ($this->db->trans_status() === TRUE)
      {
        // トランザクション終了(コミット)
        $this->db->trans_complete();
        // 成功
        $res = TRUE;
      }else{
        // ロールバック
        $this->db->trans_rollback();
        // 失敗
        $res = FALSE;
      }
      log_message('debug',"========== update_SRKTB070_data end ==========");
      return $res;
    }catch(Exception $e){
      // ロールバック
      $this->db->trans_rollback();
      return $res;
    }
  }
}

?>