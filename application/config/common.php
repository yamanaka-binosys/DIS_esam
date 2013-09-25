<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * ヘッダ項目等、共通で使用する設定
 */
	// -------------------------------------------------------------------------
	// HTML共通項目
	// -------------------------------------------------------------------------
		// DOCTYPE設定
	$config['c_doctype'] = 'html4-strict';
	// metaタグ設定
	$config['c_meta'] = array(
		array(
			'name'    => 'Content-type',
			'content' => 'text/html; charset=UTF-8',
			'type'    => 'equiv'
		),
		array(
			'name'    => 'Content-Style-Type',
			'content' => 'text/css',
			'type'    => 'equiv'
		),
		array(
			'name'    => 'Content-Script-Type',
			'content' => 'text/javascript',
			'type'    => 'equiv'
		)
	);
	// メインCSS設定
	$config['c_main_css'] = array(
		'href' => 'css/main.css',
		'rel'  => 'stylesheet',
		'type' => 'text/css'
	);
	
	// -------------------------------------------------------------------------
	// ヘッダ部共通項目
	// -------------------------------------------------------------------------
	// ヘッダパターン
	$config['c_header'] = array(
		array(
			'name'        => 'aaa',
			'id'          => 'Header',
			'span'        => '2',
			'class_title' => 'title2',
			'class_right' => 'right2'
		),
		array(
			'name'         => 'bbb',
			'id'           => 'Header',
			'span'         => '2',
			'class_title'  => 'title2',
			'class_right'  => 'right2',
			'class_button' => 'btn2'
		),
		array(
			'name'        => 'ccc',
			'id'          => 'Header',
			'span'        => '2',
			'class_title' => 'title2',
			'class_right' => 'right2'
		)
	);
	
	// -------------------------------------------------------------------------
	// メニュー部共通項目
	// -------------------------------------------------------------------------
	// メニュー項目
	$config['c_menu_name'] = array(
		'id'        => 'LMenu',
		'name'      => 'メニュー',
		'class_ul'  => 'parent',
		'class_sub' => 'sub',
		'admin_no'  => '11',
		'window'    => array(
			'other_window' => array(
				'(別ｳｨﾝﾄﾞｳ)',
				'スケジュール',
				'日報実績',
				'商談履歴',
				'TO DO'
			),
			'main_window' => array(
				'トップ',
				'スケジュール',
				'日報実績',
				'商談履歴',
				'TO DO',
				'情報メモ',
				'メッセージ',
				'企画獲得',
				'仮相手先',
				'顧客処理',
				'情報出力',
				'ヘルプ'
			),
			'admin_window' => array(
				'システム',
				'ユーザー',
				'企画情報',
				'区分設定',
				'画面生成',
                '祝日設定'
			)
		)
	);
	// メニューリンク
	$config['c_menu_link'] = array(
		'other_window' => array(
			'',
			'index.php/calendar/plan',
			'index.php/calendar/result',
			'index.php/s_rireki/index',
			'index.php/todo/index'
		),
		'main_window' => array(
			'index.php/top/index',
			'index.php/calendar/plan',
			'index.php/calendar/result',
			'index.php/s_rireki/index',
			'index.php/todo/index',
			'index.php/data_memo/index',
			'index.php/allmessage/index',
			'index.php/project_possession/index',
			'index.php/interim_client/index',
			'index.php/client/index',
			'index.php/data_output/index',
			'index.php/help/index'
		),
		'admin_window' => array(
			'',
			'index.php/user/index',
			'index.php/project_item/index',
			'index.php/division/index',
			'index.php/item_visibility/index',
            'index.php/holiday_item/index'
		)
	);
	
	// -------------------------------------------------------------------------
	// その他共通項目
	// -------------------------------------------------------------------------
	// トップ画面に出力するカレンダー日数
	$config['c_top_calendar_day'] = 7;
	// 曜日（日本語）
