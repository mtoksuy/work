$(function() {
	$("#index").each(function(index, elem) {
		$("div:odd", $(elem)).css("float", "right");
		$("div:even", $(elem)).css("float", "left");
	});
	$('#footer #sitemap .inner .in:nth-child(4n)').css('margin','0 0 0 0');
	$("table").each(function(){
    $(this).find("tr:odd").addClass("odd");
  });

	$('#index_index div.left,#index_index div.right').flatHeights();
	$("#sub li ul").hide();
  $("#active,#sub li ul ul,#sub > li.on > ul").show();
  $("#sub > li > a").click(function(){
  	var click = $("+ul",this);
		click.slideToggle();
		$("#sub > li > ul").not(click).slideUp();
		return false;
	});
});
