<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * 取引相手販売店組織・店舗CSVファイルから
 * 取引相手販売店組織・店舗マスタを更新
 *
 */
class B006Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 取引相手販売店組織・店舗マスタ更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 取引相手販売店組織・店舗マスタ更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 取引相手販売店組織・店舗マスタ更新処理失敗', LOG_ERR);
      $this->db->query('ROLLBACK');
    }
  }

  protected function _run()
  {
    $this->update_client_store_hq();
    if (!$this->continue) return false;

    $this->update_client_store_district_hq();
    if (!$this->continue) return false;

    $this->update_client_store_store();
    if (!$this->continue) return false;

    return true;
  }

  /**
   * 取引相手販売店組織・店舗マスタを更新
   * 本部更新用
   *
   * @return void
   */
  protected function update_client_store_hq()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 取引相手販売店組織・店舗マスタ(本部)更新処理開始', LOG_INFO);
    $sql = "UPDATE SGMTB050 s SET 
          HyoJun=t1.HyojJnjo,
          AiteskNm=t2.HtnSshkNm,
          AiteskKn=t2.HtnSshkKnNm,
          Yubin=t2.HtnSshkYubnNo,
          Jyusho=t2.HtnSshkJush,
          Denwa=t2.HtnSshkTelNo,
          Rank=t3.HtnRank,
          UpdateDate=to_char(timestamp 'now','YYYYMMDD') 
        FROM VMTrhkAitHtnTnp_TMP t1 
        INNER JOIN VMHtnSshk_TMP t2 ON 
          t1.TrhkAitCd = t2.TrhkAitCd 
        INNER JOIN VMHtn_TMP t3 ON 
          t2.HtnCd = t3.HtnCd 
        WHERE 
          t1.TrhkAitKbn = '1' AND 
          t1.HtnSshkKbn = '10' AND 
          t1.DeleteDate IS NULL AND 
          s.HanhonCd = t1.TrhkAitCd AND 
          s.HanchiCd = 'XXXXXXXX' AND 
          s.AiteskCd = 'XXXXXXXX' AND 
          s.Kbn = '1'"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB050 
        (
          HanhonCd,
          HanchiCd,
          AiteskCd,
          Kbn,
          HyoJun,
          AiteskNm,
          AiteskKn,
          Yubin,
          Jyusho,
          Denwa,
          Rank,
          CreateDate
        )
        (
          SELECT DISTINCT 
            t1.TrhkAitCd,
            'XXXXXXXX',
            'XXXXXXXX',
            '1',
            t1.HyojJnjo,
            t2.HtnSshkNm,
            t2.HtnSshkKnNm,
            t2.HtnSshkYubnNo,
            t2.HtnSshkJush,
            t2.HtnSshkTelNo,
            t3.HtnRank,
            to_char(timestamp 'now','YYYYMMDD') 
          FROM VMTrhkAitHtnTnp_TMP t1 
          INNER JOIN VMHtnSshk_TMP t2 ON 
            t1.TrhkAitCd = t2.TrhkAitCd 
          INNER JOIN VMHtn_TMP t3 ON 
            t2.HtnCd = t3.HtnCd 
          WHERE NOT EXISTS (
            SELECT * FROM SGMTB050 s WHERE 
              t1.TrhkAitKbn = '1' AND 
              t1.HtnSshkKbn = '10' AND 
              s.HanhonCd = t1.TrhkAitCd AND 
              s.HanchiCd = 'XXXXXXXX' AND 
              s.AiteskCd = 'XXXXXXXX' AND 
              s.Kbn = '1'
          ) AND 
            t1.TrhkAitKbn = '1' AND 
            t1.HtnSshkKbn = '10' AND 
            t1.DeleteDate IS NULL 
        )";
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  /**
   * 取引相手販売店組織・店舗マスタを更新
   * 地区本部更新用
   *
   * @return void
   */
  protected function update_client_store_district_hq()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 取引相手販売店組織・店舗マスタ(地域本部)更新処理開始', LOG_INFO);
    $sql = "UPDATE SGMTB050 s SET 
          HyoJun=t1.HyojJnjo,
          AiteskNm=t2.HtnSshkNm,
          AiteskKn=t2.HtnSshkKnNm,
          Yubin=t2.HtnSshkYubnNo,
          Jyusho=t2.HtnSshkJush,
          Denwa=t2.HtnSshkTelNo,
          Rank=t3.HtnRank,
          UpdateDate=to_char(timestamp 'now','YYYYMMDD') 
        FROM VMTrhkAitHtnTnp_TMP t1 
        INNER JOIN VMHtnSshk_TMP t2 ON 
          t1.TrhkAitCd = t2.TrhkAitCd 
        INNER JOIN VMHtn_TMP t3 ON 
          t2.HtnCd = t3.HtnCd 
        WHERE 
          t1.TrhkAitKbn = '1' AND 
          t1.HtnSshkKbn = '20' AND 
          t1.DeleteDate IS NULL AND 
          s.HanhonCd = t1.JoiSHSshkTrhkAitCd AND 
          s.HanchiCd = t1.TrhkAitCd AND 
          s.AiteskCd = 'XXXXXXXX' AND 
          s.Kbn = '2'"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB050 
        (
          HanhonCd,
          HanchiCd,
          AiteskCd,
          Kbn,
          HyoJun,
          AiteskNm,
          AiteskKn,
          Yubin,
          Jyusho,
          Denwa,
          Rank,
          CreateDate
        )
        (
          SELECT 
            t1.JoiSHSshkTrhkAitCd,
            t1.TrhkAitCd,
            'XXXXXXXX',
            '2',
            t1.HyojJnjo,
            t2.HtnSshkNm,
            t2.HtnSshkKnNm,
            t2.HtnSshkYubnNo,
            t2.HtnSshkJush,
            t2.HtnSshkTelNo,
            t3.HtnRank,
            to_char(timestamp 'now','YYYYMMDD') 
          FROM VMTrhkAitHtnTnp_TMP t1 
          INNER JOIN VMHtnSshk_TMP t2 ON 
            t1.TrhkAitCd = t2.TrhkAitCd 
          INNER JOIN VMHtn_TMP t3 ON 
            t2.HtnCd = t3.HtnCd 
          WHERE NOT EXISTS (
            SELECT * FROM SGMTB050 s WHERE 
              t1.TrhkAitKbn = '1' AND 
              t1.HtnSshkKbn = '20' AND 
              t1.DeleteDate IS NULL AND 
              s.HanhonCd = t1.JoiSHSshkTrhkAitCd AND 
              s.HanchiCd = t1.TrhkAitCd AND 
              s.AiteskCd = 'XXXXXXXX' AND 
              s.Kbn = '2'
          ) AND 
            t1.TrhkAitKbn = '1' AND 
            t1.HtnSshkKbn = '20' AND 
            t1.DeleteDate IS NULL 
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
   * 取引相手販売店組織・店舗マスタを更新
   * 店舗更新用
   *
   * @return void
   */
  protected function update_client_store_store()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 取引相手販売店組織・店舗マスタ(店舗)更新処理開始', LOG_INFO);
    $select = "SELECT 
          t1.TrhkAitCd as TrhkAitCd, 
          t2.HyojJnjo  as HyojJnjo, 
          t1.TrhkAitKbn as TrhkAitKbn1, 
          t1.HtnSshkKbn as HtnSshkKbn1, 
          t1.DeleteDate as DeleteDate1, 
          CASE WHEN t2.TrhkAitKbn = '1' AND t2.HtnSshkKbn = '10' THEN 
                    t2.TrhkAitCd 
               WHEN t2.TrhkAitKbn = '1' AND t2.HtnSshkKbn = '20' THEN 
                    t2.JoiSHSshkTrhkAitCd 
          END as HanhonCd, 
          CASE WHEN t2.TrhkAitKbn = '1' AND t2.HtnSshkKbn = '10' THEN 
                    'XXXXXXXX'
               WHEN t2.TrhkAitKbn = '1' AND t2.HtnSshkKbn = '20' THEN 
                    t2.TrhkAitCd 
          END as HanchiCd, 
          t3.TnpNm as AiteskNm, 
          t3.TnmKnNm as AiteskKn, 
          t3.TnpYubnNo as Yubin, 
          t3.TnpJush as Jyusho, 
          t3.TnpTelNo as Denwa, 
          t4.HtnRank as Rank 
        FROM VMTrhkAitHtnTnp_TMP t1 
        INNER JOIN VMTrhkAitHtnTnp_TMP t2 ON 
          t1.JoiSHSshkTrhkAitCd = t2.TrhkAitCd 
        INNER JOIN VMTnp_TMP t3 ON 
          t1.TrhkAitCd = t3.TrhkAitCd 
        INNER JOIN VMHtn_TMP t4 ON 
          t3.HtnCd = t4.HtnCd 
        WHERE 
          t1.TrhkAitKbn = '2' AND 
          t1.HtnSshkKbn = '' AND 
          t1.DeleteDate IS NULL "
      ;

    $sql = "UPDATE SGMTB050 s SET 
          HyoJun=t.HyojJnjo,
          AiteskNm=t.AiteskNm,
          AiteskKn=t.AiteskKn,
          Yubin=t.Yubin,
          Jyusho=t.Jyusho,
          Denwa=t.Denwa,
          Rank=t.Rank,
          UpdateDate=to_char(timestamp 'now','YYYYMMDD') 
        FROM 
          (" . $select . ") t 
          WHERE 
          s.HanhonCd = t.HanhonCd AND 
          s.HanchiCd = t.HanchiCd AND 
          s.AiteskCd = t.TrhkAitCd AND 
          s.Kbn = '3' AND 
          t.TrhkAitKbn1 = '2' AND 
          t.HtnSshkKbn1 = '' AND 
          t.DeleteDate1 IS NULL "
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 

    $sql = "INSERT INTO SGMTB050 
        (
          HanhonCd,
          HanchiCd,
          AiteskCd,
          Kbn,
          HyoJun,
          AiteskNm,
          AiteskKn,
          Yubin,
          Jyusho,
          Denwa,
          Rank,
          CreateDate
        )
        (
          SELECT 
            t.HanhonCd,
            t.HanchiCd,
            t.TrhkAitCd,
            '3',
            t.HyojJnjo,
            t.AiteskNm,
            t.AiteskKn,
            t.Yubin,
            t.Jyusho,
            t.Denwa,
            t.Rank,
            to_char(timestamp 'now','YYYYMMDD') 
          FROM 
            (" . $select . ") t 
          WHERE NOT EXISTS (
            SELECT * FROM SGMTB050 s WHERE 
              s.HanhonCd = t.HanhonCd AND 
              s.HanchiCd = t.HanchiCd AND 
              s.AiteskCd = t.TrhkAitCd AND 
              s.Kbn = '3'
          ) AND 
            t.TrhkAitKbn1 = '2' AND 
            t.HtnSshkKbn1 = '' AND 
            t.DeleteDate1 IS NULL 
        )"
      ;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }
}
