<template>
    <alt-box v-if="hasHeader()">
        <alt-box-header>{{header}}</alt-box-header>
        <alt-box-body>
            <div class="form-horizontal clearfix">
                <slot></slot>
            </div>
        </alt-box-body>
    </alt-box>
    <div v-else class="form-horizontal clearfix">
        <slot></slot>
    </div>
</template>
<script>
export default {
  props: {
    header: String
  },
  methods: {
    hasHeader() {
      if (this.header) {
        return true;
      }
      return false;
    }
  }
};
</script>
