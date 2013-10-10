<?php

class Srntb130 extends CI_Model {
	
    public $error_date;         // エラーが発生した日付を格納

	function __construct()
	{
		// Model クラスのコンストラクタを呼び出す
		parent::__construct();
	}

	function get_srntb130_data($condition_data){
		log_message('debug',"========== srntb130 get_srntb130_data start ==========");
		log_message('debug',"\$shbn = " . $condition_data['shbn']);
		log_message('debug',"\$jyohonum = " . $condition_data['jyohonum']);
		log_message('debug',"\$edbn = " . $condition_data['edbn']);
		
		// 引数より検索条件を取得
		// 情報ナンバー、枝番、社番
		if( ! isset($condition_data['jyohonum']) OR ! isset($condition_data['edbn']) OR ! isset($condition_data['shbn']))
		{
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		$shbn = $condition_data['shbn'];
		$jyohonum = $condition_data['jyohonum'];
//		$edbn = $condition_data['edbn'];
		$edbn = sprintf('%02d', $condition_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT jyohonum,";                    // 情報ナンバー
		$sql .= "        edbn,";                        // 枝番
		$sql .= "        shbn,";                        // 社番
		$sql .= "        ymd,";                         // 日付
		$sql .= "        sthm,";                        // 開始時刻
		$sql .= "        edhm,";                        // 終了時刻
		$sql .= "        aiteskcd,";                    // 相手先コード
		$sql .= "        aitesknm,";                    // 相手先名
		$sql .= "        mts_aiteskcd,";                // 元正式相手先コード
		$sql .= "        mtk_aiteskcd,";                // 元仮相手先コード
		$sql .= "        mtk_aitesknm,";                // 元仮相手先名
		$sql .= "        basyo,";                       // 場所
		$sql .= "        mendannm01,";                  // 面談者名１
		$sql .= "        mendannm02,";                  // 面談者名２
		$sql .= "        doukoucd01,";                  // 同行者コード１
		$sql .= "        doukounm01,";                  // 同行者名１
		$sql .= "        doukoucd02,";                  // 同行者コード２
		$sql .= "        doukounm02,";                  // 同行者名２
		$sql .= "        sdn_shohnjoho,";               // 商品情報案内
		$sql .= "        sdn_kikaku,";                  // 企画案内
		$sql .= "        sdn_jiseki,";                  // 実績報告
		$sql .= "        sdn_utiawase,";                // セールスと打ち合せ
		$sql .= "        sdn_torikmi,";                 // 取組会議
		$sql .= "        sdn_yosin,";                   // 与信
		$sql .= "        sdn_nhnkeikak,";               // 納品計画
		$sql .= "        sdn_sonota,";                  // その他
		$sql .= "        sdn_tnwrkaknin,";              // 棚割結果の確認
		$sql .= "        sdn_yobi01,";                  // 予備１（作業内容１）
		$sql .= "        sdn_yobi02,";                  // 予備２（作業内容２）
		$sql .= "        sdn_yobi03,";                  // 予備３（作業内容３）
		$sql .= "        sdn_yobi04,";                  // 予備４（作業内容４）
		$sql .= "        sdn_yobi05,";                  // 予備５（作業内容５）
		$sql .= "        sdn_yobi06,";                  // 予備６（作業内容６）
		$sql .= "        cte_tessue,";                  // ティシュー
		$sql .= "        cte_toilet,";                  // トイレット
		$sql .= "        cte_kitchen,";                 // キッチン
		$sql .= "        cte_wipe,";                    // ワイプ
		$sql .= "        cte_baby,";                    // ベビー
		$sql .= "        cte_feminine,";                // フェミニン
		$sql .= "        cte_silver,";                  // シルバー
		$sql .= "        cte_mask,";                    // マスク
		$sql .= "        cte_pet,";                     // ペット
		$sql .= "        cte_yobi01,";                  // 予備７（カテゴリー１）
		$sql .= "        cte_yobi02,";                  // 予備８（カテゴリー２）
		$sql .= "        cte_yobi03,";                  // 予備９（カテゴリー３）
		$sql .= "        cte_yobi04,";                  // 予備１０（カテゴリー４）
		$sql .= "        cte_yobi05,";                  // 予備１１（カテゴリー５）
		$sql .= "        cte_yobi06,";                  // 予備１２（カテゴリー６）
		$sql .= "        sijicmt01,";                   // 指示コメント１
		$sql .= "        znkakninshbn,";                // 前確認者
		$sql .= "        createdate,";                  // 登録日付
		$sql .= "        updatedate,";                  // 更新日付
		$sql .= "        yobim01,";                     // 予備１３（文字１）
		$sql .= "        yobim02,";                     // 予備１４（文字２）
		$sql .= "        kbncd,";                       // 区分
		$sql .= "        rnkcd,";                       // ランク
		$sql .= "        sdn_mitsumorifollow,";         // 一般店見積り提示・商談フォロー
		$sql .= "        sdn_syouhin,";                 // 商品案内
		$sql .= "        sdn_mitsumori,";               // 見積り提示
		$sql .= "        sdn_jizenutiawase,";           // 販売店商談事前打合せ
		$sql .= "        sdn_kikakujyoukyou,";          // 情報収集・企画導入状況確認
		$sql .= "        sdn_kanriyobi01,";             // 予備１（管理販売店１）
		$sql .= "        sdn_kanriyobi02,";             // 予備２（管理販売店２）
		$sql .= "        sdn_kanriyobi03,";             // 予備３（管理販売店３）
		$sql .= "        sdn_kanriyobi04,";             // 予備４（管理販売店４）
		$sql .= "        sdn_kanriyobi05,";             // 予備５（管理販売店５）
		$sql .= "        sdn_logistics,";               // 受発注・物流関連
		$sql .= "        sdn_yotei,";                   // 商談予定
		$sql .= "        recode_flg";                   // 登録フラグ
		$sql .= " FROM srntb130";
		$sql .= " WHERE jyohonum = '{$jyohonum}'";
		$sql .= "        AND edbn = '{$edbn}'";
		$sql .= "        AND shbn = '{$shbn}'";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql);
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		
		log_message('debug',"========== srntb130 get_srntb120_data end ==========");
		return $result_data;
	}
	
	function update_srntb130_data($record_data)
	{
		if (!isset($record_data['jyohonum']))
		{
			log_message('error', 'cannot update without jyohonum');
			return false;
		}
		unset($record_data['edbn']);
		$jyohonum = $record_data['jyohonum'];
		unset($record_data['jyohonum']);
		return $this->db->update('srntb130', $record_data, array('jyohonum' => $jyohonum));
	}
	
	function insert_srntb130_data($record_data)
	{
		unset($record_data['jyohonum']);
		unset($record_data['edbn']);
		
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		$name_string = "";
		$value_string = "";
		
		foreach ($record_data as $key => $value) {
			$name_string .= $key . ",";
			$value_string .= "'" . $value . "',";
		}
		
		$sql .= " INSERT INTO srntb130(";
//		$sql .= substr($name_string,0,-1);
		$sql .= $name_string;
		$sql .= "createdate";
		$sql .= ") VALUES (";
//		$sql .= substr($value_string,0,-1);
		$sql .= $value_string;
		$sql .= "'".date("Ymd")."'";
		$sql .= ")";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql);
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function delete_srntb130_data($jyohonum,$edbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srntb130";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	function get_schedule_data($jyohonum,$edbn){
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		$result_data = NULL; // 戻り値
		
		// SQL文作成
		$sql .= " SELECT";
		$sql .= "  sthm,";
		$sql .= "  aitesknm";
		$sql .= " FROM srntb130";
		$sql .= " WHERE jyohonum = ?";
		$sql .= " AND edbn = ";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($jyohonum,$edbn));
		// 取得確認
		if($query->num_rows() > 0)
		{
			$result_data = $query->result_array();
		}
		return $result_data;
	}
	
