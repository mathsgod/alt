var Vue=window.Vue;
var $=window.$;
window.ALT = new Vue({
	el: "#alt-navbar",
	data: {
		messages: [],
		notifications: [],
		tasks: []
	},
	methods: {
		addMessage(label, description, href, time, image) {
			this.messages.push({
				label: label,
				description: description,
				time: time,
				image: image
			});
		}, addNotification(label, icon, href) {
			this.messages.push({
				label: label,
				icon: icon,
				href: href
			});
		}, addTask(content) {
			this.tasks.push(content);
		}
	}
});

Vue.component("alt-sidebar-menu", {
	template: `
<ul class="treeview-menu" :data-keyword="menu.keyword">
	<li v-for="submenu in menu.submenu" :class="{active:submenu.active}">
		<a :href="submenu.link" :target="menu.target" style="cursor: pointer;">
			<i :class="submenu.icon"></i>
			<span v-text="submenu.label"></span>
			<span class="pull-right-container" v-if="submenu.submenu">
				<i class="fa fa-angle-left pull-right"></i>
			</span>
		</a>
		<alt-sidebar-menu v-if="submenu.submenu" :menu="submenu"></alt-sidebar-menu>
	</li>
</ul>`,
	props: {
		menu: Object
	}

});


new Vue({
	el: "#_sb",
	data: {
		all_menus: [],
		q: ""
	},
	created() {
		this.all_menus = JSON.parse(document.getElementById("sidebar-menu").text.trim());

		var id = 1;

		this.all_menus.forEach(m => {
			m.id = id;
			id++;
			if (m.submenu) {
				m.submenu.forEach(n => {
					n.id = id;
					id++;
					if (n.submenu) {
						n.submenu.forEach(o => {
							o.id = id;
							id++;
						});
					}
				});
			}
		});

	}, computed: {
		menus() {
			if (this.q == "") {
				return this.all_menus;
			}
			var menus = JSON.parse(JSON.stringify(this.all_menus));
			$.AdminLTE.tree('.sidebar');
			return this.filterMenu(this.q, menus);
		}

	}, methods: {
		clickMenu(event, menu) {
			return;
		},
		filterMenu(text, menu) {
			text = text.toLowerCase();
			var m = [];
			for (var i in menu) {
				if (menu[i].keyword.toLowerCase().indexOf(text) >= 0) {
					m.push(menu[i]);
					continue;
				}

				menu[i].submenu = this.filterMenu(text, menu[i].submenu);
				if (menu[i].submenu.length > 0) {
					m.push(menu[i]);
					menu[i].active = true;

				}
			}
			return m;
		}
	}
});



$(function () {
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
		$.get("User/layout?name=fixed&value=" + ($(this).is(':checked') ? 1 : 0)).done(function () {

			$("body").toggleClass("fixed");
			$.AdminLTE.pushMenu.expandOnHover();
			$.AdminLTE.layout.activate();
		});
	});


	$("[data-sidebarskin='toggle']").on('click', function () {
		var sidebar = $(".control-sidebar");
		var dark = sidebar.hasClass("control-sidebar-light");

		$.get("User/layout", {
			name: "control-sidebar",
			value: dark ? "dark" : "light"
		}).done(function () {
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

	$("[data-toggle='offcanvas']").on("click", function () {
		$.get("User/layout", {
			name: "sidebar-collapse",
			value: $("body").hasClass("sidebar-collapse") ? 0 : 1
		});
	});

	$(".nav-tabs a[data-widget='collapse']").on("click", function (e) {
		var $tab = $(this).closest(".nav-tabs-custom");
		var $tab_content = $tab.find(".tab-content");

		$(this).find("i").toggleClass("fa-minus").toggleClass("fa-plus");
		if ($(this).find("i").hasClass("fa-minus")) {
			$tab_content.slideDown(500);
		} else {
			$tab_content.slideUp(500);
		}
		return false;
	});

	//skin
	$("#app-skin").find("a[data-skin]").each(function (i, o) {
		$(o).on('click', function (e) {
			e.preventDefault();
			var skin = $(this).attr("data-skin");
			window.self.location = "User/skin?name=" + skin;

		});
	});

	$("[data-enable='expandOnHover']").on('click', function () {
		$.get("User/layout?name=expandOnHover&value=" + ($(this).is(':checked') ? 1 : 0)).done(function () {
			$.AdminLTE.pushMenu.expandOnHover();
			if (!$('body').hasClass('sidebar-collapse'))
				$("[data-layout='sidebar-collapse']").click();
		});
	});

	$("[data-layout='sidebar-collapse']").on("click", function () {
		$.get("User/layout?name=sidebar-collapse&value=" + ($(this).is(':checked') ? 1 : 0)).done(function () {
			if ($(this).is(':checked')) {
				$('body').addClass('sidebar-collapse');
			} else {
				$('body').removeClass('sidebar-collapse');
			}
		}.bind(this));
	});

	$("[data-layout='sidebar-mini']").on("click", function () {
		$.get("User/layout?name=sidebar-mini&value=" + ($(this).is(':checked') ? 1 : 0)).done(function () {
			if ($(this).is(':checked')) {
				$('body').addClass('sidebar-mini');
			} else {
				$('body').removeClass('sidebar-mini');
			}
		}.bind(this));
	});

	setTimeout(function () {
		$("[data-enable='expandOnHover']").prop("checked", $.AdminLTE.options.sidebarExpandOnHover);
	}, 0);

	if ($('body').hasClass('sidebar-collapse')) {
		$("[data-layout='sidebar-collapse']").prop('checked', true);
	}

	if ($('body').hasClass('sidebar-mini')) {
		$("[data-layout='sidebar-mini']").prop('checked', true);
	}
});