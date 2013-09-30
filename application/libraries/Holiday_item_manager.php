<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Holiday_item_manager {

    /**
     * 祝日設定 リストHTML(内容)を作成
     *
     * @access private
     * @param
     * @return string $string_table HTML-STRING文字列
     */
    public function get_holiday_data_list($syukid = 0, $syukdate = "", $syukmemo = "", $createdate = "", $syaban = "") {
        log_message('debug', "----- " . __METHOD__ . " start -----");

        $string_table = "";

        // 表示項目
        // 追加B　削除B　日付　メモ
        $string_table = '<tr name="no" id="no_' . $syukid . '">
        <td align="center" style="width: 60px; margin: 5px;">
          <input type="button" name="addbtn" id="addbtn_' . $syukid . '" value="追加">
          <input type="hidden" name="syukid[]" value="' . $syukid . '">
          <input type="hidden" name="createdate[]" value="' . $createdate . '">
          <input type="hidden" name="syaban[]" value="' . $syaban . '">
        </td>
        <td align="center" style="width: 60px; margin: 5px;">
          <input type="button" name="dellbtn" id="dellbtn_' . $syukid . '" value="削除">
        </td>
        <td align="left" style="width: 120px; padding-left: 20px;">
        <select name="syukmon[]" size="1">';
        for ($i = 1; $i < 13; $i++) {
            $string_table .= '<option value="' . $i . '"';
            if ($syukdate != "") {
                $j = date_parse_from_format("Y-m-d", $syukdate);
                if ($i == $j['month']) {
                    $string_table .= 'selected';
                };
            };
            $string_table .= '>' . $i . '</option>';
        }
        $string_table .= '</select>      
        <select name="syukday[]" size="1">';
        for ($i = 1; $i < 32; $i++) {
            $string_table .= '<option value="' . $i . '"';
            if ($syukdate != "") {
                $j = date_parse_from_format("Y-m-d", $syukdate);
                if ($i == $j['day']) {
                    $string_table .= 'selected';
                };
            };
            $string_table .= '>' . $i . '</option>';
        }
        $string_table .= '</select>      
        </td>
        <td align="left" style="width: 250px; margin: 5px;">
          <input type="text" name="syukmemo[]" class="required" title="メモ" value="' . $syukmemo . '" style="width: 230px">
        </td>
      </tr>';

        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $string_table;
    }

    /**
     * 登録用データ生成処理
     *
     * @access public
     * @param  array $post_data POSTデータ
     * @return array $res_data  DB登録用生成データ
     */
    function insert_data_set($post_data) {
        log_message('debug', "----- " . __METHOD__ . " start -----");


        // 初期化
        $CI = & get_instance();
        $CI->load->model('sgmtb150');

        $db_set_syukid = $post_data['syukid'];
        $db_set_year = $post_data['holiday_year'];
        $db_set_month = $post_data['syukmon'];
        $db_set_day = $post_data['syukday'];
        $db_set_memo = $post_data['syukmemo'];
        $db_set_createdate = $post_data['createdate'];
        $db_set_syaban = $post_data['syaban'];

        $regist_data = array();

        // もしIDが未セットの場合の準備（現在の最大値を下調べする）
        $syukid_ret = $CI->sgmtb150->get_holiday_syukid_cnt();
        if (is_null($syukid_ret)) {
            $syukid = 0;
        } else {
            $syukid = (int) $syukid_ret + 1;
        }

        // DB格納用の配列を作成する
        for ($i = 0; $i < count($db_set_month); $i++) {
            // 既存/追加のレコード判別
            if ($db_set_syukid[$i] == "" || $db_set_syukid[$i] == "0") {
                // 追加の場合の処理
                $syukid_s = (string) $syukid;
                $syukid++;
                $createdate_s = date("Ymd");
                $updatedate_s = null;
                $syaban_s = $post_data['syaban_now'];
            } else {
                // 既存レコードの更新の場合の処理
                $syukid_s = $db_set_syukid[$i];
                $createdate_s = $db_set_createdate[$i];
                $updatedate_s = date("Ymd");
                $syaban_s = $db_set_syaban[$i];
            }

            $regist_data[$i] = array(
                'syukid' => $syukid_s,
                'syukdate' => $db_set_year . '-' . $db_set_month[$i] . '-' . $db_set_day[$i],
                'syukmemo' => $db_set_memo[$i],
                'createdate' => $createdate_s,
                'updatedate' => $updatedate_s,
                'syaban' => $syaban_s);
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

        //log_message('debug', '<<<<< array count=' . count($regist_data) . ", serialize=" . serialize($regist_data));


        log_message('debug', "----- " . __METHOD__ . " end -----");
        return $regist_data;
    }

    /**
     * 登録処理
     * @access public
     * @param  array $ins_data
     * @return array
     */
    public function set_db_insert_data($ins_data, $i_year) {
        log_message('debug', "----- " . __METHOD__ . " start -----");
        // 初期化
        $CI = & get_instance();
        $CI->load->model(array('sgmtb150'));

        // DB登録処理
        $res = $CI->sgmtb150->insert_sgmtb150_data($ins_data, $i_year);

        return $res;

        log_message('debug', "----- " . __METHOD__ . " end -----");
    }

}

// END Table_manager class

/* End of file Table_manager.php */
/* Location: ./application/libraries/table_manager.php */
