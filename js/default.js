Vue.use(VueLocalStorage);

(function ($) {

    var f = function () {

        $('[data-toggle="popover"]').popover();

        $("[data-format='json']").each(function () {
            if ($(this).data("data-format") == "json") return;
            $(this).data("data-format", 'json');
            try {
                var input = JSON.parse($(this).text());


                var collapsed = false;
                if ($(this).attr("collapsed") == 'true' || $(this).is('[collapsed]')) {
                    collapsed = true;
                }



                $(this).jsonViewer(input, {
                    collapsed: collapsed,
                    withQuotes: true
                });

                //console.log($(this));
                $(this).attr('style', $(this).attr('style') + '; ' + 'padding: 0.5em 1.5em !important');

                //$(this).css('padding','0.5em 1.5em');
            } catch (error) {
                console.log(error);
            }
        });

        $("a[xeditable]").not("._xeditable").each(function () {

            var options = {
                dataType: 'json'
            };

            var dp = $(this).editable(options);

            if ($(this).attr("data-custom-type") == "date") {
                dp.on("shown", function (e, editable) {
                    $(editable.input.$input).datepicker({
                        format: 'yyyy-mm-dd',
                        todayHighlight: true,
                        todayBtn: "linked",
                        autoclose: true
                    });
                });
            }

            if ($(this).attr("data-custom-type") == "datetime") {
                dp.on("shown", function (e, editable) {
                    $(editable.input.$input).datetimepicker({
                        sideBySide: true,
                        format: "YYYY-MM-DD HH:mm"
                    });

                });
            }

        });
        $("a[xeditable]").addClass("_xeditable");

        $(".slimScroll").not("._slimScroll").each(function () {
            $(this).addClass("_slimScroll no-padding");
            $(this).wrapInner("<div class='preSlimScroll'></div>");
        });

        $(".preSlimScroll").not("._preSlimScroll").each(function () {
            $(this).addClass("_preSlimScroll");

            var options = {

            };

            if ($(this).parent()[0].style.height != "") {
                options["height"] = $(this).parent()[0].style.height;
            }

            $(this).slimScroll(options);
        });

        $(".box").box();

        //box collapse
        $(".box").not("._box").each(function (i, box) {

            $(box).addClass("_box");
            if (!$(box).attr("data-uri")) return;
            $(box).children("div.box-header").find("button[data-widget='collapse']").on("click", function () {
                var box = $(this).closest('.box');
                $.post("UI/save", {
                    type: 'box',
                    layout: JSON.stringify({
                        collapse: $(this).find("i").hasClass("fa-minus")
                    }),
                    uri: box.attr("data-uri")
                });

            });

        });


        $("form").each(function (j, v) {
            $(v).validate();

            $(v).tooltip({
                show: false,
                hide: false
            });
        });



        if (typeof CKEDITOR != 'undefined') {
            var base = $("base").attr("href");
            CKEDITOR.config.filebrowserImageBrowseUrl = base + "plugins/RoxyFileman.1.4.5/fileman/index.html?type=image";
            CKEDITOR.config.filebrowserBrowseUrl = base + "plugins/RoxyFileman.1.4.5/fileman/index.html";
        }
        //--------------

    };

    $(document).ajaxComplete(f);
    document.addEventListener("DOMContentLoaded", f);
    f();
})(jQuery);

function closeRoxyDialog() {
    $.fancybox.close();
}



(function ($) {
    var f = function () {
        if ($.jstree != undefined) {
            $("div.jstree").not("._jstree").each(function (i, o) {
                $(o).addClass("_jstree");
                var options = {};
                if (o.dataset.options) {
                    options = JSON.parse(o.dataset.options);
                }
                $(o).jstree(options);
            });
        }

        //dialog
        $("a.dialog").not("._dialog").each(function (i, o) {
            $(o).addClass("_dialog");
            $(o).on("click", function (e) {
                e.preventDefault();
                var $div = $("<div></div>");

                var title = $(this).attr("dialog-title");
                console.log(title);

                $div.load($(o).attr("href"), function () {
                    bootbox.dialog({
                        title: title == undefined ? "&nbsp;" : title,
                        message: $div
                    });
                });
            });

        });


        //t2s
        if ($.t2s) {
            $(".t2s").not("._t2s").each(function (i, o) {
                var that = $(o);
                that.addClass("_t2s");

                var btn = $('<span class="input-group-btn"><button class="btn btn-default" type="button">T2S</button></span>');

                btn.find("button").on("click", function () {
                    that.val($.t2s(that.val()));
                });

                var div = $("<div class='input-group'>");
                that.wrapAll(div);

                that.after(btn);

            });
        }
    };
    $(document).ajaxComplete(f);
    f();
    setInterval(f, 300);
})(jQuery);


function __add_favorite() {
    var label = prompt("請輸入標籤", window.document.title);
    if (label != undefined && label != "") {
        $.post("UI/save/saveFav", {
            layout: {
                label: label,
                link: self.location.pathname + self.location.search
            }
        }).done(function (resp) {
            if (resp.code == 200) {
                window.self.location.reload();
            } else {
                alert("error add fav");
            }
        });
    }
}