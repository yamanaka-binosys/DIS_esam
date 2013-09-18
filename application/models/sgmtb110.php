<?php

class Sgmtb110 extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * グループコードとグループ名を取得する
	 * 
	 * @access	public
	 * @param	nothing
	 * @return	boolean $res = TRUE=管理者、FALSE=一般
	 */
	function get_group_data()
	{
		// 初期化
		$res = FALSE;
		
		$sql = "SELECT htngrpcd,htngrpnm FROM sgmtb110 WHERE htngrpkbn = '10' AND deletedate IS NULL;";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			//$row = $query->row_array();
			if($query->num_rows() > 0)
			{
				$res = $query->result_array();
			}else{
				$res = FALSE;
			}
		}
		
		return $res;
	}
	
	/**
	 * グループコードからグループ名を取得する
	 * 
	 * @access	public
	 * @param	string $grpcd グループコード
	 * @return	string $res = グループ名、FALSE=失敗
	 */
	function get_group_name_data($grpcd)
	{
		// 初期化
		$res = FALSE;
		$sql = "SELECT htngrpnm FROM sgmtb110 WHERE htngrpcd = ? AND deletedate IS NULL;";
		$query = $this->db->query($sql,array($grpcd));
		if ($query->num_rows() > 0)
		{
			if($query->num_rows() > 0)
			{
				$grpnm = $query->row_array();
				$res = $grpnm['htngrpnm'];
			}else{
				$res = FALSE;
			}
		}
		
		return $res;
	}
	
}

?>
