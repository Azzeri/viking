<template>
  <app-layout title="O nas">
    <div class="flex-col hero-content mx-auto mt-32">
      <!-- Group info -->
      <div class="flex flex-col lg:flex-row-reverse gap-4 w-full">
        <img
          class="w-11/12 sm:w-auto max-w-sm rounded-lg shadow-2xl"
          src="https://cdn.pixabay.com/photo/2020/08/17/08/45/axe-5494732_960_720.jpg"
        />
        <div>
          <h1 class="text-5xl font-bold">
            {{ $page.props.groupInfo.name }}
          </h1>
          <h2 class="mt-2 text-2xl font-bold text-gray-500">
            {{ $page.props.groupInfo.motto }}
          </h2>
          <p class="mt-5 text-justify">
            {{ $page.props.groupInfo.description }}
          </p>
        </div>
      </div>
      <!-- Group Members -->
      <h1 class="text-3xl mt-15 self-start font-bold">Nasi członkowie</h1>
      <div
        class="
          grid
          sm:grid-cols-2
          lg:grid-cols-3
          xl:grid-cols-4
          gap-4
          w-full
          mt-5
        "
      >
        <div v-for="user in users" :key="user.id" class="flex gap-2">
          <div class="avatar">
            <div class="w-20 h-20 mask mask-squircle">
              <img
                src="http://daisyui.com/tailwind-css-component-profile-1@94w.png"
              />
            </div>
          </div>
          <div class="mt-2">
            <h1>
              {{
                `${user.name} ${user.nickname ? `"${user.nickname}"` : ""} ${
                  user.surname
                }`
              }}
            </h1>
            <h2 class="text-gray-500 text-sm">
              {{ user.role ?? "Członek grupy" }}
            </h2>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
  props: {
    users: Object,
  },

  components: {
    AppLayout,
  },
});
</script>