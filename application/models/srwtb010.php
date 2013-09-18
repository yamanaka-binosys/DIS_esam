<?php

/**
 * 確認者情報テーブル
 */
class Srwtb010 extends CI_Model {

	function __construct() {
		parent::__construct();
	}
	
	/**
	 * 一般社員トップ画面・ユニット長閲覧状況
	 * 表示用のデータを取得します。
	 * 
	 * swrtb010から
	 * ログインしているユーザが依頼者(irshbn)で、確認者(kashbn)がユーザのユニット長である
	 * 日報(shubetu=01)を取得します。
	 * 閲覧期限(kigen)が過ぎていて、かつ既読(etujukyo=1)のものは表示されない。
	 * ※未読の日報は閲覧期限が過ぎていても表示される。
	 */
	function get_unit_leader_read_general_top_data($shbn)
	{
		$sql = "
			SELECT
				srwtb010.kashbn, -- 確認者(ユニット長)の社番
				leader.shinnm as view_shinnm, -- ユニット長の名前
				srwtb010.etujukyo, -- 閲覧状況
				srwtb010.comment, -- コメント
				srwtb010.ymd -- 日報の日付
			FROM
				srwtb010,
				sgmtb010 as self,
				sgmtb010 as leader
			WHERE
				-- JOIN
				srwtb010.irshbn = self.shbn AND
				srwtb010.kashbn = leader.shbn AND
				-- 確認者がユーザのユニット長
				srwtb010.kashbn = self.unitshbn AND
				-- 依頼者がユーザ
				srwtb010.irshbn = ? AND
				-- 種別が日報
				srwtb010.shubetu = ? AND
				NOT (
					-- 掲載期限が過ぎていて、かつ既読のものは表示されない
					kigen::int < to_char(now(), 'yyyyMMdd')::int AND
					srwtb010.etujukyo = ?
				)
			-- 日報日付が新しいもの順 + データ追加順
			ORDER BY srwtb010.ymd DESC, srwtb010.etujukyo, srwtb010.jyohonum DESC";
		log_message('debug', $sql);
		$query = $this->db->query($sql, array($shbn, '01', '1'));
		return $query->result_array();
	}
	
	/**
	 * 一般社員トップ画面・受取日報
	 * 
	 * srwtb010から
	 * ログインしているユーザが確認者(kashbn)の日報(shubetu=01)を取得する。
	 * 掲載期限が過ぎているものは表示しない。
	 * ※依頼者での絞り込みはしない。
	 * ※未読のものでも掲載期限が過ぎると表示されない。
	 */
	function get_received_general_top_data($shbn) {
		$sql = "
			SELECT
				srwtb010.irshbn, -- 依頼者の社員番号
				sender.shinnm as ir_shinnm, -- 依頼者の名前
				srwtb010.etujukyo, -- 閲覧状況
				srwtb010.ymd -- 日報の日付
			FROM
				srwtb010,
				sgmtb010 as sender
			WHERE
				-- JOIN
				srwtb010.irshbn = sender.shbn AND
				-- 確認者がユーザ
				srwtb010.kashbn = ? AND
				-- 種別が日報
				srwtb010.shubetu = ? AND
				NOT (
					-- 期限が過ぎているものは表示されない
					kigen::int < to_char(now(), 'yyyyMMdd')::int
				)
			-- 日報日付が新しいもの順 + データ追加順
			ORDER BY srwtb010.ymd DESC, srwtb010.etujukyo, srwtb010.jyohonum DESC";
		log_message('debug', $sql);
		$query = $this->db->query($sql, array($shbn, '01'));
		return $query->result_array();
	}
	
