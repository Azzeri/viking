<template>
    <app-layout title="Galeria">
        <div class="flex-col hero-content place-self-start mx-auto w-full">
            
            <!-- Data not present -->
            <template v-if="!photos || !categories">
                <h1 class="text-lg font-semibold">Nie dodano jeszcze żadnych zdjęć</h1>
            </template>

            <!-- Data present -->
            <template v-else>
                <!-- Categories dropdown -->
                <div class="dropdown w-full">
                    <div @click="showDropdown('drop-filter')" tabindex="0" class="m-1 btn">{{ currentFilter }}</div> 
                    <div id="drop-filter" tabindex="0" class="flex flex-wrap gap-2 shadow dropdown-content bg-base-100 rounded-box p-2 overflow-y-auto" style="max-height:80vh;">
                         <template v-for="row in categories" :key="row.id">
							<ul v-if="row.subcategories.length" class="menu">
								<li class="menu-title">
                                    <span><img :src="row.photo_path" class="w-6 h-6 mr-1 rounded-full" />{{ row.name }}</span>
                                </li>
								<li v-for="sub in row.subcategories" :key="sub.id" @click="filterServices(sub)">
									<a>{{ sub.name }}</a>
								</li>
							</ul>
						</template>
                    </div>
                </div>

                <!-- Category hasn't photos -->
                <template v-if="!photos.length">
                    Brak zdjęć w tym folderze
                </template>

                <!-- Category has photos -->
                <template v-else>
                    <input type="checkbox" id="modal-carousel" class="modal-toggle"> 
                    <label for="modal-carousel" class="modal mt-16">
                        <div @click.prevent class="modal-box max-w-5xl self-center p-0 overflow-y-auto" style="max-height: 90vh">
                            <div class="w-full carousel">
                                <div id="slide1" class="relative w-full carousel-item">
                                    <img v-if="selectedPhoto" :src="selectedPhoto.path" class="w-full"> 
                                    <div class="absolute right-0 md:right-3 md:top-3">
                                        <label @click.stop for="modal-carousel" class="btn btn-ghost btn-square">
                                            <i class="fas fa-times fa-lg"></i>
                                        </label>
                                    </div>
                                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                        <button @click="switchPhoto(false)" class="btn btn-circle">❮</button> 
                                        <button @click="switchPhoto(true)" class="btn btn-circle">❯</button>
                                    </div>
                                </div> 
                            </div>
                        </div>
			        </label>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 overflow-y-auto no-scrollbar">
                        <div @click="openCarousel(row)" v-for="row in photos" :key="row.id" class="rounded-lg grid items-center group hover:opacity-80 hover:cursor-pointer transition ease-in-out">
                            <img :src="row.path" :alt="row.id" class="rounded-lg h-64 w-full object-cover" />
                        </div>
                    </div>
                </template>

            </template>

        </div>
    </app-layout>
</template>

<script>
import { defineComponent, ref, reactive } from 'vue'
import { pickBy, throttle } from 'lodash';
import AppLayout from '@/Layouts/AppLayout.vue'  

export default defineComponent({
    props: {
        photos:Object,
        categories:Object,
        subcategories: Object,
        filters: Object
    },

    setup(props) {
        // Magnified photo
        const selectedPhoto = ref(props.photos[0]) ?? ref(null)

        // Filter and sort parameters
        const params = reactive({
            filter: props.filters.filter
        })
        // Filter label
        const currentFilter = params.filter ? ref(props.subcategories.filter((item) => item.id == params.filter)[0].name) : ref('wszystkie')

        // Filter function
        const filterServices = (option) => {
            params.filter = option.id
            currentFilter.value = option.name
            document.getElementById('drop-filter').style.visibility = "hidden"
        }

        // Open carousel and magnify photo
        const openCarousel = (photo) => {
            selectedPhoto.value = photo
            document.getElementById('modal-carousel').checked = true
        }

        // Check if "next" is true and switch to the next or prev photo
        const switchPhoto = (next) => {
            const nextIndex = props.photos.indexOf(selectedPhoto.value) + (next ? 1 : -1)
            const nextPhotoIndex =  props.photos[nextIndex] ? nextIndex : next ? 0 : -1
            selectedPhoto.value = props.photos.at(nextPhotoIndex)
        }

        const showDropdown = (id) =>
			(document.getElementById(id).style.visibility = "visible");
			
		const hideDropdown = (id) =>
			(document.getElementById(id).style.visibility = "hidden");

        // Returned data
        return {
            currentFilter,
            params,
            selectedPhoto,
            filterServices,
            switchPhoto,
            openCarousel,
            showDropdown,
            hideDropdown
        }
      
    },

    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route('gallery.index'), params, { replace: true, preserveState: true });
            }, 150),
            deep: true
        },
    },

    components: {
        AppLayout,
    },
})
</script>
