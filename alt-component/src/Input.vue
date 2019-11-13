<template>
  <input
    :class="inputClass"
    :required="required"
    :value="value"
    :type="type"
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
    },
    sm: Boolean,
    lg: Boolean
  },
  mounted() {
    let $el = window.$(this.$el);
    if (this.required) {
      $el.after('<i class="fa fa-asterisk form-control-feedback"></i>');

      $el.closest(".form-group").addClass("has-feedback");
      if ($el.closest(".form-group").length == 0) {
        $el.css("margin-bottom", "0px");

        $el.addClass("form-group has-feedback");
      }
    }
  },
  computed: {
    inputClass() {
      let c = [];
      c.push("form-control");
      if (this.sm) {
        c.push("input-sm");
      }
      if (this.lg) {
        c.push("input-lg");
      }
      return c;
    }
  }
};
</script>
