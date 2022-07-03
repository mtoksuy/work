<?php

/*******
独自関数
*******/
// プレヴァーダンプ
function pre_var_dump($data = '') {
	echo '<pre>';
	var_dump($data);
	echo '</pre>';
}

/************
仕様確認array
************/
$arr = 
array(
	'post.json' => array(
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
/*
.........
], "tagCloud":[
{"text":" 幼稚園(または保育園)", "weight":10, "list":[5,17,118,290,320,400,525,623,1002,1483] },
......... ],
"countCloud":[
{"text":" 成績が <br> 伸びた ",
"weight":10, "list":[5,17,118,290,320,400,525,623,1002,1483] },
.........
post.jsonの仕様が変わって、post:“全件分の投稿データ“、tagCloud:“年次と学科のカウントとリスト“、countCloud:“ワードクラウド用キーワードのカウントとリスト“の、3つのプロパティが必要です。
*/////////////////////////////////////////////////////////////////////////////

//PHPExcelの読み込み
require_once('PHPExcel-1.8/Classes/PHPExcel.php');
require_once('PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');

// xlsxファイルの場所
$readFile = '100.xlsx';
//$readFile = '1500.xlsx';

// xlsxをPHPExcelでロード
$objPExcel = PHPExcel_IOFactory::load($readFile);

// 配列形式で返す(現在は使用していない)
$objPExcel->getActiveSheet()->toArray(null,true,true,true);
// Writer作成
$writer = PHPExcel_IOFactory::createWriter($objPExcel, 'csv');
// csv保存
$writer->save('excele.csv');

// 作成したcsvファイル格納場所を変数に入れる
$filepath = 'excele.csv';
setlocale(LC_ALL, 'ja_JP.UTF-8');
//---------------------------------------
// csvデータをarrayにコンバートさせて取得
//---------------------------------------
function csv_data_convert_array_get($filepath) {
	//エラー処理
	if(($fp = fopen($filepath, 'r')) === false) {
		// 必要あれば記述する
	}

	// 必要変数
	$i                     = 0;
	$csv_header_data_array = array();
	$csv_data_array        = array();
	$csv_data_age_type_list_array     = array();
	$csv_data_subject_type_list_array = array();
	$csv_data_keyword_type_list_array = array();

	// csvの行を1行ずつ読み込み
	while($line = fgetcsv($fp)) {
		// 1行目ヘッダー情報取得
		if($i == 0) {
			foreach($line as $key => $value) {
				if($value == '') {
					$csv_header_data_array[$key] = $csv_header_data_array[$key-1];
				}
					else {
						$csv_header_data_array[$key] = $value;
					}
			}
			$i++;
		}
			// 2行目以降
			else {
				foreach($line as $key => $value) {
					// UTF-8に変換
					$value = mb_convert_encoding($value,'UTF-8', mb_detect_encoding($value, ['ASCII', 'ISO-2022-JP', 'UTF-8', 'EUC-JP', 'SJIS'], true));
					// text以外の場合に空白削除
					if($csv_header_data_array[$key] != 'text') {
						$value = str_replace(array(' ', ' ', '　'), '', $value);
					}
					// ageの場合
					if($csv_header_data_array[$key] == 'age') {
						if($value != '') {
							$age_type_check = false;
							foreach($csv_data_age_type_list_array as $csv_data_age_type_list_array_key => $csv_data_age_type_list_array_value) {
								if($csv_data_age_type_list_array_value == $value) {
									$age_type_check = true;
								}
							}
							if($age_type_check) {

							}
								else {
									$csv_data_age_type_list_array[] = $value;
								}
							$csv_data_array[$i-1][$csv_header_data_array[$key]][] = $value;
						}
					}
						// subjectの場合
						else if($csv_header_data_array[$key] == 'subject') {
							if($value != '') {
								$subject_type_check = false;
								foreach($csv_data_subject_type_list_array as $csv_data_subject_type_list_array_key => $csv_data_subject_type_list_array_value) {
									if($csv_data_subject_type_list_array_value == $value) {
										$subject_type_check = true;
									}
								}
								if($subject_type_check) {
	
								}
									else {
										$csv_data_subject_type_list_array[] = $value;
									}
								$csv_data_array[$i-1][$csv_header_data_array[$key]][] = $value;
							}
						}
							// keywordの場合
							else if($csv_header_data_array[$key] == 'keyword') {
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
							}
								// 通常カラム時に通る場所
								else {	
									$csv_data_array[$i-1][$csv_header_data_array[$key]] = $value;
								}
				} // foreach($line as $key => $value) {
				$i++;
			} // else {
	} // while($line = fgetcsv($fp)) {
	// sample.csvを閉じる
	fclose($f);
	// 配列の文字コードを変換
//	mb_convert_variables('UTF-8', 'ASCII', $csv_data_array); // 多重配列は無理っぽい？
	return array($csv_data_array, $csv_data_age_type_list_array, $csv_data_subject_type_list_array, $csv_data_keyword_type_list_array);
}
// csvデータをarrayにコンバートさせて取得 関数呼び出し
list($csv_data_array, $csv_data_age_type_list_array, $csv_data_subject_type_list_array, $csv_data_keyword_type_list_array) = csv_data_convert_array_get($filepath);
/*
pre_var_dump($csv_data_age_type_list_array);
pre_var_dump($csv_data_subject_type_list_array);
pre_var_dump($csv_data_keyword_type_list_array);
pre_var_dump($csv_data_array);
*/
////////////////////////////////////////////////////////////////////////////////////////////////
/*

次はこれを実装する
post:全件分の投稿データ
tagCloud:年次と学科のカウントとリスト
countCloud:ワードクラウド用キーワードのカウントとリスト“の、3つのプロパティが必要です。


tagCloud
	age
{"text":" 幼稚園(または保育園)", "weight":10, "list":[5,17,118,290,320,400,525,623,1002,1483] },

subjectCloud
{"text":"英語", "weight":10, "list":[5,17,118,290,320,400,525,623,1002,1483] },

countCloud(keyword)
{"text":"楽しい", "weight":10, "list":[5,17,118,290,320,400,525,623,1002,1483] },

*/
////////////////////////////////////////////////////////////////////////////////////////////////



$test_array = array(
0 => array(
			"text" => " 幼稚園(または保育園)",
			"weight" =>10,
			"list" => array(5,17,118,290,320,400,525,623,1002,1483),
		),
);





//pre_var_dump($test_array);
// age関連のlist_array骨組み作成
$csv_data_age_list_array = array();
foreach($csv_data_age_type_list_array as $csv_data_age_type_list_array_key => $csv_data_age_type_list_array_value) {
	$csv_data_age_list_array[$csv_data_age_type_list_array_key]['text']   = $csv_data_age_type_list_array_value;
	$csv_data_age_list_array[$csv_data_age_type_list_array_key]['weight'] = 0;
	$csv_data_age_list_array[$csv_data_age_type_list_array_key]['list']   = array();
}

$csv_data_age_count = 0;
//pre_var_dump($csv_data_age_type_list_array);

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


//pre_var_dump($csv_data_array);
//pre_var_dump($csv_data_keyword_list_array);




$csv_data_array;
$csv_data_age_list_array;
$csv_data_subject_list_array;
$csv_data_keyword_list_array;
/*
pre_var_dump($csv_data_array);
pre_var_dump($csv_data_age_list_array);
pre_var_dump($csv_data_subject_list_array);
pre_var_dump($csv_data_keyword_list_array);
*/
$json_data = array(
	'post.json'    => $csv_data_array,
	'tagCloud'     => $csv_data_age_list_array,
	'subjectCloud' => $csv_data_subject_list_array,
	'keywordCloud' => $csv_data_keyword_list_array,
);

////////////////////////////////////////////////////////////////////////////////////////////////
// javascriptに返す時

//header ("Content-Type: text/javascript; charset=utf-8");
//return json_encode($json_data);

////////////////////////////////////////////////////////////////////////////////////////////////
// sample.jsonファイルを生成
$json = fopen('sample.json', 'w+b');
fwrite($json, json_encode($json_data));
fclose($json);
////////////////////////////////////////////////////////////////////////////////////////////////

$html = '
	<html>
		<head></head>
		<body>
			<a href="sample.json" download="sample.json">sample.jsonをダウンロードする</a>
</body>
	</html>
';
print($html);
?>