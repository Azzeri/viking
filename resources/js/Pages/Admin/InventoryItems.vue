<template>

<admin-panel-layout title="Sprzęt">
	
	<!-- Data not present -->
		<template v-if="(!items.data.length || !categories.length) && filters.search == null">
		<h1 class="text-4xl font-bold text-center my-6 lg:my-12">Nie dodano jeszcze żadnego przedmiotu</h1>
		<div class="flex flex-col gap-4 justify-center items-center">
			<Link :href="route('admin.inventory_categories.index')" class="btn btn-wide btn-secondary">
				Kategorie
			</Link>
			<button v-if="categories.length" @click="createModalOpened = true" class="btn btn-wide btn-secondary">
				<i class="fas fa-plus fa-lg mr-3"></i>
				Dodaj przedmiot
			</button>
		</div>
	</template>

	<!-- Data present -->
	<template v-else>
		<DataTable :columns=columns :data=items :filters=filters sortRoute="admin.inventory_items.index">
			<template #buttons>
				<button @click="createModalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus mr-2"></i>
					Dodaj przedmiot
				</button>
				<div class="flex justify-center space-x-2">
					<Link :href="route('admin.inventory_categories.index')" class="btn btn-secondary sm:btn-sm">
						Kategorie
					</Link>
					<Link :href="route('admin.inventory_services.index')" class="btn btn-secondary sm:btn-sm">
						Serwisy
					</Link>
				</div>
			</template>

			<template #content>
				<tr v-for="row in items.data" :key="row" class="hover">
					<th class="font-bold">{{ row.id }}</th>
					<td>{{ row.name }}</td>
					<td>{{ row.category.name }}</td>
					<td>{{ row.quantity }}</td>
					<td :class="{'text-success':row.is_functional, 'text-error':!row.is_functional}">{{ row.is_functional ? 'sprawny' : 'niesprawny' }}</td>
					<td>{{ row.owner ? row.owner.name : 'Nie określono' }}</td>
					<td class="space-x-2 text-center">
						<button @click=showDetails(row) class="btn btn-xs btn-primary">Szczegóły</button>
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
					<button v-if="editMode" @click="$refs.updateItem.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn btn-xs btn-success">Zapisz</button>
				</div>
			</template>

			<template #content>
				<template v-if="!editMode">
					<div class="flex mt-4">
						<img :src="selectedItem.photo_path" :alt="selectedItem.name" class="block h-24 w-24 object-cover mask mask-squircle">
						<div class="flex flex-col">
							<div class="flex gap-1 ml-1 mt-1">
								<h1 class="text-lg font-bold">{{ `${selectedItem.id}.` }}</h1>
								<h1	class="text-lg font-semibold">{{ selectedItem.name }}</h1>
							</div>
							<h2 class="text-xm text-gray-500 ml-1">{{ selectedItem.category.name }}</h2>
							<div class="flex flex-col ml-1 mt-2">
								<span class="font-semibold">{{ `${selectedItem.quantity}szt.` }}</span>
								<span :class="{'text-success':selectedItem.is_functional, 'text-error':!selectedItem.is_functional}">{{ selectedItem.is_functional ? 'sprawny' : 'niesprawny' }}</span>
							</div>
						</div>
					</div>
					<div class="ml-1 mt-2">{{ `Właściciel: ${selectedItem.owner ? selectedItem.owner.name : 'Nie określono'}` }}</div>
					<p class="ml-1 mt-4 text-justify">{{ selectedItem.description }}</p>
				</template>
				<template v-else>
					<form @submit.prevent="update">
						<input type="file" id="upload-file-update" @change="previewImage" ref="photo" accept="image/*" @input="form.image = $event.target.files[0]" class="hidden" />
						<div class="flex my-4 space-x-2 w-full">
							<div class="mx-auto indicator flex-shrink-0">
								<div class="indicator-item indicator-start">
									<label @click="removeImage()" class="btn btn-xs btn-ghost"><i class="fas fa-times text-error"></i></label>
								</div> 
								<img @click="$refs.photo.click()" v-if="url && form.image" :src="url" class="block h-24 w-24 object-cover mask mask-squircle hover:opacity-70 cursor-pointer" />
								<img @click="$refs.photo.click()" v-else :src="selectedItem.photo_path" :alt="selectedItem.name" id="category-image" class="hover:opacity-70 cursor-pointer block h-24 w-24 object-cover mask mask-squircle">
							</div>
							<div class="flex flex-col mt-1 gap-2 justify-center w-full">
								<select v-model=form.inventory_category_id class="select select-bordered select-primary select-sm text-sm w-full">
									<option v-for="row in categories" :key=row.id :value=row.id>{{ row.name }}</option>
								</select>
								<form-input-field id="edit-item-name" type="text" name="Nazwa" :required="true" model="name" :form="form" min="3" max="64" :label="false" extraClass="input-sm w-full"></form-input-field>							
							</div>
						</div>
						<div class="flex flex-col gap-2">
							<div class="flex gap-2 items-end">
								<form-input-field type="number" name="Ilość" :required="true" model="quantity" :form="form" min="0" max="9999" :label="false" extraClass="input-sm w-full"></form-input-field>szt.
								<div class="flex mt-4 items-center space-x-2">
									<input v-model="form.is_functional" type="checkbox" class="checkbox checkbox-primary">
									<span class="label-text">Sprawny</span> 
								</div>
							</div>
							<label class="label"><span class="label-text">Właściciel<span class="ml-1 text-red-500">*</span></span></label> 
							<select v-model=form.owner class="select select-bordered select-primary select-sm text-sm w-full">
								<option :value="null">Brak</option>
								<option v-for="row in users" :key=row.id :value=row.id>{{ row.name }}</option>
							</select>
							<textarea v-model=form.description class="textarea h-32 w-full textarea-bordered textarea-primary resize-none" placeholder="Opis..." minlength="3" maxlength="255"></textarea>
						</div>

						<input type="submit" ref="updateItem" class="hidden" />
					</form>
					
				</template>
			</template>

		</Modal>
	</template>

	<!-- Create item modal -->
	<Modal :show=createModalOpened @close=close :id="'modal-1'" :maxWidth="'max-w-sm'">
		<template #side>
			<h1 class="text-lg font-semibold">Nowy przedmiot</h1>
		</template>

		<template #content>
			<form @submit.prevent="store">
				<div class="form-control mt-4">

					<label class="label"><span class="label-text">Kategoria<span class="ml-1 text-red-500">*</span></span></label> 
					<select v-model=form.inventory_category_id class="select select-bordered select-primary w-full">
						<option v-for="row in categories" :key=row.id :value=row.id>{{ row.name }}</option>
					</select>

					<form-input-field type="text" name="Nazwa" :required="true" model="name" :form="form" min="3" max="64" ></form-input-field>
					<form-input-field type="number" name="Ilość" :required="true" model="quantity" :form="form" min="0" max="9999" ></form-input-field>

					<label class="label">
						<span class="label-text">Opis</span>
					</label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary resize-none" placeholder="Opis..." maxlength=255 minlength=3></textarea>
					<label v-if="form.errors.description" class="label label-text-alt text-error text-sm">{{ form.errors.description }}</label>

					<label class="label"><span class="label-text">Właściciel<span class="ml-1 text-red-500">*</span></span></label> 
					<select v-model=form.owner class="select select-bordered select-primary w-full">
						<option :value="null">Brak</option>
						<option v-for="row in users" :key=row.id :value=row.id>{{ row.name }}</option>
					</select>

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
				<input type="submit" ref="createItemSubmit" class="hidden" />
			</form>
		</template>

		<template #footer>
			<button @click="$refs.createItemSubmit.click()" :disabled="form.processing" :class="{ 'loading': form.processing }" class="btn  btn-info w-full ">Dodaj</button>
		</template>
	</Modal>

