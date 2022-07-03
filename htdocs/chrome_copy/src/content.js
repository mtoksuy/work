$(function() {
	var title = '';
	var url = '';
	var pagespeed_url = '';
	$('.action-menu').remove();
	  $('.yuRUbf a').each(function(index, element) {
		title            = title+$(element).find('.LC20lb').text()+'<br>';
		url              = url+$(element).attr('href')+'<br>';
		pagespeed_url = pagespeed_url+'https://developers.google.com/speed/pagespeed/insights/?hl=ja&url='+encodeURIComponent($(element).attr('href'))+'&tab=mobile'+'<br>';
//		pagespeed_url = pagespeed_url+encodeURIComponent($(element).attr('href'))+'<br>';
	  });
	  title = '<div>'+title+'</div>';
	  url = '<div style="margin: 0 0 0 185px;">'+url+'</div>';
//	  $('#footcnt').prepend(pagespeed_url);
	  $('#footcnt').prepend('<div style="margin: 0 0 0 185px;">------------------------------------------------------------------------------------------------------------</div><br>');
	  $('#footcnt').prepend(url);
//	  $('#footcnt').prepend(title);
/*
		document.write(title);
		document.write(url);
*/
});