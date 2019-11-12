<template>
  <input
    class="form-control"
    v-bind="$props"
    @input="$emit('input',$event.target.value)"
    @change="$emit('change',$event.target.value)"
  />
</template>
<script>
export default {
  name: "alt-input",
  props: {
    required: Boolean,
    value: {},
    type: {
      type: String,
      default: "text"
    }
  },
  mounted() {
    let $el = window.$(this.$el);
    if (this.required) {
      if ($el.hasClass("input-sm")) {
        $el.after('<i class="fa fa-asterisk form-control-feedback"></i>');
      } else {
        $el.after(
          '<i class="fa fa-asterisk form-control-feedback" style="top:10px"></i>'
        );
      }

      $el.closest(".form-group").addClass("has-feedback");
      if ($el.closest(".form-group").length == 0) {
        $el.css("margin-bottom", "0px");

        $el.addClass("form-group has-feedback");
      }
      //   $(this.$el).closest("form").validate()
    }
  }
};
</script>
