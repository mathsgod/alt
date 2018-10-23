Vue.use(VueLocalStorage);

(function ($) {
    //-- form validation --
    $.validator.setDefaults({
        ignore: [],
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');

            //check tab
            var tab = $(element).closest(".nav-tabs-custom");
            if (tab) {
                var pane = $(element).closest(".tab-pane");

                tab.find("> .tab-content > .tab-pane").removeClass("active");
                pane.addClass("active");
                var tab_id = pane.attr("id");
                tab.find("> ul li").removeClass('active');
                tab.find("> ul li a[href='#" + tab_id + "']").parent().addClass("active");
            }

        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {

            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });


    var f = function () {

        $("textarea[ace]").each(function () {
            var $that = $(this);
            this.removeAttribute("ace");

            $(this).addClass("hide");
            var $div = $("<div style='height:400px'></div>");
            $div.insertAfter(this);

            var editor = ace.edit($div[0]);
            var mode = $(this).attr("ace-mode");
            if (mode) {
                editor.session.setMode("ace/mode/" + mode);
            }

            editor.getSession().setValue($(this).val());

            editor.getSession().on('change', function () {
                console.log(editor.getSession().getValue());
                $that.val(editor.getSession().getValue());
            });


        });

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

        if (typeof CodeMirror != "undefined") {
            $(".code").not("._code").each(function (i, o) {
                $(o).addClass("_code");
                var editor = CodeMirror.fromTextArea(this, {
                    lineNumbers: true,
                    matchBrackets: true,
                    mode: "application/x-httpd-php",
                    indentUnit: 8,
                    indentWithTabs: true,
                    enterMode: "keep",
                    tabMode: "shift"
                });
            });
        }

        $("textarea.wysihtml5").not("._wysihtml5").each(function () {
            $(this).addClass("_wysihtml5").wysihtml5();
        });

        //markdown
        $(".markdown").not("._markdown").each(function () {
            $(this).addClass("_markdown");
            $(this).html(marked($(this).html()));
        });

        //colorpicker
        $(".cp").not("._colorpicker").each(function (i, o) {
            $(o).addClass("_colorpicker");
            $(o).colorpicker();
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


        //Make the dashboard widgets sortable Using jquery UI
        /*        $(".connectedSortable").not("._connectedSortable").sortable({
                    placeholder: "sort-highlight",
                    connectWith: ".connectedSortable",
                    handle: ".box-header, .nav-tabs",
                    forcePlaceholderSize: true,
                    zIndex: 999999
                });
        
                $(".connectedSortable").not("._connectedSortable").on("sorttstop", function (event, ui) {
        
                    var grid = $(this).closest('.grid');
        
                    var data = [];
                    grid.children("div.row").each(function (i, row) {
                        data[i] = [];
                        $(row).children("section").each(function (j, section) {
                            data[i][j] = [];
        
                            $(section).children("div[grid-item]").each(function (k, item) {
                                data[i][j].push($(item).attr("grid-item"));
                            });
                        });
                    });
        
                    $.post("UI/save", {
                        type: 'grid',
                        layout: JSON.stringify(data),
                        uri: grid.attr("data-uri")
                    });
                });
                $(".connectedSortable").not("._connectedSortable").addClass("_connectedSortable");*/




        //fancybox
        if ($.fancybox) {
            $(".fancybox").each(function () {
                if ($(this).hasClass("_fancybox")) return;

                var o;
                $(this).fancybox();
            });
        }

        //Timepicker
        $(".timepicker").each(function (i, o) {
            $(this).datetimepicker({
                sideBySide: true,
                format: "HH:mm"
            });
        });


        //Datatable
        /*        $(".DataTable").each(function (i, o) {
                    $(o).removeClass("DataTable").addClass("_DataTable");
                    $(o).DataTable();
                });*/

        //Multiselect
        $("select.multiselect").each(function (i, o) {
            $(o).removeClass("multiselect").addClass("_multiselect");

            var options = {
                includeSelectAllOption: true,
                enableFiltering: true
            };

            if ($(o).attr("buttonWidth")) options["buttonWidth"] = $(o).attr("buttonWidth");
            if ($(o).attr("numberDisplayed")) options["numberDisplayed"] = $(o).attr("numberDisplayed");
            $(o).multiselect(options);
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

        //Select2



        /*        $("input[required]").not("._required").each(function (i, o) {
                    $(o).addClass('_required');
                    var formgroup = $(o).closest(".form-group");
                    if (formgroup.length == 0) {
                        formgroup = $("<div>");
                        formgroup.addClass("no-margin form-group has-feedback");
                        $(o).wrap(formgroup);
                    }
                    formgroup.addClass("has-feedback");
        
                    var $span = '<i class="fa fa-asterisk form-control-feedback" />';
                    if ($(o).closest('.input-group').length == 1) {
                        $(o).closest('.input-group').after($span);
                    } else {
                        $(o).after($span);
                    }
                });*/



        /*        $("[datepicker]").not("._datepicker").each(function () {
                    var options = {
                        format: 'yyyy-mm-dd',
                        todayHighlight: true,
                        todayBtn: "linked",
                        autoclose: true
                    };
                    if ($(this).attr("data-format")) options["format"] = $(this).attr("data-format");
                    if ($(this).attr("data-inputClass")) options["inputClass"] = $(this).attr("data-inputClass");
                    $(this).datepicker(options);
                });
                $("[datepicker]").addClass("_datepicker");*/


        //roxy fileman
        $(".roxy_fileman").not("._roxy_fileman").each(function () {
            $(this).on("click", function () {
                var d = new Date().getTime();
                d = "roxy_" + d;

                $(this).attr("data-roxy-id", d);

                var resize_width = $(this).attr('roxy-resize-width');
                var type = ($(this).attr('roxy-type') == undefined) ? "image" : $(this).attr('roxy-type');

                var session = "";
                if ($(this).attr("roxy-session")) {
                    session = $(this).attr("roxy-session");
                }

                var path = "plugins/RoxyFileman.1.4.5/fileman/index.html?type=" + type + "&integration=custom&txtFieldId=" + d + "&session=" + session;

                if (resize_width != undefined) {
                    path += "&resize_width=" + resize_width;
                }
                $.fancybox.open({
                    src: path,
                    type: "iframe"
                });
            });
        });
        $(".roxy_fileman").addClass("_roxy_fileman");

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

        //iCheck
        $('input.iCheck').not("._iCheck").each(function (i, o) {
            $(o).iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue'
            });
            $(o).addClass("_iCheck");

            $(o).on('ifClicked', function () {
                $(this).trigger("click");
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

(function ($) {
    PNotify.prototype.options.styling = "fontawesome";

    /*    $(".connectedSortable").css("min-height", "0px");
    
        $("[data-widget='pin']").on("click", function (e) {
            e.preventDefault();
            $(this).find("i").toggleClass("fa-thumbtack").toggleClass("fa-arrows-alt");
    
            var element = $(this).closest('.ui-sortable');
    
            if ($(this).find("i").hasClass("fa-arrows")) {
                element.sortable("enable");
                element.find(".ui-sortable-handle").css("cursor", "move");
            } else {
                element.sortable("disable");
                element.find(".ui-sortable-handle").css("cursor", "");
            }
    
            //check pin status
            var move = false;
            $("[data-widget='pin']").each(function () {
                if ($(this).find("i").hasClass("fa-arrows-alt")) {
                    move = true;
                }
            });
    
            if (move) {
                $(".connectedSortable").css("min-height", "100px");
            } else {
                $(".connectedSortable").css("min-height", "0px");
            }
    
        });
    
        $("[data-widget='pin']").each(function (o) {
            var element = $(this).closest('.ui-sortable');
            element.sortable("disable");
            element.find(".ui-sortable-handle").css("cursor", "");
        });
        */
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