<?php

class Srntb110 extends CI_Model {
	
    public $error_date;         // エラーが発生した日付を格納
    
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	function get_srntb110_data($condition_data){
		log_message('debug',"========== srntb110 get_srntb110_data start ==========");
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
		$sql .= " SELECT *";
		$sql .= " FROM srntb110";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= "        AND edbn = '{$edbn}'";
		//$sql .= "        AND shbn = '{$shbn}'";
		$sql .= " WHERE jyohonum = ?";
		$sql .= "        AND edbn = ?";
		$sql .= "        AND shbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn,$shbn));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srntb110 get_srntb110_data end ==========");
		return $result_data;
	}
	
	function update_srntb110_data($record_data)
	{
		if (!isset($record_data['jyohonum']))
		{
			log_message('error', 'cannot update without jyohonum');
			return false;
		}
		unset($record_data['edbn']);
		$jyohonum = $record_data['jyohonum'];
		unset($record_data['jyohonum']);
		return $this->db->update('srntb110', $record_data, array('jyohonum' => $jyohonum));
	}
	
	
	function insert_srntb110_data($record_data)
	{
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
		
		$sql .= " INSERT INTO srntb110(";
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
	}

	function delete_srntb110_data($jyohonum,$edbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srntb110";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,$jyohonum);
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_schedule_data($jyohonum,$edbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  sthm,";
		$sql .= "  aitesknm";
		$sql .= " FROM srntb110";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= " AND edbn = '{$edbn}'";
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
		return $result_data;
	}

	/**
	 * スケジュール
	 * データ取得
	 */
	function select_plan_data($shbn, $date){
		$sql = "";
		$result_data = array();
		
		// SQL文作成
		$sql .= " 
			SELECT *
			FROM srntb110
			WHERE shbn = ?
				AND ymd = ?
				AND edbn = 
				(
					SELECT MAX(edbn)
					FROM srntb110
					WHERE shbn = ?
						AND ymd = ?
				)
		";
		$arrWhere = array($shbn, $date, $shbn, $date);
		
		// SQL実行
		$sql_result = $this->db->query($sql, $arrWhere);
		
		// 取得確認
		if($sql_result->num_rows() > 0)
		{
			$result_data = $sql_result->result_array();
		}
		
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
        $result_data = NULL; // 戻り値
        $this->error_date = null;   // エラー日付初期化
        // SQL文作成
        $sql .= " SELECT shbn, ymd, sthm, edhm FROM srntb110 WHERE shbn = ? AND ymd = ? ";
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
                // 登録済み開始時刻 < 入力開始時刻 < 登録済み終了時刻 
                if ($rec_start_time < $startdatetime && $startdatetime < $rec_end_time) {
                    $this->error_date = $startdatetime;
                    return TRUE;
                }
                // 登録済み開始時刻 < 入力終了時刻 < 登録済み終了時刻 
                if ($rec_start_time < $enddatetime && $enddatetime < $rec_end_time) {
                    $this->error_date = $startdatetime;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

	
}

?>
