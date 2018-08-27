//Confirm
(function($){
	var f=function(){
		$(".confirm").each(function(){
			var msg=$(this).attr("confirm-msg");
			if(msg==undefined)msg="Are you sure?";
			$(this).removeClass("confirm").addClass("_confirm");
			$(this).click(function(event){
				if(!confirm(msg)){
					event.preventDefault();
				}
			});
		});
	};
	$(document).ajaxComplete(function(){
		setTimeout(f,0);
	});
	$(f);

	setInterval(f, 300);
})(jQuery);