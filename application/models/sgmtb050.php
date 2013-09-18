<?php

/*
 * @author goto 20120219
 */
class Sgmtb050 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	function get_info ($hanhoncd) {
		$sql = '';
		//$sql .= 'SELECT * FROM sgmtb050 WHERE hanhoncd = \''.$hanhoncd.'\'';
		$sql .= 'SELECT * FROM sgmtb050 WHERE hanhoncd = ?';

		$query = $this->db->query($sql,array($hanhoncd));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}

		return $res;
	}

	/**
	 * 相手先検索（本部）
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function search_select_client_data_head($post,$kbn=NULL)
	{
		// 初期化
		$res = NULL;
		$where_string = '';
		$where_value = array();
		$where_tmp = array();
		$where_string2 = '';
		$where_value2 = array();
		$where_tmp2 = array();
		$sql = '';
//		$if_count = 0;
		$kbn = implode('\',\'', $kbn);

		if ( isset($post['honbucd']) && $post['honbucd'] ) {
			$where_tmp[] = " head.honbucd = '".$post['honbucd']."' ";
			$where_value[] = $post['honbucd'];
		}
		if ( isset($post['bucd']) && $post['bucd'] ) {
			$where_tmp[] = " head.bucd = '".$post['bucd']."' ";
			$where_value[] = $post['bucd'];
		}
		if ( isset($post['kacd']) && $post['kacd'] ) {
			$where_tmp[] = " head.kacd = '".$post['kacd']."' ";
			$where_value[] = $post['kacd'];
		}
		if ( isset($post['shbn']) && $post['shbn'] ) {
			$where_tmp[] = " head.shbn = '".$post['shbn']."' ";
			$where_value[] = $post['shbn'];
		}

		$where_string = implode(' AND ', $where_tmp);

		$sql = "
			SELECT
				*
			FROM sgmtb050
			WHERE hanhoncd IN (
			SELECT 
			 distinct hanhoncd
			FROM sgmtb090 umaker
			where exists(
				select * from sgmtb010 head
				where head.shbn = umaker.shbn AND
				" . $where_string . "
			) AND kbn = '1') AND kbn = '1'
		";

		if (isset($post['aitesknm']) && $post['aitesknm'] ) {
			$sql .= " AND aitesknm LIKE '%".$post['aitesknm']."%' ";
		}
		$sql .= " ORDER BY jiscd ";

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
	 * 相手先検索（本部、販売店検索）
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function search_select_client_data($post, $kbn)
	{
		// 初期化
		$res = NULL;
		$where_string = '';
		$where_value = array();
		$where_tmp = array();
		$sql = '';
//		$if_count = 0;
		$kbn = implode('\',\'', $kbn);

		//if ( isset($post['shbn']) && $post['shbn'] ) {
			// sql文作成

			if ( isset($post['honbucd']) && $post['honbucd'] ) {
				$where_tmp[] = "head.honbucd = '".$post['honbucd']."' ";
				$where_value[] = $post['honbucd'];
			}
			if ( isset($post['bucd']) && $post['bucd'] ) {
				$where_tmp[] = "head.bucd = '".$post['bucd']."' ";
				$where_value[] = $post['bucd'];
			}
			if ( isset($post['kacd']) && $post['kacd'] ) {
				$where_tmp[] = "head.kacd = '".$post['kacd']."' ";
				$where_value[] = $post['kacd'];
			}
			if ( isset($post['shbn']) && $post['shbn'] ) {
				$where_tmp[] = "head.shbn = '".$post['shbn']."' ";
				$where_value[] = $post['shbn'];
			}

			$where_string = implode(' AND ', $where_tmp);
			$like_string = "";
			if (isset($post['aitesknm']) && $post['aitesknm'] ) {
				$like_string .= " AND aitesknm LIKE '%".$post['aitesknm']."%' ";
			}
			if ( isset($post['pref']) && $post['pref'] ) {
				$like_string .= " AND maker.jyusho LIKE '%".$post['pref']."%' ";
			}
			$sql = "
				SELECT
					*
				FROM
				(
					SELECT 
						DISTINCT
						maker.aitesknm, 
						maker.hanhoncd ,
						maker.aiteskcd, 
						maker.rank,
						maker.jiscd
					FROM
						(
							SELECT 
							DISTINCT
							*
							FROM sgmtb090 umaker 
							WHERE EXISTS( 
								SELECT * FROM sgmtb010 head 
								WHERE 
								umaker.shbn = head.shbn 
								AND umaker.kbn = '1' AND
								". $where_string . "
							)
						) umaker
					INNER JOIN sgmtb050 maker on umaker.hanhoncd = maker.hanhoncd 
					WHERE maker.kbn IN ('".$kbn."') 
					" . $like_string . "
				) maker
				ORDER BY maker.jiscd
 
			";
		log_message('debug',"=========================================================sgmtb050");

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

?>