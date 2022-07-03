<?php
/*************
デフォルト変数
*************/
define('TITLE', 'KUMON jsonダウンロード');
/*******
独自関数
*******/
// プレヴァーダンプ
function pre_var_dump($data = '') {
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}
/////////////////
// html基本形関数
/////////////////
function html_basic_get($content, $title = '') {
	$html = 
		'<html>
			<head>
				<title>'.$title.'</title>
				<meta charset="utf-8">
			</head>
			<body>
				'.$content.'
			</body>
		</html>';
	return $html;
}
/************
仕様確認array
************/
$arr = 
array(
	'post' => array(
		0 => array(
			'id'       => 1,
			'url'      => 'https://photo.kumon.ne.jp/photos/view/9',
			'category' => 'フォト投稿キャンペーン ',
			'title'    => 'title',
			'head'     => 'KUMON',
			'text'     => 'text',
			'thumb'    => 'images/set1/data1/thumbA_1.jpg',
			'youtube'  => 'https://youtu.be/t7YzZVZ6jbM',
			'name'     => '名前',
			'age'      => array('幼稚園 ( または保育園)', '小学生'),
			'subject'  => array('算数', '英語'),
			'word'     => array('勉強が <br> 楽しかった', '習慣が <br> つく'),
		),
	'tagCloud' => array(),
	'countCloud' => array(),
));



