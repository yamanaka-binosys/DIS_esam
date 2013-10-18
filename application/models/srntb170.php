<?php

class Srntb170 extends CI_Model {
	
    public $error_date;
    
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	function get_srntb170_data($condition_data){
		log_message('debug',"========== " . __METHOD__ . " start ==========");
		log_message('debug',"\$shbn = " . $condition_data['shbn']);
		log_message('debug',"\$jyohonum = " . $condition_data['jyohonum']);
		log_message('debug',"\$edbn = " . $condition_data['edbn']);
		
		// 引数より検索条件を取得
		// 情報ナンバー、枝番、社番
		if( ! isset($condition_data['jyohonum']) OR ! isset($condition_data['edbn']) OR ! isset($condition_data['shbn']))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		$shbn = $condition_data['shbn'];
		$jyohonum = $condition_data['jyohonum'];
//		$edbn = $condition_data['edbn'];
		$edbn = sprintf('%02d', $condition_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT jyohonum,";          // 情報ナンバー
		$sql .= "        edbn,";              // 枝番
		$sql .= "        shbn,";              // 社番
		$sql .= "        ymd,";               // 日付
		$sql .= "        sthm,";              // 開始時刻
		$sql .= "        edhm,";              // 終了時刻
		$sql .= "        sagyoniyo,";         // 作業内容
		$sql .= "        sntsagyo,";          // その他の作業内容
		$sql .= "        znkakninshbn,";      // 前確認者
		$sql .= "        createdate,";        // 登録日付
		$sql .= "        updatedate,";        // 更新日付
		$sql .= "        yobim01,";           // 予備１（文字１）
		$sql .= "        yobim02,";           // 予備２（文字２）
		$sql .= "        kekka,";             // 結果情報
		$sql .= "        recode_flg";         // 登録フラグ
		$sql .= " FROM srntb170";
		$sql .= " WHERE jyohonum = '{$jyohonum}'";
		$sql .= "        AND edbn = '{$edbn}'";
		$sql .= "        AND shbn = '{$shbn}'";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== " . __METHOD__ . " end ==========");
		return $result_data;
	}
	
	function update_srntb170_data($record_data)
	{
		log_message('debug',"========== " . __METHOD__ . " start ==========");
		if (!isset($record_data['jyohonum']))
		{
			log_message('error', 'cannot update without jyohonum');
			return false;
		}
		unset($record_data['edbn']);
		$jyohonum = $record_data['jyohonum'];
		unset($record_data['jyohonum']);
		return $this->db->update('srntb170', $record_data, array('jyohonum' => $jyohonum));
   		log_message('debug',"========== " . __METHOD__ . " end ==========");

	}
	
	function insert_srntb170_data($record_data)
	{
    	log_message('debug',"========== " . __METHOD__ . " start ==========");
		unset($record_data['jyohonum']);
		unset($record_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		$name_string = "";
		$value_string = "";
		
		foreach ($record_data as $key => $value) {
			$name_string .= $key . ",";
			$value_string .= "'" . $value . "',";
		}
		
		$sql .= " INSERT INTO srntb170(";
//		$sql .= substr($name_string,0,-1);
		$sql .= $name_string;
		$sql .= "createdate";
		$sql .= ") VALUES (";
//		$sql .= substr($value_string,0,-1);
		$sql .= $value_string;
		$sql .= "'".date("Ymd")."'";
		$sql .= ")";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql);
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
            
		}
   		log_message('debug',"========== " . __METHOD__ . " end ==========");

	}
	
	function delete_srntb170_data($jyohonum,$edbn){
		log_message('debug',"========== " . __METHOD__ . " start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srntb170";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
   		log_message('debug',"========== " . __METHOD__ . " end ==========");

	}
	
	function get_schedule_data($jyohonum,$edbn){
		log_message('debug',"========== " . __METHOD__ . " start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  sthm,";
		$sql .= "  '内勤' as aitesknm";
		$sql .= " FROM srntb170";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " AND edbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
   		log_message('debug',"========== " . __METHOD__ . " end ==========");

		return $result_data;
	}
	
   //
    // 既に登録されている同日同時刻のデータの有無を確認する。
    //
    function st_et_check($shbn, $startdatetime, $enddatetime) {

        log_message('debug', "========== " . __METHOD__ . " (" . $shbn . ", " . $startdatetime . ", " . $enddatetime . ") start ==========");
	
        // 初期化
        $sql = ""; // sql_regcase文字列
        $query = NULL; // SQL実行結果
        $result_data = NULL; // 戻り
        $this->error_date = null;   // エラー日付初期化

        // SQL文作成
        $sql .= " SELECT shbn, ymd, sthm, edhm FROM srntb170 WHERE shbn = ? AND ymd = ? ";
        log_message('debug', "\$sql = $sql");
        // SQL実行
        $query = $this->db->query($sql, array($shbn, date("Ymd", $startdatetime)));
        // 取得確認
        log_message('debug', "\$query->num_rows() = " . $query->num_rows());
        if ($query->num_rows() > 0) {
            $result_data = $query->result_array();
            foreach ($result_data as $rec) {
                $rec_start_time = strtotime($rec['ymd'] . 't' . $rec['sthm'] . '00');   // DBに登録されている開始日付時刻
                $rec_end_time = strtotime($rec['ymd'] . 't' . $rec['edhm'] . '00');     // DBに登録されている終了日付時刻
                log_message('debug', "\$rec_start_time = " . $rec_start_time);
                log_message('debug', "\$rec_end_time = " . $rec_end_time);
                log_message('debug', "\$startdatetime = " . $startdatetime);
                log_message('debug', "\$enddatetime = " . $enddatetime);
                // 登録済み開始時刻 < 入力開始時刻 < 登録済み終了時刻 
                if ($rec_start_time < $startdatetime && $startdatetime < $rec_end_time) {
                    log_message('debug', "\$startdatetime overlap " );
                    $this->error_date = $startdatetime;
                    return TRUE;
                }
                // 登録済み開始時刻 < 入力終了時刻 < 登録済み終了時刻 
                if ($rec_start_time < $enddatetime && $enddatetime < $rec_end_time) {
                    log_message('debug', "\$enddatetime overlap " );
                    $this->error_date = $enddatetime;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
	
	
}

?>
