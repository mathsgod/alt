<template>
    <div class="input-group">
        <span class="input-group-addon">
            <i class="far fa-envelope"></i>
        </span>
        <input type="email" class="form-control" v-bind="$props" :value="value" :name="name"/>
        <i v-if="required" class="fa fa-asterisk form-control-feedback" style="top:10px"></i>
    </div>
</template>
<script>
export default {
  props: {
    name: String,
    required: Boolean,
    type: Boolean,
    value: String
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

