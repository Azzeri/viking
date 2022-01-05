<template>
  <app-layout title="Szczegóły wydarzenia">
    <div class="flex-col hero-content place-self-start mx-auto">
      <Link
        :href="route('events.index')"
        class="btn btn-primary btn-sm self-start"
      >
        <i class="fas fa-arrow-left mr-2"></i>
        Powrót
      </Link>
      <div class="card bordered shadow-lg max-w-3xl">
        <figure>
          <img :src="event.photo_path" class="h-96 object-cover" />
        </figure>
        <div class="card-body">
          <div class="card-title">
            <span>{{ event.name }}</span>
          </div>
          <div
            class="badge"
            :class="{
              'badge-secondary': !event.is_finished,
              'badge-accent': event.is_finished,
            }"
          >
            {{ event.is_finished ? "minione" : "nadchodzące" }}
          </div>
          <p class="mt-2 text-justify">{{ event.description }}</p>
          <h1 v-if="event.description_summary" class="mt-4 card-title">
            Podsumowanie
          </h1>
          <p class="text-justify">{{ event.description_summary }}</p>
          <div class="card-title mt-4">
            <span>Czas i miejsce</span>
            <h2 class="text-sm mt-2">
              <div>{{ `${event.date_start} - ${event.date_end}` }}</div>
              <div>
                {{
                  `${
                    event.time_end
                      ? `${event.start}-${event.time_end}`
                      : `${event.time_start}`
                  }`
                }}
              </div>
              <div class="mt-2">
                {{ `${event.addrStreet} ${event.addrNumber}` }}
              </div>
              <div>{{ `${event.addrPostCode} ${event.addrTown}` }}</div>
            </h2>
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";

export default defineComponent({
  props: {
    event: Object,
  },

  components: {
    AppLayout,
    Link,
  },
});
</script>