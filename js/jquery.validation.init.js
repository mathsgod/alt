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