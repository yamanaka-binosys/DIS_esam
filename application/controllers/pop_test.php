<?php

class Pop_test extends CI_Controller {
	/**
	 * ポップアップ呼び出し元画面(テスト用)
	 *
	 * @access	private
	 * @param	none
	 * @return	none
	 */
	function index()
	{
		try
		{
			log_message('debug',"========== pop_test start ==========");
			// テストデータ作成
			$common_data = $this->config->item('pop_test');
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // セッション情報からメニュー区分を取得
			$session_data = array('from_url' => $this->config->item('base_url')."/index.php/pop_test/index");
			$this->session->set_userdata($session_data);
			$checker_shbn = $this->session->userdata('checker_shbn');
			var_dump($checker_shbn);
					$this->session->unset_userdata('checker_shbn');
					$this->session->unset_userdata('busyo_shbn');
					$this->session->unset_userdata('group_shbn');

			$data['shinnm'] = "";
			$data['bunm'] = "";
			$data['group'] = "";
			// 初期共通項目情報
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			// 確認者部分データ
			$data['testdata'] = $data['shinnm'].$data['bunm'].$data['group'];
			// ヘッダ部生成 title css image errmsg を使用
			$data['header']    = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
			// メインコンテンツ生成 contents を使用
			$data['main']      = $this->load->view("parts/pop_test", $data, TRUE);
			// メニュー部生成 表示しない場合はNULL admin_flg を使用
			$data['menu']      = NULL;
			// フッダ部生成 必ず指定する事
			$data['footer']    = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
			// 表示処理
			$this->load->view(MY_VIEW_LAYOUT, $data);
			log_message('debug',"========== pop_test start ==========");
		}catch(Exception $e){
			// エラー表示
			$this->error_view($e);
		}
	}
	
}
/* End of file pop_test.php */
/* Location: ./application/controllers/pop_test.php */
