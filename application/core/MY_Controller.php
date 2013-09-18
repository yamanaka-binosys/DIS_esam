<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	protected $start_time="";
	
	public function __construct() {
		parent::__construct();
		if (!$this->session->userdata('shbn')) {
			redirect($this->config->item('base_url') . 'index.php/error/session_timeout');
		}
	}
	
	public function _remap($method, $args) {
		$this->before();
		
		call_user_func_array(array($this, $method), $args);
		
		$this->after();
	}
	
	protected function before() { 
		log_message('debug', '============ before ===========');
		log_message('INFO', '============ trans_begin ===========');
		$this->start_time = microtime(true);
		$this->db->trans_begin();
		return; 
	}
	
	protected function after() { 
		log_message('debug', '============ after ===========');
		$this->db->trans_complete();
		log_message('INFO', '============ trans_complete ===========');
		$end_time=microtime(true);
		$time = $end_time - $this->start_time;
		log_message('INFO', 'shbn'.$shbn= $this->session->userdata('shbn').'=== time'.$time.'秒==='.current_url());
		
		return; 
	}
}