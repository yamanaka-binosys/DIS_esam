<?php

class Srwtb030 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 仮相手先コードの最大値取得
	 * 
	 * @access	public
	 * @param	nothing
	 * @return	boolean $res = 最大値:FALSE = 失敗
	 */
	function get_max_code()
	{
		log_message('debug',"========== srwtb030 get_max_code start ==========");
		// 初期化
		$res = NULL;
		$sql = "";
		// SQL作成
		$sql = "SELECT MAX(CAST(SUBSTR(kraiteskcd, 2) AS int)) FROM srwtb030";
		log_message('debug',"sql=".$sql);
		// 実行
		$query = $this->db->query($sql);
		// 結果
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== srwtb030 get_max_code end ==========");
		return $res;
	}
	/**
	 * 仮相手先情報の登録
	 * 
	 * @access	public
	 * @param	string $post = 仮相手先情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function insert_kari_client($post=NULL,$max = 0)
	{
		log_message('debug',"========== srwtb030 insert_kari_client start ==========");
		foreach($post as $key => $check)
		{
			log_message('debug',"post[".$key."]=".$check);
		}
		// 初期化
		$res = NULL;
		$date = date("Ymd");
		$max++; 
		$kcode = "K".sprintf('%07d', $max);
		// sql文作成
		$sql = "INSERT INTO srwtb030 
			    (shbn,
			    kbn,
			    krAiteskCd,
			    krAiteskNm,
			    krAiteskKn,
			    jyusho,
			    denwa,
			    fax,
			    aitTntoBusyo,
			    aitTntoNm,
			    jyuyodo,
			    createDate,
			    updateDate
			    ) 
			VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?);";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$post['edit_no'],	// sdbn
					$post['kbn'],		// kbn
					$kcode,
					$post['kraitesknm'],
					$post['kraiteskkn'],
					$post['jyusho'],
					$post['denwa'],
					$post['fax'],
					$post['aitTntoBusyo'],
					$post['aitTntoNm'],
					$post['jyuyodo'],
					$date,
					NULL
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== srwtb030 insert_kari_client end ==========");
		return $res;
	}
	
	/**
	 * 仮相手先情報の取得
	 * 
	 * @access	public
	 * @param	string $kari_cname = 仮相手先名
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_search_k_client_data($shbn = NULL,$aite_cd=NULL)
	{
		log_message('debug',"========== srwtb030 get_search_k_client_data start ==========");
		// 検索ボタン押したときの処理
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT 
			    shbn,
			    kbn,
			    kraiteskcd,
			    kraitesknm,
			    kraiteskkn,
			    jyusho,
			    denwa, 
		        fax,
			    aittntobusyo,
			    aittntonm,
			    jyuyodo
			FROM 
			    srwtb030 
			WHERE 
			    shbn = ? 
			AND
			    kraiteskcd = ?;";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($shbn,$aite_cd));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== srwtb030 get_search_k_client_data end ==========");
		return $res;
	}

	/**
	 * 仮相手先情報の取得
	 * 
	 * @access	public
	 * @param	string $kari_cname = 仮相手先名
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
/*	function get_search_k_client_data($kari_cname=NULL,$other_flg=FALSE)
	{
		log_message('debug',"========== srwtb030 get_search_k_client_data start ==========");
		// 検索ボタン押したときの処理
		// 初期化
		$res = NULL;
		$other = NULL;
		// sql文作成
		$sql = "SELECT 
			shbn, kbn, kraiteskcd, kraitesknm, kraiteskkn, jyusho, denwa, 
		    fax, aittntobusyo, aittntonm, jyuyodo, createdate, updatedate
			FROM srwtb030 WHERE kraitesknm = ? AND deletedate IS NULL;";
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query($sql,array($shbn));
		if($query->num_rows() > 0)
		{
			$res = $query->row_array();
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== srwtb030 get_search_k_client_data end ==========");		
	}*/


	/**
	 * 仮相手先情報の更新
	 * 
	 * @access	public
	 * @param	string $post = 更新情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_kari_client($post=NULL)
	{
		//更新ボタン押したときの処理
		log_message('debug',"========== srwtb030 update_kari_client start ==========");
		foreach($post as $key => $check)
		{
			log_message('debug',"post[".$key."]=".$check);
		}
		// 初期化
		$res = NULL;
		$date = date("Ymd");
		// sql文作成
		$sql = "UPDATE srwtb030 SET 
				shbn = ?,
				kbn = ?,
				krAiteskCd = ?,
				krAiteskNm = ?,
				krAiteskKn = ?,
				jyusho = ?,
				denwa = ?,
				fax = ?,
				aitTntoBusyo = ?,
				aitTntoNm = ?,
				jyuyodo = ?,
				updateDate = ? 
			WHERE 
				shbn = ?
			AND 
			    krAiteskCd = ?
			";
		log_message('debug',"date=".$date);
		log_message('debug',"sql=".$sql);
		// クエリ実行
		$query = $this->db->query(
				$sql,
				array(
					$post['shbn'],	// sdbn
					$post['kbn'],		// kbn
					$post['kraiteskcd'],
					$post['kraitesknm'],
					$post['kraiteskkn'],
					$post['jyusho'],
					$post['denwa'],
					$post['fax'],
					$post['aittntobusyo'],
					$post['aittntonm'],
					$post['jyuyodo'],
					$date,
					$shbn,
					$post['kraiteskcd']
					)
				);
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== srwtb030 update_kari_client end ==========");
		return $res;
	}

	/**
	 * ユーザ情報の削除
	 * 
	 * @access	public
	 * @param	string $post = 削除情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function delete_kari_client($post)
	{
		// 削除ボタン押したときの処理
		log_message('debug',"========== srwtb030 delete_kari_client start ==========");
				
			// 初期化
			$res = NULL;
			$date = date("Ymd");
			// sql文作成
			$sql = "DELETE FROM srwtb030 WHERE shbn = ? AND krAiteskCd = ?";
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query($sql,array($post['shbn'],$post['kraiteskcd']));
		
		// 結果判定
		if($query)
		{
			$res = TRUE;
		}else{
			$res = FALSE;
		}
		log_message('debug',"========== srwtb030 delete_kari_client end ==========");
		return $res;	
	}
}

?>
