<template>
  <form novalidate @submit="submit($event)">
    <slot></slot>
  </form>
</template>

<script>
export default {
  name: "alt-form",
  mounted() {
    window.$(this.$el).validate();
  },
  methods: {
    submit(e) {
      if (window.$(this.$el).valid()) {
        this.$emit("submit", e);
      } else {
        e.preventDefault();
      }
    }
  }
};
</script>
