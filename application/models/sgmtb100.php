<?php

/*
 * @author goto 20120219
 */
class Sgmtb100 extends CI_Model {

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	/**
	 * 相手先検索（本部、販売店検索）
	 *
	 * @access	public
	 * @param	array $post = 登録情報
	 * @return	boolean $res = TRUE = 成功:FALSE = 失敗
	 */
	function get_select_client_list($data)
	{
		// 初期化
		$sql = '';
		$sql .= 'SELECT * FROM sgmtb060 maker, sgmtb100 umaker ';
		$sql .= 'WHERE maker.hanhoncd = umaker.hanhoncd ';
		$sql .= 'AND umaker.shbn = ? ';

		$query = $this->db->query($sql, array($data['shbn']));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}

		return $res;
	}
	function update_sgmtb($data)
	{
		$string_table = NULL;
		$CI =& get_instance();
		// DB名と同じものを指定
		$CI->load->model('sgmtb060'); // ユーザ別検索情報（相手先）
		$all_cnt = $CI->sgmtb060->get_info($data['hanhoncd']);

		$info = $all_cnt[0];

		if ( !$this->get_asign($data) ) {

			$sql = '';
			$sql .= 'INSERT INTO sgmtb100 VALUES(';
			$sql .= '?,?,?,?,?,?,?';
			$sql .= ');';

			$set = array(
			$data['shbn'],
			$info['kbn'],
			$info['hanhoncd'],
			date('Ymd'),
			date('Ymd'),
					'',
					''
			);
			$query = $this->db->query($sql, $set);
			if($query){
				return TRUE;
			}else{
				return FALSE;
			}

		} else {
			$sql = '';
			$sql .= 'UPDATE sgmtb100 ';
			$sql .= 'SET ';
			$sql .= 'kbn = ?, ';
			$sql .= 'updatedate = '.date('Ymd').' ';
			$sql .= 'WHERE shbn = ? AND hanhoncd = ? ';

			$set  = array($info['kbn'], $data['shbn'], $data['hanhoncd']);
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
		$sql .= 'SELECT * FROM sgmtb100 ';
		$sql .= 'WHERE ';
		$sql .= 'hanhoncd = ? ';
		$sql .= 'AND shbn = ? ';

		$query = $this->db->query($sql, array($data['hanhoncd'], $data['shbn']));
		if($query->num_rows() > 0)
		{
			$res = $query->result_array();
		}else{
			$res = FALSE;
		}
	}

}

?>
