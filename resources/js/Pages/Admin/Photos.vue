<template>
<admin-panel-layout title="Zdjęcia">
	
	<!-- Data not present -->
	<div v-if="(!photos.data.length || !categories.length) && filters.filter == null" class="flex flex-col items-center justify-center">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego zdjęcia</h1>
		<Link :href="route('admin.photo_categories.index')" class="btn btn-wide btn-secondary mt-4">
			Kategorie
		</Link>
		<button v-if="categories.length" @click="createModalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj zdjęcie
		</button>
	</div>

	<!-- Data present -->
    <template v-else>
		<div class="flex flex-col space-y-2 w-full md:flex-row md:space-y-0 md:space-x-2 my-2">
			<Link :href="route('admin.photo_categories.index')" class="btn btn-sm btn-secondary w-full md:w-auto">
				Kategorie
			</Link>
			<button @click="createModalOpened = true" class="btn btn-sm btn-primary mt-4">
				<i class="fas fa-plus fa-lg mr-3"></i>
				Dodaj zdjęcie
			</button>
			<div class="dropdown w-full">
				<div @click="showDropdown('drop-filter')" tabindex="0" class="btn btn-sm">{{ currentFilterLabel }}</div> 
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
		</div>
        
		<!-- Category hasn't photos -->
		<template v-if="!photos.data.length">
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
				<div @click="openCarousel(row)" v-for="row in photos.data" :key="row.id" class="rounded-lg grid items-center group hover:opacity-80 hover:cursor-pointer transition ease-in-out">
					<div class="relative rounded-lg">
						<div @click.stop="deletePhoto(row)" class="btn btn-error btn-xs hidden group-hover:inline-flex transition duration-500 ease-in-out absolute right-1 top-1">
							<i class="fas fa-times text-white"></i>
						</div>
						<img :src="row.path" :alt="row.id" class="rounded-lg h-64 w-full object-cover" />
					</div>
				</div>
			</div>
		</template>

		<Pagination :links="photos.links" class="mt-2"></Pagination>
    </template>
	
	<!-- Add photo form -->
	<Modal @close=close :show="createModalOpened" id="modal-crud" maxWidth="max-w-sm">
		<template #side>
			<h1 class="text-lg font-semibold">Dodaj zdjęcie</h1>
		</template>

		<template #content>
			<form @submit.prevent=store>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Kategoria<span class="ml-1 text-red-500">*</span></span></label> 
					<select v-model=form.photo_category_id class="select select-bordered select-primary w-full">
						<template v-for="row in subcategories" :key=row.id :value=row.id>
							<option :value="row.id">{{ row.name }}</option>
						</template>
					</select>

					<input type="file" id="upload-file-store" multiple @change="previewImage" ref="photo" accept="image/*" @input="form.images = $event.target.files" class="hidden" />
					<div class="flex flex-wrap overflow-y-auto overflow-x-hidden" :class="{ 'h-44': form.images.length }">
						<template v-for="(u, index) in url" :key=index>
							<div v-if="url && form.images.length" class="mx-auto mt-6">
								<img :src="u" class="block h-24 w-24 object-cover mask mask-squircle" />
							</div>
						</template>
					</div>
					<label v-if="url && form.images.length" @click="form.images = []" class="btn btn-error btn-sm mt-4">Wyczyść</label>
					<label for="upload-file-store" refs="upload-file" class="btn btn-primary mt-4">Wybierz zdjęcia</label>
					<label v-if="form.errors.images" class="label label-text-alt text-error text-sm">{{ form.errors.images }}</label>
				</div> 
				<input type="submit" ref="createPhotoSubmit" class="hidden" />
			</form>
		</template>

		<template #footer>
			<button @click="$refs.createPhotoSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full">Dodaj</button>
		</template>
	</Modal>

</admin-panel-layout>
</template>

<script>
import { defineComponent, ref, reactive } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import { pickBy, throttle } from 'lodash';
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import DataTable from '@/Components/DataTable.vue'
import Modal from '@/Components/CrudModal.vue'
import Pagination from "@/Components/Pagination.vue";

export default defineComponent({

	props: {
		photos: Object,
        categories: Object,
		subcategories: Object,
		filters: Object
	},

	setup(props) {
		// Filter and sort parameters
        const params = reactive({
            filter: props.filters.filter
        })
		
		const currentFilterLabel = params.filter ? ref(props.subcategories.filter((item) => item.id == params.filter)[0].name) : ref(props.subcategories[0].name)

		const selectedPhoto = ref(props.photos.data[0]) ?? ref(null)

		// Create modal visibility
		const createModalOpened = ref(false)
		
		// Image upload
		const url = reactive([])

		// Data form
		const form = useForm({
			images: [],
            photo_category_id: props.subcategories.length ? props.subcategories[0].id : 0
		})

		// Filter function
        const filterServices = (option) => {
            params.filter = option.id
            currentFilterLabel.value = option.name
			hideDropdown("drop-filter")
        }

		// Delete photo
        const deletePhoto = (row) => {
            if (!confirm('Na pewno chcesz usunąć zdjęcie?')) return;
            Inertia.delete(route('admin.photos.destroy', row.id))
		}

		// Close and reset forms and modals
		const reset = _ => {
			form.reset()
			form.clearErrors()
		}

		const close = _ => {
			createModalOpened.value = false
			reset()
		}

		// Images preview
		const previewImage = (e) => {
			for (let x of e.target.files) url.push(URL.createObjectURL(x))
		}

		// Store image
		const store = _ => {
			form.post(route('admin.photos.store'), {
				onSuccess: () => reset()
			})
		}

		 // Open carousel and magnify photo
        const openCarousel = (photo) => {
            selectedPhoto.value = photo
            document.getElementById('modal-carousel').checked = true
        }

        // Check if "next" is true and switch to the next or prev photo
        const switchPhoto = (next) => {
            const nextIndex = props.photos.data.indexOf(selectedPhoto.value) + (next ? 1 : -1)
            const nextPhotoIndex =  props.photos.data[nextIndex] ? nextIndex : next ? 0 : -1
            selectedPhoto.value = props.photos.data.at(nextPhotoIndex)
        }

		const showDropdown = (id) =>
			(document.getElementById(id).style.visibility = "visible");
			
		const hideDropdown = (id) =>
			(document.getElementById(id).style.visibility = "hidden");

        // Returned data
        return { params, store, close,previewImage, filterServices, deletePhoto, form, currentFilterLabel, url, createModalOpened, selectedPhoto, openCarousel, switchPhoto, showDropdown, hideDropdown }
    },

    watch: {
        params: {
            handler: throttle(function () {
                let params = pickBy(this.params);
                this.$inertia.get(this.route('admin.photos.index'), params, { replace: true, preserveState: true });
            }, 150),
            deep: true
        },
    },

    components: {
		AdminPanelLayout,
		Link,
		DataTable,
		Modal,
		Pagination
	}
});
</script>