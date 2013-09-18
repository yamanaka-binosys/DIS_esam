<?php
class Error extends CI_Controller {
	
	function session_timeout() {
		$this->load->view('/parts/error/session_timeout.php');
	}
	
}