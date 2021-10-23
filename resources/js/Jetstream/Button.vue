<template>
  <button
    :type="type"
    class="
      inline-flex
      items-center
      px-10
      py-3
      bg-gray-800
      bg-opacity-80
      shadow-lg
      border border-transparent
      rounded-full
      font-semibold
      text-xs text-white
      uppercase
      tracking-widest
      hover:bg-gray-700
      active:bg-gray-900
      focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300
      disabled:opacity-25
      transition
    "
  >
    <slot></slot>
  </button>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    type: {
      type: String,
      default: "submit",
    },
  },
});
</script>
