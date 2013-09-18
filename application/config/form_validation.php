<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * バリデーションルール設定
 */
	// -------------------------------------------------------------------------
	// ユーザー管理共通項目
	// -------------------------------------------------------------------------
    // 検索
	$config['validation_rules_search'] = array(
		array(
			'field' => 'shbn',
			'label' => '社番',
			'rules' => 'required|alpha_numeric'
			)
	);
    // 登録
	$config['validation_rules_add'] = array(
		array(
			'field' => 'shbn',
			'label' => '社番',
			'rules' => 'required'
			),
		array(
			'field' => 'menuhyjikbn',
			'label' => '区分メニュー',
			'rules' => 'required'
			),
		array(
			'field' => 'passwd',
			'label' => 'パスワード',
			'rules' => 'required'
			)
	);
    // 更新
	$config['validation_rules_update'] = array(
		array(
			'field' => 'shbn',
			'label' => '社番',
			'rules' => 'required|alpha_numeric'
			),
		array(
			'field' => 'shinnm',
			'label' => '氏名',
			'rules' => 'required'
			),
		array(
			'field' => 'menuhyjikbn',
			'label' => '区分メニュー',
			'rules' => 'required'
			),
		array(
			'field' => 'etukngen',
			'label' => '閲覧権限',
			'rules' => 'required|alpha_numeric'
			),
		array(
			'field' => 'passwd',
			'label' => 'パスワード',
			'rules' => 'required|alpha_numeric'
			),
	);
    // 削除
	$config['validation_rules_delete'] = array(
		array(
			'field' => 'shbn',
			'label' => '社番',
			'rules' => 'required|alpha_numeric'
			)
	);
	// -------------------------------------------------------------------------
	// 仮相手先共通項目 asakura
	// -------------------------------------------------------------------------
    // 検索
	$config['validation_rules_kari_client_search'] = array(
		array(
			'field' => 'kari_cname',
			'label' => '仮相手先名',
			'rules' => 'required'
			)
	);
    // 登録
	$config['validation_rules_kari_client_add'] = array(
		array(
			'field' => 'kari_cname',
			'label' => '仮相手先名',
			'rules' => 'required'
			)
	);
    // 更新
	$config['validation_rules_kari_client_update'] = array(
		array(
			'field' => 'kari_cname',
			'label' => '仮相手先名',
			'rules' => 'required'
			)
	);
    // 削除
	$config['validation_rules_kari_client_delete'] = array(
		array(
			'field' => 'kari_cname',
			'label' => '仮相手先名',
			'rules' => 'required'
			)
	);
	// -------------------------------------------------------------------------
	// 情報メモ
	// -------------------------------------------------------------------------
    // 登録
	$config['validation_rules_data_memo_add'] = array(
		array(
			'field' => 'knnm',
			'label' => '件名',
			'rules' => 'required'
			),
		array(
			'field' => 'info',
			'label' => '情報メモ内容',
			'rules' => 'required'
			),
		array(
			'field' => 's_year',
			'label' => '掲載期限（年）',
			'rules' => 'required'
			),
		array(
			'field' => 's_month',
			'label' => '掲載期限（月）',
			'rules' => 'required'
		),
		array(
			'field' => 's_day',
			'label' => '掲載期限（日）',
			'rules' => 'required'
		)
	);
	// 更新
	$config['validation_rules_data_memo_uqdate'] = array(
		array(
			'field' => 'knnm',
			'label' => '件名',
			'rules' => 'required'
			),
		array(
			'field' => 'info',
			'label' => '情報メモ内容',
			'rules' => 'required'
			),
		array(
			'field' => 's_year',
			'label' => '掲載期限（年）',
			'rules' => 'required'
			),
		array(
			'field' => 's_month',
			'label' => '掲載期限（月）',
			'rules' => 'required'
		),
		array(
			'field' => 's_day',
			'label' => '掲載期限（日）',
			'rules' => 'required'
		)
	);
    // 検索
	$config['validation_rules_data_memo_search'] = array(
		array(
			'field' => 's_year',
			'label' => '検索期間開始日（年）',
			'rules' => 'required'
			),
		array(
			'field' => 's_month',
			'label' => '検索期間開始日（月）',
			'rules' => 'required'
		),
		array(
			'field' => 's_day',
			'label' => '検索期間開始日（日）',
			'rules' => 'required'
		),
		array(
			'field' => 'e_year',
			'label' => '検索期間終了日（年）',
			'rules' => 'required'
		),
		array(
			'field' => 'e_month',
			'label' => '検索期間終了日（月）',
			'rules' => 'required'
		),
		array(
			'field' => 'e_day',
			'label' => '検索期間終了日（日）',
			'rules' => 'required'
		)
	);
	$config['validation_rules_data_memo_search_s'] = array(
		array(
				'field' => 's_year',
				'label' => '検索期間開始日（年）',
				'rules' => ''
				),
			array(
				'field' => 's_month',
				'label' => '検索期間開始日（月）',
				'rules' => ''
			),
			array(
				'field' => 's_day',
				'label' => '検索期間開始日（日）',
				'rules' => ''
			)
	);
	
	$config['validation_rules_data_memo_search_e'] = array(
		array(
			'field' => 'e_year',
			'label' => '検索期間終了日（年）',
			'rules' => ''
		),
		array(
			'field' => 'e_month',
			'label' => '検索期間終了日（月）',
			'rules' => ''
		),
		array(
			'field' => 'e_day',
			'label' => '検索期間終了日（日）',
			'rules' => ''
		)
	);
	// -------------------------------------------------------------------------
	// メッセージ
	// -------------------------------------------------------------------------
    // 登録
	$config['validation_rules_allmessage_add'] = array(
		array(
			'field' => 's_year',
			'label' => '通知開始日（年）',
			'rules' => 'required'
			),
		array(
			'field' => 'e_year',
			'label' => '通知終了日（年）',
			'rules' => 'required'
			),
		array(
			'field' => 'comment',
			'label' => 'コメント',
			'rules' => 'required'
			)
	);

	// -------------------------------------------------------------------------
	// 企画情報アイテム
	// -------------------------------------------------------------------------
    // 更新
	$config['validation_rules_project_item'] = array(
		array(
			'field' => 'add_line',
			'label' => '行',
			'rules' => 'numeric'
			)
	);

	// -------------------------------------------------------------------------
	// 正式相手処理
	// -------------------------------------------------------------------------
    // 更新
	$config['validation_rules_client'] = array(
		array(
			'field' => 's_code',
			'label' => '正式相手先コード',
			'rules' => 'required'
			),
		array(
			'field' => 's_name',
			'label' => '正式相手先名',
			'rules' => 'required'
			)
	);

	// -------------------------------------------------------------------------
	// 商談履歴検索項目
	// -------------------------------------------------------------------------
    // 検索
	$config['validation_rules_s_rireki_search'] = array(

	// フォームで管理するため不用
		array(
			'field' => 's_year',
			'label' => '商談日（開始）年',
			'rules' => 'required|numeric|min_length[4]|max_length[4]'
			),
		array(
			'field' => 's_month',
			'label' => '商談日（開始）月',
			'rules' => 'max_length[2]'
			),
		array(
			'field' => 's_day',
			'label' => '商談日（開始）日',
			'rules' => 'max_length[2]'
			),
		array(
			'field' => 'e_year',
			'label' => '商談日（終了）年',
			'rules' => 'required'
			),
		array(
			'field' => 'e_year',
			'label' => '商談日（終了）年',
			'rules' => 'max_length[4]'
			),
		array(
			'field' => 'e_month',
			'label' => '商談日（終了）月',
			'rules' => 'max_length[2]'
			),
		array(
			'field' => 'e_day',
			'label' => '商談日（終了）日',
			'rules' => 'max_length[2]'
			),
		array(
			'field' => 'shop_main',
			'label' => '販売店本部',
			'rules' => 'max_length[2]'  // 入力された場合は値がonだったので TODO
			),
		array(
			'field' => 'agency',
			'label' => '代理店',
			'rules' => 'max_length[2]' // 入力された場合は値がonだったので TODO
			),
		array(
			'field' => 'nego_contents',
			'label' => '商談内容',
			'rules' => 'max_length[10]'
			)
	);

	// -------------------------------------------------------------------------
	// ＴＯＤＯ画面
	// -------------------------------------------------------------------------
    // 登録
	$config['validation_rules_todo_add'] = array(
	/*
		array(
			'field' => 'code',
			'label' => '相手先コード',
			'rules' => 'alpha_numeric'
			),
		array(
			'field' => 'name',
			'label' => '相手先名',
			'rules' => 'required'
			),
			*/
/*		array(
			'field' => 'year',
			'label' => '期限（年）',
			'rules' => 'required|numeric'
		),
		array(
			'field' => 'month',
			'label' => '期限（月）',
			'rules' => 'required|numeric'
		),
		array(
			'field' => 'day',
			'label' => '期限（日）',
			'rules' => 'required|numeric'
		),*/
		array(
			'field' => 'todo',
			'label' => '内容',
			'rules' => 'required'
		)
	);
    // 更新
	$config['validation_rules_todo_update'] = array(
		array(
			'field' => 'year',
			'label' => '期限（年）',
			'rules' => 'required|numeric'
		),
		array(
			'field' => 'month',
			'label' => '期限（月）',
			'rules' => 'required|numeric'
		),
		array(
			'field' => 'day',
			'label' => '期限（日）',
			'rules' => 'required|numeric'
		),
		array(
			'field' => 'contents',
			'label' => '内容',
			'rules' => 'required'
		)
	);
	// 検索
	$config['validation_rules_todo_search'] = array(
	array(
				'field' => 'fromyear',
				'label' => '検索日付開始日（年）',
				'rules' => 'max_length[4]'
	),
	array(
				'field' => 'frommonth',
				'label' => '検索日付開始日（月）',
				'rules' => 'max_length[2]'
	),
	array(
				'field' => 'fromday',
				'label' => '検索日付開始日（日）',
				'rules' => 'max_length[2]'
	),
	array(
				'field' => 'toyear',
				'label' => '検索日付終了日（年）',
				'rules' => 'max_length[4]'
	),
	array(
				'field' => 'tomonth',
				'label' => '検索日付終了日（月）',
				'rules' => 'max_length[2]'
	),
	array(
				'field' => 'today',
				'label' => '検索日付終了日（日）',
				'rules' => 'max_length[2]'
	),
	);


/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */
?>
