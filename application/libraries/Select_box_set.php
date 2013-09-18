<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Select_box_set {
	
	/**
	 * SelectBoxのタイプ設定
	 *
	 * @access	public
	 * @param	array
	 * @return	string
	 */
	public function _set_select_type($type,$name,$post,$required=FALSE,$extra=NULL)
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		if(empty($post[$name]))
		{
			$post[$name] = "";
		}
		$select_box = "";
		
		switch($type)
		{
			case 'year': // 月
				$select_box = $this->set_date_box($type,$name,$post[$name],$required,$extra);
				break;
			case 'month': // 月
				$select_box = $this->set_date_box($type,$name,$post[$name],$required,$extra);
				break;
			case 'day':	  // 日
				$select_box = $this->set_date_box($type,$name,$post[$name],$required,$extra);
				break;
			case 'rank':  //ランク
				$data = $this->_get_rank_data(); // ランク情報取得
				$select_box = "ランク ";
				$select_box .= form_dropdown($name,$data,$post[$name]);
				break;
			case 'kubun': // 区分メニュー
				$data = $this->_get_kbn_data(); // 区分メニュー情報取得
				$select_box = form_dropdown($name,$data,$post[$name]);
				break;
			case 'important': // 重要度メニュー asakura
				$data = $this->_get_important_data(); // 重要度メニュー情報取得
				$select_box = form_dropdown($name,$data,$post[$name]);
				break;
		}
		return $select_box;
	}	
		
	/**
	 * 年月日セレクトボックスを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_date_box($type,$name,$select,$required=FALSE,$extra=NULL)
	{
		// 初期化
		$CI =& get_instance();
		
		$date_select_box = "";

		if($type === 'month'){
			$start = 1;
			$max = $CI->config->item('num_month');      // 開始月
			$kind = ' 月';
		}else if($type === 'day'){                     
			$start = 1;
			$max = $CI->config->item('max_day');       // 開始日
			$kind = ' 日';
		}else if($type === 'year'){
			$start = 2012;
			$max = $CI->config->item('max_year');      // 開始年
			$kind = ' 年';
		}
		
		
		// セレクトボックス作成
		
		$date_select_box .= "<select name=\"".$name."\"".$extra.">\n";
		    // 項目作成
		    if($required == FALSE){
		    	$date_select_box .= "<option value=\"\"></option>";
		    }
			for($i = $start; $i <= $max; $i++)
			{
				$date_select_box .= "<option value=\"".$i."\" ";
				// 選択項目の設定
				if($select == $i){
					$date_select_box .= "selected";
				}
				$date_select_box .= ">".$i."</option>\n";
			}
		$date_select_box .= "</select>".$kind;
		$date_select_box .= "\n";

		return $date_select_box;
	}
	/**
	 * ランク情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	public function _get_rank_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		
		// データ取得
		// 未完成
		/* テストデータ START */
		$rank_data = array('S','A','B','C');
		return $rank_data;
	}
	/**
	 * 区分メニュー情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	public function _get_kbn_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		
		// データ取得
		// 未完成
		/* テストデータ START */
		$rank_data = array('001' => '担当者','002' => '管理者');
		return $rank_data;
	}
	
	/**
	 * 重要度メニュー情報取得 asakura
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	public function _get_important_data()
	{
		$CI =& get_instance();
		//$CI->load->model('Model_name');
		
		// データ取得
		// 未完成
		/* テストデータ START */
		$important_data = array('高','中');
		return $important_data;
	}
}

// END Table_set class

/* End of file Table_set.php */
/* Location: ./application/libraries/Table_set.php */
