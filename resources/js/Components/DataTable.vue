<template>

    <!-- Table heading -->
    <div class="w-full flex justify-between">
        <slot name="buttons"></slot>
        <input v-model="params.search" @click.stop="params.searchField='name'" type="search" class="text-sm p-1 rounded-md">
    </div> 

    <!-- Table content -->
    <table class="w-full mx-auto whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden mt-4">
        <thead class="bg-gray-900">
            <tr class="text-white text-left">
                <th v-for="column in columns" :key="column" @click="sort(column.name, column.sortable)" 
                    class="font-bold text-sm uppercase">
                    <div class="flex justify-between items-center px-6 py-4">
                        <span>{{ column.label }}</span>
                        <!-- <input v-if="column.searchable" type="search" v-model="params.search" @click.stop="params.searchField=column.name" 
                            class="text-sm p-1 rounded-md"> -->
                        <i v-if="params.field === column.name && params.direction === 'asc'" class="fas fa-sort-up"></i>
                        <i v-else-if="params.field === column.name && params.direction === 'desc'" class="fas fa-sort-down"></i>
                        <i v-else-if="column.sortable" class="fas fa-sort"></i>
                    </div>
                </th>
                <th class="font-bold text-sm uppercase px-6 py-4">Działania</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            <tr v-for="row in data.data" :key="row">
                <td class="px-6 py-2" v-for="column in columns" :key="column.name">
                    {{ row[column.name] }}
                </td>
                <td class="flex justify-evenly px-6 py-2">
                    <i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
                    <i class="fas fa-trash cursor-pointer"></i>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Table footer -->
    <div class="flex justify-between mt-4 px-4">
        <span>Wyświetlam wyniki od {{data.from}} do {{data.to}} razem {{data.total}}</span>
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
        filters: Object
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
                this.$inertia.get(this.route('admin.users.index'), params, { replace: true, preserveState: true });
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