<?php
class Header extends CI_Controller {

	function index($view_name){
		$this->load->helper('date');
		$this->load->model('sgmtb010');
		$this->load->library('common_manager');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		$res = $this->common_manager->create_user_name($shbn); // ログインユーザ情報取得
		$init_data = $this->common_manager->init($view_name);

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
		$header_data['errclass']=NULL;
		if(!$header_data['errmsg'] && isset($_GET["errmsg"])) $header_data['errmsg'] = $_GET["errmsg"];
		if(!$header_data['errclass'] && isset($_GET["errclass"]) && $_GET["errclass"] != "")  $header_data['errclass'] = $_GET["errclass"];

		$header_data['btn_name'] = $init_data['btn_name'];
		$header_data['js_name'] = $init_data['js_name'];

		$header_data['btn_confirmer'] = $init_data['btn_confirmer'];
		$header_data['confirmer_text'] = isset($init_data['confirmer_text']) ? $init_data['confirmer_text'] : '';
		$header_data['confirmer_js_name'] = isset($init_data['confirmer_js_name']) ? $init_data['confirmer_js_name'] : '';

		$this->load->view('parts/header/header',$header_data,FALSE);
	}

	function plan($view_name){
		$this->load->helper('date');
		$this->load->model('sgmtb010');
		$this->load->library('common_manager');
		$this->load->library('item_manager');
		$year_data = $this->config->item('s_todo_select_year');
		$month_data = $this->config->item('s_todo_select_month');
		$day_data = $this->config->item('s_todo_select_day');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		$res = $this->common_manager->create_user_name($shbn); // ログインユーザ情報取得
		$init_data = $this->common_manager->init($view_name);

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
		$header_data['js_name'] = $init_data['js_name'];
		
		// 年月日のプルダウン作成
		$header_data['head_year'] = $this->item_manager->set_variable_dropdown_string($year_data);
		$header_data['head_month'] = $this->item_manager->set_variable_dropdown_string($month_data);
		$header_data['head_day'] = $this->item_manager->set_variable_dropdown_string($day_data);
		
		$header_data['btn_confirmer'] = $init_data['btn_confirmer'];
		$header_data['confirmer_text'] = isset($init_data['confirmer_text']) ? $init_data['confirmer_text'] : '';
		$header_data['confirmer_js_name'] = isset($init_data['confirmer_js_name']) ? $init_data['confirmer_js_name'] : '';

		$this->load->view('parts/header/header_plan',$header_data,FALSE);
	}

	function result($view_name){
		$this->load->helper('date');
		$this->load->model('sgmtb010');
		$this->load->library('common_manager');
		$this->load->library('item_manager');
		$year_data = $this->config->item('s_todo_select_year');
		$month_data = $this->config->item('s_todo_select_month');
		$day_data = $this->config->item('s_todo_select_day');
		$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		$admin_flg = $this->sgmtb010->get_user_data($shbn); // 管理者フラグ取得
		$res = $this->common_manager->create_user_name($shbn); // ログインユーザ情報取得
		$init_data = $this->common_manager->init($view_name);

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
		$header_data['js_name'] = $init_data['js_name'];


		// 年月日のプルダウン作成
		$header_data['head_year'] = $this->item_manager->set_variable_dropdown_string($year_data);
		$header_data['head_month'] = $this->item_manager->set_variable_dropdown_string($month_data);
		$header_data['head_day'] = $this->item_manager->set_variable_dropdown_string($day_data);
		
		$header_data['btn_confirmer'] = $init_data['btn_confirmer'];
		$header_data['confirmer_text'] = isset($init_data['confirmer_text']) ? $init_data['confirmer_text'] : '';
		$header_data['confirmer_js_name'] = isset($init_data['confirmer_js_name']) ? $init_data['confirmer_js_name'] : '';

		$this->load->view('parts/header/header_result',$header_data,FALSE);
	}
}
?>
