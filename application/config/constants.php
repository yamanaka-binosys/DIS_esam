<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/*
|--------------------------------------------------------------------------
| ユーザー定義の定数
|--------------------------------------------------------------------------
*/
define('MY_ZERO', '0');
define('MY_THREE', '3');
define('MY_COLSPAN_EXISTENCE', '1');
define('MY_START_DAY', '01');
//define('MY_STRING_START_DAY', '01');
define('MY_INT_START_DAY', '1');
define('MY_WEEK_DAY', '7');
define('MY_WEEK_COUNT_DAY', '6');
define('MY_MONTH_WEEK', '6');
define('MY_PLAN','plan');
define('MY_RESULT','result');
define('MY_CHECKER_TH_CNT',1);
define('MY_CHECKER_SPACE_LAST',1);
define('MY_WINDOW_OPEN','window.open');
define('MY_DATA_MEMO', 'data_memo');
define('MY_TITLE_SPACE','space');
define('MY_DEFAULT_LIST_SIZE','10');
define('MY_CODE_LENGTH','5');
define('MY_PROJECT_MAX_VIEW',20);
define('MY_KBN_LINE_MAX',10);
define('MY_KARI_KBN_NO',8);
define('MY_TYPE_GENERAL','001');
define('MY_READ_PERIOD',2);
define('MY_RESULT_PERIOD',1);
define('MY_MEMO_PERIOD',1);
define('MY_AITESK_CD_IPPAN','XXXXXXXX');
define('MY_CALENDAR_MIX','mix');
define('MY_CALENDAR_ALLPLAN','allplan');
define('MY_CALENDAR_SCROLL',3);
define('MY_TOP_CALENDAR_SCROLL',4);
define('MY_TOP_CALENDAR_DIF',4);
define('MY_PLAN_SAGYONAIYO_KBN','008');
define('MY_RESULT_SAGYONAIYO_KBN','008');
define('MY_RESULT_OUT_SITU_KBN','014');
define('MY_RESULT_OUT_SITU_BASHO_KBN','015');
define('MY_CALENDAR_CHAR_CNT',12);
define('MY_CALENDAR_SCR_CHAR_CNT',9);
/*
|--------------------------------------------------------------------------
| タブ定義項目
|--------------------------------------------------------------------------
*/
define('MY_ADD', 'add');
define('MY_UPDATE', 'update');
define('MY_DELETE', 'delete');

