<template>

<admin-panel-layout title="Sklep">
	
	<!-- Data not present -->
	<template v-if="(!items.data.length || !categories.length) && filters.search == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego przedmiotu</h1>
		<Link :href="route('admin.store_categories.index')" class="btn btn-wide btn-secondary mt-4">
			Kategorie
		</Link>
		<button v-if="categories.length" @click="createModalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj przedmiot
		</button>
	</template>

	<!-- Data present -->
	<template v-else>
		<DataTable :columns=columns :data=items :filters=filters sortRoute="admin.store_items.index">
			<template #buttons>
				<button @click="createModalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus mr-2"></i>
					Dodaj przedmiot
				</button>
				<div class="flex justify-center space-x-2">
					<Link :href="route('admin.store_categories.index')" class="btn btn-secondary sm:w-auto sm:btn-sm">
						Kategorie
					</Link>
					<Link :href="route('admin.store_requests.index')" class="btn btn-secondary sm:w-auto sm:btn-sm">
						Zamówienia
					</Link>
				</div>
			</template>

			<template #content>
				<tr v-for="row in items.data" :key="row" class="hover">
					<td class="font-bold">{{ row.id }}</td>
					<td>{{ row.name }}</td>
					<td>{{ row.category.name }}</td>
					<td>{{ row.quantity }}</td>
					<td>{{ row.price }}</td>
					<td class="space-x-2 text-center">
						<button @click=showDetails(row) class="btn btn-xs btn-accent">Szczegóły</button>
						<button @click="deleteRow(row)" class="btn btn-xs btn-error">
							<i class="fas fa-trash cursor-pointer"></i>
							<span class="ml-1">Usuń</span>
						</button>
					</td>
				</tr>
			</template>
		</DataTable>

		<!-- Modal - details -->
		<Modal :show=detailsModalOpened @close=close :id="'modal-2'">

			<template #side>
				<div class="flex space-x-2">
					<button @click=deleteRow(selectedItem) class="btn btn-error btn-xs">
						<i class="fas fa-trash"></i>
						<span class="ml-2">Usuń</span>
					</button>
					<button @click="edit(selectedItem)" :class="{'btn-info':!editMode, 'btn-error':editMode}" class="btn btn-xs">
						<i :class="{'fas fa-edit':!editMode, 'fas fa-times':editMode}"></i>
						<span v-html="editMode ? 'Anuluj' : 'Edytuj'" class="ml-2"></span>
					</button>
					<button v-if="editMode" @click=update :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-success">Zapisz</button>
				</div>
			</template>

			<template #content>
				<!-- Form and labels -->
				<form>
					<label v-if="form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>
					<label v-if="form.errors.price" class="label label-text-alt text-error text-sm">{{ form.errors.price }}</label>
					<div class="flex items-start justify-between my-4">
						<div>
							<template v-if=!editMode>
								<span class="font-bold text-lg">{{ selectedItem.id }}.</span>
								<span class="mx-2 text-lg">{{ selectedItem.name }}</span>
							</template>
							
							<h1 v-else class="text-lg font-bold">
								{{`${selectedItem.id}.`}}
								<input v-model="form.name" type="text" class="input input-primary input-sm w-60" required/>
								<input v-model="form.quantity" type="number" class="input input-primary input-sm w-24 ml-2" min=0 max=9999 required />
								<span class="ml-1">szt.</span>
							</h1>

							<h2 v-if=!editMode class="text-gray-600">{{ selectedItem.category.name }}</h2>
							<select v-else v-model=form.store_category_id class="select select-bordered select-primary select-sm mt-2 ml-5 w-60 text-sm">
								<option v-for="row in categories" :key=row.id :value=row.id>{{ row.name }}</option>
							</select>
							<input v-if=editMode v-model="form.price" type="number" step=".01" class="input input-primary input-sm w-24 ml-2" min=0 max=9999 required />
							<span v-if=editMode class="font-bold ml-2 text-lg">zł</span>

							<div v-if=!editMode class="flex space-x-3">
								<span class="font-bold">{{ selectedItem.price }}zł</span>
								<span class="font-bold">{{ selectedItem.quantity }}szt.</span>
							</div>
						</div>						
					</div>

					<!-- Content -->
					<div class="mt-4 flex space-x-4">
						<img :src="selectedItem.photo_path" :alt="selectedItem.name" class="block h-24 w-24 object-cover mask mask-squircle" />
						<p v-if=!editMode class="text-justify">{{ selectedItem.description ?? 'Nie dodano opisu'}}</p>
						<textarea v-else v-model=form.description class="textarea h-32 w-full textarea-bordered textarea-primary resize-none" placeholder="Opis..."></textarea>
					</div>
				</form>
			</template>

		</Modal>
	</template>

	<!-- Create item modal -->
	<Modal :show=createModalOpened @close=close :id="'modal-1'" :maxWidth="'max-w-sm'">
		<template #side>
			<h1 class="text-lg font-semibold">Nowy przedmiot</h1>
		</template>

		<template #content>
			<form>
				<div class="form-control mt-4">

					<label class="label"><span class="label-text">Kategoria<span class="ml-1 text-red-500">*</span></span></label> 
					<select v-model=form.store_category_id class="select select-bordered select-primary w-full">
						<option v-for="row in categories" :key=row.id :value=row.id>{{ row.name }}</option>
					</select>

					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.name type="text" placeholder="Nazwa" class="input input-primary input-bordered" required>
					<label v-if="form.errors.name" class="label label-text-alt text-error text-sm">{{ form.errors.name }}</label>

					<label class="label"><span class="label-text">Ilość<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.quantity type="number" placeholder="Ilość" class="input input-primary input-bordered" min=0 max=9999 required>
					<label v-if="form.errors.quantity" class="label label-text-alt text-error text-sm">{{ form.errors.quantity }}</label>

					<label class="label"><span class="label-text">Cena<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.price type="number" step=".01" placeholder="Cena" class="input input-primary input-bordered" min=0 max=9999 required>
					<label v-if="form.errors.price" class="label label-text-alt text-error text-sm">{{ form.errors.price }}</label>

					<label class="label">
						<span class="label-text">Opis</span>
					</label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." max=255 min=3></textarea>
					<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>

					<input type="file" id="upload-file-store" @change="previewImage" ref="photo" accept="image/*" @input="form.image = $event.target.files[0]" class="hidden" />
					<div v-if="url && form.image" class="mx-auto indicator mt-6">
						<div class="indicator-item">
							<button v-if="url && form.image" @click="form.image=null" class="btn btn-xs btn-ghost"><i class="fas fa-times text-error"></i></button>
						</div> 
						<img :src="url" class="block h-24 w-24 object-cover mask mask-squircle" />
					</div>
					<label for="upload-file-store" refs="upload-file" class="btn btn-primary mt-4">Wybierz zdjęcie</label>
					<label v-if="form.errors.image" class="label label-text-alt text-error text-sm">{{ form.errors.image }}</label>

				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=store :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn  btn-info w-full ">Dodaj</button>
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
        items: Object,
		filters: Object
	},

	setup(props) {
		// Show details of this item
		const selectedItem =  ref({'photo_path':'', 'id':'', 'name':'', 'category':{}})

		// Image upload
		const url = ref(null)

		// Modals visibility
		const createModalOpened = ref(false)
		const detailsModalOpened = ref(false)

		// Details modal edit mode
		const editMode = ref(false)

		// Data form
		const form = useForm({
            id: null,
			name: null,
			price: null,
			image: null,
            description: null,
            quantity: null,
            store_category_id: props.categories.length ? props.categories[0].id : 0
		})

		// Reset form
		const reset = _ => {
			form.reset()
			form.clearErrors()
			url.value = null
			editMode.value = null
		}

		// Close modals and reset data 
		const close = _ => { 
			createModalOpened.value = false
			detailsModalOpened.value = false
			reset()
		}

		// Show item details
		const showDetails = (item) => {
			selectedItem.value = item
			detailsModalOpened.value = true
		}

		// Edit item
		const edit = (row) => { 
			editMode.value = !editMode.value

			form.reset()
			form.clearErrors()
			
			form.id = row.id
			form.name = row.name
            form.description = row.description
			form.price = row.price
			form.store_category_id = row.store_category_id
            form.quantity = row.quantity
		}

		// Store item
        const store = _ => { 
			form.post(route('admin.store_items.store'), {
				onSuccess: () => reset()
			}) 
		}

		// Update item
		const update = _ => { 
			form.put(route('admin.store_items.update', form.id), {
				onSuccess: () => {
					reset()
					selectedItem.value = props.items.data.find(element => element.id == selectedItem.value.id)
				}
			}) 
		}

		// Delete item
        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie konserwacje związane z przedmiotem również zostaną usunięte!')) return;
            Inertia.delete(route('admin.store_items.destroy', row.id), { 
				onSuccess: () => close()
			})
		}
		
		// Images
		const previewImage = (e) => url.value = URL.createObjectURL(e.target.files[0])

		const removeImage = _ => {
			form.image = null
			document.getElementById('category-image').src = "/images/default.png"
		}

		// Datatable columns
		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'name', label:'Przedmiot', sortable: true},
            {name:'store_category_id', label:'Kategoria', sortable: true},
			{name:'quantity', label:'Ilość', sortable: true},
			{name:'price', label:'Cena', sortable: true},
			{name:'actions', label:''}
        ]

		// Returned data
		return { 
			selectedItem,
			url,
			createModalOpened,
			detailsModalOpened,
			editMode, 
			form, 
			showDetails,
			close,
			store,
			edit,
			update,
			deleteRow,
			previewImage,
			removeImage,
			columns
		}
	},

	components: {
		AdminPanelLayout,
		Link,
		DataTable,
		Modal,
	},

});
</script>