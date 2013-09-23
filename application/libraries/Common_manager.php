<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_manager {

	/**
	 * 画面ごとの初期化
	 *
	 * @access public
	 * @param  string $view_name 表示中の画面名
	 * @return array  $init_data 初期設定値
	 */
 	public function init($view_name){
 		$init_data = NULL;
		// 初期化
		$CI =& get_instance();
		$CI->load->library(array('session','result_manager'));
		$CI->load->model('sgmtb010');
		//////////////// セッション関連 ////////////////////////////
		$init_data['shbn'] = $CI->session->userdata('shbn'); // セッション情報から社番を取得
		// セッションチェック
		if(is_null($init_data['shbn']))
		{
			// セッション切れの場合
			$init_data['session_check'] = TRUE;
		}else{
			// ユーザー（権限）取得
			$init_data['admin_flg'] = $CI->sgmtb010->get_user_data($init_data['shbn']);
			// セッションに管理者フラグ保存
			$session_data = array('admin_flg' => $init_data['admin_flg']);
			$CI->session->set_userdata($session_data);
		}
		////////////////////////////////////////////////////////
		// 画面ごとの初期設定
		switch ($view_name) {
			// トップ(管理者)
			case SHOW_TOP_ADMIN:
				// 初期共通項目情報
				$init_data['title']         = "トップ(管理者)";    // タイトルに表示する文字列
				$init_data['css']           = 'css/top.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;             // エラーメッセージ
				$init_data['btn_name']      = NULL;             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/top.gif'; //
				$init_data['js_name']       = 'submit_top_admin()';           // JavaScriptのファンクション名
				break;
			// トップ（担当者）
			case SHOW_TOP_GENE:
				// 初期共通項目情報
				$init_data['title']         = "トップ(担当者)";    // タイトルに表示する文字列
				$init_data['css']           = 'css/top.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;             // エラーメッセージ
				$init_data['btn_name']      = NULL;             // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/top.gif'; //
				$init_data['js_name']       = 'submit_top_general()';           // JavaScriptのファンクション名
				break;
			// カレンダー
			case SHOW_CALENDAR:
				// 初期共通項目情報
				$init_data['title']         = "カレンダー";             // タイトルに表示する文字列
				$init_data['css']           = 'css/calendar.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;                  // エラーメッセージ
				$init_data['btn_name']      = NULL;                  // ボタン表示名
				$init_data['btn_confirmer'] = NULL;                  // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/calendar.gif'; //
				$init_data['js_name']       = 'submit_calendar()';           // JavaScriptのファンクション名
				break;
			// スケジュール（登録）
			case SHOW_PLAN_A:
				// 初期共通項目情報
				$init_data['title']         = "スケジュール（登録）";  // タイトルに表示する文字列
				$init_data['css']           = 'css/plan.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;              // エラーメッセージ
				$init_data['btn_name']      = "登録";            // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = "確認者";          // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/plan.gif'; //
				$init_data['js_name']       = 'aaa()';           // JavaScriptのファンクション名
				break;
			// スケジュール（削除）
			case SHOW_PLAN_D:
				// 初期共通項目情報
				$init_data['title']       = "スケジュール（削除）"; // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "削除";           // ボタン表示名
				break;
			// スケジュール（確認）
			case SHOW_PLAN_C:
				// 初期共通項目情報
				$init_data['title']       = "スケジュール（確認）"; // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "閉じる";          // ボタン表示名
				break;
			// スケジュール
			case SHOW_PLAN_VIEW:
				// 初期共通項目情報
				$init_data['title']         = "スケジュール";  // タイトルに表示する文字列
				$init_data['css']           = 'css/plan.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;              // エラーメッセージ
				$init_data['btn_name']      = NULL;            // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;          // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/plan.gif'; //
				$init_data['js_name']       = 'aaa()';           // JavaScriptのファンクション名
				break;
			// 定期スケジュール（登録）
			case SHOW_REGULAR_PLAN:
				// 初期共通項目情報
				$init_data['title']         = "スケジュール";  // タイトルに表示する文字列
				$init_data['css']           = 'css/plan.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;              // エラーメッセージ
				$init_data['btn_name']      = "登録";            // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             //
				$init_data['confirmer_text']    = NULL;      //
				$init_data['gif_name']          = 'images/regular_plan.gif'; //
				$init_data['js_name']           = 'submit_regular_plan()';           // JavaScriptのファンクション名
				$init_data['confirmer_js_name'] = 'submit_plan_select_checker(\''. $CI->config->item('base_url') .'\')';  // JavaScriptのファンクション名
				break;
			// 日報実績（登録）
			case SHOW_RESULT_A:
				// 初期共通項目情報
				$init_data['title']       = "日報実績（登録）";  // タイトルに表示する文字列
				$init_data['css']         = 'css/result.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "登録";           // ボタン表示名
				$init_data['btn_confirmer'] = "確認者";          // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/result.gif'; //
				$init_data['js_name']       = 'aaa()';           // JavaScriptのファンクション名
				break;
			// 日報実績（削除）
			case SHOW_RESULT_D:
				// 初期共通項目情報
				$init_data['title']       = "日報実績（削除）";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "削除";           // ボタン表示名
				break;
			// 日報実績（確認）
			case SHOW_RESULT_C:
				// 初期共通項目情報
				$init_data['title']       = "日報実績（確認）";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "閉じる";           // ボタン表示名
				break;
			// 日報実績（ユニット長確認）
			case SHOW_RESULT_VIEW_ADMIN:
				// 初期共通項目情報
				$init_data['title']         = "日報実績";     // タイトルに表示する文字列
				$init_data['css']           = 'css/result.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;                // エラーメッセージ
				$init_data['btn_name']      = "登録";              // ボタン表示名
				$init_data['btn_confirmer'] = NULL;                // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/result.gif'; // 
				$init_data['js_name']       = 'submit_result_view()';             // JavaScriptのファンクション名
				break;
			// 日報実績（受取確認）
			case SHOW_RESULT_VIEW_GENERAL:
				// 初期共通項目情報
				$init_data['title']         = "日報実績";     // タイトルに表示する文字列
				$init_data['css']           = 'css/result.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;                // エラーメッセージ
				$init_data['btn_name']      = NULL;                // ボタン表示名
				$init_data['btn_confirmer'] = NULL;                // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/result.gif'; // 
				$init_data['js_name']       = 'submit_result_view()';             // JavaScriptのファンクション名
				break;
			// 定期設定
			case SHOW_SCHEDUL:
				// 初期共通項目情報
				$init_data['title']       = "定期設定";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "登録";           // ボタン表示名
				break;
			// 商談履歴
			case SHOW_S_RIREKI:
				// 初期共通項目情報
				$init_data['title']       = "商談履歴";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = NULL;           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/s_rireki.gif'; //
				$init_data['js_name']       = ''; //
				break;
			// TODO（登録）
			case SHOW_TODO_A:
				// 初期共通項目情報
				$init_data['title']         = "TODO（登録）";  // タイトルに表示する文字列
				$init_data['css']           = 'css/todo';             // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;             // エラーメッセージ
				$init_data['btn_name']      = "登録";           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/todo.gif'; //
				$init_data['js_name']       = 'submit_todo_add()'; // JavaScriptのファンクション名
				$init_data['confirmer_js_name'] = NULL;  // 確認者選択ボタン（確認者・認証者）
				$init_data['view_name']     = SHOW_TODO_A; //　ヘッダー変更のために設定
				break;
			// TODO（変更）
			case SHOW_TODO_U:
				// 初期共通項目情報
				$init_data['title']       = "TODO（変更）";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "変更";           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/todo.gif'; //
				$init_data['js_name']       = 'submit_todo_update()'; // JavaScriptのファンクション名
				$init_data['view_name']     = SHOW_TODO_U; //　ヘッダー変更のために設定
				break;
			// TODO（TOPからの確認）
			case SHOW_TODO:
				// 初期共通項目情報
				$init_data['title']       = "TODO（確認）";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = NULL;           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/todo.gif'; //
				$init_data['js_name']       = ''; // JavaScriptのファンクション名
				break;
			// 情報メモ（登録）
			case SHOW_MEMO_A:
				// 初期共通項目情報
				$init_data['title']       = "メモ";  // タイトルに表示する文字列
				$init_data['css']         = 'css/memo.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = "登録";             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] =NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_memo.gif'; //
				$init_data['js_name']      = 'submit_memo()'; // JavaScriptのファンクション名
				$init_data['confirmer_js_name'] = 'show_select_checker(\''. $CI->config->item('base_url') .'\')';             // 確認者選択ボタン（確認者・認証者）
				break;
			// 情報メモ（検索）
			case SHOW_MEMO_S:
				// 初期共通項目情報
				$init_data['title']       = "メモ";  // タイトルに表示する文字列
				$init_data['css']         = 'css/memo.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = NULL;             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] =NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_memo.gif'; //
				$init_data['js_name']      = 'submit_item_visibility()'; // JavaScriptのファンクション名
				break;
			// 情報メモ（更新用検索）
			case SHOW_MEMO_SU:
				// 初期共通項目情報
				$init_data['title']       = "メモ";  // タイトルに表示する文字列
				$init_data['css']         = 'css/memo.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = NULL;             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] =NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_memo.gif'; //
				$init_data['js_name']      = 'submit_memo()'; // JavaScriptのファンクション名
				break;
			// 情報メモ（削除用検索）
			case SHOW_MEMO_SD:
				// 初期共通項目情報
				$init_data['title']       = "メモ";  // タイトルに表示する文字列
				$init_data['css']         = 'css/memo.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = NULL;             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_memo.gif'; //
				$init_data['js_name']      = 'submit_item_visibility()'; // JavaScriptのファンクション名
				break;
			// 情報メモ（更新用検索）
			case SHOW_MEMO_U:
				// 初期共通項目情報
				$init_data['title']       = "メモ";  // タイトルに表示する文字列
				$init_data['css']         = 'css/memo.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = "変更";             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] =NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_memo.gif'; //
				$init_data['js_name']      = 'submit_memo()'; // JavaScriptのファンクション名
				break;
			// 情報メモ（削除用検索）
			case SHOW_MEMO_D:
				// 初期共通項目情報
				$init_data['title']       = "メモ";  // タイトルに表示する文字列
				$init_data['css']         = 'css/memo.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = "削除";             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_memo.gif'; //
				$init_data['js_name']      = 'submit_item_visibility()'; // JavaScriptのファンクション名
				break;
			// メッセージ
			case SHOW_MESSAGE:
			  // 初期共通項目情報
			  $init_data['title']         = "メッセージ";  // タイトルに表示する文字列
			  $init_data['css']           = 'css/message.css';             // 個別CSSのアドレス
			  $init_data['errmsg']        = NULL;             // エラーメッセージ
			  $init_data['btn_name']      = "登録";             // ボタン表示名（登録・変更・削除）
			  $init_data['btn_confirmer'] = "選択";             //
			  $init_data['confirmer_text'] = "送付先";      //
			  $init_data['gif_name']      = 'images/message.gif'; //
			  $init_data['js_name']       = 'submit_message()'; // JavaScriptのファンクション名
			  //$init_data['$confirmer_js_name'] = ;
			  $init_data['confirmer_js_name'] = 'submit_message_select_checker(\''. $CI->config->item('base_url') .'\')';  // 確認者選択ボタン（確認者・認証者）
			  break;
			// 企画獲得情報
			case SHOW_PROJECT_P:
				// 初期共通項目情報
				$init_data['title']       = "企画獲得情報";  // タイトルに表示する文字列
				$init_data['css']         = 'css/project_possession.css'; // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "登録";           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;           // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/project_possession.gif'; //
				$init_data['js_name']       = 'submit_project_possession()'; // JavaScriptのファンクション名
				break;
			// 企画獲得情報(表示)
			case SHOW_PROJECT_V:
				// 初期共通項目情報
				$init_data['title']       = "企画獲得情報(表示)";  // タイトルに表示する文字列
				$init_data['css']         = 'css/project_possession.css'; // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;               // エラーメッセージ
				$init_data['btn_name']    = NULL; // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/project_possession.gif'; //
				$init_data['js_name']       = NULL; // JavaScriptのファンクション名
				break;
			// 情報出力
			case SHOW_DATA_OUTPUT:
				// 初期共通項目情報
				$init_data['title']       = "情報出力";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = NULL;           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/data_output.gif'; //
				$init_data['js_name']       = NULL; // JavaScriptのファンクション名
				break;
			// ユーザー管理
			case SHOW_USER:
				// 初期共通項目情報
				$init_data['title']       = "ユーザー管理";  // タイトルに表示する文字列
				$init_data['css']         = NULL;             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "更新";           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']     = 'images/admin_user.gif'; //
				$init_data['js_name']      = 'submit_memo()'; // JavaScriptのファンクション名
				break;
			// 企画情報アイテム
			case SHOW_PROJECT_ITEM:
				// 初期共通項目情報
				$init_data['title']       = "企画情報アイテム";  		// タイトルに表示する文字列
				$init_data['css']         = 'css/project_item.css'; // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "更新";           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/project_item.gif'; //
				$init_data['js_name']       = 'submit_project_item()'; // JavaScriptのファンクション名
				break;
			// 区分情報
			case SHOW_DIVISION:
				// 初期共通項目情報
				$init_data['title']       = "区分情報";  // タイトルに表示する文字列
				$init_data['css']         = 'css/division.css';             // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']      = '登録';             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/kbn_set.gif'; //
				$init_data['js_name']       = 'submit_division()'; // JavaScriptのファンクション名
				break;
			case SHOW_DIVISION_SU:
			  // 初期共通項目情報
			  $init_data['title']       = "区分情報";  // タイトルに表示する文字列
			  $init_data['css']         = 'css/division.css';             // 個別CSSのアドレス
			  $init_data['errmsg']      = NULL;             // エラーメッセージ
			  $init_data['btn_name']      = '更新';             // ボタン表示名（登録・変更・削除）
			  $init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
			  $init_data['gif_name']      = 'images/kbn_set.gif'; //
			  $init_data['js_name']       = 'submit_division()'; // JavaScriptのファンクション名
			  break;
		  case SHOW_DIVISION_SD:
			    // 初期共通項目情報
			    $init_data['title']       = "区分情報";          // タイトルに表示する文字列
			    $init_data['css']         = 'css/division.css';  // 個別CSSのアドレス
			    $init_data['errmsg']      = NULL;                // エラーメッセージ
			    $init_data['btn_name']      = '削除';            // ボタン表示名（登録・変更・削除）
			    $init_data['btn_confirmer'] = NULL;              // 確認者選択ボタン（確認者・認証者）
			    $init_data['gif_name']      = 'images/kbn_set.gif'; //
			    $init_data['js_name']       = 'submit_division()'; // JavaScriptのファンクション名
			    break;
				// 画面生成
			case SHOW_ITEM_VISI:
				// 初期共通項目情報
				$init_data['title']         = "画面生成";    // タイトルに表示する文字列
				$init_data['css']           = 'css/item_visibility.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;             // エラーメッセージ
				$init_data['btn_name']      = '登録';             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/item_visibility.gif'; //
				$init_data['js_name']       = 'submit_item_visibility()'; // JavaScriptのファンクション名
				break;

			// 企画情報アイテム
			case SHOW_HOLIDAY_ITEM:
				// 初期共通項目情報
				$init_data['title']       = "祝日設定";  		// タイトルに表示する文字列
				$init_data['css']         = 'css/holiday_item.css'; // 個別CSSのアドレス
				$init_data['errmsg']      = NULL;             // エラーメッセージ
				$init_data['btn_name']    = "更新";           // ボタン表示名
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/holiday_item.gif'; //
				$init_data['js_name']       = 'submit_holiday_item()'; // JavaScriptのファンクション名
				break;
			// 相手先選択
			case SHOW_SELECT_CLIENT:
				$init_data['title']         = "相手先選択";    // タイトルに表示する文字列
				$init_data['css']           = 'css/select_client.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;             // エラーメッセージ
				$init_data['btn_name']      = '確定';             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/select_client.gif'; //
				$init_data['js_name']       = 'submit_select_client()'; // JavaScriptのファンクション名
				break;

			// 確認者選択
			case SHOW_SELECT_CHECKER:
				$init_data['title']         = "確認者選択";    // タイトルに表示する文字列
				$init_data['css']           = 'css/select_checker.css';    // 個別CSSのアドレス
				$init_data['errmsg']        = NULL;             // エラーメッセージ
				$init_data['btn_name']      = '決定';             // ボタン表示名（登録・変更・削除）
				$init_data['btn_confirmer'] = NULL;             // 確認者選択ボタン（確認者・認証者）
				$init_data['gif_name']      = 'images/select_checker_a.gif'; //
				$init_data['js_name']       = 'submit_select_checker()'; // JavaScriptのファンクション名
				break;

			// ヘルプ
			case SHOW_HELP:
				break;

		}
		return $init_data;
	}

	/**
	 * セッション削除
	 *
	 * @access public
	 * @return nonthing
	 */
	public function session_delete(){
		// 初期化
		$CI =& get_instance();
		$CI->load->library('session');
		$session_data = array(
							'shbn' => '',
							'ather_shbn' => '',
							'checker_shbn' => '',
							'busyo_shbn' => '',
							'group_shbn' => '',
							'checker_search_shbn' => '',
							'busyo_search_shbn' => '',
							'group_search_shbn' => '',
							'admin_flg' => '',
							'plan_count' => '',
							'from_url' => '',
							'from_header_url' => ''
						);
		// セッション削除
		$CI->session->unset_userdata($session_data);
	}

	/**
	 * ヘッダー情報の生成（名前、部署）
	 *
	 * @access public
	 * @param  string $shbn        ログイン中ユーザ社番
	 * @param  bool   $c_type      生成種別
	 * @return array  $header_data 氏名、部署情報
	 */
 	public function create_user_name($shbn,$c_type = FALSE){
		// ユーザ名、部署の取得
//		$user_data = $this->get_auth_name($init_data['shbn']);
		$user_data = $this->get_auth_name($shbn);
		// 確認者生成
		if($c_type)
		{
			$header_data = $user_data['shinnm']." ".$user_data['honbu_name']." ".$user_data['bu_name']." ".$user_data['ka_name'];
		}else{
			$header_data['bu_name'] = $user_data['honbu_name']." ".$user_data['bu_name'];
			$header_data['ka_name'] = $user_data['ka_name'];
			$header_data['shinnm'] = $user_data['shinnm'];
		}

		return $header_data;
	}

	/**
	 * 社番から氏名を取得
	 *
	 * @access public
	 * @param  string $shbn  社番
	 * @return string $name  氏名
	 */
 	public function create_name($shbn){
		// ユーザ名の取得
		$user_data = $this->get_auth_name($shbn);
		$name = $user_data['shinnm'];

		return $name;
	}

	/**
	 *
	 */
	public function get_auth_name($shbn = NULL){
		log_message('debug',"========== libraries common_manager get_auth_name start ==========");
		if (is_null($shbn)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}
		// 初期化
		$CI =& get_instance();
		$CI->load->model(array('sgmtb010','sgmtb020'));
		$ret_data = NULL;
		$shinnm = NULL;
		$honbucd = NULL;
		$bucd = NULL;
		$kacd = NULL;

		// 氏名取得
		$db_data = $CI->sgmtb010->get_heder_data($shbn);
		if (is_null($db_data)) {
			throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}else{
			$ret_data['shinnm'] = $db_data['0']['shinnm'];
			$honbucd = $db_data['0']['honbucd'];
			$bucd = $db_data['0']['bucd'];
			$kacd = $db_data['0']['kacd'];
		}
		// 本部名取得
		$db_data = $CI->sgmtb020->get_name($honbucd,MY_DB_BU_ESC,MY_DB_BU_ESC);
		if (is_null($db_data)) {
			$ret_data['honbu_name'] = "";
			//throw new Exception("Error Processing Request", ERROR_SYSTEM);
		}else{
			$ret_data['honbu_name'] = $db_data['0']['bunm'];
		}
		// 部名取得
		if ($bucd === MY_DB_BU_ESC) {
			$ret_data['bu_name'] = '';
		}else{
			$db_data = $CI->sgmtb020->get_name($honbucd,$bucd,MY_DB_BU_ESC);
			if (is_null($db_data)) {
				$ret_data['bu_name'] = "";
				//throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}else{
				$ret_data['bu_name'] = $db_data['0']['bunm'];
			}
		}
		// 課・ユニット名取得
		if ($kacd === MY_DB_BU_ESC) {
			$ret_data['ka_name'] = '';
		}else{
			$db_data = $CI->sgmtb020->get_name($honbucd,$bucd,$kacd);
			if (is_null($db_data)) {
				log_message('debug',"Exception Error db_data is NULL");
				$ret_data['ka_name'] = "";
				//throw new Exception("Error Processing Request", ERROR_SYSTEM);
			}else{
				$ret_data['ka_name'] = $db_data['0']['bunm'];
			}
		}
		log_message('debug',"========== libraries common_manager get_auth_name end ==========");
		return $ret_data;
	}

	/**
	 * 情報メモテーブルのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_memo($admin_flg = MY_TYPE_GENERAL,$shbn)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
//		if($admin_flg === MY_TYPE_GENERAL){
		if($admin_flg === '003' OR $admin_flg === '002'){
			$table = $CI->config->item('c_admin_memo_table'); // テーブル設定情報取得
		}else{
			$table = $CI->config->item('c_general_memo_table'); // テーブル設定情報取得
		}
		$title = $CI->config->item('c_memo_title'); // タイトル情報取得
		$link  = $CI->config->item('c_memo_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_memo_data($admin_flg,$shbn); // 情報メモ情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 情報メモテーブル作成
		return $string_table;
	}

	/**
	 * 情報メモ情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_memo_data($admin_flg = MY_TYPE_GENERAL,$shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srktb050');
		$memo_data = NULL;
		$db_data = NULL;
		$knnm = NULL;
		$base_url = $CI->config->item('base_url');

		if ($admin_flg === '002' OR $admin_flg === '003') {
			$db_data = $CI->srktb050->get_top_admin_data($shbn);
		}else{
			$db_data = $CI->srktb050->get_top_general_data($shbn);
		}
		if (is_null($db_data)) {
			$memo_data[0][0] = '';
			$memo_data[0][1] = '';
			$memo_data[0][2] = '';
		}else{
			foreach ($db_data as $key => $value) {
				$memo_data[$key][0] = substr($value['createdate'],4,2)."/".substr($value['createdate'],6,2);
				$knnm = "<a href='javascript:data_memo_search_select(\"".$value['jyohonum']."\",\"".$value['edbn']."\",\"".$base_url."\")'>";
				$knnm .= mb_strimwidth($value['knnm'],0,15,'…','UTF-8');
				$knnm .= "</a>";
				$memo_data[$key][1] = $knnm;
				$memo_data[$key][2] = mb_strimwidth($value['aitesknm'],0,15,'…','UTF-8');
			}
		}
		return $memo_data;
	}

	/**
	 * ユニット長日報閲覧状況のHTMLを作成
	 *
	 * @access	public
	 * @param	string $shbn
	 * @param	string $user_type
	 * @return	string
	 */
	public function set_read_report($user_type = MY_TYPE_GENERAL,$shbn)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_read_report_table'); // テーブル設定情報取得
		// ログインユーザーが担当者の場合
		if($user_type === MY_TYPE_GENERAL)
		{
			$title = $CI->config->item('c_read_report_title_g'); // タイトル情報取得（担当者）
		}else{
			// それ以外
			$title = $CI->config->item('c_read_report_title_a'); // タイトル情報（管理者、上席者）取得
		}
		$link  = $CI->config->item('c_read_report_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_read_report_data($shbn); // ユニット長日報閲覧状況情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // ユニット長日報閲覧状況テーブル作成
		return $string_table;
	}

	/**
	 * ユニット長日報閲覧状況情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_read_report_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('sgmtb010');
		$CI->load->model('srntb050');
		$reading = $CI->config->item(MY_READING_KUBUN);
		$comment = $CI->config->item(MY_COMMENT_KUBUN);

		$db_data = $CI->srntb050->get_read_report_data($shbn);
		if (is_null($db_data)) {
			$read_report_data[0][0] = '';
			$read_report_data[0][1] = '';
			$read_report_data[0][2] = '';
			$read_report_data[0][3] = '';
		}else{
			foreach ($db_data as $key => $value) {
				$read_report_data[$key][0] = substr($value['ymd'],4,2)."/".substr($value['ymd'],6,2);
				$name = $CI->sgmtb010->get_shin_nm($value['kakninshbn']);
				if ($name === FALSE) {
					$read_report_data[$key][1] = '';
				}else{
					$read_report_data[$key][1] = $name;
				}
				$read_report_data[$key][2] = $reading[$value['etujukyo']];
				$read_report_data[$key][3] = $comment[$value['comment']];
			}
		}

		return $read_report_data;
	}

	/**
	 * 受取日報のHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_result_report($shbn)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_result_report_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_result_report_title'); // タイトル情報取得
		$link  = $CI->config->item('c_result_report_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_result_report_data($shbn); // 受取日報情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 受取日報テーブル作成
		return $string_table;
	}

	/**
	 * 受取日報情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_result_report_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('sgmtb010');
		$CI->load->model('srntb050');
		$reading = $CI->config->item(MY_READING_KUBUN);
		$comment = $CI->config->item(MY_COMMENT_KUBUN);

		$db_data = $CI->srntb050->get_result_report_data($shbn);
		if (is_null($db_data)) {
			$result_report_data[0][0] = '';
			$result_report_data[0][1] = '';
			$result_report_data[0][2] = '';
			$result_report_data[0][3] = '';
		}else{
			foreach ($db_data as $key => $value) {
				$result_report_data[$key][0] = substr($value['ymd'],4,2)."/".substr($value['ymd'],6,2);
				$name = $CI->sgmtb010->get_shin_nm($value['shbn']);
				if ($name === FALSE) {
					$result_report_data[$key][1] = '';
				}else{
					$result_report_data[$key][1] = $name;
				}
				$result_report_data[$key][2] = $reading[$value['etujukyo']];
				$result_report_data[$key][3] = $comment[$value['comment']];
			}
		}

		return $result_report_data;
	}

	/**
	 * ToDoテーブルのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_todo($admin_flg = MY_TYPE_GENERAL,$shbn)
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->library('table_manager');
		// 設定値取得
		if($admin_flg === '002' OR $admin_flg === '003'){
			$table = $CI->config->item('c_admin_todo_table'); // テーブル設定情報取得
		}else{
			$table = $CI->config->item('c_general_todo_table'); // テーブル設定情報取得
		}
//		$title = $CI->config->item('c_todo_title'); // タイトル情報取得
//		$link  = $CI->config->item('c_todo_link'); // リンク情報取得
		$other = $CI->config->item('c_todo_button'); // 追加情報取得
		$data  = $this->_get_todo_data($shbn); // ToDo情報取得
//		$string_table = $this->_set_table($table,$title,$link,$other,$data); // ToDoテーブル作成
		$string_table = $CI->table_manager->set_top_todo_string($data,$admin_flg); // ToDoテーブル作成
		return $string_table;
	}

	/**
	 * ToDo情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_todo_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srktb040');
		$kubun = $CI->config->item(MY_TODO_IMPKBN);
		$todo_data = NULL;

		$db_data = $CI->srktb040->get_top_data($shbn);
		if (is_null($db_data)) {
			$todo_data[0]['jyohonum'] = '';
			$todo_data[0]['edbn'] = '';
			$todo_data[0]['day'] = '';
			$todo_data[0]['todo'] = '';
			$todo_data[0]['impkbn'] = '';
			$todo_data[0]['check'] = '';
		}else{
			foreach ($db_data as $key => $value) {
				$todo_data[$key]['jyohonum'] = $value['jyohonum'];
				$todo_data[$key]['edbn'] = $value['edbn'];
				$todo_data[$key]['day'] = substr($value['designatedday'],4,2)."/".substr($value['designatedday'],6,2);
				$todo_data[$key]['todo'] = mb_strimwidth($value['todo'],0,12,'…','UTF-8');
				$todo_data[$key]['impkbn'] = $value['impkbn'];
				$todo_data[$key]['check'] = $value['finishflg'];
			}
		}
		return $todo_data;
	}

	/**
	 * 部下のスケジュールのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_schedule($shbn)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('c_schedule_table'); // テーブル設定情報取得
		$title = $CI->config->item('c_schedule_title'); // タイトル情報取得
		$link  = $CI->config->item('c_schedule_link'); // リンク情報取得
		$other = NULL; // 追加情報取得
		$data  = $this->_get_schedule_data($shbn); // 部下のスケジュール情報取得
		$string_table = $this->_set_table($table,$title,$link,$other,$data); // 部下のスケジュールテーブル作成
		return $string_table;
	}

	/**
	 * 部下のスケジュール情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_schedule_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model(array('sgmtb010','srntb110','srntb120','srntb130','srntb140','srntb150'));

		$db_data = $CI->srntb150->get_subordinate_data($shbn);
		if (is_null($db_data)) {
			$schedule_data[0][0] = '';
			$schedule_data[0][1] = '';
			$schedule_data[0][2] = '';
		}else{
			foreach ($db_data as $key => $value) {
				$name = $CI->sgmtb010->get_shin_nm($value['shbn']);
				if ($name === FALSE) {
					$schedule_data[$key][1] = '';
				}else{
					$schedule_data[$key][1] = $name;
				}
				if ($value['kubun'] === '1') {
					$honbu_data = $CI->srntb110->get_schedule_data($value['jyohonum'],$value['edbn']);
					if ( ! is_null($honbu_data)) {
						$schedule_data[$key][0] = substr($honbu_data[0]['sthm'],0,2)."/".substr($honbu_data[0]['sthm'],2,2);
						$schedule_data[$key][2] = $honbu_data[0]['aitesknm'];
					}else{
						$schedule_data[$key][0] = '';
						$schedule_data[$key][2] = '';
					}
				}else if ($value['kubun'] === '2') {
					$tenpo_data = $CI->srntb120->get_schedule_data($value['jyohonum'],$value['edbn']);
					if ( ! is_null($honbu_data)) {
						$schedule_data[$key][0] = substr($tenpo_data[0]['sthm'],0,2)."/".substr($tenpo_data[0]['sthm'],2,2);
						$schedule_data[$key][2] = $tenpo_data[0]['aitesknm'];
					}else{
						$schedule_data[$key][0] = '';
						$schedule_data[$key][2] = '';
					}
				}else if ($value['kubun'] === '3') {
					$dairi_data = $CI->srntb130->get_schedule_data($value['jyohonum'],$value['edbn']);
					if ( ! is_null($dairi_data)) {
						$schedule_data[$key][0] = substr($dairi_data[0]['sthm'],0,2)."/".substr($dairi_data[0]['sthm'],2,2);
						$schedule_data[$key][2] = $dairi_data[0]['aitesknm'];
					}else{
						$schedule_data[$key][0] = '';
						$schedule_data[$key][2] = '';
					}
				}else if ($value['kubun'] === '4') {
					$naikin_data = $CI->srntb140->get_schedule_data($value['jyohonum'],$value['edbn']);
					if ( ! is_null($naikin_data)) {
						$schedule_data[$key][0] = substr($naikin_data[0]['sthm'],0,2)."/".substr($naikin_data[0]['sthm'],2,2);
						$schedule_data[$key][2] = $naikin_data[0]['aitesknm'];
					}else{
						$schedule_data[$key][0] = '';
						$schedule_data[$key][2] = '';
					}
				}
			}
		}

		return $schedule_data;
	}

	/**
	 * InfomaionのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_info()
	{
		// 初期化
		$CI =& get_instance();
		$CI->load->library('top_table_manager');
		// 設定値取得
//		$table = $CI->config->item('c_info_table'); // テーブル設定情報取得
//		$title = $CI->config->item('c_info_title'); // タイトル情報取得
//		$link  = $CI->config->item('c_info_link'); // リンク情報取得
//		$other = NULL; // 追加情報取得
		$data  = $this->_get_info_data(); // Infomaion情報取得
		$string_table = $CI->top_table_manager->tab_list_set($data);
//		$string_table = $this->_set_table($table,$title,$link,$other,$data); // Infomaionテーブル作成
		return $string_table;
	}

	/**
	 * Infomaion情報取得
	 *
	 * @access	private
	 * @param	nothing
	 * @return	array
	 */
	function _get_info_data()
	{
		$CI =& get_instance();
		$CI->load->model('srktb060');
		$today = date("Ymd");
		$count = 0;
		$info_data[0][0] = ''; // 初期化
		$shbn = $CI->session->userdata('shbn');
		$get_data = $CI->srktb060->get_info_data($shbn);
		$base_url = $CI->config->item('base_url');
		if (is_null($get_data)) {
			$info_data[0]['jyohoniyo'] = '';
			$info_data[0]['link'] = NULL;
		}else{
			foreach ($get_data as $key => $value) {
				if ($value['tuchistartdate'] <= $today AND $today <= $value['tuchienddate']) {
//					if ($value['allflg']) {
							$info_data[$count]['jyohoniyo'] = $value['jyohoniyo'];
						// 添付ファイルの有無判定
						if(!empty($value['tempfile'])){
							$file_path = "message/".$value['jyohonum']."/".$value['tempfile'];
							// 添付ファイルの存在確認
								log_message('debug',FILE_DIR.$file_path);
							if(file_exists(FILE_DIR.$file_path)){
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
		}

		return $info_data;
	}



	/**
	 * バナーリンクのHTMLを作成
	 *
	 * @access	public
	 * @param	nothing
	 * @return	string
	 */
	public function set_banner_link()
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$CI->load->helper('html');
		$data = $CI->config->item('c_banner_link_data'); // バナーリンク設定情報取得

		// テーブル作成
		$string_table = "";
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($data['table_width'])){
			$string_table .= " width=\"" . $data['table_width'] . "\";";
		}
		// テーブル高さ設定
		if( ! is_null($data['table_height'])){
			$string_table .= " height=\"" . $data['table_height'] . "\";";
		}
		$string_table .= ">\n";
		// 見出し行高さ設定
		if( ! is_null($data['heading_tr_height'])){
			$string_table .= "<tr style=\"height:" . $data['heading_tr_height'] . "\">\n";
		}else{
			$string_table .= "<tr>\n";
		}
		// 見出し行横位置設定
		if( ! is_null($data['heading_th_style'])){
			$string_table .= "<th style=\"" . $data['heading_th_style'] . "\">\n";
		}else{
			$string_table .= "<th>\n";
		}
		// 見出し表示内容設定
		$string_table .= "<a>" . $data['heading'] . "</a>\n";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		$string_table .= "<tr>\n";
		// リンクスタイル設定
		$string_style = NULL;
		if( ! is_null($data['td_padding_left'])){
			$string_style .= " padding-left:" . $data['td_padding_left'] . ";";
		}
		if( ! is_null($data['td_border_collapse'])){
			$string_style .= " border-collapse:" . $data['td_border_collapse'] . ";";
		}
		if( ! is_null($string_style)){
			$string_table .= "<td style=\"" . $string_style . "\">\n";
		}else{
			$string_table .= "<td>\n";
		}
		// バナーリンク設定
		$string_table .= "<table>\n";
		// DC-NET
		$string_table .= "<tr style=\"" . $data['link_tr_height'] . "\">\n";
		$string_table .= "<th><a href=\"" . $data['link_a_dcnet'] . "\">\n";
		$string_table .= img($data['link_img_dcnet']);
		$string_table .= "</a>";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		// OUTLOOK
		$string_table .= "<tr style=\"" . $data['link_tr_height'] . "\">\n";
		$string_table .= "<th><a href=\"" . $data['link_a_outlook'] . "\">\n";
		$string_table .= img($data['link_img_outlook']);
		$string_table .= "</a>";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		// SFA
		$string_table .= "<tr style=\"" . $data['link_tr_height'] . "\">\n";
		$string_table .= "<th><a href=\"" . $data['link_a_sfa'] . "\">\n";
		$string_table .= img($data['link_img_sfa']);
		$string_table .= "</a>";
		$string_table .= "</th>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";
		$string_table .= "</td>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";

		return $string_table;
	}

	/**
	 * テーブル情報設定
	 *
	 * @access	private
	 * @param	array
	 * @return	string
	 */
	function _set_table($table,$title,$link,$other,$data)
	{
		// 初期化
		$CI =& get_instance();

		// テーブル作成
		$string_table = "";
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($table['table_width'])){
			$string_table .= " width=\"" . $table['table_width'] . "\";";
		}
		// テーブル高さ設定
		if( ! is_null($table['table_height'])){
			$string_table .= " height=\"" . $table['table_height'] . "\";";
		}
		$string_table .= ">\n";

		// 見出し行有無判定
		if( ! is_null($table['heading'])){
			// 見出し行高さ設定
			if( ! is_null($table['heading_tr_height'])){
				$string_table .= "<tr style=\"height:" . $table['heading_tr_height'] . "\">\n";
			}else{
				$string_table .= "<tr>\n";
			}
			// 見出し行横位置設定
			if( ! is_null($table['heading_th_style'])){
				$string_table .= "<th style=\"" . $table['heading_th_style'] . "\">\n";
			}else{
				$string_table .= "<th>\n";
			}
			// 見出し行リンク設定
			$string_table .= "<a";
			if( ! is_null($link['heading_link'])){
				$string_table .= " href=\"" . $link['heading_link'] . "\"";
			}
			// 見出し行スタイル設定
			$string_style = "";
			if( ! is_null($table['a_style_border'])){
				$string_style .= "border:" . $table['a_style_border'] . ";";
			}
			if( ! is_null($table['a_style_font_size'])){
				$string_style .= "font-size:" . $table['a_style_font_size'] . ";";
			}
			if( ! is_null($table['a_style_decoration'])){
				$string_style .= "text-decoration:" . $table['a_style_decoration'] . ";";
			}
			if( ! is_null($table['a_style_color'])){
				$string_style .= "color:" . $table['a_style_color'] . ";";
			}
			// style設定判定
			if( ! is_null($string_style)){
				$string_table .= " style=\"" . $string_style . "\">";
			}else{
				$string_table .= ">";
			}
			// 見出し表示内容設定
			if( ! is_null($table['heading'])){
				$string_table .= $table['heading'] . "</a>\n";
			}else{
				/* エラー処理 今後追加 */
				$string_table .= "</a>\n";
			}
			$string_table .= "</th>\n";
			// 追加情報有無判定
			if( ! is_null($other)){
				// スタイル設定
				if( ! is_null($other['td_style'])){
					$string_table .= "<td style=\"" . $other['td_style'] . "\">\n";
				}else{
					$string_table .= "<td>\n";
				}
				// type判定
				if( ! is_null($other['type'])){
					$string_table .= "<input type=\"" . $other['type'] . "\"";
					// style判定
					$string_style = "";
					if( ! is_null($other['style_height'])){
						$string_style .= " height:" . $other['style_height'] . ";";
					}
					if( ! is_null($other['style_font_size'])){
						$string_style .= " font-size:" . $other['style_font_size'] . ";";
					}
					if( ! is_null($string_style)){
						$string_table .= " style=\"" . $string_style . "\"";
					}
					// value判定
					if( ! is_null($other['value'])){
						$string_table .= " value=\"" . $other['value'] . "\"";
					}
					$string_table .= ">\n";
				}

				// ページネーション追加 2012/02/01　asakura
				if( isset($other['pagination'])) {
					$string_table .= $other['pagination'];
				}

				$string_table .= "</td>\n";
			}
			$string_table .= "</tr>\n";
		}
		// title設定
		$string_table .= "<tr>\n";
		$string_table .= "<td style=\"padding-left:" . $table['td_padding_left'] . ";";
		// collapse設定
		if( ! is_null($table['td_border_collapse'])){
			$string_table .= " border-collapse:" . $table['td_border_collapse'] . ";";
		}
		// border設定
		if( ! is_null($table['td_border'])){
			$string_table .= " border:" . $table['td_border'] . ";";
		}
		$string_table .= "\"";
		// 追加情報有無判定
		if($table['td_colspan'] === MY_COLSPAN_EXISTENCE){
			$string_table .= ">\n";
		}else{
			$string_table .= " colspan=\"" . $table['td_colspan'] . "\">\n";
		}
		$string_table .= "<table>\n";
		$string_table .= "<tr>\n";

		for($i=0; $i < $table['span']; $i++){
			$string_table .= "<th";
			// 下線設定
			$string_style = "";
			if( ! is_null($table['title_th_border_bottom'])){
				$string_style .= " border-bottom:" . $table['title_th_border_bottom'] . ";";
			}
			// 高さ設定
			if( ! is_null($table['title_th_height'])){
				$string_style .= " height:" . $table['title_th_height'] . ";";
			}
			// 幅設定
			if( ! is_null($table['title_th_width'])){
				$string_style .= " width:" . $table['title_th_width'][$i] . ";";
			}
			// 背景色設定
			if( ! is_null($table['title_th_bakcolor'])){
				$string_style .= " background-color:" . $table['title_th_bakcolor'] . ";";
			}
			// 上部余白設定
			if( ! is_null($table['title_th_padding_top'])){
				$string_style .= " padding-top:" . $table['title_th_padding_top'] . ";";
			}
			if( ! is_null($string_table)){
				$string_table .= " style=\"" . $string_style . " text-align:center;\"";
			}
			$string_table .= ">";
			$string_table .= $title[$i];
			$string_table .= "</th>\n";
		}
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";
		// divタグ設定有無判定
		if($table['div_existence'] === TRUE)
		{
			$string_table .= "<div style=\"margin:" . $table['div_style_margin'] . ";";
			$string_table .= " height:" . $table['div_style_height'] . ";";
			$string_table .= " width:" . $table['div_style_width'] . ";";
			$string_table .= " overflow:" . $table['div_style_overflow'] . ";";
			$string_table .= " background-color:" . $table['div_style_bakcolor'] . ";";
			$string_table .= "\">\n";
		}
		// テーブル内容設定
		$string_table .= "<table>\n";
		foreach($data as $key => $value){
			$string_table .= "<tr>\n";
			for($i=0; $i < $table['span']; $i++){
				$string_style = "";
				// 高さ設定
				if( ! is_null($table['div_td_height'])){
					$string_style .= " height:" . $table['div_td_height'] . ";";
				}
				// 幅設定
				if( ! is_null($table['div_td_width'][$i])){
					$string_style .= " width:" . $table['div_td_width'][$i] . ";";
				}
				if(! is_null($string_style)){
					$string_style .= "\"";
				}
				// 位置設定
				if( ! is_null($table['div_td_align'][$i])){
					$string_style .= " align=\"" . $table['div_td_align'][$i];
				}
				// style設定有無
				if( ! is_null($string_style)){
					$string_table .= "<td style=\"" . $string_style . "\">";
				}else{
					$string_table .= "<td>";
				}
				// "a"タグ判定
				if($table['div_td_a_existence'][$i] === TRUE){
					$string_table .= "<a href=\"" . $CI->config->item('base_url') . $link['link'] . "\">";
					$string_table .= $value[$i];
					$string_table .= "</a></td>\n";
				}else{
					// inputtype判定
					if( ! is_null($table['div_td_input_type'][$i])){
						$string_table .= "<input type=\"" . $table['div_td_input_type'][$i] . "\" style=\"width:148px;\">";
					}
					$string_table .= $value[$i];
					$string_table .= "</td>\n";
				}
			}
			$string_table .= "</tr>\n";
		}
		$string_table .= "</table>\n";
		$string_table .= "</div>\n";
		$string_table .= "</td>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";

		return $string_table;
	}

}

// END Common_manager class

/* End of file Common_manager.php */
/* Location: ./application/libraries/common_manager.php */
