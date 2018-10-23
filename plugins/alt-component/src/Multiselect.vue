<template>
    <select multiple>
        <slot></slot>
    </select>    
</template>
<script>
export default {
  name: "alt-multiselect",
  props: {
    includeSelectAllOption: {
      type: Boolean,
      default: true
    },
    enableFiltering: {
      type: Boolean,
      default: true
    }
  },
  mounted() {
    $(this.$el).multiselect(this._props);
  }
};
</script>

