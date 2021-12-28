<template>
  <label class="label"
    ><span class="label-text"
      >{{ name }}<span v-if="required" class="ml-1 text-red-500">*</span></span
    ></label
  >
  <input
    v-model="form[model]"
    :id="id"
    :type="type"
    :placeholder="name"
    :required="required"
    :min="min"
    :minlength="min"
    :max="max"
    :maxlength="max"
    :class="extraClass"
    :autocomplete="model"
    class="input input-primary input-bordered"
  />
  <label
    v-if="form.errors[model]"
    class="label label-text-alt text-error text-sm"
    >{{ form.errors[model] }}</label
  >
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    name: String,
    type: String,
    required: Boolean,
    id: String,
    model: String,
    form: Object,
    min: String,
    max: String,
    extraClass: String,
  },
});
</script>