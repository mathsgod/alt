<template>
    <div class="tab-pane" :class={active:active}>
        <slot></slot>
    </div>
</template>

<script>
export default {
  props: {
    name: {
      required: true
    },
    url: String
  },
  data() {
    return {
      active: false,
      html: ""
    };
  },
  methods: {
    loading() {
      $(this.$el).html(
        '<div class="box box-solid"><div class="box-body no-padding"><div class="overlay"><i class="fa fa-spinner fa-spin"></i></div></div></div>'
      );
    },
    select() {
      this.active = true;
      //download
      if (this.url) {
        $(this.$el).html("");
        this.loading();
        return $.get(this.url, html => {
          $(this.$el).html(html);
          this.$emit("loaded");
        }).fail(() => {
          $(this.$el).html("error when loading this page");
        });
      }
      this.$emit("loaded");
    }
  }
};
</script>
