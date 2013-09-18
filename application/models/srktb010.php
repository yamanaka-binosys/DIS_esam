<?php
// asakura4
class Srktb010 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * 商談履歴情報取得
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	boolean $res = TRUE=管理者、FALSE=一般
	 */
	function get_nego_history_data($data,$shbn)
	{
		$where = array();
		$whereOr = array();

		// 商談日（開始）
		if( isset($data['s_year']) && $data['s_year'] != ''){
			$where[] = "k2.ymd >= '".$data['s_year'] . sprintf("%02d",$data['s_month']) . sprintf("%02d",$data['s_day']) ."'";

		} else {
			// 年がない場合は、月日無視
			if(isset($data['s_month'])) unset($data['s_month']);
			if(isset($data['s_day'])) unset($data['s_day']);
		}

		// 商談日（終了）
		if( isset($data['e_year']) && $data['e_year'] != ''){
			$where[] = "k2.ymd <= '".$data['e_year'] . sprintf("%02d",$data['e_month'])  . sprintf("%02d",$data['e_day']) ."'";

		} else {
			// 年がない場合は、月日無視
			if(isset($data['e_month'])) unset($data['e_month']);
			if(isset($data['e_day'])) unset($data['e_day']);
		}

		// 販売店本部 代理店
		if( isset($data['shop_main']) && $data['shop_main'] != ''){
			$whereOr[] = "k2.kubun = '001'";
		}

		if( isset($data['agency']) && $data['agency'] != ''){
			$whereOr[] = "k2.kubun = '003'";
		}

		// 商談内容
		if( isset($data['nego_contents']) && $data['nego_contents'] != ''){
			$where[] = "k2.sdn_niyo like '%". $data['nego_contents']."%'";
		}
		
		// 相手先のどちらにもチェックがついていない場合は空の結果を返す。
		if (empty($whereOr)) return array();

		// 初期化
		$res = FALSE;

		// sql文作成
		$sql_select = 	"
			SELECT * FROM
			(SELECT 
				To_char(To_date(k.ymd, 'YYYYMMDD'), 'yyyy/mm/dd') AS ymd_sla,
			              k.aiteskcd                                        AS aitesk_cd,
			              k.aitesknm                                        AS aitesk_nm,
			              CASE
			                WHEN kubun = '001' THEN Substr(k.seiykniyo
			                                                || k.fseiykniyo
			                                                || k.horyuniyo
			                                                || k.sonotaniyo, 0, 40)
			                WHEN kubun = '002' THEN Substr(sagyokekka, 0, 40)
			                WHEN kubun = '003' THEN Substr(syodankekka, 0, 40)
			              END                                               AS sdn_niyo,
			              k.ymd                                             AS ymd,
			              k.kubun,
			              k.tantoshbn
			       FROM   srktb010 AS k
			INNER JOIN
			(SELECT
				jyohonum,
				max(edbn) as edbn
			FROM srktb010
			WHERE kubun <> '002' -- 店舗は検索対象に入れない
			GROUP BY jyohonum) ed
			    on k.jyohonum = ed.jyohonum and k.edbn = ed.edbn
				) as k2
				";

		// WHERE文構築
		$sql_where = "";

		if(count($where)>0){
			$sql_where = " WHERE ";
			$sql_where .= implode(" AND ",$where);
		}

		if(count($whereOr)>0){
			if($sql_where == ""){
				$sql_where = " WHERE ";

			} else {
				$sql_where .= " and ";

			}
			$sql_where .= " (" . implode(' or ',$whereOr) . ")";
		}
		$sql_where .= " AND k2.tantoshbn = '".$shbn."'";
		$sql_select .= $sql_where;
		$result = array();
				
		// クエリ実行
		$query = $this->db->query($sql_select);
		if($query->num_rows() > 0){
			$result = $query->result_array();

		}else if($query == ""){
			log_message('debug',"========== $query ==========");

		}else{
			$result = array();
		}

		log_message('debug',"========== select_s_rireki end ==========");

		return $result;

	}
	
	function update_srktb010_data($record_data)
	{
		if (!isset($record_data['motojyohonum']) || !isset($record_data['kubun']))
		{
			log_message('error', 'cannot update without motojyohonum, kubun');
			return false;
		}
		unset($record_data['edbn']);
		unset($record_data['jyohonum']);

		$motojyohonum = $record_data['motojyohonum'];
		$kubun = $record_data['kubun'];
		
		unset($record_data['motojyohonum']);
		unset($record_data['kubun']);
		
		return $this->db->update('srktb010', $record_data, array('motojyohonum' => $motojyohonum, 'kubun' => $kubun));
	}
	
	function insert_srktb010_data($record_data)
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
		
		$sql .= " INSERT INTO srktb010(";
		$sql .= $name_string;
		$sql .= "createdate";
		$sql .= ") VALUES (";
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
	
	function get_jyohonum($jyohonum = NULL,$kubun = NULL){
		log_message('debug',"========== srktb010 get_jyohonum start ==========");
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT BASE.jyohonum,EDA.edbn,BASE.aiteskcd";
		$sql .= " FROM srktb010 BASE";
		$sql .= " INNER JOIN";
		$sql .= " (SELECT jyohonum, MAX(CAST(edbn as int)) AS edbn FROM srktb010";
		$sql .= " GROUP BY jyohonum";
		$sql .= " ) EDA ON";
		$sql .= " BASE.jyohonum = EDA.jyohonum";
		$sql .= " and CAST(BASE.edbn as int) = EDA.edbn";
		//$sql .= " WHERE motojyohonum = '{$jyohonum}'";
		//$sql .= " AND kubun = '{$kubun}'";
		$sql .= " WHERE motojyohonum = ?";
		$sql .= " AND kubun = ?";
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$kubun));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		log_message('debug',"========== srktb010 get_jyohonum end ==========");
		return $result_data;
	}
	
	function delete_srktb010_data($jyohonum,$kubun){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srktb010";
		//$sql .= " WHERE motojyohonum = '{$jyohonum}'";
		//$sql .= " AND kubun = '{$kubun}'";
		$sql .= " WHERE motojyohonum = ?";
		$sql .= " AND kubun = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$kubun));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
}

?>
