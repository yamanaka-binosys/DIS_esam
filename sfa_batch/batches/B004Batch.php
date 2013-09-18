<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 販売店組織マスタを更新する
 *
 */
class B004Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店組織マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店組織マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店組織マスタ更新処理失敗', LOG_ERR);
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
   * 販売店組織マスタを更新
   *
   * @return void
   */
  protected function update_master()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店組織マスタ更新開始', LOG_INFO);
    $sql = "UPDATE SGMTB130 s SET "
      .      "HtnSshkKnNm=t.HtnSshkKnNm,"
      .      "HtnSshkNm=t.HtnSshkNm,"
      .      "HtnSshkRnm=t.HtnSshkRnm,"
      .      "HtnSshkKbn=t.HtnSshkKbn,"
      .      "HtnSshkYubnNo=t.HtnSshkYubnNo,"
      .      "HtnSshkJush=t.HtnSshkJush,"
      .      "HtnSshkTelNo=t.HtnSshkTelNo,"
      .      "HtnSsJisTdofknCd=t.HtnSsJisTdofknCd,"
      .      "HtnSsJisSkchosnCd=t.HtnSsJisSkchosnCd,"
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMHtnSshk_TMP t "
      .    "WHERE "
      .      "t.TrhkAitCd = s.TrhkAitCd AND "
      .      "t.HtnCd = s.HtnCd"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB130 "
      .    "("
      .      "TrhkAitCd,"
      .      "HtnCd,"
      .      "HtnSshkKnNm,"
      .      "HtnSshkNm,"
      .      "HtnSshkRnm,"
      .      "HtnSshkKbn,"
      .      "HtnSshkYubnNo,"
      .      "HtnSshkJush,"
      .      "HtnSshkTelNo,"
      .      "HtnSsJisTdofknCd,"
      .      "HtnSsJisSkchosnCd,"
      .      "CreateDate"
      .    ")"
      .    "("
      .      "SELECT "
      .        "t.TrhkAitCd,"
      .        "t.HtnCd,"
      .        "t.HtnSshkKnNm,"
      .        "t.HtnSshkNm,"
      .        "t.HtnSshkRnm,"
      .        "t.HtnSshkKbn,"
      .        "t.HtnSshkYubnNo,"
      .        "t.HtnSshkJush,"
      .        "t.HtnSshkTelNo,"
      .        "t.HtnSsJisTdofknCd,"
      .        "t.HtnSsJisSkchosnCd,"
      .        "to_char(timestamp 'now','YYYYMMDD') "
      .      "FROM VMHtnSshk_TMP t "
      .      "WHERE NOT EXISTS ("
      .        "SELECT * FROM SGMTB130 s WHERE "
      .          "t.TrhkAitCd = s.TrhkAitCd AND "
      .          "t.HtnCd = s.HtnCd"
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
