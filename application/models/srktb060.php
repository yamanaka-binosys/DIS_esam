<?php

class Srktb060 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 仮相手先情報の登録
	 * 
	 * @access	public
	 * @param	string $post = 仮相手先情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_message_data($post,$jyohoNum,$shbn,$shinNm)
	{
		log_message('debug',"========== insert_message start ==========");
		foreach($post as $key => $check)
		{
			log_message('debug',"post[".$key."]=".$check);
		}
			log_message('debug',"jyohonum=".$jyohoNum);
			log_message('debug',"shbn=".$shbn);
			log_message('debug',"shinNm=".$shinNm);
		// 初期化
		$res = NULL;
		$date = date("Ymd");
		
		// sql文作成
		$sql = "INSERT INTO srktb060
			(jyohoNum,
			edbn,
			tuchistartdate,
			tuchienddate,
			jyohoNiyo,
			shbn01,
			shinNm01,
			tempFile,
			createDate,
			updateDate,
			allFlg,
			sendshbn,
			is_bold
			) 
			VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?)";
		log_message('debug',"date=".$date);
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$jyohoNum,	// jyohoNym
					'01',		// edb
					$post['s_year'] . $post['s_month'] . $post['s_day'],
					$post['e_year'] . $post['e_month'] . $post['e_day'],
					$post['comment'],
					$shbn,
					$shinNm,
					$post['file'],
					$date,
					$date,
					$post['allflg'],
					$post['kakninshbn'],
					$post['is_bold']
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"date=".$date);
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== insert_user end ==========");
		return $res;
	}
	
	/**
	 *メッセージ情報の取得
	 * 
	 * @access	public
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_jyoho_num_data()
	{
		log_message('debug',"========== select jyohoNum start ==========");
		
		
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT max(jyohoNum) FROM srktb060;";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql);
		
		if($query->num_rows > 0){
			foreach( $query->result() as $row ) {
				$jyohoNum = $row->max;
			}
			// 取得した情報ナンバーに1加える
			$jyohoNum = intval($jyohoNum) + 1;
		} else {
			// 0のため情報ナンバー1を返す
			$jyohoNum = 1;
		}
		
		$jyohoNum = sprintf('%09d',$jyohoNum);
		
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== insert_user end ==========");
		return $jyohoNum;
	}
	
	
	function get_info_data($shbn = NULL)
	{
		// sql文作成
		$sql = NULL;
		
		$sql .= " SELECT jyohonum,";
		$sql .= " jyohoniyo,";
		$sql .= " tuchistartdate,";
		$sql .= " tuchienddate,";
		$sql .= " tempfile,";
		$sql .= " allflg,is_bold";
		$sql .= " FROM srktb060";
		$sql .= " WHERE sendshbn LIKE '%?%'";
		$sql .= " OR shbn01 = ? ";
		$sql .= " OR allflg = '1'";
		$sql .= " ORDER BY jyohonum DESC";
		$sql .= ";";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn,$shbn));
		// 結果を配列に格納
		$row = $query->result_array();
		if(empty($row))
		{
			log_message('debug',"query result is nothing");
			return NULL;
		}
		return $row;
	}
	
	/**
	 * トップに表示するデータを取得する。
	 */
	function get_top_data($shbn = NULL)
	{
		// sql文作成
		$sql = NULL;
	
		$sql .= " SELECT jyohonum,";
		$sql .= " jyohoniyo,";
		$sql .= " tuchistartdate,";
		$sql .= " tuchienddate,";
		$sql .= " tempfile,";
		$sql .= " allflg,";
		$sql .= " is_bold";
		$sql .= " FROM srktb060";
		$sql .= " WHERE sendshbn LIKE '%". $shbn."%'";
		$sql .= " OR shbn01 = '".$shbn."'";
		$sql .= " OR allflg = '1'";
		$sql .= " ORDER BY jyohonum DESC";
		$sql .= ";";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn,$shbn));
		// 結果を配列に格納
		return $query->result_array();
	}

}

?>