    //
    // 既に登録されている同日同時刻のデータの有無を確認する。
    //
    function st_et_check($shbn, $startdatetime, $enddatetime) {

        log_message('debug', "========== " . __METHOD__ . " (" . $shbn . ", " . $startdatetime . ", " . $enddatetime . ") start ==========");

        // 初期化
        $sql = ""; // sql_regcase文字列
        $query = NULL; // SQL実行結果
        $result_data = NULL; // 戻り値
        $this->error_date = null;   // エラー日付初期化
        // SQL文作成
        $sql .= " SELECT shbn, ymd, sthm, edhm FROM srntb130 WHERE shbn = ? AND ymd = ? ";
        log_message('debug', "\$sql = $sql");
        // SQL実行
        $query = $this->db->query($sql, array($shbn, date("Ymd", $startdatetime)));
        // 取得確認
        if ($query->num_rows() > 0) {
            $result_data = $query->result_array();
            foreach ($result_data as $rec) {
                $rec_start_time = strtotime($rec['ymd'] . 't' . $rec['sthm'] . '00');   // DBに登録されている開始日付時刻
                $rec_end_time = strtotime($rec['ymd'] . 't' . $rec['edhm'] . '00');     // DBに登録されている終了日付時刻
                // 登録済み開始時刻 < 入力開始時刻 < 登録済み終了時刻 
                if ($rec_start_time < $startdatetime && $startdatetime < $rec_end_time) {
                    $this->error_date = $startdatetime;
                    return TRUE;
                }
                // 登録済み開始時刻 < 入力終了時刻 < 登録済み終了時刻 
                if ($rec_start_time < $enddatetime && $enddatetime < $rec_end_time) {
                    $this->error_date = $startdatetime;
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
	
	
}

?>
