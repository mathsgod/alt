<script>
export default {
  render(createElement) {
    var n = createElement("th", [
      createElement("button", {
        class: ["btn btn-primary btn-xs"],
        on: {
          click: this.onClickAddRow
        },
        domProps: {
          innerHTML: "<i class='fa fa-plus'></i>"
        }
      })
    ]);

    return createElement("tr", [n, this.$slots.default]);
  },
  data() {
    return {
      type: String
    };
  },
  methods: {
    onClickAddRow() {
      this.$parent.addRow();
    }
  }
};
</script>
