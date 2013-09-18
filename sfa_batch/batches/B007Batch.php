<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 店舗CSVファイルから
 * 店舗マスタを更新する
 *
 */
class B007Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 店舗マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 店舗マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 店舗マスタ更新処理失敗', LOG_ERR);
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
   * 店舗マスタを更新
   *
   * @return void
   */
  protected function update_master()
  {
    $sql = "UPDATE SGMTB051 s SET "
      .      "AiteskNm=t.TnpNm,"
      .      "AiteskKn=t.TnmKnNm,"
      .      "Yubin=t.TnpYubnNo,"
      .      "Jyusho=t.TnpJush,"
      .      "Denwa=t.TnpTelNo,"
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMTnp_TMP t "
      .    "WHERE "
      .      "t.HtnCd = '''''' AND "
      .      "t.DeleteDate IS NULL AND "
      .      "t.TrhkAitCd = s.AiteskCd"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB051 "
      .    "("
      .      "AiteskCd,"
      .      "AiteskNm,"
      .      "AiteskKn,"
      .      "Yubin,"
      .      "Jyusho,"
      .      "Denwa,"
      .      "CreateDate"
      .    ")"
      .    "("
      .      "SELECT "
      .        "t.TrhkAitCd,"
      .        "t.TnpNm,"
      .        "t.TnmKnNm,"
      .        "t.TnpYubnNo,"
      .        "t.TnpJush,"
      .        "t.TnpTelNo,"
      .        "to_char(timestamp 'now','YYYYMMDD') "
      .      "FROM VMTnp_TMP t "
      .      "WHERE NOT EXISTS ("
      .        "SELECT * FROM SGMTB051 s WHERE "
      .          "t.TrhkAitCd = s.AiteskCd "
      .      ") AND "
      .        "t.HtnCd = '''''' AND "
      .        "t.DeleteDate IS NULL "
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
