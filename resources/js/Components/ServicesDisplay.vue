<template>
    <section class="w-full md:max-w-4xl">

        <!-- Header -->
        <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:justify-between">

            <div class="flex flex-col space-y-2 md:space-y-4">
                <!-- Buttons -->
                <div class="flex space-x-2 md:items-center">
                    <slot name="buttons"></slot>
                </div>

                <!-- Filters and sort -->
                <div class="flex flex-col md:flex-row justify-between md:justify-start space-y-1 md:space-y-0 md:space-x-2">
                    <div class="dropdown">
                        <div tabindex="0" class="btn btn-sm w-full">
                            <i class="mr-1 fas fa-filter"></i>
                            <span>{{ `Filtruj: ${currentFilter}` }}</span>
                        </div> 
                        <ul id="filter-list" tabindex="0" class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52">
                            <li v-for="row in frontFilters" :key="row.value" @click="filterServices(row)">
                                <a>{{ row.label }}</a>
                            </li> 
                        </ul>
                    </div>
                    <div class="dropdown">
                        <div tabindex="1" class="btn btn-sm w-full">
                            <span id="sort-icon"><i class="mr-1 fas fa-sort-amount-up"></i></span>
                            {{ `Sortuj: ${currentSortField}` }}
                        </div> 
                        <ul id="sort-list" tabindex="1" class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52">
                            <li v-for="row in columns" :key="row.value" @click="sort(row)">
                                <a>{{ row.label }}</a>
                            </li>  
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Search bar -->
            <div class="relative">
                <input v-model="params.search" @click="params.searchField='name'" placeholder="Szukaj" class="w-full pr-16 input input-primary input-sm input-bordered" type="text"> 
                <button class="absolute top-0 right-0 rounded-l-none btn btn-sm btn-primary hover:bg-primary cursor-default no-animation"><i class="fas fa-lg fa-search"></i></button>
            </div>
        </div>

        <!-- Content -->
        <div>
            <slot name="content"></slot>
        </div>

        <!-- Footer -->
        <div>
            <pagination :links=links></pagination>
        </div>
    </section>

    <!-- content -->
    <!-- <div class="space-y-2">
        <slot name="content"></slot>
    </div> -->

    <!-- footer -->
    <!-- <div class="sm:flex justify-center py-3 rounded-lg bg-white text-center mt-2 px-3 items-center">
        <pagination :links=links></pagination>
    </div> -->

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { reactive } from 'vue'
import { ref } from "vue";
import { Link } from '@inertiajs/inertia-vue3'

export default {
    props: {
        columns: Array,
        frontFilters: Array,
        links: Object,
        filters: Object,
        sortRoute: String,
    },

    setup(props) {
        // Filter label
        const currentFilter = ref(props.frontFilters[0].label)
        const currentSortField = ref(props.columns[0].label)

        // Filter and sort parameters
        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction,
            filter: props.filters.filter
        })

        // Sort and filter functions
        const sort = (field) => {
            params.field = field.name
            params.direction = field.direction
            currentSortField.value = field.label
        }

        const filterServices = (option) => {
            params.filter = option.value
            currentFilter.value = option.label
        }

        // Returned data
        return { params, sort, filterServices, currentSortField, currentFilter, }
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
        Pagination,
        Link
    }

}
</script>