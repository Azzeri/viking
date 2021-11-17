<template>

    <!-- Table heading -->
    <div class="w-full space-y-4 sm:space-y-0 sm:flex justify-between sm:mt-4">
        <div class="flex justify-between items-center sm:space-x-3">
            <slot name="buttons"></slot>
        </div>
        <div class="flex w-full sm:w-auto">
            <div class="flex justify-center items-center px-2 rounded-l-full h-10 bg-white"><i class="fas fa-lg fa-search"></i></div>
            <input v-model="params.search" @click="params.searchField='name'" type="search" class="border-none w-full h-10 px-2 py-1 rounded-r-full">
        </div>
    </div> 

    <!-- Table content -->
		<table class="w-full flex flex-row flex-no-wrap rounded-lg sm:bg-white overflow-auto sm:shadow-lg my-5 sm:mt-2 sm:inline-table">
			<thead class="text-white space-y-2">
                <tr v-for="(index) in data.data" :key=index class="bg-gray-600 flex flex-col flex-no-wrap sm:hidden rounded-l-lg sm:mb-0 sm:last:table-row divide-y divide-gray-600 sm:divide-none">
                    <th v-for="column in columns" :key="column" @click="sort(column.name, column.sortable)" 
                        class="px-3 py-1 sm:py-2 text-left uppercase" :class=extraClass>
                        <div class="flex justify-between items-center space-x-2">
                            <span>{{ column.label }}</span>
                            <i v-if="params.field === column.name && params.direction === 'asc'" class="fas fa-sort-up"></i>
                            <i v-else-if="params.field === column.name && params.direction === 'desc'" class="fas fa-sort-down"></i>
                            <i v-else-if="column.sortable" class="fas fa-sort"></i>
                        </div>
                    </th>
                </tr>
            </thead> 
            <tbody class="flex-1 sm:flex-none sm:divide-y sm:divide-gray-300 space-y-2">
                <slot name="content"></slot>
            </tbody>
        </table>

    <!-- Table footer -->
    <div class="sm:flex justify-between py-3 rounded-lg bg-white text-center space-y-3 sm:space-y-0 px-3 items-center">
        <span>Wyniki od {{data.from}} do {{data.to}}. Łącznie {{data.total}} wyników.</span>
        <pagination :links=data.links></pagination>
    </div>

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { reactive } from 'vue'

export default {
    props: {
        columns: Array,
        data: Object,
        filters: Object,
        sortRoute: String,
        extraClass: String
    },

    setup(props) {
        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction
        })

        function sort(field, sortable) {
            if (sortable){
                params.field = field
                params.direction = params.direction === 'asc' ? 'desc' : 'asc'
            }
        }

        return { params, sort }
    },

    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route(this.sortRoute), params, { replace: true, preserveState: true });
            }, 150),
            deep: true
        },
    },

    methods: {
        edit(row) {
            this.$emit('edit', row)
        }
    },

    emits : ['edit'],


    components: {
        Pagination
    }

}
</script>
<style>

  @media (min-width: 640px) {
    /* table {
      display: inline-table !important;
    } */

    /* thead tr:not(:first-child) {
      display: none;
    } */
  }

  /* td:not(:last-child) {
    border-bottom: 0;
  } */

  /* th:not(:last-child) {
    border-bottom: 2px solid rgba(0, 0, 0, .1);
  } */

</style>