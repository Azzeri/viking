<template>
  <div :id="id" class="modal mt-16" @click="close()">
    <div
      @click.stop
      class="modal-box overflow-y-auto"
      :class="maxWidth"
      style="max-height: 90vh"
    >
      <div class="flex items-center justify-between">
        <div>
          <slot name="side"></slot>
        </div>
        <button @click="close()" class="btn btn-sm btn-square btn-ghost">
          <i class="fas fa-times"></i>
        </button>
      </div>

      <slot name="content"></slot>

      <div class="modal-action">
        <slot name="footer"></slot>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, watch } from "vue";

export default defineComponent({
  emits: ["close"],

  props: {
    show: Boolean,
    id: String,
    maxWidth: String,
  },

  setup(props, { emit }) {
    watch(
      () => props.show,
      (_) =>
        document.getElementById(props.id).classList.contains("modal-open")
          ? document.getElementById(props.id).classList.remove("modal-open")
          : document.getElementById(props.id).classList.add("modal-open")
    );

    const close = (_) => emit("close");

    return { close };
  },
});
</script>