/*
|--------------------------------------------------------------------------
| セッティングファイル　項目名
|--------------------------------------------------------------------------
*/
define('MY_CALENDAR_PLAN','s_calendar_plan');
define('MY_CALENDAR_RESULT','s_calendar_result');
define('MY_ADD_PLAN_CONF','s_plan_a');
define('MY_UPDATE_PLAN_CONF','s_plan_u');
define('MY_DELETE_PLAN_CONF','s_plan_d');
define('MY_ADD_RESULT_CONF','s_result_a');
define('MY_UPDATE_RESULT_CONF','s_result_u');
define('MY_DELETE_RESULT_CONF','s_result_d');
define('MY_ADD_DATA_MEMO','s_data_memo_a');
define('MY_UPDATE_DATA_MEMO','s_data_memo_u');
define('MY_DELETE_DATA_MEMO','s_data_memo_d');
define('MY_SEARCH_DATA_MEMO','s_data_memo_s');
define('MY_UPDATE_SELECT_DATA_MEMO','s_data_memo_su');
define('MY_DELETE_SELECT_DATA_MEMO','s_data_memo_sd');
define('MY_SEARCH_SELECT_DATA_MEMO','s_data_memo_ss');
define('MY_PROJECT_DATA','s_project_item');
define('MY_PROJECT_POSSESSION','s_project_possession');
define('MY_PROJECT_POSSESSION_VIEW','s_project_possession_v');
define('MY_MESSAGE_DATA','s_message');
define('MY_CLIENT','client');
define('MY_CLIENT_KARI','s_c_client_kari');
define('MY_CLIENT_SEISHIKI','s_c_client_seishiki');
define('MY_CLIENT_MT_KARI','s_c_client_mt_kari');
define('MY_CLIENT_MT_SEISHIKI','s_c_client_mt_seishiki');
define('MY_SELECT_ACTION_TYPE', 's_select_action_type');
define('MY_BUTTON_VIEW', 's_button_view');
define('MY_BUTTON_DEL', 's_button_del');
define('MY_BUTTON_MOVE', 's_button_move');
define('MY_BUTTON_COPY', 's_button_copy');
define('MY_LABEL_KUBUN', 's_label_kubun');
define('MY_BUTTON_SELECT', 's_button_select');
define('MY_FIELD_PLAN', 's_field_plan');
define('MY_SELECT_HONBU_KUBUN', 's_select_honbu_kubun');
define('MY_SELECT_HONBU_LOCATION', 's_select_honbu_location');
define('MY_SELECT_JYOUHOU_KUBUN', 's_select_jyouhou_kubun');
define('MY_SELECT_HINSYU_KUBUN', 's_select_hinsyu_kubun');
define('MY_SELECT_TAISYOU_KUBUN', 's_select_taisyou_kubun');
define('MY_SELECT_MAKER', 's_select_maker');
define('MY_SELECT_TENPO_KUBUN', 's_select_tenpo_kubun');
define('MY_SELECT_DAIRI_KUBUN', 's_select_dairi_kubun');
define('MY_SELECT_DAIRI_RANK', 's_select_dairi_rank');
define('MY_SELECT_OFFICE_WORK', 's_select_office_work');
define('MY_SELECT_WORK_TITLE', 's_select_work_title');
define('MY_SELECT_OUT_SITUATION', 's_select_out_situation');
define('MY_SELECT_OUT_LOCATION', 's_select_out_location');
define('MY_SELECT_GENERAL_PURPOSE', 's_general_purpose');


define('MY_SELECT_CLIENT_HEAD', 'select_head');
define('MY_SELECT_CLIENT_MAKER', 'select_maker');
define('MY_SELECT_CLIENT_SHOP', 'select_shop');
define('MY_SELECT_CLIENT_AGENCY', 'select_agency');

define('MY_SELECT_SEARCH_HEAD', 'search_head');
define('MY_SELECT_SEARCH_MAKER', 'search_maker');
define('MY_SELECT_SEARCH_SHOP', 'search_shop');
define('MY_SELECT_SEARCH_AGENCY', 'search_agency');



define('MY_LABEL_FREE', 's_label_free');
define('MY_GETSUJI_NEGO', 's_getsuji_syoudan');
define('MY_HANKI_TEIAN', 's_hanki_teian');
define('MY_TENPO_SHOUDAN', 's_tenpo_syoudan');
define('MY_TENNAI_WORK', 's_tennai_work');
define('MY_DAIRI_IPPAN', 's_dairi_ippan');
define('MY_KANRI_HANBAI', 's_kanri_hanbai');
define('MY_KANRI_OTHER', 's_kanri_other');
define('MY_DIVISION_CONF','s_division');
define('MY_TODO_IMPKBN','s_todo_impkbn');
define('MY_READING_KUBUN','s_reading_kubun');
define('MY_COMMENT_KUBUN','s_comment_kubun');


define('MY_SELECT_ITEM_VISIBILITY','s_item_visibility_s');
define('MY_ADD_ITEM_VISIBILITY','s_item_visibility_a');

