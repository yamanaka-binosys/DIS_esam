<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 担当別相手先(代理店)更新
 *
 */
class B010Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 担当別相手先(代理店)更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 担当別相手先(代理店)更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 担当別相手先(代理店)更新処理失敗', LOG_ERR);
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
   * 担当別相手先(代理店)更新
   *
   * @return void
   */
  protected function update_master()
  {
    $sql = "UPDATE SGMTB100 s SET "
      .      "Kbn=(SELECT s2.Kbn FROM SGMTB050 s2 WHERE s2.AiteskCd=t.TrhkAitCd),"
      .      "UpdateDate=to_char(timestamp 'now','YYYYMMDD') "
      .    "FROM VMDtnShodnGyom_TMP t "
      .    "WHERE "
      .      "s.Shbn = t.TntoShinSshkCd AND "
      .      "s.HanhonCd = t.TrhkAitCd AND "
      .      "t.DeleteDate IS NULL"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB100 
         (
          Shbn,
          Kbn,
          HanhonCd,
          CreateDate
        )
        (
          SELECT 
            substr(t.TntoShinSshkCd, 4, 5),
            s2.Kbn,
            t.TrhkAitCd,
            to_char(timestamp 'now','YYYYMMDD') 
          FROM VMDtnShodnGyom_TMP t 
          INNER JOIN SGMTB050 s2 ON s2.AiteskCd=t.TrhkAitCd 
          WHERE NOT EXISTS (
            SELECT * FROM SGMTB100 s WHERE 
              s.Shbn = t.TntoShinSshkCd AND 
              s.HanhonCd = t.TrhkAitCd
          ) AND 
            t.DeleteDate IS NULL )
			";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }
}
