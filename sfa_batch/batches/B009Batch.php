<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 担当別相手先(販売店)更新
 *
 */
class B009Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 担当別相手先(販売店)更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 担当別相手先(販売店)更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 担当別相手先(販売店)更新処理失敗', LOG_ERR);
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
   * 担当別相手先(販売店)更新
   *
   * @return void
   */
  protected function update_master()
  {
		$select = "
			SELECT 
				  DISTINCT
			    TntoKbn,
			    substr(TntoShinSshkCd, 4, 5) AS shbn,
			    HanhonCd,
			    HanchiCd,
			    AiteskCd,
			    to_char(timestamp 'now','YYYYMMDD') AS nowdate
			FROM
			(
			SELECT * FROM SGMTB050 aite INNER JOIN VMHtnShodnGyom_TMP gyom
			ON aite.kbn = '1' AND gyom.DeleteDate IS NULL AND  aite.hanhoncd = gyom.trhkaitcd 
			UNION
			SELECT * FROM SGMTB050 aite INNER JOIN VMHtnShodnGyom_TMP gyom
			ON aite.kbn = '2' AND gyom.DeleteDate IS NULL AND  aite.hanchicd = gyom.trhkaitcd 
			UNION
			SELECT * FROM SGMTB050 aite INNER JOIN VMHtnShodnGyom_TMP gyom
			ON aite.kbn = '3' AND gyom.DeleteDate IS NULL AND aite.aiteskcd = gyom.trhkaitcd 
			) t ";
	
	
    $sql = "UPDATE SGMTB090 s SET 
          HanhonCd=t.HanhonCd,
          HanchiCd=t.HanchiCd,
          AiteskCd=t.AiteskCd,
          UpdateDate=t.nowdate
        FROM (".$select.") t  
        WHERE 
					s.HanhonCd = t.HanhonCd AND
					s.HanchiCd = t.HanchiCd AND
					s.AiteskCd = t.AiteskCd AND
	        s.Kbn = t.TntoKbn AND 
	        s.Shbn = t.shbn";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB090 
		        (
		          Kbn,
		          Shbn,
		          HanhonCd,
		          HanchiCd,
		          AiteskCd,
		          CreateDate
		        )
		        (
						SELECT * FROM (".$select.") t
		         WHERE NOT EXISTS (
		            SELECT * FROM SGMTB090 s WHERE 
									s.HanhonCd = t.HanhonCd AND
									s.HanchiCd = t.HanchiCd AND
									s.AiteskCd = t.AiteskCd AND
		              s.Kbn = t.TntoKbn AND 
		              s.Shbn = t.shbn
		          )
						)";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }
}
