<?php

class Data_output extends MY_Controller {

	/**
	 * 情報出力
	 *
	 */
		function index()
	{
		try
		{
			log_message('debug',"========== data_output index start ==========");

			$data['title']		= '情報出力';                                            // タイトルに表示する文字列
			$data['css']		= 'data_ouput.css';                                                 // 個別CSSのアドレス
			$data['errmsg']		= "";                                                 // エラーメッセージ
			$data['image']		= 'images/data_output.gif';                                // タイトルバナーのアドレス
			$data['form_url']	= $this->config->item('base_url').'index.php/data_output/output/';   // 送信先
			$data['admin_flg']  = $this->session->userdata('admin_flg'); // 管理者フラグ
//			$data['admin_flg']	= FALSE;                                                 // 管理者フラグ（管理者=TRUE、一般=FALSE）

			$this->display($data); // 画面表示処理
			log_message('debug',"========== data_output index end ==========");
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'DATA-OUTPUT-INDEX'));
		}
	}

	private function getSampleFileName($reportType){

		$sampleFileName = "";

		$file_conig_arr = array();

		switch($reportType){
			case 1:	//訪問件数推移資料-月間活動件数縦推移表
				$file_conig_arr['sampleFileName'] = "sample1.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-訪問件数推移資料-月間活動件数縦推移表.xls";
				break;
			case 2:	//訪問件数推移資料-週間活動件数横推移表

				$file_conig_arr['sampleFileName'] = "sample2.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-訪問件数推移資料-週間活動件数横推移表.xls";
				break;
			case 3:	//月間活動区分別行動時間縦推移表

				$file_conig_arr['sampleFileName'] = "sample3.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-月間活動区分別行動時間縦推移表.xls";
				break;

			case 4:	//半期提案進捗状況横推移表

				$file_conig_arr['sampleFileName'] = "sample4.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-半期提案進捗状況横推移表.xls";
				break;
			case 5:	//企画獲得状況表縦推移表

				$file_conig_arr['sampleFileName'] = "sample5.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-企画獲得状況表縦推移表.xls";
				break;
			case 6:	//営業日報

				$file_conig_arr['sampleFileName'] = "sample6.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-営業日報.xls";
				break;

			case 7:	//情報メモ

				$file_conig_arr['sampleFileName'] = "sample7.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-情報メモ.xls";
				break;
			case 8:	//カレンダーA4

				$file_conig_arr['sampleFileName'] = "sample8.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-カレンダーA4.xls";
				break;
			case 9:	//カレンダーA3

				$file_conig_arr['sampleFileName'] = "sample9.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-カレンダーA3.xls";
				break;
				
			case 10:	//商談履歴
				$file_conig_arr['sampleFileName'] = "sample10.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-商談履歴.xls";
				break;
				
			case 11:	//日報一覧
				$file_conig_arr['sampleFileName'] = "sample11.xls";
				$file_conig_arr['downloadDisplayFileName'] = "サンプル-日報一覧.xls";
				break;

			default:
				$file_conig_arr = null;
				break;
		}
		return $file_conig_arr;
	}
	/**
	 * サンプル出力
	 *
	 */
	function sample_preview($reportType = null){

		//サンプルファイル名を取得
		$file_config_arr = $this->getSampleFileName($reportType);

		//不正なパラメータの場合ファイル情報が空となる。その場合なにもしない
		if(empty($file_config_arr)){
			exit();
		}

		//サンプルフォルダに存在する実ファイル名を構築
		$download_file_name = SAMPLE_DIR . $file_config_arr['sampleFileName'];
		//ダウンロード時に表示されるファイル名を取得
		$file_name = $file_config_arr['downloadDisplayFileName'];
		//ファイル名のエンコード処理
		$file_name = mb_convert_encoding($file_name, "Shift-JIS", "UTF-8");

		// ヘッダ出力
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: inline; filename=$file_name");
		// 対象ファイルを出力する。
		readfile($download_file_name);
		exit;
	}

	private function getOutputFileName($reportType){

		$sampleFileName = "";

		$file_conig_arr = array();

		switch($reportType){
			case 1:	//訪問件数推移資料-月間活動件数縦推移表
				$file_conig_arr['sampleFileName'] = "MonthCnt_BASE.xls";
				$file_conig_arr['downloadDisplayFileName'] = "訪問件数推移資料-月間活動件数縦推移表.xls";
				break;
			case 2:	//訪問件数推移資料-週間活動件数横推移表

				$file_conig_arr['sampleFileName'] = "WeekCnt_BASE.xls";
				$file_conig_arr['downloadDisplayFileName'] = "訪問件数推移資料-週間活動件数横推移表.xls";
				break;
			case 3:	//月間活動区分別行動時間縦推移表

				$file_conig_arr['sampleFileName'] = "MonthTime_BASE.xls";
				$file_conig_arr['downloadDisplayFileName'] = "月間活動区分別行動時間縦推移表.xls";
				break;

			case 4:	//半期提案進捗状況横推移表

				$file_conig_arr['sampleFileName'] = "HalfPrdPrp_BASE.xls";
				$file_conig_arr['downloadDisplayFileName'] = "半期提案進捗状況横推移表.xls";
				break;
			case 5:	//企画獲得状況表縦推移表

				$file_conig_arr['sampleFileName'] = "PlanGain_BASE.xls";
				$file_conig_arr['downloadDisplayFileName'] = "企画獲得状況表縦推移表.xls";
				break;
			default:
				$file_conig_arr = null;
			break;
		}
		return $file_conig_arr;
	}

	/**
	* 出力
	*
	*/
	function output($reportType = null){

		//サンプルファイル名を取得
		$file_config_arr = $this->getOutputFileName($reportType);

		//不正なパラメータの場合ファイル情報が空となる。その場合なにもしない
		if(empty($file_config_arr)){
			exit();
		}

		//サンプルフォルダに存在する実ファイル名を構築
		$download_file_name = REPORT_TEMPLATE_DIR . $file_config_arr['sampleFileName'];
		//ダウンロード時に表示されるファイル名を取得
		$file_name = $file_config_arr['downloadDisplayFileName'];
		//ファイル名のエンコード処理
		$file_name = mb_convert_encoding($file_name, "Shift-JIS", "UTF-8");

		// ヘッダ出力
		header("Content-Type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$file_name");
		// 対象ファイルを出力する。
		readfile($download_file_name);
		exit;
	}

	/**
	 * 共通で使用する表示処理
	 *
	 * @access  public
	 * @param   array $data 各種HTML作成時に必要な値
	 * @return  nothing
	 */
	function display($data)
	{
		try
		{
			log_message('debug',"========== data_output display start ==========");
			$this->load->view(MY_VIEW_DATA_OUTPUT, $data);
			// 表示処理
			log_message('debug',"========== data_output display end ==========");
		}catch(Exception $e){
			// エラー処理
//			$this->error_view($e);
$this->load->view('/parts/error/error.php',array('errcode' => 'DATA-OUTPUT-DISPLAY'));
		}
	}
}

/* End of file Data_output */
/* Location: ./application/controllers/Data_output */
