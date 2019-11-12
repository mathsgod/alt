<template>
  <div class="input-group">
    <span class="input-group-addon">
      <i class="far fa-envelope"></i>
    </span>
    <input
      type="email"
      class="form-control"
      v-bind="$props"
      @input="$emit('input',$event.target.value)"
      @change="$emit('change',$event.target.value)"
    />
  </div>
</template>
<script>
export default {
  props: {
    required: Boolean,
    id: String,
    name: String,
    value: {}
  },
  mounted() {
    if (this.required) {
      let $el = window.$(this.$el);
      $el.after('<i class="fa fa-asterisk form-control-feedback"></i>');
      $el.closest(".form-group").addClass("has-feedback");
      if ($el.closest(".form-group").length == 0) {
        $el.css("margin-bottom", "0px");
        $el.addClass("form-group has-feedback");
      }
    }
  }
};
</script>

