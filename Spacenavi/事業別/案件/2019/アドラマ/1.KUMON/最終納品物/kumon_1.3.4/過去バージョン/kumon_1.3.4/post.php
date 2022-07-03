<?php
/*************
デフォルト変数
*************/
define('TITLE', 'KUMON jsonダウンロード');
/***********
関数呼び出し
***********/
require_once "function.php";
/***********************************
読み込みファイルパスを指定すると発動
***********************************/
//if($_POST['path']) {
if($_FILES['file']['name']) {
	$uploads_dir = '';
	// 不正なファイル名を省いて名前取得
	$file_name = basename($_FILES['file']['name']);
	// 拡張子取得
	$ext       = substr($file_name, strrpos($file_name, '.') + 1);
	// アップロードファイル名
	$file_name = 'upload.'.$ext;
	// アップロード
	if(move_uploaded_file($_FILES['file']['tmp_name'], $file_name)) {
	
	}
		else {
			$content_text = $_FILES['file']['name'].' は不正なファイル名またはファイルです <a href="">戻る</a>';
			$html = html_basic_get($content_text, TITLE);
			echo $html;
			exit;
		}
	//PHPExcelの読み込み
	require_once('PHPExcel-1.8/Classes/PHPExcel.php');
	require_once('PHPExcel-1.8/Classes/PHPExcel/IOFactory.php');

	// xlsxファイルの場所
	$readFile = $file_name;
	// csvファイル名
	$csv_name = 'excele.csv';
	// xlsxをPHPExcelでロード
	$objPExcel = PHPExcel_IOFactory::load($readFile);
	// Writer作成
	$writer = PHPExcel_IOFactory::createWriter($objPExcel, 'CSV');
	// csv保存
	$writer->save($csv_name);	
	// 作成したcsvファイル格納場所を変数に入れる
	$filepath = $csv_name;
	setlocale(LC_ALL, 'ja_JP.UTF-8');
	// csvデータをarrayにコンバートさせて取得 関数呼び出し
	list($csv_data_array, $csv_data_age_type_list_array, $csv_data_subject_type_list_array, $csv_data_keyword_type_list_array) = csv_data_convert_array_get($filepath);

	////////////////////////////////////////////////////////////////////////////////////////////////

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
				} // foreach($csv_data_age_list_array as $csv_data_age_list_array_key => $csv_data_age_list_array_value) {
			} // foreach($age_cloud_data_array_value['age'] as $age_cloud_data_array_value_age_key => $age_cloud_data_array_value_age_value) {
		} // if($age_cloud_data_array_value['age']) {
			// ageが存在しないarray
			else {
				// なくてもいいのかな？''を入れるべき？
			}
	} //foreach($csv_data_array as $age_cloud_data_array_key => $age_cloud_data_array_value) {
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	
		// subject関連のlist_array骨組み作成
	$csv_data_subject_list_array = array();
	foreach($csv_data_subject_type_list_array as $csv_data_subject_type_list_array_key => $csv_data_subject_type_list_array_value) {
		$csv_data_subject_list_array[$csv_data_subject_type_list_array_key]['text']   = $csv_data_subject_type_list_array_value;
		$csv_data_subject_list_array[$csv_data_subject_type_list_array_key]['weight'] = 0;
		$csv_data_subject_list_array[$csv_data_subject_type_list_array_key]['list']   = array();
	}
	
	$csv_data_subject_count = 0;
	
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
				} // foreach($csv_data_subject_list_array as $csv_data_subject_list_array_key => $csv_data_subject_list_array_value) {
			} // foreach($subject_cloud_data_array_value['subject'] as $subject_cloud_data_array_value_subject_key => $subject_cloud_data_array_value_subject_value) {
		} // if($subject_cloud_data_array_value['subject']) {
			// subjectが存在しないarray
			else {
				// なくてもいいのかな？''を入れるべき？
			}
	} //foreach($csv_data_array as $subject_cloud_data_array_key => $subject_cloud_data_array_value) {
	
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
				} // foreach($csv_data_keyword_list_array as $csv_data_keyword_list_array_key => $csv_data_keyword_list_array_value) {
			} // foreach($keyword_cloud_data_array_value['keyword'] as $keyword_cloud_data_array_value_keyword_key => $keyword_cloud_data_array_value_keyword_value) {
		} // if($keyword_cloud_data_array_value['keyword']) {
			// keywordが存在しないarray
			else {
				// なくてもいいのかな？''を入れるべき？
			}
	} //foreach($csv_data_array as $keyword_cloud_data_array_key => $keyword_cloud_data_array_value) {

	$json_data = array(
		'post'         => $csv_data_array,
		'tagCloud'     => $csv_data_age_list_array,
		'subjectCloud' => $csv_data_subject_list_array,
		'keywordCloud' => $csv_data_keyword_list_array,
	);

//pre_var_dump($json_data); // 最終チェック

	////////////////////////////////////////////////////////////////////////////////////////////////
	// sample.jsonファイルを生成
	$json_file_name = 'post.json';
	$json = fopen($json_file_name, 'w+b');
	fwrite($json, json_encode($json_data, JSON_UNESCAPED_UNICODE));

	// クローズ
	fclose($json);
	////////////////////////////////////////////////////////////////////////////////////////////////
		$content_text = 
			'<div class="last">アップロードされたファイルをjsonファイルに書き出しました <a href="'.$json_file_name.'" download="'.$json_file_name.'">'.$json_file_name.'をダウンロードする</a>
				<div>
					<a href="">戻る</a>
				</div>
			</div>';
			$html = html_basic_get($content_text, TITLE);
			echo $html;
}
	// 
	else {
		$content_html = '
			<form class="form" name="form" action="" method="post" enctype="multipart/form-data">
				<div class="element_wrap">
					<label>エクセルファイルのアップロードを行いjsonファイルを生成する</label><br>
					<input id="file" type="file" name="file">
				</div>
				<!-- submit -->
				<p class="submit clearfix">
					<input type="submit" class="button" name="submit" value="生成する">
				</p>
			</form>';
			$html = html_basic_get($content_html, TITLE);
		echo($html);
	}

