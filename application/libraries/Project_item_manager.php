<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Project_item_manager {

  /**
   * ページングボタンの作成
   *
   * @access private
   * @param $post ARRAY
   * @param $mode TRUE=登録　FALSE=更新、削除
   * @return string $tab_data HTML-STRING文字列
   */
  public function set_project_data_page($page = 1)
  {
    // 初期化
    $CI =& get_instance();
    $CI->load->model('sgmtb080'); // 企画情報アイテム
    $all_cnt = $CI->sgmtb080->get_project_all_cnt();

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
   * 企画情報 リストHTML(内容)を作成
   *
   * @access private
   * @param
   * @return string $string_table HTML-STRING文字列
   */
  public function get_project_data_list($view_no=0, $dbnrinm="", $dbnricd="", $itemnm="", $itemcd="" )
  {
    $string_table = "";

    $string_table = '<tr name="no" id="no_'.$view_no.'">
        <td align="center" style="width: 85px">
          <input type="button" name="addbtn" id="addbtn_'.$view_no.'" value="追加">
          <input type="hidden" name="view_no[]" value="'.$view_no.'">
        </td>
        <td align="center" style="width: 30px">
          <input type="text" name="addline" id="addline_'.$view_no.'" size="1" maxlength="1" value="">
        </td>
        <td align="center" style="width: 85px">
          <input type="button" name="dellbtn" id="dellbtn_'.$view_no.'" value="削除">
        </td>
        <td align="left" style="width: 200px">
          <input type="text" name="dbnrinm[]" size="30" maxlength="256" value="'.$dbnrinm.'" style="width: 180px">
          <input type="hidden" name="dbnricd[]" value="'.$dbnricd.'">
        </td>
        <td align="left" style="width: 250px">
          <input type="text" name="itemnm[]" value="'.$itemnm.'" style="width: 230px">
          <input type="hidden" name="itemcd[]" value="'.$itemcd.'">
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
    $CI->load->model('sgmtb080');

    //既存データ取得
    $all_data = $CI->sgmtb080->get_kikaku_item_data("DbnriCd, DbnriNm, ItemCd, ItemNm, createdate, updatedate, view_no", "", "view_no");

    //ビュー№をkeyに置き換え
    $select_data = array();
    if($all_data) {
      foreach($all_data as $d) {
        $select_data[$d["view_no"]] = $d;
      }
    }

    $start = $post_data["start_no"]+1;
    $end = $post_data["start_no"] + MY_PROJECT_MAX_VIEW;
    $regist_data = array();
    $regist_data2 = array();
    //変更位置のデータ
    $date = date("Ymd");
    $tmp_data=array();
    $no_cnt=array();
    $dbno = $CI->sgmtb080->get_project_dbnri_cnt();
    $dbnri_max = $dbno['dbnri_cnt'];
    $row_num=0;
    if(isset($post_data["dbnricd"])) $row_num=count($post_data["dbnricd"]);

    foreach($select_data as $key=>$d) {
      if($key < $start) {
        //変更位置より前のデータ
        $regist_data[] = $d;
      //}elseif( $key == $start ) {
      }elseif( $key > $end ) {
        //変更位置より後ろのデータ
        $regist_data2[] = $d;
      }
    }

    for($i=0; $i < $row_num; $i++ ) {
      $dbnri_cd = "";
      $dbnrinm = $post_data["dbnrinm"][$i];

      //空欄チェック
      if( trim($post_data["dbnrinm"][$i])==="" || trim($post_data["itemnm"][$i])==="" ) continue;  //空欄時 登録データに入れない

      if( isset($select_data[$post_data["view_no"][$i]]) ) {
        //データが存在時 更新データセット

        //大分類名が存在するかチェック
        $dbnri_cd = $CI->sgmtb080->get_dname_check($dbnrinm);// 大分類コード取得
        if($dbnri_cd) {
          //存在：存在する大分類として登録

          //アイテム存在チェック
          $target_view_no = $CI->sgmtb080->get_kikaku_item_data("view_no", "DbnriCd='{$dbnri_cd}' AND ItemCd='{$post_data["itemcd"][$i]}'");
          if(($target_view_no[0]["view_no"] != $post_data["view_no"][$i])) {
            //アイテムコードが加算された場所と値を記憶
            if( isset($no_cnt[$dbnrinm]["item_cnt"]) ) {
              $no_cnt[$dbnrinm]["item_cnt"]++;
            } else {
              $itemno = $CI->sgmtb080->get_project_item_cnt($dbnrinm);// アイテム数取得
              $no_cnt[$dbnrinm]["item_cnt"] = $itemno['max']+1;
            }
            $post_data["dbnricd"][$i] = $dbnri_cd;  //大分類コードセット
            $post_data["itemcd"][$i] = sprintf('%02d', $no_cnt[$dbnrinm]["item_cnt"]);// アイテムコードセット
          }
        } else {
          //存在しない：新規大分類として登録

          //大分類、アイテムコードが加算された場所と値を記憶
          if( isset($no_cnt[$dbnrinm]["item_cnt"]) ) {
            $no_cnt[$dbnrinm]["item_cnt"]++;
          } else {
            $dbnri_max++;
            $no_cnt[$dbnrinm]["item_cnt"] = 1;
          }
          $post_data["dbnricd"][$i] = sprintf('%02d', $dbnri_max); //大分類コードセット
          $post_data["itemcd"][$i]  = sprintf('%02d', $no_cnt[$dbnrinm]["item_cnt"]); //アイテムコードセット
        }

        //set
        $tmp_data['dbnricd'] = $post_data["dbnricd"][$i];
        $tmp_data['dbnrinm'] = $post_data["dbnrinm"][$i];
        $tmp_data['itemcd']  = $post_data["itemcd"][$i];
        $tmp_data['itemnm']  = $post_data["itemnm"][$i];
        $tmp_data['createdate']  = $select_data[$post_data["view_no"][$i]]['createdate'];
        $tmp_data['updatedate']  = $date;
        $tmp_data['view_no'] = $post_data["view_no"][$i];
      } else {
        //データが存在しない場合

        //大分類名が存在するかチェック
        $dbnri_cd = $CI->sgmtb080->get_dname_check($dbnrinm);// 大分類コード取得
        if($dbnri_cd) {
          //存在：存在する大分類として登録

          //アイテムコードが加算された場所と値を記憶
          if( isset($no_cnt[$dbnrinm]["item_cnt"]) ) {
            $no_cnt[$dbnrinm]["item_cnt"]++;
          } else {
            $itemno = $CI->sgmtb080->get_project_item_cnt($dbnrinm);// アイテム数取得
            $no_cnt[$dbnrinm]["item_cnt"] = $itemno['max']+1;
          }
          $post_data["dbnricd"][$i] = $dbnri_cd;  //大分類コードセット
          $post_data["itemcd"][$i] = sprintf('%02d', $no_cnt[$dbnrinm]["item_cnt"]);// アイテムコードセット
        } else {
          //存在しない：新規大分類として登録

          //大分類、アイテムコードが加算された場所と値を記憶
          if( isset($no_cnt[$dbnrinm]["item_cnt"]) ) {
            $no_cnt[$dbnrinm]["item_cnt"]++;
          } else {
            $dbnri_max++;
            $no_cnt[$dbnrinm]["item_cnt"] = 1;
          }
          $post_data["dbnricd"][$i] = sprintf('%02d', $dbnri_max);  //大分類コード
          $post_data["itemcd"][$i]  = sprintf('%02d', $no_cnt[$dbnrinm]["item_cnt"]);  // アイテムコードセット
        }

        //set
        $tmp_data['dbnricd'] = $post_data["dbnricd"][$i];
        $tmp_data['dbnrinm'] = $post_data["dbnrinm"][$i];
        $tmp_data['itemcd']  = $post_data["itemcd"][$i];
        $tmp_data['itemnm']  = $post_data["itemnm"][$i];
        $tmp_data['createdate']  = $date;
        $tmp_data['updatedate']  = $date;
        $tmp_data['view_no'] = $post_data["view_no"][$i];
      }
      $regist_data[] = $tmp_data;
    }

    //後半のデータ
    foreach($regist_data2 as $d) {
      $regist_data[] = $d;
    }

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
    log_message('debug',"========== libraries Project_item_manager set_db_insert_data start ==========");
    // 初期化
    $CI =& get_instance();
    $CI->load->model(array('sgmtb080'));

    // DB登録処理
    $res = $CI->sgmtb080->insert_sgmtb080_data($ins_data);

    return $res;

    log_message('debug',"========== libraries Project_item_manager set_db_insert_data end ==========");
  }

}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
