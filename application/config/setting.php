<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * テーブルやバナーのサイズ等、数値に関する設定
 */
	// -------------------------------------------------------------------------
	// ヘッダ部共通項目
	// -------------------------------------------------------------------------
	$config['s_header_table_width'] = '990px'; // 横幅
	$config['s_header_table_hight'] = '990px'; // 縦幅

	// -------------------------------------------------------------------------
	// 画面表示初期情報
	// タブ表示文言、使用CSS、タイトルバナー、エラーメッセージ、ボタン名の初期値を設定
	// -------------------------------------------------------------------------
	$config['s_login'] = array(
		'title'    => "ログイン",
		'css'      => 'css/login.css',
		'image'    => 'images/login.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_top'] = array(
		'title'    => "トップ",
		'css'      => 'css/top.css',
		'image'    => 'images/top.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_calendar_plan'] = array(
		'title'    => "カレンダー(スケジュール)",
		'css'      => 'css/calendar.css',
		'image'    => 'images/calendar_plan.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_calendar_result'] = array(
		'title'    => "カレンダー(日報実績)",
		'css'      => 'css/calendar.css',
		'image'    => 'images/calendar_result.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_user_add'] = array(
		'title'    => "ユーザー管理",
		'css'      => NULL,
		'image'    => 'images/admin_user.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 登録 ",
		'form'     => "add_select_type"
	);
	$config['s_user_update'] = array(
		'title'    => "ユーザー管理",
		'css'      => NULL,
		'image'    => 'images/admin_user.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 変更 ",
		'form'     => "update_select_type"
	);
	$config['s_user_delete'] = array(
		'title'    => "ユーザー管理",
		'css'      => NULL,
		'image'    => 'images/admin_user.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 削除 ",
		'form'     => "delete_select_type"
	);
	$config['s_select_checker'] = array(
		'title'    => "確認者選択画面(自)",
		'css'      => NULL,
		'image'    => 'images/select_checker_a.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 決定 ",
		'form'     => "select_type"
	);
	$config['s_checker_search_conf'] = array(
		'title'    => "確認者検索画面",
		'css'      => NULL,
		'image'    => 'images/search_conf.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 決定 ",
		'form'     => ""
	);
	$config['s_busyo_search_unit'] = array(
		'title'    => "部署検索画面",
		'css'      => NULL,
		'image'    => 'images/search_unit.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 決定 ",
		'form'     => ""
	);
	$config['s_busyo_search_group'] = array(
		'title'    => "グループ検索画面",
		'css'      => NULL,
		'image'    => 'images/search_group.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 決定 ",
		'form'     => ""
	);
	$config['s_plan_a'] = array(
		'title'    => "スケジュール",
		'css'      => NULL,
		'image'    => 'images/plan.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_plan_u'] = array(
		'title'    => "スケジュール",
		'css'      => NULL,
		'image'    => 'images/plan.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_plan_d'] = array(
		'title'    => "スケジュール",
		'css'      => NULL,
		'image'    => 'images/plan.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_result_a'] = array(
		'title'    => "日報実績",
		'css'      => NULL,
		'image'    => 'images/result.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_result_u'] = array(
		'title'    => "日報実績",
		'css'      => NULL,
		'image'    => 'images/result.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	$config['s_result_d'] = array(
		'title'    => "日報実績",
		'css'      => NULL,
		'image'    => 'images/result.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => NULL
	);
	// 仮相手先 asakura
	$config['s_kari_client_add'] = array(
		'title'    => "仮相手先",
		'css'      => NULL,
		'image'    => 'images/kari_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 登録 ",
		'form'     => "add_select_type"
	);
	$config['s_kari_client_update'] = array(
		'title'    => "仮相手先",
		'css'      => NULL,
		'image'    => 'images/kari_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 変更 ",
		'form'     => "update_select_type"
	);
	$config['s_kari_client_delete'] = array(
		'title'    => "仮相手先",
		'css'      => NULL,
		'image'    => 'images/kari_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 削除 ",
		'form'     => "delete_select_type"
	);

	// メッセージ asakura2
	//$config['s_allmessage_add'] = array(
	$config['s_message'] = array(
		'title'    => "メッセージ",
		'css'      => NULL,
		'image'    => 'images/message.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 登録 ",
		'form'     => "add_select_type"
	);
	// 区分設定
	$config['s_division'] = array(
		'title'    => "区分設定",
		'css'      => NULL,
		'image'    => 'images/kbn_set.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 登録 ",
		'form'     => 'register'
	);

	/// aiba add 20110121 //////////////////////////////
	// 情報メモ(登録画面)
	$config['s_data_memo_a'] = array(
		'title'    => "情報メモ",
		'css'      => NULL,
		'image'    => 'images/data_memo.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 登録 ",
		'form'     => "add_select_type"
	);
	// 情報メモ(更新画面)
	$config['s_data_memo_u'] = array(
		'title'    => "情報メモ",
		'css'      => NULL,
		'image'    => 'images/data_memo.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "update/search"
	);
	// 情報メモ(更新画面の検索)
	$config['s_data_memo_su'] = array(
		'title'    => "情報メモ",
		'css'      => NULL,
		'image'    => 'images/data_memo.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => "update_select_type"
	);
	// 情報メモ(削除画面)
	$config['s_data_memo_d'] = array(
		'title'    => "情報メモ",
		'css'      => NULL,
		'image'    => 'images/data_memo.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "削除",
		'form'     => "delete/search"
	);
	// 情報メモ(削除画面の検索)
	$config['s_data_memo_sd'] = array(
		'title'    => "情報メモ",
		'css'      => NULL,
		'image'    => 'images/data_memo.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => NULL,
		'form'     => "delete_select_type"
	);
	// 企画情報アイテム
	$config['s_project_item'] = array(
		'title'    => "企画情報アイテム",
		'css'      => NULL,
		'image'    => 'images/project_item.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "index"
	);
	// 企画獲得(登録)
	$config['s_project_possession'] = array(
			'title'    => "企画獲得",
			'css'      => NULL,
			'image'    => 'images/project_possession.gif',
			'msg'      => NULL,
			'errmsg'   => NULL,
			'btn_name' => "登録",
			'form'     => "index"
	);
	// 企画獲得(表示)
	$config['s_project_possession_v'] = array(
				'title'    => "企画獲得",
				'css'      => NULL,
				'image'    => 'images/project_possession.gif',
				'msg'      => NULL,
				'errmsg'   => NULL,
				'btn_name' => NULL,
				'form'     => "index"
	);
	$config['s_c_client_kari'] = array(
		'title'    => "正式相手処理",
		'css'      => NULL,
		'image'    => 'images/c_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "kari"
	);
	$config['s_c_client_seishiki'] = array(
		'title'    => "正式相手処理",
		'css'      => NULL,
		'image'    => 'images/c_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "seishiki"
	);
	$config['s_c_client_mt_kari'] = array(
		'title'    => "正式相手処理",
		'css'      => NULL,
		'image'    => 'images/c_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "mt_kari"
	);
	$config['s_c_client_mt_seishiki'] = array(
		'title'    => "正式相手処理",
		'css'      => NULL,
		'image'    => 'images/c_client.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "mt_seishiki"
	);
	// 祝日設定アイテム
	$config['s_holiday_item'] = array(
		'title'    => "祝日設定アイテム",
		'css'      => NULL,
		'image'    => 'images/holiday_item.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => "更新",
		'form'     => "index"
	);
	////////////////////////////////////////////////////

	/////////// ポップアップテスト用////////////////////
	$config['pop_test'] = array(
		'title'    => "確認者検索画面",
		'css'      => NULL,
		'image'    => 'images/search_conf.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 決定 ",
		'form'     => "checker_search_conf"
	);

	// -------------------------------------------------------------------------
	// カレンダー共通項目
	// -------------------------------------------------------------------------
	// トップ画面カレンダー設定値
	$config['s_top_calendar'] = array(
		'title'        => 'スケジュール',
		'tr_height'    => '18px',
		'th_align'     => 'text-align:left',
//		'a_href'       => 'index.php/calendar/plan',
		'a_href'       => 'index.php/plan/index/',
		'border'       => '1px solid #000000',
		'font-size'    => '9pt',
		'decoration'   => 'none',
		'a_color'      => '#000000',
		'tr_bak_color' => '#ffff99',
		'collapse'     => 'collapse',
		'th_width'     => '115px',
		'td_height'    => '70px',
		'td_bak_color' => '#FFFFFF'
	);

	// カレンダー設定値
	$config['s_select_calendar'] = array(
		'title'                    => '',
		'title_tr_height'          => '26px',
		'title_tr_width'           => '805px',
		'title_th_span'            => '5',
		'title_th_border'          => '1px solid #000000',
		'title_th_font_size'       => '14pt',
		'title_th_text_align'      => 'center',
		'title_th_back_color'      => '#FFFFFF',
		'week_th_border_col'       => 'collapse',
		'week_th_border'           => '1px solid #000000',
		'week_th_width'            => '118px',
		'week_th_back_color'       => '#FFD700',
		'day_th_border_col'        => 'collapse',
		'day_th_border'            => '1px solid #000000',
		'day_th_width'             => '118px',
		'day_th_back_color'        => '#FFFF99',
		'day_a_text_decoration'    => 'none',
		'data_td_border_col'       => 'collapse',
		'data_td_border'           => '1px solid #000000',
		'data_td_back_color'       => '#FFFFFF',
		'data_td_align'            => 'left',
		'data_td_valign'           => 'top',
		'data_td_today_back_color' => '#CCFFCC',
		'data_td_height'           => '54px',
        'no_result_day'            => '#FFFF00'
	);

	// カレンダー画面表示月設定値
	$config['s_select_calendar_month'] = array(
		'button_height' => '35px',
		'button_width'  => '100px'
	);

	// カレンダー表示色
	$config['s_calendar_color'] = array(
		'sunday'    => '#FF0000',
		'monday'    => '#000000',
		'tuesday'   => '#000000',
		'wednesday' => '#000000',
		'thursday'  => '#000000',
		'friday'    => '#000000',
		'saturday'  => '#0000FF'
	);

	// -------------------------------------------------------------------------
	// バナーリンク項目
	// -------------------------------------------------------------------------
	// バナーリンク項目設定値
	$config['c_banner_link_data'] = array(
		'heading'            => 'リンク集',
		'table_width'        => '275px',
		'table_height'       => NULL,
		'heading_tr_height'  => '18px',
		'heading_th_style'   => 'text-align:left',
		'td_padding_left'    => '0px',
		'td_border_collapse' => 'collapse',
		'link_tr_height'     => '35px',                // 必須
		'link_a_dcnet'       => '#',                  // 必須
		'link_a_outlook'     => '#',                  // 必須
		'link_a_sfa'         => '#',                  // 必須
		'link_img_dcnet'     => 'images/dcnet.gif',   // 必須
		'link_img_outlook'   => 'images/outlook.gif', // 必須
		'link_img_sfa'       => 'images/sfa.gif'      // 必須
	);

	// -------------------------------------------------------------------------
	// テーブル設定項目説明
	// -------------------------------------------------------------------------