	/**
	 * ユニット長トップ画面・ユニット長日報閲覧状況表示用
	 * ※このメソッドの引数に渡す社員番号はユニット長のものであるということが前提。
	 * 
	 * srwtb010から
	 * ログインしているユニット長が確認者(kashbn)で、かつ部下が依頼者(irshbn)の日報を取得する(shubetu=01)。
	 * 閲覧期限(kigen)が過ぎていて、かつ既読(etujukyo=1)のものは表示されない。
	 * ※未読の日報は閲覧期限が過ぎていても表示される。
	 */
	function get_followers_read_leader_top_data($shbn) {
		$sql = "
			SELECT
				follower.shbn, -- 部下の社員番号
				follower.shinnm as view_shinnm, -- 部下の名前
				srwtb010.etujukyo, -- 閲覧状況
				srwtb010.comment, -- コメントの有無
				srwtb010.ymd -- 日報の日付
			FROM
				srwtb010,
				sgmtb010 as follower
			WHERE
				-- JOIN
				srwtb010.irshbn = follower.shbn AND
				srwtb010.irshbn IN (
					-- 部下の社員番号
					SELECT shbn FROM sgmtb010 WHERE unitshbn = ? AND shbn <> ?
				)
				-- 確認者がユーザ(ユニット長)
				AND srwtb010.kashbn = ?
				-- 種別が日報
				AND srwtb010.shubetu = ?
				AND NOT (
					-- 閲覧期限が過ぎていて、かつ、既読のものは表示されない。
					kigen::int < to_char(now(), 'yyyyMMdd')::int AND
					srwtb010.etujukyo = ?
				)
			-- 日報日付が新しいもの順 + データ追加順
			ORDER BY srwtb010.ymd DESC, srwtb010.etujukyo, srwtb010.jyohonum DESC";
		
		log_message('debug', $sql);
		$query = $this->db->query($sql, array($shbn, $shbn, $shbn, '01', '1'));
		return $query->result_array();
	}
	
	/**
	 * ユニット長トップ画面・受取日報
	 * ※このメソッドの引数に渡す社員番号はユニット長のものであるということが前提になっています。
	 * 
	 * srwtb010から
	 * ログインしているユニット長が確認者(kashbn)で、依頼者(irshbn)が部下以外の
	 * 日報(shubetu=01)を取得する。
	 * 掲載期限が過ぎているものは表示しない。
	 * 
	 * ※未読のものでも掲載期限が過ぎると表示されない。
	 */
	function get_received_leader_top_data($shbn) {
		$sql = "
		SELECT
			srwtb010.irshbn, -- 依頼者の社番
			sender.shinnm as ir_shinnm, -- 依頼者の名前
			srwtb010.etujukyo, -- 閲覧状況
			srwtb010.ymd -- 日報の日付
		FROM
			srwtb010,
			sgmtb010 as sender
		WHERE
			-- JOIN
			srwtb010.irshbn = sender.shbn AND
			-- 確認者がユーザ(ユニット長)
			srwtb010.kashbn = ? AND
			-- 種別が日報
			srwtb010.shubetu = ? AND
			NOT (
				-- 掲載期限が過ぎているものは表示しない
				kigen::int < to_char(now(), 'yyyyMMdd')::int OR
				-- 依頼者が部下のものは表示しない
				srwtb010.irshbn IN (
					SELECT shbn FROM sgmtb010 WHERE unitshbn = ? AND shbn <> ?
				) OR
				-- TODO 後で消す
				srwtb010.irshbn = ?
			)
		-- 日報日付が新しいもの順 + データ追加順
		ORDER BY srwtb010.ymd DESC, srwtb010.etujukyo, srwtb010.jyohonum DESC";
		log_message('debug', $sql);
		$query = $this->db->query($sql, array($shbn, '01', $shbn, $shbn, $shbn));
		return $query->result_array();
	}
	