/***********************************
読み込みファイルパスを指定すると発動
***********************************/
if($_POST['path']) {
	if(!file_exists($_POST['path'])) {
		$content_text = $_POST['path'].' は存在しません <a href="">戻る</a>';
			$html = html_basic_get($content_text, TITLE);
			echo $html;
		exit;
	}
	//PHPExcelの読み込み
	require_once('PHPExcel-1.8/Classes/PHPExcel.php');
	require_once('PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');

	// xlsxファイルの場所
	$readFile = $_POST['path'];

	// xlsxをPHPExcelでロード
	$objPExcel = PHPExcel_IOFactory::load($readFile);

	// 配列形式で返す(現在は使用していない)
//	$objPExcel->getActiveSheet()->toArray(null,true,true,true);
	// Writer作成
	$writer = PHPExcel_IOFactory::createWriter($objPExcel, 'csv');
	// csv保存
	$writer->save('excele.csv');
	
	// 作成したcsvファイル格納場所を変数に入れる
	$filepath = 'excele.csv';
	setlocale(LC_ALL, 'ja_JP.UTF-8');

	$filepath = 'excele.csv';
	//---------------------------------------
	// csvデータをarrayにコンバートさせて取得
	//---------------------------------------
	function csv_data_convert_array_get($filepath) {
		//エラー処理
		if(($fp = fopen($filepath, 'r')) === false) {
			// 必要あれば記述する
		}
	
		// 必要変数
		$i = 0;
		$csv_header_data_array              = array();
		$csv_header_native_data_array       = array();
		$csv_header_TagCloud_data_array     = array();
		$csv_header_SubjectCloud_data_array = array();
		$csv_header_keywordCloud_data_array = array();

		$csv_header_age_category_data_array          = array();
		$csv_header_subject_category_data_array      = array();
		$csv_header_keywordCloud_category_data_array = array();

		$csv_data_array                     = array();

		$csv_data_age_type_list_array     = array();
		$csv_data_subject_type_list_array = array();
		$csv_data_keyword_type_list_array = array();
	
		// csvの行を1行ずつ読み込み
		while($line = fgetcsv($fp)) {
			// 1行目ヘッダー情報取得
			if($i == 0) {
				foreach($line as $key => $value) {
					if($value == '') {
						$csv_header_keywordCloud_data_array[$key] = $value;
					}
						else {
							$csv_header_keywordCloud_data_array[$key] = 'keyword';
						}
					$csv_header_keywordCloud_category_data_array[$key] = $value;
				}
				$i++;
			}
				// 2行目ヘッダー情報取得
				else if($i == 1) {
//					pre_var_dump($line);
					foreach($line as $key => $value) {
						if($value == '学年') {
							$age_key   = $key;
							$age_value = $value;
						}
						if($value == '教科') {
							$subject_key   = $key;
							$subject_value = $value;
						}
					}
					foreach($line as $key => $value) {
					if($key >= $age_key && $key <= ($subject_key-1)) {
						$csv_header_TagCloud_data_array[$key] = $age_value;
					}
					else if($key >= $subject_key && $key <= ($subject_key+2)) {
						$csv_header_TagCloud_data_array[$key] = $subject_value;
					}
						else {
							$csv_header_TagCloud_data_array[$key] = $value;
						}
					}
					$i++;
				}
				// 3行目ヘッダー情報取得
				else if($i == 2) {
				foreach($line as $key => $value) {
					$pattern = '/連番/';
					$value = preg_replace($pattern, 'id', $value);
					$pattern = '/見出し/';
					$value = preg_replace($pattern, 'head', $value);
					$pattern = '/本文/';
					$value = preg_replace($pattern, 'text', $value);
					$pattern = '/image:URL/';
					$value = preg_replace($pattern, 'image', $value);
					$pattern = '/thumbnail/';
					$value = preg_replace($pattern, 'thumb', $value);
					$pattern = '/embed/';
					$value = preg_replace($pattern, 'youtube', $value);
					$pattern = '/PN\(RN\)/';
					$value = preg_replace($pattern, 'name', $value);
					if($key >= 8 && $key <= 15) {
						$csv_header_age_category_data_array[$key] = $value;
					$csv_header_data_array[$key] = 'age';
					}
						else if($key >= 16 && $key <= 18) {
							$csv_header_subject_category_data_array[$key] = $value;
						$csv_header_data_array[$key] = 'subject';
						}
							else if($key >= 19 && $key <= 77) {
								$csv_header_data_array[$key] = 'keyword';
							}
								else {
									$csv_header_data_array[$key] = $value;
								}
					if($csv_header_keywordCloud_category_data_array[$key] == '') {
						$csv_header_native_data_array[$key] = $value;
					}
						else {
							$csv_header_native_data_array[$key] = $csv_header_keywordCloud_category_data_array[$key];
						}
				}
					$i++;
				}
//				else if($i < 100) {
				// 3行目以降
				else {
//					pre_var_dump($line);
					foreach($line as $key => $value) {
//						pre_var_dump($value);
						// UTF-8に変換
						$value = mb_convert_encoding($value,'UTF-8', mb_detect_encoding($value, ['ASCII', 'ISO-2022-JP', 'UTF-8', 'EUC-JP', 'SJIS'], true));

						// text以外を空白削除する
						if($csv_header_data_array[$key] != 'text') {
							// headの場合
							if($csv_header_data_array[$key] == 'head') {
								$value = str_replace(array(' ', ' '), '', $value);
							}
								// head以外の場合
								else {
									$value = str_replace(array(' ', ' ', '　'), '', $value);
								}
						}

						// ageの場合
						if($csv_header_data_array[$key] == 'age') {
							if($value) {
								$csv_data_array[$i-3][$csv_header_data_array[$key]][] = $csv_header_native_data_array[$key];
							}
						}
							// subjectの場合
							else if($csv_header_data_array[$key] == 'subject') {
								if($value) {
									$csv_data_array[$i-3][$csv_header_data_array[$key]][] = $csv_header_native_data_array[$key];
								}
							}
								// keywordの場合
								else if($csv_header_data_array[$key] == 'keyword') {
								if($value) {
									$csv_data_array[$i-3][$csv_header_data_array[$key]][] = $csv_header_native_data_array[$key];
								}
/*
									if($value != '') {
										$keyword_type_check = false;
										foreach($csv_data_keyword_type_list_array as $csv_data_keyword_type_list_array_key => $csv_data_keyword_type_list_array_value) {
											if($csv_data_keyword_type_list_array_value == $value) {
												$keyword_type_check = true;
											}
										}
										if($keyword_type_check) {
			
										}
											else {
												$csv_data_keyword_type_list_array[] = $value;
											}
										$csv_data_array[$i-1][$csv_header_data_array[$key]][] = $value;
									}
*/
								}
									// 通常カラム時に通る場所
									else {
										$csv_data_array[$i-3][$csv_header_data_array[$key]] = $value;
									}
					} // foreach($line as $key => $value) {
					$i++;
				} // else {
		} // while($line = fgetcsv($fp)) {
		// sample.csvを閉じる
		fclose($f);

		// ''を削除
		foreach($csv_header_keywordCloud_category_data_array as $key => $val) {
			if($val == '') {
				//削除実行
				unset($csv_header_keywordCloud_category_data_array[$key]);
			}
		}
		// 歯抜けになった配列を0から揃える
		$csv_header_keywordCloud_category_data_array = array_values($csv_header_keywordCloud_category_data_array);
		// 当初予定していた変数に入れ込む
		$csv_data_age_type_list_array     = $csv_header_age_category_data_array;
		$csv_data_subject_type_list_array = $csv_header_subject_category_data_array;
		$csv_data_keyword_type_list_array = $csv_header_keywordCloud_category_data_array;

		// 配列の文字コードを変換
	//	mb_convert_variables('UTF-8', 'ASCII', $csv_data_array); // 多重配列は無理っぽい？
		return array($csv_data_array, $csv_data_age_type_list_array, $csv_data_subject_type_list_array, $csv_data_keyword_type_list_array);
	}



	// csvデータをarrayにコンバートさせて取得 関数呼び出し
	list($csv_data_array, $csv_data_age_type_list_array, $csv_data_subject_type_list_array, $csv_data_keyword_type_list_array) = csv_data_convert_array_get($filepath);

/*
チェック
	pre_var_dump($csv_data_age_type_list_array);
	pre_var_dump($csv_data_subject_type_list_array);
	pre_var_dump($csv_data_keyword_type_list_array);
	pre_var_dump($csv_data_array);
*/

	// idを正しくする
	foreach($csv_data_array as $key => $value) {
		$csv_data_array[$key]['id'] = ((int)$value['id']) - 1;
	}

	// age関連のlist_array骨組み作成
	$csv_data_age_list_array = array();
	foreach($csv_data_age_type_list_array as $csv_data_age_type_list_array_key => $csv_data_age_type_list_array_value) {
		$csv_data_age_list_array[$csv_data_age_type_list_array_key]['text']   = $csv_data_age_type_list_array_value;
		$csv_data_age_list_array[$csv_data_age_type_list_array_key]['weight'] = 0;
		$csv_data_age_list_array[$csv_data_age_type_list_array_key]['list']   = array();
	}
	
	$csv_data_age_count = 0;
	
	// $age_cloud_data_array作成
	$age_cloud_data_array = array();
	foreach($csv_data_array as $age_cloud_data_array_key => $age_cloud_data_array_value) {
		if($age_cloud_data_array_value['age']) {
			foreach($age_cloud_data_array_value['age'] as $age_cloud_data_array_value_age_key => $age_cloud_data_array_value_age_value) {
				foreach($csv_data_age_list_array as $csv_data_age_list_array_key => $csv_data_age_list_array_value) {
					if($age_cloud_data_array_value_age_value == $csv_data_age_list_array_value['text']) {
						$csv_data_age_list_array[$csv_data_age_list_array_key]['weight']++;
						$csv_data_age_list_array[$csv_data_age_list_array_key]['list'][] = $age_cloud_data_array_value['id'];
					}
	//				$csv_data_age_list_array[] = $age_cloud_data_array_value_age_value;
				} // foreach($csv_data_age_list_array as $csv_data_age_list_array_key => $csv_data_age_list_array_value) {
			} // foreach($age_cloud_data_array_value['age'] as $age_cloud_data_array_value_age_key => $age_cloud_data_array_value_age_value) {
		} // if($age_cloud_data_array_value['age']) {
			// ageが存在しないarray
			else {
				// なくてもいいのかな？''を入れるべき？
			}
	} //foreach($csv_data_array as $age_cloud_data_array_key => $age_cloud_data_array_value) {
	//pre_var_dump($csv_data_array);
	//pre_var_dump($csv_data_age_list_array);
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	//pre_var_dump($test_array);
	// subject関連のlist_array骨組み作成
	$csv_data_subject_list_array = array();
	foreach($csv_data_subject_type_list_array as $csv_data_subject_type_list_array_key => $csv_data_subject_type_list_array_value) {
		$csv_data_subject_list_array[$csv_data_subject_type_list_array_key]['text']   = $csv_data_subject_type_list_array_value;
		$csv_data_subject_list_array[$csv_data_subject_type_list_array_key]['weight'] = 0;
		$csv_data_subject_list_array[$csv_data_subject_type_list_array_key]['list']   = array();
	}
	
	$csv_data_subject_count = 0;
	//pre_var_dump($csv_data_subject_type_list_array);
	
	// $subject_cloud_data_array作成
	$subject_cloud_data_array = array();
	foreach($csv_data_array as $subject_cloud_data_array_key => $subject_cloud_data_array_value) {
		if($subject_cloud_data_array_value['subject']) {
			foreach($subject_cloud_data_array_value['subject'] as $subject_cloud_data_array_value_subject_key => $subject_cloud_data_array_value_subject_value) {
				foreach($csv_data_subject_list_array as $csv_data_subject_list_array_key => $csv_data_subject_list_array_value) {
					if($subject_cloud_data_array_value_subject_value == $csv_data_subject_list_array_value['text']) {
						$csv_data_subject_list_array[$csv_data_subject_list_array_key]['weight']++;
						$csv_data_subject_list_array[$csv_data_subject_list_array_key]['list'][] = $subject_cloud_data_array_value['id'];
					}
	//				$csv_data_subject_list_array[] = $subject_cloud_data_array_value_subject_value;
				} // foreach($csv_data_subject_list_array as $csv_data_subject_list_array_key => $csv_data_subject_list_array_value) {
			} // foreach($subject_cloud_data_array_value['subject'] as $subject_cloud_data_array_value_subject_key => $subject_cloud_data_array_value_subject_value) {
		} // if($subject_cloud_data_array_value['subject']) {
			// subjectが存在しないarray
			else {
				// なくてもいいのかな？''を入れるべき？
			}
	} //foreach($csv_data_array as $subject_cloud_data_array_key => $subject_cloud_data_array_value) {
	//pre_var_dump($csv_data_array);
	//pre_var_dump($csv_data_subject_list_array);
	
	////////////////////////////////////////////////////////////////////////////////////////////////
		
	//pre_var_dump($test_array);
	// keyword関連のlist_array骨組み作成
	$csv_data_keyword_list_array = array();
	foreach($csv_data_keyword_type_list_array as $csv_data_keyword_type_list_array_key => $csv_data_keyword_type_list_array_value) {
		$csv_data_keyword_list_array[$csv_data_keyword_type_list_array_key]['text']   = $csv_data_keyword_type_list_array_value;
		$csv_data_keyword_list_array[$csv_data_keyword_type_list_array_key]['weight'] = 0;
		$csv_data_keyword_list_array[$csv_data_keyword_type_list_array_key]['list']   = array();
	}
	
	$csv_data_keyword_count = 0;
	//pre_var_dump($csv_data_keyword_type_list_array);
	
	// $keyword_cloud_data_array作成
	$keyword_cloud_data_array = array();
	foreach($csv_data_array as $keyword_cloud_data_array_key => $keyword_cloud_data_array_value) {
		if($keyword_cloud_data_array_value['keyword']) {
			foreach($keyword_cloud_data_array_value['keyword'] as $keyword_cloud_data_array_value_keyword_key => $keyword_cloud_data_array_value_keyword_value) {
				foreach($csv_data_keyword_list_array as $csv_data_keyword_list_array_key => $csv_data_keyword_list_array_value) {
					if($keyword_cloud_data_array_value_keyword_value == $csv_data_keyword_list_array_value['text']) {
						$csv_data_keyword_list_array[$csv_data_keyword_list_array_key]['weight']++;
						$csv_data_keyword_list_array[$csv_data_keyword_list_array_key]['list'][] = $keyword_cloud_data_array_value['id'];
					}
	//				$csv_data_keyword_list_array[] = $keyword_cloud_data_array_value_keyword_value;
				} // foreach($csv_data_keyword_list_array as $csv_data_keyword_list_array_key => $csv_data_keyword_list_array_value) {
			} // foreach($keyword_cloud_data_array_value['keyword'] as $keyword_cloud_data_array_value_keyword_key => $keyword_cloud_data_array_value_keyword_value) {
		} // if($keyword_cloud_data_array_value['keyword']) {
			// keywordが存在しないarray
			else {
				// なくてもいいのかな？''を入れるべき？
			}
	} //foreach($csv_data_array as $keyword_cloud_data_array_key => $keyword_cloud_data_array_value) {
	
	
//	pre_var_dump($csv_data_array);
//	pre_var_dump($csv_data_keyword_list_array);
	
/*
	pre_var_dump($csv_data_array);
	pre_var_dump($csv_data_age_list_array);
	pre_var_dump($csv_data_subject_list_array);
	pre_var_dump($csv_data_keyword_list_array);
*/

	$json_data = array(
		'post'         => $csv_data_array,
		'tagCloud'     => $csv_data_age_list_array,
		'subjectCloud' => $csv_data_subject_list_array,
		'keywordCloud' => $csv_data_keyword_list_array,
	);

//pre_var_dump($json_data); // 最終チェック

	////////////////////////////////////////////////////////////////////////////////////////////////
	// javascriptに返す時用
	
	//header ("Content-Type: text/javascript; charset=utf-8");
	//return json_encode($json_data);
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	$json_file_name = 'post.json';
	// sample.jsonファイルを生成
	$json = fopen($json_file_name, 'w+b');
	fwrite($json, json_encode($json_data, JSON_UNESCAPED_UNICODE));

	// native_sample.jsonファイルを生成
//	$json = fopen('native_sample.json', 'w+b');
//	fwrite($json, json_encode($json_data));

	fclose($json);
	////////////////////////////////////////////////////////////////////////////////////////////////
		$content_text = 
			''.$_POST['path'].' を解析いたしました <a href="'.$json_file_name.'" download="'.$json_file_name.'">'.$json_file_name.'をダウンロードする</a>
			<div>
				<a href="">戻る</a>
			</div>';
			$html = html_basic_get($content_text, TITLE);
			echo $html;
}
	// 
	else {
		$content_html = '
			<style>
				.form {
					margin: 30px;
				}
				.m_0 {
					margin: 0;
				}
				.path {
					border: 1px solid #DADADA;
					border-radius: 3px;
					color: #3A3A3A;
					font-size: 16px;
					padding: 5px 7px;
					width: 200px;
					-webkit-appearance: none;
					-moz-appearance: none;
					appearance: none;
				}
				.button {
					background-color: #599CFC;
					border: 1px solid #4B8EFD;
					border-radius: 3px;
					color: #FCFCFC;
					cursor: pointer;
					margin: 0 0 0 146px;
					padding: 5px;
					text-decoration: none;
					-webkit-appearance: none;
					-moz-appearance: none;
					appearance: none;
				}
			</style>		
			<form class="form" name="form" action="" method="post">
				<!-- block -->
				<div class="block">
					<p class="m_0">
						<label for="path">読み込みファイルパスを指定して下さい
					</label></p>
							<input type="text" class="path" id="path" name="path" value="" size="20">
				</div>
				<!-- submit -->
				<p class="submit clearfix">
					<input type="submit" class="button" name="submit" value="読み込み">
				</p>
			</form>';
			$html = html_basic_get($content_html, TITLE);
		echo($html);
	}

