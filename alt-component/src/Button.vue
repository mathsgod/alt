<template>
    <button @click="onClick">
        <i v-if="iconClass" :class="iconClass"></i>
        <slot></slot>
    </button>
</template>
<script>
export default {
  name: "alt-button",
  props: {
    icon: String,
    type: String
  },
  data() {
    return {
      submitting: false,
      iconClass: this.icon
    };
  },
  methods: {
    onClick(event) {
      if (this.type == "submit") {
        var form = this.$el.form;
        if (!$(form).valid()) {
          return false;
        }
        if (this.submitting) {
          event.preventDefault();
          return;
        }

        this.submitting = true;

        this.iconClass = "fa fa-spinner fa-spin";
      }
    }
  }
};
</script>
