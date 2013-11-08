<?php

class Calendar extends MY_Controller {

    public $buka_shbn = NULL;
    
	function index()
	{
		try
		{
			// 初期化
			//$data = $this->init();
			// カレンダー（混合）画面表示
//			$this->display($data);
			$this->get_calendar();
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'CALENDER-INDEX'));
		}
		
	}
	
	/**
	 * 共通で使用する初期化処理
	 * 
	 * @access  public
	 * @param   string $item_name 日報・予定の区分け
	 * @return  array $data ヘッダ情報他
	 */
	function init($item_name = NULL)
	{
		try
		{
			log_message('debug',"========== calendar init start ==========");
			log_message('debug',"\$item_name = " . $item_name);
/*			if(is_null($item_name))
			{
				log_message('debug',"\$item_name is NULL");
				throw new Exception("Error Processing Request", '100001');
			}
*/			$this->load->library('common_manager');
			$data = $this->common_manager->init(SHOW_CALENDAR);
            $data['unitcho_shbn'] = NULL;
            if($this->buka_shbn!=NULL){
                $data['unitcho_shbn'] = $data['shbn'];
                $data['shbn'] = $this->buka_shbn; 
            }
			$data['mode'] = MY_CALENDAR_MIX;
			$data['mode'] = $this->session->userdata('calendar_mode');
			// セッション切れの場合
			if(!$data['mode']){
				$data['mode'] = MY_CALENDAR_MIX;
			}
			
			log_message('debug',"admin_flg = " . $data['admin_flg']);
			log_message('debug',"shbn = " . $data['shbn']);
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($data['shbn']);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			log_message('debug',"========== calendar init end ==========");
			return $data;
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CALENDER-INIT'));
		}
	}
	
	/**
	 * 共通で使用する表示処理
	 * 
	 * @access  public
	 * @param   string $item_name 日報・予定の区分け
	 * @param   array $data 各種HTML作成時に必要な値
	 * @return  nothing
	 */
	function display($data)
	{
		try
		{
			$this->load->view(MY_VIEW_CALENDAR, $data, FALSE);
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CALENDER-DISPLAY'));
		}
	}
	
	/**
	 * スケジュール（混合）表示
	 * 
	 * @access  public
	 * @param   string $select_month 選択年月
	 * @return  nothing
	 */
	function get_calendar($select_month = NULL,$calendar_mode = MY_CALENDAR_MIX)
	{
		try
		{
			log_message('debug',"========== calendar plan start ==========");
			log_message('debug',"\$select_month = $select_month");
			// カレンダーモードセッション破棄
			if(is_null($select_month)){
				$this->session->unset_userdata(array('calendar_mode' => ''));
			}
			//log_message('debug',"\$select_calendar = $select_calendar");
			$this->load->library('calendar_manager');
			$data = $this->init();
//			$data = $this->init(MY_CALENDAR_PLAN);
			//$data['select_calendar'] = $select_calendar;
			// カレンダー情報取得
			//$data['month'] = $this->calendar_manager->set_select_month($select_month); // 月選択ボタンテーブル作成
			$data['mode'] = $this->calendar_manager->set_select_mode($data['shbn'],$calendar_mode,$data['unitcho_shbn']);                  // モード選択ボタンテーブル作成
			$data['calendar'] = $this->calendar_manager->set_month_calendar($data['shbn'],$select_month,$calendar_mode, $data['unitcho_shbn']); // カレンダーテーブル作成
			
			if (!$calendar_mode || $calendar_mode == MY_CALENDAR_MIX) {
				$data['summary'] = $this->common->get_summary($data['shbn'],$select_month,$calendar_mode);
			}
			
			log_message('debug',"========== calendar plan end ==========");
			$this->display($data);
		}catch(Exception $e){
			// エラー処理
			//$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CALENDER-GET-CALENDAR'));
		}
	}
	
	/**
	 * スケジュール（日報）表示
	 * 
	 * @access  public
	 * @param   string $select_month 選択年月
	 * @return  nothing
	 */
	function result($select_month = NULL,$select_calendar = MY_RESULT)
	{
		try
		{
			log_message('debug',"========== calendar result start ==========");
			log_message('debug',"\$select_month = $select_month");
			log_message('debug',"\$select_calendar = $select_calendar");
			$this->load->library('calendar_manager');
			$data = $this->init(MY_CALENDAR_RESULT);
			$data['select_calendar'] = $select_calendar;
			// カレンダー情報取得
			$data['mode'] = $this->calendar_manager->set_select_month($select_month); // カレンダーテーブル作成
			$data['calendar'] = $this->calendar_manager->set_month_calendar($data['shbn'],$select_month); // カレンダーテーブル作成
			log_message('debug',"========== calendar result end ==========");
			$this->display($data);
		}catch(Exception $e){
			// エラー処理
			$this->error_view($e);
			$this->load->view('/parts/error/error.php',array('errcode' => 'CALENDER-RESULT'));
		}
	}
	
	/**
	 * スケジュール（日報または予定）表示
	 * 初期表示の月から表示年月を変更する場合に使用
	 * 
	 * @access  public
	 * @param   string $select_month 選択年月
	 * @return  nothing
	 */
	function select()
	{
		try
		{
			log_message('debug',"========== calendar select start ==========");
			// 初期設定
			$this->load->library('calendar_manager');
			$calendar_mode = MY_CALENDAR_MIX;
			$post = NULL;
            
            // 部下のスケジュール取得かどうかを判定する
			if(isset($_POST['buka_shbn']) && $_POST['buka_shbn']!="")  {
                log_message('debug'," ===== " . __METHOD__ . ":" . __LINE__ . " \$_POST['buka_shbn'] = " . $_POST['buka_shbn'] . " ===== ");
                $this->buka_shbn = $_POST['buka_shbn'];
			}
			// 初回読み込み判定を行い、選択年月を設定する
			if(isset($_POST['year_month']))  {
				// 初回以外の場合
				$select_month = $_POST['year_month'];
				$post = $_POST;
			}else{
				// 初回読み込みの場合
				$select_month = date("Ym");
			}
			// 表示モードをセッションへ格納
			if(isset($_POST['calendar_mix'])){
				// 混合型ボタン
				$calendar_mode = MY_CALENDAR_MIX;
				$this->session->set_userdata(array('calendar_mode' => MY_CALENDAR_MIX));
			}else if(isset($_POST['calendar_allplan'])){
				// 予定のみボタン
				$calendar_mode = MY_CALENDAR_ALLPLAN;
				$this->session->set_userdata(array('calendar_mode' => MY_CALENDAR_ALLPLAN));
			}else{
				// それ以外
				// セッションからカレンダーモード取得
				$calendar_mode = $this->session->userdata('calendar_mode');
				// セッションが切れている場合
				if(!$calendar_mode){
					$calendar_mode = MY_CALENDAR_MIX;
				}
			}
			
			// 当月・前後月の年月取得
			$year_month = $this->calendar_manager->_get_year_month($select_month,$post);
			$this->get_calendar($year_month,$calendar_mode);
			log_message('debug',"========== calendar select end ==========");
		}catch(Exception $e){
			$this->load->view('/parts/error/error.php',array('errcode' => 'CALENDER-SELECT'));
		}
	}
	
	/**
	 * カレンダーへ戻る処理
	 */
	function back_calendar($ym){
		try{
			log_message('debug', "========== " . __METHOD__ . " start ==========" );
			log_message('debug', " ----------- \$ym = " . $ym);
            // セッションからカレンダーモード取得
            $calendar_mode = $this->session->userdata('calendar_mode');
            // セッションが切れている場合
            if(!$calendar_mode){
                $calendar_mode = MY_CALENDAR_MIX;
            }

            log_message('debug', " ----------- \$calendar_mode = " . $calendar_mode);

			$this->get_calendar($ym, $calendar_mode);
            
            log_message('debug', "========== " . __METHOD__ . " end ==========" );
            
		}catch(Exception $e){
		}
	}

    /**
	 * 
	 */
	function move(){
		try{
			log_message('debug',"========== calendar move start ==========");
			log_message('debug',"========== calendar move end ==========");
		}catch(Exception $e){
		}
	}
	
	/**
	 * 
	 */
	function copy(){
		try{
			log_message('debug',"========== calendar copy start ==========");
			log_message('debug',"========== calendar copy end ==========");
		}catch(Exception $e){
		}
	}
	
}

/* End of file calendar.php */
/* Location: ./application/controllers/calendar.php */
