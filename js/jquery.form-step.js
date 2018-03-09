//by Raymond Chong
(function($){
	$.fn.formStep=function(){

		var step=1;
		var that=$(this);

		var step_history=[];

		that.showForm=function(number){
			that.find(".overlay").hide();
			step=number;
			that.find("form").hide();
			$(that.find("form")[step-1]).show();
			if(step==1){
				that.find(".prev-btn").hide();
			}else{
				that.find(".prev-btn").show();
			}

			//cal
			var count_form=that.find("form").length;
			var progress_unit=100/count_form;
			var progress_value=progress_unit*step;
			that.find(".progress-bar").css("width",progress_value+"%");

			if(step==count_form){
				that.find(".next-btn").hide();
				that.find(".prev-btn").hide();
			}else{
				that.find(".next-btn").show();
			}

		}


		that.form=function(step){
			return $(that.find('form')[step-1]);
		}


		that.find(".prev-btn").hide();
		that.find(".prev-btn").on("click",function(){
			step=step_history.pop();
			that.showForm(step);
		});

		that.find('.next-btn').on("click",function(){
			that.find(".form-error").empty();
			var form=that.form(step);
			if(!form.valid()){
				return;
			}
			that.find(".overlay").show();
			form.ajaxSubmit(function(result){

				var data=JSON.parse(result);

				that.find(".overlay").hide();
				if(data.error){
					that.find(".form-error").text(data.error);
				}else{
					step_history.push(step);
					if(data.step){
						step=parseInt(data.step);
					}else{
						step++;
					}
					that.showForm(step);

					if(data.html!=""){
						that.form(step).html(data.html);
					}
				}
			});
		});

		that.find(".overlay").hide();
		that.showForm(1);
		return that;
	}
})(jQuery);