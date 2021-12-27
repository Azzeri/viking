<template>
  <div v-if="links.length > 3" class="btn-group">
    <template v-for="(link, p) in links" :key="p">
      <div
        v-if="link.url === null"
        class="btn sm:btn-sm btn-disabled"
        :class="{ 'sm:btn-md': lg }"
        v-html="link.label"
      />
      <Link
        v-else
        class="btn sm:btn-sm"
        :class="{ 'btn-active': link.active, 'sm:btn-md': lg }"
        :href="link.url"
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    links: Array,
    lg: Boolean,
  },
  components: {
    Link,
  },
});
</script>