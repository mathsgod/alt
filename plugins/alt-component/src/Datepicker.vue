<template>
    <input class="form-control"/>
</template>
<script>
export default {
  props: {
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
  data() {
    return {
      datepicker: null,
      val: null
    };
  },
  mounted() {
    this.val = this.$el.value;
    this.datepicker = $(this.$el).datepicker(this.$props);
    this.datepicker.on("change", o => {
      this.val = this.$el.value;
    });
  },
  computed: {
    value: {
      get() {
        return this.val;
      },
      set(newValue) {
        this.val = newValue;
        this.$el.value = newValue;
      }
    }
  }
};
</script>

