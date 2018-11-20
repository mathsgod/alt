<template>
    <select class="form-control"><slot></slot></select>
</template>
<script>
export default {
  name: "select2",
  props: {
    value: {
      type: String
    }
  },
  mounted() {
    $(this.$el).select2();
    if (this.value) {
      $(this.$el).val(this.value);
      $(this.$el).trigger("change");
    }

    $(this.$el).on("change", () => {
      var data = $(this.$el).select2("data");

      let v = data.map(s => {
        return s.id;
      });

      this.$emit("input", v[0]);
      this.$emit("change", v[0]);
    });
  },
  methods: {
    empty() {
      $(this.$el).empty();
    }
  }
};
</script>
