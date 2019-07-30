<template>
  <div class="box-body">
    <div v-if="content" v-html="content"></div>
    <slot v-else></slot>
  </div>
</template>
<script>
export default {
  name: "box-body",
  data() {
    return {
      content: null
    };
  },
  methods: {
    setContent(content) {
      this.content = content;
    }
  }
};
</script>