/*
|--------------------------------------------------------------------------
| VIEWファイル
|--------------------------------------------------------------------------
*/
define('MY_VIEW_HEADER','parts/header');
define('MY_PLAN_VIEW_HEADER','parts/plan_header');
//define('MY_VIEW_PLAN_HEADER','parts/plan_header');
define('MY_RESULT_VIEW_HEADER','parts/result_header');
define('MY_VIEW_P_HEADER','parts/popup_header');
define('MY_VIEW_CHECKER_HEADER','parts/checker_header');
define('MY_VIEW_MENU','parts/menu');
define('MY_VIEW_FOOTER','parts/footer');
define('MY_VIEW_LAYOUT','layout');
//define('MY_VIEW_LOGIN','parts/login');
define('MY_VIEW_LOGIN','parts/content/login');
//define('MY_VIEW_TEST','parts/content/login_test');
define('MY_VIEW_TOP_ADMIN','parts/content/top_admin');
//define('MY_VIEW_TOP_ADMIN','parts/top_admin');
define('MY_VIEW_TOP_GENERAL','parts/content/top_general');
//define('MY_VIEW_TOP_GENERAL','parts/top_general');
define('MY_VIEW_CALENDAR','parts/content/calendar');
//define('MY_VIEW_CALENDAR','parts/calendar');
define('MY_VIEW_USER','parts/user');
define('MY_VIEW_KARI_CLIENT','parts/interim_client');
define('MY_VIEW_SELECT_CHECKER','parts/content/select_checker');
define('MY_VIEW_SEARCH_CHECKER','parts/search_checker');
define('MY_VIEW_CHECKER_SEARCH_CONF','parts/content/checker_search_conf');
define('MY_VIEW_CHECKER_SEARCH_UNIT','parts/content/checker_search_unit');
define('MY_VIEW_CHECKER_SEARCH_KA','parts/content/checker_search_ka');
define('MY_VIEW_CHECKER_SEARCH_GROUP','parts/content/checker_search_group');
define('MY_VIEW_PLAN','parts/content/plan');
define('MY_VIEW_PLAN_CHECK','parts/content/plan_view');
define('MY_VIEW_REGULAR_PLAN','parts/content/regular_plan');
define('MY_VIEW_RESULT','parts/content/result');
define('MY_VIEW_RESULT_V','parts/content/result_view');
define('MY_VIEW_RESULT_TEST','parts/content/result');
define('MY_VIEW_DATA_MEMO','parts/data_memo');
define('MY_VIEW_MEMO_ADD','parts/memo_add');
define('MY_VIEW_MEMO_UPDATE','parts/memo_search');
define('MY_VIEW_MEMO_SELECT','parts/memo_update');
define('MY_VIEW_MEMO_SELECT_DELETE','parts/memo_delete');
define('MY_VIEW_MEMO_SELECT_SEARCH','parts/memo_search_select');

