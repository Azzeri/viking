<template>
  <div class="flex justify-start">
    <Link
      :href="href"
      as="button"
      :class="classes"
      class="
        btn
        h-12
        w-12
        rounded-r-none
        lg:rounded-r-lg
        group-hover:rounded-r-none
      "
    >
      <i :class="icon"></i>
      <slot name="avatar"></slot>
    </Link>
    <Link
      :href="href"
      as="button"
      :class="classes"
      class="
        btn
        lg:hidden lg:group-hover:flex
        rounded-l-none
        w-44
        overflow-hidden
        justify-start
      "
    >
      <slot></slot>
    </Link>
  </div>
</template>

<script>
import { Link } from "@inertiajs/inertia-vue3";
import { computed } from "@vue/reactivity";
import { defineComponent } from "vue";

export default defineComponent({
  props: {
    href: String,
    icon: String,
    active: Boolean,
  },

  setup(props) {
    // Changes button color if href is active
    const classes = computed(() => {
      return props.active
        ? "btn-primary text-primary-content"
        : "btn-neutral text-neutral-content";
    });

    return { classes };
  },

  components: {
    Link,
  },
});
</script>