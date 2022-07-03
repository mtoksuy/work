		// 変数群
		var map,svp;
		//--------
		//初期設定
		//--------
		function initialize() {
			// 緯度・経度
			var latlng = new google.maps.LatLng(36.109864,140.101522);
  		// 地図のオプション設定
			var myOptions = {
				// 向き
				heading: -20,
				// 初期のズーム レベル
				zoom: 16,
				// 地図の中心点
				center:latlng,
				// 地図タイプ
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};


			//地図オブジェクト生成
			map = new google.maps.Map(document.getElementById("map"), myOptions);
			// ストリートビューオブジェクト生成
			svp = new google.maps.StreetViewPanorama(
				document.getElementById("svp"),{
					position : map.getCenter()
				});
			// ストリートビューオブジェクト設定
			svp.setPov({heading: -20, pitch: 0, zoom: 0});
			// マップとストリートビューの一致させる為の記述?
			map.setStreetView(svp);
//			p(svp);
			// 回転くるくる
			timerID = setInterval("kaiten()",20);
		}
		// デバッグ関数
		function dbg(str) {
			document.getElementById("res").value=str+"\n"+document.getElementById("res").value;
			if(str == "closeclick") {
				document.getElementsByName("visible")[0].checked=false;
			}
		}
		// 回転関数
		function kaiten() {
			// カメラの位置取得
			var pov      = svp.getPov();
			var _heading = pov["heading"];
			var _pitch   = pov["pitch"];
			var _zoom    = pov["zoom"];
			// 位置をずらす
			_heading     = _heading + 0.1;
			// 360回転(いらないと思います)
			if(_heading >= 360) _heading = 0;
			// 位置情報をセット(一番大事)
			svp.setPov( {
				heading:_heading, pitch:_pitch, zoom:_zoom
			});
		}
		// ロード時に初期化
		google.maps.event.addDomListener(window, 'load', initialize);
/***********  固定設定終了 ***********/

		$(function() {

		utils.init();
		// ブリンク
		$('.brinkSoft').mouseover(utils.brinkSoft);
			// 変数宣言
			var flag = 0;
			var latlng_array;
			var svp_area = document.getElementById("svp_area");
			//------------------------------
			//svp_areaにマウスオーバーしたら
			//------------------------------
			$('#svp_area').mouseover(function() {
				// くるくる解除
				clearInterval(timerID);
			});
			//----------------------------
			//svp_areaにマウスアウトしたら
			//----------------------------
			$('#svp_area').mouseout(function() {
				// くるくる再スタート
				timerID = setInterval("kaiten()",150);
			});
			//-----------------
			// 左の矢印クリック
			//-----------------
			$('.y_l_btn').click(function(this_i) {
				var left_px     = ($('.slide_show_contents ul').css('left'));
				left_px         = left_px.replace('px','');
				left_px         = parseInt(left_px);
				var image       = $('.slide_show_contents ul li img');
				var image_widht = image[0].width;
				if(flag == 0) {
						var first_li = $(".slide_show_contents ul li").eq(7);
						$('.slide_show_contents ul').prepend(first_li);
						$('.slide_show_contents ul').css({"left": -(image_widht + 30) + 'px'});
					$('.slide_show_contents ul').animate({left: 0 + 'px'} ,500,"swing",function(){
						flag = 0;
					});
					flag = 1;
				}
					else {
					}
			}); // $('.y_l_btn').click(function(this_i) {
			//-----------------
			// 右の矢印クリック
			//-----------------
			$('.y_r_btn').click(function(this_i) {
				var left_px     = ($('.slide_show_contents ul').css('left'));
				left_px         = left_px.replace('px','');
				left_px         = parseInt(left_px);
				if(isNaN(left_px)) { left_px = 0;}
				var image       = $('.slide_show_contents ul li img');
				var image_widht = image[0].width;
				if(flag == 0) {
					$('.slide_show_contents ul').animate({left: left_px + -(image_widht + 30) + 'px'} ,500,"swing",function(){
						var first_li = $(".slide_show_contents ul li").eq(0);
						$('.slide_show_contents ul').append(first_li);
						$('.slide_show_contents ul').css({"left": left_px + 'px'});
						flag = 0;
						});
					flag = 1;
				}
					else {
					}
			}); // $('.y_l_btn').click(function(this_i) {
			var id=0;
			var latlng_i_array = $([36.109866,36.10307,36.103417,36.108305,36.110594,36.111391]);
			var latlng_k_array = $([140.101519,140.103021,140.10581,140.10242,140.101218,140.104566]);
			$('.slide_show_contents ul li').each(function(){
				$(this).data("id",id);
				id+=1;
			});
			//------------
			//画面きりかえ
			//------------
			$('.slide_show_contents ul li').click(function(this_li) {
				var id=$(this).data("id");
				svp.setPosition(new google.maps.LatLng(latlng_i_array[id], latlng_k_array[id]));
				map.panTo(new google.maps.LatLng(latlng_i_array[id], latlng_k_array[id]));
			});
		}); // $(function() {