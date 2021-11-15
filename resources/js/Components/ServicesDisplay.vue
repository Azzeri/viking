<template>

    <!-- heading -->
    <div class="w-full flex justify-between">
        <slot name="buttons"></slot>
        <input v-model="params.search" @click.stop="params.searchField='name'" type="search" class="text-sm p-1 rounded-md">
        <div v-for="column in columns" :key="column" @click="sort(column.name, column.sortable)">
            <div class="flex justify-between items-center px-6 py-4">
                <span>{{ column.label }}</span>
                <i v-if="params.field === column.name && params.direction === 'asc'" class="fas fa-sort-up"></i>
                <i v-else-if="params.field === column.name && params.direction === 'desc'" class="fas fa-sort-down"></i>
                <i v-else-if="column.sortable" class="fas fa-sort"></i>
            </div>
        </div>
        <div>
            <button @click="servicesState = false">Niezakończone</button>
            <button @click="servicesState = true">Zakończone</button>
        </div>
        
    </div> 

    <!-- content -->
    <div class="w-full mx-auto whitespace-nowrap rounded-lg bg-white divide-y divide-gray-300 overflow-hidden mt-4">
        <Link as=button :href="route('admin.inventory.items.index')">Powrót</Link>

        <div v-for="row in data.data" :key="row">
            <template v-if="row.is_finished == servicesState">
                <div class="px-6 py-2">Nazwa: {{ row.name }}</div>
                <div class="px-6 py-2">Opis: {{ row.description }}</div>
                <div class="px-6 py-2">Data utworzenia: {{ row.created_at }}</div>
                <div class="px-6 py-2">Termin: {{ row.date_due }}</div>
                <div class="px-6 py-2">Przypomnienie: {{ row.notification }}</div>
                <div class="px-6 py-2">Przedmiot: {{ row.inventory_item_name }}</div>
                <div class="px-6 py-2">zakończony: {{ row.is_finished }}</div>

                <div class="flex justify-evenly px-6 py-2">
                    <i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
                    <i @click="deleteRow(row)" class="fas fa-trash cursor-pointer"></i>
                    <button v-if="row.is_finished == false" @click="finish(row)">Zakończ</button>
                </div>
            </template>
        </div>
    </div>


    <!-- footer -->
    <div class="flex justify-between mt-4 px-4">
        <span>Wyświetlam wyniki od {{data.from}} do {{data.to}} razem {{data.total}}</span>
        <pagination :links=data.links></pagination>
    </div>

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { reactive } from 'vue'
import { ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {
    props: {
        columns: Array,
        data: Object,
        filters: Object,
        sortRoute: String,
    },

    setup(props) {
        const modalOpened = ref(false)
		const modalEditMode = ref(false)
		const servicesState = ref(false)

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

        const finish = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.post('inventoryservicesfinish/', row)
        }

        return { params, sort, servicesState, finish }
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
        Pagination,
        Link
    }

}
</script>