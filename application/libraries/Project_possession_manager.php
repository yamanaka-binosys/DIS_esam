<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_possession_manager {

  /**
   * 企画獲得情報 データ表示部分 生成
   * @param array $kiaku_data
   */
  public function set_project_possession_data($kiaku_data=array(), $daibunrui_list_view, $item_list_view, $kbn_list_vew, $disable=false)
  {
    $string_table = "";

    if(!isset($kiaku_data["shbn"])) {
      $kiaku_data["edlp_kaisiymd"] = "";
      $kiaku_data["edlp_shryoymd"] = "";
      $kiaku_data["edlp_tenponum"] = "";
      $kiaku_data["edlp_baika"] = "";

      $kiaku_data["end_kaisiymd"] = "";
      $kiaku_data["end_shryoymd"] = "";
      $kiaku_data["end_tenponum"] = "";
      $kiaku_data["end_baika"] = "";

      $kiaku_data["trs_kaisu"] = "";
      $kiaku_data["trs_tenponum"] = "";
      $kiaku_data["trs_baika"] = "";
    }

    //
    if(!$disable) {
      //通常表示
      $string_table .= '<tr class="row_'.$kiaku_data["view_no"].'">'
        . '<td><input type="checkbox" name="regist_check[]" value="'.$kiaku_data["view_no"].'"></td>'."\n"
        . '<td>'.$daibunrui_list_view.'</td>'."\n"
        . $item_list_view."\n"
        . '<td>'.$kbn_list_vew.'</td>'."\n"
        . '<td>【EDLP】</td>'."\n"
        . '<td><input type="text" name="edlp_kaisiymd[]" size="2" maxlength="2" class="numInputOnly" title="日付" value="'.$kiaku_data["edlp_kaisiymd"].'" style="ime-mode: disabled;">～<input type="text" name="edlp_shryoymd[]" class="numonly high1'.$kiaku_data["view_no"].'" title="日付" size="2" maxlength="2" value="'.$kiaku_data["edlp_shryoymd"].'" style="ime-mode: disabled;"></td>'."\n"
        . '<td><input type="text" name="edlp_tenponum[]" size="4" maxlength="4" class="numInputOnly" title="店舗数" value="'.$kiaku_data["edlp_tenponum"].'" style="ime-mode: disabled;"></td>'."\n"
        . '<td><input type="text" name="edlp_baika[]" size="6" maxlength="6" class="numInputOnly" title="売価" value="'.$kiaku_data["edlp_baika"].'" style="ime-mode: disabled;"></td>'."\n"
        . '</tr><tr class="row_'.$kiaku_data["view_no"].'">'."\n"
        . '<td></td>'."\n"
        . '<td></td>'."\n"
        . '<td></td>'."\n"
        . '<td></td>'."\n"
        . '<td>【エンド】</td>'."\n"
        . '<td><input type="text" name="end_kaisiymd[]" size="2" maxlength="2" class="numInputOnly" title="日付" value="'.$kiaku_data["end_kaisiymd"].'" style="ime-mode: disabled;">～<input type="text" name="end_shryoymd[]" class="numonly high2'.$kiaku_data["view_no"].'" title="日付" size="2" maxlength="2" value="'.$kiaku_data["end_shryoymd"].'" style="ime-mode: disabled;"></td>'."\n"
        . '<td><input type="text" name="end_tenponum[]" size="4" maxlength="4" class="numInputOnly" title="店舗数" value="'.$kiaku_data["end_tenponum"].'" style="ime-mode: disabled;"></td>'."\n"
        . '<td><input type="text" name="end_baika[]" size="6" maxlength="6" class="numInputOnly" title="売価" value="'.$kiaku_data["end_baika"].'" style="ime-mode: disabled;"></td>'."\n"
        . '</tr><tr class="border_row row_'.$kiaku_data["view_no"].'">'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row">【チラシ】</td>'."\n"
        . '<td class="border_row">回数 ： <input type="text" name="trs_kaisu[]" size="2" maxlength="2" class="numInputOnly" title="回数" value="'.$kiaku_data["trs_kaisu"].'" style="ime-mode: disabled;"></td>'."\n"
        . '<td class="border_row"><input type="text" name="trs_tenponum[]" size="4" maxlength="4" class="numInputOnly" title="店舗数" value="'.$kiaku_data["trs_tenponum"].'" style="ime-mode: disabled;"></td>'."\n"
        . '<td class="border_row"><input type="text" name="trs_baika[]" size="6" maxlength="6" class="numInputOnly" title="売価" value="'.$kiaku_data["trs_baika"].'" style="ime-mode: disabled;"></td>'."\n"
        .  '</tr>';
    } else {
      //閲覧モード
      $string_table .= '<tr>'
        . '<td></td>'."\n"
        . '<td>'.$daibunrui_list_view.'</td>'."\n"
        . $item_list_view."\n"
        . '<td>'.$kbn_list_vew.'</td>'."\n"
        . '<td>【EDLP】</td>'."\n"
        . '<td><input type="text" name="edlp_kaisiymd[]" size="2" maxlength="2" value="'.$kiaku_data["edlp_kaisiymd"].'" disabled="disabled">～<input type="text" name="edlp_shryoymd[]" size="2" maxlength="2" value="'.$kiaku_data["edlp_shryoymd"].'" disabled="disabled"></td>'."\n"
        . '<td><input type="text" name="edlp_tenponum[]" size="4" maxlength="4" value="'.$kiaku_data["edlp_tenponum"].'" disabled="disabled"></td>'."\n"
        . '<td><input type="text" name="edlp_baika[]" size="6" maxlength="6" value="'.$kiaku_data["edlp_baika"].'" disabled="disabled"></td>'."\n"
        . '</tr><tr>'."\n"
        . '<td></td>'."\n"
        . '<td></td>'."\n"
        . '<td></td>'."\n"
        . '<td></td>'."\n"
        . '<td>【エンド】</td>'."\n"
        . '<td><input type="text" name="end_kaisiymd[]" size="2" maxlength="2" value="'.$kiaku_data["end_kaisiymd"].'" disabled="disabled">～<input type="text" name="end_shryoymd[]" size="2" maxlength="2" value="'.$kiaku_data["end_shryoymd"].'" disabled="disabled"></td>'."\n"
        . '<td><input type="text" name="end_tenponum[]" size="4" maxlength="4" value="'.$kiaku_data["end_tenponum"].'" disabled="disabled"></td>'."\n"
        . '<td><input type="text" name="end_baika[]" size="6" maxlength="6" value="'.$kiaku_data["end_baika"].'" disabled="disabled"></td>'."\n"
        . '</tr><tr class="border_row">'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row"></td>'."\n"
        . '<td class="border_row">【チラシ】</td>'."\n"
        . '<td class="border_row">回数 ： <input type="text" name="trs_kaisu[]" size="2" maxlength="2" value="'.$kiaku_data["trs_kaisu"].'" disabled="disabled"></td>'."\n"
        . '<td class="border_row"><input type="text" name="trs_tenponum[]" size="4" maxlength="4" value="'.$kiaku_data["trs_tenponum"].'" disabled="disabled"></td>'."\n"
        . '<td class="border_row"><input type="text" name="trs_baika[]" size="6" maxlength="6" value="'.$kiaku_data["trs_baika"].'" disabled="disabled"></td>'."\n"
        .  '</tr>';
    }

      return $string_table;
  }

  /**
   * 登録処理
   * @access public
   * @param  array $regist_data
   * @return array
  */
  public function set_db_regist_data($regist_data){
    log_message('debug',"========== libraries Project_possession_manager set_db_insert_data start ==========");
    // 初期化
    $CI =& get_instance();
    $CI->load->model(array('srktb070'));

    //登録データ作成
    $list_data = array();
    //$no="";
    foreach($regist_data["kbn"] as $key=>$data) {
      //$no = $data - 1;
      //$list_data[$key]["regist_check"] = $regist_data['regist_check'][$key];

      $list_data[$key]["shbn"]     = $regist_data['shbn'];
      $list_data[$key]["year"]     = $regist_data['year'];
      $list_data[$key]["month"]    = $regist_data['month'];
      $list_data[$key]["aiteskcd"] = $regist_data['aiteskcd'];

      $list_data[$key]["dbnricd"]  = $regist_data["daibunrui_list"][$key];
      $list_data[$key]["itemcd"]   = $regist_data["item_list"][$key];
      $list_data[$key]["kbn"]      = $regist_data["kbn"][$key];
      $list_data[$key]["edlp_kaisiymd"] = $regist_data["edlp_kaisiymd"][$key];
      $list_data[$key]["edlp_shryoymd"] = $regist_data["edlp_shryoymd"][$key];
      $list_data[$key]["edlp_tenponum"] = $regist_data["edlp_tenponum"][$key];
      $list_data[$key]["edlp_baika"]    = (int)trim($regist_data["edlp_baika"][$key]);
      $list_data[$key]["end_kaisiymd"]  = $regist_data["end_kaisiymd"][$key];
      $list_data[$key]["end_shryoymd"]  = $regist_data["end_shryoymd"][$key];
      $list_data[$key]["end_tenponum"]  = $regist_data["end_tenponum"][$key];
      $list_data[$key]["end_baika"]     = (int)trim($regist_data["end_baika"][$key]);
      $list_data[$key]["trs_kaisu"]     = $regist_data["trs_kaisu"][$key];
      $list_data[$key]["trs_tenponum"]  = $regist_data["trs_tenponum"][$key];
      $list_data[$key]["trs_baika"]     = (int)trim($regist_data["trs_baika"][$key]);
    }

    //////////////////////////////
    //  データ登録処理
    //
    //$kakutoku_data = "";
    $res = false;
    //foreach($list_data as $d) {
      /*
    	//空欄チェック
      if( !isset($d["regist_check"]) || trim($d["shbn"])==="" || trim($d["year"])==="" || trim($d["month"])==="" || trim($d["aiteskcd"])==="" || $d["dbnricd"]==="" || $d["itemcd"]==="" ) continue;  //空欄時 登録データに入れない

      //データ取得
      $where = "shbn='{$regist_data['shbn']}' AND year='{$d['year']}' AND month='{$d['month']}' AND aiteskcd='{$d['aiteskcd']}' AND dbnricd='{$d['dbnricd']}' AND itemcd='{$d['itemcd']}' ";
      $kakutoku_data = $CI->srktb070->get_kikaku_kakutoku_data("shbn", $where);

      if(!$kakutoku_data) {
        // DB登録処理
        $res = $CI->srktb070->insert_srktb070_data($d);
      } else {
        // DB更新処理
        $res = $CI->srktb070->update_srktb070_data($d, $where);
      }
      */

    // DB登録処理
    $res = $CI->srktb070->insert_srktb070_data($list_data);
    //}

    return $res;
    log_message('debug',"========== libraries Project_possession_manager set_db_insert_data end ==========");
  }

}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
