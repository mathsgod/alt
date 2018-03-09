//Preivew
//Created By: Raymond Chong
$(function(){
	var f=function(){
		$("a.screenshot").each(function(i,o){
			$(o).addClass('_screenshot').removeClass("screenshot");
			var xOffset=10;
			var yOffset=30;

			$(o).hover(function(e){
				this.t = this.title;
				this.title = "";
				var c = (this.t != "") ? "<br/>" + this.t : "";
				$("body").append("<p id='screenshot' style='position:absolute;display:none;padding:2px;border:1px solid #ccc;background:#FFF'><img src='"+ this.rel +"' alt='url preview' />"+ c +"</p>");
				$("#screenshot")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px")
				.fadeIn("fast");
			},
			function(){
				this.title = this.t;
				$("#screenshot").remove();
			});
			$(this).mousemove(function(e){
				$("#screenshot")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px");
			});
		});
	};

	$(document).ajaxComplete(function(){
		setTimeout(f,0);
	});

	f();

});

//Tooltip
//Created By: Raymond Chong
$(function(){
	var f=function(){
		$(".preview").each(function(i,o){
			$(o).addClass('_preview').removeClass("preview");
			var xOffset=10;
			var yOffset=30;

			$(o).hover(function(e){
				this.t = this.title;
				this.title = "";
				var c = (this.t != "") ? "<br/>" + this.t : "";

				var p=$("<p id='_preview' style='position:absolute;display:none;padding:2px;border:1px solid #ccc;background:#FFF'></p>");
				$("body").append(p);
				p.load(this.rel,function(data){
					p.append(p);
				});
				$("#_preview")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px")
				.fadeIn("fast");

			},
			function(){
				this.title = this.t;
				$("#_preview").remove();
			});
			$(this).mousemove(function(e){
				$("#_preview")
				.css("top",(e.pageY - xOffset) + "px")
				.css("left",(e.pageX + yOffset) + "px");
			});
		});
	};

	$(document).ajaxComplete(function(){
		setTimeout(f,0);
	});

	f();

});