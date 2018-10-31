<template>
     <div class="input-group">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-caret-down"></i>
            </button>
            <ul class="dropdown-menu">
                <li v-for="(o,index) in options" :key="index">
                    <a href="javascript:void(0)" v-text="o" v-on:click.prevent="itemSelected(o)"></a>
                </li>
            </ul>
        </div>
        <input type="text" class="form-control"  v-bind="$props" :value="v" :required="required" @input="$emit('input',$event.data)">
    </div>
</template>

<script>
export default {
  name: "bs-input-select",
  props: {
    required: Boolean,
    name: String,
    value: String,
    options: Array|Object
  },
  data() {
    return {
      v: this.value
    };
  },
  methods: {
    itemSelected(value) {
      this.v = value;
      this.$emit("input", value);
    }
  }
};
</script>
