//create by Raymond Chong
(function($){

	var Box=function(node,options){

		this.options=$.extend({

		},options);

		var $this=$(node);

		if($this.data("box"))return ;
		$this.data("box",this);

		this.exec=function(action){
			switch(action){
				case "reload":
					this.reload();
					break;
				case "showLoading":
					this.showLoading();
					break;
				case "hideLoading":
					this.hideLoading();
					break;
			};

		};

		this.header=function(){

			var $header=$this.find(">.box-header");

			if(!$header.length){
				$header=$("<div>").addClass("box-header");
				$header.prependTo($this);
			}

			$header.tools=function(){
				var $tools=this.find(">.box-tools");
				if(!$tools.length){
					$tools=$("<div>").addClass("box-tools pull-right");
					$tools.appendTo($header);
				}

				$tools.addButton=function(){
					var $button=$("<button>");
					$button.addClass("btn btn-box-tool");
					$button.appendTo($tools);
					return $button;
				};

				return $tools;
			};

			return $header;
		};

		this.body=function(){
			var $body=$this.find(">.box-body");

			if(!$body.length){ //add body
				$body=$("<div>").addClass("box-body");
				$body.appendTo($this);
			}
			return $body;
		};

		this.showLoading=function(){
			$this.append($('<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>'));
		};

		this.hideLoading=function(){
			$this.find('>.overlay').remove();
		};

		this.reload=function(){
			var url=$this.attr("data-url");
			if(!url)return;

			this.showLoading();

			this.body().load(url,function(){
				this.hideLoading();
			}.bind(this));
		};

		if($this.attr("data-url")){
			this.reload();

			var $btn=this.header().tools().addButton();
			$btn.append('<i class="fa fa-refresh"></i>');
			$btn.on('click',this.reload.bind(this));
		}

	};

	$.fn.box=function(options){
		return this.each(function(){
			var b=$(this).data("box");
			if(typeof options =="string" && b){
				b.exec(options);
				return;
			}

			new Box(this,options);
		});
	};

})(jQuery);