<template>
    
</template>
<script>
export default {
  render(createElement) {
    var rows = this.$parent.newRows.map(r => {
      return createElement("alt-row");
    });

    return createElement("tbody", [rows, this.$slots.default]);
  },
  methods: {}
};
</script>

