

var utils={
	
	setTraceBox:function(){
		//テスト用出力ボックスのセット
		var traceBox=$("<div class='tracebox'>test</div>");
		traceBox.css({"position":"absolute","top":"50px","left":"10px","z-index":100,
		"position":"fixed","font-size":"12px","color":"red"});
		if(utils.Browser=="ie6"){
			traceBox.css({"position":"absolute"});
		}
		$("body").append(traceBox);
		window["trace"]=function(txt){
			traceBox.html(traceBox.html()+"<br />"+String(txt));
		}
		window["trace2"]=function(txt){
			traceBox.html(String(txt));
		}
	},
	
	hideTraceBox:function(){
		$('.tracebox').remove();
	},
	
	setScaleBase:function(){
		//位置チェック用スケール
		var scaleBox=$("<div></div>");
		scaleBox.css({"position":"absolute","top":"100px","left":"20px","z-indx":100,
		"bautils.Bckground-color":"red","width":"200px","height":"10px"});
		
		$("body").append(scaleBox);
		window["setScale"]=function(l,t){
			if(!l){l=0;}
			if(!t){t=0;}
			scaleBox.css({"left":l+"px","top":t+"px"})
		}
	},
	
	init:function(){
		//ブラウザの判定など
		
		var ua=utils.ua;
		ua=navigator.userAgent;
		utils.ua=ua;
		
		if(ua.indexOf('Chrome')>-1){
				utils.Browser="chrome";
		}else if(ua.indexOf('Safari')> -1){
			if(ua.indexOf('iPhone') > -1 || ua.indexOf('iPad') > -1){
				utils.Browser="iPhone";
			}else if(ua.indexOf('iPod')  > -1){
				utils.Browser="iPod"
			}else if(ua.indexOf('Android') > -1){
				utils.Browser="Android";
			}else{
				utils.Browser="safari";
			}
		}else if(ua.indexOf('Firefox')> -1){
			utils.Browser="firefox";	
		}else if(ua.indexOf('Opera')> -1){
			utils.Browser="opera";	
		}else if(ua.indexOf('MSIE 9')> -1){
			utils.Browser="ie9";	
		}else if(ua.indexOf('MSIE 8')> -1){
			utils.Browser="ie8";
		}else if(ua.indexOf('MSIE 7')> -1){
			utils.Browser="ie7";
		}else if(ua.indexOf('MSIE 6')> -1){
			utils.Browser="ie6";
		}else{
			utils.Browser="atherWebBrowsew";
		}
		
		if(utils.Browser=="ie6"|| utils.Browser=="ie7"|| utils.Browser=="ie8"){
			utils.ie=true;	
		}
		
		if(utils.Browser.indexOf("ie")==0){
			utils.dv="ie";
		}else if(utils.Browser=="iPhone"||utils.Browser=="iPad"||utils.Browser=="Android"){
			utils.dv="sp";
		}else{
			utils.dv="mb";
		}
	},
	
	ua:navigator.userAgent,
	
	ie:false,
	
	Browser:null,
	
	brink:function(){
		//ブリンク
		$(this).stop().css({"opacity":0.2}).animate({opacity:1},1500,"easeOutCubic");
	},
	
	brinkSoft:function(){
		$(this).stop().css({"opacity":0.2}).delay(100).animate({opacity:1},1000,"easeOutCubic");
		$(this).blur();
	},
	
	delayFunc:function (f,time){
		//遅延実行関数
		var timer=setTimeout(doFunction,time);
		function doFunction(){
			f();
			clearTimeout(timer);
		}
	},
	
	delayTimer:{
		//遅延実行関数2
		func:"a",
		time:"b",
		set:function(f,t){
			this.func=f;
			this.time=t;
		},
		timer:null,
		start:function(){
			var f=this.func;
			this.timer=setTimeout(function(){
				f();
			},this.time)
		},
		stop:function(){
			if(this.timer){
				trace("stop")
				clearTimeout(this.timer);
				this.timer=null;
			}
		}	
	},
	
	makeDelay:function(){
		var timerObj=new Object()
		timerObj={
			func:"a",
			time:"b",
			set:function(f,t){
					this.func=f;
					this.time=t;
			},
			timer:null,
			start:function(){
				var f=this.func;
				this.timer=setTimeout(function(){
					f();
				},this.time)
			},
			stop:function(){
				if(this.timer){
					clearTimeout(this.timer);
					this.timer=null;
				}
			}	
		}
		return timerObj;
	},
	
	unselectable:function(obj){
		//選択不可にする
		obj.attr("onSelectStart", "return false;");
		obj.attr("onMouseDown", "return false;");
		obj.attr("unselectable","on");
		obj.css({"-moz-user-select": "none", "-khtml-user-select": "none", "user-select":"none","-webkit-user-select": "none" });
		//for(var n=0;n<obj.length;n++){
			//}
		obj.focus(function(){$(this).blur()})
	},
	
	jump:function(url,target){
		//リンク先へジャンプ
		if(target=="_blank"){
			var win=window.open(url,"win1");
		}else{
			window.location.href=url;	
		}
	},
	
	getImageSize:function(image){
		//画像サイズ取得
		var run, mem, w, h, key = "actual";
 
		// for Firefox, Safari, Google Chrome
		if ("naturalWidth" in image) {
			return {width: image.naturalWidth, height: image.naturalHeight};
		}
		if ("src" in image) { // HTMLImageElement
			if (image[key] && image[key].src === image.src) {return  image[key];}
			 
			if (document.uniqueID) { // for IE
				w = $(image).css("width");
				h = $(image).css("height");
			} else { // for Opera and Other
				mem = {w: image.width, h: image.height}; // keep current style
				$(this).removeAttr("width").removeAttr("height").css({width:"",  height:""});    // Remove attributes in case img-element has set width  and height (for webkit browsers)
				w = image.width;
				h = image.height;
				image.width  = mem.w; // restore
				image.height = mem.h;
			}
			return image[key] = {width: w, height: h, src: image.src}; // bond
		}
		 
		// HTMLCanvasElement
		return {width: image.width, height: image.height};
	},
	
	transparentIePng:function(obj){//透明png対策//
		$("img",obj).css({"filter":"alpha(opacity=0)"});
		var imgQt=$("img",obj).length;
		
		for(var n=0;n<imgQt;n++){
			
			var targetImg=$($("img",obj)[n]);
			targetImg.css("width",targetImg.width()+"px");
			var parents=targetImg.parents();
			var parentsQt=parents.length;
			var parentDiv;
			
			if(parents[0].tagName=="DIV"){
				
				parentDiv=$(parents[0]);
				parentDiv.css("width",targetImg.width()+"px");
			}else{
				parentDiv=$(parents[1]);
				var fontSize=parseInt(parentDiv.css('font-size'))
				//trace(fontSize)
				if(parents[1].tagName=="LI"){
					//タグがliだった場合、divタグで囲む
					parentDiv.css("width",targetImg.width()+fontSize+"px");
					parentDiv.wrapInner("<div></div>");
					parentDiv=$("div",parentDiv);
					parentDiv.css("width",targetImg.width()+fontSize+"px");
				}else{
					parentDiv.css("width",targetImg.width()+"px");
				}
				
				if(parents[0].tagName=="A"){
					if($(parents[0]).attr("onclick")){
						parentDiv.click($(parents[0]).attr("onclick"))
					}else{
						var linkString=$(parents[0]).attr('href');
						
						if($(parents[0]).attr('href')!="#"){
							//何もしない
						}
						parentDiv.attr("link",linkString);
						parentDiv.click(function(){location.href=$(this).attr("link")})
					}
					parentDiv.css("cursor","pointer")
				}
			}
			if(targetImg.attr("src").indexOf("transparent.gif")==-1 && targetImg.attr("src")!=""){
				parentDiv.css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+targetImg.attr("src")+"',sizingmethod='crop');");
				//parentDiv.css({"visibility":"visible","opacity":1});
				
			}
		}
	},
	
	transparentIeBackPng:function(obj){
		var Qt=obj.length;
		//trace(Qt)
		for(var n=0;n<Qt;n++){
			var targetDom=$(obj[n]);
			var targetString=targetDom.css("background-image");
			var imgIndex=targetString.indexOf("img/")+3;
			
			var targetImg=ca.imgPath+targetString.slice(imgIndex,-2);
			targetDom.css("background-image","none");
			//半透明部分に色がつくのを防ぐため、もうひとくるみする。
			targetDom.wrapInner("<div></div>");
			var targetInner=$("div",targetDom)
			targetInner.css({"width":targetDom.width()+"px","height":targetDom.height()+"px"});
			targetInner.css("filter","progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+targetImg+"',sizingmethod='crop');");
			
		}
	},
	
	hitCheck:function(obj,mousep){
		var objQt=obj.length;
		var key=false;
		
		for(var n=0;n<objQt;n++){
			var minX,minY,maxX,maxY;
			var targetObj=$(obj[n])
			var offset=targetObj.offset()
			 minX=offset.left;
			 maxX=minX+targetObj.width();
			 minY=offset.top;
			 maxY=minY+targetObj.height();
		
			 if(mousep.x>minX && mousep.x<maxX && mousep.y>minY && mousep.y<maxY){
				 return true; 
				 break;
			 }
		}
		
		return false;
	},
	
	
	hitCheckMargin:function(obj,mousep){
		var objQt=obj.length;
		var key=false;
		
		for(var n=0;n<objQt;n++){
			var minX,minY,maxX,maxY;
			var targetObj=$(obj[n])
			var offset=targetObj.offset()
			 minX=offset.left-20;
			 maxX=minX+targetObj.width()+20;
			 minY=offset.top-20;
			 maxY=minY+targetObj.height()+20;
		
			 if(mousep.x>minX && mousep.x<maxX && mousep.y>minY && mousep.y<maxY){
				 return true; 
				 break;
			 }
		}
		
		return false;
	},
	
	chProp:function (prop){
		//スタイルシートに対応しているかチェック
		var prefix=['','ms','Moz','webkit','o'];
		var result=false;
		var topCar=prop.slice(0,1).toUpperCase();
		var pre;
		prop=topCar+prop.slice(1);
		for(var n=0;n<prefix.length;n++){
			if(prefix[n]+prop in document.body.style){
				pre=prefix[n];
				result=true;
				break;
			}
		}
		return {active:result,prefix:pre};
	}	
}