define('MY_VIEW_PROJECT_DATA','parts/project_item');
define('MY_VIEW_PROJECT_POSSESSION','parts/project_possession');
define('MY_VIEW_PROJECT_POSSESSION_VIEW','parts/project_possession_view');
define('MY_VIEW_CLIENT','parts/client');
define('MY_VIEW_COMPANIONS','parts/select_companions');
define('MY_VIEW_DIVISION','parts/division');
define('MY_VIEW_DATA_OUTPUT','parts/content/data_output');
define('MY_VIEW_DATA_OUTPUT_CSV','parts/content/data_output_csv');
define('MY_VIEW_SELECT_CLIENT','parts/content/select_client');
define('MY_VIEW_SELECT_CLIENT_SEARCH','parts/content/select_client_search');
define('MY_VIEW_SELECT_CLIENT_SEARCH_SHOP','parts/select_client_search_shop');
define('MY_VIEW_SELECT_CLIENT_SEARCH_HEAD','parts/content/select_client_search_head');
define('MY_VIEW_SELECT_CLIENT_SEARCH_AGENCY','parts/content/select_client_search_agency');
define('MY_VIEW_SELECT_CLIENT_SEARCH_MAKER','parts/content/select_client_search_maker');
define('MY_VIEW_S_RIREKI','parts/content/s_rireki');
define('MY_VIEW_ITEM_VISIBILITY','parts/item_visibility');
define('MY_VIEW_TODO','parts/content/todo');
define('MY_VIEW_CHECK_TODO','parts/content/check_todo');
define('MY_VIEW_MESSAGE','parts/content/message');
define('MY_VIEW_SYSTEM','base');
define('MY_NEW_VIEW_PLAN_ACTION','parts/plan/new_action');
define('MY_NEW_VIEW_PLAN_HONBU','parts/plan/new_honbu');
define('MY_NEW_VIEW_PLAN_TENPO','parts/plan/new_tenpo');
define('MY_NEW_VIEW_PLAN_DAIRI','parts/plan/new_dairi');
define('MY_NEW_VIEW_PLAN_GYOUSYA','parts/plan/new_gyousya');
define('MY_NEW_VIEW_PLAN_OFFICE','parts/plan/new_office');
define('MY_VIEW_PLAN_INPUT_CHECK','parts/content/plan_input_check');
define('MY_NEW_VIEW_REGULAR_PLAN_ACTION','parts/regular_plan/new_action');
define('MY_NEW_VIEW_REGULAR_PLAN_HONBU','parts/regular_plan/new_honbu');
define('MY_NEW_VIEW_REGULAR_PLAN_TENPO','parts/regular_plan/new_tenpo');
define('MY_NEW_VIEW_REGULAR_PLAN_DAIRI','parts/regular_plan/new_dairi');
define('MY_NEW_VIEW_REGULAR_PLAN_GYOUSYA','parts/regular_plan/new_gyousya');
define('MY_NEW_VIEW_REGULAR_PLAN_OFFICE','parts/regular_plan/new_office');
define('MY_NEW_VIEW_RESULT_ACTION','parts/result/new_action');
define('MY_NEW_VIEW_RESULT_HONBU','parts/result/new_honbu');
define('MY_NEW_VIEW_RESULT_TENPO','parts/result/new_tenpo');
define('MY_NEW_VIEW_RESULT_DAIRI','parts/result/new_dairi');
define('MY_NEW_VIEW_RESULT_GYOUSYA','parts/result/new_gyousya');
define('MY_NEW_VIEW_RESULT_OFFICE','parts/result/new_office');
define('MY_VIEW_RESULT_INPUT_CHECK','parts/content/result_input_check');
define('MY_NEW_VIEW_RESULT_V_ACTION','parts/result_view/new_action');
define('MY_NEW_VIEW_RESULT_V_HONBU','parts/result_view/new_honbu');
define('MY_NEW_VIEW_RESULT_V_TENPO','parts/result_view/new_tenpo');
define('MY_NEW_VIEW_RESULT_V_DAIRI','parts/result_view/new_dairi');
define('MY_NEW_VIEW_RESULT_V_GYOUSYA','parts/result_view/new_gyousya');
define('MY_NEW_VIEW_RESULT_V_OFFICE','parts/result_view/new_office');

/*
|--------------------------------------------------------------------------
| 活動区分定数
|--------------------------------------------------------------------------
*/
define('MY_ACTION_TYPE_NON','non');
define('MY_ACTION_TYPE_HONBU','honbu');
define('MY_ACTION_TYPE_TENPO','tenpo');
define('MY_ACTION_TYPE_DAIRI','dairi');
define('MY_ACTION_TYPE_GYOUSYA','gyousya');
define('MY_ACTION_TYPE_OFFICE','office');
define('MY_ACTION_TYPE_FIRM','firm');
define('MY_ACTION_TYPE_OTHER','other');