/*
	$config['table_name'] = array(
		'heading'                  => 'TO DO',                                // 左上に出す見出しの表示内容
		'table_width'              => '275px',                                // テーブル横幅を"px"で指定（指定しない場合は空にする）
		'table_height'             => NULL,                                   // テーブル縦幅を"px"で指定（指定しない場合は空にする）
		'span'                     => 4,                                      // テーブルのセル数を指定（数値、必須）
		'heading_tr_height'        => '18px',                                 // 見出しの縦幅を"px"で指定
		'heading_th_style'         => 'text-align:left',                      // 見出しのtext-alignを指定
		'a_style_border'           => '1px #000000 solid',                    // 見出しに付ける"a"タグのボーダーを指定
		'a_style_font_size'        => '12pt',                                 // 見出しのフォントサイズを"pt"で指定
		'a_style_decoration'       => 'none',                                 // 見出しのデコレーション指定
		'a_style_color'            => '#000000',                              // 見出しの色を指定
		'td_colspan'               => 2,                                      // 見出し行のカラム数を指定
		'td_padding_left'          => '0px',                                  // テーブル内容設置基本情報（基本固定）
		'td_border_collapse'       => 'collapse',                             // テーブル内容設置基本情報の"collapse"を指定
		'td_border'                => '1px solid #000000',                    // テーブル基本設置基本情報の"border"を指定
		'title_th_border_bottom'   => '1px solid #000000',                    // タイトル行の下線を指定（太さ、スタイル、色）
		'title_th_height'          => '20px',                                 // タイトル行の縦幅を"px"で指定
		'title_th_width'           => array('40px','100px','50px','75px'),    // タイトル行の横幅を各列毎に"px"で指定
		'title_th_bakcolor'        => '#FFFF99',                              // タイトル行のバックグラウンドカラーを指定
		'title_th_padding_top'     => '5px',                                  // タイトル行の"padding-top"を"px"で指定
		'div_existence'            => TRUE,                                   // テーブル内用のDIVタグ有無（TRUE=有、FALSE=無）
		'div_style_margin'         => 0,                                      // テーブル内容のDIVタグ、"margin"指定（基本固定）
		'div_style_height'         => '80px',                                 // テーブル内容のDIVタグ、縦幅を"px"で指定
		'div_style_width'          => '270px',                                // テーブル内容のDIVタグ、横幅を"px"で指定
		'div_style_overflow'       => 'auto',                                 // テーブル内容のDIVタグ、"overflow"指定
		'div_style_bakcolor'       => '#FFFFFF',                              // テーブル内容のDIVタグ、バックグラウンドの色を指定
		'div_td_height'            => '20px',                                 // DIVタグ内、セルの縦幅を"px"で指定
		'div_td_width'             => array('40px','100px','50px','50px')     // DIVタグ内、各列の横幅を"px"で指定
		'div_td_align'             => array('left','left','center','center'), // DIVタグ内、各列内の文字列位置を指定（指定無しは"left"かなぁ）
		'div_td_a_existence'       => array(FALSE,TRUE,FALSE,FALSE),          // DIVタグ内、セルの"a"タグ有無（TRUE=有、FALSE=無）
		'div_td_input_type'        => array(NULL,NULL,NULL,'checkbox')        // DIVタグ内、セルの内容がテキスト以外で指定（button、checkbox等）
	);
*/

	// -------------------------------------------------------------------------
	// テーブル設定項目
	// -------------------------------------------------------------------------
	// 管理者用ToDo設定値
	$config['c_admin_todo_table'] = array(
		'heading'                  => 'TO DO',
		'table_width'              => '275px',
		'table_height'             => NULL,
		'span'                     => 4,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 2,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','100px','50px','75px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '80px',
		'div_style_width'          => '270px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '20px',
		'div_td_width'             => array('40px','100px','50px','50px'),
		'div_td_align'             => array('center','left','center','left'),
		'div_td_a_existence'       => array(FALSE,TRUE,FALSE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL,'checkbox')
	);
	// 一般用ToDo設定値
	$config['c_general_todo_table'] = array(
		'heading'                  => 'TO DO',
		'table_width'              => '275px',
		'table_height'             => NULL,
		'span'                     => 4,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 2,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','100px','50px','75px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '245px',
		'div_style_width'          => '270px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '20px',
		'div_td_width'             => array('40px','100px','50px','50px'),
		'div_td_align'             => array('center','left','center','left'),
		'div_td_a_existence'       => array(FALSE,TRUE,FALSE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL,'checkbox')
	);

	// 管理者用情報メモ設定値
	$config['c_admin_memo_table'] = array(
		'heading'                  => '情報メモ',
		'table_width'              => '275px',
		'table_height'             => NULL,
		'span'                     => 3,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 1,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','100px','125px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '80px',
		'div_style_width'          => '270px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '15px',
		'div_td_width'             => array('40px','100px','105px'),
		'div_td_align'             => array('left','left','left'),
		'div_td_a_existence'       => array(FALSE,TRUE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL)
	);
	// 一般用情報メモ設定値
	$config['c_general_memo_table'] = array(
		'heading'                  => '情報メモ',
		'table_width'              => '275px',
		'table_height'             => NULL,
		'span'                     => 3,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 1,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','100px','125px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '205px',
		'div_style_width'          => '265px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '15px',
		'div_td_width'             => array('40px','100px','105px'),
		'div_td_align'             => array('left','left','left'),
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL)
	);

	// ユニット長日報閲覧状況設定値
	$config['c_read_report_table'] = array(
		'heading'                  => 'ユニット長日報閲覧状況',
		'table_width'              => '275px',
		'table_height'             => NULL,
		'span'                     => 4,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 1,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','100px','50px','75px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '80px',
		'div_style_width'          => '270px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '15px',
		'div_td_width'             => array('40px','100px','75px','55px'),
		'div_td_align'             => array('center','center','center','center'),
		'div_td_a_existence'       => array(FALSE,TRUE,FALSE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL,NULL)
	);

	// 受取日報設定値
	$config['c_result_report_table'] = array(
		'heading'                  => '受取日報',
		'table_width'              => '275',
		'table_height'             => NULL,
		'span'                     => 3,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 1,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','140px','85px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '80px',
		'div_style_width'          => '270px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '15px',
		'div_td_width'             => array('40px','130px','75px'),
		'div_td_align'             => array('center','center','center'),
		'div_td_a_existence'       => array(FALSE,TRUE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL)
	);

	// 部下のスケジュール報設定値
	$config['c_schedule_table'] = array(
		'heading'                  => '部下のスケジュール',
		'table_width'              => '275px',
		'table_height'             => NULL,
		'span'                     => 3,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 1,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '20px',
		'title_th_width'           => array('40px','100px','125px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '200px',
		'div_style_width'          => '270px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '20px',
		'div_td_width'             => array('40px','100px','105px'),
		'div_td_align'             => array('center','center','center'),
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL)
	);

	// Informaion報設定値
	$config['c_info_table'] = array(
		'heading'                  => NULL,
		'table_width'              => '550px',
		'table_height'             => NULL,
		'span'                     => 1,
		'heading_tr_height'        => '18px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => '1px #000000 solid',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 1,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px #000000 solid',
		'title_th_height'          => '18px',
		'title_th_width'           => array('550px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '3px',
		'div_existence'            => TRUE,
		'div_style_margin'         => 0,
		'div_style_height'         => '80px',
		'div_style_width'          => '550px',
		'div_style_overflow'       => 'auto',
		'div_style_bakcolor'       => '#FFFFFF',
		'div_td_height'            => '20px',
		'div_td_width'             => array('540px'),
		'div_td_align'             => array('left'),
		'div_td_a_existence'       => array(FALSE),
		'div_td_input_type'        => array(NULL)
	);

	// 商談履歴検索結果一覧設定値
	$config['c_nego_history_table'] = array(
		'heading'                  => '【検索結果一覧】',
		'table_width'              => '620px',
		'table_height'             => NULL,
		'span'                     => 4,
		'data_key'                 => array('ymd_sla','aitesk_cd','aitesk_nm','sdn_niyo'),
		'heading_tr_height'        => '20px',
		'heading_th_style'         => 'text-align:left',
		'a_style_border'           => 'none',
		'a_style_font_size'        => '10pt',
		'a_style_decoration'       => 'none',
		'a_style_color'            => '#000000',
		'td_colspan'               => 2,
		'td_padding_left'          => '0px',
		'td_border_collapse'       => 'collapse',
		'td_border'                => '1px solid #000000',
		'title_th_border_bottom'   => '1px solid #000000',
		'title_th_height'          => '15px',
		'title_th_width'           => array('80px','80px','150px','300px'),
		'title_th_bakcolor'        => '#FFFF99',
		'title_th_padding_top'     => '5px',
		'div_td_height'            => '20px',
		'div_td_width'             => array('80px','80px','150px','300px'),
		'div_td_align'             => array('center','center','left','left'),
		'div_td_a_existence'       => array(TRUE,FALSE,FALSE,FALSE),
		'div_td_input_type'        => array(NULL,NULL,NULL,NULL)
	);
	// 確認者選択画面
	$config['s_checker_table'] = array(
		'heading'                  => '【確認者】',       // 見出しタイトル
		'table_width'              => '730px',           // 見出しテーブル幅
		'table_height'             => NULL,              // 見出しテーブル高さ
		'max_span'                 => 2,                 // １行における最大表示セット数
		'span'                     => 5,                 // 項目数
		'heading_tr_height'        => '21px',            // 行の高さ
		'heading_th_style'         => 'text-align:left', // 見出しタイトルそろえ位置
		'a_style_border'           => 'none',            // リンクの下線
		'a_style_font_size'        => '10pt',            // リンクの大きさ
		'a_style_decoration'       => 'none',            // リンクの装飾
		'a_style_color'            => '#000000',         // リンクの色
		'other_option'             => FALSE,              // 見出し行の追加情報設定（TRUE=有、FALSE=無）
		'td_colspan'               => 2,                 // 見出し行セルの結合数
		'td_padding_left'          => '0px',             // セルの左位置
		'td_border_collapse'       => 'collapse',        // セルの境目設定
		'td_border'                => NULL,              // セルの境界線　なしの場合NULL
		'table_th_width'           => '365px',           // 項目名テーブル幅
		'table_th_height'          => NULL,              // 項目名テーブル高さ
		'title_th_border_bottom'   => '1px solid #ffebcd',  // 項目名の下線設定
		'title_th_border'          => '1px solid #000000',  // 項目名の境界線設定　なしの場合NULL
		'title_th_height'          => '15px',               // 項目名の高さ
		'title_th_text-align'      => 'left',               // 項目名のそろえ位置
		'title_th_width'           => array('30px','83px','83px','83px','74px'),  // 項目名の幅
		'title_th_bakcolor'        => '#FFFF99',                                  // 項目名の背景色
		'title_th_padding'         => '3px',                                      // 項目名の位置設定
		'div_existence'            => FALSE,                                      // スクロール設定（TRUE=有、FALSE=無）
		'div_style_margin'         => 0,                                          // テーブル内容のスクロール設定用DIVタグ、"margin"指定（基本固定
		'div_style_height'         => '100px',                                    // テーブル内容のスクロール設定用DIVタグ、高さ
		'div_style_width'          => '365px',                                    // テーブル内容のスクロール設定用DIVタグ、幅
		'div_style_overflow'       => 'auto',                                     // スクロール属性
		'div_style_bakcolor'       => '#FFFFFF',                                  // セルの背景色
		'div_td_height'            => '20px',                                     // 項目内容高さ
		'div_td_width'             => array('30px','83px','83px','83px','74px'),  // 項目内容幅
		'div_td_align'             => array('left','left','left','left','center'),// 項目内用そろえ位置(必須)
		'div_td_border'            => '3px solid #000000 border-collapse:separate;',                        // 項目内容の境界線設定　なしの場合NULL
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE,FALSE,FALSE),       // リンク表示
		'div_td_input_type'        => array('checkbox',NULL,NULL,NULL,'button'),   // inputタイプ
		'div_td_name'              => array('kshbn',NULL,NULL,NULL,'button')   // 各セルのname
	);
	// 部署コード選択画面
	$config['s_busyo_table'] = array(
		'heading'                  => '【部署名選択】', // 見出しタイトル
		'table_width'              => '900px',           // 見出しテーブル幅
		'table_height'             => NULL,              // 見出しテーブル高さ
		'max_span'                 => 2,                 // １行における最大表示セット数
		'span'                     => 3,                 // 項目数
		'heading_tr_height'        => '21px',            // 行の高さ
		'heading_th_style'         => 'text-align:left', // 見出しタイトルそろえ位置
		'a_style_border'           => 'none',            // リンクの下線
		'a_style_font_size'        => '10pt',            // リンクの大きさ
		'a_style_decoration'       => 'none',            // リンクの装飾
		'a_style_color'            => '#000000',         // リンクの色
		'other_option'             => FALSE,             // 見出し行の追加情報設定（TRUE=有、FALSE=無）
		'td_colspan'               => 3,                 // 見出し行セルの結合数
		'td_padding_left'          => '0px',             // セルの左位置
		'td_border_collapse'       => 'collapse',        // セルの境目設定
		'td_border'                => NULL,              // セルの境界線　なしの場合NULL
		'table_th_width'           => '365px',           // 項目名テーブル幅
		'table_th_height'          => NULL,              // 項目名テーブル高さ
		'title_th_border_bottom'   => '1px solid #000000',  // 項目名の下線設定
		'title_th_border'          => '1px solid #000000',  // 項目名の境界線設定　なしの場合NULL
		'title_th_height'          => '15px',               // 項目名の高さ
		'title_th_text-align'      => 'left',               // 項目名のそろえ位置
		'title_th_width'           => array('30px','299px','74px'),  // 項目名の幅
		'title_th_bakcolor'        => '#FFFF99',                                  // 項目名の背景色
		'title_th_padding'         => '3px',                                      // 項目名の位置設定
		'div_existence'            => FALSE,                                      // スクロール設定（TRUE=有、FALSE=無）
		'div_style_margin'         => 0,                                          // テーブル内容のスクロール設定用DIVタグ、"margin"指定（基本固定
		'div_style_height'         => '100px',                                    // テーブル内容のスクロール設定用DIVタグ、高さ
		'div_style_width'          => '365px',                                    // テーブル内容のスクロール設定用DIVタグ、幅
		'div_style_overflow'       => 'auto',                                     // スクロール属性
		'div_style_bakcolor'       => '#FFFFFF',                                  // セルの背景色
		'div_td_height'            => '20px',                                     // 項目内容高さ
		'div_td_width'             => array('30px','299px','74px'),  // 項目内容幅
		'div_td_align'             => array('left','left','center'),// 項目内用そろえ位置(必須)
		'div_td_border'            => '1px solid #000000',                        // 項目内容の境界線設定　なしの場合NULL
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE),       // リンク表示
		'div_td_input_type'        => array('checkbox',NULL,'button'),   // inputタイプ
		'div_td_name'              => array('bucd',NULL,'button')   // 各セルのname
	);
	
		// 部署コード選択画面
	$config['s_ka_table'] = array(
		'heading'                  => '【ユニット名選択】', // 見出しタイトル
		'table_width'              => '900px',           // 見出しテーブル幅
		'table_height'             => NULL,              // 見出しテーブル高さ
		'max_span'                 => 2,                 // １行における最大表示セット数
		'span'                     => 3,                 // 項目数
		'heading_tr_height'        => '21px',            // 行の高さ
		'heading_th_style'         => 'text-align:left', // 見出しタイトルそろえ位置
		'a_style_border'           => 'none',            // リンクの下線
		'a_style_font_size'        => '10pt',            // リンクの大きさ
		'a_style_decoration'       => 'none',            // リンクの装飾
		'a_style_color'            => '#000000',         // リンクの色
		'other_option'             => FALSE,             // 見出し行の追加情報設定（TRUE=有、FALSE=無）
		'td_colspan'               => 3,                 // 見出し行セルの結合数
		'td_padding_left'          => '0px',             // セルの左位置
		'td_border_collapse'       => 'collapse',        // セルの境目設定
		'td_border'                => NULL,              // セルの境界線　なしの場合NULL
		'table_th_width'           => '365px',           // 項目名テーブル幅
		'table_th_height'          => NULL,              // 項目名テーブル高さ
		'title_th_border_bottom'   => '1px solid #000000',  // 項目名の下線設定
		'title_th_border'          => '1px solid #000000',  // 項目名の境界線設定　なしの場合NULL
		'title_th_height'          => '15px',               // 項目名の高さ
		'title_th_text-align'      => 'left',               // 項目名のそろえ位置
		'title_th_width'           => array('30px','299px','74px'),  // 項目名の幅
		'title_th_bakcolor'        => '#FFFF99',                                  // 項目名の背景色
		'title_th_padding'         => '3px',                                      // 項目名の位置設定
		'div_existence'            => FALSE,                                      // スクロール設定（TRUE=有、FALSE=無）
		'div_style_margin'         => 0,                                          // テーブル内容のスクロール設定用DIVタグ、"margin"指定（基本固定
		'div_style_height'         => '100px',                                    // テーブル内容のスクロール設定用DIVタグ、高さ
		'div_style_width'          => '365px',                                    // テーブル内容のスクロール設定用DIVタグ、幅
		'div_style_overflow'       => 'auto',                                     // スクロール属性
		'div_style_bakcolor'       => '#FFFFFF',                                  // セルの背景色
		'div_td_height'            => '20px',                                     // 項目内容高さ
		'div_td_width'             => array('30px','299px','74px'),  // 項目内容幅
		'div_td_align'             => array('left','left','center'),// 項目内用そろえ位置(必須)
		'div_td_border'            => '1px solid #000000',                        // 項目内容の境界線設定　なしの場合NULL
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE),       // リンク表示
		'div_td_input_type'        => array('checkbox',NULL,'button'),   // inputタイプ
		'div_td_name'              => array('kacd',NULL,'button')   // 各セルのname
	);

	// グループコード選択画面
	$config['s_group_table'] = array(
		'heading'                  => '【グループコード選択】', // 見出しタイトル
		'table_width'              => '600px',           // 見出しテーブル幅
		'table_height'             => NULL,              // 見出しテーブル高さ
		'max_span'                 => 3,                 // １行における最大表示セット数
		'span'                     => 3,                 // 項目数
		'heading_tr_height'        => '21px',            // 行の高さ
		'heading_th_style'         => 'text-align:left', // 見出しタイトルそろえ位置
		'a_style_border'           => 'none',            // リンクの下線
		'a_style_font_size'        => '10pt',            // リンクの大きさ
		'a_style_decoration'       => 'none',            // リンクの装飾
		'a_style_color'            => '#000000',         // リンクの色
		'other_option'             => FALSE,             // 見出し行の追加情報設定（TRUE=有、FALSE=無）
		'td_colspan'               => 3,                 // 見出し行セルの結合数
		'td_padding_left'          => '0px',             // セルの左位置
		'td_border_collapse'       => 'collapse',        // セルの境目設定
		'td_border'                => NULL,              // セルの境界線　なしの場合NULL
		'table_th_width'           => '242px',           // 項目名テーブル幅
		'table_th_height'          => NULL,              // 項目名テーブル高さ
		'title_th_border_bottom'   => '1px solid #000000',  // 項目名の下線設定
		'title_th_border'          => '1px solid #000000',  // 項目名の境界線設定　なしの場合NULL
		'title_th_height'          => '15px',               // 項目名の高さ
		'title_th_text-align'      => 'left',               // 項目名のそろえ位置
		'title_th_width'           => array('30px','125px','74px'),                // 項目名の幅
		'title_th_bakcolor'        => '#FFFF99',                                  // 項目名の背景色
		'title_th_padding'         => '3px',                                      // 項目名の位置設定
		'div_existence'            => FALSE,                                      // スクロール設定（TRUE=有、FALSE=無）
		'div_style_margin'         => 0,                                          // テーブル内容のスクロール設定用DIVタグ、"margin"指定（基本固定
		'div_style_height'         => '100px',                                    // テーブル内容のスクロール設定用DIVタグ、高さ
		'div_style_width'          => '242px',                                    // テーブル内容のスクロール設定用DIVタグ、幅
		'div_style_overflow'       => 'auto',                                     // スクロール属性
		'div_style_bakcolor'       => '#FFFFFF',                                  // セルの背景色
		'div_td_height'            => '20px',                                     // 項目内容高さ
		'div_td_width'             => array('30px','125px','74px'),                // 項目内容幅
		'div_td_align'             => array('left','left','center'),              // 項目内用そろえ位置(必須)
		'div_td_border'            => '1px solid #000000',                        // 項目内容の境界線設定　なしの場合NULL
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE),                   // リンク表示
		'div_td_input_type'        => array('checkbox',NULL,'button'),            // inputタイプ
		'div_td_name'              => array('grpcd',NULL,'button')                 // 各セルのname
	);

	// 確認者選択画面
	$config['s_companions_table'] = array(
		'heading'                  => '【同行者】',       // 見出しタイトル
		'table_width'              => '730px',           // 見出しテーブル幅
		'table_height'             => NULL,              // 見出しテーブル高さ
		'max_span'                 => 2,                 // １行における最大表示セット数
		'span'                     => 5,                 // 項目数
		'heading_tr_height'        => '21px',            // 行の高さ
		'heading_th_style'         => 'text-align:left', // 見出しタイトルそろえ位置
		'a_style_border'           => 'none',            // リンクの下線
		'a_style_font_size'        => '10pt',            // リンクの大きさ
		'a_style_decoration'       => 'none',            // リンクの装飾
		'a_style_color'            => '#000000',         // リンクの色
		'other_option'             => FALSE,              // 見出し行の追加情報設定（TRUE=有、FALSE=無）
		'td_colspan'               => 2,                 // 見出し行セルの結合数
		'td_padding_left'          => '0px',             // セルの左位置
		'td_border_collapse'       => 'collapse',        // セルの境目設定
		'td_border'                => NULL,              // セルの境界線　なしの場合NULL
		'table_th_width'           => '365px',           // 項目名テーブル幅
		'table_th_height'          => NULL,              // 項目名テーブル高さ
		'title_th_border_bottom'   => '1px solid #000000',  // 項目名の下線設定
		'title_th_border'          => '1px solid #000000',  // 項目名の境界線設定　なしの場合NULL
		'title_th_height'          => '15px',               // 項目名の高さ
		'title_th_text-align'      => 'left',               // 項目名のそろえ位置
		'title_th_width'           => array('30px','83px','83px','83px','74px'),  // 項目名の幅
		'title_th_bakcolor'        => '#FFFF99',                                  // 項目名の背景色
		'title_th_padding'         => '3px',                                      // 項目名の位置設定
		'div_existence'            => FALSE,                                      // スクロール設定（TRUE=有、FALSE=無）
		'div_style_margin'         => 0,                                          // テーブル内容のスクロール設定用DIVタグ、"margin"指定（基本固定
		'div_style_height'         => '100px',                                    // テーブル内容のスクロール設定用DIVタグ、高さ
		'div_style_width'          => '365px',                                    // テーブル内容のスクロール設定用DIVタグ、幅
		'div_style_overflow'       => 'auto',                                     // スクロール属性
		'div_style_bakcolor'       => '#FFFFFF',                                  // セルの背景色
		'div_td_height'            => '20px',                                     // 項目内容高さ
		'div_td_width'             => array('30px','83px','83px','83px','74px'),  // 項目内容幅
		'div_td_align'             => array('left','left','left','left','center'),// 項目内用そろえ位置(必須)
		'div_td_border'            => '1px solid #000000',                        // 項目内容の境界線設定　なしの場合NULL
		'div_td_a_existence'       => array(FALSE,FALSE,FALSE,FALSE,FALSE),       // リンク表示
		'div_td_input_type'        => array('checkbox',NULL,NULL,NULL,'button'),   // inputタイプ
		'div_td_name'              => array('kshbn',NULL,NULL,NULL,'button')   // 各セルのname
	);
	// -------------------------------------------------------------------------
	// タイトル項目
	// -------------------------------------------------------------------------
	$config['c_todo_title']          = array("期限","内容","重要度",'終了確認');                                         // ToDoタイトル名
	$config['c_memo_title']          = array("日付","件名","入手先名");                                                  // 情報メモタイトル名
	$config['c_read_report_title_a']   = array("日付","担当者名","状態","コメント");                                        // ユニット長日報閲覧状況（管理者）タイトル名
	$config['c_read_report_title_g']   = array("日付","指示者名","状態","コメント");                                        // ユニット長日報閲覧状況（部下）タイトル名
	$config['c_result_report_title'] = array("日付","担当者","状態");                                          // 受取日報タイトル名
	$config['c_schedule_title']      = array("時刻","担当者名","場所");                                                  // 部下のスケジュールタイトル名
	$config['c_info_title']          = array("Informaion");                                                              // Informaionタイトル名
	$config['c_nego_history_title']  = array("日付","コード","相手先社名","商談内容(最初の40文字)");  // 商談履歴検索結果一覧タイトル名
	
	$config['s_checker_title']       = array("","氏名","部署",'課・ユニット',"検索");                                     // 確認者タイトル名
	$config['s_busyo_title']         = array("","部署名","検索");                                            // 部署コード選択タイトル名
	$config['s_ka_title']         = array("","ユニット名","検索");                                            // ユニットコード選択タイトル名
	$config['s_group_title']         = array("","グループ名","検索");                                                    // グループ選択タイトル名
	$config['s_companions_title']    = array(NULL,"同行者名",NULL);                                                    // グループ選択タイトル名

	// -------------------------------------------------------------------------
	// リンク項目
	// -------------------------------------------------------------------------
	$config['c_todo_link']          = array('heading_link' => 'index.php/todo/index','link' => 'plan');                 // ToDo用リンク設定
	$config['c_memo_link']          = array('heading_link' => 'index.php/data_memo/index','link' => 'index.php/data_memo/index'); // 情報メモ用リンク設定
	$config['c_read_report_link']   = array('heading_link' => '#','link' => 'index.php/result/index');                            // ユニット長日報閲覧状況リンク設定
	$config['c_result_report_link'] = array('heading_link' => '#','link' => '#');                             // 受取日報用リンク設定
	$config['c_schedule_link']      = array('heading_link' => NULL,'link' => NULL);                           // 部下のスケジュール用リンク設定
	$config['c_info_link']          = array('heading_link' => NULL,'link' => NULL);                           // Informaion用リンク設定
	$config['c_nego_history_link']  = array('heading_link' => NULL,'link' => 'index.php/result/index');		  // 商談履歴検索結果一覧用リンク設定
	$config['s_checker_link']       = array('heading_link' => NULL,'link' => NULL);                           // 確認者選択画面用リンク設定
	$config['s_busyo_link']         = array('heading_link' => NULL,'link' => NULL);                           // 部署選択画面用リンク設定
	$config['s_group_link']         = array('heading_link' => NULL,'link' => NULL);                           // グループ選択画面用リンク設定
	$config['s_cheker_search_link'] = array('heading_link' => NULL,'link' => NULL);                           // 確認者検索画面用リンク設定
	$config['s_doko_search_link'] = array('heading_link' => NULL,'link' => NULL);                           // 確認者検索画面用リンク設定

	// -------------------------------------------------------------------------
	// 追加項目
	// -------------------------------------------------------------------------
	// ToDo用ボタン設定
	$config['c_todo_button'] = array(
		'td_style'        => 'text-align:right',
		'type'            => 'button',
		'style_height'    => '18px',
		'style_font_size' => '10px',
		'value'           => '更新'
	);
	// 確認者選択用ボタン設定
	$config['s_select_checker_button'] = array(
		'td_style'        => 'text-align:center',
		'type'            => 'button',
		'style_height'    => '17px',
		'style_font_size' => '10px',
		'name'            => 'checker_button',
		'value'           => '検索',
		'onclick_type'    => MY_WINDOW_OPEN,            // onclick時のアクション
		'open_call'       => 'index.php/checker_search_conf', // 呼び出し先
		'window_name'     => '確認者検索',           // ポップアップウインドウ名
		'scrollbars'      => 'scrollbars=no',       // スクロールの有無
		'open_width'      => 'width=600',           // 呼び出しウインドウ幅
		'open_height'     => 'height=450',          // 呼び出しウインドウ高さ
		'space_align'     => 'center'               // 表示位置
	);
	// 部署コード選択用ボタン設定
	$config['s_select_busyo_button'] = array(
		'td_style'        => 'text-align:center',
		'type'            => 'button',
		'style_height'    => '17px',
		'style_font_size' => '10px',
		'name'            => 'busyo_button',
		'value'           => '検索',
		'onclick_type'    => MY_WINDOW_OPEN,            // onclick時のアクション
		'open_call'       => 'index.php/checker_search_unit', // 呼び出し先
		'window_name'     => '部署検索',             // ポップアップウインドウ名
		'scrollbars'      => 'scrollbars=no',       // スクロールの有無
		'open_width'      => 'width=600',           // 呼び出しウインドウ幅
		'open_height'     => 'height=450',          // 呼び出しウインドウ高さ
		'space_align'     => 'center'               // 表示位置
	);
		// 部署コード選択用ボタン設定
	$config['s_select_ka_button'] = array(
		'td_style'        => 'text-align:center',
		'type'            => 'button',
		'style_height'    => '17px',
		'style_font_size' => '10px',
		'name'            => 'busyo_button',
		'value'           => '検索',
		'onclick_type'    => MY_WINDOW_OPEN,            // onclick時のアクション
		'open_call'       => 'index.php/checker_search_ka', // 呼び出し先
		'window_name'     => 'ユニット検索',             // ポップアップウインドウ名
		'scrollbars'      => 'scrollbars=no',       // スクロールの有無
		'open_width'      => 'width=600',           // 呼び出しウインドウ幅
		'open_height'     => 'height=450',          // 呼び出しウインドウ高さ
		'space_align'     => 'center'               // 表示位置
	);
	// グループ選択用ボタン設定
	$config['s_select_group_button'] = array(
		'td_style'        => 'text-align:center',
		'type'            => 'button',
		'style_height'    => '17px',
		'style_font_size' => '10px',
		'name'            => 'group_button',
		'value'           => '検索',
		'onclick_type'    => MY_WINDOW_OPEN,            // onclick時のアクション
		'open_call'       => 'index.php/checker_search_group',  // 呼び出し先
		'window_name'     => 'グループ検索',           // ポップアップウインドウ名
		'scrollbars'      => 'scrollbars=no',         // スクロールの有無
		'open_width'      => 'width=600',             // 呼び出しウインドウ幅
		'open_height'     => 'height=450',            // 呼び出しウインドウ高さ
		'space_align'     => 'center'                 // 表示位置
	);
	// 同行者選択用ボタン設定
	$config['s_select_companions_button'] = array(
		'td_style'        => 'text-align:center',
		'type'            => 'button',
		'style_height'    => '17px',
		'style_font_size' => '10px',
		'value'           => '検索',
		'onclick_type'    => 'location.href',           // onclick時のアクション
		'open_call'       => 'index.php/checker_search_group',  // 呼び出し先
		'window_name'     => '同行者検索',           // ポップアップウインドウ名
		'scrollbars'      => 'scrollbars=no',         // スクロールの有無
		'open_width'      => 'width=600',             // 呼び出しウインドウ幅
		'open_height'     => 'height=450',            // 呼び出しウインドウ高さ
		'space_align'     => 'center'                 // 表示位置
	);

	// -------------------------------------------------------------------------
	// 見出しと外枠テーブル項目説明
	// -------------------------------------------------------------------------
