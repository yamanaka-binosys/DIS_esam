<?php

class Holiday_item extends MY_Controller {
    /*     * *
     * @param mode TRUE：表有り　FALSE：表無し　
     */

    function index($conf_name = MY_HOLIDAY_DATA) {
        try {
            log_message('debug', "========== Holiday_item index start ==========");
            $data = $this->init($conf_name); // ヘッダデータ等初期情報取得
            // モデル呼び出し
            $this->load->model('sgmtb150'); //
            // 初期表示
            $holiday_data = $this->sgmtb150->get_holiday_data($data["holiday_year"]);   //データ取得
            $data["list_tabel"] = $this->_get_page_list($holiday_data, $data['shbn']); //表示
            $data["select_year"] = date('Y');
            $data["max_year"] = $this->config->item('max_year'); // 最大年
            // Main表示情報取得
            $this->display($data); // 画面表示処理
            log_message('debug', "========== Holiday_item index end ==========");
        } catch (Exception $e) {
            // エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-index'));
            $this->error_view($e);
        }
    }

    /**
     * 登録,更新,削除(内部的には、all削除、登録)
     * @param
     */
    function register($conf_name = MY_HOLIDAY_DATA) {
        try {
            log_message('debug', "========== Holiday_item register start ==========");

            $select_year = (int) $this->input->post('holiday_year');   //データ取得
            $data = $this->init($conf_name); // ヘッダデータ等初期情報取得
            // モデル呼び出し
            $this->load->model('sgmtb150'); //
            $this->load->library('holiday_item_manager');
            $this->load->library('message_manager');

            $holiday_year = $data['holiday_year'];

            // データチェック
            $err_flag = FALSE;
            $i = 0;
            foreach($_POST['syukmon'] as $syukmon){
                if(!checkdate($syukmon, $_POST['syukday'][$i], $select_year)){
                    $data['errmsg'] = "設定月日が正しくありません";
                    $err_flag = TRUE;
                    break;
                }       
                $i++;
            }
            if (isset($_POST['syukmemo'])) {
                foreach($_POST['syukmemo'] as $syukmemo){
                    if($syukmemo == ""){
                        $data['errmsg'] = "メモが入力されていません";
                        $err_flag = TRUE;
                        break;
                    }       
                }
            }

            if(!$err_flag){
                // 登録前処理
                $regist_data = array();

                $i_year = $_POST['holiday_year'];

                // 登録データ生成
                $regist_data = $this->holiday_item_manager->insert_data_set($_POST);

                //登録処理
                if ($regist_data) {
                    $res = $this->holiday_item_manager->set_db_insert_data($regist_data, $i_year);
                    //$page_max = ceil(count($regist_data) / MY_HOLIDAY_MAX_VIEW);
                    //if($page > $page_max) $page = $page_max;  //表示ページ調整
                }
            }

            // 表示
            $holiday_data = $this->sgmtb150->get_holiday_data($holiday_year);   //データ取得
            $data["list_tabel"] = $this->_get_page_list($holiday_data); //表示
            $data["select_year"] = $select_year; // 最大年
            $data["max_year"] = $this->config->item('max_year'); // 最大年
            //log_message('debug',">>>>>>>>>>>>> " . $data["max_year"]);
            //      
            // Main表示情報取得
            $this->display($data); // 画面表示処理
            log_message('debug', "========== Holiday_item register end ==========");
        } catch (Exception $e) {
            // エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-index'));
            //$this->error_view($e);
        }
    }

    /**
     * 年変更
     * @param
     */
    function changeyear($conf_name = MY_HOLIDAY_DATA) {
        try {
            log_message('debug', "========== Holiday_item changeyear start ==========");

            //log_message('debug', ">>>>>>>>>>>>> " . $this->input->post('holiday_year'));
            // モデル呼び出し
            $this->load->model('sgmtb150'); //

            $data = $this->init($conf_name); // ヘッダデータ等初期情報取得
            // 表示
            $holiday_data = $this->sgmtb150->get_holiday_data((int) $this->input->post('holiday_year'));   //データ取得
            $data["list_tabel"] = $this->_get_page_list($holiday_data); //表示
            $data["select_year"] = (int) $this->input->post('holiday_year'); // 最大年
            $data["max_year"] = $this->config->item('max_year'); // 最大年

            log_message('debug', ">>>>>>>>>>>>> " . $data["max_year"]);
            //      
            // Main表示情報取得
            $this->display($data); // 画面表示処理
            log_message('debug', "========== Holiday_item changeyear end ==========");
        } catch (Exception $e) {
            // エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-index'));
            //$this->error_view($e);
        }
    }

