<?php

class Data_output_csv extends MY_Controller {

	/**
	 * 情報出力
	 *
	 */
	function index($type)
	{
		try
		{
			log_message('debug',"========== Data_output_csv index start ==========");
			$data = $this->init($type);
			$this->display($data); // 画面表示処理
			log_message('debug',"========== Data_output_csv index end ==========");
		}catch(Exception $e){
			// エラー処理
		}
	}

	/**
	 * 画面初期化処理
	 */
	function init($type){
		$data['title']		= '情報出力';                                            // タイトルに表示する文字列
		$data['css']		= 'data_ouput.css';                                                 // 個別CSSのアドレス
		$data['errmsg']		= "";                                                 // エラーメッセージ
		$data['image']		= 'images/data_output.gif';                                // タイトルバナーのアドレス
		$data['form_url']	= $this->config->item('base_url').'index.php/data_output_csv/output/';   // 送信先
		$data['admin_flg']	= FALSE;                                                 // 管理者フラグ（管理者=TRUE、一般=FALSE）
		$data['type']       = $type;

		// 情報メモ
		if($type == 7){
			$CI =& get_instance();
			
			$CI->load->library('table_manager');
			$CI->load->model(array('sgmtb030','sgmtb031'));
			$data['kbn_data'] = $CI->sgmtb031->get_koumoku_name('情報メモ');
			foreach($data['kbn_data'] as $i => $kbn_name) {
				$data['kbn_data'][$i]['kbn'] = $CI->sgmtb030->get_list_name($kbn_name['kbnid']);
			}
			$data['kbn_names'] = array(
				'001' => 'jyohokbnm',
				'002' => 'hinsyukbnm',
				'003' => 'tishokbnm',
				'013' => 'maker');
			$shbn= $CI->session->userdata('shbn');
			$data['search_result_busyo_table'] = $CI->table_manager->search_unit_set_b_table($shbn,NULL,NULL,NULL,"4");     // 部署検索
			
		// 日報一覧
		} else if($type == 10){
			$this->setNitiranData($data);

		// 商談履歴
		} else if($type == 11){
			$this->setSRirekiData($data);
			
		} else if($type == 12){
			$this->setProjectPossessionData($data);
		}
		
		return $data;
	}
	
	private function getCsvFileName($reportType, $kind=null){

		$sampleFileName = "";

		$file_conig_arr = array();

		switch($reportType){
			case 6:	//営業日報
				return "daily_report.csv";
				break;
			case 7:	//情報メモ
				return "memo.csv";
				break;

			case 8:	//カレンダーA4
				return "calendar_a4.csv";
				break;
				
			case 9:	//カレンダーA3
				return "calendar_a3.csv";
				break;

			case 10://日報一覧
				return "report_list_hon.csv";
				break;
/*
				switch($kind){
					case 1:	//販売店本部
						return "report_list_hon.csv";
						break;
					case 2:	//店舗
						return "report_list_ten.csv";
						break;
					case 3:	//代理店
						return "report_list_dairi.csv";
						break;
					case 4:	//内勤
						return "report_list_naikin.csv";
						break;
					case 5:	//業者
						return "report_list_gyosha.csv";
						break;
					default:
						return null;
						break;
				}
				break;
*/
			case 11://商談履歴
				return "s_rireki.csv";
				break;
				
			case 12://企画獲得
				return "project_possession.csv";
				break;

			default:
				return null;
				break;
		}
	}

	private function getExcelTemplateFileName($reportType){

		$sampleFileName = "";

		$file_conig_arr = array();

		switch($reportType){
			case 6:	//営業日報
				return "daily_report.xls";
				break;

			case 7:	//情報メモ
				return "memo.xls";
				break;

			case 8:	//カレンダーA4
				return "calendar_a4.xls";
				break;
			
			case 9:	//カレンダーA3
				return "calendar_a3.xls";
				break;

			case 10://日報一覧
				return "report_list.xls";
				break;

			case 11://商談履歴
				return "s_rireki.xls";
				break;
			case 12://商談履歴
				return "project_possession.xls";
				break;
			default:
				return null;
				break;
		}
	}

	/**
	 * 入力チェック
	 */
	function formValidation($type){
		// 情報メモ
		if($type == 7){
			// 日付（from to　MAX１ヶ月）のチェック
			$fromAdd1month = date('Ymd', strtotime($_POST['date_from'] . '+1 month'));
			$to = date('Ymd', strtotime($_POST['date_to']));
			if($fromAdd1month <= $to){
				$errmsg = '終了日は開始日から１ヶ月以内の日付を指定してください。';
			}
			
		}
		
		/*
		// カレンダーA4、カレンダーA3
		if($type == 8 || $type == 9){
			// 年月必須チェック
			
			if( $_POST['date_yyyymm'] == '' ){
				$errmsg = '年月は必須です。';
			
			} else {
				$month = substr($_POST['date_yyyymm'], 5, 2);
				$year = substr($_POST['date_yyyymm'], 1, 4);
				if(strlen($_POST['date_yyyymm']) != 6 || ! checkdate ((int)$month , 1, (int)$year )){
					$errmsg = '年月が不正です。';
				}
				
			}
		}
		*/
		// 入力エラー時
		if(isset($errmsg)){
			$data = $this->init($type);
			$data['errmsg'] = $errmsg;
			$this->display($data); // 画面表示処理
			return FALSE;
		}
		
		return TRUE;
	}
	
