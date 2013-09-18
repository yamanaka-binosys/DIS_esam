<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 代理店組織CSVファイルから
 * 代理店組織マスタを更新する
 *
 */
class B008Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 代理店組織マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 代理店組織マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 代理店組織マスタ更新処理失敗', LOG_ERR);
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
   * 代理店組織マスタを更新
   *
   * @return void
   */
  protected function update_master()
  {
    $sql = "UPDATE SGMTB060 s SET "
      .      "Kbn='1',"
      .      "AiteskNm=t.DtnSshkNm,"
      .      "AiteskKn=t.DtnSshkKnNm,"
      .      "Yubin=t.DtnSshkYubnNo,"
      .      "Jyusho=t.DtnSshkJush,"
      .      "Denwa=t.DtnSshkTelNo,"
      .      "WisDCd=t.WisdomDtnCd,"
      .      "kyotuCd=t.KyotsuTrhkskCd,"
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMDtnSshk_TMP t "
      .    "WHERE "
      .      "s.HanhonCd = t.TrhkAitCd AND "
      .      "s.Kbn = '1' AND "
      .      "t.DeleteDate IS NULL"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB060 "
      .    "("
      .      "HanhonCd,"
      .      "Kbn,"
      .      "eigyoum,"
      .      "AiteskNm,"
      .      "AiteskKn,"
      .      "Yubin,"
      .      "Jyusho,"
      .      "Denwa,"
      .      "WisDCd,"
      .      "kyotuCd,"
      .      "CreateDate"
      .    ")"
      .    "("
      .      "SELECT "
      .        "TrhkAitCd,"
      .        "'1',"
      .        "'1',"
      .        "DtnSshkNm,"
      .        "DtnSshkKnNm,"
      .        "DtnSshkYubnNo,"
      .        "DtnSshkJush,"
      .        "DtnSshkTelNo,"
      .        "WisdomDtnCd,"
      .        "KyotsuTrhkskCd,"
      .        "to_char(timestamp 'now','YYYYMMDD') "
      .      "FROM VMDtnSshk_TMP t "
      .      "WHERE NOT EXISTS ("
      .        "SELECT * FROM SGMTB060 s WHERE "
      .          "s.HanhonCd = t.TrhkAitCd AND "
      .          "s.Kbn = '1' "
      .      ") AND "
      .      "t.DeleteDate IS NULL"
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
