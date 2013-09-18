<?php
/*
 * @author goto 20120219
 */
class Sgmtb060 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	function get_info ($hanhoncd) {
		$sql = '';
		$sql .= 'SELECT * FROM sgmtb060 WHERE hanhoncd = \''.$hanhoncd.'\'';

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
			//社員区分が存在する場合
		  // sql文作成
			$sql = "";
			$sql .= "SELECT maker.aitesknm, maker.hanhoncd as aiteskcd ";
			$sql .= "FROM sgmtb010 staff, sgmtb060 maker, sgmtb100 umaker ";
			$sql .= "WHERE ";
			$sql .= "staff.shbn = umaker.shbn AND ";
			$sql .= "maker.hanhoncd = umaker.hanhoncd ";
			if ( isset($post['honbucd']) && $post['honbucd'] ) {  //本部コード
				$where_tmp[] = "staff.honbucd = '".$post['honbucd']."' ";
				$where_value[] = $post['honbucd'];
			}
			if ( isset($post['bucd']) && $post['bucd'] ) {        //部コード
				$where_tmp[] = "staff.bucd = '".$post['bucd']."' ";
				$where_value[] = $post['bucd'];
			}
			if ( isset($post['kacd']) && $post['kacd'] ) {        //課コード
				$where_tmp[] = "staff.kacd = '".$post['kacd']."' ";
				$where_value[] = $post['kacd'];
			}
			if ( isset($post['shbn']) && $post['shbn'] ) {  //社員区分
				$where_tmp[] = "staff.shbn = '".$post['shbn']."' ";
				$where_value[] = $post['shbn'];
			}
			if ( isset($post['aitesknm']) && $post['aitesknm'] ) {  //相手先名(店舗名称)
				$where_tmp[] = "maker.aitesknm LIKE '%".$post['aitesknm']."%' ";
				$where_value[] = '%'.$post['aitesknm'].'%';
			}
			if ( isset($post['pref']) && $post['pref'] ) {
				$where_tmp[] = "maker.jyusho LIKE '%".$post['pref']."%' ";
				$where_value[] = '%'.$post['pref'].'%';
			}
			
			$where_string = implode(' AND ', $where_tmp);
			if ( $where_string ) {
				$sql .= ' AND ';
			}
			$sql .= $where_string;
		log_message('debug',"==========================sgmtb060===============================");
		
		log_message('debug',"\$sql = $sql");
		$query = $this->db->query($sql);
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
