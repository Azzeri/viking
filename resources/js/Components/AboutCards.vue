<template>
  <div class="w-full">
    <Link
      :href="route(link)"
      class="text-2xl hover:text-gray-400"
      style="font-family: montserrat"
      >{{ title }}</Link
    >

    <div class="w-full grid md:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
      <article
        v-for="row in data"
        :key="row"
        class="card shadow-lg bordered rounded-lg"
      >
        <figure>
          <img :src="row.photo" class="h-64 object-cover" />
        </figure>
        <div v-if="row.label" class="card-body justify-between">
          <div class="card-title">
            <span>{{ row.label }}</span>
          </div>
        </div>
      </article>
    </div>
  </div>
</template>

<script>
import { defineComponent } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  props: {
    data: Object,
    title: String,
    link: String,
  },

  components: {
    Link,
  },
});
</script>