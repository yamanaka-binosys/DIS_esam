<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Top_manager {

	/**
	 * カレンダーデータ取得
	 */
	function get_calendar_data($shbn)
	{
		log_message('debug',"========== Calendar_manager _get_calendar_data start ==========");
		log_message('debug',"\$shbn = $shbn");

		// 初期化
		$CI =& get_instance();
		$CI->load->library('calendar_manager');
		$d = $CI->calendar_manager->_get_calendar_data($shbn);
		return $d;
	}

	/**
	 * ToDoデータ取得
	 */
	function get_todo_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srktb040');
		$todo_data = array();
		$db_data = $CI->srktb040->get_top_data($shbn);
		$db_data = is_null($db_data) ? array() : $db_data;
		
		foreach ($db_data as $key => $value) {
			$todo_data[$key]['jyohonum'] = $value['jyohonum'];
			$todo_data[$key]['edbn'] = $value['edbn'];
			$todo_data[$key]['day'] = substr($value['designatedday'],4,2)."/".substr($value['designatedday'],6,2);
			$todo_data[$key]['todo'] = $value['todo'];
			$todo_data[$key]['impkbn'] = $value['impkbn'];
			$todo_data[$key]['check'] = $value['finishflg'];
		}
		return $todo_data;
	}

	/**
	 * メモデータ取得
	 */
	function get_memo_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srktb050');
		$db_data = array();
		$base_url = $CI->config->item('base_url');
		
		$db_data = $CI->srktb050->get_top_data();
		
		foreach ($db_data as $key => $value) {
			$createdate = $db_data[$key]['createdate'];
			$db_data[$key]['createdate'] = DateTime::createFromFormat('Ymd', $createdate);
		}
		return $db_data;
	}

	/**
	 * バナーリンクの情報を取得する
	 */
	function get_banner_link_data($shbn) {
		return array();
	}

	/**
	 * ユニット長閲覧状況データを取得する
	 */
	function get_read_report_data($shbn, $isUnitLeader) {
		log_message('debug',"----- " . __METHOD__ . " Start -----");

        $CI =& get_instance();
		$CI->load->model('srwtb010');
		$reading = $CI->config->item(MY_READING_KUBUN);
		$comment = $CI->config->item(MY_COMMENT_KUBUN);
        $boss_comment_reading = $CI->config->item(MY_READING_KUBUN);
		
		$db_data = array();
		
		if ($isUnitLeader) {
			$db_data = $CI->srwtb010->get_followers_read_leader_top_data($shbn);
		} else {
			$db_data = $CI->srwtb010->get_unit_leader_read_general_top_data($shbn);
		}
		foreach ($db_data as $key => $value) {
       		log_message('debug',"----- " . serialize($value) . " -----");

			$date = DateTime::createFromFormat('Ymd', $value['ymd']);
			$db_data[$key]['ymd_date'] = $date;
			$db_data[$key]['etujukyo'] = $reading[$value['etujukyo']];
			$db_data[$key]['comment'] = $comment[$value['comment']];
            if (!$isUnitLeader) {
                if(is_null($value['commentetujokyo']) || $value['commentetujokyo'] == ''){
                    $db_data[$key]['comment'] .= '(' . $boss_comment_reading['0'] . ')';
                }else{
                    $db_data[$key]['comment'] .= '(' . $boss_comment_reading[$value['commentetujokyo']] . ')';
                }
            }
        }
		log_message('debug',"----- " . __METHOD__ . " End -----");
        return $db_data;
	}

	/**
	 * 受取日報データの取得
	 */
	function get_received_report_data($shbn, $isUnitLeader) {
        
		log_message('debug',"----- " . __METHOD__ . " Start -----");
		$CI =& get_instance();
		$CI->load->model('srwtb010');
		$reading = $CI->config->item(MY_READING_KUBUN);
		$comment = $CI->config->item(MY_COMMENT_KUBUN);
        $boss_comment_reading = $CI->config->item(MY_READING_KUBUN);
        
		$db_data = array();
		
		if ($isUnitLeader) {
			$db_data = $CI->srwtb010->get_received_leader_top_data($shbn);
			foreach ($db_data as $key => $value) {
				$date = DateTime::createFromFormat('Ymd', $value['ymd']);
				$db_data[$key]['ymd_date'] = $date;
				$db_data[$key]['etujukyo'] = $reading[$value['etujukyo']];
                $db_data[$key]['comment'] = $comment[$value['comment']];
                if(is_null($value['commentetujokyo']) || $value['commentetujokyo'] == ''){
                    $db_data[$key]['comment'] .= '(' . $boss_comment_reading['0'] . ')';
                }else{
                    $db_data[$key]['comment'] .= '(' . $boss_comment_reading[$value['commentetujokyo']] . ')';
                }
            }
		} else {
			$db_data = $CI->srwtb010->get_received_general_top_data($shbn);
			foreach ($db_data as $key => $value) {
				$date = DateTime::createFromFormat('Ymd', $value['ymd']);
				$db_data[$key]['ymd_date'] = $date;
				$db_data[$key]['etujukyo'] = $reading[$value['etujukyo']];
    			$db_data[$key]['comment'] = $comment[$value['comment']];
			}
		}
        log_message('debug',"\$db_data = " . serialize($db_data));
		log_message('debug',"----- " . __METHOD__ . " End -----");
		return $db_data;
	}

	/**
	 * スケジュールデータの取得
	 */
	function get_schedule_data($shbn) {
		$CI =& get_instance();
		$CI->load->model(array('common'));
		return $CI->common->get_followers_schedule($shbn);
	}

	/**
	 * メッセージデータの取得
	 */
	function get_info_data($shbn) {
		$CI =& get_instance();
		$CI->load->model('srktb060');
		$today = date("Ymd");
		$count = 0;
		$info_data = array(); // 初期化
		$shbn = $CI->session->userdata('shbn');
		$get_data = $CI->srktb060->get_top_data($shbn);
		$base_url = $CI->config->item('base_url');
		
		foreach ($get_data as $key => $value) {
			if ($value['tuchistartdate'] <= $today AND $today <= $value['tuchienddate']) {
				//					if ($value['allflg']) {
				$info_data[$count]['jyohoniyo'] = $value['jyohoniyo'];
				$info_data[$count]['is_bold'] = $value['is_bold'];
				$info_data[$count]['jyohonum'] = $value['jyohonum'];
				// 添付ファイルの有無判定
				if(!empty($value['tempfile'])){
					$file_path = "message/".$value['jyohonum']."/".$value['tempfile'];
					
					// 添付ファイルの存在確認
					log_message('debug',FILE_DIR.$file_path);
					if(file_exists(mb_convert_encoding(FILE_DIR.$file_path,"SJIS"))){
						log_message('debug',"添付あり");
						$info_data[$count]['link'] = $base_url."files/message/".$value['jyohonum']."/".$value['tempfile'];
					}else{
						log_message('debug',"添付なし");
						// 添付ファイルなし
						$info_data[$count]['link'] = NULL;
					}
				}else{
					// 添付ファイルなし
					$info_data[$count]['link'] = NULL;
				}
				$count++;
				//				}
			}
		}

		return $info_data;
	}

}