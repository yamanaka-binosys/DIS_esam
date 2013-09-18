<?php
require_once(dirname(__FILE__) . '/BaseBatch.php');

/**
 * CSVファイルのロードを行う
 *
 */
class CSVLoadBatch extends BaseBatch {

  protected $csv_file_names = array(
    'VMSshk',
    'VMHtn',
    'VMHtnSshk',
    'VMTnp',
    'VMHtnShodnGyom',
    'VMTrhkAitHtnTnp',
    'VMHtnGrp',
    'VMHtnGrpShzk',
    'VMDtnShodnGyom',
    'VMDtnSshk',
  );

  public function run()
  {
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': CSVファイルロード更新処理開始', LOG_INFO);
    $this->db->query('BEGIN');
    $re = $this->_run();


    if ($re)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': CSVファイルロード更新処理成功', LOG_INFO);
      $this->db->query('COMMIT');
    }
    else
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': CSVファイルロード更新処理失敗', LOG_ERR);
      $this->db->query('ROLLBACK');
    }
  }

  protected function _run()
  {
    $this->drop_tmp_tables();
    foreach ($this->csv_file_names as $name)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': TMPテーブル'.$name.'_TMP作成開始', LOG_INFO);
      $funcname = 'init_tmp_table_' . strtolower($name);
      $this->$funcname();
      if (!$this->continue) return false;
    }
    
    Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': CSVファイルのロード開始', LOG_INFO);
    $this->load_csv();
    if (!$this->continue) return false;

    foreach ($this->csv_file_names as $name)
    {
      Logger::write('class:'.get_class($this).' method:'.__METHOD__.' line:'.__LINE__.': TMPテーブル('.$name.'_TMP)データ、前処理開始', LOG_INFO);
      $this->trim_table(strtolower($name));
      $funcname = 'update_tmp_table_' . strtolower($name);
      $this->$funcname();
      if (!$this->continue) return false;
    }

    return true;
  }

  protected function trim_table($name)
  {
	  $table_name = $name . '_tmp';
	
	  $filed_list_sql = "
			SELECT
			    att.attname AS COL_NAME,
			    typ.typname AS COL_TYPE
			FROM
			    pg_attribute att,
			    pg_stat_user_tables sut,
			    pg_type typ
			WHERE
			    att.attrelid = sut.relid
			AND att.atttypid = typ.oid
			AND att.attnum > 0
			AND sut.relname = '{$table_name}'
			AND typ.typname = 'varchar'
			ORDER BY
			    att.attnum
	  ";
	
	  $result = $this->db->query($filed_list_sql);
	
	  if ($result) {
		  $trim_sql = "UPDATE ${table_name} SET \n";
		  foreach($result as $i => $r) {
			  $field = $r['col_name'];
			  $trim_sql .= " {$field} = replace(rtrim({$field}, ' '), '''''', '') ";
			  if ($i < sizeof($result) -1) { $trim_sql .= ", \n"; }
		  }
		  $this->db->query($trim_sql);
	  }
  }

  protected function init_tmp_table_vmsshk()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmsshk_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMSshk_TMP ("
        .      "SoCd varchar(8) NOT NULL,"
        .      "SoKb varchar(1),"
        .      "ShinNm varchar(54),"
        .      "JiCd varchar(8),"
        .      "HonbuCd varchar(8),"
        .      "BuCd varchar(8),"
        .      "KaCd varchar(8),"
        .      "Shbn varchar(5),"
        .      "BuKb varchar(2),"
        .      "Sebn varchar(5),"
        .      "JSoCd varchar(8),"
        .      "HyoJun numeric,"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "UpShbn varchar(5),"
        .      "SystemDate varchar(8)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMSshk_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmhtngrp()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmhtngrp_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMHtnGrp_TMP ("
        .      "HtnGrpCd varchar(8),"
        .      "HtnGrpNm varchar(40),"
        .      "HtnGrpRnm varchar(20),"
        .      "HtnGrpKbn varchar(2),"
        .      "JoiHtnGrpCd varchar(8),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMHtnGrp_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmhtngrpshzk()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmhtngrpshzk_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMHtnGrpShzk_TMP ("
        .      "HtnGrpCd varchar(8),"
        .      "HtnCd varchar(8),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMHtnGrpShzk_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmhtnsshk()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmhtnsshk_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMHtnSshk_TMP ("
        .      "TrhkAitCd varchar(8),"
        .      "HtnCd varchar(8),"
        .      "HtnSshkKnNm varchar(80),"
        .      "HtnSshkNm varchar(80),"
        .      "HtnSshkRnm varchar(20),"
        .      "HtnSshkKbn varchar(2),"
        .      "HtnSshkYubnNo varchar(7),"
        .      "HtnSshkJush varchar(60),"
        .      "HtnSshkTelNo varchar(12),"
        .      "HtnSsJisTdofknCd varchar(2),"
        .      "HtnSsJisSkchosnCd varchar(3),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMHtnSshk_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmhtnshodngyom()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmhtnshodngyom_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMHtnShodnGyom_TMP ("
        .      "TrhkAitCd varchar(8),"
        .      "ShohnCd varchar(8),"
        .      "TntoSshkCd varchar(8),"
        .      "TntoShinSshkCd varchar(8),"
        .      "TntoKbn varchar(1),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMHtnShodnGyom_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmtrhkaithtntnp()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmtrhkaithtntnp_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMTrhkAitHtnTnp_TMP ("
        .      "TrhkAitCd varchar(8),"
        .      "TrhkAitRnm varchar(40),"
        .      "TrhkAitKbn varchar(1),"
        .      "HtnSshkKbn varchar(2),"
        .      "JoiSHSshkTrhkAitCd varchar(8),"
        .      "HtnCd varchar(8),"
        .      "PrnttHyojnTrhkskCd varchar(8),"
        .      "PrnttHyojnTrhkskCd2 varchar(8),"
        .      "PrnttHyojnTrhkskCd3 varchar(8),"
        .      "PrnttHyojnTrhkskCd4 varchar(8),"
        .      "PrnttHyojnTrhkskCd5 varchar(8),"
        .      "HtnGyotiKbn varchar(3),"
        .      "TrkmKnrKbn varchar(2),"
        .      "HyojJnjo numeric,"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMTrhkAitHtnTnp_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmtnp()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmtnp_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMTnp_TMP ("
        .      "TrhkAitCd varchar(8),"
        .      "HtnCd varchar(8),"
        .      "TnpNo numeric,"
        .      "TnmKnNm varchar(80),"
        .      "TnpNm varchar(40),"
        .      "TnpRnm varchar(20),"
        .      "HtnTnpNo varchar(8),"
        .      "TnpYubnNo varchar(7),"
        .      "TnpJush varchar(60),"
        .      "TnpTelNo varchar(12),"
        .      "TnpJisTdofknCd varchar(2),"
        .      "TnpShkchsnCd varchar(3),"
        .      "PrnttHyojnTrhkskCd varchar(8),"
        .      "EntryDate varchar(8),"
        .      "PrnttHyojnTrhkskCd2 varchar(8),"
        .      "EntryDate2 varchar(8),"
        .      "PrnttHyojnTrhkskCd3 varchar(8),"
        .      "EntryDate3 varchar(8),"
        .      "PrnttHyojnTrhkskCd4 varchar(8),"
        .      "EntryDate4 varchar(8),"
        .      "PrnttHyojnTrhkskCd5 varchar(8),"
        .      "EntryDate5 varchar(8),"
        .      "PrnttTrhkskKbn varchar(2),"
        .      "PrnttTrhkskKnNm varchar(40),"
        .      "PrnttTrhkskKnRnm varchar(16),"
        .      "PrnttTdkskKbn varchar(1),"
        .      "PrnttGyoshCd varchar(2),"
        .      "PrnttGyotiCd varchar(2),"
        .      "PrnttHnshCd varchar(8),"
        .      "TnpIdo varchar(10),"
        .      "TnpKido varchar(10),"
        .      "HisuCntr varchar(1),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMTnp_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmdtnshodngyom()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmdtnshodngyom_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMDtnShodnGyom_TMP ("
        .      "TrhkAitCd varchar(8),"
        .      "ShohnCd varchar(8),"
        .      "TntoSshkCd varchar(8),"
        .      "TntoShinSshkCd varchar(8),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")";
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMDtnShodnGyom_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmdtnsshk()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmdtnsshk_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMDtnSshk_TMP ("
        .      "TrhkAitCd varchar(8),"
        .      "DtnCd varchar(8),"
        .      "DtnSshkKnNm varchar(40),"
        .      "DtnSshkNm varchar(40),"
        .      "DtnSshkRnm varchar(20),"
        .      "DtnSshkKbn varchar(2),"
        .      "ChkHnJyKbn varchar(1),"
        .      "ChkHnJyJduKishDate varchar(8),"
        .      "ChkJuKbn varchar(1),"
        .      "DtnSshkYubnNo varchar(7),"
        .      "DtnSshkJush varchar(60),"
        .      "DtnSshkTelNo varchar(12),"
        .      "DtnSsJisTdofknCd varchar(2),"
        .      "DtnSsJisSkchosnCd varchar(3),"
        .      "JoiDtnSshkCd varchar(8),"
        .      "WisdomDtnCd varchar(5),"
        .      "KyotsuTrhkskCd varchar(8),"
        .      "HkukskDtnSshkCd varchar(8),"
        .      "PlnHnbiDtFlg varchar(1),"
        .      "PlnShreDtFlg varchar(1),"
        .      "PlnHchuDtFlg varchar(1),"
        .      "PlnSikyDtFlg varchar(1),"
        .      "PlnStszkKishDate varchar(8),"
        .      "PlnStszkYkDate varchar(8),"
        .      "MDDtnFlg varchar(1),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMDtnSshk_TMP";
    $this->db->query($sql);
  }

  protected function init_tmp_table_vmhtn()
  {
    $sql = "SELECT COUNT(*) FROM pg_tables WHERE schemaname='public' AND tablename='vmhtn_tmp'";
    $count = (int)array_pop(array_pop($this->db->query($sql)));

    if ($count == 0) {
      $sql = "CREATE TABLE VMHtn_TMP ("
        .      "HtnCd varchar(8),"
        .      "HtnKnNm varchar(40),"
        .      "HtnNm varchar(40),"
        .      "HtnRnm varchar(20),"
        .      "HtnGyotiKbn varchar(3),"
        .      "TrkmKnrKbn varchar(2),"
        .      "HtnYubnNo varchar(7),"
        .      "HtnJush varchar(60),"
        .      "HtnTelNo varchar(12),"
        .      "HtnJisTdofknCd varchar(2),"
        .      "HtnJisSkchosnCd varchar(3),"
        .      "ShktnYubi varchar(1),"
        .      "BkHsiKbn varchar(1),"
        .      "BkHsiStrNngts varchar(6),"
        .      "BkHsiEndNngts varchar(6),"
        .      "HnskhHtnFlg varchar(1),"
        .      "HtnRank varchar(1),"
        .      "CreateDate varchar(8),"
        .      "UpdateDate varchar(8),"
        .      "DeleteDate varchar(8),"
        .      "LastUpdateShainNo varchar(5),"
        .      "TimeStamp varchar(26)"
        .    ")"
        ;
      $this->db->query($sql);
    }

    $sql = "TRUNCATE TABLE VMHtn_TMP";
    $this->db->query($sql);
  }

  protected function drop_tmp_tables()
  {
    foreach ($this->csv_file_names as $name)
    {
      $sql = "DROP TABLE " . $name . "_TMP";
      $this->db->query($sql);
    }
  }

  protected function load_csv()
  {
    foreach ($this->csv_file_names as $name)
    {
      $sql = "COPY ".$name."_TMP FROM '" . $this->csv_file_path($name) . "' WITH (FORMAT CSV)";
      $this->db->query($sql);
    }
  }


  protected function update_tmp_table_vmsshk()
  {
    $sql = "
			UPDATE VMSshk_TMP SET 
				SoCd=substr(SoCd,1,5),
				JiCd=substr(JiCd,1,5),
				HonbuCd=ifEmptyToX(substr(HonbuCd,1,5), 5),
				BuCd=ifEmptyToX(substr(BuCd,1,5), 5),
				KaCd=ifEmptyToX(substr(KaCd,1,5), 5),
				Shbn=ifEmptyToX(Shbn, 5),
				JSoCd=substr(JSoCd,1,5),
				ShinNm=CASE WHEN strpos(ShinNm, ' ') > 0 THEN substr(ShinNm,0,strpos(ShinNm, ' ')) ELSE ShinNm END
    ";
    $this->db->query($sql);
  }

  protected function update_tmp_table_vmhtn() 
  {
    $sql = "DELETE FROM VMHtn_TMP WHERE htngyotikbn IN ('060','120','130','140','150','160')";
    $this->db->query($sql);
  }

  protected function update_tmp_table_vmhtnsshk() 
  {
		$sql = "DELETE FROM VMHtnSshk_TMP t1 WHERE NOT EXISTS (SELECT * FROM VMHtn_TMP t2 WHERE t1.htncd = t2.htncd) ";
		$this->db->query($sql);
  }

  protected function update_tmp_table_vmtnp()
  {
		$sql = "DELETE FROM VMTnp_TMP t1 WHERE NOT EXISTS (SELECT * FROM VMHtn_TMP t2 WHERE t1.htncd = t2.htncd) ";
		$this->db->query($sql);
  }
  
  protected function update_tmp_table_vmhtnshodngyom() {
		// $sql = "
		// DELETE FROM VMHtnShodnGyom_TMP t1
		// WHERE NOT EXISTS (SELECT * FROM VMHtnSshk_TMP t2 WHERE t1.trhkaitcd = t2.trhkaitcd) AND
		// NOT EXISTS (SELECT * FROM VMTnp_TMP t2 WHERE t1.trhkaitcd = t2.trhkaitcd)
		//    	";
		// 	  $this->db->query($sql);
  }

  protected function update_tmp_table_vmtrhkaithtntnp() {
		// $sql = "
		// DELETE FROM vmtrhkaithtntnp_TMP t1
		// WHERE NOT EXISTS (SELECT * FROM VMHtnSshk_TMP t2 WHERE t1.trhkaitcd = t2.trhkaitcd) AND
		// NOT EXISTS (SELECT * FROM VMTnp_TMP t2 WHERE t1.trhkaitcd = t2.trhkaitcd)
		//    	";
		// 	  $this->db->query($sql);
  }
	
  protected function update_tmp_table_vmhtngrp(){}
  protected function update_tmp_table_vmhtngrpshzk(){}
  
  protected function update_tmp_table_vmdtnshodngyom(){}
  protected function update_tmp_table_vmdtnsshk(){}

  protected function csv_file_path($file_name, $ext = 'csv')
  {
    return CSV_DIR . '/' . $file_name . '.' . $ext;
  }

  protected function query($q, $class, $method, $line, $stop = true)
  {
    $re = $this->db->query($q);
    if (!$re and ($err = pg_result_error($this->db->result())) !== false)
    {
      Logger::write('class:'.$class.' method:'.$method.' line:'.$line.': '.$err, LOG_ERR);
      if ($stop)
      {
        $this->continue = false;
      }
      return false;
    } 

    return true;
  }
}
