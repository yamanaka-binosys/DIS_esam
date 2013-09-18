<?php

class Kari extends CI_Model {
	
	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}
	
	/**
	 * 社番を引数にし、ユーザー情報を取得する
	 * 
	 * @access	public
	 * @param	string $shbn = 社番
	 * @return	boolean $res = TRUE=ユーザー情報、FALSE=失敗
	 */
	function get_user_data($shbn)
	{
		log_message('debug',"========== get_user_data start ==========");
		log_message('debug',"shbn=".$shbn);
		// 初期化
		$result = FALSE;
		// データがないので以下の仮データを返す
		// SQL文
		//$sql = "SELECT * FROM kari WHERE shbn = ?";
		//$query = $this->db->query($sql, array($shbn));
		// 仮データ
		$kari_data = array(
			'shbn'        => $shbn,            // 社番
			'shinnm'      => '仮名　前',       // 社員名
			'shinnmkn'    => 'カリナ マエ',   // 社員名カナ
			'honbucd'     => 'kari1',	      // 本部コード
			'bucd'        => 'bu001',         // 部コード
			'kacd'        => 'ka001',         // 課コード
			'menuhyjikbn' => '担当者',        // メニュー区分
			'etukngen'    => '00',            // 閲覧権限
			'passwd'      => 'pass'           // パスワード
		);
		$result['shbn'] = $kari_data['shbn'];
		$result['shinnm'] = $kari_data['shinnm'];
		$result['shinnmkn'] = $kari_data['shinnmkn'];
		$result['honbucd'] = $kari_data['honbucd'];
		$result['bucd'] = $kari_data['bucd'];
		$result['kacd'] = $kari_data['kacd'];
		$result['menuhyjikbn'] = $kari_data['menuhyjikbn'];
		$result['etukngen'] = $kari_data['etukngen'];
		$result['passwd'] = $kari_data['passwd'];
		
		/*log_message('debug',"query=".$query);
		if ($query->num_rows() > 0)
		{
			$row = $query->row_array();
			$result = $row;
		log_message('debug',"row=".$row);
		}
		 */
		log_message('debug',"========== get_user_data end ==========");
		return $result;
	}
}

?>
