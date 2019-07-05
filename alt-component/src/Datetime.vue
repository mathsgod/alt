<template>
  <div>
    <div class="input-group">
      <span class="input-group-addon">
        <i :class="icon"></i>
      </span>
      <input class="form-control" ref="input" v-bind="$props" :value="value" :name="name" />
      <i v-if="required" class="fa fa-asterisk form-control-feedback" style="top:10px"></i>
    </div>
  </div>
</template>
<script>
export default {
  name: "alt-datetime",
  props: {
    name: String,
    required: Boolean,
    type: Boolean,
    value: String,
    sideBySide: {
      type: Boolean,
      default: true
    },
    format: {
      type: String,
      default: "YYYY-MM-DD HH:mm"
    },
    icon: {
      type: String,
      default: "far fa-calendar-alt"
    },
    minDate: {
      default: false
    },
    maxDate: {
      default: false
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
    $(this.$refs.input).datetimepicker({
      format: this.format,
      minDate: this.minDate,
      maxDate: this.maxDate
    });
  }
};
</script>
