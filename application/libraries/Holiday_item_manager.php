<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Holiday_item_manager {

  /**
   * ページングボタンの作成
   *
   * @access private
   * @param $post ARRAY
   * @param $mode TRUE=登録　FALSE=更新、削除
   * @return string $tab_data HTML-STRING文字列
   */
  public function set_holiday_data_page($page = 1)
  {
    // 初期化
    $CI =& get_instance();
    $CI->load->model('sgmtb150'); // 企画情報アイテム
    $all_cnt = $CI->sgmtb150->get_holiday_all_cnt();

    $max_page = ceil($all_cnt / MY_PROJECT_MAX_VIEW);
    if($page < $max_page)
    {
      $next_page = $page + 1;
    }else{
      $next_page = $max_page;
    }
    $prev_page = ($page == 1) ? 1 : $page - 1;
    $string_table = '<table><tr><td colspan="5" style="text-align: right; width: 800px;">';
    if($page > 1) $string_table .= '<input type="submit" name="prev" id="prev" value=" 前項 ">';
    $string_table .= '<input type="hidden" name="prev_page" id="prev_page" value="'.$prev_page.'" >' .
    						'&emsp;&emsp;';
    if($page != $max_page) $string_table .= '<input type="submit" name="next" id="next" value=" 次項 ">';
    $string_table .= '<input type="hidden" name="next_page" id="next_page" value="'.$next_page.'" >' .
    						'</td></tr></table>';

    return $string_table;
  }

  /**
   * 祝日設定 リストHTML(内容)を作成
   *
   * @access private
   * @param
   * @return string $string_table HTML-STRING文字列
   */
  public function get_holiday_data_list($syukid=0, $syukdate="", $syukmemo="",$createdate="")
  {
    $string_table = "";

    // 表示項目
    // 追加B　削除B　日付　メモ
    $string_table = '<tr name="no" id="no_'.$syukid.'">
        <td align="center" style="width: 85px">
          <input type="button" name="addbtn" id="addbtn_'.$syukid.'" value="追加">
          <input type="hidden" name="view_no[]" value="'.$syukid.'">
          <input type="hidden" name="create_date[]" value="'.$createdate.'">
        </td>
        <td align="center" style="width: 85px">
          <input type="button" name="dellbtn" id="dellbtn_'.$syukid.'" value="削除">
        </td>
        <td align="left" style="width: 200px">
        <select name="syukmon[]" size="1">';
        for($i=1;$i<13;$i++){
            $string_table .= '<option value="' . $i . '"';
            if($syukdate != ""){ 
                $j = date_parse_from_format("Y-m-d", $syukdate);
                if( $i == $j['month']) {
                    $string_table .= 'selected';
                };
            };
            $string_table .= '>' . $i . '</option>';
        }
    $string_table .= '</select>      
        <select name="syukday[]" size="1">';
        for($i=1;$i<32;$i++){
            $string_table .= '<option value="' . $i . '"';
            if($syukdate != ""){ 
                $j = date_parse_from_format("Y-m-d", $syukdate);
                if( $i == $j['day']) {
                    $string_table .= 'selected';
                };
            };
            $string_table .= '>' . $i . '</option>';
        }
    $string_table .= '</select>      
        </td>
        <td align="left" style="width: 250px">
          <input type="text" name="syukmemo[]" value="'.$syukmemo.'" style="width: 230px">
        </td>
      </tr>';

    return $string_table;
  }

  /**
   * 登録用データ生成処理
   *
   * @access public
   * @param  array $post_data POSTデータ
   * @return array $res_data  DB登録用生成データ
   */
  function insert_data_set($post_data){
    log_message('debug',"========== libraries Project_item_manager insert_data_set start ==========");
    // 初期化
    $CI =& get_instance();
    $CI->load->model('sgmtb150');

    $db_set_year  = $post_data['holiday_year'];
    $db_set_month = $post_data['syukmon'];
    $db_set_day   = $post_data['syukday'];
    $db_set_memo  = $post_data['syukmemo'];
    
    $regist_data = array();
    
    $syukid_ret = $CI->sgmtb150->get_holiday_syukid_cnt();
    if(is_null($syukid_ret)){
        $syukid = 0;
    }else{
        $syukid = (int)$syukid_ret['syukid_cnt'];
    }
    
    
    // DB格納用の配列を作成する
    for($i=0;$i<count($db_set_month);$i++){
        $regist_data[$i] = array(
            'syukid' => (string)($syukid + $i),
            'syukdate' => $db_set_year . '-' . $db_set_month[$i] . '-' . $db_set_day[$i],
            'syukmemo' => $db_set_memo[$i]);
    }
    
    // TODO
    // 重複検出
    /*
    // 作成した配列内の重複を確認する
    for($i=0;$i<count($db_set_month);$i++){
        if (isset($regist_data[$i])){      // 重複していた場合、既に削除されているかも知れないので
            for($j=0;$j<count($db_set_month);$j++){
                if($i!==$j){    // 同じ行は無視
                    // もし重複があったら配列から除く
                    if ($regist_data[$i]['syukdate'] === $regist_data[$j]['syukdate']){
                        unset($regist_data[$i]);
                        continue;
                    }
                }
            }
        }
    }    
    */
    
    log_message('debug', '<<<<< array count=' . count($regist_data) . ", serialize=" . serialize($regist_data));
        
    
    log_message('debug',"========== libraries Project_item_manager insert_data_set end ==========");
    return $regist_data;
  }

  /**
   * 登録処理
   * @access public
   * @param  array $ins_data
   * @return array
  */
  public function set_db_insert_data($ins_data){
    log_message('debug',"========== libraries Holiday_item_manager set_db_insert_data start ==========");
    // 初期化
    $CI =& get_instance();
    $CI->load->model(array('sgmtb150'));

    // DB登録処理
    $res = $CI->sgmtb150->insert_sgmtb150_data($ins_data);

    return $res;

    log_message('debug',"========== libraries Holiday_item_manager set_db_insert_data end ==========");
  }

}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