/*
	$config['c_nego_search_data'] = array(
		'heading'               => '【相手先選択】',           // 見出し名
		'heading_tr_height'     => '20px',                    // 見出しの高さ
		'heading_td_style'      => 'text-align:left',         // 見出しの位置
		'table_width'           => '600px',                   // テーブル幅
		'table_height'          => '20px',                    // テーブル高さ
		'table_border'          => '1px solid #000000',       // 外枠表示
		'table_padding'         => 'padding:5px;',            // テーブル全体位置
		'td_style'              => 'padding-left:5px;',       // 項目名の位置
		'td_table_width'        => '494px',                   // 項目内容の幅
		'td_table_height'       => '170px'                    // 項目内容の高さ
	);
 */
	// -------------------------------------------------------------------------
	// 商談履歴検索項目
	// -------------------------------------------------------------------------
	$config['c_nego_search_data'] = array(
		'heading'               => '',
		'heading_tr_height'     => '20px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '600px',
		'table_height'          => '20px',
		'table_border'          => '1px solid #000000',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => '100px',
		'td_table_width'        => '494px',
		'td_table_height'       => NULL
	);
	// -------------------------------------------------------------------------
	// 確認者検索画面項目
	// -------------------------------------------------------------------------
	// 部署検索
	$config['s_checker_search_b_data'] = array(
		'heading'               => '【部署から検索】',
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '30px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => '80px',
		'td_table_width'        => '170px',
		'td_table_height'       => NULL
	);
	// 氏名検索
	$config['s_checker_search_n_data'] = array(
		'heading'               => '【氏名から検索】',
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '30px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "80px",
		'td_table_width'        => "170px",
		'td_table_height'       => "55px"
	);
	// 確認者氏名
	$config['s_checker_name_data'] = array(
		'heading'               => '確認者氏名',
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '20px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:2px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "0",
		'td_table_width'        => "170px",
		'td_table_height'       => "90px"
	);
	// -------------------------------------------------------------------------
	// 部署検索画面項目
	// -------------------------------------------------------------------------
	// 部署検索
	$config['s_busyo_search_data'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '30px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => '80px',
		'td_table_width'        => '170px',
		'td_table_height'       => NULL
	);
	// 部署名
	$config['s_busyo_name_data'] = array(
		'heading'               => '部署名',
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '20px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:2px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "0",
		'td_table_width'        => "170px",
		'td_table_height'       => "90px"
	);
	
	// ユニット名
	$config['s_unit_name_data'] = array(
		'heading'               => 'ユニット名',
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '20px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:2px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "0",
		'td_table_width'        => "170px",
		'td_table_height'       => "90px"
	);
	// -------------------------------------------------------------------------
	// グループ検索画面項目
	// -------------------------------------------------------------------------
	// グループ検索
	$config['s_group_name_data'] = array(
		'heading'               => 'グループ名',
		'heading_tr_height'     => '15px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '250px',
		'table_height'          => '20px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:2px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "0",
		'td_table_width'        => "170px",
		'td_table_height'       => "90px"
	);
	// -------------------------------------------------------------------------
	// ボックス内テーブル項目説明
	// -------------------------------------------------------------------------
