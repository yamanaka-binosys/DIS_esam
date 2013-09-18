<?php
class System extends MY_Controller {

	function index(){
		log_message('debug',"----- Start system logic -----");
		$this->load->view(MY_VIEW_SYSTEM,"",FALSE);
		log_message('debug',"----- End system logic -----");
	}

	function header(){
		$this->load->helper('date');
		$this->load->model('sgmtb010');
		$this->load->library('common_manager');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		$res = $this->common_manager->create_user_name($shbn); // ログインユーザ情報取得
		// 管理者フラグよりトップページ情報取得
		if($admin_flg === "002" OR $admin_flg === "003"){
			$init_data = $this->common_manager->init(SHOW_TOP_ADMIN);
		}else{
			$init_data = $this->common_manager->init(SHOW_TOP_GENE);
		}
//		if($admin_flg === "001"){
//			$init_data = $this->common_manager->init(SHOW_TOP_GENE);
//		}else{
//			$init_data = $this->common_manager->init(SHOW_TOP_ADMIN);
//		}

		$time       = time(); // 日付取得
		$datestring = "%Y/%m/%d "; // 日付フォーマット
		$weekday    = $this->config->item('c_day_week_ja'); // 日本語曜日フォーマット

		$header_data['bu_name'] = $res['bu_name'];
		$header_data['ka_name'] = $res['ka_name'];
		$header_data['shinnm'] = $res['shinnm'];

		$header_data['gif_name'] = $init_data['gif_name'];
		$header_data['day'] = mdate($datestring, $time);
		$header_data['week_day'] = $weekday[date("w")];

		$header_data['errmsg'] = $init_data['errmsg'];

		$header_data['btn_name'] = $init_data['btn_name'];
		$header_data['btn_confirmer'] = $init_data['btn_confirmer'];
		$header_data['confirmer_text'] = isset($init_data['confirmer_text']) ? $init_data['confirmer_text'] : '';
		$header_data['confirmer_js_name'] = isset($init_data['confirmer_js_name']) ? $init_data['confirmer_js_name'] : '';

		$this->load->view('parts/header/header',$header_data,FALSE);
	}

	function menu(){
		$this->load->model('sgmtb010');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		if($admin_flg === "003"){
			$this->load->view('parts/menu/menu_admin');
		}else{
			$this->load->view('parts/menu/menu_general');
		}
	}

	function content(){
		$this->load->model('sgmtb010');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		log_message('debug',"system content admin_flg");
		log_message('debug',"\$admin_flg = $admin_flg");
		if($admin_flg === "002" OR $admin_flg === "003"){
			log_message('debug',"view admin");
			$this->load->view('parts/content/top_admin');
		}else{
			log_message('debug',"view general");
			$this->load->view('parts/content/top_general');
		}
	}

}
?>
