<template>
    <div>
    <form>
        <input type='file' style='display:none' ref="file" v-on:change="onSelectedFile" accept="image/*"/>
    </form>
    <div class='btn-group'>
        <button class='btn btn-xs btn-primary' v-on:click='onUpload'><i class='fa fa-upload'></i></button>
        <button class='btn btn-xs btn-danger' v-if="displayImage"><i class='fa fa-times' v-on:click='onDelete'></i></button>
    </div>
    <div>
        <div v-if='loading'>
            <i class="fa fa-refresh fa-spin fa-2x fa-fw"></i>
            <span class="sr-only">Loading...</span>
        </div>
        <img width='100px' :src='imgSrc' v-if="displayImage"/>
    </div>
</div>
</template>
<script>
export default {
  props: ["imgSrc", "uploadUri", "deleteUri", "hasImage", "imgWidth"],
  data: function() {
    return {
      displayImage: true,
      loading: false
    };
  },
  created: function() {
    if (this.$props.hasImage) {
      this.$data.displayImage = true;
    } else {
      this.$data.displayImage = false;
    }
  },
  methods: {
    onSelectedFile: function() {
      if (!this.$props.uploadUri) {
        console.error("upload-uri not defined");
        return;
      }

      var formData = new FormData();
      var f = this.$refs.file;
      var file = f.files[0];
      formData.append("file", file, file.name);

      var xhr = new XMLHttpRequest();
      xhr.open("POST", this.$props.uploadUri, true);

      // Set up a handler for when the request finishes.
      var that = this;
      that.$data.displayImage = false;
      xhr.onload = function() {
        if (xhr.status === 200) {
          that.$data.loading = false;
          that.$data.displayImage = true;
        } else {
          alert("An error occurred!");
        }
      };
      this.$data.loading = true;
      xhr.send(formData);
    },
    onUpload: function() {
      this.$refs.file.click();
    },
    onDelete: function() {
      if (!this.$props.deleteUri) {
        console.error("delete-uri not defined");
        return;
      }

      var that = this;
      this.$data.displayImage = false;
      that.$data.loading = true;

      var xhr = new XMLHttpRequest();
      xhr.open("GET", this.$props.deleteUri, true);
      xhr.onload = function() {
        if (xhr.status === 200) {
          that.$data.loading = false;
        } else {
          alert("An error occurred!");
        }
      };
      xhr.send();
    }
  }
};
</script>

