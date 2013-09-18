<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 *
 */
class S001Batch extends BaseBatch {

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 帳票バッチ処理開始', LOG_INFO);
    $this->db->query('BEGIN');

    $re = $this->_run();

    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 帳票バッチ処理正常終了', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': 帳票バッチ処理失敗', LOG_ERR);
      $this->db->query('ROLLBACK');
    }
  }

  protected function _run()
  {
    $this->run_sql1();
    if (!$this->continue) return false;

    $this->run_sql2();
    if (!$this->continue) return false;

    $this->run_sql3();
    if (!$this->continue) return false;

    $this->run_sql4();
    if (!$this->continue) return false;

    $this->run_sql5();
    if (!$this->continue) return false;

    return true;
  }

  protected function run_sql1()
  {
    $sql = "delete from SRRTB010";

    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  protected function run_sql2()
  {
    $sql = <<<SQL
      INSERT INTO SRRTB010
      SELECT
      BUSHO.HonbuCd,
      BUSHO.BuCd,
      BUSHO.KaCd,
      BASE.Shbn,
      BASE.Year,
      BASE.Month,
      COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0) as totalcnt,
      COALESCE(HON.HmnCnt,0) as honCnt,
      CASE 
        WHEN COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0) >0
        THEN COALESCE(HON.HmnCnt,0)/(COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0))
        ELSE 0
      END as honhi,
      COALESCE(HON_SA.HmnCnt,0) as honSAcnt,
      CASE 
        WHEN COALESCE(HON.HmnCnt,0) >0
        THEN COALESCE(HON_SA.HmnCnt,0)/COALESCE(HON.HmnCnt,0)
        ELSE 0
      END as honSAhi,
      COALESCE(HON_B.HmnCnt,0) as honBcnt,
      CASE 
        WHEN COALESCE(HON.HmnCnt,0) >0
        THEN COALESCE(HON_B.HmnCnt,0)/COALESCE(HON.HmnCnt,0)
        ELSE 0
      END as honBhi,
      COALESCE(TEN.HmnCnt,0) as tencnt,
      CASE 
        WHEN COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0) >0
        THEN COALESCE(TEN.HmnCnt,0)/(COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0))
        ELSE 0
      END as tenhi,
      COALESCE(TEN_SA.HmnCnt,0) as tenSAcnt,
      CASE 
        WHEN COALESCE(TEN.HmnCnt,0) >0
        THEN COALESCE(TEN_SA.HmnCnt,0)/COALESCE(TEN.HmnCnt,0)
        ELSE 0
      END as tenSAhi,
      COALESCE(TEN_B.HmnCnt,0) as tenBcnt,
      CASE 
        WHEN COALESCE(TEN.HmnCnt,0) >0
        THEN COALESCE(TEN_B.HmnCnt,0)/COALESCE(TEN.HmnCnt,0)
        ELSE 0
      END as tenBhi,
      COALESCE(IPPAN.HmnCnt,0) as ippancnt,
      CASE 
        WHEN COALESCE(TEN.HmnCnt,0) >0
        THEN COALESCE(IPPAN.HmnCnt,0)/COALESCE(TEN.HmnCnt,0)
        ELSE 0
      END as ippanhi,
      COALESCE(DAIRI.HmnCnt,0) as dairicnt,
      CASE 
        WHEN COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0) >0
        THEN COALESCE(DAIRI.HmnCnt,0)/(COALESCE(HON.HmnCnt,0)+COALESCE(TEN.HmnCnt,0)+COALESCE(DAIRI.HmnCnt,0))
        ELSE 0
      END as dairihi
      from
      (
      select distinct
      SGMTB010.Shbn as Shbn,
      case when to_number(substr(SRNTB010.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB010.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB010.Ymd,1,4)
      end as Year,
      substr(SRNTB010.Ymd,5,2) as Month
      from SGMTB010 
      inner join SRNTB010 on
      SGMTB010.Shbn=SRNTB010.Shbn
      union
      select distinct
      SGMTB010.Shbn as Shbn,
      case when to_number(substr(SRNTB020.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB020.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB020.Ymd,1,4)
      end as Year,
      substr(SRNTB020.Ymd,5,2) as Month
      from SGMTB010 
      inner join SRNTB020 on
      SGMTB010.Shbn=SRNTB020.Shbn
      union
      select distinct
      SGMTB010.Shbn as Shbn,
      case when to_number(substr(SRNTB030.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB030.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB030.Ymd,1,4)
      end as Year,
      substr(SRNTB030.Ymd,5,2) as Month
      from SGMTB010 
      inner join SRNTB030 on
      SGMTB010.Shbn=SRNTB030.Shbn
      ) BASE
      INNER JOIN SGMTB010 BUSHO ON
      BASE.Shbn=BUSHO.Shbn
      left join
      (select 
      SRNTB010.Shbn as Shbn,
      case when to_number(substr(SRNTB010.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB010.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB010.Ymd,1,4)
      end as Year,
      substr(SRNTB010.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB010 
      inner join SGMTB050 on
      SRNTB010.AiteskCd=SGMTB050.AiteskCd
      group by Shbn,Year,Month
      ) HON on
      BASE.Shbn=HON.Shbn
      and BASE.Year=HON.Year
      and BASE.Month=HON.Month
      left join
      (select 
      SRNTB010.Shbn as Shbn,
      case when to_number(substr(SRNTB010.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB010.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB010.Ymd,1,4)
      end as Year,
      substr(SRNTB010.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB010 
      inner join SGMTB050 on
      SRNTB010.AiteskCd=SGMTB050.AiteskCd
      where SGMTB050.rank in ('S','A')
      group by Shbn,Year,Month
      ) HON_SA on
      BASE.Shbn=HON_SA.Shbn
      and BASE.Year=HON_SA.Year
      and BASE.Month=HON_SA.Month
      left join
      (select 
      SRNTB010.Shbn as Shbn,
      case when to_number(substr(SRNTB010.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB010.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB010.Ymd,1,4)
      end as Year,
      substr(SRNTB010.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB010 
      inner join SGMTB050 on
      SRNTB010.AiteskCd=SGMTB050.AiteskCd
      where SGMTB050.rank ='B'
      group by Shbn,Year,Month
      ) HON_B on
      BASE.Shbn=HON_B.Shbn
      and BASE.Year=HON_B.Year
      and BASE.Month=HON_B.Month
      left join 
      (select 
      SRNTB020.Shbn as Shbn,
      case when to_number(substr(SRNTB020.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB020.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB020.Ymd,1,4)
      end as Year,
      substr(SRNTB020.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB020 
      inner join SGMTB050 on
      SRNTB020.AiteskCd=SGMTB050.AiteskCd
      group by Shbn,Year,Month
      ) TEN on
      BASE.Shbn=TEN.Shbn
      and BASE.Year=TEN.Year
      and BASE.Month=TEN.Month
      left join 
      (select 
      SRNTB020.Shbn as Shbn,
      case when to_number(substr(SRNTB020.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB020.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB020.Ymd,1,4)
      end as Year,
      substr(SRNTB020.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB020 
      inner join SGMTB050 on
      SRNTB020.AiteskCd=SGMTB050.AiteskCd
      where 
      SRNTB020.TnpKB='1'
      and SGMTB050.rank in ('S','A')
      group by Shbn,Year,Month
      ) TEN_SA on
      BASE.Shbn=TEN_SA.Shbn
      and BASE.Year=TEN_SA.Year
      and BASE.Month=TEN_SA.Month
      left join
      (select 
      SRNTB020.Shbn as Shbn,
      case when to_number(substr(SRNTB020.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB020.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB020.Ymd,1,4)
      end as Year,
      substr(SRNTB020.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB020 
      inner join SGMTB050 on
      SRNTB020.AiteskCd=SGMTB050.AiteskCd
      where 
      SRNTB020.TnpKB='1'
      and SGMTB050.rank ='B'
      group by Shbn,Year,Month
      ) TEN_B on
      BASE.Shbn=TEN_B.Shbn
      and BASE.Year=TEN_B.Year
      and BASE.Month=TEN_B.Month
      left join 
      (select 
      SRNTB020.Shbn as Shbn,
      case when to_number(substr(SRNTB020.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB020.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB020.Ymd,1,4)
      end as Year,
      substr(SRNTB020.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB020 
      where 
      SRNTB020.TnpKB='2'
      group by Shbn,Year,Month
      ) IPPAN on
      BASE.Shbn=IPPAN.Shbn
      and BASE.Year=IPPAN.Year
      and BASE.Month=IPPAN.Month
      left join
      (select 
      SRNTB030.Shbn as Shbn,
      case when to_number(substr(SRNTB030.Ymd,5,2),'99') < 4 
           then to_char(to_number(substr(SRNTB030.Ymd,1,4),'9999')-1,'9999')
           else substr(SRNTB030.Ymd,1,4)
      end as Year,
      substr(SRNTB030.Ymd,5,2) as Month,
      count(*) as HmnCnt
      from SRNTB030 
      group by Shbn,Year,Month
      ) DAIRI on
      BASE.Shbn=DAIRI.Shbn
      and BASE.Year=DAIRI.Year
      and BASE.Month=DAIRI.Month;
SQL;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  protected function run_sql3()
  {
    $sql = <<<SQL
      --月間ユニット別訪問件数 データ作成SQL
      INSERT INTO SRRTB011
      SELECT 
      HonbuCd,
      BuCd,
      KaCd,
      Year,
      Month,
      COUNT(*),
      SUM(HmnCnt),
      SUM(HH_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(HH_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END,
      SUM(HH_SA_HmnCnt),
      CASE 
        WHEN SUM(HH_HmnCnt) >0
        THEN SUM(HH_SA_HmnCnt)/SUM(HH_HmnCnt)
        ELSE 0
      END,
      SUM(HH_B_HmnCnt),
      CASE 
        WHEN SUM(HH_HmnCnt) >0
        THEN SUM(HH_B_HmnCnt)/SUM(HH_HmnCnt)
        ELSE 0
      END,
      SUM(TP_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(TP_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END,
      SUM(TP_SA_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(TP_SA_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(TP_B_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(TP_B_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(IP_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(IP_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(DR_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(DR_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END
      FROM SRRTB010
      group by 
      HonbuCd,BuCd,KaCd,Year,Month;
SQL;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  protected function run_sql4()
  {
    $sql = <<<SQL
      --月間部別訪問件数 データ作成SQL
      INSERT INTO SRRTB012
      SELECT 
      HonbuCd,
      BuCd,
      Year,
      Month,
      COUNT(*),
      SUM(HmnCnt),
      SUM(HH_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(HH_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END,
      SUM(HH_SA_HmnCnt),
      CASE 
        WHEN SUM(HH_HmnCnt) >0
        THEN SUM(HH_SA_HmnCnt)/SUM(HH_HmnCnt)
        ELSE 0
      END,
      SUM(HH_B_HmnCnt),
      CASE 
        WHEN SUM(HH_HmnCnt) >0
        THEN SUM(HH_B_HmnCnt)/SUM(HH_HmnCnt)
        ELSE 0
      END,
      SUM(TP_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(TP_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END,
      SUM(TP_SA_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(TP_SA_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(TP_B_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(TP_B_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(IP_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(IP_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(DR_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(DR_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END
      FROM SRRTB011
      group by 
      HonbuCd,BuCd,Year,Month;
SQL;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }

  protected function run_sql5()
  {
    $sql = <<<SQL
      --月間本部別訪問件数 データ作成SQL
      INSERT INTO SRRTB013
      SELECT 
      HonbuCd,
      Year,
      Month,
      COUNT(*),
      SUM(HmnCnt),
      SUM(HH_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(HH_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END,
      SUM(HH_SA_HmnCnt),
      CASE 
        WHEN SUM(HH_HmnCnt) >0
        THEN SUM(HH_SA_HmnCnt)/SUM(HH_HmnCnt)
        ELSE 0
      END,
      SUM(HH_B_HmnCnt),
      CASE 
        WHEN SUM(HH_HmnCnt) >0
        THEN SUM(HH_B_HmnCnt)/SUM(HH_HmnCnt)
        ELSE 0
      END,
      SUM(TP_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(TP_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END,
      SUM(TP_SA_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(TP_SA_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(TP_B_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(TP_B_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(IP_HmnCnt),
      CASE 
        WHEN SUM(TP_HmnCnt) >0
        THEN SUM(IP_HmnCnt)/SUM(TP_HmnCnt)
        ELSE 0
      END,
      SUM(DR_HmnCnt),
      CASE 
        WHEN SUM(HmnCnt) >0
        THEN SUM(DR_HmnCnt)/SUM(HmnCnt)
        ELSE 0
      END
      FROM SRRTB012
      group by 
      HonbuCd,Year,Month;
SQL;
    $re = $this->db->query($sql);
    if (($err = pg_result_error($re)) !== false)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': '.$err, LOG_ERR);
      $this->continue = false;
    } 
  }
}
