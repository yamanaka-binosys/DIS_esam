<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 販売店グループマスタを更新する
 *
 */
class B002Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループマスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループマスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループマスタ更新処理失敗', LOG_ERR);
      $this->db->query('ROLLBACK');
    }
  }

  protected function _run()
  {
    $this->update_master();
    if (!$this->continue) return false;

    return true;
  }

  /**
   * 販売店グループマスタを更新
   *
   * @return void
   */
  protected function update_master()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループマスタ更新開始', LOG_INFO);
    $sql = "UPDATE SGMTB110 s SET "
      .      "HtnGrpNm=t.HtnGrpNm,"
      .      "HtnGrpKbn=t.HtnGrpKbn,"
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMHtnGrp_TMP t "
      .    "WHERE "
      .      "t.HtnGrpCd = s.HtnGrpCd"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB110 "
      .    "("
      .      "HtnGrpCd,"
      .      "HtnGrpNm,"
      .      "HtnGrpKbn,"
      .      "CreateDate"
      .    ")"
      .    "("
      .      "SELECT "
      .        "t.HtnGrpCd,"
      .        "t.HtnGrpNm,"
      .        "t.HtnGrpKbn,"
      .        "to_char(timestamp 'now','YYYYMMDD') "
      .      "FROM VMHtnGrp_TMP t "
      .      "WHERE NOT EXISTS ("
      .        "SELECT * FROM SGMTB110 s WHERE "
      .          "t.HtnGrpCd = s.HtnGrpCd"
      .      ")"
      .    ")"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }
}
