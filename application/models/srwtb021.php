<?php

class Srwtb021 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	/**
	 * ユーザー別検索情報(相手先)登録件数取得
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	array  $edbn
	 */
	function get_select_client_data($shbn=NULL)
	{
		$row = '01';
		// 初期化
		$user_search_data = NULL;
		// sql文作成
		// 登録件数
		$sql = "SELECT count(*) FROM srwtb021 WHERE shbn = ?";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
		}

		return $row['count'];
	}


	/**
	 * 相手先検索
	 *
	 * @access	public
	 * @param	array $data データ
	 * @return	boolean $kbn 区分 001:本部 002:店舗 003:代理店
	 */
	function get_select_client_list($data, $kbn)
	{
		log_message('debug',"========== srwtb021 get_select_client_list start ==========");

		if($kbn=='001'){
		
		$sql = "SELECT DISTINCT
				srw.shbn, srw.kbn, srw.aiteskcd, srw.hyojun, srw.aitesknm ,
				srw.hanhoncd, maker.rank aiteskrank 
				FROM srwtb021 srw 
			 	LEFT JOIN sgmtb050 maker 
				ON maker.hanhoncd = srw.aiteskcd 
			 	WHERE srw.shbn = ? AND srw.kbn IN (?) 
				ORDER BY srw.hyojun";
		
		}else{
		
				$sql = "SELECT DISTINCT
				srw.shbn, srw.kbn, srw.aiteskcd, srw.hyojun, srw.aitesknm ,
				srw.hanhoncd, maker.rank aiteskrank 
				FROM srwtb021 srw 
			 	LEFT JOIN sgmtb050 maker 
				ON maker.hanhoncd = srw.hanhoncd 
			 	WHERE srw.shbn = ? AND srw.kbn IN (?) 
				ORDER BY srw.hyojun";
		
		}
		
		log_message('debug',"================================================================");
						log_message('debug',"--------------------sql = ".$sql);
		$query = $this->db->query($sql,array($data['shbn'],$kbn));
		$res = array();

		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		log_message('debug',"========== srwtb021 get_select_client_list end ==========");
		return $res;
	}

	
	/**
	 * 相手先検索
	 *
	 * @access	public
	 * @param	array $data データ
	 * @return	boolean $kbn 区分 001:本部 002:店舗 003:代理店
	 */
	function get_select_client_list_d($data, $kbn)
	{
		log_message('debug',"========== srwtb021 get_select_client_list start ==========");

		// 本部と店舗（メーカー）のデータを取得
		$sql = "SELECT srw.shbn, srw.kbn, srw.aiteskcd, srw.hyojun, srw.aitesknm ,srw.hanhoncd, srw.hanchicd, sgm.rank aiteskrank FROM srwtb021 srw
				 LEFT JOIN sgmtb060 sgm 
				 ON sgm.hanhoncd = srw.hanhoncd 
				 WHERE srw.shbn = ? AND srw.kbn IN (?)
				ORDER BY srw.hyojun";
		
		// 店舗一般店のデータを取得
		//$sql = "SELECT";
		log_message('debug',"================================================================");
						log_message('debug',"--------------------sql = ".$sql);
		$query = $this->db->query($sql,array($data['shbn'],$kbn));
		$res = array();

		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}
		log_message('debug',"========== srwtb021 get_select_client_list end ==========");
		return $res;
	}	

	
	/**
	 * 相手先検索 srwtb021    sgmtb050/090にあったものを処理
	 * 登録・更新処理　
	*/
	function date_insert_update($data)
	{
		$string_table = NULL;
		$CI =& get_instance();
		// DB名と同じものを指定
		$CI->load->model('sgmtb050'); // ユーザ別検索情報（相手先）
		if ( !$this->get_asign($data) ) {
			$sql = '';
			$sql .= 'INSERT INTO srwtb021 VALUES(';
			$sql .= '?,?,?,?,?,?';
			$sql .= ');';

			$set = array(
				$data['shbn'],
				$data['kbn'],
				$data['aiteskcd'],
				$data['hyojun'],
				$data['aitesknm'],
				$data['hanhoncd']
				);
			$query = $this->db->query($sql, $set);
			if($query){
				return TRUE;
			}else{
				return FALSE;
			}

		} else {

			$sql = '';
			$sql .= 'UPDATE srwtb021 ';
			$sql .= 'SET ';
			$sql .= 'aiteskcd = ?, ';
			$sql .= 'aitesknm = ?, ';
			$sql .= 'hanhoncd = ? ';
			$sql .= 'WHERE shbn = ? AND kbn = ? AND hyojun = ? ';

			$set  = array(
				$data['aiteskcd'], 
				$data['aitesknm'],
				$data['hanhoncd'],
				$data['shbn'],
				$data['kbn'],
				$data['hyojun']
			);
			$this->db->query($sql, $set);
			$query = $this->db->query($sql, $set);
			if($query){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	function get_asign($data)
	{
		$sql = '';
		$sql .= 'SELECT * FROM srwtb021 ';
		$sql .= 'WHERE ';
		$sql .= 'shbn = ? ';
		$sql .= 'AND kbn = ? ';
		$sql .= 'AND hyojun = ? ';
		
		$query = $this->db->query($sql, array($data['shbn'],$data['kbn'],$data['hyojun']));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		return $res;
	}
	
	function get_aitesk_data($shbn)
	{
		$sql = '';
		$sql .= 'SELECT aitesknm FROM srwtb021 ';
		$sql .= 'WHERE ';
		$sql .= 'shbn = ? ';
		$sql .= "and kbn = '001' ";
		$sql .= 'ORDER BY hyojun ';
		
		$query = $this->db->query($sql, array($shbn));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = array();
		}
		return $res;
	}


}