    /**
     * 年行追加
     * @param unknown_type $conf_name
     */
    function ajax_add_row() {
        try {
            log_message('debug', "========== Holiday_item add_row start ==========");
            //$data = $this->init($conf_name); // ヘッダデータ等初期情報取得
            //挿入数
            //$add_num = (int)$this->input->post('add_num');
            $add_num = 1;                 // 常に１行だけ
            //if(!$add_num) $add_num = 1;
            //if($add_num >= 10) $add_num = 9;
            //追加位置
            $view_no = (int) $this->input->post('view_no');

            // 行追加
            $add_data = $this->_get_list_add_data($add_num, $view_no);

            // 追加表示
            header("Content-type: text/html; charset=utf-8");
            echo $add_data;
            log_message('debug', "========== Holiday_item add_row end ==========");
        } catch (Exception $e) {
            // エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-add_row'));
            //$this->error_view($e);
        }
    }

    /**
     * 共通で使用する初期化処理
     *
     * @access public
     * @param  nothing
     * @return array $data ヘッダ情報他
     */
    function init($conf_name) {
        try {
            log_message('debug', "========== Holiday_item init start ==========");
            //log_message('debug', "\$conf_name = $conf_name");
            // 初期化
            $common_data = $this->config->item($conf_name);
            $this->load->library('table_set');
            $this->load->library('common_manager');
            // セッション情報から社番を取得
            $data['shbn'] = $this->session->userdata('shbn');
            // ログイン者情報取得
            $heder_auth = $this->common_manager->get_auth_name($data['shbn']);
            $data['bu_name'] = $heder_auth['honbu_name'] . " " . $heder_auth['bu_name'];
            $data['ka_name'] = $heder_auth['ka_name'];
            $data['shinnm'] = $heder_auth['shinnm'];
            // セッション情報から管理者フラグを取得
            $data['admin_flg'] = $this->session->userdata('admin_flg');

            // 初期共通項目情報
            $data['title'] = $common_data['title']; // タイトルに表示する文字列
            $data['css'] = $common_data['css']; // 個別CSSのアドレス
            $data['image'] = $common_data['image']; // タイトルバナーのアドレス
            $data['errmsg'] = $common_data['errmsg']; // エラーメッセージ
            $data['btn_name'] = $common_data['btn_name']; // ボタン名
            $data['app_url'] = "http://" . $_SERVER["SERVER_NAME"] . $_SERVER["SCRIPT_NAME"]; // 実行アプリurl
            $data['form'] = "/holiday_item/" . $common_data['form'];  // フォームアクション
            $data['form_name'] = "holiday_item_form";  // フォーム名
            //選択年情報
            if ($this->input->post('holiday_year') !== FALSE) {
                $h_year = (int) $this->input->post('holiday_year');
            } else {
                $h_year = intval(date("Y"));
            }
            //if( $this->input->post('prev') ) $page = (int)$this->input->post('prev_page');
            //if( $this->input->post('next') ) $page = (int)$this->input->post('next_page');
            //if(!$page || $page < 0) $page=1;

            $data["holiday_year"] = $h_year;  //現在の年
            //$data["max_view"] = MY_HOLIDAY_MAX_VIEW;  //一ページの表示数
            //$data["start_no"] = MY_HOLIDAY_MAX_VIEW * ($page-1);  //データ番号(javascriptでの採番に使用)

            log_message('debug', "========== Holiday_item init end ==========");
            return $data;
        } catch (Exception $e) {
            // エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-init'));
            //$this->error_view($e);
        }
    }