/*
	$config['c_nego_search_title'] = array("商談日","相手先","商談実施者","商談内容");	              // 左端に表示する項目名

	$config['c_nego_search_date'] = array(
		'row'               => 1,                                                                 // 内容テーブル数
		'span'              => 7,                                                                 // 内容テーブル内のセル数
		'vertical-align'    => "middle",                                                          // 項目名の縦表示位置
		'contents_td_width' => array('60px','70px','70px','20px','60px','70px','70px'),           // 内容テーブル内のセル幅
		'td_text_align'     => array(NULL,NULL,NULL,NULL,NULL,NULL,NULL),                         // 内容テーブル内の横位置
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE,FALSE,FALSE,FALSE),                  // 内容テーブル内のrowspanフラグ
		'td_rowspan'        => 1,                                                                 // 内容テーブル内のrowspan値
		'td_form_type'      => array("text","select","select","from","text","select","select"),   // 内容テーブル内のセル毎のフォーム種類
		'td_form_attribute' => array("text","month","day",NULL,"text","month","day"),             // 内容テーブル内の各フォームの種類
		'td_form_name'      => array("s_year","s_month","s_day",NULL,"e_year","e_month","e_day"), // 内容テーブル内の各フォーム名
		'textbox_size'      => array(4,NULL,NULL,NULL,4,NULL,NULL),                               // テキストボックスのサイズ（あれば設定）
		'text_maxlength'    => array(4,NULL,NULL,NULL,4,NULL,NULL)                                // テキストボックスの最大入力数（あれば設定）
	);
 */

	// -------------------------------------------------------------------------
	// 商談履歴の項目設定
	// -------------------------------------------------------------------------
	// 項目名
	$config['c_nego_search_title'] = array("商談日","相手先","商談内容","");
	// 商談日内容設定値
	$config['c_nego_search_date'] = array(
		'row'               => 1,
		'span'              => 7,
		'vertical-align'    => "middle",
		'contents_td_width' => array('80px','65px','65px','20px','80px','65px','65px'),
		'td_text_align'     => array(NULL,NULL,NULL,NULL,NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE,FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select","select","select","from","select","select","select"),
		'td_form_attribute' => array("year","month","day",NULL,"year","month","day"),
		'td_form_name'      => array("s_year","s_month","s_day",NULL,"e_year","e_month","e_day"),
		'td_form_required'  => array(TRUE,TRUE,TRUE,NULL,TRUE,TRUE,TRUE),
		'textbox_size'      => array(4,NULL,NULL,NULL,4,NULL,NULL),
		'text_maxlength'    => array(4,NULL,NULL,NULL,4,NULL,NULL)
	);
	// 相手先内容設定値
	$config['c_nego_search_client'] = array(
		'row'               => 2,
		'span'              => 1,
		'vertical-align'    => "top",
		'contents_td_width' => array('90px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("checkbox"),
		'td_form_attribute' => array(NULL),
		'td_form_name'      => array("shop_main")
	);
	// 商談内容設定値
	$config['c_nego_search_contents'] = array(
		'row'               => 1,
		'span'              => 2,
		'vertical-align'    => "middle",
		'contents_td_width' => array('248px','246px'),
		'td_text_align'     => array(NULL,"right"),
		'td_rowspan_flg'    => array(FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text","button"),
		'td_form_attribute' => array("text","button"),
		'td_form_name'      => array("nego_contents","output"),
		'textbox_size'      => array(20,NULL),
		'text_maxlength'    => array(10,NULL)
	);
	//
	$config['c_nego_search_contents_info'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('248px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('※入力した"文字を含む"で検索'),
		'td_form_attribute' => array(NULL),
		'td_form_name'      => array(NULL),
	);
	// チェックボックス設定
	$config['c_nego_search_check'] = array(
		array("name" => "shop_main","title" => "販売店本部"),
		array("name" => "agency","title" => "代理店")
	);
	// ボタン設定
	$config['c_nego_search_button'] = array(
		array(
			"type" => "submit",
			"name" => "out_put",
			"value" => "検索"
			)
	);
	// 仮相手先テーブル項目　asakura　ここから
	// -------------------------------------------------------------------------
	$config['c_kari_client'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '0px',
		'heading_td_style'      => 'text-align:center',
		'table_width'           => '400px',
		'table_height'          => '0px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "90px",
		'td_table_width'        => "310px"
	);
	// -------------------------------------------------------------------------
	// 仮相手先の項目設定
	// -------------------------------------------------------------------------
	// 項目名
	$config['c_kari_client_title'] = array("仮相手先名","仮相手先カナ","登録社番","住所","電話","ＦＡＸ","相手先担当部署","相手先担当者名","区分","重要度");

	// -----------------------登録画面------------------------
	// 仮相手先名・仮相手先コード
	$config['s_kari_cname_add'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => "middle",
		'contents_td_width' => array('350px','100px','200px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text","仮相手先コード","readonly"),
		'td_form_attribute' => array("text","label","text"),
		'td_form_name'      => array("kari_cname",NULL,"kari_ccode"),
		'textbox_size'      => array(20,NULL,8),
		'text_maxlength'    => array(20,NULL,8)
	);
	// -----------------------変更・削除共通項目------------------------
	// 仮相手先名・仮相手先コード
	$config['s_kari_cname_updel'] = array(
		'row'               => 1,
		'span'              => 4,
		'vertical-align'    => "middle",
		'contents_td_width' => array('150px','200px','100px','200px'),
		'td_text_align'     => array(NULL,NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text","button","仮相手先コード","readonly"),
		'td_form_attribute' => array("text","button","label","text"),
		'td_form_name'      => array("kari_cname","select",NULL,"kari_ccode"),
		'textbox_size'      => array(20,NULL,NULL,8),
		'text_maxlength'    => array(20,NULL,NULL,8)
	);
	// ---------------------共通項目--------------------------
	// 仮相手先カナ
	$config['s_kari_kana'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("kari_kana"),
		'textbox_size'      => array(40),
		'text_maxlength'    => array(40)
	);
	// 登録社番
	$config['s_edit_no'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("edit_no"),
		'textbox_size'      => array(5),
		'text_maxlength'    => array(5)
	);
	// 住所
	$config['s_address'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("address"),
		'textbox_size'      => array(30),
		'text_maxlength'    => array(30)
	);
	// 電話
	$config['s_tel'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("tel"),
		'textbox_size'      => array(12),
		'text_maxlength'    => array(12)
	);
	// FAX
	$config['s_fax'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("fax"),
		'textbox_size'      => array(12),
		'text_maxlength'    => array(12)
	);
	// 相手先担当部署
	$config['s_client_busyo'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("client_busyo"),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 相手先担当者名
	$config['s_client_name'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("client_name"),
		'textbox_size'      => array(8),
		'text_maxlength'    => array(8)
	);
	// 区分
	$config['s_kbn'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('130px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("drop"),
		'td_form_attribute' => array("kubun"),
		'td_form_name'      => array("kbn"),
		'textbox_size'      => array(NULL),
		'text_maxlength'    => array(NULL)
	);

	// 重要度
	$config['c_important_update'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('50px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select"),
		'td_form_attribute' => array("important"),
		'td_form_name'      => array("important")
	);

	// -----------------------削除画面------------------------
	// 仮相手先名・仮相手先コード
	$config['c_kari_cname_delete'] = array(
		'row'               => 1,
		'span'              => 4,
		'vertical-align'    => "middle",
		'contents_td_width' => array('150px','200px','100px','200px'),
		'td_text_align'     => array(NULL,NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text","button","仮相手先コード","text"),
		'td_form_attribute' => array("text","button","label","text"),
		'td_form_name'      => array("kari_cname","select",NULL,"kari_ccode"),
		'textbox_size'      => array(20,NULL,NULL,8),
		'text_maxlength'    => array(20,NULL,NULL,8)
	);
	// 仮相手先カナ
	$config['c_kari_kana_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("kari_kana"),
		'textbox_size'      => array(40),
		'text_maxlength'    => array(40)
	);
	// 登録社番
	$config['c_edit_no_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("edit_no"),
		'textbox_size'      => array(5),
		'text_maxlength'    => array(5)
	);
	// 住所
	$config['c_address_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("address"),
		'textbox_size'      => array(30),
		'text_maxlength'    => array(30)
	);
	// 電話
	$config['c_tel_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("tel"),
		'textbox_size'      => array(12),
		'text_maxlength'    => array(2)
	);
	// FAX
	$config['c_fax_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("fax"),
		'textbox_size'      => array(12),
		'text_maxlength'    => array(12)
	);
	// 相手先担当部署
	$config['c_client_busyo_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("client_busyo"),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 相手先担当者名
	$config['c_client_name_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("client_name"),
		'textbox_size'      => array(8),
		'text_maxlength'    => array(8)
	);

	// 重要度
	$config['s_important'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('50px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select"),
		'td_form_attribute' => array("important"),
		'td_form_name'      => array("important")
	);
	// ボタン設定
	$config['s_kari_client_button'] = array(
		array(
			"type" => "button",
			"name" => "select",
			"value" => " 選択 "
			)
	);
	// 確認者選択用ボタン設定
	/*$config['s_kari_client_button'] = array(
		'td_style'        => 'text-align:center',
		'type'            => 'button',
		'style_height'    => '17px',
		'style_font_size' => '10px',
		'value'           => '選択',
		'onclick_type'    => 'location.href',                // onclick時のアクション
		'open_call'       => 'index.php/checker_search_conf' // 呼び出し先
	);*/

	// -------------------------------------------------------------------------
	// メッセージテーブル項目
	// -------------------------------------------------------------------------
	$config['c_allmessage_data'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '0px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '400px',
		'table_height'          => '0px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => "90px",
		'td_table_width'        => "250px"
	);

	// -------------------------------------------------------------------------
	// メッセージの項目設定
	// -------------------------------------------------------------------------
	// 項目名
	$config['c_allmessage_title'] = array("通知開始日","通知終了日","コメント");
	$config['c_allmessage_sys_all_title'] = array("当システム内全員");

	// -----------------------登録画面------------------------
	// メッセージ
	$config['c_s_date'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => "middle",
		'contents_td_width' => array('80px','80px','80px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select","select","select"),
		'td_form_attribute' => array("year","month","day"),
		'td_form_name'      => array("s_year","s_month","s_day"),
		'td_form_required'  => array(TRUE,TRUE,TRUE),
		'textbox_size'      => array(4,NULL,NULL),
		'text_maxlength'    => array(4,NULL,NULL),
		'extra'				=> array(' class="" title="通知開始日" ',' class="" title="通知開始日" ',' class="" title="通知開始日" ')
	);
	$config['c_e_date'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => "middle",
		'contents_td_width' => array('80px','80px','80px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select","select","select"),
		'td_form_attribute' => array("year","month","day"),
		'td_form_name'      => array("e_year","e_month","e_day"),
		'td_form_required'  => array(TRUE,TRUE,TRUE),
		'textbox_size'      => array(4,NULL,NULL),
		'text_maxlength'    => array(4,NULL,NULL),
		'extra'				=> array(' class="" title="通知終了日" ',' class="" title="通知終了日" ',' class="" title="通知終了日" ')
	);
	$config['c_comment'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('60px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("comment"),
		'textbox_size'      => array(60),
		'text_maxlength'    => array(NULL),
		'extra'				=> ' class="required" title="コメント" '
	);
		$config['c_sys_all'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('60px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("checkbox"),
		'td_form_attribute' => array(NULL),
		'td_form_name'      => array("sys_all")
	);
	// 添付ファイル
	$config['c_allmessage_file_title'] = array("添付ファイル");
	// 添付ファイル
	$config['c_allmessage_file'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('500px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("file"),
		'td_form_attribute' => array("file"),
		'td_form_name'      => array("file"),
		'textbox_size'      => array(250),
		'text_maxlength'    => array(256)
	);

	//asakuraここまで
	// -------------------------------------------------------------------------
	// ユーザー管理テーブル項目
	// -------------------------------------------------------------------------
	$config['c_user_data'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '0px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '400px',
		'table_height'          => '0px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => '90px',
		'td_table_width'        => '310px',
		'td_table_height'       => NULL
	);
	// -------------------------------------------------------------------------
	// ユーザー管理の項目設定
	// -------------------------------------------------------------------------
	// 項目名
	$config['c_user_shbn_title'] = array("社番");
	$config['c_user_name_title'] = array("氏名");
	$config['c_user_busyo_title'] = array("本部コード","部コード","課コード");
	$config['c_user_kbn_title'] = array("メニュー区分");
	$config['c_user_pass_title'] = array("パスワード");
	// 社番内容設定値
	$config['c_user_shbn'] = array(
		'row'               => 1,
		'span'              => 2,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('50px','250px'),
		'td_text_align'     => array(NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('text','button'),
		'td_form_attribute' => array('text','button'),
		'td_form_name'      => array('shbn','search'),
		'textbox_size'      => array(5,NULL),
		'text_maxlength'    => array(5,NULL)

	);
	// ボタン設定
	$config['c_user_search_button'] = array(
		array(
			'type' => 'submit',
			'name' => 'search',
			'value' => ' 検索 '
			)
	);
	// -----------------------登録画面------------------------
	// 氏名内容設定値
	$config['c_user_name_add'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('300px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label'),
		'td_form_attribute' => array('label'),
		'td_form_name'      => array('user_name')
	);
	// 本部内容設定値
	$config['c_user_honbu_add'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','100px','100px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label','本部名','label'),
		'td_form_attribute' => array('label',NULL,'label'),
		'td_form_name'      => array('honbucd',NULL,'honbunm')
	);
	// 部内容設定値
	$config['c_user_bu_add'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','100px','100px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label','部名','label'),
		'td_form_attribute' => array('label',NULL,'label'),
		'td_form_name'      => array('bucd',NULL,'bunm')
	);
	// 課内容設定値
	$config['c_user_ka_add'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','100px','100px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label',"課名",'label'),
		'td_form_attribute' => array('label',NULL,'label'),
		'td_form_name'      => array('kacd',NULL,'kanm')
	);
	// -----------------------更新画面------------------------
	// 氏名内容設定値
	$config['c_user_name_update'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('300px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('text'),
		'td_form_attribute' => array('text'),
		'td_form_name'      => array('user_name'),
		'textbox_size'      => array(10),
		'text_maxlength'    => array(256)
	);
	// 本部内容設定値
	$config['c_user_honbu_update'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','70px','130px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('text',"本部名",'text'),
		'td_form_attribute' => array('text',NULL,'text'),
		'td_form_name'      => array('honbucd',NULL,'honbunm'),
		'textbox_size'      => array(5,NULL,15),
		'text_maxlength'    => array(5,NULL,15)
	);
	// 部内容設定値
	$config['c_user_bu_update'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','70px','130px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('text','部名','text'),
		'td_form_attribute' => array('text',NULL,'text'),
		'td_form_name'      => array('bucd',NULL,'bunm'),
		'textbox_size'      => array(5,NULL,15),
		'text_maxlength'    => array(5,NULL,15)
	);
	// 課内容設定値
	$config['c_user_ka_update'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','70px','130px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('text',"課名",'text'),
		'td_form_attribute' => array('text',NULL,'text'),
		'td_form_name'      => array('kacd',NULL,'kanm'),
		'textbox_size'      => array(5,NULL,15),
		'text_maxlength'    => array(5,NULL,15)
	);
	// -----------------------削除画面------------------------
	// 氏名内容設定値
	$config['c_user_name_delete'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('300px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label'),
		'td_form_attribute' => array('label'),
		'td_form_name'      => array('shinnm'),
		'textbox_size'      => array(10),
		'text_maxlength'    => array(10)
	);
	// 本部内容設定値
	$config['c_user_honbu_delete'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','70px','130px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label',"本部名",'label'),
		'td_form_attribute' => array('label',NULL,'label'),
		'td_form_name'      => array('honbucd',NULL,'honbunm'),
		'textbox_size'      => array(5,NULL,15),
		'text_maxlength'    => array(5,NULL,15)
	);
	// 部内容設定値
	$config['c_user_bu_delete'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','70px','130px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label',"部名",'label'),
		'td_form_attribute' => array('label',NULL,'label'),
		'td_form_name'      => array('bucd',NULL,'bunm'),
		'textbox_size'      => array(5,NULL,15),
		'text_maxlength'    => array(5,NULL,15)
	);
	// 課内容設定値
	$config['c_user_ka_delete'] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px','70px','130px'),
		'td_text_align'     => array(NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('label',"課名",'label'),
		'td_form_attribute' => array('label',NULL,'label'),
		'td_form_name'      => array('kacd',NULL,'kanm'),
		'textbox_size'      => array(5,NULL,15),
		'text_maxlength'    => array(5,NULL,15)
	);
	// ------------------------共通----------------------------
	// メニュー区分内容設定値
	$config['c_user_kbn'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('kubun'),
		'td_form_name'      => array('menuhyjikbn'),
		'textbox_size'      => array(NULL),
		'text_maxlength'    => array(NULL),
		'extra'				=> ' class="menu_kbn" title="メニュー区分" '
	);
	// パスワード内容設定値
	$config['c_user_pass'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('200px'),
		'td_text_align'     => array(NULL),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('text'),
		'td_form_attribute' => array('password'),
		'td_form_name'      => array('passwd'),
		'textbox_size'      => array(NULL),
		'text_maxlength'    => array(10),
		'extra'				=> ' class="pw_length" title="パスワード" '
	);
	// -------------------------------------------------------------------------
	// 確認者検索の項目設定
	// -------------------------------------------------------------------------
	// 項目名
	$config['s_checker_search_b_title'] = array("本部","部","ユニット",NULL);
	$config['s_checker_search_n_title'] = array("氏名",NULL);
	$config['s_checker_list_title']     = array("space");
	// 確認者検索の本部項目
	$config['s_checker_search_honbu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('200px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_busyo'),
		'td_form_name'      => array('honbucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 確認者検索の部項目
	$config['s_checker_search_bu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_bu'),
		'td_form_name'      => array('bucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 確認者検索のユニット項目
	$config['s_checker_search_unit'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_unit'),
		'td_form_name'      => array('unitcd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
		// 確認者検索のユニット項目
	$config['s_checker_search_user'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_unit'),
		'td_form_name'      => array('shbn'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 確認者検索の氏名項目
	$config['s_checker_search_name'] = array(
		'row'               => 3,
		'span'              => 1,
		'vertical-align'    => 'top',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(TRUE),
		'td_rowspan'        => 3,
		'td_form_type'      => array('text'),
		'td_form_attribute' => array('text'),
		'td_form_name'      => array('name'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 確認者検索のボタン
	$config['s_checker_search_btn'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('50px'),
		'td_text_align'     => array('right'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('button'),
		'td_form_attribute' => array('button'),
		'td_form_name'      => array('search')
	);
	// 確認者検索の確認者氏名セレクトボックス
	$config['s_checker_search_select'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('200px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('select'),
		'td_form_attribute' => array('s_list_checker'),
		'td_form_name'      => array('select_name')
	);

	// 部署検索の部署名セレクトボックス
	$config['s_busyo_search_select'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('select'),
		'td_form_attribute' => array('s_list_busyo'),
		'td_form_name'      => array('select_name')
	);
	
		// 部署検索の部署名セレクトボックス
	$config['s_ka_search_select'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('select'),
		'td_form_attribute' => array('s_list_ka'),
		'td_form_name'      => array('select_name')
	);

	// 部署検索ボタン設定
	$config['s_checker_b_search_button'] = array(
		array(
			"type" => "submit",
			"name" => "b_search",
			"value" => "検索"
			)
	);
	// 氏名検索
	$config['s_checker_n_search_button'] = array(
		array(
			"type" => "submit",
			"name" => "n_search",
			"value" => "検索"
			)
	);
	// グループ検索のグループ名セレクトボックス
	$config['s_group_search_select'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('100px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('select'),
		'td_form_attribute' => array('s_list_group'),
		'td_form_name'      => array('select_name')
	);
	// -------------------------------------------------------------------------
	// 情報メモの項目設定
	// -------------------------------------------------------------------------
	$config['c_data_memo'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '0px',
		'heading_td_style'      => 'text-align:left;',
		'table_width'           => '480px',
		'table_height'          => '0px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:0px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => '150px',
		'td_table_width'        => '480px',
		'td_table_height'       => NULL
	);
	$config['c_data_memo_search'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '0px',
		'heading_td_style'      => 'text-align:left',
		'table_width'           => '600px',
		'table_height'          => '0px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:0px;',
		'td_style'              => 'padding-left:5px;',
		'td_title_width'        => '150px',
		'td_table_width'        => '600px',
		'td_table_height'       => NULL
	);
	$config['c_data_memo_scv'] = array(
		'heading'               => NULL,
		'heading_tr_height'     => '0px',
		'heading_td_style'      => 'text-align:left;',
		'table_width'           => '',
		'table_height'          => '0px',
		'table_border'          => 'none',
		'table_padding'         => '',
		'td_style'              => '',
		'td_title_width'        => '50px',
		'td_table_width'        => '',
		'td_table_height'       => NULL
	);
	// 項目名
	$config['s_busyo_search_title'] = array("本部","部",NULL);
	$config['s_ka_search_title'] = array("本部","部","ユニット",NULL);
	$config['s_busyo_search_title_memo'] = array("●本部","●部","●ユニット","●入力者");
	$config['s_busyo_search_title_memo_csv'] = array("本部","部");
	$config['s_busyo_search_title_memo_csv_2'] = array("本部","部","ユニット","入力者");
	// 情報メモ
	$config['c_data_memo_kname_title']		= array("●件名<font color='#ff0000'>※必須</font>");
	$config['c_data_memo_kname_title_s']		= array("●件名");
	$config['c_data_memo_office_title']		= array("&emsp;社名", "&emsp;役職", "&emsp;氏名");
	$config['c_data_memo_kbn_title']		= array("●情報区分","●対象区分");
	$config['c_data_memo_maker_title']		= array("●メーカー");
	$config['c_data_memo_file_title']		= array("●添付ファイル");
	$config['c_data_memo_date_title']		= array("●掲載期限<font color='#ff0000'>※必須</font>");
	$config['c_data_memo_date_from_title']	= array("●期間");
	$config['c_data_memo_date_from_title_s']	= array("●期間<font color='#ff0000'>※必須</font>");
	$config['c_data_memo_info_title']		= array("●情報メモ内容<font color='#ff0000'>※必須</font>");
	$config['c_data_memo_info_title_s']		= array("●情報メモ内容");
	$config['c_data_memo_search_title']		= array("日付","件名", "入手先",  "ユニット長閲覧状況");
	$config['c_data_memo_search_s_title']	= array("日付","件名", "ユニット","入力者" ,"入手先",  "ユニット長閲覧状況");
	$config['c_data_memo_kbn_type_title']	= "●品種区分";
	$config['c_data_memo_kbn_maker_title']	= "●メーカー";
	$config['c_data_memo_date_search_title'] = array("");

	// 件名(登録)
	$config["c_data_memo_kname"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('300px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("k_name"),
		'textbox_size'      => array(40),
		'text_maxlength'    => array(256),
		'extra'				=> ' class="required" title="件名" '
	);
	// 件名(更新・削除)
	$config["c_data_memo_kname_ud"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('300px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("knnm"),
		'textbox_size'      => array(40),
		'text_maxlength'    => array(256),
		'extra'				=> ' class="required" title="件名" '
	);
	// 検索ボタン設定
	$config['c_data_memo_search_button'] = array(
		array(
			"type" => "submit",
			"name" => "search",
			"value" => " 検索 "
			)
	);
	// 社名
	$config["c_data_memo_office_name"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('150px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("aitesknm"),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(256),
		'extra'				=> ' autocomplete="off" '
	);
	// 役職
	$config["c_data_memo_office_job"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('150px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("yksyoku"),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(256)
	);
	// 氏名
	$config["c_data_memo_office_u_name"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('150px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("name"),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(256)
	);
	// 情報区分
	$config["c_data_memo_kbn_data"] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => "middle",
		'contents_td_width' => array('90px','80px','80px'),
		'td_text_align'     => array('left','left','left'),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("drop","type_kbn","drop2"),
		'td_form_attribute' => array("kubun",NULL,"kubun"),
		'td_form_name'      => array("data_kbn",NULL,"type_kbn"),
		'textbox_size'      => array(15,15,15),
		'text_maxlength'    => array(15,15,15)
	);

	// 品種区分
	$config["c_data_memo_kbn_type"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('170px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("drop"),
		'td_form_attribute' => array("kubun"),
		'td_form_name'      => array("type_kbn"),
		'textbox_size'      => array(15),
		'text_maxlength'    => array(15)
	);

	// 対象区分
	$config["c_data_memo_kbn_target"] = array(
		'row'               => 1,
		'span'              => 3,
		'vertical-align'    => "middle",
		'contents_td_width' => array('90px','80px','80px'),
		'td_text_align'     => array('left','left','left'),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("drop","maker_kbn","drop2"),
		'td_form_attribute' => array("kubun",NULL,"kubun"),
		'td_form_name'      => array("target_kbn",NULL,"maker"),
		'textbox_size'      => array(15,15,15),
		'text_maxlength'    => array(15,15,15)
	);
	// メーカー名
	$config["c_data_memo_maker"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('170px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("drop"),
		'td_form_attribute' => array("kubun"),
		'td_form_name'      => array("maker"),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 添付ファイル
	$config["c_data_memo_file"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('170px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("file"),
		'td_form_attribute' => array("temp_files"),
		'td_form_name'      => array("temp_files"),
		'textbox_size'      => array(NULL),
		'text_maxlength'    => array(256),
		'extra'				=> '  '
	);
	// 情報メモ内容
	$config["c_data_memo_info"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('300px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("textarea"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("info"),
		'textbox_size'      => array(NULL),
		'text_maxlength'    => array(NULL),
		'extra'				=> ' class="required" title="情報メモ内容" '
	);
	// 情報メモ内容(検索）
	$config["c_data_memo_info_search"] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => "middle",
		'contents_td_width' => array('600px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("text"),
		'td_form_attribute' => array("text"),
		'td_form_name'      => array("info"),
		'textbox_size'      => array(70),
		'text_maxlength'    => array(NULL),
	);
		//掲載期限
		$config['c_data_memo_date'] = array(
		'row'               => 1,
		'span'              => 4,
		'vertical-align'    => "middle",
		'contents_td_width' => array('80px','70px','70px','160px'),
		'td_text_align'     => array(NULL,NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select","select","select",NULL),
		'td_form_attribute' => array("year","month","day",NULL),
		'td_form_name'      => array("s_year","s_month","s_day",NULL),
		'textbox_size'      => array(4,2,2,NULL),
		'text_maxlength'    => array(4,2,2,NULL),
		'td_form_required'  => array(TRUE,TRUE,TRUE,NULL),
		'extra'				=> array(' class="checkdate_year checkdate_to check_notpastdate_1 check_year" title="掲載期限" ',' class="checkdate_month checkdate_to check_notpastdate_1 check_month" title="掲載期限" ',' class="checkdate_daycheck checkdate_to check_notpastdate_1 check_date" title="" ','掲載期限')

	);
	//期間
		$config['c_data_memo_date_from'] = array(
		'row'               => 1,
		'span'              => 7,
		'vertical-align'    => "middle",
		'contents_td_width' => array('80px','80px','80px','20px','80px','80px','80px'),
		'td_text_align'     => array(NULL,NULL,NULL,NULL,NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE,FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select","select","select","from","select","select","select"),
		'td_form_attribute' => array("year","month","day",NULL,"year","month","day"),
		'td_form_name'      => array("s_year","s_month","s_day",NULL,"e_year","e_month","e_day"),
		'td_form_required'  => array(FALSE,FALSE,FALSE,NULL,FALSE,FALSE,FALSE),
		'textbox_size'      => array(4,2,2,NULL,4,2,2),
		'text_maxlength'    => array(4,2,2,NULL,4,2,2),
		'extra'				=> array(' class="checkdate_year" title="年" ',' class="checkdate_month" title="月" ',' class="checkdate_daycheck" title="期間"',NULL,' class="checkdate_year" title="年" ',' class="checkdate_month" title="月" ',' class="checkdate_daycheck" title="期間"')
	);

	//期間
		$config['c_data_memo_date_from_s'] = array(
		'row'               => 1,
		'span'              => 7,
		'vertical-align'    => "middle",
		'contents_td_width' => array('80px','80px','80px','20px','80px','80px','80px'),
		'td_text_align'     => array(NULL,NULL,NULL,NULL,NULL,NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE,FALSE,FALSE,FALSE,FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array("select","select","select","from","select","select","select"),
		'td_form_attribute' => array("year","month","day",NULL,"year","month","day"),
		'td_form_name'      => array("s_year","s_month","s_day",NULL,"e_year","e_month","e_day"),
		'td_form_required'  => array(TRUE,TRUE,TRUE,NULL,TRUE,TRUE,TRUE),
		'textbox_size'      => array(4,2,2,NULL,4,2,2),
		'text_maxlength'    => array(4,2,2,NULL,4,2,2),
		'extra'				=> array(' class="checkdate_year" title="年" ',' class="checkdate_month" title="月" ',' class="checkdate_daycheck" title="期間"',NULL,' class="checkdate_year" title="年" ',' class="checkdate_month" title="月" ',' class="checkdate_daycheck" title="期間"')
	);

	//検索ボタン
		$config['c_data_memo_date_search'] = array(
		'row'               => 1,
		'span'              => 2,
		'vertical-align'    => "middle",
		'contents_td_width' => array('75px','100px'),
		'td_text_align'     => array(NULL,NULL),
		'td_rowspan_flg'    => array(FALSE,FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array(NULL,"button"),
		'td_form_attribute' => array(NULL,"button"),
		'td_form_name'      => array(NULL,"search"),
		'textbox_size'      => array(NULL,NULL),
		'text_maxlength'    => array(NULL,NULL)
	);

	// -------------------------------------------------------------------------
	// ラベル項目設定
	// -------------------------------------------------------------------------
	// 活動区分ラベル
	$config['s_label_kubun'] = array(
		'value'    => '活動区分',
		'for_name' => 'katsudo_kubun'
	);
	// フリーラベル
	$config['s_label_free'] = array(
		'value'    => '',
		'for_name' => ''
	);

	// -------------------------------------------------------------------------
	// フィールドセット項目設定
	// -------------------------------------------------------------------------
	// 予定画面
	$config['s_field_plan'] = array(
		'id' => '',
		'class' => '',
		'align' => 'none',
		'style' => 'border:0px'
	);


	// -------------------------------------------------------------------------
	// ボタン項目設定
	// -------------------------------------------------------------------------
	// 表示ボタン
	$config['s_button_view'] = array(
		'name'    => 'do_action',
		'value'   => 'view',
		'type'    => 'submit',
		'content' => '表示'
	);
	// 削除ボタン
	$config['s_button_del'] = array(
		'name'    => 'do_action',
		'value'   => 'delete',
		'type'    => 'submit',
		'content' => '削除'
	);
	// 移動ボタン
	$config['s_button_move'] = array(
		'name'    => 'do_action',
		'value'   => 'move',
		'type'    => 'submit',
		'content' => '移動'
	);
	// コピーボタン
	$config['s_button_copy'] = array(
		'name'    => 'do_action',
		'value'   => 'copy',
		'type'    => 'submit',
		'content' => 'コピー'
	);
	// 選択ボタン
	$config['s_button_select'] = array(
		'name'    => 'do_action',
		'value'   => 'select',
		'type'    => 'submit',
		'content' => '選択'
	);


	// -------------------------------------------------------------------------
	// ドロップダウンリスト項目設定
	// -------------------------------------------------------------------------
	// 汎用ドロップダウンリスト
	$config['s_general_purpose'] = array(
		'title_name' => '',
		'name' => '',
		'data' => '',
		'check' => '000'
	);

	// 年選択用ドロップダウンリスト
	$config['s_select_year'] = array(
		'title_name' => '年',
		'name' => 'year',
		'data' => array(
			'2012' => '2012',
			'2013' => '2013',
			'2014' => '2014',
			'2015' => '2015',
			'2016' => '2016',
			'2017' => '2017',
			'2018' => '2018',
			'2019' => '2019',
			'2020' => '2020',
			'2021' => '2021',
			'2022' => '2022'
		),
		'check' => '0'
	);

	$config['s_year'] = array(
			''    => '',
			'2012' => '2012',
			'2013' => '2013',
			'2014' => '2014',
			'2015' => '2015',
			'2016' => '2016',
			'2017' => '2017',
			'2018' => '2018',
			'2019' => '2019',
			'2020' => '2020',
			'2021' => '2021',
			'2022' => '2022'
	);

	// 月選択用ドロップダウンリスト
	$config['s_select_month'] = array(
		'title_name' => '月',
		'name' => 'month',
		'data' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
			'11' => '11',
			'12' => '12'
		),
		'check' => '0'
	);
	// 日選択用ドロップダウンリスト
	$config['s_select_day'] = array(
		'title_name' => '日',
		'name' => 'day',
		'data' => array(
			'1' => '1',   '2' => '2',   '3' => '3',   '4' => '4',   '5' => '5',
			'6' => '6',   '7' => '7',   '8' => '8',   '9' => '9',   '10' => '10',
			'11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15',
			'16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20',
			'21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25',
			'26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30',
			'31' => '31'
		),
		'check' => '0'
	);

		// 年選択用ドロップダウンリスト
	$config['s_todo_select_year'] = array(
		'title_name' => '年',
		'name' => 'year',
		'data' => array(
			'2012' => '2012',
			'2013' => '2013',
			'2014' => '2014',
			'2015' => '2015',
			'2016' => '2016',
			'2017' => '2017',
			'2018' => '2018',
			'2019' => '2019',
			'2020' => '2020',
			'2021' => '2021',
			'2022' => '2022'
		),
		'check' => '0',
		'extra' => 'class="" title="期限" '
	);

	// 月選択用ドロップダウンリスト
	$config['s_todo_select_month'] = array(
		'title_name' => '月',
		'name' => 'month',
		'data' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
			'11' => '11',
			'12' => '12'
		),
		'check' => '0',
		'extra' => 'class="" title="月" '
	);
	// 日選択用ドロップダウンリスト
	$config['s_todo_select_day'] = array(
		'title_name' => '日',
		'name' => 'day',
		'data' => array(
			'1' => '1',   '2' => '2',   '3' => '3',   '4' => '4',   '5' => '5',
			'6' => '6',   '7' => '7',   '8' => '8',   '9' => '9',   '10' => '10',
			'11' => '11', '12' => '12', '13' => '13', '14' => '14', '15' => '15',
			'16' => '16', '17' => '17', '18' => '18', '19' => '19', '20' => '20',
			'21' => '21', '22' => '22', '23' => '23', '24' => '24', '25' => '25',
			'26' => '26', '27' => '27', '28' => '28', '29' => '29', '30' => '30',
			'31' => '31'
		),
		'check' => '0',
		'extra' => 'class="" title="期限"  '
	);

	// 活動区分ドロップダウンリスト
	$config['s_select_action_type'] = array(
		'title_name' => '',
		'name' => 'action_type',
		'data' => array(
			'non'    => '',
			'honbu'  => '販売店本部',
			'tenpo'  => '店舗',
			'dairi'  => '代理店',
			'office' => '内勤'
		),
		'check' => 'non'
	);
	// 本部場所ドロップダウンリスト
	$config['s_select_honbu_location'] = array(
		'title_name' => '',
		'name' => 'honbu_location',
		'data' => array(
			'0'    => '',
			'1'  => '販売店本部',
			'2'  => '店舗',
			'3'  => '代理店',
			'4'   => '当社',
			'5'  => 'その他'
		),
		'check' => 'non'
	);
	// 本部区分ドロップダウンリスト
	$config['s_select_honbu_kubun'] = array(
		'title_name' => '',
		'name' => 'honbu_kubun',
		'data' => array(
			'0' => '',
			'1' => 'S',
			'2' => 'A',
			'3' => 'B',
			'4' => 'C'
		),
		'check' => '0'
	);
	// 店舗区分ドロップダウンリスト
	$config['s_select_tenpo_kubun'] = array(
		'title_name' => '',
		'name' => 'tenpo_kubun',
		'data' => array(
			'0' => '',
			'1' => 'フォロー店舗',
			'2' => 'それ以外'
		),
		'check' => '0'
	);
	// 代理店区分ドロップダウンリスト
	$config['s_select_dairi_kubun'] = array(
		'title_name' => '',
		'name' => 'dairi_kubun',
		'data' => array(
			'000' => '',
			'001' => '代理店',
			'002' => '契約店'
		),
		'check' => '0'
	);
	// 代理店ランクドロップダウンリスト
	$config['s_select_dairi_rank'] = array(
		'title_name' => '',
		'name' => 'dairi_rank',
		'data' => array(
			'000' => '',
			'001' => 'A',
			'002' => 'B'
		),
		'check' => '0'
	);
	// 内勤作業内容ドロップダウンリスト
	$config['s_select_office_work'] = array(
		'title_name' => '',
		'name' => 'office_work',
		'data' => array(
			'000' => '',
			'001' => 'SFA見直し',
			'002' => '商談資料作成',
			'003' => '棚割作成',
			'004' => '得意先以来資料作成',
			'005' => '情報メモ入力',
			'006' => '情報メモ閲覧',
			'007' => '社内資料作成（部・ユニット）',
			'008' => '社内資料作成（本部）',
			'009' => '社内資料作成（事業部）',
			'010' => 'POS分析',
			'011' => '値引',
			'012' => 'MDリベート・ERP処理',
			'013' => '販売提案書作成',
			'014' => '日報入力',
			'015' => '個別面談（部長）',
			'016' => '個別面談（ユニット長）',
			'017' => '受注調整',
			'018' => '環境整備',
			'019' => 'その他'
		),
		'check' => '0'
	);
	// 情報メモ・情報区分ドロップダウンリスト
	$config['s_select_jyouhou_kubun'] = array(
		'title_name' => '',
		'name' => 'memo_jyouhou',
		'data' => array(
			'000' => '情報区分テスト用１',
			'001' => '情報区分テスト用２',
			'002' => '情報区分テスト用３'
		),
		'check' => '000'
	);
	// 情報メモ・品種区分ドロップダウンリスト
	$config['s_select_hinsyu_kubun'] = array(
		'title_name' => '',
		'name' => 'memo_hinsyu',
		'data' => array(
			'000' => '品種区分テスト用１',
			'001' => '品種区分テスト用２',
			'002' => '品種区分テスト用３'
		),
		'check' => '000'
	);
	// 情報メモ・対象区分ドロップダウンリスト
	$config['s_select_taisyou_kubun'] = array(
		'title_name' => '',
		'name' => 'memo_taisyou',
		'data' => array(
			'000' => '対象区分テスト用１',
			'001' => '対象区分テスト用２',
			'002' => '対象区分テスト用３'
		),
		'check' => '000'
	);
	// 情報メモ・対象区分ドロップダウンリスト
	$config['s_select_work_title'] = array(
		'title_name' => '',
		'name' => 'work_title',
		'data' => array(
			'000' => '',
			'001' => '成約',
			'002' => '不成約（終了）',
			'003' => '不成約（再商談）'
		),
		'check' => '000'
	);
	// 重点商品・アウト展開状況ドロップダウンリスト
	$config['s_select_out_situation'] = array(
		'title_name' => '',
		'name' => 'out_situation',
		'data' => array(
			'000' => '',
			'001' => '＋WATER　BOX　1-5P',
			'002' => 'NBTR',
			'003' => 'エルフォーレ',
			'004' => 'フラワープリント',
			'005' => 'テープ',
			'006' => 'パンツ',
			'007' => 'うす型パンツ',
			'008' => '夜一枚安心パッド',
			'009' => 'ウルトラガードDX14枚',
			'010' => '昼用バンドル22×2',
			'011' => 'Megamiやわらかスリム　26枚',
			'012' => '除菌アルコール本体・詰替・携帯',
			'013' => 'トイレクリーナー詰替',
			'014' => 'おしりふき3P・6P'
		),
		'check' => '000'
	);
	// 重点商品・アウト展開場所ドロップダウンリスト
	$config['s_select_out_location'] = array(
		'title_name' => '',
		'name' => 'out_location',
		'data' => array(
			'000' => '',
			'001' => '',
			'002' => '',
			'003' => ''
		),
		'check' => '000'
	);
	// ユーザ管理メニュー区分ドロップダウンリスト
	$config['s_drop_menu_kbn'] = array(
		'title_name' => '',
		'name' => 'menuhyjikbn',
		'data' => array(
			'000' => '',
			'001' => '',
			'002' => ''
		),
		'check' => '000'
	);
	// 仮相手先情報区分ドロップダウンリスト
	$config['s_drop_kari_kbn'] = array(
		'title_name' => '',
		'kbnid' => '011',
		'tag_name' => 'kbn',
		'check' => '000'
	);

	// -------------------------------------------------------------------------
	// リストボックス項目設定
	// -------------------------------------------------------------------------
	// リストボックス確認者氏名
	$config['s_list_checker'] = array(
		'title_name' => '',
		'name' => 'checker_list',
		'size' => '15',
		'width' => '345px',
		'data' => array(),
		'check' => '0'
	);
	// リストボックス部署名
	$config['s_list_busyo'] = array(
		'title_name' => '',
		'name' => 'busyo_list',
		'size' => '5',
		'width' => '245px',
		'data' => array(),
		'check' => '0'
	);
	// リストボックス部署名
	$config['s_list_ka'] = array(
		'title_name' => '',
		'name' => 'busyo_list',
		'size' => '15',
		'width' => '245px',
		'data' => array(),
		'check' => '0'
	);
	// リストボックスグループ名
	$config['s_list_group'] = array(
		'title_name' => '',
		'name' => 'group_list',
		'size' => '20',
		'width' => '245px',
		'data' => array(),
		'check' => '0'
	);

	// -------------------------------------------------------------------------
	// 行動予定項目設定
	// -------------------------------------------------------------------------
	// 行動予定（月次商談）
	$config['s_getsuji_syoudan'] = array(
		'data' => array(
			'0' => '見積り提示',
			'1' => '採用企画の確認',
			'2' => '販売計画',
			'3' => 'クレーム対応',
			'4' => '売場提案',
			'5' => '予備１',
			'6' => '予備２',
			'7' => '予備３',
			'8' => '予備４',
			'9' => '予備５'
		),
		'count_right' => '5',
		'count_left' => '0'
	);
	// 行動予定（半期提案）
	$config['s_hanki_teian'] = array(
		'data' => array(
			'0' => '商品案内',
			'1' => '導入提案',
			'2' => 'MD提案',
			'3' => '棚割提案',
			'4' => '販売店の棚割日情報',
			'5' => '導入日の詰め',
			'6' => '棚割結果確認',
			'7' => '予備１',
			'8' => '予備２',
			'9' => '予備３',
			'10' => '予備４',
			'11' => '予備５'
		),
		'count_right' => '6',
		'count_left' => '0'
	);
	// 行動予定（店舗商談）
	$config['s_tenpo_syoudan'] = array(
		'data' => array(
			'0' => '情報収集',
			'1' => '商品情報案内',
			'2' => '展開場所・ｱｳﾄ展開交渉',
			'3' => '推奨販売交渉',
			'4' => '受注促進',
			'5' => '予備１',
			'6' => '予備２',
			'7' => '予備３',
			'8' => '予備４',
			'9' => '予備５'
		),
		'count_right' => '5',
		'count_left' => '0'
	);
	// 行動予定（店内作業）
	$config['s_tennai_work'] = array(
		'data' => array(
			'0' => '売場撮影',
			'1' => '売場メンテナンス',
			'2' => '在庫確認',
			'3' => '商品補充',
			'4' => '販促物の設置',
			'5' => '山積み',
			'6' => 'ベタ付け',
			'7' => '予備１',
			'8' => '予備２',
			'9' => '予備３',
			'10' => '予備４',
			'11' => '予備５'
		),
		'count_right' => '6',
		'count_left' => '0'
	);
	// 行動予定（代理店・一般）
	$config['s_dairi_ippan'] = array(
		'data' => array(
			'0' => '一般店見積り提示・商談フォロー',
			'1' => '商品案内',
			'2' => '企画案内',
			'3' => '実績報告（経理・配荷）',
			'4' => '予備１',
			'5' => '予備２',
			'6' => '予備３',
			'7' => '予備４',
			'8' => '予備５'
		),
		'count_right' => '4',
		'count_left' => '0'
	);
	// 行動予定（管理販売）
	$config['s_kanri_hanbai'] = array(
		'data' => array(
			'0' => '見積り提示',
			'1' => '販売店商談事前打合せ',
			'2' => '情報収集・企画導入状況確認',
			'3' => '予備１',
			'4' => '予備２',
			'5' => '予備３',
			'6' => '予備４',
			'7' => '予備５'
		),
		'count_right' => '3',
		'count_left' => '0'
	);
	// 行動予定（管理その他）
	$config['s_kanri_other'] = array(
		'data' => array(
			'0' => '受発注・物流関連',
			'1' => '取組会議'
		),
		'count_right' => '2',
		'count_left' => '0'
	);
	$config['s_button_link'] = array(
		'aitesaki_name' => '/aaaaaaaa/bbb',
		'doukousya_name' => '/bbbbb/cccc'
	);
	// DoTo重要度
	$config['s_todo_impkbn'] = array(
		'1' => '低',
		'2' => '中',
		'3' => '高'
	);
	// ユニット長閲覧区分
	$config['s_reading_kubun'] = array(
		'0' => '未読',
		'1' => '既読'
	);
	// ユニット長コメント
	$config['s_comment_kubun'] = array(
		'0' => 'なし',
		'1' => 'あり'
	);

	// add goto 20120218
	// 相手先検索
	$config['search_head'] = array(
			'title'    => "相手先選択",
			'css'      => NULL,
			'image'    => 'images/select_client.gif',
			'msg'      => NULL,
			'errmsg'   => NULL,
			'btn_name' => " 決定 ",
			'form'     => "select_client"
	);
	// 相手先　本部用
	$config['search_head'] = array(
			'title'    => "相手先選択（本部）",
			'css'      => NULL,
			'image'    => 'images/search_headquarters.gif',
			'msg'      => NULL,
			'errmsg'   => NULL,
			'btn_name' => " 閉じる ",
			'form'     => "search_head",
			'form_name'=> 'child'
	);
	// 相手先　メーカー用
	$config['search_maker'] = array(
			'title'    => "相手先選択（メーカー）",
			'css'      => NULL,
			'image'    => 'images/search_manufacturer.gif',
			'msg'      => NULL,
			'errmsg'   => NULL,
			'btn_name' => " 閉じる ",
			'form'     => "search_maker",
			'form_name'=> 'child'
	);
	// 相手先　店舗用
	$config['search_shop'] = array(
			'title'    => "相手先選択（店舗一般）",
			'css'      => NULL,
			'image'    => 'images/search_shop.gif',
			'msg'      => NULL,
			'errmsg'   => NULL,
			'btn_name' => " 閉じる ",
			'form'     => "search_shop",
			'form_name'=> 'child'
	);
	// 相手先　代理店用
	$config['search_agency'] = array(
			'title'    => "相手先選択（代理店）",
			'css'      => NULL,
			'image'    => 'images/search_agency.gif',
			'msg'      => NULL,
			'errmsg'   => NULL,
			'btn_name' => " 閉じる ",
			'form'     => "search_agency",
			'form_name'=> 'child'
	);
	// 相手先
	$config[MY_SELECT_CLIENT_HEAD] = array(
				'title'    => "相手先選択",
				'css'      => NULL,
				'image'    => 'images/select_client.gif',
				'msg'      => NULL,
				'errmsg'   => NULL,
				'btn_name' => " 決定 ",
				'form'     => "search_head",
				'form_name'=> 'parent'
	);
	// 相手先
	$config[MY_SELECT_CLIENT_MAKER] = array(
					'title'    => "相手先選択",
					'css'      => NULL,
					'image'    => 'images/select_client.gif',
					'msg'      => NULL,
					'errmsg'   => NULL,
					'btn_name' => " 決定 ",
					'form'     => "search_maker",
					'form_name'=> 'parent'
	);
	// 相手先
	$config[MY_SELECT_CLIENT_SHOP] = array(
					'title'    => "相手先選択",
					'css'      => NULL,
					'image'    => 'images/select_client.gif',
					'msg'      => NULL,
					'errmsg'   => NULL,
					'btn_name' => " 決定 ",
					'form'     => "search_shop",
					'form_name'=> 'parent'
	);
	// 相手先
	$config[MY_SELECT_CLIENT_AGENCY] = array(
					'title'    => "相手先選択",
					'css'      => NULL,
					'image'    => 'images/select_client.gif',
					'msg'      => NULL,
					'errmsg'   => NULL,
					'btn_name' => " 決定 ",
					'form'     => "search_agency",
					'form_name'=> 'parent'
	);

	// 画面生成
	$config['s_item_visibility_s'] = array(
		'title'    => "画面生成",
		'css'      => 'css/item_visibility.css',
		'image'    => 'images/item_visibility.gif',
		'msg'      => NULL,
		'errmsg'   => NULL,
		'btn_name' => " 登録 ",
		'form'     => "add_item_visibility"
	);

	// 業務区分
	$config['s_business_type'] = array(
		array('id' => MY_USER_ACTIVITY_HEAD, 'name' => '本部'),
		array('id' => MY_USER_ACTIVITY_MAKER, 'name' => '店舗（メーカー）'),
		//array('id' => MY_USER_ACTIVITY_SHOP, 'name' => '店舗（一般店）'),
		array('id' => MY_USER_ACTIVITY_AGENCY, 'name' => '代理店')
	);

	// ToDo用ボタン設定
	$config['s_todo_add'] = array(
					'title'    => "TODO",
					'css'      => NULL,
					'image'    => 'images/todo.gif',
					'msg'      => NULL,
					'errmsg'   => NULL,
					'btn_name' => " 登録 ",
					'form'     => "todo"
	);
	$config['s_todo_update'] = array(
						'title'    => "TODO",
						'css'      => NULL,
						'image'    => 'images/todo.gif',
						'msg'      => NULL,
						'errmsg'   => NULL,
						'btn_name' => " 変更 ",
						'form'     => "update"
	);

	// -------------------------------------------------------------------------
	// 情報出力　商談履歴検索画面項目
	// -------------------------------------------------------------------------
	// 部署検索
	$config['output_csv_s_rireki_b_title'] = array("本部","部","ユニット");
	$config['output_csv_s_rireki_b_data'] = array(
		'heading'               => NULL,
		'table_width'           => '250px',
		'table_height'          => '30px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;border:none;',
		'td_title_width'        => '80px',
		'td_table_width'        => '170px',
		'td_table_height'       => NULL
	);
	// 情報出力　商談履歴の本部項目
	$config['output_csv_s_rireki_honbu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('250px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_busyo'),
		'td_form_name'      => array('honbucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 情報出力　商談履歴の部項目
	$config['output_csv_s_rireki_bu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_bu'),
		'td_form_name'      => array('bucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 情報出力　商談履歴のユニット項目
	$config['output_csv_s_rireki_unit'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_unit'),
		'td_form_name'      => array('kacd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 情報出力　商談履歴のユニット項目
	$config['output_csv_s_rireki_user'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_user'),
		'td_form_name'      => array('shbn'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);

	// -------------------------------------------------------------------------
	// 情報出力　日報一覧検索画面項目
	// -------------------------------------------------------------------------
	// 部署検索
	$config['output_csv_n_rireki_b_title'] = array("本部","部","ユニット","担当者");
	
	$config['output_csv_n_rireki_b_data'] = array(
		'heading'               => NULL,
		'table_width'           => '250px',
		'table_height'          => '30px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:5px;',
		'td_style'              => 'padding-left:5px;border:none;',
		'td_title_width'        => '80px',
		'td_table_width'        => '170px',
		'td_table_height'       => NULL
	);
	// 情報出力　日報一覧の本部項目
	$config['output_csv_n_rireki_honbu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('200px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_busyo'),
		'td_form_name'      => array('honbucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 情報出力　日報一覧の部項目
	$config['output_csv_n_rireki_bu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_bu'),
		'td_form_name'      => array('bucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 情報出力　日報一覧のユニット項目
	$config['output_csv_n_rireki_unit'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_unit'),
		'td_form_name'      => array('unitcd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
// 情報出力　日報一覧の担当者項目
	$config['output_csv_n_rireki_user'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_user'),
		'td_form_name'      => array('shbn'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);


	$config['output_csv_m_rireki_b_data'] = array(
		'heading'               => NULL,
		'table_width'           => '200px',
		'table_height'          => '30px',
		'table_border'          => 'none',
		'table_padding'         => 'padding:1px;',
		'td_style'              => 'padding-left:1px;border:none;',
		'td_title_width'        => '50px',

		'td_table_height'       => NULL
	);

// 情報出力　情報メモの本部項目
	$config['output_csv_m_rireki_honbu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('200px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_busyo'),
		'td_form_name'      => array('honbucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20),
		'extra'				=> ' style="margin-left: -20px; margin-top: -10px; margin-bottom: -10px; margin-right: -20px;"'
	);
	// 情報出力　日報一覧の部項目
	$config['output_csv_m_rireki_bu'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_bu'),
		'td_form_name'      => array('bucd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
	// 情報出力　日報一覧のユニット項目
	$config['output_csv_m_rireki_unit'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_unit'),
		'td_form_name'      => array('unitcd'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);
// 情報出力　日報一覧の担当者項目
	$config['output_csv_m_rireki_user'] = array(
		'row'               => 1,
		'span'              => 1,
		'vertical-align'    => 'middle',
		'contents_td_width' => array('60px'),
		'td_text_align'     => array('left'),
		'td_rowspan_flg'    => array(FALSE),
		'td_rowspan'        => 1,
		'td_form_type'      => array('drop'),
		'td_form_attribute' => array('s_select_user'),
		'td_form_name'      => array('shbn'),
		'textbox_size'      => array(20),
		'text_maxlength'    => array(20)
	);


/* End of file setting.php */
/* Location: ./application/config/setting.php */
