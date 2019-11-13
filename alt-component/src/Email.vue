<template>
  <div class="input-group">
    <span class="input-group-addon">
      <i class="far fa-envelope"></i>
    </span>
    <alt-input
      type="email"
      :id="id"
      :name="name"
      :value="value"
      :required="required"
      :sm="sm"
      :lg="lg"
      @input="$emit('input',$event)"
      @change="$emit('change',$event)"
    ></alt-input>
  </div>
</template>
<script>
import input from "./Input.vue";
export default {
  props: {
    required: Boolean,
    id: String,
    name: String,
    value: {},
    sm: Boolean,
    lg: Boolean
  },
  components: {
    "alt-input": input
  }
};
</script>

