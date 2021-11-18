<template>

    <!-- heading -->
    <div class="w-full space-y-4 lg:space-y-0 lg:mt-4">

        <div class="flex justify-between lg:justify-start items-center lg:space-x-3 lg:space-y-0 lg:mb-4">
            <Link as=button :href="route('admin.inventory.items.index')" class="lg:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
				<i class="fas fa-arrow-left fa-lg"></i>
            </Link>
            
            <button @click="modalOpened = true" class="lg:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
                <i class="fas fa-plus fa-lg"></i>
            </button>
            <button @click="modalOpened = true" class="hidden lg:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
                <i class="fas fa-plus fa-lg"></i>
            </button>
        </div>       

        <div class="lg:flex space-y-4 lg:space-y-0">
            <div class="flex lg:justify-start lg:w-1/2">
                <div class="lg:flex items-center w-1/2 lg:w-auto">
                    <span class="text-white">Filtruj</span>
                    <select class="lg:ml-2 rounded-lg w-full lg:w-auto" v-model=filter @change=filterServices(filter)>
                        <option value=0>
                            Niewykonane
                        </option>
                        <option value=1>
                            Wykonane
                        </option>
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
        <template v-for="row in data.data" :key="row">
            <div class="w-full rounded-lg bg-white p-3 mt-3">
                <div @click=showDetails(row.id) class="flex justify-between">
                    <div class="font-semibold"> {{ row.name }} </div>
                    <div>
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>
                
                <div class="text-sm text-gray-600"> {{ row.inventory_item_name }} </div>
                <div class="hidden my-3" :id=row.id>
                    <ul>
                        <li><span class="font-semibold">Utworzono: </span>{{row.created_at.split('T')[0]}}</li>
                        <li><span class="font-semibold">Termin: </span>{{row.date_due}}</li>
                        <li v-if="!row.is_finished"><span class="font-semibold">Przypomnienie: </span><span v-html="booleanIcon(row.notification)"></span></li>
                        <li v-if=row.assigned_user><span class="font-semibold">Przydzielone użytkownikowi: {{ row.assigned_user }}</span></li>
                        <li v-if=row.is_finished><span class="font-semibold">Wykonane przez: {{ row.performed_by }}</span></li>
                        <li v-if=row.is_finished><span class="font-semibold">Wykonano: {{ row.date_performed }}</span></li>

                        <li v-if="!row.is_finished" class="space-x-3">
                            <i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
						    <i @click="deleteRow(row)" class="fas fa-trash cursor-pointer text-red-700"></i>
                        </li>
                    </ul>
                </div>
                <div v-if=row.description>{{ row.description }}</div>
                <button v-if="row.is_finished == false" @click="finish(row)" class="font-semibold mt-2 px-2 py-1 bg-green-500 rounded-full text-white" >Zrobione!</button>
            </div>
		</template>
<!-- 
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
        </div> -->
    </div>


    <!-- footer -->
    <div class="sm:flex justify-center py-3 rounded-lg bg-white text-center mt-2 px-3 items-center">
        <pagination :links=data.links></pagination>
    </div>

</template>

<script>
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { reactive } from 'vue'
import { ref, computed } from "vue";
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

        const filter = ref(0)
        const sortField = ref(props.columns[0])

        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction
        })

        function sort(field, sortable) {
            if (sortable){
                params.field = field
                changeSortOrder()
            }
        }

        const booleanIcon = (notification) => {
            return notification == true ? '<i class="fas fa-check text-green-500">' : '<i class="fas fa-times text-red-500">'
        }

        const changeSortOrder = _ => {
            params.direction = params.direction === 'asc' ? 'desc' : 'asc'
        }

        sort(props.columns[0].name, props.columns[0].sortable)

        const finish = (row) => {
            if (!confirm('Czy potwierdzasz wykonanie serwisu?')) return;
            Inertia.post('inventoryservicesfinish/', row)
        }

        const filterServices = (option) => {
            params.filter = option
            // servicesState.value = option
        }

        const showDetails = (id) => {
			let element = document.getElementById(id)
			element.classList.contains('hidden') ? element.classList.remove('hidden') : element.classList.add('hidden')
		}

        return { params, sort, servicesState, finish, filter, filterServices, sortField, changeSortOrder, showDetails, booleanIcon }
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