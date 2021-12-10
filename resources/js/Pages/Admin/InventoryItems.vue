<template>

<admin-panel-layout title="Sprzęt">
	
	<!-- No data scenario -->
	<template v-if="(!items.data.length || !categories.length) && filters.search == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego przedmiotu</h1>
		<Link :href="route('admin.inventory_categories.index')" class="btn btn-wide btn-secondary mt-4">
			Kategorie
		</Link>
		<button v-if="categories.length" @click="modalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj przedmiot
		</button>
	</template>

	<template v-else>
		<DataTable :columns=columns :data=items :filters=filters sortRoute="admin.inventory_items.index">
			<template #buttons>
				<button @click="modalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus mr-2"></i>
					Dodaj przedmiot
				</button>
				<div class="flex justify-center space-x-2">
					<Link :href="route('admin.inventory_categories.index')" class="btn btn-secondary sm:w-auto sm:btn-sm">
						Kategorie
					</Link>
					<Link :href="route('admin.inventory_services.index')" class="btn btn-secondary sm:w-auto sm:btn-sm">
						Serwisy
					</Link>
				</div>
			</template>

			<template #content>
				<tr v-for="row in items.data" :key="row" class="hover">
					<td class="font-bold">{{ row.id }}</td>
					<td>{{ row.name }}</td>
					<td>{{ row.category.name }}</td>
					<td>{{ row.quantity }}</td>
					<td class="space-x-2">
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
		<input type="checkbox" id="modal-details" class="modal-toggle"> 
		<div class="modal overflow-y-auto">
			<div class="modal-box max-w-2xl">
				<jet-validation-errors v-if="form.hasErrors" />
				<div class="flex justify-between items-center">
					<div class="flex space-x-2">
						<button class="btn btn-error btn-xs">
							<i class="fas fa-trash"></i>
							<span @click=deleteRow(currentItem) class="ml-2">Usuń</span>
						</button>
						<button @click="edit(currentItem)" :class="{'btn-info':!editMode, 'btn-error':editMode}" class="btn btn-xs">
							<i :class="{'fas fa-edit':!editMode, 'fas fa-times':editMode}"></i>
							<span v-html="editMode ? 'Anuluj' : 'Edytuj'" class="ml-2"></span>
						</button>
						<button v-if="editMode" @click=update class="btn btn-xs btn-info">Zapisz</button>
					</div>
					<label @click=close for="task-details" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></label>
				</div>
				<!-- Form and labels -->
				<form @submit.prevent=update>
					<div class="flex items-start justify-between mb-4">
						<div>
							<h1 v-if=!editMode class="text-lg font-bold">{{ `${currentItem.id}. ${currentItem.name} - ${currentItem.quantity}szt.` }}</h1>
							<h1 v-else class="text-lg font-bold">
								{{`${currentItem.id}.`}}
								<input v-model="form.name" type="text" class="input input-primary input-sm w-60" />
								<input v-model="form.quantity" type="number" class="input input-primary input-sm w-20 ml-2" />
								<span class="ml-1">szt.</span>
							</h1>

							<h2 v-if=!editMode class="text-gray-600">{{ currentItem.category.name }}</h2>
							<select v-else v-model=form.inventory_category_id class="select select-bordered select-primary select-sm mt-2 ml-5 w-60 text-sm">
								<option v-for="row in categories" :key=row.id :value=row.id>{{ row.name }}</option>
							</select>
						</div>
						
					</div>

					<!-- Content -->
					<div class="mt-4 flex space-x-4">
						<img :src=currentItem.photo_path :alt="currentItem.name" class="block h-32 object-fill">
						<p v-if=!editMode class="text-justify">{{ currentItem.description ?? 'Nie dodano opisu'}}</p>
						<textarea v-else v-model=form.description class="textarea h-32 w-full textarea-bordered textarea-primary" placeholder="Opis..."></textarea>
					</div>
				</form>
			</div>
		</div>
	</template>

	<CrudModal :show=modalOpened @close=close>
		<template #title>Nowy przedmiot</template>

		<template #content>
			<jet-validation-errors v-if="form.hasErrors" />
			<form @submit.prevent=store,update>
				<div class="form-control mt-4">

					<label class="label"><span class="label-text">Kategoria<span class="ml-1 text-red-500">*</span></span></label> 
					<select v-model=form.inventory_category_id class="select select-bordered select-primary w-full">
						<option v-for="row in categories" :key=row.id :value=row.id>{{ row.name }}</option>
					</select>

					<label class="label"><span class="label-text">Nazwa<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.name type="text" placeholder="Nazwa" class="input input-primary input-bordered">

					<label class="label"><span class="label-text">Ilość<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.quantity type="number" placeholder="Ilość" class="input input-primary input-bordered">

					<label class="label">
						<span class="label-text">Opis</span>
					</label> 
					<textarea v-model=form.description class="textarea h-24 textarea-bordered textarea-primary" placeholder="Opis..."></textarea>

				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=store :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="btn  btn-info w-full ">Dodaj</button>
		</template>
	</CrudModal>

</admin-panel-layout>

<!-- <PhotoModal :item=itemForPhotoForm :show=photoModalOpened path='admin.inventory.items' @closePhotoModal=closePhotoModal></PhotoModal> -->

</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'
import PhotoModal from '@/Components/PhotoModal.vue'
import Label from '@/Jetstream/Label.vue'

export default defineComponent({
	props: {
		categories: Object,
        items: Object,
		filters: Object
	},

	setup(props) {
		const modalOpened = ref(false)
		const editMode = ref(false)
		const photoModalOpened = ref(false)
		const itemForPhotoForm = props.items.length ? ref(props.items.data[0]) : ref({})
		const currentItem = ref(props.items.data[0]) ?? ref({})

		const form = useForm({
            id: null,
			name: null,
			photo: null,
            description: null,
            quantity: null,
            inventory_category_id: props.categories.length ? props.categories[0].id : 0
		})

		const closePhotoModal = _ => photoModalOpened.value = false

		const openPhotoModal = (row) => {
			itemForPhotoForm.value = row
			photoModalOpened.value = true
		}

		const reset = _ => { 
			form.reset()
			editMode.value = false 
		}

		const close = _ => { 
			modalOpened.value = false
			editMode.value = false
			document.getElementById('modal-details').checked = false
			form.reset()
			form.clearErrors()
		}

		const edit = (row) => { 
			editMode.value = !editMode.value

			form.id = row.id
			form.name = row.name
            form.description = row.description
			form.inventory_category_id = row.inventory_category_id
            form.quantity = row.quantity
		}

        const store = _ => { 
			form.post(route('admin.inventory_items.store'), {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			form.put(route('admin.inventory_items.update', form.id), {
				onSuccess: () => close()
			}) 
		}

        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie konserwacje związane z przedmiotem również zostaną usunięte!')) return;
            Inertia.delete(route('admin.inventory_items.destroy', row.id), { onSuccess: () => close() })
        }

		const showDetails = (item) => {
			currentItem.value = item
			document.getElementById('modal-details').checked = true
		}

		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'name', label:'Przedmiot', sortable: true},
            {name:'inventory_category_id', label:'Kategoria', sortable: true},
			{name:'quantity', label:'Ilość', sortable: true},
			{name:'actions', label:''}
        ]

		return { form, columns, modalOpened, editMode, itemForPhotoForm, 
				 close, store, edit, update, deleteRow, photoModalOpened, closePhotoModal, openPhotoModal,
				 currentItem, showDetails }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
		DataTable,
		CrudModal,
		PhotoModal,
		Label
	},

});
</script>