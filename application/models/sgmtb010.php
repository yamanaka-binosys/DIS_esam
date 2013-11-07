<?php

class Sgmtb010 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * 社番を引数にし、メニュー表示区分をTRUEかFALSEで返す
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	boolean $res = TRUE=管理者、FALSE=一般
	 */
	function get_user_data($shbn)
	{
		// 初期化
//		$res = FALSE;
		$res = "001";

		$sql = "SELECT menuhyjikbn FROM sgmtb010 WHERE shbn = ? AND deletedate IS NULL";
		$query = $this->db->query($sql, array($shbn));
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			$res = $row['menuhyjikbn'];
/*
			if($row['menuhyjikbn'] === '002')
			{
				$res = TRUE;
			}else{
				$res = FALSE;
			}
*/
		}

		return $res;
	}

	/**
	 * 社番を引数にし、ユーザデータを全て取得
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	boolean $res = TRUE=管理者、FALSE=一般
	 */
	function get_user_data_pk($shbn)
	{
		// 初期化
		$sql = "SELECT * FROM sgmtb010 WHERE shbn = ? AND deletedate IS NULL";
		$query = $this->db->query($sql, array($shbn));
		$result = array();
		if ($query->num_rows() > 0)
		{
			$result = $query->row_array();
		}

		return $result;
	}

	/**
	 * 社番・パスワードの一致チェック
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 * @param	string $passwd = パスワード
	 * @return	array
	 */
	function check_passwd($shbn,$passwd)
	{
		// 初期化
		$res = NULL;

		$sql = "SELECT * FROM sgmtb010 WHERE shbn = ? AND passwd = ?";
		$query = $this->db->query($sql, array($shbn,$passwd));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}

		return $res;
	}

	/**
	 * ユーザ情報の登録
	 *
	 * @access	public
	 * @param	string $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_user($post=NULL)
	{
		log_message('debug',"========== insert_user start ==========");
		foreach($post as $key => $check)
		{
			log_message('debug',"post[".$key."]=".$check);
		}
		// 初期化
		$res = NULL;
		$date = date("Ymd");
		// sql文作成
		$sql = "INSERT INTO sgmtb010 
			(shbn,
			shinnm,
			honbucd,
			bucd,
			kacd,
			menuhyjikbn,
			passwd,
			pwupdatedate,
			etukngen,
			createdate,
			updatedate
			) 
			VALUES(?,?,?,?,?,?,?,?,?,?,?)";
		log_message('debug',"date=".$date);
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$post['shbn'],
					$post['user_name'],
					$post['shinnmkn'],
					$post['honbucd'],
					$post['bucd'],
					$post['kacd'],
					$post['menuhyjikbn'],
					$post['passwd'],
					$date,
					$post['etukngen'],
					$date,
					$date
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
	 * 検索ユーザ情報の取得
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_user_data($shbn=NULL)
	{
		log_message('debug',"========== select_user start ==========");
		log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		$other = NULL;
		// sql文作成
		$sql = "SELECT 
			shbn,
			shinnm,
			honbucd,
			bucd,
			kacd,
			menuhyjikbn,
			passwd,
			etukngen
			FROM sgmtb010 WHERE shbn = ? AND deletedate IS NULL;";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($shbn));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== select_user end ==========");
		return $res;
	}

	/**
	 * 部署からのユーザ名情報の取得
	 *
	 * @access	public
	 * @param	string $busyo_all = 部署
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_name_data($busyo_all = NULL)
	{
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// where 作成
		if(! is_null($busyo_all))
		{
			$sql = "SELECT DISTINCT shbn,shinnm FROM sgmtb010 WHERE ";
			foreach($busyo_all as $b_name => $busyo)
			{
				if($b_name !== 'honbu')
				{
					$sql .= " UNION SELECT shbn,shinnm FROM sgmtb010 WHERE ";
				}
				foreach($busyo as $key => $value)
				{
					// 必須
					if($key === 'honbucd')
					{
						$sql .= "(honbucd = '".$value."' AND bucd = '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."') ";
						$honbu = $value;
					}else if($key === 'bucd' AND $value !== MY_DB_BU_ESC){
						$sql .= "OR (honbucd = '".$honbu."' AND bucd = '".$value."' AND kacd = '".MY_DB_BU_ESC."') ";
						$bu = $value;
					}else if($key === 'kacd' AND $value !== MY_DB_BU_ESC){
						$sql .= "OR (honbucd = '".$honbu."' AND bucd = '".$bu."' AND kacd = '".$value."') ";
					}
				}
			}
		}
		// sql文作成

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}

	/**
	 * 部署からの社番の取得
	 *
	 * @access	public
	 * @param	string $busyo_all = 部署
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_shbn_data($busyo_all = NULL)
	{
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// where 作成
		if(! is_null($busyo_all))
		{
			$no = 0;
			$sql = "SELECT DISTINCT shbn FROM sgmtb010 WHERE ";
			foreach($busyo_all as $b_name => $busyo)
			{

				if($no > 0)
				{
					$sql .= " UNION SELECT shbn FROM sgmtb010 WHERE ";
				}
				foreach($busyo as $key => $value)
				{
					// 必須
					if($key === 'honbucd')
					{
						$sql .= "(honbucd = '".$value."') ";
						$honbu = $value;
					}else if($key === 'bucd' AND $value !== MY_DB_BU_ESC){
						$sql .= "OR (honbucd = '".$honbu."' AND bucd = '".$value."') ";
						$bu = $value;
					}
				}
					$no++;
			}
		}
		// sql文作成

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}
	
	/**
	 * ユニットからの社番の取得
	 *
	 * @access	public
	 * @param	string $grp = グループ
	 * @return	boolean $shbn = TRUE = 成功:FALSE = 失敗
	 */
	function get_unit_search_shbn_data($unit = NULL)
	{
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// sql文作成
		$sql = "SELECT shbn FROM sgmtb010 WHERE honbucd = ? AND bucd = ? AND kacd = ? AND deletedate IS NULL";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($unit['ka']['honbucd'],$unit['ka']['bucd'],$unit['ka']['kacd']));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}


	/**
	 * グループからの社番の取得
	 *
	 * @access	public
	 * @param	string $grp = グループ
	 * @return	boolean $shbn = TRUE = 成功:FALSE = 失敗
	 */
	function get_grp_search_shbn_data($grp = NULL)
	{
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// sql文作成
		$sql = "SELECT shbn FROM sgmtb010 WHERE groupcd = ? AND deletedate IS NULL";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($grp));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}

	/**
	 * 名前からのユーザ情報の取得
	 *
	 * @access	public
	 * @param	string $name = 検索名
	 * @return	boolean $rnamees = TRUE = 成功:FALSE = 失敗
	 */
	function get_name_search_data($name = NULL)
	{
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// sql文作成
		$sql = "SELECT shbn,shinnm,honbucd,bucd,kacd FROM sgmtb010 WHERE shinnm LIKE '%?%' AND deletedate IS NULL";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($name));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}

	/**
	 * ユーザ所属本部情報の取得
	 *
	 * @access	public
	 * @param	string $user_data = 検索ユーザ情報
	 * @return	boolean $rnamees = TRUE = 成功:FALSE = 失敗
	 */
	function get_user_honbu_data($user_data)
	{
		//var_dump($user_data);
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// sql文作成
		// 部名
		$sql = "SELECT bunm FROM sgmtb020 WHERE honbucd = ? AND bucd = '".MY_DB_BU_ESC."' AND kacd = '".MY_DB_BU_ESC."';";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$user_data['honbucd'],
					$user_data['bucd'],
					$user_data['kacd']
					)
				);
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}

	/**
	 * ユーザ所属部情報の取得
	 *
	 * @access	public
	 * @param	string $user_data = 検索ユーザ情報
	 * @return	boolean $rnamees = TRUE = 成功:FALSE = 失敗
	 */
	function get_user_bu_data($user_data)
	{
		//var_dump($user_data);
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// sql文作成
		// 部名
		$sql = "SELECT bunm FROM sgmtb020 WHERE honbucd = ? AND bucd = ? AND kacd = '".MY_DB_BU_ESC."';";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$user_data['honbucd'],
					$user_data['bucd'],
					$user_data['kacd']
					)
				);
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}
	/**
	 * ユーザ所属課・ユニット情報の取得
	 *
	 * @access	public
	 * @param	string $user_data = 検索ユーザ情報
	 * @return	boolean $rnamees = TRUE = 成功:FALSE = 失敗
	 */
	function get_user_unit_data($user_data)
	{
		//var_dump($user_data);
		log_message('debug',"========== get_name_search_data start ===========");
		$res = FALSE;
		// sql文作成
		// ユニット名
		$sql = "SELECT bunm FROM sgmtb020 WHERE honbucd = ? AND bucd = ? AND kacd = ?;";

		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$user_data['honbucd'],
					$user_data['bucd'],
					$user_data['kacd']
					)
				);
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_name_search_data end ===========");
		return $res;
	}

	/**
	 * 確認者(社番)登録
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_adduser_data($post)
	{
		try
		{
			log_message('debug',"========== insert_user_data start ==========");
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$res = NULL;
			$where = NULL;
			$date = date("Ymd");
			// WHERE条件作成
			$where = " WHERE shbn = '".$post['shbn']."' AND deletedate IS NULL";
			// sql文作成
			$sql = "
				UPDATE sgmtb010 SET 
				shinnm = ?,
				menuhyjikbn = ?,
				passwd = ?,
				pwupdatedate = ?,
				updatedate = ?
				".$where;
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$post['user_name'],
						$post['menuhyjikbn'],
						$post['passwd'],
						$date,
						$date
						)
					);
			// 結果判定
			if($this->db->affected_rows())
			{
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
			}else{
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
			}
			log_message('debug',"========== insert_user_data end ==========");
			return $res;
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			return $res;
		}
	}

	/**
	 * ユーザ情報の更新
	 *
	 * @access	public
	 * @param	string $post = 更新情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_user($post=NULL)
	{
		try
		{
			log_message('debug',"========== update_user start ==========");
			log_message('debug',"========== update trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== update trans_start end ==========");
			// 初期化
			$res = NULL;
			$where = NULL;
			$date = date("Ymd");
			// WHERE条件作成
			$where = " WHERE shbn = '".$post['shbn']."' AND deletedate IS NULL";
			// 必須項目sql文作成
			$sql = "
				UPDATE sgmtb010 SET 
				shinnm = ?,
				menuhyjikbn = ?,
				passwd = ?,
				pwupdatedate = ?,
				etukngen = ?,
				updatedate = ?,
				honbucd = ?,
				bucd = ?,
				kacd = ?
				".$where;

			log_message('debug',"date=".$date);
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$post['user_name'],
						$post['menuhyjikbn'],
						$post['passwd'],
						$date,
						$post['etukngen'],
						$date,
						$post['honbucd'],
						$post['bucd'],
						$post['kacd']
						)
					);
			log_message('debug',"date=".$date);
			log_message('debug',"sql=".$sql);
			log_message('debug',"query=".$query);
			// 結果判定
			if($this->db->trans_status() === TRUE)
			{
				log_message('debug',"========== update trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				// 成功
				$res = TRUE;
				log_message('debug',"========== update trans_complete end ==========");
			}else{
				log_message('debug',"========== update trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				// 失敗
				$res = FALSE;
				log_message('debug',"========== update trans_rollback end ==========");
			}
			log_message('debug',"========== update_user end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== update catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== update catch trans_rollback end ==========");
			return $res;
		}
	}

	/**
	 * ユーザ情報の削除
	 *
	 * @access	public
	 * @param	string $post = 削除情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function delete_user($shbn=NULL)
	{
		try
		{
			log_message('debug',"========== delete_user start ==========");
			log_message('debug',"========== delete trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== delete trans_start end ==========");
			log_message('debug',"shbn=".$shbn);
			// 初期化
			$res = NULL;
			$date = date("Ymd");
			// sql文作成
			$sql = "UPDATE sgmtb010 SET deletedate = ? WHERE shbn = ? AND deletedate IS NULL";
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query($sql,array($date,$shbn));
			// 結果判定
			if($query)
			{
				// 成功
				log_message('debug',"========== delete trans_complete start ==========");
				// トランザクション終了(コミット)
				$this->db->trans_complete();
				$res = TRUE;
				log_message('debug',"========== delete trans_complete end ==========");
			}else{
				// 失敗
				log_message('debug',"========== delete trans_rollback start ==========");
				// ロールバック
				$this->db->trans_rollback();
				$res = FALSE;
				log_message('debug',"========== delete trans_rollback end ==========");
			}
			log_message('debug',"========== delete_user end ==========");
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== delete catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== delete catch trans_rollback end ==========");
			return FALSE;
		}
	}
	// 社番より社員名取得 asakura3
	function get_shin_nm($shbn){

		log_message('debug',"========== get_shinNm start ==========");
		log_message('debug',"shbn=".$shbn);
		// 初期化
		// sql文作成
		$sql = "SELECT shinNm FROM sgmtb010 WHERE shbn = ? ";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($shbn));

		// 結果判定
		if($query->num_rows === 0 )
		{
			// 失敗
			return FALSE;
		}else{
			// 社員名返却
			foreach( $query->result() as $row ) {
				$shinNm = $row->shinnm;
			}
			return $shinNm;
		}
	}

	/**
	 * 全スタッフデータ取得
	 * Enter description here ...
	 */
	function get_staff_info($shbn)
	{
		log_message('debug',"========== select_user start ==========");
		// 初期化
		$res = NULL;
		$other = NULL;
		// sql文作成
		$sql = "SELECT ";
		$sql .= "shbn, ";
		$sql .= "shinnm, ";
		$sql .= "honbucd, ";
		$sql .= "bucd, ";
		$sql .= "kacd, ";
		$sql .= "menuhyjikbn, ";
		$sql .= "passwd, ";
		$sql .= "etukngen ";
		$sql .= "FROM sgmtb010 ";
		$sql .= "WHERE deletedate IS NULL ";
		if ( $shbn ) {
			$sql .= "AND shbn = ? ;";
		}
		$query = $this->db->query($sql,array($shbn));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}

		return $res;
	}
	
	/**
	 * 社番より氏名、本部コード、部コード、課コードを取得
	 * 
	 */
	function get_heder_data($shbn = NULL){
		log_message('debug',"========== sgmtb010 get_heder_data start ==========");
		// 初期化
		$sql = "";
		$query = NULL;
		$result_data = NULL;
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  shinnm,";
		$sql .= "  honbucd,";
		$sql .= "  bucd,";
		$sql .= "  kacd";
		$sql .= " FROM";
		$sql .= "  sgmtb010";
		$sql .= " WHERE";
		$sql .= " shbn = ? ";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($shbn));
		if ($query->num_rows > MY_ZERO) {
			// 結果を配列に格納
			$result_data = $query->result_array();
		}
		log_message('debug',"========== sgmtb010 get_heder_data end ==========");
		return $result_data;
	}
	
	public function update_pass($shbn=NULL,$new_pass=NULL){
		log_message('debug',"========== sgmtb010 update_pass start ==========");
		$sql = "";
		$query = NULL;
		$res = FALSE;
		$date = date("Ymd");
		if(is_null($shbn) OR is_null($new_pass)){
			return $res;
		}
		// トランザクション開始
		$this->db->trans_start();
		/*
		$sql .= " UPDATE sgmtb010";
		$sql .= " SET";
		$sql .= "  passwd = '{$new_pass}',";
		$sql .= "  updatedate = '{$date}'";
		$sql .= " WHERE";
		$sql .= "  shbn = '{$shbn}'";
		$sql .= " ;";
		*/
		$sql .= " UPDATE sgmtb010";
		$sql .= " SET";
		$sql .= "  passwd = ?,";
		$sql .= "  updatedate = ?";
		$sql .= " WHERE";
		$sql .= "  shbn = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($new_pass,$date,$shbn));
		if($query){
			// トランザクション終了(コミット)
			$this->db->trans_complete();
			$res = TRUE;
		}else{
			// ロールバック
			$this->db->trans_rollback();
			$res = FALSE;
		}
		log_message('debug',"========== sgmtb010 update_pass end ==========");
		return $res;
	}
	
	/**
	 * 社番を引数にし、ユニット長の社番を取得
	 *
	 * @access	public
	 * @param	string $shbn = 社番
	 */
	function get_unit_cho_shbn($shbn)
	{
		// 初期化
		$sql = "SELECT shbn
				FROM sgmtb010 
				WHERE shbn = (
					SELECT DISTINCT unitshbn
					FROM sgmtb010 
					WHERE shbn = ?
					AND deletedate IS NULL
				)
				AND deletedate IS NULL
				LIMIT 1
				";
		$query = $this->db->query($sql, array($shbn));
		$result = array();
		if ($query->num_rows() > 0)
		{
			$result = $query->row_array();
			return $result['shbn'];
		}

		return '';
	}
	
	/**
	 * 社番を引数にし、ユニット長であれば部下の社番を取得
	 *
	 * @access	public
	 * @param	string $shbn = 社番(ユニット長かどうか判定用)
	 */
	function get_unit_buka_shbn($shbn)
	{
		// 初期化
		$sql = "SELECT shbn, shinnm
				FROM sgmtb010 
				WHERE unitshbn = ? 
                AND shbn != ? 
                ORDER BY shbn;";
		$query = $this->db->query($sql, array($shbn, $shbn));
        if ($query->num_rows() > 0)
		{
			$result = $query->result_array();
		}else{
       		$result = NULL;
        }

		return $result;
	}

    /**
	 * 部署情報から、所属する担当者名を取得
	 *
	 * @access	public
	 * @param	string 
	 */
	function get_shin_data($honbu,$busyo,$unit)
	{
		// 初期化
		$res = NULL;
		$other = NULL;
		// sql文作成
		/*
		$sql = "SELECT ";
		$sql .= "shbn, ";
		$sql .= "shinnm ";
		$sql .= "FROM sgmtb010 ";
		$sql .= "WHERE deletedate IS NULL ";
		$sql .= " AND honbucd = '".$honbu."'";
		$sql .= " AND bucd = '".$busyo."'";
		$sql .= " AND kacd = '".$unit."'";
		$sql .= " ORDER BY shbn";
		*/
		$sql = "SELECT ";
		$sql .= "shbn, ";
		$sql .= "shinnm ";
		$sql .= "FROM sgmtb010 ";
		$sql .= "WHERE deletedate IS NULL ";
		$sql .= " AND honbucd = ?";
		$sql .= " AND bucd = ?";
		$sql .= " AND kacd = ?";
		$sql .= " ORDER BY shbn";
		$query = $this->db->query($sql,array($honbu,$busyo,$unit));
		log_message('debug',"sql=".$sql);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = array();
		}
		
		return $res;
	
	}
	
	function get_shin_data_s($honbu,$busyo,$unit)
	{
		// 初期化
		$res = NULL;
		$other = NULL;
		// sql文作成
		$sql = "SELECT ";
		$sql .= "shinnm ";
		$sql .= "FROM sgmtb010 ";
		$sql .= "WHERE deletedate IS NULL ";
		$sql .= " AND honbucd = ? ";
		$sql .= " AND bucd = ? ";
		$sql .= " AND kacd = ? ";
		$sql .= " ORDER BY shbn";
		$query = $this->db->query($sql,array($honbu,$busyo,$unit));
		log_message('debug',"sql=".$sql);

		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = array();
		}		
		return $res;
	
	}

}

?>
