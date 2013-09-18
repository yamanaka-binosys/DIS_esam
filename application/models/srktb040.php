<?php
/**
* todo情報の登録
*
* @access	public
* @param	string $post = 登録情報
* @return	boolean $res = TRUE = 成功:FALSE = 失敗
*/
class Srktb040 extends CI_Model {

function __construct()
{
	// Model クラスのコンストラクタを呼び出す
	parent::__construct();
}

function insert_user($post = NULL)
{
try{
		log_message('debug',"========== insert_todo start ==========");
		// トランザクション開始
		$this->db->trans_start();
		// 初期化
		$res = NULL;
		$date = date("Ymd");
		$sql = "INSERT INTO srktb040
					(Act ,Act_Ymd, Biko, ZnkakninShbn, UpdateDate  ,yobim01 ,yobim02 ,yobis01 ,yobis02,kakuninshbn,
					 ImpKbn, DesignatedDay, Todo, FinishFlg)
				VALUES
					(
					NULL,
					?, 
					?, 
					NULL, 
					?, 
					NULL, 
					NULL, 
					NULL, 
					NULL,
					?, 
					?, 
					?, 
					?, 
					?);";

		$hiduke = sprintf("%04d%02d%02d", $_POST["year"], $_POST["month"], $_POST["day"]);
		$insertdata = array(
			$hiduke,
			$post["todo"],		    // 内容
			$date,					// 更新日付
			$post["shbn"],
			$post["impkbn"],	    // 重要度
			$hiduke,				// 指定日
			$post["todo"],		    // 内容
			"0"
		);
	
		// クエリ実行
		// POSTデータ、日付、情報№、使用テーブル
		$query = $this->db->query($sql, $insertdata); // SQL実行
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
			break;
		}
		log_message('debug',"sql=".$sql);

		if($res)
		{
			// トランザクション終了(コミット)
			$this->db->trans_complete();
		}
		else
		{
			// ロールバック
			$this->db->trans_rollback();
		}
		log_message('debug',"========== insert_user end ==========");
		return $res;
	} catch(Exception $e) {
		log_message('debug',"========== delete catch trans_rollback start ==========");
		// ロールバック
		$this->db->trans_rollback();
		log_message('debug',"========== delete catch trans_rollback end ==========");
		return FALSE;
	}
}

