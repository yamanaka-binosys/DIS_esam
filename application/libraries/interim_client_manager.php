<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Interim_client_manager {

	/**
	 * HTML-STRINGの作成
	 * 
	 * @access public
	 * @param  bool   $button_flg ボタン表示フラグ
	 * @param  array  $post       POSTデータ
	 * @return string $view_data  HTML文字列
	 */
	function set_view($button_flg = FALSE,$post = NULL){
		log_message('debug',"========== libraries interim_client_manager set_add_view start ==========");
		// 初期化
		$CI =& get_instance();
		$CI->load->helper('form');
		$CI->load->library('item_manager');
		
		$view_data = "";
		$view_data .= "<table>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>仮相手先名</td>\n";
		$view_data .= "<td style=\"width:350px;\">\n";
		$view_data .= "<input type=\"text\" name=\"kraitesknm\" size=\"20\" maxlength=\"20\" value=\"";
		$view_data .= (empty($post['kraitesknm'])) ? "": $post['kraitesknm'] ;
		$view_data .= "\">\n";
		// 選択ボタンの表示判定
		if($button_flg)
		{
			$view_data .= " <input type=\"button\" name=\"select\" value=\"選択\"";
			$view_data .= " onclick=\"location.href='".$CI->config->item('base_url')."index.php/select_client'\"";
			$view_data .= ">\n";
		}
		$view_data .= "</td>\n";
		$view_data .= "<td style=\"width:100px;\">仮相手先コード</td>\n";
		$view_data .= "<td style=\"width:200px;\">\n";
		$view_data .= "<input type=\"text\" name=\"kraiteskcd\" size=\"8\" maxlength=\"8\" value=\"";
		$view_data .= (empty($post['kraiteskcd'])) ? "": $post['kraiteskcd'] ;
		$view_data .= "\" readonly>\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>仮相手先カナ</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"kraiteskkn\" size=\"40\" maxlength=\"40\" value=\"";
		$view_data .= (empty($post['kraiteskkn'])) ? "": $post['kraiteskkn'] ;
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>登録社番</td>\n";
		$view_data .= "</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"shbn\" size=\"5\" maxlength=\"5\" value=\"";
		$view_data .= (empty($post['shbn'])) ? "": $post['shbn'] ;
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>住所</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"jyusho\" size=\"30\" maxlength=\"30\" value=\"";
		$view_data .= (empty($post['jyusho'])) ? "": $post['jyusho'] ;
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>電話</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"denwa\" size=\"12\" maxlength=\"12\" value=\"";
		$view_data .= (empty($post['denwa'])) ? "": $post['denwa'] ;
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>ＦＡＸ</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"fax\" size=\"12\" maxlength=\"12\" value=\"";
		$view_data .= (empty($post['fax'])) ? "": $post['fax'] ;
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>相手先担当部署</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"aittntobusyo\" size=\"20\" maxlength=\"20\" value=\"";
		$view_data .= (empty($post['aittntobusyo'])) ? "": $post['aittntobusyo'] ;
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>相手先担当者名</td>\n";
		$view_data .= "<td style=\"width:100px;\">\n";
		$view_data .= "<input type=\"text\" name=\"aittntonm\" size=\"8\" maxlength=\"8\" value=\"";
		$view_data .= (empty($post['aittntonm'])) ? "": $post['aittntonm'];
		$view_data .= "\">\n";
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>区分</td>\n";
		$view_data .= "<td style=\"width:130px;\">\n";
		$kbn_check = (empty($post['kbn'])) ? "001": $post['kbn'];
		$view_data .= $CI->item_manager->set_dropdown_in_db_string("011","kbn",$kbn_check);
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "<tr>\n";
		$view_data .= "<td>重要度</td>\n";
		$view_data .= "<td style=\"width:50px;\">\n";
		$imp_check = (empty($post['jyuyodo'])) ? "001": $post['jyuyodo'];
		$view_data .= $CI->item_manager->set_dropdown_in_db_string("012","jyuyodo",$imp_check);
		$view_data .= "</td>\n";
		$view_data .= "</tr>\n";
		$view_data .= "</table>\n";
		
		return $view_data;
		log_message('debug',"========== libraries interim_client_manager set_add_view end ==========");
	}
	
	/**
	 * 相手先選択結果からの登録済情報検索
	 * @param nothing
	 * @return array $result   検索結果
	 */
	public function get_aite_code()
	{
		$aite_cd = 'K0000001';
		return $aite_cd;
	}

	/**
	 * 相手先選択結果からの登録済情報検索
	 * @param string  aite_cd  選択された相手先コード
	 * @return array $result   検索結果
	 */
	public function get_interim_client_data($shbn = NULL,$aite_cd = NULL)
	{
		$result = FALSE;
		// 初期化
		$CI =& get_instance();
		$CI->load->model('srwtb030'); // 仮相手先情報
		if(!is_null($shbn) OR !is_null($aite_cd))
		{
			$result = $CI->srwtb030->get_search_k_client_data($shbn,$aite_cd); // 検索データ取得			
		}
		return $result;
	}
	
}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
