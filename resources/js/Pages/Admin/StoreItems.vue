<template>

<admin-panel-layout title="Sklep">
	
	<template #page-title>Sklep</template>

	<div v-if="!items.data.length && filters.search == null" class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
		<h1>Brak danych</h1>
		<div class="flex space-x-2">
			<Link as=button :href="route('admin.store.category.index')" class="px-2 py-1 bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full border-2">Kategorie</Link>
			<button v-if="categories.length" @click="modalOpened = true" class="sm:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
				<i class="fas fa-plus fa-lg"></i>
			</button>
			<button v-if="categories.length" @click="modalOpened = true" class="hidden sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
				<i class="fas fa-plus fa-lg"></i>
			</button>
		</div>
		
	</div>

	<div v-else>
		<DataTable :columns=columns :data=items :filters=filters sortRoute="admin.store.items.index" extraClass="first:h-16 sm:first:h-auto flex sm:table-cell">

			<template #buttons>
				<div class="w-1/3 sm:w-auto flex justify-center">
					<Link as=button :href="route('admin.store.category.index')" class="px-2 py-1 bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full border-2">Kategorie</Link>
				</div>
				<div class="w-1/3 sm:w-auto flex justify-center">
					<button @click="modalOpened = true" class="sm:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
						<i class="fas fa-plus fa-lg"></i>
					</button>
					<button @click="modalOpened = true" class="hidden sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-plus fa-lg"></i>
					</button>
				</div>
			</template>

			<template #content>
				<tr v-for="row in items.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1 flex items-center space-x-3">
						<img @click=openPhotoModal(row) class="w-14 h-14 rounded-full cursor-pointer" :src=row.photo_path :alt=row.name>
						<span>{{ row.name }}</span>
					</td>
					<td class="px-3 py-1">{{ row.category_name }}</td>
					<td class="px-3 py-1">{{ row.price }}</td>
					<td class="px-3 py-1">{{ row.quantity }}</td>
					<td class="px-3 py-1 space-x-3">
						<i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
						<i @click="deleteRow(row)" class="fas fa-trash cursor-pointer text-red-700"></i>
					</td>
				</tr>
			</template>
			
		</DataTable>
	</div>

</admin-panel-layout>

<CrudModal :show=modalOpened @close=close>
	<template #title>Nowy przedmiot w sklepie</template>

	<template #content>
		<jet-validation-errors class="my-6" />
		<form @submit.prevent="store, update">

			<div class="mt-6">
				<label for=name>Nazwa</label>
				<jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus placeholder="Nazwa" autocomplete="name" />
			</div>
            <div class="mt-6">
				<label for=description>Opis</label>
				<jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" required placeholder="Opis" autocomplete="description" />
			</div>
            <div class="mt-6">
				<label for=price>Cena</label>
				<jet-input id="price" type="number" class="mt-1 block w-full" v-model="form.price" required  placeholder="Cena" autocomplete="price" />
			</div>
            <div class="mt-6">
				<label for=quantity>Ilość</label>
				<jet-input id="quantity" type="number" class="mt-1 block w-full" v-model="form.quantity" required placeholder="Ilość" autocomplete="quantity" />
			</div>
			<div class="mt-6">
				<label for=category>Kategoria</label>
				<select id=category class="w-full rounded-lg" v-model=form.store_category_id>
					<template v-for="row in categories" :key=row>
						<option :value="row.id"> {{ row.name }} </option>
					</template>
				</select>
			</div>

		</form>
	</template>

	<template #footer>
		<jet-button type="submit" v-if=!modalEditMode @click=store class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
			Dodaj
		</jet-button>

		<jet-button type="submit" v-else @click=update class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
			Edytuj
		</jet-button>

	</template>
</CrudModal>

<PhotoModal :item_id=itemForPhotoForm.id :show=photoModalOpened :src=itemForPhotoForm.photo_path path='storeItems' @closePhotoModal=closePhotoModal></PhotoModal>

</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetButton from '@/Jetstream/Button.vue'
import JetInput from '@/Jetstream/Input.vue'
import JetLabel from '@/Jetstream/Label.vue'
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'
import { Inertia } from '@inertiajs/inertia'
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
		const modalEditMode = ref(false)
		const photoModalOpened = ref(false)
		const itemForPhotoForm = props.items.length ? ref(props.items.data[0]) : ref(0)
		
		const form = useForm({
            id: null,
			name: null,
			photo: null,
            description: null,
            price: null,
            quantity: null,
            store_category_id: props.categories.length ? props.categories[0].id : 0
		})

		const closePhotoModal = _ => photoModalOpened.value = false

		const openPhotoModal = (row) => {
			itemForPhotoForm.value = row
			photoModalOpened.value = true
		}

		const reset = _ => { 
			form.reset()
			modalEditMode.value = false 
		}

		const close = _ => { 
			modalOpened.value = false
			reset() 
		}

		const edit = (row) => { 
			modalEditMode.value = true

			form.id = row.id
			form.name = row.name
            form.description = row.description
			form.store_category_id = row.store_category_id
            form.quantity = row.quantity
            form.price = row.price

			modalOpened.value = true 
		}

        const store = _ => { 
			form.post('storeitems/', {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			form.put('storeitems/' + form.id, {
				onSuccess: () => close()
			}) 
		}

        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie zamówienia związane z przedmiotem również zostaną usunięte!')) return;
            Inertia.delete('storeitems/' + row.id)
        }

		const columns = [
			{name:'name', label:'Przedmiot', sortable: true},
            {name:'store_category_id', label:'Kategoria', sortable: true},
			{name:'price', label:'Cena', sortable: true},
			{name:'quantity', label:'Ilość', sortable: true},
			{name:'actions', label:'Działania'}
        ]

		return { form, columns, modalOpened, modalEditMode, itemForPhotoForm, 
				 close, store, edit, update, deleteRow, photoModalOpened, closePhotoModal, openPhotoModal }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetButton,
		JetInput,
		JetLabel,
		JetValidationErrors,
		DataTable,
		CrudModal,
		PhotoModal,
		Label
	},

});
</script>