<template>
<admin-panel-layout title="Zdjęcia">
	
	<!-- Data not present -->
	<template v-if="(!photos.data.length || !categories.length) && filters.filter == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego zdjęcia</h1>
		<Link :href="route('admin.photo_categories.index')" class="btn btn-wide btn-secondary mt-4">
			Kategorie
		</Link>
		<button v-if="categories.length" @click="createModalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj zdjęcie
		</button>
	</template>

	<!-- Data present -->
    <template v-else>
		<div class="flex flex-col space-y-2 w-full md:flex-row md:space-y-0 md:space-x-2">
			<Link :href="route('admin.photo_categories.index')" class="btn btn-sm btn-primary w-full md:w-auto">
				Kategorie
			</Link>
			<button @click="createModalOpened = true" class="btn btn-sm btn-secondary mt-4">
				<i class="fas fa-plus fa-lg mr-3"></i>
				Dodaj zdjęcie
			</button>
			<div class="dropdown w-full">
				<div tabindex="0" class="btn btn-sm w-full md:w-auto">
					<span>{{ `${currentFilter}` }}</span>
				</div> 
				<div id="filter-list" tabindex="0" class="w-full md:w-auto p-2 shadow menu dropdown-content bg-base-100 rounded-box">
					<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
						<template v-for="row in categories" :key="row.id">
							<ul v-if="row.category == null && row.subcategories.length" class="menu">
								<li class="menu-title"><span>{{ row.name }}</span></li>
								<li v-for="sub in row.subcategories" :key="sub.id" @click="filterServices(sub)">
									<a>{{ sub.name }}</a>
								</li>
							</ul>
						</template>
					</div>
				</div>
			</div>
		</div>
        
		<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 overflow-y-auto no-scrollbar">
			<div @click="magnify(row)" v-for="row in photos.data" :key="row.id" class="rounded-lg grid items-center group hover:opacity-80 hover:cursor-pointer transition ease-in-out">
				<div class="relative rounded-lg">
					<div @click.stop="deletePhoto(row)" class="btn btn-error btn-xs hidden group-hover:inline-flex transition duration-500 ease-in-out absolute right-1 top-1">
						<i class="fas fa-times text-white"></i>
					</div>
					<img :src="row.path" :alt="row.id" class="rounded-lg h-64 w-full object-cover" />
				</div>
			</div>
		</div>

		<Pagination :links="photos.links" class="md:self-start"></Pagination>

		<!-- Magnified photo modal -->
		<input type="checkbox" id="modal" class="modal-toggle"> 
		<div class="modal">
			<div class="modal-box max-w-5xl overflow-y-auto flex justify-center" style="max-height: 100vh;">
				<div>
					<img id="modal-img" alt="magnified" class="rounded-lg">
					<div class="modal-action">
						<label for="modal" class="btn btn-xs btn-sm">Zamknij</label>
					</div>
				</div>
				
			</div>
		</div>
    </template>
	
	<!-- Add photo form -->
	<Modal @close=close :show="createModalOpened" id="modal-crud" maxWidth="max-w-sm">
		<template #side>
			<h1 class="text-lg font-semibold">Dodaj zdjęcie</h1>
		</template>

		<template #content>
			<form>
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
			</form>
		</template>

		<template #footer>
			<button @click=store :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full">Dodaj</button>
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
        // Filter label
        const currentFilter = ref("kategoria")

		// Create modal visibility
		const createModalOpened = ref(false)
		
		// Image upload
		const url = reactive([])

        // Filter and sort parameters
        const params = reactive({
            filter: props.filters.filter
        })

		// Data form
		const form = useForm({
			images: [],
            photo_category_id: props.subcategories.length ? props.subcategories[0].id : 0
		})

		// Filter function
        const filterServices = (option) => {
            params.filter = option.id
            currentFilter.value = option.name
        }

		// Delete photo
        const deletePhoto = (row) => {
            if (!confirm('Na pewno chcesz usunąć zdjęcie?')) return;
            Inertia.delete(route('admin.photos.destroy', row.id))
		}

		// Magnify photo
		const magnify = (row) => {
			document.getElementById('modal').checked = true
			document.getElementById('modal-img').src = row.path
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
        // Returned data
        return { params, store, close,previewImage, filterServices, deletePhoto, form, currentFilter, url, magnify, createModalOpened }
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