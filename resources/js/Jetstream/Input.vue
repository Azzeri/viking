<template>
  <input
    class="
      border-gray-500 border-opacity-5
      bg-white bg-opacity-30
      rounded-full
      shadow-md
      py-3
      text-gray-700
      placeholder-gray-700
      font-semibold
      focus:border-gray-500 focus:ring focus:ring-gray-700 focus:ring-opacity-80
    "
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
  />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: ["modelValue"],

  emits: ["update:modelValue"],

  methods: {
    focus() {
      this.$refs.input.focus();
    },
  },
});
</script>
 class="
      border-gray-300
      focus:border-indigo-300
      focus:ring
      focus:ring-indigo-200
      focus:ring-opacity-50
      rounded-full
      shadow-sm
    "