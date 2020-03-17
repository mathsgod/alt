<template>
  <div class="input-group">
    <span class="input-group-addon">
      <i :class="icon"></i>
    </span>
    <input
      type="text"
      :class="inputClass"
      is="datepicker"
      ref="datepicker"
      :value="value"
      v-bind="$props"
      @input="$emit('input',$event)"
      @change="$emit('change',$event)"
      autocomplete="off"
    />
    <i v-if="required" class="fa fa-asterisk form-control-feedback"></i>
  </div>
</template>
<script>
export default {
  name: "alt-date",
  props: {
    sm: Boolean,
    lg: Boolean,
    value: {
      type: String
    },
    icon: {
      type: String,
      default: "far fa-calendar-alt"
    },
    name: String,
    required: Boolean,
    type: Boolean,
    format: {
      type: String,
      default: "yyyy-mm-dd"
    },
    disabled: {
      type: Boolean,
      defalut: false
    },
    todayHighlight: {
      type: Boolean,
      default: true
    },
    todayBtn: {
      type: String,
      default: "linked"
    },
    autoclose: {
      type: Boolean,
      default: true
    }
  },
  mounted() {
    if (this.required) {
      $(this.$el)
        .closest(".form-group")
        .addClass("has-feedback");
      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");
        $(this.$el).addClass("form-group has-feedback");
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