    /**
     * 共通で使用する表示処理
     *
     * @access  public
     * @param   array $data 各種HTML作成時に必要な値
     * @return  nothing
     */
    function display($data) {
        try {
            log_message('debug', "========== Holiday_item display start ==========");
            // ヘッダ部生成 title css image errmsg を使用
            $data['header'] = $this->load->view(MY_VIEW_HEADER, $data, TRUE);
            // メインコンテンツ生成 contents を使用
            $data['main'] = $this->load->view(MY_VIEW_HOLIDAY_DATA, $data, TRUE);
            // メニュー部生成 表示しない場合はNULL admin_flg を使用
            $data['menu'] = $this->load->view(MY_VIEW_MENU, $data, TRUE);
            // フッダ部生成 必ず指定する事
            $data['footer'] = $this->load->view(MY_VIEW_FOOTER, '', TRUE);
            // 表示処理
            log_message('debug', "========== Holiday_item display end ==========");
            //$this->load->view(MY_VIEW_LAYOUT, $data);
            $this->load->view(MY_VIEW_HOLIDAY_DATA, $data);
        } catch (Exception $e) {
            // エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-display'));
//			$this->error_view($e);
        }
    }

    /**
     * 表の作成
     *
     * @access private
     * @param $project_data ARRAY
     * @param $mode TRUE=登録　FALSE=更新、削除
     * @return string $table_data HTML-STRING文字列
     */
    private function _get_page_list($holiday_data, $syaban = "") {
        try {
            log_message('debug', "========== Holiday_item _get_page_list start ==========");
            //log_message('debug', "*******" . $syaban . " =========");
            // 初期化
            $this->load->library('holiday_item_manager');
            $table_data = "";

            if (!$holiday_data) {
                //データなし
                $table_data = $this->holiday_item_manager->get_holiday_data_list();
            } else {
                //データあり
                foreach ($holiday_data as $key => $val) {
                    $table_data .= $this->holiday_item_manager->get_holiday_data_list($val['syukid'], $val['syukdate'], $val['syukmemo'], $val['createdate'], $val['syuksyaban']);
                }
            }

            log_message('debug', "========== Holiday_item _get_page_list end ==========");
            return $table_data;
        } catch (Exception $e) {
            //エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item-_get_page_list'));
        }
    }

    /**
     * リストの行追加
     *
     * @access private
     * @param $add_point int
     * @param $mode TRUE=登録　FALSE=更新、削除
     * @return string $table_data HTML-STRING文字列
     */
    private function _get_list_add_data($add_num, $view_no) {
        try {
            log_message('debug', "========== Holiday_item _get_list_add_data start ==========");

            // 初期化
            $this->load->library('holiday_item_manager');
            $table_data = "";

            //追加行取得
            for ($i = 0; $i < $add_num; $i++) {
                $table_data .= $this->holiday_item_manager->get_holiday_data_list();
            }

            log_message('debug', "========== Holiday_item _get_list_add_data end ==========");
            return $table_data;
        } catch (Exception $e) {
            //エラー処理
            $this->load->view('/parts/error/error.php', array('errcode' => 'Holiday_item_get_list_add_data'));
        }
    }

    /**
     * バリデーションチェック
     *
     * @access	public
     * @param	string $post POSTデータ
     * @return	boolean TRUE:成功 FALSE:エラー
     */
    function validate_check($post) {
        try {
            $v_result = FALSE;
            // ライブラリ読み込み
            $this->load->library('message_manager');
            $this->load->library('form_validation');
            // ルール読み込み
            $config = $this->config->item('validation_rules_project_item');
            // バリデーションルールセット
            //$this->form_validation->set_rules($config);
            // バリデーション結果チェック
            foreach ($post['add_line'] as $line) {
                if (is_numeric($line) OR $line == "") {
                    // 成功
                    $v_result = TRUE;
                    log_message('debug', "========== delete_user validation success ==========");
                } else {
                    // 失敗
                    $v_result = FALSE;
                    throw new Exception(ERROR_VALI);
                    log_message('debug', "========== delete_user validation error ==========");
                }
            }
            return $v_result;
        } catch (Exception $e) {
            // エラー処理
            log_message('debug', "message = " . $e->getMessage());
            $v_result = $this->error_view($e->getMessage(), "update", NULL);
            return $v_result;
        }
    }

    /**
     * エラー発生時処理
     * @access	public
     * @param	string $e：エラー番号 $type:画面種類 $item：メッセージ表示に使用するもの
     * @return	none
     */
    function error_view($e, $type = "update", $item = NULL) {
        log_message('debug', "exception : $e");
        $this->load->library('message_manager');
        // POSTデータ引継ぎ
        $data = $_POST;
        if ($e == ERROR_USER_SEARCH) {
            $data = $this->init($type);
        }

        return $this->message_manager->get_message($e, $item);
    }

}

/* End of file Plan.php */
/* Location: ./application/controllers/plan.php */
