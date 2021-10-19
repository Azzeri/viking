<template>
  <admin-panel-layout title="Użytkownicy">
    <div class="mx-auto max-w-6xl text-center">
        {{users}}
    </div>
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";

export default defineComponent({
  props: {
    users: Object,
  },
  setup() {},

  components: {
    AdminPanelLayout,
  },
});
</script>
        AdminPanelLayout