<template>
  <app-layout title="Wydarzenia">
    <div class="flex-col hero-content mx-auto">
      <template v-if="events.data == null">
        <h1 class="text-lg font-semibold">
          Nie dodano jeszcze żadnych wydarzeń
        </h1>
      </template>

      <template v-else>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-8">
          <article
            v-for="row in events.data"
            :key="row.id"
            class="card shadow-lg border rounded-lg"
          >
            <figure class="rounded-lg">
              <img :src="row.photo_path" class="h-64 object-cover" />
            </figure>
            <div class="card-body justify-between">
              <div class="card-title">
                <span>{{ row.name }}</span>
                <h2 class="text-gray-500 text-base">
                  <div>{{ `${row.date_start} - ${row.date_end}` }}</div>
                  <div class="text-sm">{{ `${row.addrTown}` }}</div>
                </h2>
              </div>
              <p>{{ row.description }}</p>
              <div
                class="badge mt-2"
                :class="{
                  'badge-secondary': !row.is_finished,
                  'badge-accent': row.is_finished,
                }"
              >{{ row.is_finished ? 'minione' : 'nadchodzące' }}</div>
              <div class="card-actions">
                <Link
                  as="button"
                  :href="route('events.show', row.id)"
                  class="btn btn-primary w-full md:w-auto"
                  >Więcej</Link
                >
              </div>
            </div>
          </article>
        </div>
        <Pagination
          :links="events.links"
          :lg="true"
          class="md:self-start mt-4"
        ></Pagination>
      </template>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Pagination from "@/Components/Pagination.vue";

export default defineComponent({
  props: {
    events: Object,
  },

  components: {
    AppLayout,
    Pagination,
    Link,
  },
});
</script>
