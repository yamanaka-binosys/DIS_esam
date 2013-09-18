<?php

class Srktb050 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * 情報メモの登録
	 *
	 * @access	public
	 * @param	string $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_datamemo($post = NULL)
	{
		try{
			log_message('debug',"========== insert_datamemo start ==========");
			foreach($post as $key => $check)
			{
				log_message('debug',"post[".$key."]=".$check);
			}
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$res = NULL;
			$date = date("Ymd");
			$post['createdate'] = $date;
			$post["edbn"] = '01';
			$jyoho = NULL;
			
			// sql文作成
			//// 情報メモテーブル【SRKTB050】////////////////////
			$sql["SRK050"] = "INSERT INTO SRKTB050
					(edbn ,knnm ,nyusyu ,jyohokbnm ,hinsyukbnm ,tishokbnm ,
					 jyohoniyo ,znkakninshbn ,createdate ,updatedate ,yobim01 ,yobim02 ,yobis01 ,yobis02,
					 kakninshbn,tempfile, aitesknm, yksyoku, name, maker, shbn, posteddate,etujukyo)
					VALUES
					('01' ,? ,? ,? ,? ,? ,
					 ? ,? ,? ,? ,NULL, NULL, NULL, NULL,
					 ?, ?, ?, ?, ?, ?, ?, ?, ?);";

			foreach($sql as $key => $insert_sql)
			{
				log_message('debug',"date=".$date);
				log_message('debug', "【".$key."】sql=".$insert_sql);

				// クエリ実行
				// POSTデータ、日付、情報№、使用テーブル
				$query = $this->db->query($insert_sql, $this->insert_data_set($post, $date, $jyoho, $key));
				// 結果判定
				if($query)
				{
					$res = TRUE;
				}else{
					$res = FALSE;
					break;
				}
				log_message('debug',"sql=".$sql);
			}
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
			log_message('debug',"========== insert_datamemo end ==========");
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
	 * 情報メモの更新
	 *
	 * @access	public
	 * @param	string $post = 更新情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_datememo($post = NULL)
	{
		
			// 初期化
			if (!isset($post['jyohonum']))
			{
				log_message('error', 'cannot update without jyohonum');
				return false;
			}
			$res = NULL;
			$date = date("Ymd");

			$post['knnm'] = $post['knnm'];
			$post['createdate'] = $date;
			$post['jyohokbnm'] = $post['jyohokbnm'];
			$post['hinsyukbnm'] = $post['hinsyukbnm'];
			$post['tishokbnm'] = $post['tishokbnm'];
			$post['createdate'] = $date;
			if($post['temp_files']!=""){
			$post['tempfile'] = $post['temp_files'];
			}
			$post['etujukyo'] = 0;
			$post['jyohoniyo'] = $post['info'];

			$jyoho = $post['jyohonum'];
			unset($post['jyohonum']);
			unset($post['edbn']);
			//余計なデータの削除
			unset($post['old_knnm']);
			unset($post['old_temp_files']);
			unset($post['temp_files']);
			unset($post['jyohokbnm']);
			unset($post['hinsyukbnm']);
			unset($post['tishokbnm']);
			unset($post['s_year']);
			unset($post['s_month']);
			unset($post['s_day']);
			unset($post['info']);
			// クエリ実行
			return $this->db->update('srktb050', $post, array('jyohonum' => $jyoho));;
	}

	/**
	 * 情報メモの削除
	 *
	 * @access	public
	 * @param	string $post = 削除情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function delete_datamemo($jyoho = NULL)
	{
		try
		{
			log_message('debug',"========== delete delete_datamemo start ==========");
			log_message('debug',"========== delete trans_start start ==========");
			// トランザクション開始
			$this->db->trans_start();
			log_message('debug',"========== delete trans_start end ==========");
			// 初期化
			$res = NULL;
			// sql文作成
			$sql["SRK050"] = "DELETE FROM SRKTB050 WHERE jyohonum = '".$jyoho."';";
			log_message('debug',"sql=".$sql);
			// クエリ実行
			foreach($sql as $delete_sql)
			{
				$query = $this->db->query($delete_sql);
				// 結果判定
				if($query)
				{
					$res = TRUE;
				}else{
					$res = FALSE;
					break;
				}
				log_message('debug',"========== delete_datamemo end ==========");
			}
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
			return $res;
		}catch(Exception $e){
			log_message('debug',"========== delete catch trans_rollback start ==========");
			// ロールバック
			$this->db->trans_rollback();
			log_message('debug',"========== delete catch trans_rollback end ==========");
			return FALSE;
		}
	}

	/**
	 * 情報メモの重複検索
	 *
	 * @access	public
	 * @param	string $knnm = 件名
	 * @return	boolean $res = TRUE = 重複あり : FALSE = 重複なし
	 */
	function search_knnm($knnm)
	{
		log_message('debug',"========== search_knnm start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT jyohonum,edbn FROM SRKTB050 WHERE knnm = ?";
		// クエリ実行
		$query = $this->db->query($sql, array($knnm));

		// 結果判定
		if($query)
		{
			$res = $query->result_array();
			$res = (empty($res)) ? TRUE : FALSE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== search_knnm end ==========");
		return $res;
	}

	/**
	 * 情報ナンバーの最大値取得
	 *
	 * @access	public
	 * @return	string $res = TRUE = 成功:FALSE = 失敗
	 */
	function max_jouhonum()
	{
		log_message('debug',"========== max_jouhonum start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT MAX(JyohoNum) FROM SRKTB050";
		// クエリ実行
		$query = $this->db->query($sql);

		// 結果判定
		if($query->num_rows() > 0)
		{
			$result = $query->row_array();
			$res = $result['max'];
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== max_jouhonum end ==========");	
		return $res;
	}

	/**
	 * 枝番の取得
	 *
	 * @access	public
	 * @return	string $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_edbn($knnm)
	{
		log_message('debug',"========== get_edbn start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT edbn FROM SRKTB050 WHERE jyohonum = ?";
		// クエリ実行
		$query = $this->db->query($sql, array($knnm));

		// 結果判定
		if($query)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== get_edbn end ==========");
		return $res['edbn'];
	}
	
		/**
	 * 枝番の最大取得
	 *
	 * @access	public
	 * @return	string $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_maxedbn($knnm)
	{
		log_message('debug',"========== get_edbn start ==========");
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT MAX(CAST(SUBSTR(edbn, 2) AS int)) FROM SRKTB050 WHERE jyohonum = ?";
		// クエリ実行
		$query = $this->db->query($sql, array($knnm));

		// 結果判定
		if($query)
		{
			$result = $query->row_array();
			$res = $result['max'];
		}else{
			$res = FALSE;
		}
		log_message('debug',"sql=".$sql);
		log_message('debug',"========== get_edbn end ==========");
		return $res;
	}

	/**
	 * 検索した情報の取得
	 *
	 * @access	public
	 * @param	string $post  POSTデータ
	 * @param	string $order ソートタイプ
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_data_memo($post = NULL, $order = "ASC")
	{
		log_message('debug',"========== get_search_data_memo start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		$and = "";
		// 検索条件の設定
		if(!is_null($post))
		{
			foreach($post as $key => $s_data)
			{
				log_message('debug',$key." = ".$s_data);
				if($key === "aitesknm" AND $s_data != "")
				{
					$and .= " AND ".$key." = ".$s_data;
				}else if($key === "yksyoku" AND $s_data != "")
				{
					$and .= " AND ".$key." = ".$s_data;
				}else if($key === "name" AND $s_data != "")
				{
					$and .= " AND ".$key." = ".$s_data;
				}
			}
		}
		
		// sql文作成
		$sql = "SELECT
				 SRK.Jyohonum,
				 SRK.Edbn,
				 SRK.KnNm,
				 SRK.Nyusyu,
				 SRK.JyohoKbnm,
				 SRK.HinsyuKbnm,
				 SRK.TishoKbNm,
				 SRK.JyohoNiyo,
				 SRK.ZnKakninShbn,
				 SRK.createdate,
				 SRK.aitesknm,
				 SRK.yksyoku,
				 SRK.name,
				 SRK.maker,
				 SRK.tempfile,
				 SRK.posteddate,
				 SRK.etujukyo,
				 SRK.shbn
				FROM
				 SRKTB050 AS SRK
				WHERE
				 SRK.JyohoNum = ? AND
				 SRK.Edbn = ? 
				ORDER BY
				 createdate ".$order;
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($post['jyohonum'],$post['edbn']));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else if($query == ""){
		log_message('debug',"========== $query ==========");

		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_search_data_memo end ==========");
		return $res;
	}
	
	/**
	 * 検索した情報の取得(更新・削除画面の場合）
	 *
	 * @access	public
	 * @param	string $post  POSTデータ
	 * @param	string $order ソートタイプ
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_data_memo_ud($post = NULL, $order = "DESC")
	{
		log_message('debug',"========== get_search_data_memo start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		$where = array($post['shbn'],$post['n_date']);
		$and = "";
		
		if(isset($_POST['s_date']) && $_POST['s_date'] != ""){
			$s_date = " AND SRK.CreateDate >= ? ";
			if(isset($_POST['e_date']) && $_POST['e_date'] != ""){
				$e_date = " AND SRK.CreateDate <= ? ";
				$where = array($post['shbn'],$post['n_date'],$_POST['s_date'],$_POST['e_date']);
			}else{
				$e_date = " ";
				$where = array($post['shbn'],$post['n_date'],$_POST['s_date']);
			}
		}else{
			$s_date = " ";
			if(isset($_POST['e_date']) && $_POST['e_date'] != ""){
				$e_date = " AND SRK.CreateDate <= ? ";
				$where = array($post['shbn'],$post['n_date'],$_POST['e_date']);
			}else{
				$e_date = " ";
				$where = array($post['shbn'],$post['n_date']);
			}
		}
		// sql文作成
		$sql = "SELECT
				 SRK.JyohoNum,
				 SRK.Edbn,
				 SRK.KnNm,
				 SRK.Nyusyu,
				 SRK.JyohoKbNm,
				 SRK.HinsyuKbNm,
				 SRK.TishoKbNm,
				 SRK.JyohoNiyo,
				 SRK.ZnKakninShbn,
				 SRK.CreateDate,
				 SRK.aitesknm,
				 SRK.yksyoku,
				 SRK.name,
				 SRK.maker,
				 SRK.tempfile,
				 SRK.posteddate,
				 SRK.etujukyo,
				 SRK.shbn,
				 SGM010.shinnm
				FROM
				( SRKTB050 AS SRK
				inner JOIN
				 SGMTB010 AS SGM010
				ON
				 SRK.shbn = SGM010.shbn
				)
				left JOIN
				 SGMTB020 AS SGM020
				ON
				 SGM010.honbucd = SGM020.honbucd AND
				 SGM010.bucd = SGM020.bucd AND
				 SGM010.kacd = SGM020.kacd
				WHERE
				 ( SRK.JyohoNum, SRK.Edbn ) in(select SRK.JyohoNum,max(SRK.Edbn) from SRKTB050 AS SRK group by SRK.JyohoNum ) 
				  AND
				  SRK.shbn = ? AND SRK.posteddate >= ? ";
		$sql .= $s_date;
		$sql .= $e_date;
		$sql .= " ORDER BY
				 CreateDate ".$order;
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,$where);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else if($query == ""){
		log_message('debug',"========== $query ==========");

		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_search_data_memo end ==========");
		return $res;
	}
	
	/**
	 * 検索した情報の取得 検索画面
	 *
	 * @access	public
	 * @param	string $post  POSTデータ
	 * @param	string $order ソートタイプ
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_data_memo_s($post = NULL, $order = "DESC")
	{
		log_message('debug',"========== get_search_data_memo start ==========");
		//log_message('debug',"shbn=".$shbn);
		// 初期化
		$res = NULL;
		$and = "";
		// 検索条件の設定
		if(!is_null($post))
		{
			foreach($post as $key => $s_data)
			{
				log_message('debug',$key." = ".$s_data);
				
				if($key === "maker" AND $s_data != "000")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.maker = '".$s_data."'";
				}else if($key === "jyohokbnm" AND $s_data != "000")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.JyohoKbnm = '".$s_data."'";
				}else if($key === "hinsyukbnm" AND $s_data != "000")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.HinsyuKbnm = '".$s_data."'";
				}else if($key === "tishokbnm" AND $s_data != "000")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.TishoKbnm = '".$s_data."'";
				}else if($key === "info" AND pg_escape_string($s_data) != "")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.JyohoNiyo like '%".pg_escape_string($s_data)."%'";
				}else if($key === "s_date" AND $s_data != "")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.CreateDate  >= '".$s_data."'";
				}else if($key === "e_date" AND $s_data != "")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.CreateDate  <= '".$s_data."'";
				}else if($key === "knnm" AND $s_data != "")
				{
					if($and!="") $and .= " AND ";
					$and .= " SRK.knnm like '%".pg_escape_string($s_data)."%'";
				}else if($key === "honbucd" AND $s_data != "XXXXX")
				{
					if($and!="") $and .= " AND ";
					$and .= " SGM020.honbucd = '".$s_data."'";
				}else if($key === "bucd" AND $s_data != "XXXXX")
				{
					if($and!="") $and .= " AND ";
					$and .= " SGM020.bucd = '".$s_data."'";
				}else if($key === "kacd" AND $s_data != "XXXXX")
				{
						if($and!="") $and .= " AND ";
						$and .= " SGM020.kacd = '".$s_data."'";
				}else if($key === "user" AND $s_data != "")
				{
						if($and!="") $and .= " AND ";
						$and .= " SRK.shbn = '".$s_data."'";
				}
				
			}
		}
		if($and!=""){
			$where = 'WHERE ( SRK.JyohoNum, SRK.Edbn ) in(select SRK.JyohoNum,max(SRK.Edbn) from SRKTB050 AS SRK group by SRK.JyohoNum ) AND '.$and;
		}else{
			$where = 'WHERE ( SRK.JyohoNum, SRK.Edbn ) in(select SRK.JyohoNum,max(SRK.Edbn) from SRKTB050 AS SRK group by SRK.JyohoNum ) ';
		}
		
		// sql文作成
		$sql = "SELECT
				 SRK.Jyohonum,
				 SRK.Edbn,
				 SRK.KnNm,
				 SRK.Nyusyu,
				 SRK.JyohoKbnm,
				 SRK.HinsyuKbnm,
				 SRK.TishoKbnm,
				 SRK.JyohoNiyo,
				 SRK.ZnKakninShbn,
				 SRK.CreateDate,
				 SRK.aitesknm,
				 SRK.yksyoku,
				 SRK.name,
				 SRK.maker,
				 SRK.shbn,
				 SRK,posteddate,
				 SRK.etujukyo,
				 SGM020.bunm,
				 SGM010.shinnm,
				 SGM020.honbucd,
				 SGM020.bucd
				FROM
				( SRKTB050 AS SRK
				inner JOIN
				 SGMTB010 AS SGM010
				ON
				 SRK.shbn = SGM010.shbn
				)
				left JOIN
				 SGMTB020 AS SGM020
				ON
				 SGM010.honbucd = SGM020.honbucd AND
				 SGM010.bucd = SGM020.bucd AND
				 SGM010.kacd = SGM020.kacd
				 ".$where."
				ORDER BY
				 CreateDate ".$order." , Jyohonum ASC";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array());
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else if($query == ""){
		log_message('debug',"========== $query ==========");

		}else{
			$res = FALSE;
		}
		log_message('debug',"========== get_search_data_memo end ==========");
		return $res;
	}

	/**
	 * INSERTデータ情報生成
	 * @param <type> $post   POSTデータ
	 * @param <type> $date   日付
	 * @param <type> $jyoho　情報№
	 * @param <type> $mode   使用テーブル
	 * @return <type>
	 */
	private function insert_data_set($post, $date, $jyoho, $mode)
	{
		$data_list = NULL;
		if($mode == "SRK050")
		{
		    ///// 情報メモ
			$data_list = array(
				$post["k_name"],		// 件名
				NULL,					// 入手元（使用しない）
				$post["jyohokbnm"],		// 情報区分名
				$post["hinsyukbnm"],		// 品種区分名
				$post["tishokbnm"],	// 対象区分名
				$post["info"],			// 情報メモ内容
				NULL,					// 前確認者
				$post["createdate"],	// 登録日時
				$date,					// 更新日時
				$post["kakninshbn"],	// 確認者社番
				$post["temp_files"],		// 添付ファイル（ファイルパスに関しては検討中）
				$post["aitesknm"],	// 社名
				$post["yksyoku"],			// 役職
				$post["name"],		// 氏名
				$post["maker"],			// メーカー
				$post["shbn"],			//社番（入力者）
				$post["posteddate"],		//掲載日付
				0						//閲覧状況
			);
		}
		return $data_list;
	}
	
	/**
	 * 一般ユーザとユニット長ユーザ共通のトップページに表示する
	 * 情報メモデータを取得します。
	 */
	function get_top_data() {
		$sql = "
			SELECT
				base.jyohonum,
				base.edbn,
				base.createdate,
				base.knnm,
				base.aitesknm,
				sgmtb010.shinnm
			FROM
				srktb050 base
				INNER JOIN 
				(SELECT jyohonum, MAX(edbn) AS edbn FROM srktb050
				GROUP BY jyohonum) eda
				ON base.jyohonum = eda.jyohonum AND base.edbn = eda.edbn
				LEFT JOIN sgmtb010 ON base.shbn = sgmtb010.shbn
			WHERE
				NOT base.posteddate::int < to_char(now(), 'yyyyMMdd')::int
			ORDER BY
				createdate DESC, jyohonum ASC";
			log_message('debug', $sql);
			$query = $this->db->query($sql, array());
			return $query->result_array();
	}
	
	/**
	 * 情報メモ（上席者、管理者）の取得
	 * @param  <type> $shbn        社番
	 * @return <type> $result_data 情報メモデータ
	 */
	function get_top_admin_data($shbn){
		log_message('debug',"========== srktb050 get_top_admin_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		// 当日 
		//$date = (int)(date("Ymd"));
		$today = (int)(date("Ymd"));
		// 掲載期間の設定
		$after = MY_WEEK_DAY * MY_MEMO_PERIOD;
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= " jyohonum,";
		$sql .= " edbn,";
		$sql .= " createdate,";
		$sql .= " knnm,";
		$sql .= " aitesknm";
		$sql .= " FROM srktb050";
		$sql .= " WHERE CAST(posteddate as int) >= {$today}";
		$sql .= " AND (to_date( createdate, 'YYYYMMDD' ) + {$after}) >= to_date('{$today}','YYYYMMDD')";
		$sql .= " AND (kakninshbn = '{$shbn}'";
		$sql .= " OR shbn = '{$shbn}')";
		$sql .= " ORDER BY jyohonum DESC";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srktb050 get_top_admin_data end ==========");
		return $result_data;
	}
	
	
	/**
	 * 閲覧状況の更新
	 * @param  <type> $shbn        社番
	 * @return <type> $result_data 情報メモデータ
	 */
	function update_etujukyo($jyohonum,$edbn){
		log_message('debug',"========== srktb050 get_top_general_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		$sql .= " UPDATE srktb050";
		$sql .= " SET etujukyo ='1'";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= " AND edbn = '{$edbn}'";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " AND edbn = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = num_rows();
		}
		
		log_message('debug',"========== srktb050 get_top_general_data end ==========");
		return $result_data;
	}
	
	/**
	 * 情報メモ（担当者）の取得
	 * @param  <type> $shbn        社番
	 * @return <type> $result_data 情報メモデータ
	 */
	function get_top_general_data($shbn){
		log_message('debug',"========== srktb050 get_top_general_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		$date = (int)(date("Ymd"));
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= " jyohonum,";
		$sql .= " edbn,";
		$sql .= " createdate,";
		$sql .= " knnm,";
		$sql .= " aitesknm";
		$sql .= " FROM srktb050";
		//$sql .= " WHERE CAST(posteddate as int) >= {$date}";
		$sql .= " WHERE CAST(posteddate as int) >= ?";
//		$sql .= " WHERE posteddate >= '{$date}'";
		//$sql .= " AND shbn = '{$shbn}'";
		$sql .= " AND shbn = ?";
		$sql .= " ORDER BY jyohonum DESC";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($date,$shbn));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srktb050 get_top_general_data end ==========");
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
		$sql .= " UPDATE srktb050";
		$sql .= " SET etuJukyo='1'";
		//$sql .= " WHERE jyohonum = '{$jyohonum}'";
		//$sql .= " AND edbn = '{$edbn}'";
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
