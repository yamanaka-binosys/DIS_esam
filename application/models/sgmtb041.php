<?php

class Sgmtb041 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}


	/**
	 * 画面生成情報の取得
	 *
	 * @access public
	 * @param	string $post = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_item_visibility_data($data=NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT distinct * FROM sgmtb041 WHERE PID = ? ORDER BY PID ASC, DBNAME ASC, SORTNM ASC ";
		$query = $this->db->query($sql, array($data['PID']));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}

		return $res;
	}

	/**
	 * 画面生成項目を更新します
	 * @param $data　更新データ
	 */
	function update_item($data = NULL){

		$sql = '';
		$sql .= 'UPDATE sgmtb041 ';
		$sql .= 'SET ';
		$sql .= 'itemdspname = ?, ';
		$sql .= 'dispflg = ?, ';
		$sql .= 'updatedate = '.date('Ymd').' ';
		$sql .= 'WHERE pid = ? AND dbname = ? AND dbitem = ?';

		$itemdspnamae = NULL;
		if(!empty($data['itemdspname'])){
			$itemdspnamae = $data['itemdspname'];
		}
		$set  = array($itemdspnamae, $data['dispflg'], $data['pid'], $data['dbname'], $data['dbitem']);
		$this->db->query($sql, $set);
		$query = $this->db->query($sql, $set);
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}

	}
	
	function plan_view_setting($pid = NULL,$dbname = NULL){
		log_message('debug',"========== sgmtb041 plan_view_setting start ==========");
		// 初期化
		$sql = NULL;
		$res = FALSE;
		if(is_null($pid) OR is_null($dbname)){
			log_message('debug',"pid or dbname NULL");
			return $res;
		}
		
		$sql .= " SELECT";
		$sql .= "  dbitem,";
		$sql .= "  itemdspname,";
		$sql .= "  dispflg";
		$sql .= " FROM";
		$sql .= "  sgmtb041";
		$sql .= " WHERE";
		//$sql .= "     pid = '{$pid}'";
		//$sql .= " AND dbname = '{$dbname}'";
		$sql .= "     pid = ?";
		$sql .= " AND dbname = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql,array($pid,$dbname));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		
		log_message('debug',"========== sgmtb041 plan_view_setting end ==========");
		return $res;
	}
	
	function result_view_setting($pid = NULL,$dbname = NULL){
		log_message('debug',"========== sgmtb041 result_view_setting start ==========");
		// 初期化
		$sql = NULL;
		$res = FALSE;
		if(is_null($pid) OR is_null($dbname)){
			log_message('debug',"pid or dbname NULL");
			return $res;
		}
		
		$sql .= " SELECT";
		$sql .= "  dbitem,";
		$sql .= "  itemdspname,";
		$sql .= "  dispflg";
		$sql .= " FROM";
		$sql .= "  sgmtb041";
		$sql .= " WHERE";
		//$sql .= "     pid = '{$pid}'";
		//$sql .= " AND dbname = '{$dbname}'";
		$sql .= "     pid = ?";
		$sql .= " AND dbname = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql,array($pid,$dbname));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		
		log_message('debug',"========== sgmtb041 result_view_setting end ==========");
		return $res;
	}

}

?>
