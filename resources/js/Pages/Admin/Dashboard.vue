<template>
    <admin-panel-layout>
      <template #page-title>Panel</template>
      <div class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
        <h1>Witaj w panelu administratora</h1>
  
      </div>
    </admin-panel-layout>
</template>

<script>
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";

export default {
  components: {
    AdminPanelLayout
  },
};
</script>

<style>
</style>