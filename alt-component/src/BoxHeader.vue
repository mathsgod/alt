<template>
  <div class="box-header">
    <h3 class="box-title">
      <i v-if="icon" :class="icon"></i>
      <slot></slot>
    </h3>
    <div class="box-tools pull-right">
      <slot name="tools"></slot>

      <button v-if="dataUrl" type="button" class="btn btn-box-tool" @click="$parent.reload()">
        <i class="fa fa-sync-alt"></i>
      </button>

      <div class="btn-group" v-if="aclGroup">
        <button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
          <i class="fa fa-lock"></i>
        </button>
        <ul class="dropdown-menu pull-right" role="menu">
          <li
            v-for="(acl,index) in aclGroup"
            :key="index"
            :class="{
              checked:acl.selected,
              disabled:acl.disabled
            }"
          >
            <a href="#" @click.prevent="aclClicked(acl)">
              <i class="fa fa-check" v-if="acl.selected"></i>
              {{acl.name}}
            </a>
          </li>
        </ul>
      </div>

      <button v-if="pinable" type="button" class="btn btn-box-tool" @click="togglePin()">
        <i class="fa fa-fw" :class="[pinned?'fa-thumbtack':'fa-arrows-alt']"></i>
      </button>

      <button v-if="collapsible" type="button" class="btn btn-box-tool" @click="toggleCollapse()">
        <i class="fa fa-fw" :class="[collapsed?'fa-plus':'fa-minus']"></i>
      </button>

      <button
        v-if="closeable"
        type="button"
        class="btn btn-box-tool"
        data-widget="remove"
        @click="$emit('closed')"
      >
        <i class="fa fa-fw fa-times"></i>
      </button>
    </div>
  </div>
</template>
<script>
export default {
  name: "box-header",
  props: {
    icon: String
  },
  data() {
    return {
      pinable: false,
      dataUrl: "",
      collapsible: false,
      collapsed: false,
      closeable: false,
      pinned: true,
      acl:false,
      aclGroup: [],
    };
  },
  mounted() {},
  methods: {
    togglePin() {
      this.pinned = !this.pinned;
      this.$emit("pinned", this.pinned);
      this.$parent.$emit("pinned", this.pinned);
    },
    toggleCollapse() {
      this.collapsed = !this.collapsed;
      this.$emit("collapsed", this.collapsed);
    },
    aclClicked: function(acl) {
      if (acl.disabled) return;
      acl.selected = !acl.selected;
      this.$emit("acl", acl);
    },
    pin() {
      this.pinned = true;
    },
    unpin() {
      this.pinned = false;
    }
  }
};
</script>

