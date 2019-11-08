<template>
    <box v-if="hasHeader()">
        <box-header>{{header}}</box-header>
        <box-body>
            <div class="form-horizontal clearfix">
                <slot></slot>
            </div>
        </box-body>
    </box>
    <div v-else class="form-horizontal clearfix">
        <slot></slot>
    </div>
</template>
<script>
import Box from "./Box";
import BoxBody from "./BoxBody";
import BoxHeader from "./BoxHeader";

export default {
  props: {
    header: String
  },
  components:{
    Box,
    BoxBody,
    BoxHeader
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
