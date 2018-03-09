angular.module("ALT",['ngSanitize']).controller("NavBarController",function($scope, $window,$sce) {
	$scope.messages=[];
    $scope.notifications=[];
    $scope.tasks=[];

    $scope.addTaskHTML=function(html){
        $scope.tasks.push($sce.getTrustedHtml(html));
    }

});

var ALT={};
ALT.Messages={

	//data.label, data.description, data.href, data.time, data.image
	add:function(data){
		scope=angular.element("[ng-controller='NavBarController']").scope();
		if(!data.image)data.image="User/image?dummy=1";
		scope.$apply(function(){

			scope.messages.push(data);
		});
	}
};

ALT.Tasks={
    addHTML:function(html){
        scope=angular.element("[ng-controller='NavBarController']").scope();
        scope.addTaskHTML(html);
        scope.$apply();
    }
};

ALT.Notifications={
	add:function(data){
		scope=angular.element("[ng-controller='NavBarController']").scope();
		scope.$apply(function(){
			scope.notifications.push(data);
		});
	}
};

$(function(){
	function change_layout(cls) {
		$("body").toggleClass(cls);
		$.AdminLTE.layout.fixSidebar();
		//Fix the problem with right sidebar and layout boxed
		if (cls == "layout-boxed")
		$.AdminLTE.controlSidebar._fix($(".control-sidebar-bg"));
		if ($('body').hasClass('fixed') && cls == 'fixed') {
			$.AdminLTE.pushMenu.expandOnHover();
			$.AdminLTE.layout.activate();
		}
		$.AdminLTE.controlSidebar._fix($(".control-sidebar-bg"));
		$.AdminLTE.controlSidebar._fix($(".control-sidebar"));
	}

	$("[data-layout='fixed']").on('click', function () {
		$.get("User/layout?name=fixed&value="+($(this).is(':checked')?1:0)).done(function(){

			$("body").toggleClass("fixed");
			$.AdminLTE.pushMenu.expandOnHover();
			$.AdminLTE.layout.activate();
		});
	});


	$("[data-sidebarskin='toggle']").on('click', function () {
		var sidebar = $(".control-sidebar");
		var dark=sidebar.hasClass("control-sidebar-light");

		$.get("User/layout",{
			name:"control-sidebar",
			value:dark?"dark":"light"
		}).done(function(){
			if (sidebar.hasClass("control-sidebar-dark")) {
				sidebar.removeClass("control-sidebar-dark");
				sidebar.addClass("control-sidebar-light");
			} else {
				sidebar.removeClass("control-sidebar-light");
				sidebar.addClass("control-sidebar-dark");
			}
		});
	});

	$("[data-controlsidebar]").on('click', function () {
		change_layout($(this).data('controlsidebar'));
		var slide = !$.AdminLTE.options.controlSidebarOptions.slide;

		$.AdminLTE.options.controlSidebarOptions.slide = slide;
		if (!slide)
		$('.control-sidebar').removeClass('control-sidebar-open');
	});

	if ($('body').hasClass('fixed')) {
		$("[data-layout='fixed']").attr('checked', 'checked');
	}

	$("[data-toggle='offcanvas']").on("click",function(){
		$.get("User/layout",{
			name:"sidebar-collapse",
			value:$("body").hasClass("sidebar-collapse")?0:1
		});
	});

	$(".nav-tabs a[data-widget='collapse']").on("click",function(e){
		var $tab=$(this).closest(".nav-tabs-custom");
		var $tab_content=$tab.find(".tab-content");

		$(this).find("i").toggleClass("fa-minus").toggleClass("fa-plus");
		if($(this).find("i").hasClass("fa-minus")){
			$tab_content.slideDown(500);
		}else{
			$tab_content.slideUp(500);
		}
		return false;
	});

	//skin
	$("#app-skin").find("a[data-skin]").each(function(i,o){
		$(o).on('click', function (e) {
			e.preventDefault();
			var skin=$(this).attr("data-skin");
			window.self.location="User/skin?name="+skin;

		});
	});

	$("[data-enable='expandOnHover']").on('click', function () {
		$.get("User/layout?name=expandOnHover&value="+($(this).is(':checked')?1:0)).done(function(){
			$.AdminLTE.pushMenu.expandOnHover();
			if (!$('body').hasClass('sidebar-collapse'))
			$("[data-layout='sidebar-collapse']").click();
		});
	});

	$("[data-layout='sidebar-collapse']").on("click",function(){
		$.get("User/layout?name=sidebar-collapse&value="+($(this).is(':checked')?1:0)).done(function(){
			if($(this).is(':checked')){
				$('body').addClass('sidebar-collapse');
			}else{
				$('body').removeClass('sidebar-collapse');
			}
		}.bind(this));
	});

	$("[data-layout='sidebar-mini']").on("click",function(){
		$.get("User/layout?name=sidebar-mini&value="+($(this).is(':checked')?1:0)).done(function(){
			if($(this).is(':checked')){
				$('body').addClass('sidebar-mini');
			}else{
				$('body').removeClass('sidebar-mini');
			}
		}.bind(this));
	});

	setTimeout(function(){
		$("[data-enable='expandOnHover']").prop("checked",$.AdminLTE.options.sidebarExpandOnHover);
	},0);

	if ($('body').hasClass('sidebar-collapse')) {
		$("[data-layout='sidebar-collapse']").prop('checked', true);
	}

	if ($('body').hasClass('sidebar-mini')) {
		$("[data-layout='sidebar-mini']").prop('checked', true);
	}
});


