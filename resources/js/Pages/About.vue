<template>
  <app-layout title="O nas">
    <div class="flex-col hero-content mx-auto self-start mt-16 space-y-16">
      <!-- Group info -->
      <div class="card lg:card-side w-full border">
        <div class="card-body">
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
        <figure>
          <img
            class="h-96 object-cover"
            src="https://images.unsplash.com/photo-1620306521121-3a55af2cd05b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=735&q=80"
          />
        </figure>
      </div>

      <!-- Events -->
      <div class="card lg:card-side w-full border">
        <figure>
          <img
            class="h-72 object-cover"
            src="https://images.unsplash.com/photo-1554540908-8f02b631026b?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80"
          />
        </figure>
        <div class="card-body">
          <h2 class="card-title">Wydarzenia</h2>
          <p>
            Rerum reiciendis beatae tenetur excepturi aut pariatur est eos. Sit
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sapiente.
          </p>
          <div class="card-actions">
            <Link :href="route('events.index')" class="btn btn-primary"
              >Zobacz</Link
            >
          </div>
        </div>
      </div>

      <!-- News -->
      <div class="card lg:card-side w-full border">
        <figure>
          <img
            class="h-72 object-cover"
            src="https://images.unsplash.com/photo-1559552440-8e17304991e1?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1174&q=80"
          />
        </figure>
        <div class="card-body">
          <h2 class="card-title">Aktualności</h2>
          <p>
            Rerum reiciendis beatae tenetur excepturi aut pariatur est eos. Sit
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sapiente.
          </p>
          <div class="card-actions">
            <Link :href="route('news.index')" class="btn btn-primary"
              >Zobacz</Link
            >
          </div>
        </div>
      </div>

      <!-- Store -->
      <div class="card lg:card-side w-full border">
        <div class="card-body">
          <h2 class="card-title">Nasze produkty</h2>
          <p>
            Rerum reiciendis beatae tenetur excepturi aut pariatur est eos. Sit
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sapiente.
          </p>
          <div class="card-actions">
            <Link :href="route('store.index')" class="btn btn-primary"
              >Zobacz</Link
            >
          </div>
        </div>
        <figure>
          <img
            class="h-72 object-cover"
            src="https://images.unsplash.com/photo-1564598522417-e9ac1425d7c6?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=687&q=80"
          />
        </figure>
      </div>

      <!-- Gallery -->
      <div class="card lg:card-side w-full border">
        <div class="card-body">
          <h2 class="card-title">Galeria zdjęć</h2>
          <p>
            Rerum reiciendis beatae tenetur excepturi aut pariatur est eos. Sit
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sit necessitatibus veritatis sed molestiae voluptates incidunt iure
            sapiente.
          </p>
          <div class="card-actions">
            <Link :href="route('gallery.index')" class="btn btn-primary"
              >Zobacz</Link
            >
          </div>
        </div>
        <figure>
          <img
            class="h-72 object-cover"
            src="https://images.unsplash.com/photo-1609894851180-7be27983da7d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80"
          />
        </figure>
      </div>

      <!-- Group Members -->
      <div class="w-full">
        <h1 class="card-title">Nasi członkowie</h1>
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
                  :src="
                    user.profile_photo_path ||
                    `https://ui-avatars.com/api/?name=${user.name} ${user.surname}&color=7F9CF5&background=EBF4FF`
                  "
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
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  props: {
    users: Object,
  },

  components: {
    AppLayout,
    Link,
  },
});
</script>