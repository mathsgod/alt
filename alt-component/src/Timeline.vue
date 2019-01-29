<template>
    <ul class="timeline">
        <template v-for="(item,index) in data" >
            <li v-if="item.type=='label'" class="time-label" :key="index">
                <span :class="item.class">
                    {{item.content}}
                </span>
            </li>

            <li v-else-if="item.type=='item'" :key="index">
                <i :class="item.icon"></i>
                <div class="timeline-item">
                    <span v-if="item.time" class="time"><i class="far fa-clock"></i> {{item.time}}</span>
                    <h3 v-if="item.header" class="timeline-header" v-html="item.header"></h3>
                    <div v-if="item.body" class="timeline-body" v-html="item.body"></div>
                    <div v-if="item.footer" class="timeline-footer" v-html="item.footer"></div>
                </div>
            </li>
        </template>
    </ul>
</template>
<script>
export default {
  props: {
    dataUrl: String
  },
  data() {
    return {
      data: Array
    };
  },
  mounted() {
    this.$http.get(this.dataUrl).then(resp => {
      this.data = resp.body;
    });
  }
};
</script>