//	$config['c_day_week'] = array("日","月","火","水","木","金","土");
	$config['c_day_week_ja'] = array("日","月","火","水","木","金","土");
	// 曜日（英語）
	$config['c_day_week_en'] = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
	// タブ(登録・変更・削除)
	$config['tab_list'] = array(
		"add" => array(
			"name"   => "tab_add",
			"id"     => "tab_add",
			"url"    => "/add_select_type",
			"img"    => "images/touroku.gif",
			"width"  => "140px",
			"height" => "20px" ,
			"border" => "border-style:none"
			),
		"update" => array(
			"name"   => "tab_update",
			"id"     => "tab_update",
			"url"    => "/update_select_type",
			"img"    => "images/henkou.gif",
			"width"  => "140px",
			"height" => "20px" ,
			"border" => "border-style:none"
		),
		"delete" => array(
			"name"   => "tab_delete",
			"id"     => "tab_delete",
			"url"    => "/delete_select_type",
			"img"    => "images/sakujyo.gif",
			"width"  => "140px",
			"height" => "20px" ,
			"border" => "border-style:none"
		)
	);
	// タブスタイル設定
	$config['c_tab_style'] = array(
		'ul_list_style'    => 'none',
		'ul_border_bottom' => '2px solid #333333',
		'ul_margin'        => '0',
		'ul_padding'       => '0',
		'ul_height'        => '1.5em',
		'li_float'         => 'left'
	);
	// タブ(登録・変更)
	$config['c_tab_list_au'] = array(
		'add' => array(
			'link' => 'add',
			'img'  => 'images/touroku.gif'
		),
		'update' => array(
			'link' => 'update',
			'img'  => 'images/henkou.gif'
		)
	);
	// タブ(登録・削除)
	$config['c_tab_list_ad'] = array(
		'add' => array(
			'link' => 'add',
			'img'  => 'images/touroku.gif'
		),
		'del' => array(
			'link' => 'delete',
			'img'  => 'images/sakujyo.gif'
		)
	);
	// タブ(登録)
	$config['c_tab_list_a'] = array(
		'add' => array(
			"name"   => "tab_add",
			"id"     => "tab_add",
			"url"    => "/add_select_type",
			"img"    => "images/henkou.gif",
			"width"  => "140px",
			"height" => "20px" ,
			"border" => "border-style:none"
		)
	);

	//////// aiba add 20120121 /////////////////////
	// タブ(登録・変更・削除)
	$config['c_tab_list_all'] = array(
		'add' => array(
			'link' => 'add',
			'img'  => 'images/touroku.gif'
		),
		'update' => array(
			'link' => 'update',
			'img'  => 'images/henkou.gif'
		),
		'delete' => array(
			'link' => 'delete',
			'img'  => 'images/sakujyo.gif'
		)
	);

	// タブ(登録・変更・削除)
	$config['c_tab_list_client'] = array(
		'kari' => array(
			'link' => 'kari',
			'img'  => 'images/kari_seishiki.gif'
		),
		'seishiki' => array(
			'link' => 'seishiki',
			'img'  => 'images/seichiki_seishiki.gif'
		),
		'mt_seishiki' => array(
			'link' => 'mt_seishiki',
			'img'  => 'images/seishiki_seishikifukki.gif'
		),
		'mt_kari' => array(
			'link' => 'mt_kari',
			'img'  => 'images/seishiki_karifukki.gif'
		)
	);
	///////////////////////////////////////////////

	// 月セレクトボックスの月数
	$config['max_year'] = 2022;
	// 月セレクトボックスの月数
	$config['num_month'] = 12;
	// 日セレクトボックスの最大日数
	$config['max_day'] = 31;
	// ランク
	$config['rank'] = array("S","A","B","C");
	// 活動区分
	$config['kubun'] = array("","販売店本部","店舗","代理店","内勤");
	
	// -------------------------------------------------------------------------
	// セレクトボックス共通項目
	// -------------------------------------------------------------------------
	// 月選択用セレクトボックス
	$config['c_select_month'] = array(
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

	// goto 20120219
	$config['c_pref'] = array('1'=>'北海道','2'=>'青森県','3'=>'岩手県','4'=>'宮城県','5'=>'秋田県','6'=>'山形県','7'=>'福島県','8'=>'茨城県','9'=>'栃木県','10'=>'群馬県','11'=>'埼玉県','12'=>'千葉県','13'=>'東京都','14'=>'神奈川県','15'=>'新潟県','16'=>'富山県','17'=>'石川県','18'=>'福井県','19'=>'山梨県','20'=>'長野県','21'=>'岐阜県','22'=>'静岡県','23'=>'愛知県','24'=>'三重県','25'=>'滋賀県','26'=>'京都府','27'=>'大阪府','28'=>'兵庫県','29'=>'奈良県','30'=>'和歌山県','31'=>'鳥取県','32'=>'島根県','33'=>'岡山県','34'=>'広島県','35'=>'山口県','36'=>'徳島県','37'=>'香川県','38'=>'愛媛県','39'=>'高知県','40'=>'福岡県','41'=>'佐賀県','42'=>'長崎県','43'=>'熊本県','44'=>'大分県','45'=>'宮崎県','46'=>'鹿児島県','47'=>'沖縄県');







/* End of file common.php */
/* Location: ./application/config/common.php */
