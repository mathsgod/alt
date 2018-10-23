<template>
    <div>
        <ul class="nav nav-tabs">
            <li v-for="(tab, i) in tabs" :class="[tab.active?'active':'']" @click.prevent="selectTab(tab)" :key="i">
                <a href="#" v-html="tab.name"></a>
            </li>
        </ul>
        <div class="tab-content">
            <slot></slot>
        </div>
    </div>
</template>
<script>
export default {
  props: {
    name: String
  },
  data: () => ({
    tabs: [],
    selectedTab: null,
    xhr: null
  }),
  created() {
    this.tabs = this.$children;
  },
  mounted() {
    this.tabs.forEach(t => {
      t.$on("loaded", () => {
        this.$localStorage.set(this.name + "/tab", t.name);
      });
    });

    let name;
    if ((name = this.$localStorage.get(this.name + "/tab"))) {
      let tab = this.tabs.find(t => t.name == name);

      if (tab) {
        this.selectTab(tab);
        return;
      }
    }

    let tab = this.tabs.find(t => t.active);
    if (tab) {
      this.selectTab(tab);
    }
  },
  methods: {
    selectTab(tab) {
      if (this.xhr) {
        this.xhr.abort();
      }
      this.selectedTab = tab;
      this.tabs.forEach(t => {
        t.active = false;
      });
      this.xhr = tab.select();
    }
  }
};
</script>
