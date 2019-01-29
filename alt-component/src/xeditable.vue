<template>
    <a>
        <slot></slot>
    </a>
</template>
<script>
export default {
  props: {},
  mounted() {
    var dp = $(this.$el).editable();

    if ($(this.$el).attr("data-custom-type") == "date") {
      dp.on("shown", function(e, editable) {
        $(editable.input.$input).datepicker({
          format: "yyyy-mm-dd",
          todayHighlight: true,
          todayBtn: "linked",
          autoclose: true
        });
      });
    }

    if ($(this.$el).attr("data-custom-type") == "datetime") {
      dp.on("shown", function(e, editable) {
        $(editable.input.$input).datetimepicker({
          sideBySide: true,
          format: "YYYY-MM-DD HH:mm"
        });
      });
    }
  }
};
</script>
