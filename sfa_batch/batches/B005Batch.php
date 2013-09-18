<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 販売店商談業務CSVファイルから
 * 販売店商談業務マスタを更新する
 *
 */
class B005Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店商談業務マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店商談業務マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店商談業務マスタ更新処理失敗', LOG_ERR);
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
   * 販売店商談業務マスタを更新
   *
   * @return void
   */
  protected function update_master()
  {
    $sql = "UPDATE SGMTB140 s SET "
      .      "TntoShinSshkCd=t.TntoShinSshkCd,"
      .      "TntoKbn=t.TntoKbn,"
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMHtnShodnGyom_TMP t "
      .    "WHERE "
      .      "t.TrhkAitCd = s.TrhkAitCd AND "
      .      "t.TntoSshkCd = s.TntoSshkCd AND "
      .      "t.TntoShinSshkCd = s.TntoShinSshkCd"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB140 "
      .    "("
      .      "TrhkAitCd,"
      .      "TntoSshkCd,"
      .      "TntoShinSshkCd,"
      .      "TntoKbn,"
      .      "CreateDate"
      .    ")"
      .    "("
      .      "SELECT DISTINCT "
      .        "TrhkAitCd,"
      .        "TntoSshkCd,"
      .        "TntoShinSshkCd,"
      .        "TntoKbn,"
      .        "to_char(timestamp 'now','YYYYMMDD') "
      .      "FROM VMHtnShodnGyom_TMP t "
      .      "WHERE NOT EXISTS ("
      .        "SELECT * FROM SGMTB140 s WHERE "
      .          "t.TrhkAitCd = s.TrhkAitCd AND "
      .          "t.TntoSshkCd = s.TntoSshkCd AND "
      .          "t.TntoShinSshkCd = s.TntoShinSshkCd"
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