</admin-panel-layout>
</template>

<script>
import { defineComponent, ref, nextTick } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import DataTable from '@/Components/DataTable.vue'
import Modal from '@/Components/CrudModal.vue'
import FormInputField from "@/Components/FormInputField.vue";

export default defineComponent({
	props: {
		categories: Object,
        items: Object,
		filters: Object,
		users: Object
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
			image: null,
            description: null,
            quantity: null,
			is_functional: true,
            inventory_category_id: props.categories.length ? props.categories[0].id : 0,
			deleteImage: false,
			owner: null
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
			form.inventory_category_id = row.inventory_category_id
            form.quantity = row.quantity
			form.is_functional = row.is_functional
			form.owner = row.owner.id

			nextTick(() => {
				if(document.getElementById('edit-item-name'))
					document.getElementById('edit-item-name').focus()
			}) 
		}

		// Store item
        const store = _ => { 
			form.post(route('admin.inventory_items.store'), {
				onSuccess: () => reset()
			}) 
		}

		// Update item
		const update = _ => { 
			form.post(route(`admin.inventory_items.update`, { inventory_item:selectedItem.value.id, _method:'put' }), {
				onSuccess: () => {
					reset()
					selectedItem.value = props.items.data.find(element => element.id == selectedItem.value.id)
				}
			}) 
		}

		// Delete item
        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie konserwacje związane z przedmiotem również zostaną usunięte!')) return;
            Inertia.delete(route('admin.inventory_items.destroy', row.id), { 
				onSuccess: () => close()
			})
		}
		
		// Images
		const previewImage = (e) => url.value = URL.createObjectURL(e.target.files[0])

		const removeImage = _ => {
			form.image = null
			form.deleteImage = true
			document.getElementById('category-image').src = "/images/default.png"
		}

		// Datatable columns
		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'name', label:'Przedmiot', sortable: true},
            {name:'inventory_category_id', label:'Kategoria', sortable: true},
			{name:'quantity', label:'Ilość', sortable: true},
			{name:'is_functional', label:'Sprawność', sortable: true},
			{name:'owner', label:'Właściciel', sortable: true},
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
		FormInputField
	},

});
</script>