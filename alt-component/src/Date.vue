<template>
<div>
    <div class="input-group">
        <span class="input-group-addon">
            <i :class="icon"></i>
        </span>
        <input is="datepicker" ref="datepicker" :value="value" v-bind="$props" @input="$emit('input',$event)"/>
        <i v-if="required" class="fa fa-asterisk form-control-feedback" style="top:10px"></i>
    </div>
</div>
</template>
<script>
export default {
  name: "alt-date",
  props: {
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
  }
};
</script>

