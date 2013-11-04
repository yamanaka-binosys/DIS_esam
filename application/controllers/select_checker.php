<?php

class Select_checker extends MY_Controller {
	/**
	 * 確認者選択画面
	 *
	 * @access private
	 * @param  none
	 * @return none
	 */
	function index($r_shbn=NULL,$check=NULL,$type=NULL)
	{
		try
		{
			// 初期化
			$data = $this->init();
			$data['kakunin'] = "";
			$data['kakunin_name']="";
			
			if($r_shbn){
				$this->load->model(array('sgmtb010', 'srwtb010'));
				$kakunin = "";
				$cnt = 0;
				$shbn = $this->session->userdata('shbn');
				$confirmer_no = $this->sgmtb010->get_unit_cho_shbn($shbn);
				$this->load->library('common_manager');
				foreach ($r_shbn as $key => $value) {
					if(!isset($value)){
						break;
					}
					if($cnt != 0){
						$kakunin .= " ";
					}
					$kakunin .= $value;
					$cnt++;
				}
				$data['kakunin'] = $kakunin;
				if(strpos($data['kakunin']," ")){
					$data['kakninshnm'] = "複数選択";
				}else{
					if($data['kakunin'] === $confirmer_no){
						$data['kakninshnm'] = "";
					}else{
						$data['kakninshnm'] = $this->common_manager->create_name($data['kakunin']);
						
					}
				}
				
				$data['check']=$check;
			
			}else{
				$data['kakunin'] = "";
				$data['kakninshnm'] = "";
				$data['check']=$check;
			}
			
			
			// 画面表示
			$this->display($data);
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_checker-index'));
			//$this->error_view($e);
		}
	}

	
	/**
	 * 確認者選択(初期設定)
	 *
	 * @access private
	 * @param  none
	 * @return array
	 */
	function init()
	{
		try
		{
			$this->load->library('table_manager_another');
			$common_data = $this->config->item('s_select_checker');
			$this->load->model('srwtb020');
			$this->load->library('common_manager');
			$shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
			//$data["k_shbn"] = $this->session->userdata('k_shbn');
			
			
			log_message('debug',"---------------------------------------------------------");
			log_message('debug',"---------------------------------------------------------");
	//		log_message('debug',"kedbn=".$data["k_shbn"]);
			
			log_message('debug',"---------------------------------------------------------");
			log_message('debug',"---------------------------------------------------------");
			$checker_shbn = $this->session->userdata('checker_search_shbn'); // セッション情報から確認者の社番を取得
			$busyo_shbn = $this->session->userdata('busyo_search_shbn'); // セッション情報から部署コードを取得
			$ka_shbn = $this->session->userdata('ka_search_shbn'); // セッション情報から部署コードを取得
			$group_shbn = $this->session->userdata('group_search_shbn'); // セッション情報からグループコードを取得
			
			if(isset($_POST['checker']) && $_POST['checker']!=""){
				if($_POST['checker']=="conf"){
					$checker_shbn = $_POST['checker_list'];
				}else if($_POST['checker']=="ka"){
					$ka_shbn = $_POST['busyo_list'];
				}else if($_POST['checker']=="unit"){
					$busyo_shbn =  $_POST['busyo_list'];
				}else if($_POST['checker']=="group"){
					$group_shbn =  $_POST['group_list'];
				}
			}
			
			// ログイン者情報取得
			$heder_auth = $this->common_manager->get_auth_name($shbn);
			$data['bu_name'] = $heder_auth['honbu_name']." ".$heder_auth['bu_name'];
			$data['ka_name'] = $heder_auth['ka_name'];
			$data['shinnm'] = $heder_auth['shinnm'];
			
			$data["checker_shbn"] = $this->session->userdata('kakninshbn'); // セッション情報から確認者の社番を取得
			$data["busyo_shbn"] = $this->session->userdata('busyo_search_shbn'); // セッション情報から部署コードを取得
			$data["ka_shbn"] = $this->session->userdata('ka_search_shbn'); // セッション情報から部署コードを取得
			$data["group_shbn"] = $this->session->userdata('group_search_shbn'); // セッション情報からグループコードを取得
		
			$data["checker_edbn"] = $this->session->userdata('checker_ednm'); // セッション情報から確認者の社番を取得
			
			
			$edbn = NULL;
			$c_result = FALSE;
			
			// 確認者登録
			if($checker_shbn)
			{
				$edbn = $this->session->userdata('edbn');
				
				//var_dump($edbn);
				if($edbn)
				{
					log_message('debug',"---------------------------------------------------------");
					log_message('debug',"edbn=".$edbn);
					// 更新処理
					$c_result = $this->srwtb020->update_checker_data($shbn,$edbn,$checker_shbn);
				}else{
					// 新規登録処理
					// 枝番取得
					$edbn = $this->srwtb020->get_edbn_data($shbn);
					$c_result = $this->srwtb020->insert_checker_data($shbn,$edbn,$checker_shbn);
				}//
				if($c_result)
				{
					$checker_shbn = NULL;
					$this->session->unset_userdata('checker_search_shbn');
					$this->session->unset_userdata('edbn');
				}else{
					throw new Exception("Error Processing Request", ERROR_SYSTEM);
				}
			}else if($busyo_shbn){
				// 部署登録
				$code['honbu'] = substr($busyo_shbn,0,MY_CODE_LENGTH);
				$code['bu'] = substr($busyo_shbn,MY_CODE_LENGTH,MY_CODE_LENGTH);
				$code['ka'] = substr($busyo_shbn,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
				
				$edbn = $this->session->userdata('edbn');
				if($edbn)
				{
					// 更新処理
					$b_result = $this->srwtb020->update_busyo_data($shbn,$edbn,$code);
				}else{
					// 枝番取得
					$edbn = $this->srwtb020->get_edbn_data($shbn);
					$b_result = $this->srwtb020->insert_busyo_data($shbn,$edbn,$code);
				}
				if($b_result)
				{
					$busyo_shbn = NULL;
					$this->session->unset_userdata('busyo_search_shbn');
					$this->session->unset_userdata('edbn');
				}else{
					throw new Exception("Error Processing Request", ERROR_SYSTEM);
				}
				
			}else if($ka_shbn){
				// 課登録
				$code['honbu'] = substr($ka_shbn,0,MY_CODE_LENGTH);
				$code['bu'] = substr($ka_shbn,MY_CODE_LENGTH,MY_CODE_LENGTH);
				$code['ka'] = substr($ka_shbn,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
				
				$edbn = $this->session->userdata('edbn');
				if($edbn)
				{
					// 更新処理
					$b_result = $this->srwtb020->update_ka_data($shbn,$edbn,$code);
				}else{
					// 枝番取得
					$edbn = $this->srwtb020->get_edbn_data($shbn);
					$b_result = $this->srwtb020->insert_ka_data($shbn,$edbn,$code);
				}
				if($b_result)
				{
					$ka_shbn = NULL;
					$this->session->unset_userdata('ka_search_shbn');
					$this->session->unset_userdata('edbn');
				}else{
					throw new Exception("Error Processing Request", ERROR_SYSTEM);
				}
			
			
			}else if($group_shbn){
				// グループ登録
				$edbn = $this->session->userdata('edbn');
				if($edbn)
				{
					// 更新処理
					$g_result = $this->srwtb020->update_group_data($shbn,$edbn,$group_shbn);
				}else{
					// 枝番取得
					$edbn = $this->srwtb020->get_edbn_data($shbn);
					$g_result = $this->srwtb020->insert_group_data($shbn,$edbn,$group_shbn);
				}
				if($g_result)
				{
					$this->session->unset_userdata('group_search_shbn');
					$this->session->unset_userdata('edbn');
				}else{
					throw new Exception("Error Processing Request", ERROR_SYSTEM);
				}
			}
			log_message('debug',"shbn=".$shbn);
			
			
			// 結果判定
			$data['c_checker_table'] = $this->table_manager_another->checker_set_table($shbn,$data["checker_edbn"]); // 確認者テーブル
			$data['c_busyo_table'] = $this->table_manager_another->busyo_set_table($shbn,$data["checker_edbn"]); // 部署テーブル
			$data['c_ka_table'] = $this->table_manager_another->ka_set_table($shbn,$data["checker_edbn"]); // 部署テーブル
			$data['c_group_table'] = $this->table_manager_another->group_set_table($shbn,$data["checker_edbn"]); // グループテーブル
			
			$this->session->unset_userdata('checker_ednm');
			
			$data['admin_flg'] = $this->session->userdata('admin_flg'); // 管理者フラグ取得
			// 初期共通項目情報
			$data['title']     = $common_data['title'];    // タイトルに表示する文字列
			$data['css']       = $common_data['css'];      // 個別CSSのアドレス
			$data['image']     = $common_data['image'];    // タイトルバナーのアドレス
			$data['errmsg']    = $common_data['errmsg'];   // エラーメッセージ
			$data['btn_name']  = $common_data['btn_name']; // ボタン名
			$data['form']      = $common_data['form'];     // formタグのあり・なし
			// ユーザー管理画面独自項目
			
			return $data;
		}catch(Exception $e){
			// エラー表示
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_checker-init'));
			//$this->error_view($e);
		}
	}
	
	/**
	 * 確認者選択(表示)
	 *
	 * @access private
	 * @param  array
	 * @return none
	 */
	function display($data)
	{
		try
		{
			
			log_message('debug',"===================== select_checker display start =========================");
			// 表示処理
			$checker_shbn = $this->session->userdata('checker_search_shbn');
			
			$this->load->view(MY_VIEW_SELECT_CHECKER, $data);
			log_message('debug',"===================== select_checker display end =========================");
			
		}catch(Exception $e){
			// エラー処理
			$this->load->view('/parts/error/error.php',array('errcode' => 'select_checker-display'));
			//$this->error_view($e);
		}
	}

	/**
	 * 確認者選択(戻る)
	 *
	 * @access private
	 * @param  array post POSTデータ
	 * @return none
	 */
	function select_type(){
				
		log_message('debug',"============= " . __METHOD__ . " start ================");
        log_message('debug', "********** \$_POST['kshbn'] = " . serialize($_POST['kshbn']));

        $this->load->model('sgmtb010');
		$s_shbn = "";
		$s_edbn = "";
		$r_shbn = "";
		if(isset($_POST['kshbn'])){
			$s_shbn = $_POST['kshbn'];
			
			$s_select = $s_shbn;
			$s_shbn = NULL;
			foreach($s_select as $key => $value){

                $select_check = explode('/',$value);
                $s_edbn[] = $select_check[1];

                $s_shbn[] = $select_check[0];
                log_message('debug', "********** \$s_shbn[] = " . serialize($s_shbn));
			}
		}
		
		
		
		// 部署コードから社番検索
		if(isset($_POST['bucd'])){
		
		
			$busyo_code = $_POST['bucd'];
			
			foreach($busyo_code as $key => $value){
		
			$select_check = explode('/',$value);
			$s_edbn[] = $select_check[1];
			//$s_shbn[] = $select_check[0];
			$busyo_code[$key] = $select_check[0];
			}
			
			foreach($busyo_code as $key => $bucd){
				if(strlen($bucd) > (MY_CODE_LENGTH * 2)){
					$bunkatu[$key]['ka']['honbucd'] = substr($bucd,0,MY_CODE_LENGTH);
					$bunkatu[$key]['ka']['bucd'] = substr($bucd,MY_CODE_LENGTH,MY_CODE_LENGTH);
					$bunkatu[$key]['ka']['kacd'] = substr($bucd,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
				}else if(strlen($bucd) > MY_CODE_LENGTH){
					$bunkatu[$key]['bu']['honbucd'] = substr($bucd,0,MY_CODE_LENGTH);
					$bunkatu[$key]['bu']['bucd'] = substr($bucd,MY_CODE_LENGTH,MY_CODE_LENGTH);
					$bunkatu[$key]['bu']['kacd'] = MY_DB_BU_ESC;
				}else{
					$bunkatu[$key]['honbu']['honbucd'] = substr($bucd,0,MY_CODE_LENGTH);
					$bunkatu[$key]['honbu']['bucd'] = MY_DB_BU_ESC;
					$bunkatu[$key]['honbu']['kacd'] = MY_DB_BU_ESC;
				}
			}	
			
			
			for($i = 0; $i < count($bunkatu); $i++){
				$busyo_shbn[] = $this->sgmtb010->get_search_shbn_data($bunkatu[$i]);
			}
			
			
			foreach($busyo_shbn as $b_data){
				foreach($b_data as $shbn){
					$s_shbn[] = $shbn['shbn'];
				}
			}
		}
		
		// 課コードから社番検索
		if(isset($_POST['kacd'])){
		
		
			$unit_code = $_POST['kacd'];
			
			foreach($unit_code as $key => $value){
		
                $select_check = explode('/',$value);
                $s_edbn[] = $select_check[1];
                //$s_shbn[] = $select_check[0];
                $unit_code[$key] = $select_check[0];
			}
			
			foreach($unit_code as $key => $kacd){
				if(strlen($kacd) > (MY_CODE_LENGTH * 2)){
					$bunkatu[$key]['ka']['honbucd'] = substr($kacd,0,MY_CODE_LENGTH);
					$bunkatu[$key]['ka']['bucd'] = substr($kacd,MY_CODE_LENGTH,MY_CODE_LENGTH);
					$bunkatu[$key]['ka']['kacd'] = substr($kacd,(MY_CODE_LENGTH * 2),MY_CODE_LENGTH);
				}
			}	
			
			
			for($i = 0; $i < count($bunkatu); $i++){
			
				$unit_shbn[] = $this->sgmtb010->get_unit_search_shbn_data($bunkatu[$i]);
			}
			
			foreach($unit_shbn as $b_data){
				foreach($b_data as $shbn){
					$s_shbn[] = $shbn['shbn'];
				}
			}

		}
		
		
		// グループコードから社番検索
		if(isset($_POST['grpcd'])){
			$grp_code = $_POST['grpcd'];
			foreach($grp_code as $key => $value){
                $select_check = explode('/',$value);
                $s_edbn[] = $select_check[1];
                $grp_code[] = $select_check[0];
			}
		
			foreach($grp_code as $key => $grp){
			
				$grp_shbn[$key] = $this->sgmtb010->get_grp_search_shbn_data($grp);
				if(!$grp_shbn[$key]){
					unset($grp_shbn[$key]);
				}
			}
			foreach($grp_shbn as $g_data){
				foreach($g_data as $gshbn){
					$s_shbn[] = $gshbn['shbn'];
				}
			}
		}
		
		
		$login_shbn = $this->session->userdata('shbn'); // セッション情報から社番を取得
		
		$this->load->model('srwtb020');
		$this->srwtb020->update_clear_checkar_data($login_shbn);
		
        log_message('debug', "********** \$s_edbn = " . serialize($s_edbn));
        
		if($s_edbn){
			foreach($s_edbn as $key => $value){
				$save_edbn = sprintf("%02d",$value);
				
				$this->srwtb020->update_checkar_data($save_edbn,$login_shbn);
                log_message('debug', "********** \$save_edbn = " . $save_edbn . " \$login_shbn = " . $login_shbn);
			
			}
		}
       
		if($s_shbn){
			// 重複チェック
			$r_shbn = array_unique($s_shbn);
			foreach($r_shbn as $key => $value){
				$this->srwtb020->update_set_flg_data($login_shbn, $value);
                log_message('debug', "********** \$value = " . $value . " \$login_shbn = " . $login_shbn);
			
			}
		}
		
		
		// 確認者社番をセッションに保存
		$session_data = array('checker_ednm' => $s_edbn);
		$this->session->set_userdata($session_data);
		$session_data = array('checker_shbn' => $r_shbn);
		$this->session->set_userdata($session_data);
		
		$from_url = $this->session->userdata('from_url'); // セッション情報からグループコードを取得
		
		log_message('debug',"\$from_url = $from_url");
		log_message('debug',"--------------------------------------------------");
		log_message('debug',"\$r_shbn = " . serialize($r_shbn));
		log_message('debug',"--------------------------------------------------");
		// 確認者選択画面へ遷移
		
		$this->index($r_shbn,'1');
		//header("Location: ".$from_url);
		return;
	}
	
	/**
	 * システムエラー発生時処理
	 */
	function error_view($e)
	{
		$this->load->library('error_set');
		$data = $this->init();
		$data['errmsg'] = $this->error_set->get_message(ERROR_SYSTEM,NULL);
		$this->display($data);
		
	}
	
	
	
}
/* End of file login.php */
/* Location: ./application/controllers/login.php */
