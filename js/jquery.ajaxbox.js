(function($){
	$.fn.ajaxBox = function(options) {
		var defaults = {
			onKeyUp:function(){},
			url:$(this).attr("data-url"),
			wait:500,
			width:"800px"
		};
		options = $.extend(defaults, options);

		var obj = $(this);

		var div=function(){
/*__START__
<div style='position: absolute; z-index: 999; width: 800px; display:none'>
	<div class='box'>
		<div class="box-header">
			<div class="box-tools">
				<button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
			</div>
		</div>
		<div class='box-body'></div>
	</div>
</div>
__END__*/
		}.toString().match(/__START__([^]*)__END__/)[1];

		var $div=$(div);
		$(this).after($div);

		var seq=0;

		obj.keyup(function(event){
			seq+=1;
			var f=new function(s){
				setTimeout(function(){
					if(s==seq){
						$div.show();
						$div.find(".box").show();
						if(obj.val()!=""){
							$.get(options.url,{
								q:$(obj).val()
							},function(html){
								$div.find('.box-body').html(html);
							});
						}else{
							$div.hide();
						}
					}
				},options.wait);
			}(seq);
		});

		obj.focus(function(event){
			seq+=1;
			var f=new function(s){
				setTimeout(function(){
					if(s==seq){
						$div.show();
						$div.find(".box").show();
						if(obj.val()!=""){
							$.get(options.url,{
								q:$(obj).val()
							},function(html){
								$div.find('.box-body').html(html);
							});
						}else{
							$div.hide();
						}
					}
				},options.wait);
			}(seq);
		});
	};
})(jQuery);