$(window.parent.document).find("iframe").load(function(){
	if($(document).height() > $(window.parent.document).height() - 175){
		high = $(document).find('body').height()+30;
	}else{
		high = $(window.parent.document).height()-175;
	}
		$(window.parent.document).find("iframe").height(high);
		$(window.parent.document).find("div.embed-responsive").height(high);
});
