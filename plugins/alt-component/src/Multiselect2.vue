<template>
    <select class="form-control" multiple><slot></slot></select>
</template>
<script>
export default {
  name: "multiselect2",
  props: {
    value: {
      type: Array
    }
  },
  watch: {
    value(newValue, oldValue) {
      $(this.$el).val(newValue);
      $(this.$el).trigger("change.select2");
    }
  },
  mounted() {
    $(this.$el).select2();

    if (this.value) {
      $(this.$el).val(this.value);
      $(this.$el).trigger("change.select2");
    }

    $(this.$el).on("change", () => {
      console.log("onchange");
      var data = $(this.$el).select2("data");

      let v = data.map(s => {
        return s.id;
      });

      this.$emit("input", v);
    });
  }
};
</script>
