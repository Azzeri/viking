<template>

    <div v-if=showCategoriesOverlay>
        <div class="w-screen fixed h-full p-4 bg-black bg-opacity-50"></div>
        <div class="w-screen fixed h-screen p-4 bg-white mt-36 ">
            <div class="flex justify-between items-center">
                <h1 class="text-lg font-bold">Kategorie</h1>
                <i @click="showCategoriesOverlay = false" class="far fa-times-circle fa-lg"></i>
            </div>
        </div>
    </div>

    <app-layout title="O nas">
        
        <div class="main mx-auto max-w-6xl py-6 px-2 lg:pt-16">
            <div class="sm:flex justify-between flex-row-reverse">
                <div class="flex w-full sm:w-auto items-center">
                    <div class="flex justify-center items-center px-2 rounded-l-full h-10 bg-white"><i class="fas fa-lg fa-search"></i></div>
                    <input v-model="params.search" @click="params.searchField='name'" type="search" class="border-none w-full h-10 px-2 py-1 rounded-r-full">
                </div>
                <div class="flex justify-between space-x-2 items-center mt-4 sm:mt-0">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-sort fa-lg"></i>
                        <select class="rounded-lg" v-model="sortField" @change=sort(sortField)>
                            <option :value="['name','asc']">Nazwa rosnąco</option>
                            <option :value="['name','desc']">Nazwa malejąco</option>
                            <option :value="['price','asc']">Cena rosnąco</option>
                            <option :value="['price','desc']">Cena malejąco</option>
                        </select>
                    </div>
                    

                    <div @click="showCategoriesOverlay = true" class="cursor-pointer lg:hidden rounded-full w-12 h-12 bg-white border-2 border-gray-700 p-1 flex items-center justify-center">
                        <i class="fas fa-filter fa-lg"></i>
                    </div>
                    <button @click="showCategoriesOverlay = true" class="hidden lg:block rounded-full bg-white border-2 border-gray-700 px-2 py-2 font-semibold">Kategorie</button>
                </div>
            </div>        

            <div class="flex flex-wrap -mx-1 lg:-mx-4 sm:mt-5 mt-3">
                <div v-for="row in items.data" :key="row" class=" my-1 px-1 w-full sm:w-1/2 lg:my-4 lg:px-4 lg:w-1/3">

                    <div class="overflow-hidden rounded-lg bg-white">
                        <img :alt=row.name class="block h-64 w-full object-fill" :src=row.photo_path>

                        <header class="leading-tight p-2 md:p-4">
                            <h1 class="text-lg capitalize">
                                {{ row.name }}
                            </h1>
                            <p class="text-gray-700 text-sm">
                                {{ row.category_name }}
                            </p>
                            <p class="mt-4 text-lg">
                                {{row.price}} zł
                            </p>
                        </header>
                    </div>
                </div>
            </div>
            <div class="sm:flex sm:flex-row-reverse justify-between py-3 rounded-lg bg-white text-center space-y-4 sm:space-y-0 px-3 items-center">
                <pagination :links=items.links></pagination>
                <span>Wyniki od {{items.from}} do {{items.to}}. Łącznie {{items.total}} wyników.</span>
            </div>
        </div>
        
    </app-layout>

    
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'  
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { Link } from '@inertiajs/inertia-vue3'

export default defineComponent({
    props: {
        items: Object,
        filters: Object,
        categories: Object
    },

    setup(props) {
        const filter = props.filters.filter ? ref(props.filters.filter) : ref(0)
        const sortField = ref(['name','asc'])
        const showCategoriesOverlay = ref(false)

        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction,
            filter: props.filters.filter
        })

        function sort(field) {
            params.field = field[0]
            params.direction = field[1]
        }
        
        const filterServices = (option) => {
            params.filter = option
        }

        return { params, sort, filter, filterServices, sortField, showCategoriesOverlay }
    },

    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route('store'), params, { replace: true, preserveState: true });
            }, 150),
            deep: true
        },
    },

    components: {
        AppLayout,
        Pagination
    },
})
</script>

<style>
.main {
    min-height: 75vh
}
</style>
