<?php

class Sgmtb070 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * 組織情報取得
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_ssk_data($post)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT * FROM sgmtb070 WHERE shbn = ? ";
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
	 * 組織情報更新
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function update_addssk_data($post)
	{
		try
		{
			// トランザクション開始
			$this->db->trans_start();
			// 初期化
			$res = NULL;
			$where = NULL;
			$date = date("Ymd");
			// WHERE条件作成
			$where = " WHERE shbn = ? ";
			// sql文作成
			$sql = "
				UPDATE sgmtb070 SET 
				honbucd = ?,
				bucd = ?,
				kacd = ?,
				shinnm = ?,
				hyojun = ?,
				updatedate = ?
				".$where;
			log_message('debug',"sql=".$sql);
			// クエリ実行
			$query = $this->db->query(
					$sql,
					array(
						$post['honbucd'],
						$post['bucd'],
						$post['kacd'],
						$post['user_name'],
						NULL,
						$date,
						$post['shbn']
						)
					);
			log_message('debug',$this->db->last_query());
			// 結果判定
			if ($this->db->trans_status() === TRUE)
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
			log_message('debug',"========== update_addssk_data end ==========");
			return $res;
		}catch(Exception $e){
			// ロールバック
			$this->db->trans_rollback();
			return $res;
		}
	}

	/**
	 * 相手先検索
	 * Enter description here ...
	 * @author goto 20120219
	 * @param unknown_type $post
	 */
	function search_select_client_data($post)
	{
		// 初期化
		$res = NULL;
		$where_value = array();
		if ( $post['shbn'] ) {
			// sql文作成
			$sql = "";
			$sql .= "SELECT * ";
			$sql .= "FROM sgmtb010 staff, sgmtb050 maker, sgmtb090 umaker ";
			$sql .= "WHERE ";
			$sql .= "staff.shbn = ? ";
			$sql .= "AND staff.shbn = umaker.shbn ";
			$sql .= "AND maker.aiteskcd = umaker.aiteskcd ";

			$where_value = array($post['shbn']);
		} else {
			$sql = "";
			$sql .= "SELECT * ";
			$sql .= "FROM sgmtb070 head, sgmtb050 maker, sgmtb090 umaker ";
			$sql .= "WHERE ";
			$sql .= "head.honbucd = ? ";
			$sql .= "AND head.bucd = ? ";
			$sql .= "AND head.kacd = ? ";
			$sql .= "AND maker.aitesknm LIKE ? ";
			$sql .= "AND head.shbn = umaker.shbn ";
			$sql .= "AND umaker.aiteskcd = maker.aiteskcd ";

			$where_value = array($post['honbucd'], $post['bucd'], $post['kacd'], $post['aitesknm']);
		}
		if ( $post['aitesknm'] ) {
			$sql .= "AND maker.aitesknm LIKE '%".$post['aitesknm']."%' ";
			$where_value = array_shift($where_value, $post['aitesknm']);
		}

		$query = $this->db->query($sql, $where_value);
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}

		return $res;

	}
	/**
	 * 組織情報をすべて取得
	 * @author goto 20120219
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_ssk_data_all($post, $business_unit)
	{
		// 初期化
		$res = NULL;
		// sql文作成
		$sql = "SELECT * FROM sgmtb020 WHERE busyu = ? ORDER BY honbucd, bucd, kacd;";
		$query = $this->db->query($sql,array($business_unit));
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
