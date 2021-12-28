<template>
  <!-- Header -->
  <div
    class="
      flex flex-col
      md:flex-row
      space-y-3
      md:space-y-0 md:justify-between
      mt-4
    "
  >
    <div class="flex flex-col space-y-2 md:space-y-4">
      <!-- Buttons -->
      <div class="flex space-x-2 md:items-center">
        <slot name="buttons"></slot>
      </div>

      <!-- Filters and sort -->
      <div
        class="
          flex flex-col
          md:flex-row
          justify-between
          md:justify-start
          space-y-1
          md:space-y-0 md:space-x-2
        "
      >
        <div class="dropdown">
          <div
            @click="showDropdown('filter-ul')"
            tabindex="0"
            class="btn btn-sm w-full"
          >
            <i class="mr-1 fas fa-filter"></i>
            <span>{{ `Filtruj: ${currentFilterLabel}` }}</span>
          </div>
          <ul
            id="filter-ul"
            tabindex="0"
            class="
              p-2
              shadow
              menu
              dropdown-content
              bg-base-100
              rounded-box
              w-52
            "
          >
            <li
              v-for="row in frontFilters"
              :key="row.value"
              @click="filterServices(row)"
            >
              <a>{{ row.label }}</a>
            </li>
          </ul>
        </div>
        <div class="dropdown">
          <div
            @click="showDropdown('sort-ul')"
            tabindex="1"
            class="btn btn-sm w-full"
          >
            <span id="sort-icon"
              ><i class="mr-1 fas fa-sort-amount-up"></i
            ></span>
            {{ `Sortuj: ${currentSortLabel}` }}
          </div>
          <ul
            id="sort-ul"
            tabindex="1"
            class="
              p-2
              shadow
              menu
              dropdown-content
              bg-base-100
              rounded-box
              w-52
            "
          >
            <li v-for="row in columns" :key="row.value" @click="sort(row)">
              <a>{{ row.label }}</a>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Search bar -->
    <div class="relative">
      <input
        v-model="params.search"
        @click="params.searchField = 'name'"
        placeholder="Szukaj"
        class="w-full pr-16 input input-primary input-sm input-bordered"
        type="text"
      />
      <button
        class="
          absolute
          top-0
          right-0
          rounded-l-none
          btn btn-sm btn-primary
          hover:bg-primary
          cursor-default
          no-animation
        "
      >
        <i class="fas fa-lg fa-search"></i>
      </button>
    </div>
  </div>

  <!-- Content -->
  <div>
    <slot name="content"></slot>
  </div>

  <!-- Footer -->
  <div
    class="
      w-full
      sm:w-auto
      flex-col flex
      sm:flex-row-reverse
      justify-between
      py-3
      text-center
      space-y-4
      sm:space-y-0
      pl-1
      items-center
      sm:text-sm
    "
  >
    <pagination :links="data.links"></pagination>
    <span v-if="data.total"
      >Wyniki od {{ data.from }} do {{ data.to }}. Łącznie
      {{ data.total }} wyników.</span
    >
  </div>
</template>

<script>
import { pickBy, throttle } from "lodash";
import { reactive, watchEffect, defineComponent, ref } from "vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import Pagination from "@/Components/Pagination.vue";

export default defineComponent({
  props: {
    columns: Array,
    frontFilters: Array,
    data: Object,
    filters: Object,
    sortRoute: String,
  },

  setup(props) {
    // Filter and sort parameters
    const params = reactive({
      search: props.filters.search,
      field: props.filters.field,
      direction: props.filters.direction,
      filter: props.filters.filter,
    });

    const currentFilterLabel = props.frontFilters[params.filter]
      ? ref(props.frontFilters[params.filter].label)
      : ref(props.frontFilters[0].label);

    const currentSortLabel = params.field
      ? ref(
          props.columns.filter(
            (item) =>
              item.name === params.field && item.direction === params.direction
          )[0].label
        )
      : ref(props.columns[0].label);

    // Sort and filter functions
    const sort = (field) => {
      params.field = field.name;
      params.direction = field.direction;
      currentSortLabel.value = field.label;
      hideDropdown("sort-ul");
    };

    const filterServices = (option) => {
      params.filter = option.value;
      currentFilterLabel.value = option.label;
      hideDropdown("filter-ul");
    };

    const showDropdown = (id) =>
      (document.getElementById(id).style.visibility = "visible");
    const hideDropdown = (id) =>
      (document.getElementById(id).style.visibility = "hidden");

    // watchEffect(
    //   throttle(() => {
    //     let paramsPicked = pickBy(params);
    //     Inertia.get(route(props.sortRoute), paramsPicked, {
    //       replace: true,
    //       preserveState: true,
    //     });
    //   }, 150)
    // );

    // Returned data
    return {
      params,
      sort,
      filterServices,
      currentSortLabel,
      currentFilterLabel,
      showDropdown,
    };
  },

  watch: {
    params: {
      handler: throttle(function () {
        let params = pickBy(this.params);
        this.$inertia.get(this.route(this.sortRoute), params, {
          replace: true,
          preserveState: true,
        });
      }, 150),
      deep: true,
    },
  },

  components: {
    Pagination,
    Link,
  },
});
</script>