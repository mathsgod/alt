<template>
    
</template>
<script>
export default {
  render(createElement) {
    var n = createElement("td", [
      createElement("button", {
        class: ["btn btn-danger btn-xs"],
        on: {
          click: this.onClickRemoveRow
        },
        domProps: {
          innerHTML: "<i class='fa fa-minus'></i>"
        }
      })
    ]);
    return createElement("tr", [n, this.$slots.default]);
  },
  props: {
    dataIndex: String
  },
  methods: {
    onClickRemoveRow() {
      this.$parent.addRow();
    }
  }
};
</script>

