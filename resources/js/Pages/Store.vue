<template>

    <div v-if=showCategoriesOverlay>
        <div @click="showCategoriesOverlay = false" class="w-screen fixed h-full p-4 bg-black bg-opacity-50"></div>
        <div class="w-screen fixed h-screen p-4 bg-white mt-36 ">
            <div class="flex justify-between items-center">
                <h1 class="text-lg font-bold">Kategorie</h1>
                <i @click="showCategoriesOverlay = false" class="far fa-times-circle fa-lg"></i>
            </div>
            <div>
                <template v-for="row in categories" :key=row>
                    <div v-if="!row.parent_category_id">
                        <div @click="filterServices(row.id)">{{ row.name }}</div>
                        <div v-if="row.subcategories.length" class="text-red-600">
                            {{row.subcategories}}
                            <!-- <div v-for="subrow in row.subcategories" :key=subrow>
                                <div @click="filterServices(subrow.id)">{{ subrow.name }}</div>
                            </div> -->
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <app-layout title="Sklep">
        
        <div class="main mx-auto max-w-6xl py-6 px-2 lg:pt-16">
            <div class="sm:flex justify-between flex-row-reverse">
                <div class="form-control">
                <div class="relative">
                    <input v-model="params.search" @click="params.searchField='name'" placeholder="Szukaj" class="w-full pr-16 input input-primary input-bordered" type="text"> 
                    <button class="absolute top-0 right-0 rounded-l-none btn btn-primary hover:bg-primary cursor-default"><i class="fas fa-lg fa-search"></i></button>
                </div>
                </div> 
            
                <div class="flex justify-between space-x-2 items-center mt-4 sm:mt-0">
                    <div class="dropdown">
                        <div @click="showDropdown" tabindex="0" class="btn">{{ 'Sortuj: ' + sortLabel}}<span id="sort-icon"><i class="ml-1 fas fa-sort-amount-up"></i></span></div> 
                            <ul id="sort-ul" tabindex="0" class="p-2 shadow menu dropdown-content bg-base-100 rounded-box w-52">
                                <li @click="sort(['name', 'asc','nazwa'])">
                                    <a>Nazwa rosnąco</a>
                                </li> 
                                <li @click="sort(['name', 'desc', 'nazwa'])">
                                    <a>Nazwa malejąco</a>
                                </li> 
                                <li @click="sort(['price', 'asc', 'cena'])">
                                    <a>Cena rosnąco</a>
                                </li>
                                <li @click="sort(['price', 'desc', 'cena'])">
                                    <a>Cena malejąco</a>
                                </li>
                            </ul>
                        </div>

                    <button @click="showCategoriesOverlay = true" class="btn btn-circle lg:hidden">
                        <i class="fas fa-filter fa-lg"></i>
                    </button>
                    <button @click="showCategoriesOverlay = true" class="hidden lg:block btn">Kategorie</button>
                </div>
            </div>        

            <div class="flex flex-wrap -mx-1 lg:-mx-4 sm:mt-5 mt-3">
                <div v-for="row in items.data" :key="row" class="my-1 px-1 w-full sm:w-1/2 lg:my-4 lg:px-4 lg:w-1/3">

                    <div class="card shadow-lg bordered">
                        <figure>
                            <img :alt=row.name  :src=row.photo_path>
                        </figure> 
                        <div class="card-body">
                            <h1 class="card-title capitalize">
                                <div>{{ row.name }}</div>
                                <div class="text-gray-500 text-sm">{{ row.category_name }}</div>
                            </h1> 
                            
                            <div class="card-actions justify-between items-center">
                                <p class="text-lg">
                                    {{ row.price }} zł
                                </p>
                                <button @click="itemDetails(row.id)" class="btn btn-primary">Zobacz</button>
                            </div>
                        </div>
                    </div>

                    <!-- <img :alt=row.name class="block h-64 w-full object-fill" :src=row.photo_path>  -->
                </div>
            </div>
            
            <div class="flex-col flex sm:flex-row-reverse justify-between py-3 rounded-lg bg-white text-center space-y-4 shadow-lg sm:space-y-0 px-3 items-center">
                <pagination :links=items.links class="mt-4 sm:mt-0"></pagination>
                <span>Pozycje od {{items.from}} do {{items.to}} z {{items.total}} pozycji.</span>
            </div>
        </div>
        
    </app-layout>

    
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import AppLayout from '@/Layouts/AppLayout.vue'  
import Pagination from "@/Components/Pagination.vue";
import { pickBy, throttle } from 'lodash';
import { Inertia } from '@inertiajs/inertia';

export default defineComponent({
    props: {
        items: Object,
        filters: Object,
        categories: Object
    },

    setup(props) {
        const filter = props.filters.filter ? ref(props.filters.filter) : ref(0)
        const sortLabel = ref('Nazwa')
        const showCategoriesOverlay = ref(false)

        const params = reactive({
            search: props.filters.search,
            field: props.filters.field,
            direction: props.filters.direction,
            filter: props.filters.filter
        })

        function sort(field) {
            document.getElementById('sort-ul').style.visibility = 'hidden'
            params.field = field[0]
            params.direction = field[1]
            sortLabel.value = field[2]
            document.getElementById('sort-icon').innerHTML = field[1] === 'asc' ? '<i class="ml-1 fas fa-sort-amount-up"></i>' : '<i class="ml-1 fas fa-sort-amount-down"></i>'

        }
        
        const showDropdown = _ => document.getElementById('sort-ul').style.visibility = 'visible'

        const filterServices = (option) => {
            params.filter = option
            showCategoriesOverlay.value = false
        }

        const itemDetails = (id) => Inertia.get('/storeItem/'+id)

        return { params, sort, filter, filterServices, sortLabel, showCategoriesOverlay, itemDetails, showDropdown }
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
