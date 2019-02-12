<template>
  <input type="checkbox">
</template>
<script>
module.exports = {
  props: {
    value: {
      default: null
    },
    checkboxClass: {
      type: String,
      default: "icheckbox_square-blue"
    },
    radioClass: {
      type: String,
      default: "iradio_square-blue"
    },
    checked: {
      type: Boolean
    },
    trueValue: {
      default: 1
    },
    falseValue: {
      default: null
    }
  },
  created() {},
  mounted() {
    $(this.$el).iCheck(this._props);

    if (this.checked) {
      $(this.$el).iCheck("check");
    }

    if (this.value == this.trueValue) {
      $(this.$el).iCheck("check");
    }

    $(this.$el)
      .on("ifClicked", event => {
        $(this.$el).trigger("click");
        this.$emit("click", event);
      })
      .on("ifChanged", event => {
        this.$emit("change", event);
      })
      .on("ifChecked", event => {
        $(this.$el).val(this.value);
        this.$emit("input", this.trueValue);
        this.$emit("checked", event);
      })
      .on("ifUnchecked", event => {
        $(this.$el).val(null);
        this.$emit("input", this.falseValue);
        this.$emit("unchecked", event);
      });
  }
};
</script>

