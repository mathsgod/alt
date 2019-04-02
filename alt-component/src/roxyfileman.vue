<template>
  <input class="form-control">
</template>
<script>
export default {
  name: "roxyfileman",
  mounted() {
    $(this.$el).on("click", function() {
      var d = new Date().getTime();
      d = "roxy_" + d;

      $(this).attr("data-roxy-id", d);

      var resize_width = $(this).attr("roxy-resize-width");
      var type =
        $(this).attr("roxy-type") == undefined
          ? "image"
          : $(this).attr("roxy-type");

      var session = "";
      if ($(this).attr("roxy-session")) {
        session = $(this).attr("roxy-session");
      }

      var path =
        "plugins/RoxyFileman.1.4.5/fileman/index.html?type=" +
        type +
        "&integration=custom&txtFieldId=" +
        d +
        "&session=" +
        session;

      if (resize_width != undefined) {
        path += "&resize_width=" + resize_width;
      }
      $.fancybox.open({
        src: path,
        type: "iframe"
      });
    });
  }
};
</script>

