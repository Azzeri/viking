<template>

    <!-- heading -->
    <div class="w-full space-y-4 lg:space-y-0 lg:mt-4">

        <div class="flex justify-between lg:justify-start items-center lg:space-x-3 lg:space-y-0 lg:mb-4">
            <slot name="buttons"></slot>
        </div>       

        <div class="lg:flex space-y-4 lg:space-y-0">
            <div class="flex lg:justify-start lg:w-1/2">
                <div class="lg:flex items-center w-1/2 lg:w-auto">
                    <span class="text-white">Filtruj</span>
                    <select class="lg:ml-2 rounded-lg w-full lg:w-auto" v-model=filter @change=filterServices(filter)>
                        <option v-for="filter in frontFilters" :key=filter :value=filter.value>{{ filter.label }}</option>
                    </select>
                </div>
                <div class="lg:flex items-center w-1/2 lg:w-auto">
                    <div class="flex space-x-2">
                        <span class="lg:ml-2 text-white">Sortuj</span>
                        <div @click=changeSortOrder class="flex justify-between items-center">
                            <i v-if="params.direction === 'asc'" class="fas fa-sort-up"></i>
                            <i v-else-if="params.direction === 'desc'" class="fas fa-sort-down"></i>
                        </div>
                    </div>
                    
                    <select class="lg:ml-2 rounded-lg w-full lg:w-auto" v-model="sortField" @change=sort(sortField.name,sortField.sortable)>
                        <template v-for="row in columns" :key=row> 
                            <option v-if=row.sortable :value="row">
                                {{ row.label }}
                            </option>    
                        </template>
                    </select>
                </div>
            </div>

            <div class="flex justify-end w-full items-center lg:w-1/2">
                <div class="flex justify-center items-center px-2 rounded-l-full h-10 bg-white"><i class="fas fa-lg fa-search"></i></div>
                <input v-model="params.search" @click="params.searchField='name'" type="search" class="border-none w-full lg:w-auto h-10 px-2 py-1 rounded-r-full">
            </div>
        </div>

    </div> 

    <!-- content -->
    <div class="space-y-2">
        <slot name="content"></slot>
    </div>

    <!-- footer -->
    <div class="sm:flex justify-center py-3 rounded-lg bg-white text-center mt-2 px-3 items-center">
        <pagination :links=links></pagination>
    </div>

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
		const servicesState = ref(false)
        const filter = props.filters.filter ? ref(props.filters.filter) : ref(0)
        const sortField = ref(props.columns[0])

        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction,
            filter: props.filters.filter
        })

        function sort(field, sortable) {
            if (sortable){
                params.field = field
                changeSortOrder()
            }
        }

        const changeSortOrder = _ => {
            params.direction = params.direction === 'asc' ? 'desc' : 'asc'
        }

        sort(props.columns[0].name, props.columns[0].sortable)

        const filterServices = (option) => {
            params.filter = option
        }

        return { params, sort, servicesState, filter, filterServices, sortField, changeSortOrder }
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