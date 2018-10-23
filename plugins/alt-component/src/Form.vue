<template>
    <form novalidate @submit="onSubmit($event)">
        <slot></slot>
    </form>
</template>

<script>
export default {
  name: "alt-form",
  created() {
    $(this.$el).validate();
  },
  methods: {
    onSubmit(e) {
      if ($(this.$el).valid()) {
        this.$emit("submit", e);
      } else {
        e.preventDefault();
      }
    }
  }
};
</script>