/*
|--------------------------------------------------------------------------
| エラー定数
|--------------------------------------------------------------------------
*/
define('ERROR_SYSTEM', '100001');
define('ERROR_USER_ADD', '100002');
define('ERROR_USER_UPDATE', '100003');
define('ERROR_USER_DELETE', '100004');
define('ERROR_USER_SEARCH', '100005');
define('ERROR_DIVISION_ADD','100006');
define('ERROR_DIVISION_UPDATE','100007');
define('ERROR_DIVISION_DELETE','100008');
define('ERROR_DIVISION_NOT_DELETE','100009');
define('ERROR_AUTH', '100101');
define('ERROR_AUTH_SUCCESS', '100102');
define('ERROR_AUTH_NEW', '100103');
define('ERROR_AUTH_FAILURE', '100104');
define('ERROR_VALI', '100301');
define('ERROR_KARI_CLIENT_VALID_REQUIRE', '400101');//仮相手必須項目asakura
define('ERROR_KARI_CLIENT_ADD', '400001');//仮相手必須項目asakura
define('ERROR_KARI_CLIENT_UPDATE', '400002');//仮相手必須項目asakura
define('ERROR_KARI_CLIENT_DELETE', '400003');//仮相手必須項目asakura
define('ERROR_ALLMESSAGE_REQUIRE', '500101');
define('ERROR_ALLMESSAGE_ADD', '500001');
define('ERROR_ITEM_VISIBILITY_ADD', '600001');
define('ERROR_LOGIN_NEW_PW', '700001');
define('ERROR_LOGIN_ID', '700002');
define('ERROR_LOGIN_NEW_PW_LENGTH', '700003');
define('ERROR_LOGIN_PW', '700004');
define('ERROR_SHBN', '700005');
define('ERROR_KBN', '700006');
define('ERROR_PW', '700007');

//define('', '');
//define('', '');
//define('', '');
//define('', '');
/*
|--------------------------------------------------------------------------
| バリデーションエラー定数
|--------------------------------------------------------------------------
*/
define('ERROR_VALID_S_MONTH', '300002');
define('ERROR_VALID_S_DAY', '300003');
define('ERROR_VALID_E_YEAR', '300004');
define('ERROR_VALID_E_MONTH', '300005');
define('ERROR_VALID_E_DAY', '300006');
define('ERROR_VALID_S_DATE', '300007');
define('ERROR_VALID_E_DATE', '300008');
define('ERROR_VALID_DATE_TERM', '300009');
define('ERROR_VALID_SHOP_MAIN', '300010');
define('ERROR_VALID_AGENCY', '300011');
define('ERROR_VALID_RANK', '300012');
define('ERROR_VALID_IMPLE_USER', '300013');
define('ERROR_VALID_NEGO_CONTENTS', '300014');

/*
|--------------------------------------------------------------------------
| 画面定数
|--------------------------------------------------------------------------
*/
define('USER_SEARCH', '101001');
define('USER_ADD', '101002');
define('USER_UPDATE', '101003');
define('USER_DELETE', '101004');
define('DIVISION_ADD_COMP','101005');
define('DIVISION_UPDATE_COMP','101006');
define('DIVISION_DELETE_COMP','101007');

define('PLAN_VIEW_ITEM','SRN020x');
define('RESULT_VIEW_ITEM','SRN030x');
define('HISTORY_VIEW_ITEM','SRR010x');
define('MEMO_VIEW_ITEM','SRS030x');

/*
|--------------------------------------------------------------------------
| 曜日定数
|--------------------------------------------------------------------------
*/
define('MY_EN_SUNDAY', 'sunday');
define('MY_EN_MONDAY', 'monday');
define('MY_EN_TUESDAY', 'tuesday');
define('MY_EN_WEDNESDAY', 'wednesday');
define('MY_EN_THURSDAY', 'thursday');
define('MY_EN_FRIDAY', 'friday');
define('MY_EN_SATURDAY', 'saturday');
/*
|--------------------------------------------------------------------------
| DB部署エスケープ定数
|--------------------------------------------------------------------------
*/
//define('MY_DB_BU_ESC','');
define('MY_DB_BU_ESC','XXXXX');
define('MY_JYOHONUM_ESC','XXXXXXXXX');

