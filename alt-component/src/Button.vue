<template>
  <button @click="onClick" :type="type">
    <i v-if="iconClass" :class="displayIcon"></i>
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
  computed: {
    displayIcon() {
      if (!this.submitting) {
        return this.iconClass;
      }
      return "fa fa-spinner fa-spin";
    }
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
      }
    }
  }
};
</script>
