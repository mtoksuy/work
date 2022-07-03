$(function() {
	$('.news div:nth-child(3n)').css('margin','0 0 12px 0');
	$('#footer #sitemap .inner .in:nth-child(4n)').css('margin','0 0 0 0');
	$('#key_visual').bxSlider({
  	auto: true,
  	pager: true,
  	speed: 600,
  	controls: true,
  	prevImage: '/images/btn_left.png',
  	nextImage: '/images/btn_right.png'
	});
	$('.visual01').backstretch("/images/main01.jpg");
	$('.visual02').backstretch("/images/main02.jpg");
	$('.visual03').backstretch("/images/main03.jpg");
	
	// リストの総数を取得
	
	var nam01 = $("#tab01 .inner").length;

	// リストの6つめ以降非表示
	$("#tab01 .inner:gt(5)").hide();

	var Num = 6;
	$(".btnClick01").click(function(){
		// クリックするごとに+12
		Num +=12;
		// Num+3つ目以前を表示
	$("#tab01 .inner:lt("+Num+")").show("fade");

		// ボタン消す
		if(nam01 <= Num){
			$(".btnClick01").hide();
		};
	});
	
	var nam02 = $("#tab02 .inner").length;

	// リストの6つめ以降非表示
	$("#tab02 .inner:gt(5)").hide();

	var Num02 = 6;
	$(".btnClick02").click(function(){
		// クリックするごとに+12
		Num02 +=12;
		// Num+3つ目以前を表示
	$("#tab02 .inner:lt("+Num02+")").show("fade");

		// ボタン消す
		if(nam02 <= Num02){
			$(".btnClick02").hide();
		};
	});
	
	var nam03 = $("#tab03 .inner").length;

	// リストの6つめ以降非表示
	$("#tab03 .inner:gt(5)").hide();

	var Num03 = 6;
	$(".btnClick03").click(function(){
		// クリックするごとに+12
		Num03 +=12;
		// Num+3つ目以前を表示
	$("#tab03 .inner:lt("+Num03+")").show("fade");

		// ボタン消す
		if(nam03 <= Num03){
			$(".btnClick03").hide();
		};
	});
	$('a[href=#]').click(function(){
		return false;
	});
});
