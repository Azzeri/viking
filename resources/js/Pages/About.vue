<template>
  <app-layout title="O nas" :displayNavbar="false">
    <div class="flex-col hero-content mx-auto self-start mt-16 space-y-24">
      <div
        class="
          flex flex-col
          md:flex-row-reverse
          gap-4
          w-full
          items-center
          justify-center
        "
      >
        <div class="md:w-2/3">
          <img
            class="object-cover rounded-lg md:mask md:mask-squircle"
            src="https://images.unsplash.com/photo-1554540908-8f02b631026b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80"
          />
        </div>
        <div class="md:w-1/3 flex flex-col">
          <h1 class="text-5xl lg:text-7xl" style="font-family: montserrat">
            {{ $page.props.groupInfo.name }}
          </h1>
          <h2
            class="mt-2 text-2xl text-gray-500"
            style="font-family: montserrat"
          >
            {{ $page.props.groupInfo.motto }}
          </h2>
          <p class="mt-5 text-justify lg:text-lg">
            {{ $page.props.groupInfo.description }}
          </p>
        </div>
      </div>

      <AboutCards title="Aktualności" :data="news" link="news.index" />

      <AboutCards title="Wydarzenia" :data="events" link="events.index" />

      <AboutCards title="Sklep" :data="store" link="store.index" />

      <AboutCards title="Galeria" :data="gallery" link="gallery.index" />

      <!-- Group Members -->
      <div class="w-full">
        <h1 class="text-2xl" style="font-family: montserrat">
          Nasi członkowie
        </h1>

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
                <img :src="profilePhotoSource(user)" />
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
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import AboutCards from "@/Components/AboutCards.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { profilePhotoSource } from "@/shared.js";

export default defineComponent({
  props: {
    users: Object,
    events: Object,
    gallery: Object,
    news: Object,
    store: Object,
  },

  setup() {
    return { profilePhotoSource };
  },

  components: {
    AppLayout,
    Link,
    AboutCards,
  },
});
</script>