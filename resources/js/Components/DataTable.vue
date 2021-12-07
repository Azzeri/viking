<template>
    <div class="">

        <!-- Table heading -->
        <div class="space-y-4 sm:space-y-0 sm:flex justify-between sm:mt-4 sm:space-x-5">
            <!-- Buttons -->
            <div class="flex justify-between items-center sm:space-x-3">
                <slot name="buttons"></slot>
            </div>

            <!-- Filters -->
            <div class="space-x-2 text-center">
                <button v-for="filter in frontFilters" :key=filter @click=filterServices(filter.value) :class="[filter.color, {'btn-active':params.filter == filter.value}]"  class="btn sm:btn-xs">{{ filter.label }}</button> 
            </div>

             <!-- Search -->
            <div class="relative">
                <input v-model="params.search" @click="params.searchField='name'" placeholder="Szukaj" class="w-full pr-16 input input-primary sm:input-sm input-bordered" type="text"> 
                <button class="absolute top-0 right-0 rounded-l-none btn sm:btn-sm btn-primary hover:bg-primary cursor-default no-animation"><i class="fas fa-lg fa-search"></i></button>
            </div>
        </div> 

        <!-- Table content -->
            <!-- <table class="w-full flex flex-row flex-no-wrap rounded-lg sm:bg-white shadow-lg overflow-auto mt-4 sm:mt-2 sm:inline-table">
                <thead class="text-white space-y-2">
                    <tr v-for="(index) in data.data" :key=index class="bg-gray-600 flex flex-col flex-no-wrap sm:hidden rounded-l-lg sm:mb-0 sm:last:table-row divide-y divide-gray-600 sm:divide-none">
                        <th v-for="column in columns" :key="column" @click="sort(column)" 
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
            </table> -->
            <div class="overflow-x-auto mt-4">
                <table class="table sm:table-compact w-full">
                    <thead>
                        <tr>
                            <th v-for="column in columns" :key="column" @click="column.sortable ? sort(column) : true">
                                <div class="flex justify-between items-center space-x-2">
                                    <span>{{ column.label }}</span>
                                    <i v-if="params.field === column.name && params.direction === 'asc'" class="fas fa-sort-up"></i>
                                    <i v-else-if="params.field === column.name && params.direction === 'desc'" class="fas fa-sort-down"></i>
                                    <i v-else-if="column.sortable" class="fas fa-sort"></i>
                                </div>
                            </th>
                        </tr>
                    </thead> 
                    <tbody>
                        <slot name="content"></slot>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th v-for="column in columns" :key="column">{{ column.label }}</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            

        <!-- Table footer -->
        <div class="w-full flex-col flex sm:flex-row-reverse justify-between py-3 rounded-lg bg-white text-center space-y-4 sm:space-y-0 px-3 items-center sm:text-sm">
            <pagination :links=data.links></pagination>
            <span>Wyniki od {{data.from}} do {{data.to}}. Łącznie {{data.total}} wyników.</span>
        </div>
    </div>
</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { reactive, ref } from 'vue'

export default {
    props: {
        columns: Array,
        data: Object,
        filters: Object,
        frontFilters: Array,
        sortRoute: String,
    },

    setup(props) {
        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction,
            filter: props.filters.filter || ref(0)
        })

        const sort = (field) => {
            params.field = field.name
            params.direction = params.direction === 'asc' ? 'desc' : 'asc'
        }

        const filterServices = (option) => params.filter = option

        return { params, sort, filterServices }
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

    components: {
        Pagination
    }

}
</script>