	function insert_srwtb010_data($record_data){
		unset($record_data['jyohonum']);
		unset($record_data['edbn']);
		
		log_message('debug',"========== srwtb010 insert_srwtb010_data start ==========");
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
		
		$sql .= " INSERT INTO srwtb010(";
		$sql .= $name_string;
		$sql .= "createdate";
		$sql .= ") VALUES (";
		$sql .= $value_string;
		$sql .= "'".date("Ymd")."'";
		$sql .= ")";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		
		// SQL実行
		$query = $this->db->query($sql);
		
		if($query){
			log_message('debug',"========== srwtb010 insert_srwtb010_data end ==========");
			return TRUE;
		}else{
			log_message('debug',"========== srwtb010 insert_srwtb010_data end ==========");
			return FALSE;
		}
	}
	
	/**
	 * 社番と日付に該当する確認者がいるかどうかを返します。
	 */ 
	function has_confirmer($shbn, $ymd) {
		$sql = "SELECT irshbn FROM srwtb010 WHERE irshbn= ? AND ymd = ?";
		$query = $this->db->query($sql, array($shbn, $ymd));
		return 0 < sizeOf($query->result_array());
	}

	function delete_srwtb010_data($irshbn,$ymd){
		log_message('debug',"========== srwtb010 delete_srwtb010_data start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "DELETE FROM srwtb010";
		//$sql .= " WHERE irshbn = '{$irshbn}'";
		//$sql .= " AND ymd = '{$ymd}'";
		$sql .= " WHERE irshbn = ?";
		$sql .= " AND ymd = ?";
		$sql .= " ;";
		
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql, array($irshbn, $ymd));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
		log_message('debug',"========== srwtb010 delete_srwtb010_data end ==========");
	}
	
	function update_etujukyo($kashbn,$irshbn,$ymd){
		log_message('debug',"========== srwtb010 update_etujukyo start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "UPDATE srwtb010";
		$sql .= " SET etujukyo = '1'";
		//$sql .= " WHERE kashbn = '{$kashbn}'";
		//$sql .= " AND irshbn = '{$irshbn}'";
		//$sql .= " AND ymd = '{$ymd}'";
		$sql .= " WHERE kashbn = ?";
		$sql .= " AND irshbn = ?";
		$sql .= " AND ymd = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($kashbn,$irshbn,$ymd));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
		log_message('debug',"========== srwtb010 update_etujukyo end ==========");
	}
	
	function update_comment($kashbn,$irshbn,$ymd){
		log_message('debug',"========== srwtb010 update_comment start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "UPDATE srwtb010";
		$sql .= " SET comment = '1'";
		//$sql .= " WHERE kashbn = '{$kashbn}'";
		//$sql .= " AND irshbn = '{$irshbn}'";
		//$sql .= " AND ymd = '{$ymd}'";
		$sql .= " WHERE kashbn = ?";
		$sql .= " AND irshbn = ?";
		$sql .= " AND ymd = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($kashbn,$irshbn,$ymd));
		
		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
		log_message('debug',"========== srwtb010 update_comment end ==========");
	}
	
	function update_kakninflg($kashbn,$irshbn,$ymd){
		log_message('debug',"========== srwtb010 update_kakninflg start ==========");
		// 初期化
		$sql = ""; // sql_regcase文字列
		$query = NULL; // SQL実行結果
		
		$sql .= "UPDATE srwtb010";
		$sql .= " SET kakninflg = '1'";
		//$sql .= " WHERE kashbn = '{$kashbn}'";
		//$sql .= " AND irshbn = '{$irshbn}'";
		//$sql .= " AND ymd = '{$ymd}'";
		$sql .= " WHERE kashbn = ?";
		$sql .= " AND irshbn = ?";
		$sql .= " AND ymd = ?";
		$sql .= " ;";
		log_message('debug',"\$sql = $sql");
		// SQL実行
		$query = $this->db->query($sql,array($kashbn,$irshbn,$ymd));

		if($query){
			return TRUE;
		}else{
			return FALSE;
		}
		log_message('debug',"========== srwtb010 update_kakninflg end ==========");
	}
	
}