<template>
<admin-panel-layout title="Kategorie w sklepie">
	
	<!-- Data not present -->
	<template v-if="!categories.data.length && filters.search == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnej kategorii</h1>
		<Link :href="route('admin.store_items.index')" class="btn btn-wide btn-secondary mt-4">
			Powrót
		</Link>
		<button @click="createModalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj kategorię
		</button>
	</template>

	<!-- Data present -->
	<template v-else>
		<DataTable :columns=columns :data=categories :filters=filters sortRoute="admin.store_categories.index">
			<template #buttons>
				<div class="flex space-x-2">
					<Link :href="route('admin.store_items.index')" class="btn btn-secondary sm:btn-sm">
						<i class="fas fa-arrow-left mr-2"></i>
						Powrót
					</Link>
					<button @click="createModalOpened = true" class="btn btn-primary sm:btn-sm">
						<i class="fas fa-plus mr-2"></i>
						Dodaj kategorię
					</button>
				</div>
			</template>

			<template #content>
				<template v-for="row in categories.data" :key="row">
					<tr v-if="row.category.length == false" class="hover">
						<td class="font-bold">{{ row.id }}</td>
						<td>{{ row.name }}</td>
						<td class="space-x-2 text-center"> 
							<button @click=showDetails(row) class="btn btn-xs btn-accent">Szczegóły</button>
							<button @click="deleteRow(row.id, false)" class="btn btn-xs btn-error">
								<i class="fas fa-trash cursor-pointer"></i>
								<span class="ml-1">Usuń</span>
							</button>
						</td>
					 </tr>
				</template>
			</template>
		</DataTable>

		<!-- Modal - details -->
		<Modal :show=detailsModalOpened @close=close :id="'modal-2'">

			<template #side>
				<div class="flex space-x-2">
					<button @click="deleteRow(selectedCategory.id, false)" class="btn btn-error btn-xs">
						<i class="fas fa-trash"></i>
						<span class="ml-2">Usuń</span>
					</button>
					<button @click="editCategory(selectedCategory)" :class="{'btn-info':!editCategoryMode, 'btn-error':editCategoryMode}" class="btn btn-xs">
						<i :class="{'fas fa-edit':!editCategoryMode, 'fas fa-times':editCategoryMode}"></i>
						<span v-html="editCategoryMode ? 'Anuluj' : 'Edytuj'" class="ml-2"></span>
					</button>
					<button v-if="editCategoryMode" @click=update(selectedCategory) :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-success">Zapisz</button>
				</div>
			</template>

			<template #content>
				<!-- Form and content -->
				<form>
					<div class="flex items-start space-x-2 my-3">
						<input type="file" id="upload-file-update" @change="previewImage" ref="photo" accept="image/*" @input="form.image = $event.target.files[0]" class="hidden" />
						<div v-if="url && form.image" class="mx-auto indicator">
							<div class="indicator-item indicator-start">
								<button v-if="url && form.image" @click="form.image=null" class="btn btn-xs btn-ghost"><i class="fas fa-times text-error"></i></button>
							</div> 
							<img :src="url" class="block h-24 w-24 object-cover mask mask-squircle" />
						</div>
						<img v-else :src="selectedCategory.photo_path" :alt="selectedCategory.name" id="category-image" class="block h-24 w-24 object-cover mask mask-squircle">
						<div class="flex flex-col">
							<div class="flex space-x-2 items-center">
								<div class="font-bold text-lg">{{ `${selectedCategory.id}.` }}</div>
								<div v-if=!editCategoryMode class="font-semibold text-lg">{{ `${selectedCategory.name}` }}</div>
								<span v-else class="text-lg font-bold">
									<input v-model="form.name" type="text" class="input input-primary input-sm" required/>
									<label v-if="form.hasErrors && form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>
								</span>
							</div>
							<div v-if="editCategoryMode" class="flex flex-col space-y-2 mt-2 ml-6">
								<label for="upload-file-update" refs="upload-file" class="btn btn-xs btn-primary">Zmień zdjęcie</label>
								<label v-if="selectedCategory.photo_path != '/images/default.png'" @click=removeImage class="btn btn-xs btn-error">Usuń zdjęcie</label>
								<label v-if="form.hasErrors && form.errors.image" class="label label-text-alt text-error text-sm">{{ form.errors.image }}</label>
							</div>
						</div>
					</div>
				</form>
					
				<!-- Subcategories -->
				<div class="flex items-center space-x-2">
					<h1 class="text-lg font-semibold">Podkategorie</h1>
					<button @click="createSubcategory" :class="{'btn-primary':!createSubcategoryMode, 'btn-error':createSubcategoryMode}" class="btn btn-xs">
						<i :class="{'fas fa-plus':!createSubcategoryMode, 'fas fa-times':createSubcategoryMode}"></i>
						<span v-html="createSubcategoryMode ? 'Anuluj' : 'Dodaj'" class="ml-2"></span>
					</button>
					<button @click=store(true) v-if="createSubcategoryMode" :disabled="form.processing" :class="{ 'loading': form.processing }"  class="btn btn-xs btn-success">Zapisz</button>
				</div>

				<form v-show="createSubcategoryMode" class="mt-4" id="form-subcategory">
					<input v-model="form.name" id="form-subcategory-name" type="text" class="input input-primary input-sm" placeholder="Nazwa" required />
					<label v-if="form.hasErrors && form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>
				</form>

				<div v-if="selectedCategory.subcategories != null" class="overflow-y-auto max-h-64">
				<ul class="menu">
					<li v-for="row in selectedCategory.subcategories" :key="row.id" class="hover-bordered"> 
						<a class="flex justify-between">
							<template v-if="editSubcategoryMode && subcategoryIndex == row.id">
								<form>
									<input v-model="form.name" type="text" class="input input-primary input-sm" placeholder="Nazwa" required />
									<label v-if="form.hasErrors && form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>
								</form>
								<div class="flex space-x-2 items-center">
									<button @click="update(row)" :disabled="form.processing" :class="{ 'loading': form.processing }"  class="btn btn-xs btn-success">Zapisz</button>
									<i @click="editSubcategory(row, false)" class="fas fa-times text-error"></i>
								</div>
							</template>
							<template v-else>
								<div>{{ `${row.name}` }}</div>
								<div class="flex space-x-2 items-center">
									<i @click="editSubcategory(row, true)" class="fas fa-edit text-info"></i>
									<i @click="deleteRow(row.id, true)" class="fas fa-trash text-error"></i>
								</div>
							</template>
						</a>
					</li>
				</ul>
				</div>
				<h1 v-else class="ml-2">Kategoria nie ma podkategorii</h1>
			</template>
		</Modal>
	</template>

	<!-- Create category modal -->
	<Modal :show=createModalOpened @close=close :id="'modal-1'" :maxWidth="'max-w-sm'">
		<template #side>
			<h1 class="text-lg font-semibold">Nowa kategoria</h1>
		</template>

		<template #content>
			<form>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.name type="text" placeholder="Nazwa" class="input input-primary input-bordered" required />
					<label v-if="form.hasErrors && form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>
				</div> 

				<div class="form-control mt-4 space-y-3">
					<input type="file" id="upload-file-store" @change="previewImage" ref="photo" accept="image/*" @input="form.image = $event.target.files[0]" class="hidden" />
					<div v-if="url && form.image" class="mx-auto indicator">
						<div class="indicator-item">
							<button v-if="url && form.image" @click="form.image=null" class="btn btn-xs btn-ghost"><i class="fas fa-times text-error"></i></button>
						</div> 
						<img :src="url" class="block h-24 w-24 object-cover mask mask-squircle" />
					</div>
					<label for="upload-file-store" refs="upload-file" class="btn btn-primary">Wybierz zdjęcie</label>
					<label v-if="form.hasErrors && form.errors.image" class="label label-text-alt text-error text-sm">{{ form.errors.image }}</label>
				</div>
			</form>
		</template>

		<template #footer>
			<button @click=store(false) :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-info w-full">Dodaj</button>
		</template>
	</Modal>

</admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import DataTable from '@/Components/DataTable.vue'
import Modal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		categories: Object,
		filters: Object
	},

	setup(props) {
		// Show details of this category
		const selectedCategory = ref({'photo_path':''})

		// Image upload
		const url = ref(null)

		// Modals visibility
		const createModalOpened = ref(false)
		const detailsModalOpened = ref(false)

		// Details modal modes
		const editCategoryMode = ref(false)
		const editSubcategoryMode = ref(false)
		const createSubcategoryMode = ref(false)
		// Selected subcategory to edit
		const subcategoryIndex = ref(0)

		// Data form
		const form = useForm({
			name: null,
			image: null,
            store_category_id: null
		})

		// Show category details
		const showDetails = (category) => {
			selectedCategory.value = category
			detailsModalOpened.value = true
		}

		// Reset modal modes
		const resetModes = (ec, es, cs) => {
			if (ec) editCategoryMode.value = false
			if (es) editSubcategoryMode.value = false
			if (cs) createSubcategoryMode.value = false

			reset()
		}

		// Reset form
		const reset = _ => {
			form.reset()
			form.clearErrors()
			url.value = null
		}

		// Close modals and reset data and modes
		const close = _ => { 
			createModalOpened.value = false
			detailsModalOpened.value = false
			resetModes(true, true, true)
		}

		// Subcategory create
		const createSubcategory = _ => {
			resetModes(true, true, false)
			createSubcategoryMode.value = !createSubcategoryMode.value
			form.store_category_id = selectedCategory.value.id

			// focus
		}

		// Subcategory edit
		const editSubcategory = (subcategory, mode) => {
			resetModes(true, false, true)

			subcategoryIndex.value = subcategory.id
			editSubcategoryMode.value = mode

			form.name = subcategory.name
			form.store_category_id = selectedCategory.value.id

			// focus
		}

		// Category edit
		const editCategory = (category) => { 
			resetModes(false, true, true)
			editCategoryMode.value = ! editCategoryMode.value
			form.name = category.name
			document.getElementById('category-image').src = category.photo_path

			// focus
		}

		// CRUD actions for both category and subcategory
        const store = (is_subcategory) => { 
			form.post(route('admin.store_categories.store'), {
				onSuccess: () => {
					reset()
					if (is_subcategory) {
						form.store_category_id = selectedCategory.value.id
						selectedCategory.value = props.categories.data.find(element => element.id == selectedCategory.value.id)
					}
				}
			}) 
		}

		const update = (category) => { 
			form.put(route('admin.store_categories.update', category.id), {
				onSuccess: () => {
					resetModes(true, true, true)
					selectedCategory.value = props.categories.data.find(element => element.id == selectedCategory.value.id)
				} 
			}) 
		}

        const deleteRow = (id, is_subcategory) => {
            if (!confirm('Na pewno? Wszystkie przedmioty należące do kategorii również zostaną usunięte!')) return;
			resetModes(true, true, true)
            Inertia.delete(route('admin.store_categories.destroy', id), {
				onSuccess: () => {
					if (is_subcategory)
						selectedCategory.value = props.categories.data.find(element => element.id == selectedCategory.value.id)
					else close()
					if (props.categories.data.length === 0) selectedCategory.value = ref({'photo_path':''})
				}
			})
        }

		// Images
		const previewImage = (e) => url.value = URL.createObjectURL(e.target.files[0])

		const removeImage = _ => {
			form.image = null
			document.getElementById('category-image').src = "/images/default.png"
		}

		// Columns for data table
		const columns = [
			{ name:'id', label:'ID', sortable: true },
			{ name:'name', label:'Kategoria', sortable: true },
			{ name:'actions', label:'' }
        ]

		// Returned data
		return { 
			selectedCategory,
			createModalOpened, 
			detailsModalOpened,
			editCategoryMode,
			editSubcategoryMode,
			createSubcategoryMode,
			subcategoryIndex,
			form,
			showDetails,
			close,
			createSubcategory,
			editSubcategory,
			editCategory,
			store,
			update,
			deleteRow ,
			columns,
			url,
			previewImage,
			removeImage
		}
	},

	components: {
		AdminPanelLayout,
		Link,
		DataTable,
		Modal,
	}
});
</script>