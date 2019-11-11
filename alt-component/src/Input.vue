<template>
  <input
    class="form-control"
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
    }
  },
  mounted() {
    if (this.required) {
      if ($(this.$el).hasClass("input-sm")) {
        $(this.$el).after(
          '<i class="fa fa-asterisk form-control-feedback"></i>'
        );
      } else {
        $(this.$el).after(
          '<i class="fa fa-asterisk form-control-feedback" style="top:10px"></i>'
        );
      }

      $(this.$el)
        .closest(".form-group")
        .addClass("has-feedback");
      if ($(this.$el).closest(".form-group").length == 0) {
        $(this.$el).css("margin-bottom", "0px");

        $(this.$el).addClass("form-group has-feedback");
      }
      //   $(this.$el).closest("form").validate()
    }
  }
};
</script>
