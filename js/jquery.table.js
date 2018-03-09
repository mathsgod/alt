//created by Raymond Chong
(function ($) {
    var f = function () {
        $(".table-childrow-btn").off('click').on("click", function () {
            var $this = $(this);
            var tr = $(this).closest("tr");
            var colspan = $(tr).find("td").length;

            if ($this.hasClass("table-childrow-close")) {
                $this.removeClass("table-childrow-close").addClass("table-childrow-open");
                $this.find("i").removeClass("fa-chevron-up").addClass("fa-chevron-down");

                var s=(new Date()).getTime();
                $this.attr("data-id",s);

                var new_tr = $("<tr class='table-childrow'>");
                new_tr.attr("data-id",s);
                tr.after(new_tr);

                var new_td = $("<td>");
                new_tr.append(new_td);
                new_td.attr("colspan", colspan);

                if ($this.attr("data-url")) {
                    var box = $(this).closest(".box");
                    if (box) {
                        box.data("box").showLoading();
                    }

                    $.get($this.attr("data-url")).done(function (html) {
                        if (box) {
                            box.data("box").hideLoading();
                        }

                        new_td.html(html);
                    });
                } else {
                    new_td.html($this.attr("data-child"));
                }

            } else {
                $this.removeClass("table-childrow-open").addClass("table-childrow-close");
                $this.find("i").removeClass("fa-chevron-down").addClass("fa-chevron-up");
                var s=$this.attr("data-id");
                var $table=$this.closest("table")
                $table.find("tr.table-childrow[data-id='"+s+"']").remove();


            }
        });
    };

    $(document).ajaxComplete(function () {
        setTimeout(f, 0);
    });

    $(f);

})(jQuery);