/*
|--------------------------------------------------------------------------
| 区分定数
|--------------------------------------------------------------------------
*/
define('MY_MEMO_JYOUHOU_KUBUN','001');
define('MY_MEMO_HINSYU_KUBUN','002');
define('MY_MEMO_TAISYOU_KUBUN','003');
define('MY_SCHEDULE_HONBU_KUBUN','004');
define('MY_SCHEDULE_TENPO_KUBUN','005');
define('MY_SCHEDULE_DAIRITEN_KUBUN','006');
define('MY_SCHEDULE_DAIRITEN_RANK','007');
define('MY_SCHEDULE_OFFISE_KUBUN','008');
//define('MY_DROP_HONBU_KUBUN','009');
//define('MY_DROP_HONBU_KUBUN','010');
//define('MY_DROP_HONBU_KUBUN','011');
//define('MY_DROP_HONBU_KUBUN','012');
//define('MY_DROP_HONBU_KUBUN','013');
//define('MY_DROP_HONBU_KUBUN','014');


/*
|--------------------------------------------------------------------------
| 活動区分
|--------------------------------------------------------------------------
*/
define('MY_USER_ACTIVITY_HEAD','001');
define('MY_USER_ACTIVITY_MAKER','002');
//define('MY_USER_ACTIVITY_SHOP','003');
define('MY_USER_ACTIVITY_AGENCY','003');

/*
|--------------------------------------------------------------------------
| 部種
|--------------------------------------------------------------------------
*/
define('BUSINESS_UNIT_HEAD','20');
define('BUSINESS_UNIT_DIVISION','40');
define('BUSINESS_UNIT_UNIT','50');
define('BUSINESS_UNIT_BRANCH','60');


/*
|--------------------------------------------------------------------------
| ダウンロード
|--------------------------------------------------------------------------
*/
define('FILE_DIR', 'C:/app/elleair/files/');
//情報出力「サンプル」置き場
define('SAMPLE_DIR', FILE_DIR . 'sample/');
//情報出力「エクセルテンプレート」置き場
define('REPORT_TEMPLATE_DIR', FILE_DIR . 'report_template/');

/*
|--------------------------------------------------------------------------
| 画面名定数
|--------------------------------------------------------------------------
*/
define('SHOW_TOP_ADMIN','top_admin');
define('SHOW_TOP_GENE','top_general');
define('SHOW_CALENDAR','calendar');
define('SHOW_PLAN_A','plan_add');
define('SHOW_PLAN_D','plan_delete');
define('SHOW_PLAN_C','plan_check');
define('SHOW_PLAN_VIEW','plan_view');
define('SHOW_REGULAR_PLAN','regular_plan');
define('SHOW_RESULT_A','result_add');
define('SHOW_RESULT_D','result_delete');
define('SHOW_RESULT_C','result_check');
define('SHOW_RESULT_VIEW_ADMIN','result_view_admin');
define('SHOW_RESULT_VIEW_GENERAL','result_view_general');
define('SHOW_SCHEDUL','schedul');
define('SHOW_S_RIREKI','s_rireki');
define('SHOW_TODO_A','todo_add');
define('SHOW_TODO_U','todo_update');
define('SHOW_TODO','todo');
define('SHOW_TODO_D','todo_delete');
define('SHOW_MEMO_A','data_memo_add');
define('SHOW_MEMO_S','data_memo_search');
define('SHOW_MEMO_SU','data_memo_search_update');
define('SHOW_MEMO_SD','data_memo_search_delete');
define('SHOW_MEMO_U','data_memo_update');
define('SHOW_MEMO_D','data_memo_delete');
define('SHOW_MESSAGE','message');
define('SHOW_PROJECT_P','project_possession');
define('SHOW_PROJECT_V','project_possession_view');
define('SHOW_DATA_OUTPUT','data_output');
define('SHOW_USER','user');
define('SHOW_PROJECT_ITEM','project_item');
define('SHOW_DIVISION','division');
define('SHOW_DIVISION_SU','division_update');
define('SHOW_DIVISION_SD','division_delete');
define('SHOW_ITEM_VISI','item_visibility');
define('SHOW_HELP','help');
define('SHOW_SELECT_CHECKER','select_checker');
define('SHOW_SEARCH_CHECKER','search_checker');
define('SHOW_SELECT_CLIENT','select_client');
define('SHOW_SEARCH_CLIENT','search_client');

/* End of file constants.php */
/* Location: ./application/config/constants.php */