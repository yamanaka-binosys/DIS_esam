<?php

class Common extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 指定された範囲（開始日～終了日）の日報情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $start_date 開始日
	 * @param   string $end_date 終了日
	 * @return  array
	 */
	function get_calendar_result_data($shbn,$start_date,$end_date)
	{
		log_message('debug',"========== Common get_calendar_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$start_date = $start_date");
		log_message('debug',"\$end_date = $end_date");
		// 初期化
		$res = FALSE;
		$sql = "";
		$result_data = array();
		// SQL文作成
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.aitesknm AS viewnm,'本部' AS action_type, '' AS opt";
		$sql .= " FROM srntb010 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb010";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.aitesknm AS viewnm,'店舗' AS action_type, '' AS opt";
		$sql .= " FROM srntb020 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb020";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.aitesknm AS viewnm,'代理店' AS action_type, '' AS opt";
		$sql .= " FROM srntb030 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb030";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.sagyoniyo AS viewnm,'内勤' AS action_type, BASE.sntsagyo AS opt";
		$sql .= " FROM srntb040 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb040";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,'業者' AS viewnm,'業者' AS action_type, '' AS opt";
		$sql .= " FROM srntb060 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb060";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_calendar_result_data end ==========");
			return NULL;
		}
		// 取得した結果を配列に格納
		log_message('debug',"========== Common get_calendar_result_data end ==========");
		return $row;
	}
	
	/**
	 * 指定された範囲（開始日～終了日）の予定情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $start_date 開始日
	 * @param   string $end_date 終了日
	 * @return  array
	 */
	function get_calendar_plan_data($shbn,$start_date,$end_date)
	{
		log_message('debug',"========== Common get_calendar_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$start_date = $start_date");
		log_message('debug',"\$end_date = $end_date");
		// 初期化
		$res = FALSE;
		$sql = "";
		$result_data = array();
		// SQL文作成
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.aitesknm AS viewnm,'本部' AS action_type, '' AS opt";
		$sql .= " FROM srntb110 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb110";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.aitesknm AS viewnm,'店舗' AS action_type, '' AS opt";
		$sql .= " FROM srntb120 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb120";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.aitesknm AS viewnm,'代理店' AS action_type, '' AS opt";
		$sql .= " FROM srntb130 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb130";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,BASE.sagyoniyo AS viewnm,'内勤' AS action_type, BASE.sntsagyo AS opt";
		$sql .= " FROM srntb140 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb140";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " UNION ALL";
		$sql .= " SELECT BASE.shbn,BASE.ymd,BASE.sthm,'業者' AS viewnm,'業者' AS action_type, '' AS opt";
		$sql .= " FROM srntb160 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb160";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd BETWEEN '{$start_date}' AND '{$end_date}'";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_calendar_plan_data end ==========");
			return NULL;
		}
		log_message('debug',"========== Common get_calendar_plan_data end ==========");
		return $row;
	}
	
	/**
	 * 指定された範囲（開始日～終了日）の予定情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $start_date 開始日
	 * @param   string $end_date 終了日
	 * @return  array
	 */
	function get_header_plan_data($shbn,$select_date)
	{
		log_message('debug',"========== Common get_header_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		// 初期化
		$res = FALSE;
		$sql = "";
		$result_data = array();
		// SQL文作成
		$sql .= " SELECT shbn,ymd,sthm,edhm,aitesknm,'本部' AS kubun FROM srntb110";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb110 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " UNION";
		$sql .= " SELECT shbn,ymd,sthm,edhm,aitesknm,'店舗' AS kubun FROM srntb120";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb120 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " UNION";
		$sql .= " SELECT shbn,ymd,sthm,edhm,aitesknm,'代理店' AS kubun FROM srntb130";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb130 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " UNION";
		$sql .= " SELECT shbn,ymd,sthm,edhm,'' AS aitesknm,'内勤' AS kubun FROM srntb140";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb140 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_header_plan_data end ==========");
			return NULL;
		}
		log_message('debug',"========== Common get_header_plan_data end ==========");
		return $row;
	}
	
	/**
	 * 指定された範囲（開始日～終了日）の予定情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_select_plan_data($shbn = NULL,$select_date = NULL)
	{
		log_message('debug',"========== Common get_select_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb110' AS dbname";
		$sql .= " FROM srntb110 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb110";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb120' AS dbname";
		$sql .= " FROM srntb120 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb120";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb130' AS dbname";
		$sql .= " FROM srntb130 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb130";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb140' AS dbname";
		$sql .= " FROM srntb140 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb140";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb160' AS dbname";
		$sql .= " FROM srntb160 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb160";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_select_plan_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_select_plan_data end ==========");
		return $result_data;
	}
	/**
	 * 指定された日の予定情報を取得
	 * ・本部、店舗、代理店、業者、内勤の５テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_select_day_plan_data($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_select_day_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date)){
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$count = 0;
		$sql = "";
		$result_data = FALSE;
		
		// SQL文作成
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb110' AS dbname FROM srntb110";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb120' AS dbname FROM srntb120";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb130' AS dbname FROM srntb130";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb140' AS dbname FROM srntb140";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb160' AS dbname FROM srntb160";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
//		$row = $query->result_array();
		$result_data = $query->result_array();
		log_message('debug',"========== Common get_select_day_plan_data end ==========");
		return $result_data;
	}
	
	function get_select_day_result_data($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_select_day_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date)){
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$count = 0;
		$sql = "";
		$result_data = FALSE;
		
		// SQL文作成
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb010' AS dbname FROM srntb010";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb020' AS dbname FROM srntb020";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb030' AS dbname FROM srntb030";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb040' AS dbname FROM srntb040";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,'srntb060' AS dbname FROM srntb060";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
//		$row = $query->result_array();
		$result_data = $query->result_array();
		log_message('debug',"========== Common get_select_day_result_data end ==========");
		return $result_data;
	}
	
	/**
	 * テンプテーブルに一時保管されていた情報を取得
	 * ・本部、店舗、代理店、業者、内勤の５テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_select_tmp_plan_data($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_select_tmp_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb110' AS dbname FROM tmpsrntb110";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb120' AS dbname FROM tmpsrntb120";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb130' AS dbname FROM tmpsrntb130";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb140' AS dbname FROM tmpsrntb140";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb160' AS dbname FROM tmpsrntb160";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " ORDER BY ymd,sthm,edbn;";
		/*
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb110' AS dbname FROM tmpsrntb110";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb120' AS dbname FROM tmpsrntb120";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb130' AS dbname FROM tmpsrntb130";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb140' AS dbname FROM tmpsrntb140";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb160' AS dbname FROM tmpsrntb160";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " ORDER BY ymd,sthm;";
		*/
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row)){
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_select_tmp_plan_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_select_tmp_plan_data end ==========");
		return $result_data;
	}

	/**
	 * テンプテーブルに一時保管されていた情報を削除
	 * ・本部、店舗、代理店、業者、内勤の５テーブル
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_del_tmp_plan_data($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_del_tmp_plan_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " DELETE FROM tmpsrntb110";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb120";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb130";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb140";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb160";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row)){
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_del_tmp_plan_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_del_tmp_plan_data end ==========");
		return $result_data;
	}

	/**
	 * テンプテーブルに一時保管されていた情報を取得
	 * ・本部、店舗、代理店、業者、内勤の５テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_select_tmp_result_data($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_select_tmp_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb010' AS dbname FROM tmpsrntb010";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb020' AS dbname FROM tmpsrntb020";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb030' AS dbname FROM tmpsrntb030";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb040' AS dbname FROM tmpsrntb040";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,edbn,shbn,ymd,sthm,'srntb060' AS dbname FROM tmpsrntb060";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " ORDER BY ymd,sthm,edbn;";
		/*
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb010' AS dbname FROM tmpsrntb010";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb020' AS dbname FROM tmpsrntb020";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb030' AS dbname FROM tmpsrntb030";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb040' AS dbname FROM tmpsrntb040";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb060' AS dbname FROM tmpsrntb060";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " ORDER BY ymd,sthm;";
		*/
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row)){
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_select_tmp_result_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_select_tmp_result_data end ==========");
		return $result_data;
	}
	
	function get_plan_data_for_result($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_plan_data_for_result start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb010' AS dbname FROM srntb110";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb020' AS dbname FROM srntb120";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb030' AS dbname FROM srntb130";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb040' AS dbname FROM srntb140";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb060' AS dbname FROM srntb160";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row)){
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_plan_data_for_result end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_plan_data_for_result end ==========");
		return $result_data;
	}
	
	/**
	 * テンプテーブルに一時保管されていた情報を削除
	 * ・本部、店舗、代理店、業者、内勤の５テーブル
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_del_tmp_result_data($shbn = NULL,$select_date = NULL){
		log_message('debug',"========== Common get_del_tmp_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " DELETE FROM tmpsrntb010";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb020";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb030";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb040";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " DELETE FROM tmpsrntb060";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row)){
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_del_tmp_result_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_del_tmp_result_data end ==========");
		return $result_data;
	}	
	
	/**
	 * 指定された範囲（開始日～終了日）の予定情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_del_select_data($select_data = NULL)
	{
		log_message('debug',"========== Common get_del_select_data start ==========");
		log_message('debug',"select_data start_date = " . $select_data['start_date']);
		log_message('debug',"select_data end_date = " . $select_data['end_date']);
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " SELECT jyohonum,MAX(edbn) AS edbn,shbn,ymd,sthm,aitesknm,'本部' AS kubun";
		$sql .= " FROM srntb110";
		$sql .= " WHERE ymd BETWEEN '{$select_data['start_date']}' AND '{$select_data['end_date']}'";
		if ( ! is_null($select_data['shbn']) AND is_null($select_data['name'])) {
			$sql .= " AND shbn = '{$select_data['shbn']}'";
		}else if(is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%')";
		}else if( ! is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND (shbn = '{$select_data['shbn']}' OR shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%'))";
		}
		$sql .= " GROUP BY jyohonum,edbn,ymd,shbn,sthm,aitesknm,kubun";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(edbn) AS edbn,shbn,ymd,sthm,aitesknm,'店舗' AS kubun";
		$sql .= " FROM srntb120";
		$sql .= " WHERE ymd BETWEEN '{$select_data['start_date']}' AND '{$select_data['end_date']}'";
		if ( ! is_null($select_data['shbn']) AND is_null($select_data['name'])) {
			$sql .= " AND shbn = '{$select_data['shbn']}'";
		}else if(is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%')";
		}else if( ! is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND (shbn = '{$select_data['shbn']}' OR shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%'))";
		}
		$sql .= " GROUP BY jyohonum,edbn,ymd,shbn,sthm,aitesknm,kubun";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(edbn) AS edbn,shbn,ymd,sthm,aitesknm,'代理店' AS kubun";
		$sql .= " FROM srntb130";
		$sql .= " WHERE ymd BETWEEN '{$select_data['start_date']}' AND '{$select_data['end_date']}'";
		if ( ! is_null($select_data['shbn']) AND is_null($select_data['name'])) {
			$sql .= " AND shbn = '{$select_data['shbn']}'";
		}else if(is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%')";
		}else if( ! is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND (shbn = '{$select_data['shbn']}' OR shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%'))";
		}
		$sql .= " GROUP BY jyohonum,edbn,ymd,shbn,sthm,aitesknm,kubun";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(edbn) AS edbn,shbn,ymd,sthm,'' AS aitesknm,'内勤' AS kubun";
		$sql .= " FROM srntb140";
		$sql .= " WHERE ymd BETWEEN '{$select_data['start_date']}' AND '{$select_data['end_date']}'";
		if ( ! is_null($select_data['shbn']) AND is_null($select_data['name'])) {
			$sql .= " AND shbn = '{$select_data['shbn']}'";
		}else if(is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%')";
		}else if( ! is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND (shbn = '{$select_data['shbn']}' OR shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%'))";
		}
		$sql .= " GROUP BY jyohonum,edbn,ymd,shbn,sthm,aitesknm,kubun";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(edbn) AS edbn,shbn,ymd,sthm,'' AS aitesknm,'業者' AS kubun";
		$sql .= " FROM srntb160";
		$sql .= " WHERE ymd BETWEEN '{$select_data['start_date']}' AND '{$select_data['end_date']}'";
		if ( ! is_null($select_data['shbn']) AND is_null($select_data['name'])) {
			$sql .= " AND shbn = '{$select_data['shbn']}'";
		}else if(is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%')";
		}else if( ! is_null($select_data['shbn']) AND ! is_null($select_data['name'])){
			$sql .= " AND (shbn = '{$select_data['shbn']}' OR shbn IN (SELECT shbn FROM sgmtb010 WHERE shinnm LIKE '%{$select_data['name']}%'))";
		}
		$sql .= " GROUP BY jyohonum,edbn,ymd,shbn,sthm,aitesknm,kubun";
				$sql .= " ORDER BY ymd,sthm";
		$sql .= ";";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_del_select_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_del_select_data end ==========");
		return $result_data;
	}
	
	/**
	 * 指定された範囲（開始日～終了日）の予定情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $start_date 開始日
	 * @param   string $end_date 終了日
	 * @return  array
	 */
	function get_header_result_data($shbn,$select_date)
	{
		log_message('debug',"========== Common get_header_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		// 初期化
		$res = FALSE;
		$sql = "";
		$result_data = array();
		// SQL文作成
		$sql .= " SELECT shbn,ymd,sthm,edhm,aitesknm,'本部' AS kubun FROM srntb010";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb010 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " UNION";
		$sql .= " SELECT shbn,ymd,sthm,edhm,aitesknm,'店舗' AS kubun FROM srntb020";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb020 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " UNION";
		$sql .= " SELECT shbn,ymd,sthm,edhm,aitesknm,'代理店' AS kubun FROM srntb030";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb030 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " UNION";
		$sql .= " SELECT shbn,ymd,sthm,edhm,'' AS aitesknm,'内勤' AS kubun FROM srntb040";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " AND edbn = (select max(edbn) from srntb040 WHERE shbn = '{$shbn}' AND ymd = '{$select_date}')";
		$sql .= " ORDER BY ymd,sthm;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_header_result_data end ==========");
			return NULL;
		}
		log_message('debug',"========== Common get_header_result_data end ==========");
		return $row;
	}
	
	/**
	 * 指定された範囲（開始日～終了日）の予定情報を取得
	 * ・本部、店舗、代理店、社内の４テーブルから取得
	 * 
	 * @access  public
	 * @param   string $shbn 社番
	 * @param   string $select_date 対象日付
	 * @return  array
	 */
	function get_select_result_data($shbn = NULL,$select_date = NULL)
	{
		log_message('debug',"========== Common get_select_result_data start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_date = $select_date");
		if(is_null($shbn) OR is_null($select_date))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$res = FALSE;
		$count = 0;
		$sql = "";
		$result_data['data'] = array();
		$result_data['count'] = $count;
		
		// SQL文作成
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb010' AS dbname";
		$sql .= " FROM srntb010 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb010";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb020' AS dbname";
		$sql .= " FROM srntb020 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb020";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb030' AS dbname";
		$sql .= " FROM srntb030 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb030";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb040' AS dbname";
		$sql .= " FROM srntb040 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb040";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " UNION";
		$sql .= " SELECT BASE.jyohonum, EDA.edbn,BASE.shbn,BASE.ymd,BASE.sthm,'srntb060' AS dbname";
		$sql .= " FROM srntb060 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int))  AS edbn FROM srntb060";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " ORDER BY ymd,sthm;";
		/*
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb010' AS dbname FROM srntb010";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb020' AS dbname FROM srntb020";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb030' AS dbname FROM srntb030";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb040' AS dbname FROM srntb040";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " UNION";
		$sql .= " SELECT jyohonum,MAX(CAST(edbn as int)) AS edbn,shbn,ymd,sthm,'srntb060' AS dbname FROM srntb060";
		$sql .= " WHERE shbn = '{$shbn}' AND ymd = '{$select_date}'";
		$sql .= " GROUP BY jyohonum,shbn,ymd,sthm";
		$sql .= " ORDER BY ymd,sthm;";
		*/
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			log_message('debug',"========== Common get_select_result_data end ==========");
			return $result_data;
		}
		// 結果から取得件数を取得
		$count = $query->num_rows();
		// 戻り値に格納
		$result_data['data'] = $row;
		$result_data['count'] = $count;
		log_message('debug',"========== Common get_select_result_data end ==========");
		return $result_data;
	}
	
	function get_result_count($shbn,$select_day){
		log_message('debug',"========== Common get_result_count start ==========");
		log_message('debug',"\$shbn = $shbn");
		log_message('debug',"\$select_day = $select_day");
		
		// 初期化
		$res = FALSE;
		$sql = "";
		
		$sql .= " SELECT";
		$sql .= " honbu.count as hon_cnt,";
		$sql .= " tenpo.count as ten_cnt,";
		$sql .= " dairi.count as dai_cnt,";
		$sql .= " office.count as off_cnt,";
		$sql .= " gyousya.count as gyo_cnt";
		$sql .= " FROM";
		$sql .= " (SELECT count(*) from srntb010 WHERE shbn = '{$shbn}' AND ymd = '{$select_day}') honbu,";
		$sql .= " (SELECT count(*) from srntb020 WHERE shbn = '{$shbn}' AND ymd = '{$select_day}') tenpo,";
		$sql .= " (SELECT count(*) from srntb030 WHERE shbn = '{$shbn}' AND ymd = '{$select_day}') dairi ,";
		$sql .= " (SELECT count(*) from srntb040 WHERE shbn = '{$shbn}' AND ymd = '{$select_day}') office,";
		$sql .= " (SELECT count(*) from srntb060 WHERE shbn = '{$shbn}' AND ymd = '{$select_day}') gyousya";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 結果を配列に格納
		$res = $query->result_array();
		
		log_message('debug',"========== Common get_result_count end ==========");
		return $res;
	}
	
	/**
	 * 部下のスケジュールを取得
	 */
	function get_followers_schedule($shbn) {
		$sql = "
			WITH follower_shbn AS (
				SELECT shbn FROM sgmtb010 WHERE unitshbn = ? AND shbn <> ?
			)
			SELECT
				sthm, schedule.shbn, follower.shinnm, ymd, doing
			FROM
			(
				-- 本部
				SELECT ymd, sthm, shbn, aitesknm as doing FROM 
				srntb110 base INNER JOIN 
				(SELECT jyohonum, max(edbn) as edbn FROM srntb110 GROUP BY jyohonum) ed
				ON base.jyohonum = ed.jyohonum AND base.edbn = ed.edbn
				WHERE shbn IN (SELECT shbn FROM follower_shbn)
				AND ymd::int = to_char(now(), 'yyyyMMdd')::integer
				UNION
				-- 店舗
				SELECT ymd, sthm, shbn, aitesknm as doing FROM 
				srntb120 base INNER JOIN 
				(SELECT jyohonum, max(edbn) as edbn  FROM srntb120 GROUP BY jyohonum) ed
				ON base.jyohonum = ed.jyohonum AND base.edbn = ed.edbn
				WHERE shbn IN (SELECT shbn FROM follower_shbn)
				AND ymd::int = to_char(now(), 'yyyyMMdd')::integer
				UNION
				-- 代理店
				SELECT ymd, sthm, shbn, aitesknm as doing FROM
				srntb130 base INNER JOIN 
				(SELECT jyohonum, max(edbn) as edbn  FROM srntb130 GROUP BY jyohonum) ed
				ON base.jyohonum = ed.jyohonum AND base.edbn = ed.edbn
				WHERE shbn IN (SELECT shbn FROM follower_shbn)
				AND ymd::int = to_char(now(), 'yyyyMMdd')::integer
				UNION
				-- 内勤
				SELECT ymd, sthm, shbn, sgmtb030.ichiran as doing FROM 
				(srntb140 base INNER JOIN 
				(SELECT jyohonum, max(edbn) as edbn  FROM srntb140 GROUP BY jyohonum) ed
				ON base.jyohonum = ed.jyohonum AND base.edbn = ed.edbn) srntb140,
				sgmtb030
				WHERE srntb140.sagyoniyo = sgmtb030.kbncd AND sgmtb030.kbnid = ? AND
				shbn IN (SELECT shbn FROM follower_shbn)
				AND ymd::int = to_char(now(), 'yyyyMMdd')::integer
				UNION
				-- 業者
				SELECT ymd, sthm, shbn, '業者' as doing FROM 
				srntb160 base INNER JOIN 
				(SELECT jyohonum, max(edbn) as edbn  FROM srntb160 GROUP BY jyohonum) ed
				ON base.jyohonum = ed.jyohonum AND base.edbn = ed.edbn
				WHERE shbn IN (SELECT shbn FROM follower_shbn)
				AND ymd::int = to_char(now(), 'yyyyMMdd')::integer
			) schedule,
			sgmtb010 follower
			WHERE schedule.shbn = follower.shbn
			ORDER BY follower.shbn, schedule.sthm ASC
		";
		
		$query = $this->db->query($sql, array($shbn, $shbn, '008'));
		return $query->result_array();
	}


	function get_summary($shbn, $select_month = NULL, $mode) {
		if(is_null($select_month))
		{
			$year = date("Y");
			$month = date("m");
		}else{
			$year = substr($select_month,0,4);
			$month = substr($select_month,4,2);
		}

		$sql = "
SELECT 'honbu' as name, count(*) AS num FROM srntb010 BASE INNER JOIN (SELECT jyohonum, MAX(CAST(edbn as int)) AS edbn FROM srntb010 GROUP BY jyohonum ) EDA ON BASE.jyohonum = EDA.jyohonum and CAST(BASE.edbn as int) = EDA.edbn WHERE shbn = '${shbn}' AND ymd ~ '${year}${month}' AND (hold_flg = '0' OR hold_flg IS NULL)

UNION ALL 

SELECT 'tenpo' as name, count(*) AS num FROM srntb020 BASE INNER JOIN (SELECT jyohonum, MAX(CAST(edbn as int)) AS edbn FROM srntb020 GROUP BY jyohonum ) EDA ON BASE.jyohonum = EDA.jyohonum and CAST(BASE.edbn as int) = EDA.edbn WHERE shbn = '${shbn}' AND ymd ~ '${year}${month}'  AND (hold_flg = '0' OR hold_flg IS NULL)

UNION ALL 

SELECT 'dairi' as name, count(*) AS num  FROM srntb030 BASE INNER JOIN (SELECT jyohonum, MAX(CAST(edbn as int)) AS edbn FROM srntb030 GROUP BY jyohonum ) EDA ON BASE.jyohonum = EDA.jyohonum and CAST(BASE.edbn as int) = EDA.edbn WHERE shbn = '${shbn}' AND ymd ~ '${year}${month}'  AND (hold_flg = '0' OR hold_flg IS NULL)
		";
		$query = $this->db->query($sql);
		$array = $query->result_array();
		$d = array();
		foreach($array as $row) {
			$d[$row['name']] = $row['num'];
 		}
 		return $d;

	}
	
}

?>
