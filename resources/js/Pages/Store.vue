<template>
<app-layout title="Sklep">
    <div class="flex-col hero-content place-self-start mx-auto w-full">
        
        <!-- Data not present -->
        <template v-if="(!items.data || !categories) && filters.search == null">
            <h1 class="text-lg font-semibold">Nie dodano jeszcze żadnych przedmiotów</h1>
        </template>

        <!-- Data present -->
        <template v-else>
            <!-- Categories dropdown -->
            <div class="dropdown w-full">
                <div @click="showDropdown('filter-ul')" tabindex="1" class="btn w-full md:w-auto">Kategorie</div> 
                <div id="filter-ul" tabindex="1" class="flex flex-wrap gap-2 shadow dropdown-content bg-base-100 rounded-box p-2 overflow-y-auto" style="max-height:80vh;">
                        <template v-for="row in categories" :key="row.id">
                            <ul v-if="row.subcategories.length" class="menu">
                                <li class="menu-title"><span>{{ row.name }}</span></li>
                                <li v-for="sub in row.subcategories" :key="sub.id" @click="filterItems(sub)">
                                    <a>{{ sub.name }}</a>
                                </li>
                            </ul>
                    </template>
                </div>
            </div>
            <div class="flex flex-col md:flex-row gap-2 w-full">
                <!-- Sort -->
                <div class="dropdown">
                    <div @click="showDropdown('sort-ul')" tabindex="0" class="btn w-full md:w-auto">{{ 'Sortuj: ' + sortLabel}}<span id="sort-icon"><i class="ml-1 fas fa-sort-amount-up"></i></span></div> 
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
                <div class="relative">
                    <input v-model="params.search" @click="params.searchField='name'" placeholder="Szukaj" class="w-full md:w-auto pr-16 input input-primary input-bordered" type="text"> 
                    <button class="absolute top-0 right-0 rounded-l-none btn btn-primary hover:bg-primary cursor-default"><i class="fas fa-lg fa-search"></i></button>
                </div>
            </div>
            

             <!-- Category hasn't items -->
            <template v-if="!items.data.length && filters.search == null">
                Brak produktów w tej kategorii
            </template>

            <!-- Category has items -->
            <template v-else>
                <div class="flex flex-wrap gap-4 mt-16 justify-center">
                    <article v-for="row in items.data" :key="row.id" class="card shadow-lg bordered rounded-lg">
                        <figure>
                            <img :src=row.photo_path class="h-64 object-cover">
                        </figure> 
                        <div class="card-body justify-between">
                            <div class="card-title">
                                <span>{{ row.name }}</span>
                                <h2 class="text-gray-500 text-base">
                                    <div>{{ `${row.category.name}` }}</div> 
                                    <div class="text-2xl text-base-content mt-2">{{ `${row.price}zł` }}</div>
                                </h2>
                            </div>
                            <p>{{ row.description }}</p>
                            <div class="card-actions">
                                <Link as="button" :href="route('store.show', row.id)" class="btn btn-primary w-full md:w-auto">Więcej</Link>
                            </div>
                        </div>
                    </article>
                </div>
                <Pagination :links=items.links :lg="true" class=""></Pagination>
            </template>

        </template>

    </div>
    
</app-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { pickBy, throttle } from 'lodash';
import { Link } from '@inertiajs/inertia-vue3'
import AppLayout from '@/Layouts/AppLayout.vue'  
import Pagination from "@/Components/Pagination.vue";

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
        
        const showDropdown = (id) => document.getElementById(id).style.visibility = 'visible'

        const filterItems = (option) => {
            document.getElementById('filter-ul').style.visibility = 'hidden'
            params.filter = option.id
        }

        return { params, sort, filter, filterItems, sortLabel, showCategoriesOverlay, showDropdown }
    },

    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route('store.index'), params, { replace: true, preserveState: true });
            }, 150),
            deep: true
        },
    },

    components: {
        AppLayout,
        Pagination,
        Link
    },
})
</script>
