<?php

/*
 * @author goto 20120219
 */
class Sgmtb051 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * 相手先検索（代理店検索）
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function search_select_client_data($post)
	{
			// 初期化
		$res = NULL;
		$where_string = '';
		$where_value = array();
		$where_tmp = array();
		$sql = '';
		$count = 0;

		// 全相手先より検索
		if ( isset($post['search']) && $post['search'] ) {
			$where_string = '';
			$where_tmp = array();
			if ( isset($post['aitesknm_all']) && $post['aitesknm_all'] ) {
				$where_tmp[] = 'aitesknm = ?';
				$where_value[] = '%'.$post['aitesknm'].'%';
			}
			$address = '';
			if ( isset($post['pref']) && $post['pref'] ) {
				$address .= $post['pref'];
			}
			if ( isset($post['other_address']) && $post['other_address'] ) {
				$address .= $post['other_address'];
			}
			if ( $address ) {
				$where_tmp[] = 'jyusho LIKE ?';
				$where_value[] = '%'.$address.'%';
			}
			$where_string = implode(' AND ', $where_tmp);
			$sql .= 'SELECT aitesknm, aiteskcd ';
			$sql .= 'FROM sgmtb051 ';
			$sql .= 'WHERE ';
			$sql .= $where_string;

			log_message('debug',"\$sql = $sql");
			$query = $this->db->query($sql, $where_value);
			$count = $query->num_rows();
		}
		if( $count > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}

	}

}

?>
