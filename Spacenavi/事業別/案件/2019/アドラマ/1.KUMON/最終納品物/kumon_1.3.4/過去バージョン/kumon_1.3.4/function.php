<?php
//-----------------
// プレヴァーダンプ
//-----------------
function pre_var_dump($data = '') {
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}
//---------------
// html基本形関数
//---------------
function html_basic_get($content, $title = '') {
	$html = 
		'<html>
			<head>
				<title>'.$title.'</title>
				<meta charset="utf-8">
				<link rel="stylesheet" href="assets/css/core.css" type="text/css">
			</head>
			<body>
				'.$content.'
			</body>
		</html>';
	return $html;
}
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
					$value = preg_replace($pattern, 'post_number', $value);
					$pattern = '/カテゴリ/';
					$value = preg_replace($pattern, 'category', $value);
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
					if($key >= 9 && $key <= 16) {
						$csv_header_age_category_data_array[$key] = $value;
					$csv_header_data_array[$key] = 'age';
					}
						else if($key >= 17 && $key <= 19) {
							$csv_header_subject_category_data_array[$key] = $value;
						$csv_header_data_array[$key] = 'subject';
						}
							else if($key >= 20 && $key <= 77) {
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
//				else if($i < 4) {
				// 3行目以降
				else {
					// id
					$csv_data_array[$i-3]['id'] = $i-3;
					foreach($line as $key => $value) {
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

						// post_numberの場合
						if($csv_header_data_array[$key] == 'post_number') {
							$csv_data_array[$i-3][$csv_header_data_array[$key]] = (int)$value;
						}
							// ageの場合
							else if($csv_header_data_array[$key] == 'age') {
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
									}
										// 通常カラム時に通る場所
										else {
											$csv_data_array[$i-3][$csv_header_data_array[$key]] = $value;
										}
					} // foreach($line as $key => $value) {
					$i++;
				} // else {
		} // while($line = fgetcsv($fp)) {
		// csvを閉じる
		fclose($fp);

		// ''を削除
		foreach($csv_header_keywordCloud_category_data_array as $key => $val) {
			if($val == '') {
				//削除実行
				unset($csv_header_keywordCloud_category_data_array[$key]);
			}
		}
		// 歯抜けになった配列を0から揃える
		$csv_header_age_category_data_array          = array_values($csv_header_age_category_data_array);
		$csv_header_subject_category_data_array      = array_values($csv_header_subject_category_data_array);
		$csv_header_keywordCloud_category_data_array = array_values($csv_header_keywordCloud_category_data_array);

		// 当初予定していた変数に入れ込む
		$csv_data_age_type_list_array     = $csv_header_age_category_data_array;
		$csv_data_subject_type_list_array = $csv_header_subject_category_data_array;
		$csv_data_keyword_type_list_array = $csv_header_keywordCloud_category_data_array;

		// 配列の文字コードを変換
	//	mb_convert_variables('UTF-8', 'ASCII', $csv_data_array); // 多重配列は無理っぽい？
		return array($csv_data_array, $csv_data_age_type_list_array, $csv_data_subject_type_list_array, $csv_data_keyword_type_list_array);
	}
