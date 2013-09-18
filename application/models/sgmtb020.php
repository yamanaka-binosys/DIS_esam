<?php

class Sgmtb020 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	

	/**
	 * 部署情報の取得
	 * 
	 * @access public
	 * @param	string $post = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_busyo_data($data=NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT * FROM sgmtb020 WHERE honbucd = ?";
		$query = $this->db->query($sql, array($data['honbucd']));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else if($query == ""){
		log_message('debug',"========== $query ==========");
			
		}else{
			$res = FALSE;
		}
		
		return $res;
	}
	
	/**
	 * 全部署情報の取得
	 * 
	 * @access public
	 * @param	string $post = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_busyo_all_data()
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT * FROM sgmtb020";
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		
		return $res;
	}

	/**
	 * 本部名の取得
	 * 
	 * @access public
	 * @param	string $post = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_honbu_name_data($user = NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		//
		if(! is_null($user))
		{
			/*
			$sql = "SELECT bunm 
				FROM sgmtb020 
				WHERE honbucd = '".$user['honbucd']."' AND bucd = '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."'  ORDER BY hyojun;
				";		
			*/
			$sql = "SELECT bunm 
				FROM sgmtb020 
				WHERE honbucd = ? AND bucd = '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."'  ORDER BY hyojun;
				";				
			$query = $this->db->query($sql,array($user['honbucd']));
		}else{
			$sql = "SELECT honbucd,bunm 
				FROM sgmtb020 WHERE bucd = '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."' 
				ORDER BY hyojun;
				";
			$query = $this->db->query($sql);
		}		
		
		if($query->num_rows() > 0)
		{
			if(! is_null($user))
			{
				$res = $query->row_array();				
			}else{
				$res = $query->result_array();
			}
		}else{
			$res = FALSE;
		}
		return $res;
	}
	
	/**
	 * 部名の取得
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_bu_name_data($user = NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		if(! is_null($user))
		{
		/*
			$sql = "SELECT bunm  
				FROM sgmtb020 
				WHERE honbucd = '" .$user['honbucd']. "' 
					AND bucd = '" .$user['bucd']. "' 
					AND kacd = '".MY_DB_BU_ESC."';";
				*/
				$sql = "SELECT bunm  
				FROM sgmtb020 
				WHERE honbucd = ?
					AND bucd = ? 
					AND kacd = '".MY_DB_BU_ESC."';";	
					
				$query = $this->db->query($sql,array($user['honbucd'],$user['bucd']));
				
		}else{
			$sql = "SELECT DISTINCT honbucd,bucd,bunm  
				FROM sgmtb020 WHERE kacd = '".MY_DB_BU_ESC."' AND bucd <> '".MY_DB_BU_ESC."' 
				ORDER BY bunm;
				";
				$query = $this->db->query($sql);
		}
		log_message('debug',$sql);
		
		if($query->num_rows() > 0)
		{
			if(! is_null($user))
			{
				$res = $query->row_array();
			}else{
				$res = $query->result_array();
			}
		}else{
			$res = FALSE;
		}
		
		return $res;
	}
	
	/**
	 * 課・ユニット名の取得
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_unit_name_data($user = NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		if(! is_null($user))
		{
		/*
			$sql = "SELECT bunm  
				FROM sgmtb020 
				WHERE honbucd = '" .$user['honbucd']. "' 
					AND bucd = '" .$user['bucd']. "' 
					AND kacd = '" .$user['kacd']. "';";
					*/
				$sql = "SELECT bunm  
				FROM sgmtb020 
				WHERE honbucd = ? 
					AND bucd = ?
					AND kacd = ?;";
					
			log_message("debug",$sql);
			$query = $this->db->query($sql,array($user['honbucd'],$user['bucd'],$user['kacd']));
		}else{
			$sql = "SELECT DISTINCT honbucd,bucd,kacd,bunm 
				FROM sgmtb020 WHERE bucd <> '".MY_DB_BU_ESC."' AND kacd <> '".MY_DB_BU_ESC."' 
				ORDER BY bunm;
				";
				$query = $this->db->query($sql);
		}
		
		if($query->num_rows() > 0)
		{
			if(! is_null($user))
			{
				$res = $query->row_array();
			}else{
				$res = $query->result_array();				
			}
		}else{
			$res = FALSE;
		}
		
		return $res;
	}
	
		/**
	 * 課・ユニット名の取得(条件:本部・部署)
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_unit_name_data_busyo($user = NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		if(! is_null($user))
		{
				$sql = "SELECT bunm,kacd
				FROM sgmtb020 
				WHERE honbucd = ? 
					AND bucd = ?
					AND kacd <> 'XXXXX';";
					
			log_message("debug",$sql);
			$query = $this->db->query($sql,array($user['honbucd'],$user['bucd']));
		}else{
			$sql = "SELECT DISTINCT honbucd,bucd,kacd,bunm 
				FROM sgmtb020 WHERE bucd <> '".MY_DB_BU_ESC."' AND kacd <> '".MY_DB_BU_ESC."' 
				ORDER BY bunm;
				";
				$query = $this->db->query($sql);
		}
		
		if($query->num_rows() > 0)
		{
			if(! is_null($user))
			{
				$res = $query->result_array();		
				//$res = $query->row_array();
			}else{
				$res = $query->result_array();				
			}
		}else{
			$res = FALSE;
		}
		
		return $res;
	}
	
			/**
	 * 課・ユニット名の取得(条件:本部)
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_unit_name_data_honbu($user = NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		if(! is_null($user))
		{
				$sql = "SELECT bunm,bucd,kacd 
				FROM sgmtb020 
				WHERE honbucd = ?
				AND bucd <> 'XXXXX'
				AND kacd <> 'XXXXX';";
					
			log_message("debug",$sql);
			$query = $this->db->query($sql,array($user['honbucd']));
		}else{
			$sql = "SELECT DISTINCT honbucd,bucd,kacd,bunm 
				FROM sgmtb020 WHERE bucd <> '".MY_DB_BU_ESC."' AND kacd <> '".MY_DB_BU_ESC."' 
				ORDER BY bunm;
				";
				$query = $this->db->query($sql);
		}
		
		if($query->num_rows() > 0)
		{
			if(! is_null($user))
			{
				$res = $query->result_array();		
				//$res = $query->row_array();
			}else{
				$res = $query->result_array();				
			}
		}else{
			$res = FALSE;
		}
		
		return $res;
	}
			/**
	 * 全課・ユニット名の取得(
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_unit_name_data_all($user = NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
	
				$sql = "SELECT bunm,honbucd,bucd,kacd 
				FROM sgmtb020 
				WHERE honbucd  <> 'XXXXX'
				AND bucd <> 'XXXXX'
				AND kacd <> 'XXXXX';";
					
			log_message("debug",$sql);
			$query = $this->db->query($sql);
		
		
		if($query->num_rows() > 0)
		{
			if(! is_null($user))
			{
				$res = $query->result_array();		
				//$res = $query->row_array();
			}else{
				$res = $query->result_array();				
			}
		}else{
			$res = FALSE;
		}
		
		return $res;
	}
	
	/**
	 * グループ名の取得
	 * 
	 * @access public
	 * @param	string $post = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_all_group_data()
	{
		// 初期化
		$res = NULL;
		// sql文作成
		//
		$sql = "SELECT DISTINCT kacd,bunm 
			FROM sgmtb020 WHERE bucd <> '".MY_DB_BU_ESC."' AND kacd <> '".MY_DB_BU_ESC."' 
			ORDER BY bunm;
			";
	
 
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else if($query == ""){
		log_message('debug',"========== $query ==========");
			
		}else{
			$res = FALSE;
		}
		
		return $res;
	}

	/**
	 * 部署情報の登録
	 * 
	 * @access	public
	 * @param	string $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_busyo($post=NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "INSERT INTO sgmtb010 
			(shbn,
			shinnm,
			shinkn,
			honbucd,
			bucd,
			kacd,
			menuhyjikbn,
			passwd,
			pwupdate,
			etukngen,
			updatedate
			) 
			VALUES(?,?,?,?,?,?,?,?,?,?,?)";// insert
		$query = $this->db->query($sql, array(
			$post['shbn'],
			$post['name'],
			$post['kana'],
			$post['honbu_code'],
			$post['bu_code'],
			$post['ka_code'],
			$post['kbn_menu'],
			$post['authority'],
			$post['password']));
		
		return $res;
	}
	
	function get_name($honbucd = NULL,$bucd = MY_DB_BU_ESC,$kacd = MY_DB_BU_ESC){
		log_message('debug',"========== sgmtb020 get_name start ==========");
		if (is_null($honbucd)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$sql = "";
		$query = NULL;
		$result_data = NULL;
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  bunm";
		$sql .= " FROM sgmtb020";
		/*
		$sql .= " WHERE honbucd = '{$honbucd}'";
		$sql .= " AND bucd = '{$bucd}'";
		$sql .= " AND kacd = '{$kacd}'";
		*/
		$sql .= " WHERE honbucd = ?";
		$sql .= " AND bucd = ? ";
		$sql .= " AND kacd = ? ";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($honbucd,$bucd,$kacd));
		if ($query->num_rows > MY_ZERO) {
			// 結果を配列に格納
			$result_data = $query->result_array();
		}
		log_message('debug',"========== sgmtb020 get_name end ==========");
		return $result_data;
	}
	
	/**
	 * 部名の取得 本部選択時
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_bu_name_data_select($honbu,$bu=NULL)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT honbucd,bucd,bunm  
			FROM sgmtb020 ";
			// WHERE honbucd = '" .$honbu. "' AND bucd <> '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."' ORDER BY hyojun;";
		$sql .= "WHERE honbucd = ? AND bucd <> '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."' ORDER BY hyojun;";
		log_message('debug',$sql);
		$query = $this->db->query($sql,array($honbu));
		if($query->num_rows() > 0)
		{
				$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		return $res;
	}
	
	/**
	 * 課・ユニット名の取得　本部選択時
	 * 
	 * @access public
	 * @param	string $user = 登録情報
	 * @return boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_unit_name_data_select($honbu,$bu=MY_DB_BU_ESC)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		if($bu!='XXXXX'){
			$sql = "SELECT honbucd,bucd,kacd,bunm
				FROM sgmtb020 WHERE honbucd = ? AND bucd = ? AND kacd <> '".MY_DB_BU_ESC."' ORDER BY hyojun;";
		}else{
			$sql = "SELECT honbucd,bucd,kacd,bunm
				FROM sgmtb020 WHERE honbucd = ? AND bucd <> ? AND kacd <> '".MY_DB_BU_ESC."' ORDER BY hyojun;";
		}		
		
		$query = $this->db->query($sql,array($honbu,$bu));
		if($query->num_rows() > 0)
		{
				$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		
		return $res;
	}

}

?>
