<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 販売店グループ所属マスタを更新する
 *
 */
class B003Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループ所属マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループ所属マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループ所属マスタ更新処理失敗', LOG_ERR);
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
   * 販売店グループ所属マスタを更新
   *
   * @return void
   */
  protected function update_master()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 販売店グループ所属マスタ更新開始', LOG_INFO);
    $sql = "UPDATE SGMTB120 s SET "
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMHtnGrpShzk_TMP t "
      .    "WHERE "
      .      "t.HtnGrpCd = s.HtnGrpCd AND "
      .      "t.HtnCd = s.HtnCd"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    }

    $sql = "INSERT INTO SGMTB120 "
      .    "("
      .      "HtnGrpCd,"
      .      "HtnCd,"
      .      "CreateDate"
      .    ")"
      .    "("
      .      "SELECT "
      .        "t.HtnGrpCd,"
      .        "t.HtnCd,"
      .        "to_char(timestamp 'now','YYYYMMDD') "
      .      "FROM VMHtnGrpShzk_TMP t "
      .      "WHERE NOT EXISTS ("
      .        "SELECT * FROM SGMTB120 s WHERE "
      .          "t.HtnGrpCd = s.HtnGrpCd AND "
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
