<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 組織情報のCSVファイルから
 * ユーザマスタ、組織マスタ、部署マスタを更新する
 *
 */
class B001Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': ユーザ、組織、部署マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': ユーザ、組織、部署マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': ユーザ、組織、部署マスタ更新処理失敗', LOG_ERR);
      $this->db->query('ROLLBACK');
    }
  }

  protected function _run()
  {
    $this->update_station_master();
    if (!$this->continue) return false;

    $this->update_organization_master();
    if (!$this->continue) return false;

    $this->update_user_master();
    if (!$this->continue) return false;

    $this->update_retire_user();
    if (!$this->continue) return false;

    return true;
  }

  /**
   * 部署マスタを更新
   *
   * @return void
   */
  protected function update_station_master()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 部署マスタ更新開始', LOG_INFO);
    $sql = "UPDATE SGMTB020 s SET 
            Busyu = t.BuKb,
          BuNm = t.ShinNm,
          HyoJun = t.HyoJun,
					updatedate = to_char(timestamp 'now','YYYYMMDD')
        FROM VMSshk_TMP t 
        WHERE 
          s.HonbuCd = t.HonbuCd AND 
          s.BuCd = t.BuCd AND 
          s.KaCd = t.KaCd AND 
          t.SoKb = '1' AND 
          t.Shbn = 'XXXXX' AND 
          t.DeleteDate IS NULL ";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB020 
        (
          HonbuCd,
          BuCd,
          KaCd,
          Busyu,
          BuNm,
          HyoJun,
          createdate
        )
        (
          SELECT t.HonbuCd, t.BuCd, t.KaCd, t.BuKb, t.ShinNm, t.HyoJun, to_char(timestamp 'now','YYYYMMDD') 
          FROM VMSshk_TMP t 
          WHERE NOT EXISTS (
            SELECT * FROM SGMTB020 s WHERE 
              t.HonbuCd = s.HonbuCd AND 
              t.BuCd = s.BuCd AND 
              t.KaCd = s.KaCd 
          ) AND 
            t.SoKb = '1' AND 
            t.Shbn = 'XXXXX' AND 
            t.HonbuCd <> 'XXXXX' AND 
            t.DeleteDate IS NULL)";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  /**
   * 組織マスタを更新
   *
   * @return void
   */
  protected function update_organization_master()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 組織マスタ更新開始', LOG_INFO);
    $sql = "UPDATE SGMTB070 as s SET 
            HonbuCd=t.HonbuCd,
          BuCd=t.BuCd,
          KaCd=t.KaCd,
          ShinNm=t.ShinNm,
          HyoJun=t.HyoJun,
					updatedate=to_char(timestamp 'now','YYYYMMDD')
        FROM VMSshk_TMP t 
        WHERE 
          s.Shbn = t.Shbn AND 
          t.Shbn <> 'XXXXX' AND 
          t.SoKb = '2' AND 
          t.DeleteDate IS NULL";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB070 
        (
          HonbuCd,
          BuCd,
          KaCd,
          Shbn,
          ShinNm,
          HyoJun,
          createdate
        )
        (
          SELECT t.HonbuCd, t.BuCd, t.KaCd, t.Shbn, t.ShinNm, t.HyoJun, to_char(timestamp 'now','YYYYMMDD') 
          FROM VMSshk_TMP t 
          WHERE NOT EXISTS (
            SELECT * FROM SGMTB070 s WHERE 
              t.Shbn = s.Shbn
          ) AND 
            t.Shbn <> 'XXXXX' AND 
            t.SoKb = '2' AND 
            t.DeleteDate IS NULL
        )";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  /**
   * ユーザマスタを更新
   *
   * @return void
   */
  protected function update_user_master()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': ユーザマスタ更新開始', LOG_INFO);
    $sql = "
			UPDATE SGMTB010 s SET 
          HonbuCd=t.HonbuCd,
          BuCd=t.BuCd,
          KaCd=t.KaCd, 
          UnitShbn=t.Sebn,
					menuhyjikbn=CASE WHEN menuhyjikbn = '003' THEN menuhyjikbn ELSE CASE WHEN t.Shbn = t.Sebn THEN '002' ELSE '001' END END
			FROM VMSshk_TMP t 
      WHERE 
          s.Shbn = t.Shbn AND 
          t.Shbn <> 'XXXXX'"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    }

    $sql = "
			INSERT INTO SGMTB010 (
         Shbn,
          ShinNm,
          HonbuCd,
          BuCd,
          KaCd,
          HojinRhanKb,
          Passwd,
					menuhyjikbn,
					unitshbn,
          PWUpdateDate,
          CreateDate
        )
        (
          SELECT 
						t.Shbn, 
						t.ShinNm, 
						t.HonbuCd, 
						t.BuCd, 
						t.KaCd, 
						'1', 
						t.Shbn || 'daio', 
						CASE WHEN t.Shbn = t.Sebn THEN '002' ELSE '001' END,
						t.Sebn, 
						to_char(timestamp 'now','YYYYMMDD'), 
						to_char(timestamp 'now','YYYYMMDD') 
          FROM VMSshk_TMP t 
          WHERE NOT EXISTS ( 
            SELECT * FROM SGMTB010 s WHERE 
              t.Shbn = s.Shbn
          ) AND 
            t.Shbn <> 'XXXXX'
        )"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    }
  }

  /**
   * 退職ユーザ処理
   *
   */
  protected function update_retire_user()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': ユーザマスタ退職処理開始', LOG_INFO);
    $sql = "UPDATE SGMTB010 s SET "
      .      "DeleteDate=to_char(timestamp 'now','YYYYMMDD')"
      .    "WHERE Shbn !~ '^TEST' AND NOT EXISTS ("
      .      "SELECT * FROM VMSshk_TMP t WHERE "
      .        "t.Shbn = s.Shbn"
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
