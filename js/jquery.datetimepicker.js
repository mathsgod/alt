//datetimepicker
$(function () {
    $.fn.dp = function () {
        var that = $(this);
        if (that.hasClass("_datetimepicker")) return that;
        that.addClass("_datetimepicker");
        that.addClass("form-control");

        if (!that.prop('required')) {
            //that.css("width","inherit");
            var input_group = $("<div class='input-group'>");
            that.wrap(input_group);

            var btn_class = "";
            if (that.hasClass("input-sm")) {
                btn_class = "btn-sm";
            } else if (that.hasClass("input-xs")) {
                btn_class = "btn-xs";
            }

            var btn = $('<span class="input-group-btn" style="width:inherit"><button type="button" class="btn btn-default ' + btn_class + '"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button></span>');
            that.after(btn);
            btn.on("click", function () {
                that.val("");
                $(this).find("button").blur();
            });
        }


        var format = $(this).attr('format');
        var locale="en";
        if(moment.locales().indexOf($("html").attr("lang"))>=0){
            locale=$("html").attr("lang");
        }
        
        if (format == undefined) format = "YYYY-MM-DD HH:mm";
        $(this).datetimepicker({
            sideBySide: true,
            format: format,
            locale:locale
        });
        $(this).on("dp.change", function () {
            //that.blur();
        });

        $(this).on("focus", function () {
            that.select();
        });

    };

    var f = function () {
        $(".datetimepicker").not("._datetimepicker").each(function (i, o) {
            $(o).dp();
        });
    }

    $(document).ajaxComplete(function () {
        setTimeout(f, 0);
    });

    f();
});