<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Table_manager_another {
	/**
	 * 確認者選択の項目確認者のHTMLを作成
	 *
	 * @access	public
	 * @param	array $checker_data 確認者情報
	 * @return	string $string_table
	 */
	public function checker_set_table($checker_data,$check=NULL)
	{
       	log_message('debug'," =========== " . __METHOD__ . " start ========== ");

        // 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_checker_table'); // テーブル設定情報取得
		$title = $CI->config->item('s_checker_title'); // タイトル情報取得
		//レ点
		$base_url = $CI->config->item('base_url');
		$title[0] = "<img src=\"";
		$title[0] .= $base_url;
		$title[0] .= "images/re2.gif\" >";
		$link  = $CI->config->item('s_checker_link');  // リンク情報取得
		$other = $CI->config->item('s_select_checker_button');  // 追加情報取得
		$data  = $this->_get_checker_data($checker_data);                   // 確認者データ取得
		$unit_cho_shbn = $CI->sgmtb010->get_unit_cho_shbn($checker_data);	//ユニット長
		
		// 確認者情報数取得
		$data_count = count($data);
		$all_count = 0;
		$string_table = $this->_checker_table($table,$title,$link,$other,$data,$data_count,$unit_cho_shbn,0,$check); // 確認者テーブル作成

        //log_message('debug'," ====== \$string_table = " . $string_table . " ===== ");
        log_message('debug'," =========== " . __METHOD__ . " end ========== ");

		return $string_table;
	}
	
	/**
	 * 確認者選択の項目部署コード選択のHTMLを作成
	 *
	 * @access public
	 * @param  array $checker_data 確認者情報
	 * @return string $string_table
	 */
	public function busyo_set_table($checker_data,$check=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_busyo_table'); // テーブル設定情報取得
		$title = $CI->config->item('s_busyo_title'); // タイトル情報取得
		//レ点
		$base_url = $CI->config->item('base_url');
		$title[0] = "<img src=\"";
		$title[0] .= $base_url;
		$title[0] .= "images/re2.gif\" >";
		$link  = $CI->config->item('s_busyo_link');  // リンク情報取得
		$other = $CI->config->item('s_select_busyo_button');  // 追加情報取得
		$data  = $this->_get_check_busyo_data($checker_data);         // 部署情報取得
		$data2  = $this->_get_checker_data($checker_data);                   // 確認者データ取得
		// 確認者情報数取得
		$data_count = count($data);
		$all_count = count($data2);
		// 確認者情報数取得
		$string_table = $this->_checker_table($table,$title,$link,$other,$data,$data_count,NULL,$all_count,$check); // 部署コード選択テーブル作成
		return $string_table;
	}
	
		/**
	 * 確認者選択の項目課コード選択のHTMLを作成
	 *
	 * @access public
	 * @param  array $checker_data 確認者情報
	 * @return string $string_table
	 */
	public function ka_set_table($checker_data,$check=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_ka_table'); // テーブル設定情報取得
		$title = $CI->config->item('s_ka_title'); // タイトル情報取得
		//レ点
		$base_url = $CI->config->item('base_url');
		$title[0] = "<img src=\"";
		$title[0] .= $base_url;
		$title[0] .= "images/re2.gif\" >";
		$link  = $CI->config->item('s_busyo_link');  // リンク情報取得
		$other = $CI->config->item('s_select_ka_button');  // 追加情報取得
		$data  = $this->_get_check_ka_data($checker_data);         // 部署情報取得
		$data2  = $this->_get_checker_data($checker_data);                   // 確認者データ取得
		$data3  = $this->_get_check_busyo_data($checker_data);         // 部署情報取得
		// 確認者情報数取得
		$data_count = count($data);
		$all_count = count($data2);
		$all_count = $all_count + count($data3);
		// 確認者情報数取得
		$string_table = $this->_checker_table($table,$title,$link,$other,$data,$data_count,NULL,$all_count,$check); // 部署コード選択テーブル作成
		return $string_table;
	}
	/**
	 * 確認者選択のグループコード選択のHTMLを作成
	 *
	 * @access public
	 * @param  array $shbn 社番
	 * @return string $string_table
	 */
	public function group_set_table($shbn,$check=NULL)
	{
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		$table = $CI->config->item('s_group_table'); // テーブル設定情報取得
		$title = $CI->config->item('s_group_title'); // タイトル情報取得
		//レ点
		$base_url = $CI->config->item('base_url');
		$title[0] = "<img src=\""; 
		$title[0] .= $base_url;
		$title[0] .= "images/re2.gif\" >";
		$link  = $CI->config->item('s_group_link');  // リンク情報取得
		$other = $CI->config->item('s_select_group_button');  // 追加情報取得
		$data  = $this->_get_check_group_data($shbn);         // ユニット情報取得
		$data2  = $this->_get_check_busyo_data($shbn);         // 部署情報取得
		$data3  = $this->_get_checker_data($shbn);                   // 確認者データ取得
		$data4  = $this->_get_check_ka_data($shbn);                   // 確認者データ取得
		// 確認者情報数取得
		$data_count = count($data);
		$all_count = count($data2);
		$all_count = $all_count + count($data3);
		$all_count = $all_count + count($data4);
		// 確認者情報数取得
		$string_table = $this->_checker_table($table,$title,$link,$other,$data,$data_count,NULL,$all_count,$check); // 部署コード選択テーブル作成
		return $string_table;
	}
	/**
	 * 確認者選択の確認者情報取得
	 *
	 * @access	public
	 * @param	array $shbn 社番
	 * @return	string $data
	 */
	public function _get_checker_data($shbn)
	{
		$CI =& get_instance();

		$CI->load->model('srwtb020'); // ユーザー別検索情報（確認者）
		$CI->load->model('sgmtb010'); // ユーザー情報
		$CI->load->model('sgmtb020'); // 部署情報
		$data = NULL;
		// ユーザ別検索情報の確認者社番取得
		$checker_data = $CI->srwtb020->get_user_shbn_data($shbn);
		
		$no = 0;
		if(!is_null($checker_data))
		{
			foreach($checker_data as $user)
			{
				$user_data = $CI->sgmtb010->get_search_user_data($user['kshbnnn']);
				// 部名なし
				if($user_data['bucd'] !== MY_DB_BU_ESC)
				{
					$user_bu = $CI->sgmtb020->get_bu_name_data($user_data);
				}else{
					$user_bu['bunm'] = NULL;
				}
				// 課・ユニット名なし
				if($user_data['kacd'] !== MY_DB_BU_ESC)
				{
					$user_unit = $CI->sgmtb020->get_unit_name_data($user_data);
				}else{
					$user_unit['bunm'] = NULL;
				}
				
                // '---'で結合し、後で_checker_tableの処理の際に分解する
				$data[$no][0] = $user['kshbnnn'] . '---' . $user['set_flg'];
				//$data[$no][0] = $user['kshbnnn'];
                $data[$no][1] = $user_data['shinnm'];
				$data[$no][2] = $user_bu['bunm'];
				$data[$no][3] = $user_unit['bunm'];
				$data[$no][4] = $user['edbn'];
//				$data[$no][4] = NULL;
				$no++;
			}
		}
		return $data;
	}
	/**
	 * 確認者選択の部署情報取得
	 *
	 * @access	public
	 * @param	array $shbn 社番
	 * @return	string $data
	 */
	public function _get_check_busyo_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srwtb020'); // ユーザ別検索情報(確認者)
		$CI->load->model('sgmtb010'); // ユーザー情報
		$CI->load->model('sgmtb020'); // 部署情報
		$data = NULL;
		// ユーザ別検索情報の部署取得
		$busyo_data = $CI->srwtb020->get_user_busyo_data($shbn);
		$no = 0; // ユーザ識別用
		if(!is_null($busyo_data))
		{
			foreach($busyo_data as $user)
			{
				$honbu_name = $CI->sgmtb020->get_honbu_name_data($user);
				$bu_name = $CI->sgmtb020->get_bu_name_data($user);
				$ka_name = $CI->sgmtb020->get_unit_name_data($user);
				if($user['bucd'] === MY_DB_BU_ESC)
				{
					$user['bucd'] = NULL;
					$bu_name['bunm'] = NULL;
				}
				if($user['kacd'] === MY_DB_BU_ESC)
				{
					$user['kacd'] = NULL;
					$ka_name['kanm'] = NULL;
				}
				$data[$no][0] = $user['honbucd'].$user['bucd'] . '---' . $user['set_flg'];
				$data[$no][1] = $honbu_name['bunm'].$bu_name['bunm'];
				$data[$no][2] = $user['edbn'];
				$no++;
			}
		}
		return $data;
	}
	
		/**
	 * 確認者選択のユニット情報取得
	 *
	 * @access	public
	 * @param	array $shbn 社番
	 * @return	string $data
	 */
	public function _get_check_ka_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srwtb020'); // ユーザ別検索情報(確認者)
		$CI->load->model('sgmtb010'); // ユーザー情報
		$CI->load->model('sgmtb020'); // 部署情報
		$data = NULL;
		// ユーザ別検索情報の部署取得
		$busyo_data = $CI->srwtb020->get_user_ka_data($shbn);
		$no = 0; // ユーザ識別用
		if(!is_null($busyo_data))
		{
			foreach($busyo_data as $user)
			{
				$honbu_name = $CI->sgmtb020->get_honbu_name_data($user);
				$bu_name = $CI->sgmtb020->get_bu_name_data($user);
				$ka_name = $CI->sgmtb020->get_unit_name_data($user);
				if($user['bucd'] === MY_DB_BU_ESC)
				{
					$user['bucd'] = NULL;
					$bu_name['bunm'] = NULL;
				}
				if($user['kacd'] === MY_DB_BU_ESC)
				{
					$user['kacd'] = NULL;
					$ka_name['kanm'] = NULL;
				}
				$data[$no][0] = $user['honbucd'].$user['bucd'].$user['kacd'] . '---' . $user['set_flg'];
				$data[$no][1] = $honbu_name['bunm'].$bu_name['bunm'].$ka_name['bunm'];
				$data[$no][2] = $user['edbn'];
				$no++;
			}
		}
		return $data;
	}

	/**
	 * 確認者選択のグループ情報取得
	 *
	 * @access	public
	 * @param	array $checker_data 確認者情報
	 * @return	string $data
	 */
	public function _get_check_group_data($shbn)
	{
		$CI =& get_instance();
		$CI->load->model('srwtb020'); // ユーザ別検索情報(確認者)
		$CI->load->model('sgmtb110'); // グループ情報
		$data = NULL;
		// ユーザ別検索情報のグループ取得
		$group_data = $CI->srwtb020->get_user_group_data($shbn);
		$no = 0; // ユーザ識別用
		$d_no = 0;
		if(!is_null($group_data))
		{
			foreach($group_data as $user)
			{
				foreach($user as $key => $group)
				{
					// グループコードからグループ名取得
					if($key === 'kgrpnn')
					{
						log_message('debug',$group);
						// グループコード保存
						$data[$no][$d_no++] = $group . '---' . $user['set_flg'];
						// グループ名保存
						$data[$no][$d_no++] = $CI->sgmtb110->get_group_name_data($group);
						$data[$no][$d_no] = $user['edbn'];
					}
				}
				$no++;
				$d_no = 0;
			}
		}
		return $data;
	}

	/**
	 * 確認者選択画面テーブル情報設定
	 *
	 * @access	private
	 * @param	array $table:テーブル設定情報
	 * @param	array $title:項目情報
	 * @param	array $link:リンク情報
	 * @param	array $data:検索結果情報
	 * @param	array $other:その他設定情報(ボタンなど)
	 * @param	array $data_count:確認者の数
	 * @return	string
	 */
	function _checker_table($table,$title,$link,$other,$data,$data_cnt = NULL,$unit_cho_shbn = NULL,$all_count=0,$check=NULL)
	{
       	log_message('debug'," =========== " . __METHOD__ . " start ========== ");
       	//log_message('debug'," ----- \$table = " . serialize($table));
       	//log_message('debug'," ----- \$other = " . serialize($other));
       	//log_message('debug'," ----- \$data = " . serialize($data));
        
        // 初期化
		
		$CI =& get_instance();
		$base_url = $CI->config->item('base_url');
		$edbn_val=0;
		// 設定値取得
		// テーブルHTML作成
		$string_table = "";
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($table['table_width']))
		{
			$string_table .= " width=\"" . $table['table_width']."\"";
		}
		// テーブル高さ設定
		if( ! is_null($table['table_height']))
		{
			$string_table .= " height=\"" . $table['table_height']."\"";
		}
		$string_table .= ">";
		// 見出しタイトル作成
		if(! is_null($table['heading']))
		{
			// tr設定
			$string_table .= "<tr>\n";
			// td設定
			$string_table .= "<th";
			// 見出しタイトルそろえ位置
			$string_table .= " style=\"".$table['heading_th_style'].";\"";
			$string_table .= ">\n";
			$string_table .= $table['heading']."\n";
			$string_table .= "</th>\n";
			// td　end
			$string_table .= "</tr>\n";
			// tr　end
		}
		 
		// ユニット長の設定
		if(! empty($unit_cho_shbn)){
			//$string_table .= '<tr><td ><input type="checkbox" name="kshbn[]" value="'. $unit_cho_shbn . '">ユニット長</td></tr>';
		}
		
		// 表の作成開始------------------------------------------------
		$string_table .= "<tr>\n";
		$string_table .= "<td";
		$string_table .= " style=\"";
		// 左側余白設定
		if(! is_null($table['td_padding_left']))
		{
			$string_table .= "padding-left:".$table['td_padding_left'].";";
		}
		// collapse設定
		if( ! is_null($table['td_border_collapse']))
		{
			$string_table .= " border-collapse:" . $table['td_border_collapse'] . ";";
		}
		// border設定
		if( ! is_null($table['td_border']))
		{
			$string_table .= " border:" . $table['td_border'] . ";";
		}
		$string_table .= "\">\n";
		// 表の中身作成開始------------------------------------------------------
		// 項目名作成
		// table設定
		// 最大値設定
		$max_span = $table['max_span'];
		$max_th = MY_CHECKER_SPACE_LAST;
		log_message('debug',"max_span".$max_span);
		// 縦

		// 表示項目セット数判定
		// データが設定値以上なら
		if($max_span <= $data_cnt)
		{
			$max_th = $max_span;
		}else if(($max_span - 1) == $data_cnt){
			// 最大表示数より１つ少ない場合
			$max_th = $max_span;
		}else if($max_span > $data_cnt){
			// データが設定値より少ない
			$max_th = $data_cnt + 1;
			log_message('debug',"data_cnt = ".$data_cnt." : max_span = ".$max_span);
		}else{
			// データ無しの場合、１つ表示
			$max_th = MY_CHECKER_SPACE_LAST;
		}
			$string_table .= "<table";
		log_message('debug',"table_th_width = ".$table['table_th_width']);
		// テーブル幅設定
		if( ! is_null($table['table_th_width']))
		{
			$width = $table['table_th_width'] * $max_th;
			$string_table .= " width=".$width;
		}
		// 
		if( ! is_null($table['table_th_height']))
		{
			$string_table .= " height=".$table['table_th_height'];
		}
		// セルの境界線の設定
		$string_table .= " style=\"border-collapse:".$table['td_border_collapse'].";";
		$string_table .= "\">\n";
		// 表の項目名を設定
		// 項目数(横)の設定
			$string_table .= "<tr>\n";
			// 設定セット分繰り返し
			log_message('debug',"max_th = ".$max_th);
			for($d_cnt = 0; $d_cnt < $max_th; $d_cnt++)
			{
				// 項目数分繰り返し
				for($th_cnt = 0; $th_cnt < $table['span']; $th_cnt++)
				{
					// th設定
					$string_table .= "<th";
					// styleそろえ位置
					$string_table .= " style=\"text-align:".$table['title_th_text-align'].";";
					// style幅
					$string_table .= "width:".$table['title_th_width'][$th_cnt].";";
					// style高さ
					$string_table .= "height:".$table['title_th_height'].";";
					// 上との空白の大きさ
					$table['title_th_padding'] = '5px';
					$string_table .= "padding:".$table['title_th_padding'].";";
					
					// 枠線設定
					if(! is_null($table['title_th_border']))
					{
						// 境界あり
						$string_table .= "border:".$table['title_th_border'].";";
					}else if(! is_null($table['title_th_border_bottom'])){
						// 境界なし
						$string_table .= "border-bottom:".$table['title_th_border_bottom'];
					}
					// 背景色
					if(! is_null($table['title_th_bakcolor']))
					{
						$string_table .= "background-color:".$table['title_th_bakcolor'].";";
					}
					$string_table .= "\">\n";
					// 項目名
					if(! is_null($title[$th_cnt]))
					{
						$string_table .= $title[$th_cnt]."\n";
					}else{
						$string_table .= "&ensp;\n";
					}
					$string_table .= "</th>\n";
					// th　end
				}
				$string_table .= "<th></th>\n";
			}
			$string_table .= "</tr>";

			// 項目内容セル作成時に必要なものを初期化
			$set_cnt = 0;  // セット数
			$tr_cnt = 0;   // 行数
			
			$string_table .= "<tr>";
			
			if(count($data) >= 1)
			{
                //log_message('debug'," ----- \$data = " . serialize($data));
				// １セットのデータに小分け
				foreach($data as $tr_data)
				{
	//				log_message('debug',"set_cnt = ".$set_cnt);
	//				log_message('debug',"start_tr_cnt = ".$tr_cnt);
					$vno = 0;// 項目の順番リセット
					
					// 項目内容行
					foreach($tr_data as $key => $value)
					{
	//					log_message('debug',"in_set_cnt = ".$set_cnt);
						// 表の項目内容を設定
						// td設定
						$string_table .= "<td";
						// styleそろえ位置
						$string_table .= " style=\"text-align:".$table['div_td_align'][$vno].";";
						// style幅
						$string_table .= "width:".$table['div_td_width'][$vno].";";
						// style高さ
						$string_table .= "height:".$table['div_td_height'].";";
						// 余白設定
						$string_table .= "padding:".$table['title_th_padding'].";";
						// 枠線設定
						if(! is_null($table['title_th_border']))
						{
							$string_table .= "border:".$table['title_th_border'].";";
						}
						// 背景設定
						if(! is_null($table['div_style_bakcolor']))
						{
							$string_table .= "background-color:".$table['div_style_bakcolor'].";";
						}
						$string_table .= "\">\n";
						// 項目内容設定
						// input 設定
						if(! is_null($table['div_td_input_type'][$vno]))
						{
							$string_table .= "<input type=\"";
							// 項目内容判定
							switch($table['div_td_input_type'][$vno])
							{
								// ボタン
								case "button":
								case "submit":
									// type値設定
									$string_table .= $other['type']."\"";
									// name値設定
									$string_table .= " name=\"".$other['name'].$tr_data[$key]."\"";
									// value値設定
									$string_table .= " value=\"".$other['value']."\"";
									// onclick判定
									if(! is_null($other['onclick_type']))
									{
										// 値設定
										$string_table .= "onclick='location.href=\"".$base_url.$other['open_call']."/index/".$tr_data[$key]."\"'";
									}
									break;
								// チェックボックス
								case "checkbox":
									$string_table .= "checkbox\"";
									$string_table .= " name=\"".$table['div_td_name'][$vno]."[]\"";
									// _get_checker_dataで結合されたデータを分解する
                                    $value_shbn = explode('---', $value);
                                    $string_table .= " value=\"".$value_shbn[0]."/".$all_count."\"";
									//echo $all_count;
									
									if(isset($check) && $check!=""){
										if(in_array($all_count,$check)){ 
											$string_table .= "  checked ";
										}
									}
                                    // 以前チェックされていた場合、チェックをセットする
									elseif(isset($value_shbn[1]) && $value_shbn[1] == "1"){
                                        $string_table .= " checked ";
									}
									break;
							}
							$string_table .= ">\n";
						}else{
							// input以外の場合
							// リンク設定
							if($table['div_td_a_existence'][$vno])
							{
								$string_table .= "<a href=\"";
								$string_table .= $link['link'];
								$string_table .= "\">";
								$string_table .= $value;
								$string_table .= "</a>";
							}
							// 表示名
							$string_table .= $value;
							// hidden設定
							$string_table .= "<input type=\"hidden\" name=\"\" value=\"".$value."\"";
							$string_table .= ">";
							
						}
						$string_table .= "</td>\n";
						$vno++;// 項目数
					}
					$string_table .="<td></td>";
					$set_cnt++; // セット数
					$edbn_val++;
					$all_count++;
					// 最大セット列数を超えた場合
					if($set_cnt >= $max_span)
					{
					log_message('debug',"set_cnt = ".$set_cnt);
						$set_cnt = 0;// セット数初期化
						$tr_cnt++;// 行数
						$vno = 0;
						$string_table .= "</tr>\n<tr>\n";
					}
				}
				
			}
			// データがない場合空の行をセット
			// 項目数分繰り返し
			for($free = 0; $free < $table['span']; $free++)
			{
				// 表の項目内容を設定
				// td設定
				$string_table .= "<td";
				// styleそろえ位置
				$string_table .= " style=\"text-align:".$table['div_td_align'][$free].";";
				// style幅
				$string_table .= "width:".$table['div_td_width'][$free].";";
				// style高さ
				$string_table .= "height:".$table['div_td_height'].";";
				// 余白設定
				$string_table .= "padding:".$table['title_th_padding'].";";
				// 枠線設定
				if(! is_null($table['title_th_border']))
				{
					$string_table .= "border:".$table['title_th_border'].";";
				}
				// 背景設定
				if(! is_null($table['div_style_bakcolor']))
				{
					$string_table .= "background-color:".$table['div_style_bakcolor'].";";
				}
				$string_table .= "\">\n";
				// 項目内容設定
				// input 設定
				if(! is_null($table['div_td_input_type'][$free]))
				{
					$string_table .= "<input type=\"";
					// 項目内容判定
					switch($table['div_td_input_type'][$free])
					{
						// ボタン
						case "button":
						case "submit":
							// type値設定
							$string_table .= $other['type']."\"";
							// value値設定
							$string_table .= " value=\"".$other['value']."\"";
							// onclick判定
							if(! is_null($other['onclick_type']))
							{
								
								// 値設定
								// ウインドウ呼びさき出し
								$string_table .= "onclick='location.href=\"".$base_url.$other['open_call']."\"'";
								
							}
							break;
						// チェックボックス
						case "checkbox":
							$string_table .= $table['div_td_input_type'][$free]."\"";
							break;
					}
					$string_table .= ">\n";
				}else{
					// input以外の場合
					// リンク設定
					if($table['div_td_a_existence'][$free])
					{
						$string_table .= "<a href=\"";
						$string_table .= $link['link'];
						$string_table .= "\">";
						$string_table .= "</a>";
					}
				}
				$string_table .= "</td>\n";
			}
			$string_table .= "</tr>\n";
			$string_table .= "</table>\n";
       	
            log_message('debug'," =========== " . __METHOD__ . " end ========== ");

			return $string_table;
	}	
	
	/**
	 * 確認者選択画面テーブル情報設定
	 *
	 * @access	private
	 * @param	array $table:テーブル設定情報
	 * @param	array $title:項目情報
	 * @param	array $link:リンク情報
	 * @param	array $data:検索結果情報
	 * @param	array $other:その他設定情報(ボタンなど)
	 * @return	string
	 */
	function _set_table_kai($table,$title,$link,$other,$data)
	{
		log_message('debug',"===================== checker_table =======================");
		// 初期化
		$CI =& get_instance();
		// 設定値取得
		
		// テーブルHTML作成
		$string_table = "";
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($table['table_width']))
		{
			$string_table .= " width=\"" . $table['table_width']."\"";
		}
		// テーブル高さ設定
		if( ! is_null($table['table_height']))
		{
			$string_table .= " height=\"" . $table['table_height']."\"";
		}
		$string_table .= ">";
		// 見出しタイトル作成
		if(! is_null($table['heading']))
		{
			// tr設定
			$string_table .= "<tr>\n";
			// td設定
			$string_table .= "<th";
			// 見出しタイトルそろえ位置
			$string_table .= " style=\"".$table['heading_th_style'].";\"";
			$string_table .= ">\n";
			// 見出し行リンク設定
			if( ! is_null($link['heading_link']))
			{
				$string_table .= "<a";
				$string_table .= " href=\"" . $link['heading_link'] . "\"";
				// 見出し行スタイル設定
				$string_style = "";
				if( ! is_null($table['a_style_border']))
				{
					$string_style .= "border:" . $table['a_style_border'] . ";";
				}
				// フォントサイズ
				if( ! is_null($table['a_style_font_size']))
				{
					$string_style .= "font-size:" . $table['a_style_font_size'] . ";";
				}
				// デコレーション設定
				if( ! is_null($table['a_style_decoration']))
				{
					$string_style .= "text-decoration:" . $table['a_style_decoration'] . ";";
				}
				// 文字色
				if( ! is_null($table['a_style_color']))
				{
					$string_style .= "color:" . $table['a_style_color'] . ";";
				}
				// style設定判定
				if( ! is_null($string_style))
				{
					$string_table .= " style=\"" . $string_style . "\">";
				}else{
					$string_table .= ">\n";
				}
				// 見出しタイトル名
				$string_table .= $table['heading']."\n";
				$string_table .= "</a>\n";
			}else{
				$string_table .= $table['heading']."\n";
			}// 見出しタイトル設定　end
			$string_table .= "</th>\n";
			// td　end
			// 追加情報有無判定
			if(($table['other_option']))
			{
				// スタイル設定
				if( ! is_null($other['td_style']))
				{
					$string_table .= "<td style=\"" . $other['td_style'] . "\">\n";
				}else{
					$string_table .= "<td>\n";
				}
				// type判定
				if( ! is_null($other['type']))
				{
					$string_table .= "<input type=\"" . $other['type'] . "\"";
					// style判定
					$string_style = "";
					if( ! is_null($other['style_height']))
					{
						$string_style .= " height:" . $other['style_height'] . ";";
					}
					if( ! is_null($other['style_font_size']))
					{
						$string_style .= " font-size:" . $other['style_font_size'] . ";";
					}
					if( ! is_null($string_style))
					{
						$string_table .= " style=\"" . $string_style . "\"";
					}
					// value判定
					if( ! is_null($other['value']))
					{
						$string_table .= " value=\"" . $other['value'] . "\"";
					}
					$string_table .= ">\n";
				}
				$string_table .= "</td>\n";
			}// 追加情報有無 end
			$string_table .= "</tr>\n";
			// tr　end
		}
		// 表の作成開始------------------------------------------------
		$string_table .= "<tr>\n";
		$string_table .= "<td";
		$string_table .= " style=\"";
		// 左側余白設定
		if(! is_null($table['td_padding_left']))
		{
			$string_table .= "padding-left:".$table['td_padding_left'].";";
		}
		// collapse設定
		if( ! is_null($table['td_border_collapse']))
		{
			$string_table .= " border-collapse:" . $table['td_border_collapse'] . ";";
		}
		// border設定
		if( ! is_null($table['td_border']))
		{
			$string_table .= " border:" . $table['td_border'] . ";";
		}
		$string_table .= "\"";
		// 追加情報有無判定
		if($table['td_colspan'] === MY_COLSPAN_EXISTENCE)
		{
			$string_table .= ">\n";
		}else{
			$string_table .= " colspan=\"" . $table['td_colspan'] . "\">\n";
		}
		// 表の中身作成開始------------------------------------------------------
		// 項目名作成
		// table設定
		$string_table .= "<table";
		// テーブル幅設定
		if( ! is_null($table['table_th_width']))
		{
			$string_table .= " width=".$table['table_th_width'];
		}
		// 
		if( ! is_null($table['table_th_height']))
		{
			$string_table .= " height=".$table['table_th_height'];
		}
		// セルの境界線の設定
		$string_table .= " style=\"border-collapse:".$table['td_border_collapse'].";";
		$string_table .= "\">\n";
		// tr設定
		$string_table .= "<tr>\n";
		// 表の項目名を設定
		// 項目数(横)の設定
		for($i = 0; $i < $table['span']; $i++)
		{
			// th設定
			$string_table .= "<th";
			// styleそろえ位置
			$string_table .= " style=\"text-align:".$table['title_th_text-align'].";";
			// style幅
			$string_table .= "width:".$table['title_th_width'][$i].";";
			// style高さ
			$string_table .= "height:".$table['title_th_height'].";";
			// 上との空白の大きさ
			$string_table .= "padding:".$table['title_th_padding'].";";
			// 枠線設定
			if(! is_null($table['title_th_border']))
			{
				// 境界あり
				$string_table .= "border:".$table['title_th_border'].";";
			}else if(! is_null($table['title_th_border_bottom'])){
				// 境界なし
				$string_table .= "border-bottom:".$table['title_th_border_bottom'];
			}
			// 背景色
			if(! is_null($table['title_th_bakcolor']))
			{
				$string_table .= "background-color:".$table['title_th_bakcolor'].";";
			}
			$string_table .= "\">\n";
			// 項目名
			if(! is_null($title[$i]))
			{
				$string_table .= $title[$i]."\n";
			}else{
				$string_table .= "&ensp;\n";
			}
			$string_table .= "</th>\n";
			// th　end
		}
 
		$string_table .= "</tr>\n";
		// tr　end
		$string_table .= "</table>\n";
		
		// スクロール表示判定
		if($table['div_existence'])
		{
			$string_table .= "<div";
			// overflow設定
			$string_table .= " style=\"overflow:".$table['div_style_overflow'].";";
			// 幅設定
			$string_table .= "width:".$table['div_style_width'].";";
			// 高さ設定
			$string_table .= "height:".$table['div_style_height'].";";
			// margin設定
			$string_table .= "margin:0;";
			$string_table .= "\">\n";
		}
		// 項目内容の作成
		// table設定
		$string_table .= "<table";
		// テーブル幅設定
		$string_table .= " width=".$table['table_width'];
		// セルの境界線の設定
		$string_table .= " style=\"border-collapse:".$table['td_border_collapse'].";";
		$string_table .= "\">\n";
		// 確認者数と同数行表示
		foreach($data as $key => $tr_data)
		{
			// tr設定
			$string_table .= "<tr>\n";
			// 表の項目内容を設定
			// 項目数(横)の設定
			for($n = 0; $n < $table['span']; $n++)
			{
				// td設定
				$string_table .= "<td";
				// styleそろえ位置
				$string_table .= " style=\"text-align:".$table['div_td_align'][$n].";";
				// style幅
				$string_table .= "width:".$table['div_td_width'][$n].";";
				// style高さ
				$string_table .= "height:".$table['div_td_height'].";";
				// 余白設定
				$string_table .= "padding:".$table['title_th_padding'].";";
				// 枠線設定
				if(! is_null($table['title_th_border']))
				{
					$string_table .= "border:".$table['title_th_border'].";";
				}
				// 背景設定
				if(! is_null($table['div_style_bakcolor']))
				{
					$string_table .= "background-color:".$table['div_style_bakcolor'].";";
				}
				$string_table .= "\">\n";
				// 項目内容設定
				// input 設定
				if(! is_null($table['div_td_input_type'][$n]))
				{
					$string_table .= "<input type=\"";
					// 項目内容判定
					switch($table['div_td_input_type'][$n])
					{
						// ボタン
						case "button":
						case "submit":
							// type値設定
							$string_table .= $other['type']."\"";
							// value値設定
							$string_table .= " value=\"".$other['value']."\"";
							// onclick判定
							if(! is_null($other['onclick_type']))
							{
								$string_table .= " onclick=\"";
							    // onclickの属性判定
								$string_table .= $other['onclick_type'];
								// 値設定
								// ウインドウ呼びさき出し
								$string_table .= MY_WINDOW_OPEN ."('".$base_url.$other['open_call']."',";
								$string_table .= "'',";
								// スクロールバーの有り無し
								$string_table .= "'".$other['scrollbars'].",";
								// ウインドウ幅
								$string_table .= $other['open_width'].",";
								// ウインドウ高さ
								$string_table .= $other['open_height'].",";
								$string_table .= "');\"";
							}
							break;
						// テキストボックス
						case "text":
							$string_table .= $table['div_td_input_type'][$n]."\"";
							$string_table .= " value=\"".$tr_data[$n]."\"";
							break;
						// チェックボックス
						case "checkbox":
							$string_table .= $table['div_td_input_type'][$n]."\"";
							break;
					}
					$string_table .= ">\n";
				}else{
					// input以外の場合
					// リンク設定
					if($table['div_td_a_existence'][$n])
					{
						$string_table .= "<a href=\"";
						$string_table .= $link['link'];
						$string_table .= "\">";
						$string_table .= $tr_data[$n];
						$string_table .= "</a>";
					}
					$string_table .= $tr_data[$n];
				}
				$string_table .= "</td>\n";
				// td　end
			}
			$string_table .= "</tr>\n";
			// tr　end
		}// foreach data end
		
		$string_table .= "</table>\n";
		// スクロール表示のdiv閉じタグ判定
		if($table['div_existence'])
		{
			$string_table .= "</div>\n";
		}
		// 表の中身作成終了 -------------------------------------------------------
		$string_table .= "</td>\n";
		$string_table .= "</tr>\n";
		$string_table .= "</table>\n";
		// table end

		return $string_table;
	}

}

// END Table_set class

/* End of file Table_set.php */
/* Location: ./application/libraries/Table_set.php */
