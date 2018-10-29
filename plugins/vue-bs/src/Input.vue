<template>
    <input :type="type" class="form-control" v-bind="$props">
</template>

<script>
export default {
  name: "bs-input",
  props: {
    required: Boolean,
    value: String,
    type: {
      type: String,
      default: "text"
    }
  }
};
</script>