	/**
	 * csvファイル出力メソッド
	 * @param $type タイプ
	 */
	function output($type){
		
		// 入力チェック
		if(! $this->formValidation($type)){
			return;
		}
		
		//CodeIgniterの'zip'モジュールをロード
		$this->load->library('zip');

		//ダウンロードファイル名
		$download_file_name = date("ymd_his") .".zip";

		//テンプレートファイル名を取得
		$file = $this -> getExcelTemplateFileName($type);
		log_message('debug', $file);
		
		//不正なパラメータの場合ファイル情報が空となる。その場合なにもしない
		if(empty($file)){
			exit();
		}

		//テンプレートエクセルファイルをarchiveに追加
		$this->zip->read_file(REPORT_TEMPLATE_DIR . $file);

		$this->load->dbutil();

		$displayFileName = $this->getCsvFileName($type);
		switch($type){
			// 営業日報
			case 6:
				$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
				$from = date('Ymd', strtotime($_POST['date_from']));
				$to = date('Ymd', strtotime($_POST['date_to']));

				$result = $this->db->query(
					"SELECT BU.BUNM AS ユニット, s010.SHINNM AS 氏名\n".
					' FROM SGMTB010 s010'."\n".
					' INNER JOIN SGMTB020 BU ON'."\n".
					'	s010.HONBUCD=BU.HONBUCD'."\n".
					'	AND s010.BUCD=BU.BUCD'."\n".
					'	AND s010.KACD=BU.KACD'."\n".
					' WHERE '."\n".
					'	  s010.SHBN='."'" . $shbn ."'");

				$csv = mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				$result = $this->db->query(
					" SELECT \n".
					" 	substr(Ymd,1,4) || '/' || substr(Ymd,5,2) || '/' || substr(Ymd,7,2)   AS 日付,\n".
					" 	substr(StHm,1,2) || ':' || substr(StHm,3,2) AS 開始時刻,\n".
					" 	'～' AS  ～,\n".
					" 	substr(EdHm,1,2) || ':' || substr(EdHm,3,2) AS 終了時刻,\n".
					" 	AiteskNm AS 訪問先,\n".
					" 	RANK AS ランク,\n".
					" 	MendanNm01 AS 面談者,\n".
					" 	NAIYO AS 内容,\n".
					" 	JISSI AS 実施内容\n".
					" FROM\n".
					" 	(\n".
					" 	SELECT \n".	
					" 		base1.Ymd AS Ymd,\n".
					" 		base1.StHm AS StHm,\n".
					" 		base1.EdHm AS EdHm,\n".
					" 		base1.AiteskNm AS AiteskNm,\n".
					" 		base1.aiteskrank AS RANK,\n".
					" 		base1.MendanNm01  AS MendanNm01,\n".
					" 		CASE ".
					" 			WHEN base1.Sdn_Mitumori='1' THEN '見積り提示 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_SiyoKaknin='1' THEN '採用企画の確認 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_HnbiKekaku='1' THEN '販売計画 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_Claim='1' THEN 'クレーム対応 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_UribaTan='1' THEN '売場提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_HnkTan='1' THEN '半期提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_Shohin='1' THEN '商品案内'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_DonyuTan='1' THEN '導入提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_MDTeian='1' THEN 'ＭＤ提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_Tanawari='1' THEN '棚割提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_Tnwrbi='1' THEN '販売店の棚割日情報'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_DonyuTume='1' THEN '導入日の詰め'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN base1.Sdn_TnwrKeka='1' THEN '棚割結果確認'".
					"		ELSE '' ".
					" 		END AS NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(base1.Sdn_Yotei,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI\n".
					" 	FROM SRNTB110 base1 \n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM srntb110 GROUP BY jyohonum) eda1 \n".
					"	ON base1.jyohonum = eda1.jyohonum and base1.edbn = eda1.edbn \n".
					" 	WHERE\n".
					" 		base1.Shbn=?\n".
					" 		AND base1.Ymd>=?\n".
					" 		AND base1.Ymd<=?\n".
					" 	UNION\n".

					" 	SELECT \n".			
					" 		base2.Ymd,\n".
					" 		base2.StHm,\n".
					" 		base2.EdHm,\n".
					" 		base2.AiteskNm,\n".
					" 		base2.aiteskrank AS RANK,\n".
					" 		base2.MendanNm01,\n".
					" 		CASE ".
					" 			WHEN base2.Ktd_JohoSusu='1' THEN '情報収集'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_JohoAnai='1' THEN '商品情報案内'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_TnkiKosyo='1' THEN '展開場所・ｱｳﾄ展開交渉'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_SuisnHanbai='1' THEN '推奨販売交渉'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_Jyutyu='1' THEN '受注促進'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_ShdnIgai='1' THEN '商談以外の内容'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_Satuei='1' THEN '売場撮影'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_Mente='1' THEN '売場メンテナンス'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_Zaiko='1' THEN '在庫確認'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_Hoju='1' THEN '商品補充'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_HanskSeti='1' THEN '販促物の設置'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base2.Ktd_Yamazumi='1' THEN '山積み'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE\n".
					" 			WHEN base2.Ktd_Beta='1' THEN 'ﾍﾞﾀ付け'\n".
					"		ELSE '' ".
					" 		END AS NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(base2.Sagyo_Yotei,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI\n".
					" 	FROM SRNTB120 base2 \n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM srntb120 GROUP BY jyohonum) eda2 \n".
					"	ON base2.jyohonum = eda2.jyohonum and base2.edbn = eda2.edbn \n".
					" 	WHERE\n".
					" 		base2.Shbn=?\n".
					" 		AND base2.Ymd>=?\n".
					" 		AND base2.Ymd<=?\n".
					" 	UNION\n".
					" 	SELECT \n".
					" 		base3.Ymd,\n".
					" 		base3.StHm,\n".
					" 		base3.EdHm,\n".
					" 		base3.AiteskNm,\n".
					" 		NULL AS RANK,\n".
					" 		base3.MendanNm01,\n".
					" 		CASE ".
					" 			WHEN base3.Sdn_MitsumoriFollow='1' THEN '一般店見積り提示・商談フォロー　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_shohnjoho='1' THEN '商品情報案内　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_Kikaku='1' THEN '企画案内　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_Jiseki='1' THEN '実績報告　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_Mitsumori='1' THEN '見積り提示　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_JizenUtiawase='1' THEN '販売店商談事前打合せ　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_KikakuJyoukyou='1' THEN '情報収集・規格導入状況確認　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_Logistics='1' THEN '受発注・物流関連　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base3.Sdn_Torikmi='1' THEN '取組会議　'\n".
					"		ELSE '' ".
					" 		END AS NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(base3.Sdn_Yotei,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI\n".
					" 	FROM SRNTB130 base3\n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM srntb130 GROUP BY jyohonum) eda3 \n".
					"	ON base3.jyohonum = eda3.jyohonum and base3.edbn = eda3.edbn \n".
					" 	WHERE\n".
					" 		base3.Shbn=?\n".
					" 		AND base3.Ymd>=?\n".
					" 		AND base3.Ymd<=?\n".
					" 	UNION\n".
					" 	SELECT \n".
					" 		base4.Ymd,\n".
					" 		base4.StHm,\n".
					" 		base4.EdHm,\n".
					" 		'社内業務' as AiteskNm,\n".
					" 		NULL AS RANK,\n".
					" 		NULL AS MendanNm01,\n".
					" 		CASE\n".
					" 			WHEN SagyoNiyo='001' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='001' ) \n".
					" 			WHEN SagyoNiyo='002' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='002' ) \n".
					" 			WHEN SagyoNiyo='003' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='003' ) \n".
					" 			WHEN SagyoNiyo='004' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='004' ) \n".
					" 			WHEN SagyoNiyo='005' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='005' ) \n".
					" 			WHEN SagyoNiyo='006' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='006' ) \n".
					" 			WHEN SagyoNiyo='007' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='007' ) \n".
					" 			WHEN SagyoNiyo='008' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='008' ) \n".
					" 			WHEN SagyoNiyo='009' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='009' ) \n".
					" 			WHEN SagyoNiyo='010' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='010' ) \n".
					" 			WHEN SagyoNiyo='011' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='011' ) \n".
					" 			WHEN SagyoNiyo='012' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='012' ) \n".
					" 			WHEN SagyoNiyo='013' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='013' ) \n".
					" 			WHEN SagyoNiyo='014' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='014' ) \n".
					" 			WHEN SagyoNiyo='015' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='015' ) \n".
					" 			WHEN SagyoNiyo='016' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='016' ) \n".
					" 			WHEN SagyoNiyo='017' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='017' ) \n".
					" 			WHEN SagyoNiyo='018' THEN SntSagyo\n".
					" 			WHEN SagyoNiyo='019' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='019' ) \n".
					" 			WHEN SagyoNiyo='020' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='020' ) \n".
					" 			WHEN SagyoNiyo='021' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='021' ) \n".
					" 			WHEN SagyoNiyo='022' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='022' ) \n".
					" 			WHEN SagyoNiyo='023' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='023' ) \n".
					" 			WHEN SagyoNiyo='024' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='024' ) \n".
					" 			WHEN SagyoNiyo='025' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='025' ) \n".
					" 			WHEN SagyoNiyo='026' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='026' ) \n".
					" 			WHEN SagyoNiyo='027' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='027' ) \n".
					" 			WHEN SagyoNiyo='028' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='028' ) \n".
					" 			WHEN SagyoNiyo='029' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='029' ) \n".
					" 			WHEN SagyoNiyo='030' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='030' ) \n".
					"			ELSE '' ".
					" 		END AS  NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(base4.Kekka,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI\n".
					" 	FROM SRNTB140 base4\n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM SRNTB140 GROUP BY jyohonum) eda4 \n".
					"	ON base4.jyohonum = eda4.jyohonum and base4.edbn = eda4.edbn \n".
					" 	WHERE\n".
					" 		base4.Shbn=?\n".
					" 		AND base4.Ymd>=?\n".
					" 		AND base4.Ymd<=?\n".
					" 	) A\n".
					
					" ORDER BY Ymd,StHm,EdHm",
					array($shbn, $from, $to, $shbn, $from, $to, $shbn, $from, $to,$shbn, $from, $to)
				);
				
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				$result = $this->db->query(
					" SELECT \n".
					" 	substr(Ymd,1,4) || '/' || substr(Ymd,5,2) || '/' || substr(Ymd,7,2)   AS 日付,\n".
					" 	substr(StHm,1,2) || ':' || substr(StHm,3,2) AS 開始時刻,\n".
					" 	'～' AS  ～,\n".
					" 	substr(EdHm,1,2) || ':' || substr(EdHm,3,2) AS 終了時刻,\n".
					" 	AiteskNm AS 訪問先,\n".
					" 	RANK AS ランク,\n".
					" 	MendanNm01 AS 面談者,\n".
					" 	NAIYO AS 内容,\n".
					" 	JISSI AS 実施内容,\n".
					" 	sijicmt01 AS ユニット長のコメント\n".
					" FROM\n".
					" (\n".
					" 	SELECT \n".
					" 	Ymd,\n".
					" 	StHm,\n".
					" 	EdHm,\n".
					" 	AiteskNm,\n".
					" 	NULL AS RANK,\n".
					" 	MendanNm01,\n".
					" 		CASE ".
					" 			WHEN Sdn_Mitumori='1' THEN '見積り提示 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_SiyoKaknin='1' THEN '採用企画の確認 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_HnbiKekaku='1' THEN '販売計画 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_Claim='1' THEN 'クレーム対応 '".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_UribaTan='1' THEN '売場提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_HnkTan='1' THEN '半期提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_Shohin='1' THEN '商品案内'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_DonyuTan='1' THEN '導入提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_MDTeian='1' THEN 'ＭＤ提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_Tanawari='1' THEN '棚割提案'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_Tnwrbi='1' THEN '販売店の棚割日情報'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_DonyuTume='1' THEN '導入日の詰め'".
					"		ELSE '' ".
					" 		END || ".
					"		CASE ".
					" 			WHEN Sdn_TnwrKeka='1' THEN '棚割結果確認'".
					"		ELSE '' ".
					" 		END AS NAIYO,\n".
					" 	('「成約内容」：' || regexp_replace(regexp_replace(regexp_replace(SeiykNiyo,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  || \n".
					" 	'　/　「不成約内容」：' || regexp_replace(regexp_replace(regexp_replace(FseiykNiyo,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  || \n".
					" 	'　/　「保留内容」：' || regexp_replace(regexp_replace(regexp_replace(HoryuNiyo,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  || \n".
					" 	'　/　「その他内容」：' || regexp_replace(regexp_replace(regexp_replace(SonotaNiyo,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  ) AS JISSI, \n".
					"	regexp_replace(regexp_replace(regexp_replace(sijicmt01,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g') AS sijicmt01 \n".
					" 	FROM SRNTB010 base01\n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM SRNTB010 GROUP BY jyohonum) eda01 \n".
					"	ON base01.jyohonum = eda01.jyohonum and base01.edbn = eda01.edbn \n".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND Ymd>=?\n".
					" 		AND Ymd<=?\n".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" 		NULL AS RANK,\n".
					" 		MendanNm01,\n".
					" 		CASE ".
					" 			WHEN Ktd_TnpShdn='1' THEN '店舗商談'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_JohoSusu='1' THEN '情報収集'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_JohoAnai='1' THEN '商品情報案内'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_TnkiKosyo='1' THEN '展開場所・ｱｳﾄ展開交渉'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_SuisnHanbai='1' THEN '推奨販売交渉'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_Jyutyu='1' THEN '受注促進'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_ShdnIgai='1' THEN '商談以外の内容'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_Satuei='1' THEN '売場撮影'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_Mente='1' THEN '売場メンテナンス'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_Zaiko='1' THEN '在庫確認'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_Hoju='1' THEN '商品補充'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_HanskSeti='1' THEN '販促物の設置'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN Ktd_Yamazumi='1' THEN '山積み'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE\n".
					" 			WHEN Ktd_Beta='1' THEN 'ﾍﾞﾀ付け'\n".
					"		ELSE '' ".
					" 		END AS NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(sdn_niyo,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI, \n".
					"	regexp_replace(regexp_replace(regexp_replace(sijicmt01,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g') AS sijicmt01 \n".
					" 	FROM SRNTB020 base02\n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM SRNTB020 GROUP BY jyohonum) eda02 \n".
					"	ON base02.jyohonum = eda02.jyohonum and base02.edbn = eda02.edbn \n".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND Ymd>=?\n".
					" 		AND Ymd<=?\n".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" 		NULL AS RANK,\n".
					" 		MendanNm01,\n".
					" 		CASE ".
					" 			WHEN base03.Sdn_MitsumoriFollow='1' THEN '一般店見積り提示・商談フォロー　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_shohnjoho='1' THEN '商品情報案内　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_Kikaku='1' THEN '企画案内　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_Jiseki='1' THEN '実績報告　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_Mitsumori='1' THEN '見積り提示　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_JizenUtiawase='1' THEN '販売店商談事前打合せ　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_KikakuJyoukyou='1' THEN '情報収集・規格導入状況確認　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_Logistics='1' THEN '受発注・物流関連　'\n".
					"		ELSE '' ".
					" 		END || ".
					" 		CASE ".
					" 			WHEN base03.Sdn_Torikmi='1' THEN '取組会議　'\n".
					"		ELSE '' ".
					" 		END AS NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(sdn_niyo,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI, \n".
					"	regexp_replace(regexp_replace(regexp_replace(sijicmt01,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g') AS sijicmt01 \n".
					" 	FROM SRNTB030 base03 \n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM SRNTB030 GROUP BY jyohonum) eda03 \n".
					"	ON base03.jyohonum = eda03.jyohonum and base03.edbn = eda03.edbn \n".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND Ymd>=?\n".
					" 		AND Ymd<=?\n".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		'社内業務' as AiteskNm,\n".
					" 		NULL AS RANK,\n".
					" 		NULL AS MendanNm01,\n".
					" 		CASE\n".
					" 			WHEN SagyoNiyo='001' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='001' ) \n".
					" 			WHEN SagyoNiyo='002' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='002' ) \n".
					" 			WHEN SagyoNiyo='003' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='003' ) \n".
					" 			WHEN SagyoNiyo='004' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='004' ) \n".
					" 			WHEN SagyoNiyo='005' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='005' ) \n".
					" 			WHEN SagyoNiyo='006' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='006' ) \n".
					" 			WHEN SagyoNiyo='007' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='007' ) \n".
					" 			WHEN SagyoNiyo='008' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='008' ) \n".
					" 			WHEN SagyoNiyo='009' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='009' ) \n".
					" 			WHEN SagyoNiyo='010' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='010' ) \n".
					" 			WHEN SagyoNiyo='011' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='011' ) \n".
					" 			WHEN SagyoNiyo='012' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='012' ) \n".
					" 			WHEN SagyoNiyo='013' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='013' ) \n".
					" 			WHEN SagyoNiyo='014' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='014' ) \n".
					" 			WHEN SagyoNiyo='015' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='015' ) \n".
					" 			WHEN SagyoNiyo='016' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='016' ) \n".
					" 			WHEN SagyoNiyo='017' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='017' ) \n".
					" 			WHEN SagyoNiyo='018' THEN SntSagyo\n".
					" 			WHEN SagyoNiyo='019' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='019' ) \n".
					" 			WHEN SagyoNiyo='020' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='020' ) \n".
					" 			WHEN SagyoNiyo='021' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='021' ) \n".
					" 			WHEN SagyoNiyo='022' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='022' ) \n".
					" 			WHEN SagyoNiyo='023' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='023' ) \n".
					" 			WHEN SagyoNiyo='024' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='024' ) \n".
					" 			WHEN SagyoNiyo='025' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='025' ) \n".
					" 			WHEN SagyoNiyo='026' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='026' ) \n".
					" 			WHEN SagyoNiyo='027' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='027' ) \n".
					" 			WHEN SagyoNiyo='028' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='028' ) \n".
					" 			WHEN SagyoNiyo='029' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='029' ) \n".
					" 			WHEN SagyoNiyo='030' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='030' ) \n".
					"			ELSE '' ".
					" 		END AS NAIYO,\n".
					" 		regexp_replace(regexp_replace(regexp_replace(kekka,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g')  AS JISSI, \n".
					"	regexp_replace(regexp_replace(regexp_replace(sijicmt01,'\r\n','**BR**','g'),'\r','**BR**','g'),'\n','**BR**','g') AS sijicmt01 \n".
					" 	FROM SRNTB040 base04 \n".
					"   INNER JOIN \n".
					"	(SELECT jyohonum, max(edbn) as edbn FROM SRNTB040 GROUP BY jyohonum) eda04 \n".
					"	ON base04.jyohonum = eda04.jyohonum and base04.edbn = eda04.edbn \n".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND Ymd>=?\n".
					" 		AND Ymd<=?\n".
					" 	) A\n".
					" ORDER BY Ymd,StHm,EdHm",
					array($shbn, $from, $to, $shbn, $from, $to, $shbn, $from, $to,$shbn, $from, $to)
				);
				
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");
				break;
				
			
			// 情報メモ
			case 7:
				$sql = " 
				with edmax as (
					SELECT * FROM srktb050 base
					INNER JOIN (SELECT jyohonum, MAX(edbn) AS edbn FROM srktb050
					GROUP BY jyohonum) eda
					ON base.jyohonum = eda.jyohonum AND base.edbn = eda.edbn
				),
				jyhoho as (
					SELECT kbncd,ichiran FROM sgmtb030 WHERE ichiran <> '' AND kbnid = ?
				),
				hinsyu as (
					SELECT kbncd,ichiran FROM sgmtb030 WHERE ichiran <> '' AND kbnid = ?
				),
				tisho as (
					SELECT kbncd,ichiran FROM sgmtb030 WHERE ichiran <> '' AND kbnid = ?
				),
				maker as (
					SELECT kbncd,ichiran FROM sgmtb030 WHERE ichiran <> '' AND kbnid = ?
				)
				
				SELECT 
				 		CASE 
			 			WHEN JOHO.EtuJukyo='1' THEN '●' 
			 			ELSE ' ' 
			 			END 
						AS ユニット長閲覧状況, 
					 	substr(JOHO.CreateDate,1,4) || '/' || substr(JOHO.CreateDate,5,2) || '/' || substr(JOHO.CreateDate,7,2)  AS 登録日,
					 	BU.BuNm AS 担当営業部,
					 	UNIT.BuNm AS 担当ユニット,
					 	USER2.ShinNm AS 担当者,
					 	JOHO.KnNm AS 件名,
					 	JOHO.AiteskNm AS 入手先,
					 	(SELECT ichiran FROM jyhoho WHERE kbncd = JOHO.JyohoKbNm) AS 情報区分,
					 	(SELECT ichiran FROM hinsyu WHERE kbncd = JOHO.HinsyuKbNm) AS 品種区分,
					 	(SELECT ichiran FROM tisho WHERE kbncd = JOHO.TishoKbNm) AS 対象区分,
					 	(SELECT ichiran FROM maker WHERE kbncd = JOHO.Maker) AS メーカー,
					 	JOHO.JyohoNiyo AS 内容
					 FROM
					 	edmax JOHO
					 	INNER JOIN SGMTB010 USER2 ON
					 		JOHO.shbn=USER2.Shbn
					 	left JOIN SGMTB020 BU ON
					 		USER2.HonbuCd=BU.HonbuCd
					 		AND USER2.BuCd=BU.BuCd
					 	left JOIN SGMTB020 UNIT ON
					 		USER2.HonbuCd=UNIT.HonbuCd
					 		AND USER2.BuCd=UNIT.BuCd
					 		AND USER2.KaCd=UNIT.KaCd
					 WHERE
					 	BU.KaCd='XXXXX' 
					 	AND JOHO.CreateDate>=?
					 	AND JOHO.CreateDate<=?";
				
				$from = date('Ymd', strtotime($_POST['date_from']));
				$to = date('Ymd', strtotime($_POST['date_to']));
	
				$arrWhere = array('001', '002', '003', '013', $from, $to);
								
				// 件名
				if (!empty($_POST['title'])) {
					$sql .= " AND JOHO.KnNm LIKE ? ";
					array_push($arrWhere, '%'.$_POST['title'].'%');
				}
				
				// 内容
				if (!empty($_POST['body'])) {
					$sql .= " AND JOHO.JyohoNiyo LIKE ? ";
					array_push($arrWhere, '%'.$_POST['body'].'%');
				}

				// 情報区分
				if(!empty($_POST['jyohokbnm'])){
					$sql .= " AND JOHO.JyohoKbNm = ?";
					array_push($arrWhere, $_POST['jyohokbnm']);
				}
				// 品種区分
				if(!empty($_POST['hinsyukbnm'])){
					$sql .= " AND JOHO.HinsyuKbNm = ?";
					array_push($arrWhere, $_POST['hinsyukbnm']);
				}
				if(!empty($_POST['tishokbnm'])) {
					$sql .= " AND JOHO.TishoKbNm = ?";
					array_push($arrWhere, $_POST['tishokbnm']);
				}
				if(!empty($_POST['maker'])) {
					$sql .= " AND JOHO.Maker = ?";
					array_push($arrWhere, $_POST['maker']);
				}
				
				if(!empty($_POST['honbucd'])) {
					if($_POST['honbucd']!="XXXXX"){
						$sql .= " AND USER2.honbucd = ?";
						array_push($arrWhere, $_POST['honbucd']);
					}
				}
				if(!empty($_POST['bucd'])) {
					if($_POST['bucd']!="XXXXX"){
						$sql .= " AND USER2.bucd = ?";
						array_push($arrWhere,  substr($_POST['bucd'],5));
					}
				}
				if(!empty($_POST['kacd'])) {
					if($_POST['kacd']!="XXXXX"){
						$sql .= " AND USER2.kacd = ?";
						array_push($arrWhere,  substr($_POST['kacd'],10));
					}
				}
				if(!empty($_POST['user'])) {
					$sql .= " AND JOHO.shbn = ?";
					array_push($arrWhere,  $_POST['user']);
				}
				$result = $this->db->query($sql, $arrWhere);
				$csv = mb_convert_encoding($this->dbutil->csv_from_result($result), "Windows-31J", "UTF-8");
				
				break;
			

			//カレンダーA4
			case 8:
				// 社員情報取得
				$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
				log_message('debug',"s_year = ".$_POST['s_year']);
				log_message('debug',"s_month = ".$_POST['s_month']); 
				$date =$_POST['s_year'].$_POST['s_month'];

				// 社員情報
				$result = $this->db->query(
					" SELECT BU.BUNM AS ユニット, s010.SHINNM AS 氏名\n".
					" FROM SGMTB010 s010\n".
					" INNER JOIN SGMTB020 BU ON\n".
					" 	s010.HONBUCD=BU.HONBUCD\n".
					" 	AND s010.BUCD=BU.BUCD\n".
					" 	AND s010.KACD=BU.KACD\n".
					" WHERE \n".
					" 	  s010.SHBN=?", array($shbn)
				);

				$csv = mb_convert_encoding($this->dbutil->csv_from_result($result), "Windows-31J", "UTF-8");

				// 件数取得
								// 訪問件数実績
				$result = $this->db->query(
					" SELECT \n".
					" A.CNT+B.CNT+C.CNT AS 訪問件数実績, \n".
					" ? AS 選択年, ? AS 選択月    FROM\n".
					" (\n".
					" SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB010 base1\n".
					" INNER JOIN ".
					" (SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB010".
					" GROUP BY jyohonum) eda1".
					" ON base1.jyohonum = eda1.jyohonum AND base1.edbn = eda1.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) A,\n".
					" (\n".
					" SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB020 base2\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB020  ".
					" GROUP BY jyohonum) eda2 ".
					" ON base2.jyohonum = eda2.jyohonum AND base2.edbn = eda2.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) B,\n".
					" ( SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB030 base3\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB030".
					" GROUP BY jyohonum) eda3".
					" ON base3.jyohonum = eda3.jyohonum AND base3.edbn = eda3.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) C,\n".
					" ( SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB040 base4\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB040".
					" GROUP BY jyohonum) eda4".
					" ON base4.jyohonum = eda4.jyohonum AND base4.edbn = eda4.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) D,\n".
					" ( SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB060 base6\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB060".
					" GROUP BY jyohonum) eda6".
					" ON base6.jyohonum = eda6.jyohonum AND base6.edbn = eda6.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) E\n",
				array($_POST['s_year'],$_POST['s_month'],$shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date)
				);
				/*
				$result = $this->db->query(
					" SELECT \n".
					" A.CNT+B.CNT+C.CNT+D.CNT+E.CNT AS 訪問件数実績 FROM\n".
					" (\n".
					" SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB010\n".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) A,\n".
					" (\n".
					" SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB020\n".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) B,\n".
					" (SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB030 \n".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) C, \n".
					" (SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB040 \n".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) D, \n".
					" (SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB060 \n".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) E",
				array($shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date)
				);
				*/
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				// 実績
				$result = $this->db->query(
					" SELECT \n".
					" 	Ymd AS 日付,\n".
					" 	StHm AS 開始時刻,\n".
					" 	EdHm AS 終了時刻,\n".
					" 	AiteskNm AS 相手先,\n".
					" 	NAIYO AS 内容\n".
					" FROM\n".
					" (\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" CASE\n".
					" 	WHEN Sdn_Mitumori='1' THEN '見積り提示 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_SiyoKaknin='1' THEN '採用企画の確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnbiKekaku='1' THEN '販売計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Claim='1' THEN 'クレーム対応 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_UribaTan='1' THEN '売場提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnkTan='1' THEN '半期提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Shohin='1' THEN '商品案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTan='1' THEN '導入提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_MDTeian='1' THEN 'ＭＤ提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tanawari='1' THEN '棚割提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tnwrbi='1' THEN '販売店の棚割日情報 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n ".
					" 	WHEN Sdn_DonyuTume='1' THEN '導入日の詰め '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKeka='1' THEN '棚割結果確認 '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB010 srn010 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB010 GROUP BY jyohonum) eda010 
						ON srn010.jyohonum = eda010.jyohonum and srn010.edbn = eda010.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		srn020.Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" CASE\n".
					" 	WHEN Ktd_TnpShdn='1' THEN '店舗商談 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoSusu='1' THEN '情報収集 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoAnai='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_TnkiKosyo='1' THEN '展開場所・ｱｳﾄ展開交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_SuisnHanbai='1' THEN '推奨販売交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Jyutyu='1' THEN '受注促進 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_ShdnIgai='1' THEN '商談以外の内容 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Satuei='1' THEN '売場撮影 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Mente='1' THEN '売場メンテナンス '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Zaiko='1' THEN '在庫確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Hoju='1' THEN '商品補充 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_HanskSeti='1' THEN '販促物の設置 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Yamazumi='1' THEN '山積み '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Beta='1' THEN 'ﾍﾞﾀ付け '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB020 srn020\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB020 GROUP BY jyohonum) eda020 
						ON srn020.jyohonum = eda020.jyohonum and srn020.edbn = eda020.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		srn030.Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" CASE\n".
					" 	WHEN Sdn_ShohnJoho='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Kikaku='1' THEN '企画案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Jiseki='1' THEN '実績報告 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Utiawase='1' THEN 'セールスと打ち合せ '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Torikmi='1' THEN '取組会議 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Yosin='1' THEN '与信 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_NhnKeiKak='1' THEN '納品計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Sonota='1' THEN 'その他 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKaknin='1' THEN '棚割結果の確認'\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB030 srn030 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB030 GROUP BY jyohonum) eda030 
						ON srn030.jyohonum = eda030.jyohonum and srn030.edbn = eda030.edbn ".

					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		srn040.Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		CASE\n".
					" 			WHEN SagyoNiyo='001' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='001' ) \n".
					" 			WHEN SagyoNiyo='002' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='002' ) \n".
					" 			WHEN SagyoNiyo='003' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='003' ) \n".
					" 			WHEN SagyoNiyo='004' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='004' ) \n".
					" 			WHEN SagyoNiyo='005' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='005' ) \n".
					" 			WHEN SagyoNiyo='006' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='006' ) \n".
					" 			WHEN SagyoNiyo='007' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='007' ) \n".
					" 			WHEN SagyoNiyo='008' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='008' ) \n".
					" 			WHEN SagyoNiyo='009' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='009' ) \n".
					" 			WHEN SagyoNiyo='010' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='010' ) \n".
					" 			WHEN SagyoNiyo='011' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='011' ) \n".
					" 			WHEN SagyoNiyo='012' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='012' ) \n".
					" 			WHEN SagyoNiyo='013' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='013' ) \n".
					" 			WHEN SagyoNiyo='014' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='014' ) \n".
					" 			WHEN SagyoNiyo='015' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='015' ) \n".
					" 			WHEN SagyoNiyo='016' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='016' ) \n".
					" 			WHEN SagyoNiyo='017' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='017' ) \n".
					" 			WHEN SagyoNiyo='018' THEN SntSagyo\n".
					" 			WHEN SagyoNiyo='019' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='019' ) \n".
					" 			WHEN SagyoNiyo='020' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='020' ) \n".
					" 			WHEN SagyoNiyo='021' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='021' ) \n".
					" 			WHEN SagyoNiyo='022' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='022' ) \n".
					" 			WHEN SagyoNiyo='023' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='023' ) \n".
					" 			WHEN SagyoNiyo='024' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='024' ) \n".
					" 			WHEN SagyoNiyo='025' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='025' ) \n".
					" 			WHEN SagyoNiyo='026' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='026' ) \n".
					" 			WHEN SagyoNiyo='027' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='027' ) \n".
					" 			WHEN SagyoNiyo='028' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='028' ) \n".
					" 			WHEN SagyoNiyo='029' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='029' ) \n".
					" 			WHEN SagyoNiyo='030' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='030' ) \n".
					"			ELSE '' ".
					" 		END AS AiteskNm, \n".
					" 		NULL as NAIYO\n".
					" FROM SRNTB040 srn040\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB040 GROUP BY jyohonum) eda040 
						ON srn040.jyohonum = eda040.jyohonum and srn040.edbn = eda040.edbn ".
					" 		WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		srn060.Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
                    "       CASE \n" .  
                    "         WHEN gyoshanm != '' THEN gyoshanm \n" .
                    "         WHEN gyoshanm is not null THEN gyoshanm \n" .
                    "       ELSE '業者' END AS AiteskNm,\n" .
					" 		NULL AS NAIYO\n".
					" FROM SRNTB060 srn060 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB060 GROUP BY jyohonum) eda060 
						ON srn060.jyohonum = eda060.jyohonum and srn060.edbn = eda060.edbn ".
					" 		WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	) A\n".
					" ORDER BY Ymd,StHm,EdHm",
					array($shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date)
				);
				
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				// 予定
				$result = $this->db->query(
					" SELECT \n".
					" Ymd AS 日付,\n".
					" StHm AS 開始時刻,\n".
					" EdHm AS 終了時刻,\n".
					" AiteskNm AS 相手先,\n".
					" NAIYO AS 内容\n".
					" FROM\n".
					" (\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" CASE \n".
					" 	WHEN Sdn_Mitumori='1' THEN '見積り提示 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_SiyoKaknin='1' THEN '採用企画の確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnbiKekaku='1' THEN '販売計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Claim='1' THEN 'クレーム対応 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_UribaTan='1' THEN '売場提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnkTan='1' THEN '半期提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Shohin='1' THEN '商品案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTan='1' THEN '導入提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_MDTeian='1' THEN 'ＭＤ提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tanawari='1' THEN '棚割提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tnwrbi='1' THEN '販売店の棚割日情報 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTume='1' THEN '導入日の詰め '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKeka='1' THEN '棚割結果確認 '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB110 srn110 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB110 GROUP BY jyohonum) eda110 
						ON srn110.jyohonum = eda110.jyohonum and srn110.edbn = eda110.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" CASE\n".
					" 	WHEN Ktd_TnpShdn='1' THEN '店舗商談 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoSusu='1' THEN '情報収集 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoAnai='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_TnkiKosyo='1' THEN '展開場所・ｱｳﾄ展開交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_SuisnHanbai='1' THEN '推奨販売交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Jyutyu='1' THEN '受注促進 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_ShdnIgai='1' THEN '商談以外の内容 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Satuei='1' THEN '売場撮影 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Mente='1' THEN '売場メンテナンス '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Zaiko='1' THEN '在庫確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Hoju='1' THEN '商品補充 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_HanskSeti='1' THEN '販促物の設置 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Yamazumi='1' THEN '山積み '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Beta='1' THEN 'ﾍﾞﾀ付け '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB120 srn120\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB120 GROUP BY jyohonum) eda120 
						ON srn120.jyohonum = eda120.jyohonum and srn120.edbn = eda120.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		AiteskNm,\n".
					" CASE\n".
					" 	WHEN Sdn_ShohnJoho='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Kikaku='1' THEN '企画案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Jiseki='1' THEN '実績報告 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Utiawase='1' THEN 'セールスと打ち合せ '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Torikmi='1' THEN '取組会議 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Yosin='1' THEN '与信 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_NhnKeiKak='1' THEN '納品計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Sonota='1' THEN 'その他 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKaknin='1' THEN '棚割結果の確認 '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB130 srn130 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB130 GROUP BY jyohonum) eda130 
						ON srn130.jyohonum = eda130.jyohonum and srn130.edbn = eda130.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		CASE\n".
					" 			WHEN SagyoNiyo='001' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='001' ) \n".
					" 			WHEN SagyoNiyo='002' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='002' ) \n".
					" 			WHEN SagyoNiyo='003' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='003' ) \n".
					" 			WHEN SagyoNiyo='004' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='004' ) \n".
					" 			WHEN SagyoNiyo='005' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='005' ) \n".
					" 			WHEN SagyoNiyo='006' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='006' ) \n".
					" 			WHEN SagyoNiyo='007' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='007' ) \n".
					" 			WHEN SagyoNiyo='008' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='008' ) \n".
					" 			WHEN SagyoNiyo='009' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='009' ) \n".
					" 			WHEN SagyoNiyo='010' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='010' ) \n".
					" 			WHEN SagyoNiyo='011' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='011' ) \n".
					" 			WHEN SagyoNiyo='012' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='012' ) \n".
					" 			WHEN SagyoNiyo='013' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='013' ) \n".
					" 			WHEN SagyoNiyo='014' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='014' ) \n".
					" 			WHEN SagyoNiyo='015' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='015' ) \n".
					" 			WHEN SagyoNiyo='016' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='016' ) \n".
					" 			WHEN SagyoNiyo='017' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='017' ) \n".
					" 			WHEN SagyoNiyo='018' THEN SntSagyo\n".
					" 			WHEN SagyoNiyo='019' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='019' ) \n".
					" 			WHEN SagyoNiyo='020' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='020' ) \n".
					" 			WHEN SagyoNiyo='021' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='021' ) \n".
					" 			WHEN SagyoNiyo='022' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='022' ) \n".
					" 			WHEN SagyoNiyo='023' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='023' ) \n".
					" 			WHEN SagyoNiyo='024' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='024' ) \n".
					" 			WHEN SagyoNiyo='025' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='025' ) \n".
					" 			WHEN SagyoNiyo='026' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='026' ) \n".
					" 			WHEN SagyoNiyo='027' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='027' ) \n".
					" 			WHEN SagyoNiyo='028' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='028' ) \n".
					" 			WHEN SagyoNiyo='029' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='029' ) \n".
					" 			WHEN SagyoNiyo='030' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='030' ) \n".
					"			ELSE '' ".
					" 		END AS AiteskNm, \n".
					" 		NULL as NAIYO\n".
					" 	FROM SRNTB140 srn140\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB140 GROUP BY jyohonum) eda140 
						ON srn140.jyohonum = eda140.jyohonum and srn140.edbn = eda140.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
                    "       CASE \n" .  
                    "         WHEN gyoshanm != '' THEN gyoshanm \n" .
                    "         WHEN gyoshanm is not null THEN gyoshanm \n" .
                    "       ELSE '業者' END AS AiteskNm,\n" .
					" 		NULL AS NAIYO \n".
					" 	FROM SRNTB160 srn160\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB160 GROUP BY jyohonum) eda160 
						ON srn160.jyohonum = eda160.jyohonum and srn160.edbn = eda160.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 		AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')\n".
					" 	) A\n".
					" ORDER BY Ymd,StHm,EdHm\n",
					array($shbn,$date,$shbn,$date,$shbn,$date,$shbn,$date, $shbn, $date)
				);
				
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				break;

			// カレンダーA3
			case 9:
				// 社員情報取得
				$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
				$date =$_POST['s_year'].$_POST['s_month'];
				
				// 社員情報取得
				$result = $this->db->query(
					" SELECT BU.BUNM  AS ユニット, s010.SHINNM AS 氏名\n".
					" FROM SGMTB010 s010\n".
					" INNER JOIN SGMTB020 BU ON\n".
					" 	s010.HONBUCD=BU.HONBUCD\n".
					" 	AND s010.BUCD=BU.BUCD\n".
					" 	AND s010.KACD=BU.KACD\n".
					" WHERE \n".
					" 	  s010.SHBN=?\n",
					array($shbn)
				);

				$csv = mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				// 訪問件数計画
				$result = $this->db->query(
					" SELECT \n".
					" A.CNT+B.CNT+C.CNT AS 訪問件数計画, \n".
					" ? AS 選択年, ? AS 選択月 FROM\n".
					" (\n".
					" SELECT count(*) as CNT\n".
					" FROM SRNTB110 base1\n".
					" INNER JOIN ".
					" (SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB110".
					" GROUP BY jyohonum) eda1".
					" ON base1.jyohonum = eda1.jyohonum AND base1.edbn = eda1.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')) A,\n".
					" (SELECT count(*) as CNT\n".
					" FROM SRNTB120 base2\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB120  ".
					" GROUP BY jyohonum) eda2 ".
					" ON base2.jyohonum = eda2.jyohonum AND base2.edbn = eda2.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD'))B,\n".
					" (SELECT count(*) as CNT\n".
					" FROM SRNTB130 base3\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB130".
					" GROUP BY jyohonum) eda3".
					" ON base3.jyohonum = eda3.jyohonum AND base3.edbn = eda3.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD'))C,\n".
					" (SELECT count(*) as CNT\n".
					" FROM SRNTB140 base4\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB140".
					" GROUP BY jyohonum) eda4".
					" ON base4.jyohonum = eda4.jyohonum AND base4.edbn = eda4.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD'))D,\n".
					" (SELECT count(*) as CNT\n".
					" FROM SRNTB160 base6\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB160".
					" GROUP BY jyohonum) eda6".
					" ON base6.jyohonum = eda6.jyohonum AND base6.edbn = eda6.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd>=TO_CHAR(NOW(), 'YYYYMMDD')\n)E\n",
				array($_POST['s_year'],$_POST['s_month'],$shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date)
				);

				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				// 訪問件数実績
				$result = $this->db->query(
					" SELECT \n".
					// mod (2013.03.14) infront 実績件数をA4と合わせる(社内作業と業者を除く) start -------
					//" A.CNT+B.CNT+C.CNT+D.CNT+E.CNT AS 訪問件数実績 FROM\n".
					" A.CNT+B.CNT+C.CNT AS 訪問件数実績 FROM\n".
					// mod (2013.03.14) infront 実績件数をA4と合わせる(社内作業と業者を除く) end   -------
					" (\n".
					" SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB010 base1\n".
					" INNER JOIN ".
					" (SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB010".
					" GROUP BY jyohonum) eda1".
					" ON base1.jyohonum = eda1.jyohonum AND base1.edbn = eda1.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) A,\n".
					" (\n".
					" SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB020 base2\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB020  ".
					" GROUP BY jyohonum) eda2 ".
					" ON base2.jyohonum = eda2.jyohonum AND base2.edbn = eda2.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) B,\n".
					" ( SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB030 base3\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB030".
					" GROUP BY jyohonum) eda3".
					" ON base3.jyohonum = eda3.jyohonum AND base3.edbn = eda3.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) C,\n".
					" ( SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB040 base4\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB040".
					" GROUP BY jyohonum) eda4".
					" ON base4.jyohonum = eda4.jyohonum AND base4.edbn = eda4.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) D,\n".
					" ( SELECT COUNT(*) AS CNT\n".
					" FROM SRNTB060 base6\n".
					" INNER JOIN ".
					" ( SELECT jyohonum, MAX(edbn) AS edbn FROM SRNTB060".
					" GROUP BY jyohonum) eda6".
					" ON base6.jyohonum = eda6.jyohonum AND base6.edbn = eda6.edbn".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" AND Ymd<TO_CHAR(NOW(), 'YYYYMMDD')) E\n",
				array($shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date, $shbn, $date)
				);
				
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				// 実績
				$result = $this->db->query(
					" SELECT \n".
					" Ymd AS 日付,\n".
					" StHm AS 開始時刻,\n".
					" EdHm AS 終了時刻,\n".
					" AiteskNm AS 相手先,\n".
					" NAIYO AS 内容\n".
					" FROM\n".
					" (\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" AiteskNm,\n".
					" CASE\n".
					" 	WHEN Sdn_Mitumori='1' THEN '見積り提示 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_SiyoKaknin='1' THEN '採用企画の確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnbiKekaku='1' THEN '販売計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Claim='1' THEN 'クレーム対応 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_UribaTan='1' THEN '売場提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnkTan='1' THEN '半期提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Shohin='1' THEN '商品案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTan='1' THEN '導入提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_MDTeian='1' THEN 'ＭＤ提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tanawari='1' THEN '棚割提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tnwrbi='1' THEN '販売店の棚割日情報 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTume='1' THEN '導入日の詰め '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKeka='1' THEN '棚割結果確認 '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB010 srn010 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB010 GROUP BY jyohonum) eda010 
						ON srn010.jyohonum = eda010.jyohonum and srn010.edbn = eda010.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" AiteskNm,\n".
					" CASE\n".
					" 	WHEN Ktd_TnpShdn='1' THEN '店舗商談 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoSusu='1' THEN '情報収集 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoAnai='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_TnkiKosyo='1' THEN '展開場所・ｱｳﾄ展開交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_SuisnHanbai='1' THEN '推奨販売交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Jyutyu='1' THEN '受注促進 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_ShdnIgai='1' THEN '商談以外の内容 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Satuei='1' THEN '売場撮影 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Mente='1' THEN '売場メンテナンス '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Zaiko='1' THEN '在庫確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Hoju='1' THEN '商品補充 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_HanskSeti='1' THEN '販促物の設置 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Yamazumi='1' THEN '山積み '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Beta='1' THEN 'ﾍﾞﾀ付け '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB020 srn020\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB020 GROUP BY jyohonum) eda020 
						ON srn020.jyohonum = eda020.jyohonum and srn020.edbn = eda020.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" AiteskNm,\n".
					" CASE\n".
					" 	WHEN Sdn_ShohnJoho='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Kikaku='1' THEN '企画案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Jiseki='1' THEN '実績報告 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Utiawase='1' THEN 'セールスと打ち合せ '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Torikmi='1' THEN '取組会議 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Yosin='1' THEN '与信 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_NhnKeiKak='1' THEN '納品計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Sonota='1' THEN 'その他 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKaknin='1' THEN '棚割結果の確認'\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB030 srn030 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB030 GROUP BY jyohonum) eda030 
						ON srn030.jyohonum = eda030.jyohonum and srn030.edbn = eda030.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" 		CASE\n".
					" 			WHEN SagyoNiyo='001' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='001' ) \n".
					" 			WHEN SagyoNiyo='002' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='002' ) \n".
					" 			WHEN SagyoNiyo='003' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='003' ) \n".
					" 			WHEN SagyoNiyo='004' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='004' ) \n".
					" 			WHEN SagyoNiyo='005' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='005' ) \n".
					" 			WHEN SagyoNiyo='006' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='006' ) \n".
					" 			WHEN SagyoNiyo='007' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='007' ) \n".
					" 			WHEN SagyoNiyo='008' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='008' ) \n".
					" 			WHEN SagyoNiyo='009' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='009' ) \n".
					" 			WHEN SagyoNiyo='010' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='010' ) \n".
					" 			WHEN SagyoNiyo='011' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='011' ) \n".
					" 			WHEN SagyoNiyo='012' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='012' ) \n".
					" 			WHEN SagyoNiyo='013' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='013' ) \n".
					" 			WHEN SagyoNiyo='014' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='014' ) \n".
					" 			WHEN SagyoNiyo='015' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='015' ) \n".
					" 			WHEN SagyoNiyo='016' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='016' ) \n".
					" 			WHEN SagyoNiyo='017' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='017' ) \n".
					" 			WHEN SagyoNiyo='018' THEN SntSagyo\n".
					" 			WHEN SagyoNiyo='019' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='019' ) \n".
					" 			WHEN SagyoNiyo='020' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='020' ) \n".
					" 			WHEN SagyoNiyo='021' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='021' ) \n".
					" 			WHEN SagyoNiyo='022' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='022' ) \n".
					" 			WHEN SagyoNiyo='023' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='023' ) \n".
					" 			WHEN SagyoNiyo='024' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='024' ) \n".
					" 			WHEN SagyoNiyo='025' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='025' ) \n".
					" 			WHEN SagyoNiyo='026' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='026' ) \n".
					" 			WHEN SagyoNiyo='027' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='027' ) \n".
					" 			WHEN SagyoNiyo='028' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='028' ) \n".
					" 			WHEN SagyoNiyo='029' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='029' ) \n".
					" 			WHEN SagyoNiyo='030' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='030' ) \n".
					"			ELSE '' ".
					" 		END AS AiteskNm, \n".
					" 		NULL as NAIYO\n".
					" FROM SRNTB040 srn040\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB040 GROUP BY jyohonum) eda040 
						ON srn040.jyohonum = eda040.jyohonum and srn040.edbn = eda040.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
                    " CASE \n" .  
                    "   WHEN gyoshanm != '' THEN gyoshanm \n" .
                    "   WHEN gyoshanm is not null THEN gyoshanm \n" .
                    " ELSE '業者' END AS AiteskNm,\n" .
					" NULL AS NAIYO\n".
					" FROM SRNTB060 srn060 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB060 GROUP BY jyohonum) eda060 
						ON srn060.jyohonum = eda060.jyohonum and srn060.edbn = eda060.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" ) A\n".
					" ORDER BY Ymd,StHm,EdHm\n",
					array($shbn,$date,$shbn,$date,$shbn,$date,$shbn,$date,$shbn,$date)
				);
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");

				// 予定
				$result = $this->db->query(
					" SELECT \n".
					" Ymd AS 日付,\n".
					" StHm AS 開始時刻,\n".
					" EdHm AS 終了時刻,\n".
					" AiteskNm AS 相手先,\n".
					" NAIYO AS 内容\n".
					" FROM\n".
					" (\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" AiteskNm,\n".
					" CASE \n".
					" 	WHEN Sdn_Mitumori='1' THEN '見積り提示 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_SiyoKaknin='1' THEN '採用企画の確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnbiKekaku='1' THEN '販売計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Claim='1' THEN 'クレーム対応 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_UribaTan='1' THEN '売場提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_HnkTan='1' THEN '半期提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Shohin='1' THEN '商品案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTan='1' THEN '導入提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_MDTeian='1' THEN 'ＭＤ提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tanawari='1' THEN '棚割提案 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Tnwrbi='1' THEN '販売店の棚割日情報 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_DonyuTume='1' THEN '導入日の詰め '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKeka='1' THEN '棚割結果確認 '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB110 srn110 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB110 GROUP BY jyohonum) eda110 
						ON srn110.jyohonum = eda110.jyohonum and srn110.edbn = eda110.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" AiteskNm,\n".
					" CASE\n".
					" 	WHEN Ktd_TnpShdn='1' THEN '店舗商談 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoSusu='1' THEN '情報収集 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_JohoAnai='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_TnkiKosyo='1' THEN '展開場所・ｱｳﾄ展開交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_SuisnHanbai='1' THEN '推奨販売交渉 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Jyutyu='1' THEN '受注促進 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_ShdnIgai='1' THEN '商談以外の内容 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Satuei='1' THEN '売場撮影 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Mente='1' THEN '売場メンテナンス '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Zaiko='1' THEN '在庫確認 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Hoju='1' THEN '商品補充 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_HanskSeti='1' THEN '販促物の設置 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Yamazumi='1' THEN '山積み '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Ktd_Beta='1' THEN 'ﾍﾞﾀ付け '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB120 srn120\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB120 GROUP BY jyohonum) eda120 
						ON srn120.jyohonum = eda120.jyohonum and srn120.edbn = eda120.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
					" AiteskNm,\n".
					" CASE\n".
					" 	WHEN Sdn_ShohnJoho='1' THEN '商品情報案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Kikaku='1' THEN '企画案内 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Jiseki='1' THEN '実績報告 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Utiawase='1' THEN 'セールスと打ち合せ '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Torikmi='1' THEN '取組会議 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Yosin='1' THEN '与信 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_NhnKeiKak='1' THEN '納品計画 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_Sonota='1' THEN 'その他 '\n".
					"	ELSE '' ".
					" END ||  ".
					" CASE\n".
					" 	WHEN Sdn_TnwrKaknin='1' THEN '棚割結果の確認 '\n".
					"	ELSE '' ".
					" END AS NAIYO\n".
					" FROM SRNTB130 srn130 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB130 GROUP BY jyohonum) eda130 
						ON srn130.jyohonum = eda130.jyohonum and srn130.edbn = eda130.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" 	".
					" 	UNION\n".
					" 	SELECT \n".
					" 		Ymd,\n".
					" 		StHm,\n".
					" 		EdHm,\n".
					" 		CASE\n".
					" 			WHEN SagyoNiyo='001' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='001' ) \n".
					" 			WHEN SagyoNiyo='002' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='002' ) \n".
					" 			WHEN SagyoNiyo='003' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='003' ) \n".
					" 			WHEN SagyoNiyo='004' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='004' ) \n".
					" 			WHEN SagyoNiyo='005' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='005' ) \n".
					" 			WHEN SagyoNiyo='006' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='006' ) \n".
					" 			WHEN SagyoNiyo='007' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='007' ) \n".
					" 			WHEN SagyoNiyo='008' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='008' ) \n".
					" 			WHEN SagyoNiyo='009' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='009' ) \n".
					" 			WHEN SagyoNiyo='010' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='010' ) \n".
					" 			WHEN SagyoNiyo='011' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='011' ) \n".
					" 			WHEN SagyoNiyo='012' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='012' ) \n".
					" 			WHEN SagyoNiyo='013' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='013' ) \n".
					" 			WHEN SagyoNiyo='014' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='014' ) \n".
					" 			WHEN SagyoNiyo='015' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='015' ) \n".
					" 			WHEN SagyoNiyo='016' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='016' ) \n".
					" 			WHEN SagyoNiyo='017' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='017' ) \n".
					" 			WHEN SagyoNiyo='018' THEN SntSagyo\n".
					" 			WHEN SagyoNiyo='019' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='019' ) \n".
					" 			WHEN SagyoNiyo='020' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='020' ) \n".
					" 			WHEN SagyoNiyo='021' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='021' ) \n".
					" 			WHEN SagyoNiyo='022' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='022' ) \n".
					" 			WHEN SagyoNiyo='023' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='023' ) \n".
					" 			WHEN SagyoNiyo='024' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='024' ) \n".
					" 			WHEN SagyoNiyo='025' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='025' ) \n".
					" 			WHEN SagyoNiyo='026' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='026' ) \n".
					" 			WHEN SagyoNiyo='027' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='027' ) \n".
					" 			WHEN SagyoNiyo='028' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='028' ) \n".
					" 			WHEN SagyoNiyo='029' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='029' ) \n".
					" 			WHEN SagyoNiyo='030' THEN (select ichiran from sgmtb030 where kbnid='008' and kbncd='030' ) \n".
					"			ELSE '' ".
					" 		END AS AiteskNm, \n".
					" 		NULL as NAIYO\n".
					" 	FROM SRNTB140 srn140\n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB140 GROUP BY jyohonum) eda140 
						ON srn140.jyohonum = eda140.jyohonum and srn140.edbn = eda140.edbn ".
					" 	WHERE\n".
					" 		Shbn=?\n".
					" 		AND substr(Ymd,1,6)=?\n".
					" 	".
					" UNION\n".
					" SELECT \n".
					" Ymd,\n".
					" StHm,\n".
					" EdHm,\n".
                    " CASE \n" .  
                    "   WHEN gyoshanm != '' THEN gyoshanm \n" .
                    "   WHEN gyoshanm is not null THEN gyoshanm \n" .
                    " ELSE '業者' END AS AiteskNm,\n" .
					" NULL AS NAIYO\n".
					" FROM SRNTB160 srn160 \n".
					" INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM SRNTB160 GROUP BY jyohonum) eda160 
						ON srn160.jyohonum = eda160.jyohonum and srn160.edbn = eda160.edbn ".
					" WHERE\n".
					" Shbn=?\n".
					" AND substr(Ymd,1,6)=?\n".
					" ) A\n".
					" ORDER BY Ymd,StHm,EdHm\n",
					array($shbn,$date,$shbn,$date,$shbn,$date,$shbn,$date,$shbn,$date)
				);
				
				$csv .= "\n\n" . mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");
				log_message('debug',"csv = ".$csv);
				break;

			// 日報一覧
			case 10:
				$ymd_start = date('Ymd', strtotime($_POST['date_from']));
				$ymd_end = date('Ymd', strtotime($_POST['date_to']));
				$honbucd = $_POST['honbucd'];
				$bucd = substr($_POST['bucd'], 5, 5);
				$kacd = substr($_POST['kacd'], 10, 5);
				$shbn = $_POST['user'];
				
				$csv1 = $this->create_nippo_srntb010_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn);
				$csv2 = $this->create_nippo_srntb020_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn);
				$csv3 = $this->create_nippo_srntb030_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn);
				$csv4 = $this->create_nippo_srntb040_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn);
				$csv5 = $this->create_nippo_srntb060_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn);
				
				$this->zip->add_data(mb_convert_encoding('nippo_honbu.csv', "Shift-JIS", "UTF-8"), $csv1);
				$this->zip->add_data(mb_convert_encoding('nippo_tempo.csv', "Shift-JIS", "UTF-8"), $csv2);
				$this->zip->add_data(mb_convert_encoding('nippo_dairiten.csv', "Shift-JIS", "UTF-8"), $csv3);
				$this->zip->add_data(mb_convert_encoding('nippo_shanai.csv', "Shift-JIS", "UTF-8"), $csv4);
				$this->zip->add_data(mb_convert_encoding('nippo_gyosha.csv', "Shift-JIS", "UTF-8"), $csv5);
				
				//ダウンロード実行
		 		$this->zip->download($download_file_name);
				break;

			// 商談履歴
			case 11:
				$where = "";
				//$arrWhere = array();
				
				// 商談日（開始）
				if( isset($_POST['s_year']) && $_POST['s_year'] != ''){
					$where  .= " WHERE base.ymd >= '".$_POST['s_year'] . sprintf("%02d",$_POST['s_month']) . sprintf("%02d",$_POST['s_day']) ."'";
				}
				// 商談日（終了）
				if( isset($_POST['e_year']) && $_POST['e_year'] != ''){
					$where .= " and  base.ymd <= '".$_POST['e_year'] . sprintf("%02d",$_POST['e_month'])  . sprintf("%02d",$_POST['e_day']) ."'";
				}
				// 本部
				if(isset($_POST['honbucd']) && $_POST['honbucd'] != MY_DB_BU_ESC){
					
					$honbucd = $_POST['honbucd'];
					$where .= ' and sgmtb010.honbucd = "'.$honbucd.'"';
				}
				// 部
				if(isset($_POST['bucd']) && $_POST['bucd'] != MY_DB_BU_ESC){
					
					$bucd = substr($_POST['bucd'], 5, 5);
					$where .= ' and  sgmtb010.bucd = "'.$bucd.'"';
					//$arrWhere[] = $_POST['bucd'];
				}
				// 課ユニット
				if(isset($_POST['kacd']) && $_POST['kacd'] != MY_DB_BU_ESC){
					
					$kacd = substr($_POST['kacd'], 10, 5);
					$where .= ' and  sgmtb010.kacd = "'.$kacd.'"';
					//$arrWhere[] = $_POST['kacd'];
				}
				

			
				// sql文作成
				$sql = 	"
					SELECT 
						substr(base.ymd,1,4) || '/' || substr(base.ymd,5,2) || '/' || substr(base.ymd,7,2) as 日付,
						/* base.aiteskCd as コード, */
						base.aiteskNm as 相手先社名,
						
						CASE 
						 	WHEN base.kubun = '001' and Sdn1.Sdn_Mitumori='1' THEN '見積り提示' 
						 	ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_SiyoKaknin='1' THEN '採用企画の確認' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_HnbiKekaku='1' THEN '販売計画' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_Claim='1' THEN 'クレーム対応' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_UribaTan='1' THEN '売場提案' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_HnkTan='1' THEN '半期提案' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_Shohin='1' THEN '商品案内' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_DonyuTan='1' THEN '導入提案' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_MDTeian='1' THEN 'ＭＤ提案' 
							ELSE '' 
						END ||	
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_Tanawari='1' THEN '棚割提案' 
							ELSE '' 
						END ||	
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_Tnwrbi='1' THEN '販売店の棚割日情報' 
							ELSE '' 
						END ||	
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_DonyuTume='1' THEN '導入日の詰め' 
							ELSE '' 
						END ||	
						CASE 
							WHEN base.kubun = '001' and Sdn1.Sdn_TnwrKeka='1' THEN '棚割結果確認' 
							ELSE '' 
						END as 項目 ,
						
						CASE
							WHEN kubun = '001' THEN (base.SeiykNiyo || base.FseiykNiyo || base.HoryuNiyo || base.SonotaNiyo)
							WHEN kubun = '002' THEN base.SagyoKekka
							WHEN kubun = '003' THEN base.SyodanKekka
						END as 商談内容,	
						base.tantoShNm as 商談実施者 
					FROM 
						srktb010 base 
					INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM srktb010 GROUP BY jyohonum) eda 
						ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn 
						
					INNER JOIN
						srntb010 Sdn1
						ON base.motojyohonum = Sdn1.jyohonum and base.kubun = '001' /* and Sdn1.edbn = (SELECT max(edbn) as edbn1 FROM srntb010  GROUP BY jyohonum) */
					INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM srntb010 GROUP BY jyohonum) eda1 
						ON Sdn1.jyohonum = eda1.jyohonum and Sdn1.edbn = eda1.edbn 
					
					
					INNER JOIN  sgmtb010 ON base.tantoShbn = sgmtb010.shbn
					
					".$where."
					
					union
					
					SELECT 
						substr(base.ymd,1,4) || '/' || substr(base.ymd,5,2) || '/' || substr(base.ymd,7,2) as 日付,
						/* base.aiteskCd as コード, */
						base.aiteskNm as 相手先社名,
						
						CASE 
						 	WHEN base.kubun = '003' and Sdn3.Sdn_MitsumoriFollow='1' THEN '一般店見積り提示・商談フォロー ' 
						 	ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_ShohnJoho='1' THEN '商品情報案内 ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_Kikaku='1' THEN '企画案内 ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_Jiseki='1' THEN '実績報告 ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_Mitsumori='1' THEN '見積り提示 ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_JizenUtiawase='1' THEN '販売店商談事前打合せ ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_KikakuJyoukyou='1' THEN '情報収集・企画導入状況確認 ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_Logistics='1' THEN '受発注・物流関連 ' 
							ELSE '' 
						END ||  
						CASE 
							WHEN base.kubun = '003' and Sdn3.Sdn_Torikmi='1' THEN '取組会議 ' 
							ELSE '' 
						END as 項目 ,
						
						CASE
							WHEN kubun = '001' THEN (base.SeiykNiyo || base.FseiykNiyo || base.HoryuNiyo || base.SonotaNiyo)
							WHEN kubun = '002' THEN base.SagyoKekka
							WHEN kubun = '003' THEN base.SyodanKekka
						END as 商談内容,	
						base.tantoShNm as 商談実施者 
					FROM 
						srktb010 base 
					INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM srktb010 GROUP BY jyohonum) eda 
						ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn 
						
					INNER JOIN
						srntb030 Sdn3
						ON base.motojyohonum = Sdn3.jyohonum and base.kubun = '003' /* and Sdn3.edbn =  (SELECT max(edbn) as edbn2 FROM srntb030  GROUP BY jyohonum)  */
					INNER JOIN 
						(SELECT jyohonum, max(edbn) as edbn FROM srntb030 GROUP BY jyohonum) eda3 
						ON Sdn3.jyohonum = eda3.jyohonum and Sdn3.edbn = eda3.edbn 
					
					INNER JOIN  sgmtb010 ON base.tantoShbn = sgmtb010.shbn
					
					".$where." 
					";

				
				log_message('debug', $sql);
				$result = $this->db->query($sql);
				
				$csv = mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");
				break;
			// 企画獲得
			case 12:
				$s_date = $_POST['s_year'].$_POST['s_month'];
				$e_date = $_POST['e_year'].$_POST['e_month'];

				$where ="";

				if($_POST['honbucd']=="XXXXX"){
					$honbucd = '';
				}else{
					$honbucd = $_POST['honbucd'];
					$where .=" and sgm010.honbucd = '".$honbucd."'  ";
					//$arrWhere[]=$honbucd;
					
				}
				if($_POST['bucd']=="XXXXX"){
					$bucd = '';
				}else{
					
					$bucd = substr($_POST['bucd'], 5, 5);
					$where .=" and sgm010.bucd = '".$bucd."'  ";
					//$arrWhere[]=$bucd;
				}
				if($_POST['bucd']=="XXXXX"){
					$kacd = '';
				}else{
					
					$kacd = substr($_POST['kacd'], 10, 5);
					$where .=" and sgm010.kacd = '".$kacd."'  ";
					//$arrWhere[]=$kacd;
				}
				if($_POST['user']!=""){
					
					$where .=" and srk070.shbn = '".$_POST['user']."' ";
					
					//$shbn = $_POST['user'];
				}else{
					$shbn = '';
				}
				if($_POST['aitesknm']!=""){
					$where .= " and srn050.aitesknm = '".$_POST['aitesknm']."' ";
					//$arrWhere[] = $_POST['aitesknm'];
				}else{
					$and ="";
					$aitesknm = '';
				}
				
				$when ="";
				$when_item ="";
				
				// モデル呼び出し
	     	 	$this->load->model('sgmtb080'); // ユーザー情報1
				$dbnri_count = $this->sgmtb080->get_project_dbnri_cnt();
				log_message('debug', $dbnri_count['dbnri_cnt']);

				for($i=6;$i<=$dbnri_count['dbnri_cnt'];$i++){
					$when .= " WHEN srk070.dbnricd = '".sprintf("%02d", $i)."' THEN (SELECT DISTINCT dbnrinm FROM sgmtb080 where dbnricd = '".sprintf("%02d", $i)."')";
					$item_count = $this->sgmtb080->get_project_item_cnt_cd(sprintf("%02d", $i));
					
					for($j=1;$j<=$item_count['item_cnt'];$j++){
					$when_item .=" WHEN srk070.dbnricd = '".sprintf("%02d", $i)."' and srk070.itemcd = '".sprintf("%02d", $j)."' THEN (SELECT itemnm FROM SGMTB080 WHERE itemcd ='".sprintf("%02d", $j)."' and dbnricd = '".sprintf("%02d", $i)."')  ";
					}
				}
				log_message('debug', $when);

				
				// 初期化
				$res = FALSE;

				// sql文作成
				$sql = 	"
				SELECT 
					srk070.shbn AS 社番,
					srk070.year AS 年,
					srk070.month AS 月,
					sgm130.htncd AS 販売店コード,
					srn050.aitesknm AS 相手先名,
					srn050.rank AS ランク,
					CASE 
						WHEN srk070.dbnricd = '01' THEN '紙製品'
						WHEN srk070.dbnricd = '02' THEN 'ベビー用紙おむつ'
						WHEN srk070.dbnricd = '03' THEN '大人用紙おむつ'
						WHEN srk070.dbnricd = '04' THEN 'フェミニン'
						WHEN srk070.dbnricd = '05' THEN 'ウェット'
						".$when."
					END as 大分類,	
					CASE 
						WHEN srk070.dbnricd = '01' and srk070.itemcd = '01' THEN 'キュートティシュー'
						WHEN srk070.dbnricd = '01' and srk070.itemcd = '02' THEN 'プラスウォーターBOX'
						WHEN srk070.dbnricd = '01' and srk070.itemcd = '03' THEN 'NBトイレット'
						WHEN srk070.dbnricd = '01' and srk070.itemcd = '04' THEN 'エルフォーレ'
						WHEN srk070.dbnricd = '01' and srk070.itemcd = '05' THEN 'フラワープリント'
						WHEN srk070.dbnricd = '02' and srk070.itemcd = '01' THEN 'グーンテープ'
						WHEN srk070.dbnricd = '02' and srk070.itemcd = '02' THEN 'グーンパンツ'
						WHEN srk070.dbnricd = '03' and srk070.itemcd = '01' THEN 'アテント長時間パンツ'
						WHEN srk070.dbnricd = '03' and srk070.itemcd = '02' THEN 'アテント薄型パンツ'
						WHEN srk070.dbnricd = '03' and srk070.itemcd = '03' THEN 'アテント汎用パッド'
						WHEN srk070.dbnricd = '03' and srk070.itemcd = '04' THEN 'アテント夜一枚パッド'
						WHEN srk070.dbnricd = '04' and srk070.itemcd = '01' THEN 'Megamiやわらかスリム'
						WHEN srk070.dbnricd = '04' and srk070.itemcd = '02' THEN 'ウルトラガード'
						WHEN srk070.dbnricd = '04' and srk070.itemcd = '03' THEN '新・素肌感バンドル'
						WHEN srk070.dbnricd = '05' and srk070.itemcd = '01' THEN 'ベビー用おしりふき'
						WHEN srk070.dbnricd = '05' and srk070.itemcd = '02' THEN 'ミチガエルTC詰替'
						WHEN srk070.dbnricd = '05' and srk070.itemcd = '03' THEN '除菌アルコールタオル詰替'
						".$when_item."
					END as アイテム,
					CASE 
						WHEN srk070.kbn = '01' THEN '確定'
						WHEN srk070.kbn = '02' THEN '予定'
					END as 区分,	
					srk070.edlp_kaisiymd as EDLP開始日付,
					srk070.edlp_shryoymd as EDLP終了日付,
					srk070.edlp_tenponum as EDLP店舗数,
					srk070.edlp_baika as EDLP売価,
					srk070.end_kaisiymd as エンド開始日付,
					srk070.end_shryoymd as エンド終了日付,
					srk070.end_tenponum as エンド店舗数,
					srk070.end_baika as エンド売価,
					srk070.trs_kaisu as チラシ回数,
					srk070.trs_tenponum as チラシ店舗数,
					srk070.trs_baika as チラシ売価 
				FROM SRKTB070 srk070 
				 LEFT JOIN SGMTB050 srn050
				 ON srk070.aiteskcd = srn050.hanhoncd 
				 
				 LEFT JOIN SGMTB010 sgm010 
				ON srk070.shbn = sgm010.shbn 

				 LEFT JOIN SGMTB130 sgm130
				ON srk070.aiteskcd = sgm130.trhkaitcd

				WHERE
					srn050.kbn = '1' and
				  (? <= (year || lpad(month,2,'0'))::int AND (year || lpad(month,2,'0'))::int <= ?)".$where."
				";
				log_message('debug', $sql);
				$result = $this->db->query($sql, array($s_date,$e_date));
				
				$csv = mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");
				log_message('debug', "----------------------------------------------------------------------------------------");
				log_message('debug', $sql);
				break;
				

		}

		
		$this->zip->add_data(mb_convert_encoding($displayFileName, "Shift-JIS", "UTF-8"), $csv);
		
		//ダウンロード実行
 		$this->zip->download($download_file_name);
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
			log_message('debug',"========== Data_output_csv display start ==========");
			$this->load->view(MY_VIEW_DATA_OUTPUT_CSV, $data);
			log_message('debug',"========== Data_output_csv display end ==========");
		}catch(Exception $e){
			// エラー処理
//			$this->error_view($e);
		}
	}
	
	/**
	 * 情報メモの項目
	 */
	function setMemoData($data){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		
		// ドロップダウン項目設定
		$data['kbn_table'] = $this->_get_kbn($data);                   // 区分HTML作成
		// ドロップダウン項目設定（メモ・メーカー）
		$data['drop_maker'] = $this->_get_maker($data);
		
	}
	
	/**
	 * 商談履歴の項目
	 */
	function setSRirekiData(&$data){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');

		// 検索者の社番を取得
		$shbn = $this->session->userdata('shbn');
		// 部署検索
		$data['search_result_busyo_table'] = $this->table_manager->output_csv_s_rireki_set_b_table($shbn);
	}
	
	/**
	 *　日報
	 */
	function setNitiranData(&$data){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');

		// 検索者の社番を取得
		$shbn = $this->session->userdata('shbn');
		// 部署検索
		$data['search_result_busyo_table'] = $this->table_manager->output_csv_nipo_set_b_table($shbn);
	}
	
	/**
	 *　企画獲得
	 */
	function setProjectPossessionData(&$data){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');

		// 検索者の社番を取得
		$shbn = $this->session->userdata('shbn');
		// 部署検索
		$data['search_result_busyo_table'] = $this->table_manager->output_csv_nipo_set_b_table($shbn);
		
		// モデル呼び出し
      	$this->load->model('srwtb021'); // ユーザー情報1
		$data['aitesk_list'] = $this->srwtb021->get_aitesk_data($shbn);
	}
	
	/**
	 * 区分名取得
	 */
	function getKubun($itemKey){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('item_manager');
		$kubunItem = $CI->config->item($itemKey);
		$kubunVal = $_POST[$kubunItem['name']];
		
		return $kubunVal ;
		
	}

	/**
	 * 区分（情報区分、品種区分、対象区分）の作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_kbn($data)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_kbn($data);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
		}
	}

	/**
	 * メーカーの作成を取得
	 *
	 * @access private
	 * @param
	 * @return string $tab_data HTML-STRING文字列
	 */
	private function _get_maker($data)
	{
		try
		{
			log_message('debug',"========== data_memo _set_tab_data start ==========");
			// 初期化
			$this->load->library('table_set');
			$table_data = NULL;
			$table_data = $this->table_set->set_data_memo_maker($data);         // 社番テーブル

			log_message('debug',"========== data_memo _set_tab_data end ==========");
			return $table_data;
		}catch(Exception $e){
			//エラー処理
		}
	}
	
	  /**
   *  ドロップダウンリスト変更
   */
  function select_item_list()
  {
    try
    {
      log_message('debug',"==========  select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報1

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");
      
      $staff_list =array();

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val'];
      $data['search_result_busyo_table'] = $this->table_manager->output_csv_s_rireki_set_b_table($shbn,NULL,$honbu); // 部署検索
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
	
	  /**
   *  ドロップダウンリスト変更
   */
  function memo_select_item_list()
  {
    try
    {
      log_message('debug',"==========  select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報1

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");
      
      $staff_list =array();

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val'];
      $data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,NULL,"2"); // 部署検索
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
	
   /**
   *  ドロップダウンリスト変更
   */
	function select_item_unit_list($type=NULL)
		{
			try
			{
			log_message('debug',"========== select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
			$honbu =$_POST['selected_val']['val'];
			$shbn = $this->session->userdata('shbn');

			// 部情報取得
			$bu = substr($_POST['selected_val']['val2'],5);

			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$bu);
			
			$this->load->library('table_set');
			
			$staff_list =array();
			
			// 本部
			//$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			
			$this->load->library('table_manager');
			//表示用データ作成
			$data['search_result_busyo_table'] = $this->table_manager->output_csv_s_rireki_set_b_table($shbn,NULL,$honbu,$bu);
			
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	
  /**
   *  ドロップダウンリスト変更
   */
  function select_item_n_list()
  {
    try
    {
      log_message('debug',"==========  select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");
      
      $staff_list =array();

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val'];
      $data['search_result_busyo_table'] = $this->table_manager->output_csv_nipo_set_b_table($shbn,NULL,$honbu); // 部署検索
      
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
     
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
  
   /**
   *  ドロップダウンリスト変更
   */
  function select_item_n_aitesk_list()
  {
    try
    {
      log_message('debug',"==========  select_item_list start ==========");

      // モデル呼び出し
      	$this->load->model('srwtb021'); // ユーザー情報1
      	$aitesk_list = array();
		$data['aitesk_list'] = "<table style=\"margin-left:20px\">
										<tr>
											<th>販売店名</th>
											<td>
											<select name=\"aitesknm\" style=\"width:148px;margin-left:30px\">
													<option value=\"\" ></option>";
													foreach($aitesk_list as $key => $value) { 
													$data['aitesk_list'] .=	"<option value=\"".$value['aitesknm']."\" >".$value['aitesknm']."</option>";
													}
		$data['aitesk_list'] .=						"</select>
											</td>
										</tr>
										</table>";
     
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['aitesk_list'];
     
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
  
     /**
   *  ドロップダウンリスト変更
   */
  function select_item_n_aitesk_change_list()
  {
    try
    {
      log_message('debug',"==========  select_item_list start ==========");

		$shbn = $_POST['selected_val'];
      // モデル呼び出し
      	$this->load->model('srwtb021'); // ユーザー情報1
      	$aitesk_list = $this->srwtb021->get_aitesk_data($shbn);
      	
      	
		$data['aitesk_list'] = "<table style=\"margin-left:20px\">
										<tr>
											<th>販売店名</th>
											<td>
											<select name=\"aitesknm\" style=\"width:148px;margin-left:30px\">
													<option value=\"\" ></option>";
													foreach($aitesk_list as $key => $value) { 
													$data['aitesk_list'] .=	"<option value=\"".$value['aitesknm']."\" >".$value['aitesknm']."</option>";
													}
		$data['aitesk_list'] .=						"</select>
											</td>
										</tr>
										</table>";
     
      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['aitesk_list'];
     
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
	
   /**
   *  ドロップダウンリスト変更
   */
	function select_item_n_unit_list($type=NULL)
		{
			try
			{
			log_message('debug',"========== select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
			$honbu =$_POST['selected_val']['val'];
			$shbn = $this->session->userdata('shbn');

			// 部情報取得
			$bu = substr($_POST['selected_val']['val2'],5);

			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$bu);
			
			$this->load->library('table_set');
			
			//$staff_list =array();
			
			// 本部
			//$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			
			$this->load->library('table_manager');
			//表示用データ作成
			$data['search_result_busyo_table'] = $this->table_manager->output_csv_nipo_set_b_table($shbn,NULL,$honbu,$bu);
			
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	
	   /**
   *  ドロップダウンリスト変更
   */
	function select_item_n_user_list($type=NULL)
		{
			try
			{
			log_message('debug',"========== select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
		    $honbu = $_POST['selected_val']['val'];
		    // 部情報取得
			$bu= $this->sgmtb020->get_bu_name_data_select($honbu);
		   
		    $busyo =  substr($_POST['selected_val']['val2'],5);
			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$busyo);
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			

			$ka = substr($_POST['selected_val']['val3'],10);

			
			$this->load->model('sgmtb010'); // ユーザ別検索情報（相手先）

			$staff_list = $this->sgmtb010->get_shin_data($honbu,$busyo,$ka);
		
			
			if($ka=="XXXXX"){
			 $staff_list = array();
			}
			
			$this->load->library('table_set');
			$shbn = $this->session->userdata('shbn');

			// 本部
			//$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			
			$this->load->library('table_manager');
			//表示用データ作成
			$data['search_result_busyo_table'] = $this->table_manager->output_csv_nipo_set_b_table($shbn,NULL,$honbu,$busyo,$ka);

			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	function create_nippo_srntb010_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn) {
		$params = array($ymd_start, $ymd_end);
		if ($honbucd && $honbucd != 'XXXXX') { array_push($params, $honbucd); $honbucd_clause = " and HonbuCd = ? "; } else { $honbucd_clause = ""; }
		if ($bucd && $bucd != 'XXXXX') { array_push($params, $bucd); $bucd_clause = " and bucd = ? "; } else { $bucd_clause = ""; }
		if ($kacd && $kacd != 'XXXXX') { array_push($params, $kacd); $kacd_clause = " and kacd = ? "; } else { $kacd_clause = ""; }
		if ($shbn && $shbn != 'XXXXX') { array_push($params, $shbn); $shbn_clause = " and srntb010.shbn = ? "; } else { $shbn_clause = ""; }

		$sql ='
		SELECT  
		HonbuCd AS "本部コード",
		(SELECT bunm FROM honbu_view WHERE Honbucd = SGMTB010.HonbuCd ORDER BY updatedate DESC LIMIT 1) AS "本部名称",
		BuCd AS "部コード",
		(SELECT bunm FROM bu_view WHERE BuCd = SGMTB010.BuCd ORDER BY updatedate DESC LIMIT 1) AS "部名称", 
		KaCd AS "課・ユニットコード",
		(SELECT bunm FROM ka_view WHERE KaCd = SGMTB010.KaCd ORDER BY updatedate DESC LIMIT 1) AS "課・ユニット名称", 
		SRNTB010.Shbn AS "社番",
		(SELECT shinnm FROM sgmtb010 WHERE sgmtb010.shbn = SRNTB010.Shbn) AS "氏名",
		Ymd AS "日付", 
		substr(StHm,1,2) || \':\' || substr(StHm,3,2) AS "開始時刻",  
			substr(EdHm,1,2) || \':\' || substr(EdHm,3,2) AS "終了時刻",  
		htncd AS "相手先コード", 
		AiteskNm AS "相手先名", 
		AiteskRank AS "相手先ランク", 
		Basyo AS "場所", 
		MendanNm01 AS "面談者名１", 
		MendanNm02 AS "面談者名２", 
		DoukouNm01 AS "同行者名１", 
		Sdn_Mitumori AS "見積り提示", 
		Sdn_SiyoKaknin AS "採用企画の確認", 
		Sdn_HnbiKekaku AS "販売計画", 
		Sdn_Claim AS "クレーム対応", 
		Sdn_UribaTan AS "売場提案", 
		Sdn_GtjYobi01 AS "月次商談・予備１", 
		Sdn_GtjYobi02 AS "月次商談・予備２", 
		Sdn_GtjYobi03 AS "月次商談・予備３", 
		Sdn_GtjYobi04 AS "月次商談・予備４", 
		Sdn_GtjYobi05 AS "月次商談・予備５", 
		Sdn_Cte_Tessue AS "ティシュー(月次商談)", 
		Sdn_Cte_Toilet AS "トイレット(月次商談)", 
		Sdn_Cte_Kitchen AS "キッチン(月次商談)", 
		Sdn_Cte_Wipe AS "ワイプ(月次商談)", 
		Sdn_Cte_Baby AS "ベビー(月次商談)", 
		Sdn_Cte_Feminine AS "フェミニン(月次商談)", 
		Sdn_Cte_Silver AS "シルバー(月次商談)", 
		Sdn_Cte_Mask AS "マスク(月次商談)", 
		Sdn_Cte_Pet AS "ペット(月次商談)", 
		Sdn_Cte_Yobi01 AS "月次商談カテゴリ予備1", 
		Sdn_Cte_Yobi02 AS "月次商談カテゴリ予備2", 
		Sdn_Cte_Yobi03 AS "月次商談カテゴリ予備3", 
		Sdn_Cte_Yobi04 AS "月次商談カテゴリ予備4", 
		Sdn_Cte_Yobi05 AS "月次商談カテゴリ予備5", 
		Sdn_Cte_Yobi06 AS "月次商談カテゴリ予備6", 
		CASE 
		  WHEN date_part(\'month\', ymd::date)::int BETWEEN 4 AND 9 THEN
		    CASE Sdn_HnkTan
		      WHEN \'0\' THEN
		        date_part(\'year\', ymd::date)::text || \'上期\'
		      WHEN \'1\' THEN
		        date_part(\'year\', ymd::date)::text || \'下期\'
		      ELSE \' \'
		    END
		  ELSE 
		    CASE Sdn_HnkTan
		      WHEN \'0\' THEN
		        date_part(\'year\', ymd::date)::text || \'下期\'
		      WHEN \'1\' THEN
		        CASE
		          WHEN date_part(\'month\', ymd::date)::int BETWEEN 1 AND 3 THEN
		            (date_part(\'year\', ymd::date)::int)::text || \'上期\'
		          ELSE
		            (date_part(\'year\', ymd::date)::int + 1)::text || \'上期\'
		          END
		      ELSE \' \'
		    END
		END AS "半期提案",
		Sdn_Shohin AS "商品案内", 
		Sdn_DonyuTan AS "導入提案", 
		Sdn_MDTeian AS "ＭＤ提案", 
		Sdn_Tanawari AS "棚割提案", 
		Sdn_Tnwrbi AS "販売店の棚割日情報", 
		Sdn_DonyuTume AS "導入日の詰め", 
		Sdn_TnwrKeka AS "棚割結果確認", 
		Sdn_HnkYobi01 AS "半期提案・予備１", 
		Sdn_HnkYobi02 AS "半期提案・予備２", 
		Sdn_HnkYobi03 AS "半期提案・予備３", 
		Sdn_HnkYobi04 AS "半期提案・予備４", 
		Sdn_HnkYobi05 AS "半期提案・予備５", 
		other AS "その他",
		Hnk_Cte_Tessue AS "ティシュー(半期提案)", 
		Hnk_Cte_Toilet AS "トイレット(半期提案)", 
		Hnk_Cte_Kitchen AS "キッチン(半期提案)", 
		Hnk_Cte_Wipe AS "ワイプ(半期提案)", 
		Hnk_Cte_Baby AS "ベビー(半期提案)", 
		Hnk_Cte_Feminine AS "フェミニン(半期提案)", 
		Hnk_Cte_Silver AS "シルバー(半期提案)", 
		Hnk_Cte_Mask AS "マスク(半期提案)", 
		Hnk_Cte_Pet AS "ペット(半期提案)", 
		Hnk_Cte_Yobi01 AS "半期提案カテゴリ予備1", 
		Hnk_Cte_Yobi02 AS "半期提案カテゴリ予備2", 
		Hnk_Cte_Yobi03 AS "半期提案カテゴリ予備3", 
		Hnk_Cte_Yobi04 AS "半期提案カテゴリ予備4", 
		Hnk_Cte_Yobi05 AS "半期提案カテゴリ予備5",
		SeiykNiyo AS "成約商談結果内容", 
		FseiykNiyo AS "不成約商談結果内容", 
		HoryuNiyo AS "保留商談結果内容", 
		SonotaNiyo AS "その他商談結果内容", 
		KakninShbn AS "確認者社番",
		(SELECT shinnm FROM sgmtb010 WHERE shbn = KakninShbn) AS "確認者氏名",
		SijiCmt01 AS "指示コメント", 
		SRNTB010.CreateDate AS "登録日付", 
		SRNTB010.UpdateDate AS "更新日付"
		from 
		(SELECT * FROM srntb010 base 
			INNER JOIN
			(SELECT jyohonum, max(edbn) as edbn FROM srntb010 GROUP BY jyohonum) eda
			ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn
		) srntb010
		inner join SGMTB010 on  
		srntb010.shbn=SGMTB010.shbn
		inner join sgmtb130 on
		srntb010.aiteskcd = sgmtb130.trhkaitcd
		where
		? <= ymd and ymd <= ?
		'.$honbucd_clause.'
		'.$bucd_clause.'
		'.$kacd_clause.'
		'.$shbn_clause.'
		order by  
		Ymd, 
		HonbuCd, 
		BuCd, 
		KaCd, 
		srntb010.Shbn, 
		StHm ' ;
		$result = $this->db->query($sql, $params);		
		return mb_convert_encoding($this->dbutil->csv_from_result($result),"Windows-31J", "UTF-8");
	}
	
	function create_nippo_srntb020_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn) {
		$params = array('015', $ymd_start, $ymd_end);
		if ($honbucd && $honbucd != 'XXXXX') { array_push($params, $honbucd); $honbucd_clause = " and HonbuCd = ? "; } else { $honbucd_clause = ""; }
		if ($bucd && $bucd != 'XXXXX') { array_push($params, $bucd); $bucd_clause = " and bucd = ? "; } else { $bucd_clause = ""; }
		if ($kacd && $kacd != 'XXXXX') { array_push($params, $kacd); $kacd_clause = " and kacd = ? "; } else { $kacd_clause = ""; }
		if ($shbn && $shbn != 'XXXXX') { array_push($params, $shbn); $shbn_clause = " and srntb020.shbn = ? "; } else { $shbn_clause = ""; }

		$sql = '
		WITH situation_list AS (
			SELECT dbnricd, itemcd, itemnm FROM sgmtb080
		),
		basyo_list AS (
			SELECT kbncd,ichiran FROM sgmtb030 WHERE ichiran <> \'\' AND kbnid = ?
		)
		SELECT  
		HonbuCd AS "本部コード",
		(SELECT bunm FROM honbu_view WHERE Honbucd = SGMTB010.HonbuCd ORDER BY updatedate DESC LIMIT 1) AS "本部名称",
		BuCd AS "部コード",
		(SELECT bunm FROM bu_view WHERE BuCd = SGMTB010.BuCd ORDER BY updatedate DESC LIMIT 1) AS "部名称", 
		KaCd AS "課・ユニットコード",
		(SELECT bunm FROM ka_view WHERE KaCd = SGMTB010.KaCd ORDER BY updatedate DESC LIMIT 1) AS "課・ユニット名称", 
		SRNTB020.Shbn AS "社番",
		(SELECT shinnm FROM sgmtb010 WHERE sgmtb010.shbn = SRNTB020.Shbn) AS "氏名",
		TnpKB AS "店舗区分", 
		Ymd AS "日付", 
		substr(StHm,1,2) || \':\' || substr(StHm,3,2) AS "開始時刻",  
		substr(EdHm,1,2) || \':\' || substr(EdHm,3,2) AS "終了時刻",  
		AiteskCd AS "相手先コード", 
		AiteskNm AS "相手先名", 
		MendanNm01 AS "面談者名１", 
		MendanNm02 AS "面談者名２", 
		DoukouNm01 AS "同行者名１", 
		Ktd_TnpShdn AS "店舗商談", 
		Ktd_JohoSusu AS "情報収集", 
		Ktd_JohoAnai AS "商品情報案内", 
		Ktd_TnkiKosyo AS "展開場所・アウト展開交渉", 
		Ktd_SuisnHanbai AS "推奨販売交渉", 
		Ktd_Jyutyu AS "受注促進", 
		Ktd_TnpYobi01 AS "店舗商談・予備１", 
		Ktd_TnpYobi02 AS "店舗商談・予備２", 
		Ktd_TnpYobi03 AS "店舗商談・予備３", 
		Ktd_TnpYobi04 AS "店舗商談・予備４", 
		Ktd_TnpYobi05 AS "店舗商談・予備５", 
		Ktd_ShdnIgai AS "店内作業", 
		Ktd_Satuei AS "売場撮影", 
		Ktd_Mente AS "売場メンテナンス", 
		Ktd_Zaiko AS "在庫確認", 
		Ktd_Hoju AS "商品補充", 
		Ktd_HanskSeti AS "販促物の設置", 
		Ktd_Yamazumi AS "山積み", 
		Ktd_Beta AS "ベタ付け", 
		Ktd_SdnIgiYobi01 AS "店内作業・予備１", 
		Ktd_SdnIgiYobi02 AS "店内作業・予備２", 
		Ktd_SdnIgiYobi03 AS "店内作業・予備３", 
		Ktd_SdnIgiYobi04 AS "店内作業・予備４", 
		Ktd_SdnIgiYobi05 AS "店内作業・予備５", 
		mr AS "競合店調査(MR)",
		Cte_Tessue AS "ティシュー", 
		Cte_Toilet AS "トイレット", 
		Cte_Kitchen AS "キッチン", 
		Cte_Wipe AS "ワイプ", 
		Cte_Baby AS "ベビー", 
		Cte_Feminine AS "フェミニン", 
		Cte_Silver AS "シルバー", 
		Cte_Mask AS "マスク", 
		Cte_Pet AS "ペット", 
		Cte_Yobi01 AS "カテゴリ予備1", 
		Cte_Yobi02 AS "カテゴリ予備2", 
		Cte_Yobi03 AS "カテゴリ予備3", 
		Cte_Yobi04 AS "カテゴリ予備4", 
		Cte_Yobi05 AS "カテゴリ予備5", 
		Cte_Yobi06 AS "カテゴリ予備6",
		sdn_niyo AS "作業結果", 
		
		Out_SituationCd01 AS "重点商品アウト展開状況コード１",
		(SELECT itemnm FROM situation_list 
			WHERE dbnricd = substr(Out_SituationCd01, 1, 2) and
			itemcd = substr(Out_SituationCd01, 3, 2)) AS "重点商品アウト展開状況１", 
		Out_situationBasyo01 AS "重点商品アウト展開状況場所コード１", 
		(SELECT ichiran FROM basyo_list WHERE kbncd = Out_situationBasyo01) AS "重点商品アウト展開状況場所1",
		
		Out_SituationCd02 AS "重点商品アウト展開状況コード2",
		(SELECT itemnm FROM situation_list 
			WHERE dbnricd = substr(Out_SituationCd02, 1, 2) and
			itemcd = substr(Out_SituationCd02, 3, 2)) AS "重点商品アウト展開状況2", 
		Out_situationBasyo02 AS "重点商品アウト展開状況場所コード2", 
		(SELECT ichiran FROM basyo_list WHERE kbncd = Out_situationBasyo02) AS "重点商品アウト展開状況場所2",
		
		Out_SituationCd03 AS "重点商品アウト展開状況コード3",
		(SELECT itemnm FROM situation_list 
			WHERE dbnricd = substr(Out_SituationCd03, 1, 2) and
			itemcd = substr(Out_SituationCd03, 3, 2)) AS "重点商品アウト展開状況3", 
		Out_situationBasyo03 AS "重点商品アウト展開状況場所コード3", 
		(SELECT ichiran FROM basyo_list WHERE kbncd = Out_situationBasyo03) AS "重点商品アウト展開状況場所3",
		
		Out_SituationCd04 AS "重点商品アウト展開状況コード4",
		(SELECT itemnm FROM situation_list 
			WHERE dbnricd = substr(Out_SituationCd04, 1, 2) and
			itemcd = substr(Out_SituationCd04, 3, 2)) AS "重点商品アウト展開状況4", 
		Out_situationBasyo04 AS "重点商品アウト展開状況場所コード4", 
		(SELECT ichiran FROM basyo_list WHERE kbncd = Out_situationBasyo04) AS "重点商品アウト展開状況場所4",
		
		Out_SituationCd05 AS "重点商品アウト展開状況コード5",
		(SELECT itemnm FROM situation_list 
			WHERE dbnricd = substr(Out_SituationCd05, 1, 2) and
			itemcd = substr(Out_SituationCd05, 3, 2)) AS "重点商品アウト展開状況5", 
		Out_situationBasyo05 AS "重点商品アウト展開状況場所コード5", 
		(SELECT ichiran FROM basyo_list WHERE kbncd = Out_situationBasyo05) AS "重点商品アウト展開状況場所5",
		
		KakninShbn AS "確認者社番",
		(SELECT shinnm FROM sgmtb010 WHERE shbn = KakninShbn) AS "確認者氏名",
		SijiCmt01 AS "指示コメント", 
		SRNTB020.CreateDate AS "登録日付", 
		SRNTB020.UpdateDate AS "更新日付"
		from
		(SELECT * FROM SRNTB020 base 
			INNER JOIN
			(SELECT jyohonum, max(edbn) as edbn FROM SRNTB020 GROUP BY jyohonum) eda
			ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn
		) SRNTB020
		inner join SGMTB010 on 
		SRNTB020.shbn=SGMTB010.shbn
		where
		? <= ymd and ymd <= ?
		'.$honbucd_clause.'
		'.$bucd_clause.'
		'.$kacd_clause.'
		'.$shbn_clause.'
		order by  
		Ymd, 
		HonbuCd, 
		BuCd, 
		KaCd,
		SRNTB020.Shbn, 
		StHm';
		
		$result = $this->db->query($sql, $params);
		return mb_convert_encoding($this->dbutil->csv_from_result($result), "Windows-31J", "UTF-8");
	}

	function create_nippo_srntb030_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn) {
		$params = array($ymd_start, $ymd_end);
		if ($honbucd && $honbucd != 'XXXXX') { array_push($params, $honbucd); $honbucd_clause = " and HonbuCd = ? "; } else { $honbucd_clause = ""; }
		if ($bucd && $bucd != 'XXXXX') { array_push($params, $bucd); $bucd_clause = " and bucd = ? "; } else { $bucd_clause = ""; }
		if ($kacd && $kacd != 'XXXXX') { array_push($params, $kacd); $kacd_clause = " and kacd = ? "; } else { $kacd_clause = ""; }
		if ($shbn && $shbn != 'XXXXX') { array_push($params, $shbn); $shbn_clause = " and srntb030.shbn = ? "; } else { $shbn_clause = ""; }

		$sql = '
			SELECT  
			HonbuCd AS "本部コード",
			(SELECT bunm FROM honbu_view WHERE Honbucd = SGMTB010.HonbuCd ORDER BY updatedate DESC LIMIT 1) AS "本部名称",
			BuCd AS "部コード",
			(SELECT bunm FROM bu_view WHERE BuCd = SGMTB010.BuCd ORDER BY updatedate DESC LIMIT 1) AS "部名称", 
			KaCd AS "課・ユニットコード",
			(SELECT bunm FROM ka_view WHERE KaCd = SGMTB010.KaCd ORDER BY updatedate DESC LIMIT 1) AS "課・ユニット名称", 
			SRNTB030.Shbn AS "社番",
			(SELECT shinnm FROM sgmtb010 WHERE sgmtb010.shbn = SRNTB030.Shbn) AS "氏名",
			Ymd AS "日付",  
		substr(StHm,1,2) || \':\' || substr(StHm,3,2) AS "開始時刻",  
			substr(EdHm,1,2) || \':\' || substr(EdHm,3,2) AS "終了時刻",  
			AiteskCd AS "相手先コード",  
			AiteskNm AS "相手先名",  
			Basyo AS "場所",  
			MendanNm01 AS "面談者名１",  
			MendanNm02 AS "面談者名２",  
			DoukouNm01 AS "同行者名１",  
			Sdn_MitsumoriFollow AS "一般店見積り提示・商談フォロー", 
			Sdn_Syouhin AS "商品案内", 
			Sdn_Kikaku AS "企画案内", 
			Sdn_Jiseki AS "実績報告", 
			Sdn_Yobi01 AS "対代理店・一般店・予備1", 
			Sdn_Yobi02 AS "対代理店・一般店・予備2", 
			Sdn_Yobi03 AS "対代理店・一般店・予備3", 
			Sdn_Yobi04 AS "対代理店・一般店・予備4", 
			Sdn_Yobi05 AS "対代理店・一般店・予備5", 
			Sdn_Mitsumori AS "見積もり提示", 
			Sdn_JizenUtiawase AS "販売店商談事前打合せ", 
			Sdn_KikakuJyoukyou AS "情報収集・企画導入状況確認", 
			Sdn_KanriYobi01 AS "対販売店・予備１", 
			Sdn_KanriYobi02 AS "対販売店・予備２", 
			Sdn_KanriYobi03 AS "対販売店・予備３", 
			Sdn_KanriYobi04 AS "対販売店・予備４", 
			Sdn_KanriYobi05 AS "対販売店・予備５", 
			Sdn_Logistics AS "受発注・物流関連", 
			Sdn_Torikmi AS "取組会議", 
			Sdn_Niyo AS "商談内容",  
			KakninShbn AS "確認者社番",
			(SELECT shinnm FROM sgmtb010 WHERE shbn = KakninShbn) AS "確認者氏名",
			SijiCmt01 AS "指示コメント", 
			SRNTB030.CreateDate AS "登録日付",  
			SRNTB030.UpdateDate AS "更新日付"
			from 
			(SELECT * FROM SRNTB030 base 
				INNER JOIN
				(SELECT jyohonum, max(edbn) as edbn FROM SRNTB030 GROUP BY jyohonum) eda
				ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn
			) SRNTB030
			inner join SGMTB010 on  
			SRNTB030.shbn=SGMTB010.shbn 
			where
			? <= ymd and ymd <= ?
			'.$honbucd_clause.'
			'.$bucd_clause.'
			'.$kacd_clause.'
			'.$shbn_clause.'
			order by  
			Ymd, 
			HonbuCd, 
			BuCd, 
			KaCd, 
			SRNTB030.Shbn, 
			StHm';
		$result = $this->db->query($sql, $params);
		return mb_convert_encoding($this->dbutil->csv_from_result($result), "Windows-31J", "UTF-8");
	}
	
	function create_nippo_srntb040_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn) {
		$params = array('008', $ymd_start, $ymd_end);
		if ($honbucd && $honbucd != 'XXXXX') { array_push($params, $honbucd); $honbucd_clause = " and HonbuCd = ? "; } else { $honbucd_clause = ""; }
		if ($bucd && $bucd != 'XXXXX') { array_push($params, $bucd); $bucd_clause = " and bucd = ? "; } else { $bucd_clause = ""; }
		if ($kacd && $kacd != 'XXXXX') { array_push($params, $kacd); $kacd_clause = " and kacd = ? "; } else { $kacd_clause = ""; }
		if ($shbn && $shbn != 'XXXXX') { array_push($params, $shbn); $shbn_clause = " and srntb040.shbn = ? "; } else { $shbn_clause = ""; }

		$sql = '
			with sagyo_list AS (
				SELECT kbncd,ichiran FROM sgmtb030 WHERE ichiran <> \'\' AND kbnid = ?
			)
			SELECT  
			HonbuCd AS "本部コード",
			(SELECT bunm FROM honbu_view WHERE Honbucd = SGMTB010.HonbuCd ORDER BY updatedate DESC LIMIT 1) AS "本部名称",
			BuCd AS "部コード",
			(SELECT bunm FROM bu_view WHERE BuCd = SGMTB010.BuCd ORDER BY updatedate DESC LIMIT 1) AS "部名称", 
			KaCd AS "課・ユニットコード",
			(SELECT bunm FROM ka_view WHERE KaCd = SGMTB010.KaCd ORDER BY updatedate DESC LIMIT 1) AS "課・ユニット名称", 
			SRNTB040.Shbn AS "社番",
			(SELECT shinnm FROM sgmtb010 WHERE sgmtb010.shbn = SRNTB040.Shbn) AS "氏名",
			Ymd AS "日付", 
		substr(StHm,1,2) || \':\' || substr(StHm,3,2) AS "開始時刻",  
			substr(EdHm,1,2) || \':\' || substr(EdHm,3,2) AS "終了時刻",  
			SagyoNiyo AS "作業内容コード",
			(SELECT ichiran FROM sagyo_list WHERE kbncd = SagyoNiyo) AS "作業内容",
			SntSagyo AS "その他の作業内容", 
			kekka AS "作業結果",
			KakninShbn AS "確認者社番",
			(SELECT shinnm FROM sgmtb010 WHERE shbn = KakninShbn) AS "確認者氏名",
			SijiCmt01 AS "指示コメント", 
			SRNTB040.CreateDate AS "登録日付", 
			SRNTB040.UpdateDate AS "更新日付" 
			from 
			(SELECT * FROM SRNTB040 base 
				INNER JOIN
				(SELECT jyohonum, max(edbn) as edbn FROM SRNTB040 GROUP BY jyohonum) eda
				ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn
			) SRNTB040
			inner join SGMTB010 on  
			SRNTB040.shbn=SGMTB010.shbn 
			where
			? <= ymd and ymd <= ?
			'.$honbucd_clause.'
			'.$bucd_clause.'
			'.$kacd_clause.'
			'.$shbn_clause.'
			order by  
			Ymd, 
			HonbuCd, 
			BuCd, 
			KaCd, 
			SRNTB040.Shbn, 
			StHm';
			
		$result = $this->db->query($sql, $params);
		return mb_convert_encoding($this->dbutil->csv_from_result($result), "Windows-31J", "UTF-8");
	}
	
	function create_nippo_srntb060_csv($ymd_start, $ymd_end, $honbucd, $bucd, $kacd, $shbn) {
		$params = array($ymd_start, $ymd_end);
		if ($honbucd && $honbucd != 'XXXXX') { array_push($params, $honbucd); $honbucd_clause = " and HonbuCd = ? "; } else { $honbucd_clause = ""; }
		if ($bucd && $bucd != 'XXXXX') { array_push($params, $bucd); $bucd_clause = " and bucd = ? "; } else { $bucd_clause = ""; }
		if ($kacd && $kacd != 'XXXXX') { array_push($params, $kacd); $kacd_clause = " and kacd = ? "; } else { $kacd_clause = ""; }
		if ($shbn && $shbn != 'XXXXX') { array_push($params, $shbn); $shbn_clause = " and srntb060.shbn = ? "; } else { $shbn_clause = ""; }

		$sql = '
			SELECT
			HonbuCd AS "本部コード",
			(SELECT bunm FROM honbu_view WHERE Honbucd = SGMTB010.HonbuCd ORDER BY updatedate DESC LIMIT 1) AS "本部名称",
			BuCd AS "部コード",
			(SELECT bunm FROM bu_view WHERE BuCd = SGMTB010.BuCd ORDER BY updatedate DESC LIMIT 1) AS "部名称", 
			KaCd AS "課・ユニットコード",
			(SELECT bunm FROM ka_view WHERE KaCd = SGMTB010.KaCd ORDER BY updatedate DESC LIMIT 1) AS "課・ユニット名称", 
			SRNTB060.Shbn AS "社番",
			(SELECT shinnm FROM sgmtb010 WHERE sgmtb010.shbn = SRNTB060.Shbn) AS "氏名", 
			Ymd AS "日付", 
		substr(StHm,1,2) || \':\' || substr(StHm,3,2) AS "開始時刻",  
			substr(EdHm,1,2) || \':\' || substr(EdHm,3,2) AS "終了時刻",  
			Memo AS "メモ", 
			KakninShbn AS "確認者社番",
			(SELECT shinnm FROM sgmtb010 WHERE shbn = KakninShbn) AS "確認者氏名", 
			SijiCmt AS "指示コメント", 
			SRNTB060.CreateDate AS "登録日付", 
			SRNTB060.UpdateDate AS "更新日付" 
			from 
			(SELECT * FROM SRNTB060 base 
				INNER JOIN
				(SELECT jyohonum, max(edbn) as edbn FROM SRNTB060 GROUP BY jyohonum) eda
				ON base.jyohonum = eda.jyohonum and base.edbn = eda.edbn
				) SRNTB060
			inner join SGMTB010 on  
			SRNTB060.shbn=SGMTB010.shbn
			where
			? <= ymd and ymd <= ?
			'.$honbucd_clause.'
			'.$bucd_clause.'
			'.$kacd_clause.'
			'.$shbn_clause.'
			order by  
			Ymd, 
			HonbuCd, 
			BuCd, 
			KaCd, 
			SRNTB060.Shbn, 
			StHm';
		$result = $this->db->query($sql, $params);
		return mb_convert_encoding($this->dbutil->csv_from_result($result), "Shift-JIS", "UTF-8");
	}
	
		  /**
   *  ドロップダウンリスト変更
   */
  function select_item_memo_n_list()
  {
    try
    {
      log_message('debug',"==========  select_item_list start ==========");

      // モデル呼び出し
      $this->load->model('sgmtb020'); // ユーザー情報

      //
      $DbnriCd = $this->input->post('selected_val');
      $data["busyo_data"] = $this->sgmtb020->get_busyo_data("DbnriCd='{$DbnriCd}'");
      
      $staff_list =array();

      //表示用データ作成
      $this->load->library('table_manager');
      $shbn = $this->session->userdata('shbn');
      $honbu = $_POST['selected_val'];
      $data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,NULL,"4");  // 部署検索　

      //リストデータ出力
      header("Content-type: text/html; charset=utf-8");
      echo $data['search_result_busyo_table'];
      log_message('debug',"========== checker_search_conf select_item_list end ==========");
    }catch(Exception $e){
      // エラー処理
      $this->error_view($e);
    }
  }
	
   /**
   *  ドロップダウンリスト変更
   */
	function select_item_memo_n_unit_list($type=NULL)
		{
			try
			{
			log_message('debug',"========== select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
			$honbu =$_POST['selected_val']['val'];
			$shbn = $this->session->userdata('shbn');

			// 部情報取得
			$bu = substr($_POST['selected_val']['val2'],5);

			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$bu);
			
			$this->load->library('table_set');
			
			//$staff_list =array();
			
			// 本部
			//$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			
			$this->load->library('table_manager');
			//表示用データ作成
			$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,$bu,"4"); 
			
			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}
	
	   /**
   *  ドロップダウンリスト変更
   */
	function select_item_memo_n_user_list($type=NULL)
		{
			try
			{
			log_message('debug',"========== select_item_list start ==========");

			// モデル呼び出し
			$this->load->model('sgmtb020'); // ユーザー情報
		    $honbu = $_POST['selected_val']['val'];
		    // 部情報取得
			$bu= $this->sgmtb020->get_bu_name_data_select($honbu);
		   
		    $busyo =  substr($_POST['selected_val']['val2'],5);
			// 課・ユニット情報取得
			$unit = $this->sgmtb020->get_unit_name_data_select($honbu,$busyo);
			
			$this->load->library('table_set');
			
			// セッション情報から社番を取得
			$data2['shbn'] = $this->session->userdata('shbn');
			// セッション情報から管理者フラグを取得
			$data2['admin_flg'] = $this->session->userdata('admin_flg');
			
			// 本部
			$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			// 担当者
			

			$ka = substr($_POST['selected_val']['val3'],10);

			
			$this->load->model('sgmtb010'); // ユーザ別検索情報（相手先）

			$staff_list = $this->sgmtb010->get_shin_data($honbu,$busyo,$ka);
		
			
			if($ka=="XXXXX"){
			 $staff_list = array();
			}
			
			$this->load->library('table_set');
			$shbn = $this->session->userdata('shbn');

			// 本部
			//$head = $this->sgmtb020->get_honbu_name_data();
			//$head = $this->table_set->get_head_info($data2, BUSINESS_UNIT_HEAD);
			
			$this->load->library('table_manager');
			//表示用データ作成
			$data['search_result_busyo_table'] = $this->table_manager->search_unit_set_b_table($shbn,NULL,$honbu,$busyo,"4",$ka); ;

			//リストデータ出力
			header("Content-type: text/html; charset=utf-8");
			echo $data['search_result_busyo_table'];
			log_message('debug',"========== Select_client_search select_item_list end ==========");
			}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
		}
	}

}
/* End of file Data_output_csv.php */
/* Location: ./application/controllers/Data_output_csv.php */
