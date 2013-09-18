<?php

/*
 * @author goto 20120219
 */
class Sgmtb090 extends CI_Model {

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
	function get_select_client_list($data, $kbn)
	{
		// 初期化
		$sql = '';
		$sql .= 'SELECT * FROM sgmtb050 maker, sgmtb090 umaker ';
		$sql .= 'WHERE maker.hanhoncd = umaker.hanhoncd ';
		$sql .= 'AND umaker.shbn = ? ';
		$sql .= 'AND maker.kbn in (\''.$kbn.'\')';
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
		$CI->load->model('sgmtb050'); // ユーザ別検索情報（相手先）
		$all_cnt = $CI->sgmtb050->get_info($data['hanhoncd']);

		$info = $all_cnt[0];
		if ( !$this->get_asign($data) ) {
			echo '['.__LINE__.']';

			$sql = '';
			$sql .= 'INSERT INTO sgmtb090 VALUES(';
			$sql .= '?,?,?,?,?,?,?,?,?';
			$sql .= ');';

			$set = array(
				$info['kbn'],
				$data['shbn'],
				$info['hanhoncd'],
				$info['hanchicd'],
				$info['aiteskcd'],
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
			$sql .= 'UPDATE sgmtb090 ';
			$sql .= 'SET ';
			$sql .= 'kbn = ?, ';
			$sql .= 'hanchicd = ?, ';
			$sql .= 'aiteskcd = ?, ';
			$sql .= 'updatedate = '.date('Ymd').' ';
			$sql .= 'WHERE shbn = ? AND hanhoncd = ? ';

			$set  = array($info['kbn'], $info['hanchicd'], $info['aiteskcd'],
			$data['shbn'], $data['hanhoncd']);

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
		$sql .= 'SELECT * FROM sgmtb090 ';
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
		return $res;
	}
}

?>
