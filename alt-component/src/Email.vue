<template>
  <div class="input-group">
    <span class="input-group-addon">
      <i class="far fa-envelope"></i>
    </span>
    <input
      type="email"
      :class="inputClass"
      :id="id"
      :name="name"
      :value="value"
      :required="required"
      @input="$emit('input',$event.target.value)"
      @change="$emit('change',$event.target.value)"
    />
    <i v-if="required" class="fa fa-asterisk form-control-feedback"></i>
  </div>
</template>
<script>
export default {
  props: {
    required: Boolean,
    id: String,
    name: String,
    value: {},
    sm: Boolean
  },
  mounted() {
    if (this.required) {
      let $el = window.$(this.$el);
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
      return c;
    }
  }
};
</script>

