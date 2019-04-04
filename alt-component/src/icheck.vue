<template>
  <input type="checkbox">
</template>
<script>
module.exports = {
  props: {
    value: {
      default: "on"
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
      type: Boolean,
      default: false
    },
    trueValue: {
      default: 1
    },
    falseValue: {
      default: 0
    }
  },
  watch: {
    value(val) {
      if (val === this.trueValue) {
        this.checked = true;
        $(this.$el).iCheck("check");
      } else {
        this.checked = false;
        $(this.$el).iCheck("uncheck");
      }
    }
  },
  created() {},
  mounted() {
    $(this.$el).iCheck(this._props);

    if (this.checked) {
      $(this.$el).iCheck("check");
    }

    this.$el.value = this.value;

    $(this.$el).val(this.value);

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
        this.$emit("input", this.trueValue);
        this.$emit("checked", event);
      })
      .on("ifUnchecked", event => {
        this.$emit("input", this.falseValue);
        this.$emit("unchecked", event);
      });
  }
};
</script>