/**
*
* テーブルを更新する
*
*/
function update_data($data) {


	try {
		// トランザクション開始
		$this->db->trans_start();
			// 以下からUPDATE文を書く ---------------- //
			
		$sql = "UPDATE srktb040 SET designatedday = ?
										, act_ymd = ?
										, impkbn = ?
										, biko = ?
										, todo = ?
										, finishflg = ?
										 where jyohonum = ?";
		// --------------------------------------- //
		log_message('debug',"sql = ". $sql);
		// SQL実行
		$query = $this->db->query($sql,array($data["designatedday"],$data["designatedday"],$data["impkbn"],$data["todo"],$data["todo"],$data["finishflg"],$data["jyohonum"]));
		if($this->db->trans_status() === TRUE){
			// トランザクション終了(コミット)
			$this->db->trans_complete();
			$result_data = TRUE;
			return $result_data;
		}else{
			throw new Exception('DB更新失敗');
			return false;
		}
	} catch ( Exception $e ){
		$this->db->trans_rollback();
		return false;
	}
}

	/**
	 * TODOデータの取得
	 */ 
	function get_todo_data($post){
		{
			// 初期化
			$res = NULL;
			// sql文作成
			$sql = "SELECT jyohonum, act_ymd, designatedday, updatedate, impkbn, todo, finishflg FROM srktb040 ORDER BY designatedday, impkbn DESC, jyohonum DESC";
			$query = $this->db->query($sql);
			if($query->num_rows() > 0)
			{
				$res = $query->result_array();
			}else{
				$res = FALSE;
			}

			return $res;
		}
	}

	function get_search_todo_data($data){
		log_message('debug',"========== srktb040 get_search_todo_data start ==========");
		// 初期化
		if(isset($data['fromyear']) && $data['fromyear'] &&
			isset($data['frommonth']) && $data['frommonth'] &&
			isset($data['fromday']) && $data['fromday']){
			// fromの日時を連結
			$data["fromyear"] = str_pad($data["fromyear"], 4, '0', STR_PAD_LEFT);
			$data["frommonth"] = str_pad($data["frommonth"], 2, '0', STR_PAD_LEFT);
			$data["fromday"] =str_pad($data["fromday"], 2, '0', STR_PAD_LEFT);
			$from = $data["fromyear"].$data["frommonth"].$data["fromday"];
		}

		if(isset($data['toyear']) && $data['toyear'] &&
			isset($data['tomonth']) && $data['tomonth'] &&
			isset($data['today']) && $data['today']){
			// toの日時を連結
			$data["toyear"] = str_pad($data["toyear"], 4, '0', STR_PAD_LEFT);
			$data["tomonth"] = str_pad($data["tomonth"], 2, '0', STR_PAD_LEFT);
			$data["today"] = str_pad($data["today"], 2, '0', STR_PAD_LEFT);
			$to = $data["toyear"].$data["tomonth"].$data["today"];
		}

		$sqlh = "SELECT jyohonum, act_ymd, designatedday, updatedate, impkbn, todo, finishflg FROM srktb040";
		$sqlt = " ORDER BY designatedday, impkbn DESC, jyohonum DESC";

		$sqlw = "";
		$tmp_where = array();
		$tmp_where[] = " finishflg <> '1'";
		// 開始と終了
		if(isset($from) && isset($to)){
			$tmp_where[] = " act_ymd >= '".$from."' AND act_ymd <= '".$to."'";
		}else if(isset($from)){
			// 開始日以降
			$tmp_where[] = " act_ymd >= '".$from."'";			
		}else if(isset($to)){
			// 終了日以降
			$tmp_where[] = " act_ymd <= '".$to."'";
		}
		$sqlw = implode(' AND ', $tmp_where);
		
		if($sqlw != ""){
			$sqlw = " WHERE kakuninshbn = '".$data['shbn']."' AND".$sqlw;
		}

		$sql = $sqlh.$sqlw.$sqlt;

		log_message('debug',"sql = ".$sql);
		$query = $this->db->query($sql);
		$res = array();
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		log_message('debug',"========== srktb040 get_search_todo_data end ==========");
		return $res;
	}
	
	/**
	 * 
	 */
	function get_srktb040_data($condition_data){
		log_message('debug',"========== srktb0400 get_srktb040_data start ==========");
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
		$sql .= " SELECT jyohonum,";                  // 情報ナンバー
		$sql .= "        other";                      // その他
		$sql .= " FROM srktb0400";
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
		
		log_message('debug',"========== srktb0400 get_srktb040_data end ==========");
		return $result_data;
	}
	
	/**
	 * 
	 */
	function get_top_data($shbn){
		log_message('debug',"========== srktb0400 get_top_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  jyohonum,";
		$sql .= "  edbn,";
		$sql .= "  designatedday,";
		$sql .= "  todo,";
		$sql .= "  impkbn,";
		$sql .= "  finishflg";
		$sql .= " FROM";
		$sql .= "  srktb040";
		//$sql .= " WHERE kakuninshbn = '{$shbn}'";
		$sql .= " WHERE kakuninshbn = ?";
		$sql .= " AND finishflg = '0'";
		$sql .= " ORDER BY designatedday, impkbn DESC, jyohonum DESC";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn));
		// 取得確認
		if($query->num_rows() > 0){
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srktb0400 get_top_data end ==========");
		return $result_data;
	}

	/**
	 * 
	 */
	function get_check_todo_data($jyohonum,$edbn){
		log_message('debug',"========== srktb040 get_check_todo_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  jyohonum,";
		$sql .= "  edbn,";
		$sql .= "  designatedday,";
		$sql .= "  todo,";
		$sql .= "  impkbn,";
		$sql .= "  finishflg";
		$sql .= " FROM";
		$sql .= "  srktb040";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= " AND edbn ='{$edbn}'";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " AND edbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn));
		// 取得確認
		if($query->num_rows() > 0){
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srktb040 get_check_todo_data end ==========");
		return $result_data;
	}
	
	function update_flg($jyohonum,$edbn){
		log_message('debug',"========== srktb0400 update_flg start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = FALSE; // 戻り値
		// トランザクション開始
		$this->db->trans_start();
		// SQL文作成
		$sql .= " UPDATE srktb040";
		$sql .= " SET finishflg='1'";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " AND edbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn));
		// 取得確認
		if($this->db->trans_status() === TRUE){
			// トランザクション終了(コミット)
			$this->db->trans_complete();
			$result_data = TRUE;
		}else{
			$this->db->trans_rollback();
		}
		log_message('debug',"========== srktb0400 update_flg end ==========");
		
		return $result_data;
	}
	
}
?>