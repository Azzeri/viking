<template>

<admin-panel-layout title="Kategorie sprzętu">
	
	<template #page-title>Sprzęt</template>

	<div v-if="!items.data.length && filters.search == null" class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
		<h1>Brak danych</h1>
		<button @click="modalOpened = true" class="p-3 rounded-full border-2">Dodaj przedmiot</button>
		<Link as=button :href="route('admin.inventory.category.index')">Kategorie</Link>
	</div>

	<div v-else>
		<DataTable :columns=columns :data=items :filters=filters sortRoute="admin.inventory.items.index" extraClass="first:h-16 sm:first:h-auto flex sm:table-cell">

			<template #buttons>
				<div class="w-1/3 sm:w-auto flex justify-center">
					<Link as=button :href="route('admin.inventory.category.index')" class="px-2 py-1 bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full border-2">Kategorie</Link>
				</div>
				<div class="w-1/3 sm:w-auto flex justify-center">
					<button @click="modalOpened = true" class="sm:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
						<i class="fas fa-plus fa-lg"></i>
					</button>
					<button @click="modalOpened = true" class="hidden sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-plus fa-lg"></i>
					</button>
				</div>
				<div class="w-1/3 sm:w-auto flex justify-center">
					<Link as=button :href="route('admin.inventory.services.index')" class="bg-white bg-opacity-70 text-gray-800 font-semibold px-2 py-1 rounded-full border-2">Serwisy</Link>
				</div>
			</template>

			<template #content>
				<tr v-for="row in items.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1 flex items-center space-x-3">
						<img class="w-14 h-14" :src=row.photo_path :alt=row.name>
						<span>{{ row.name }}</span>
					</td>
					<td class="px-3 py-1">{{ row.category_name }}</td>
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
	<template #title>Nowy kategoria sprzętu</template>

	<template #content>
		<jet-validation-errors class="my-6" />
		<form @submit.prevent="store, update">

			<div class="mt-6">
				<jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus placeholder="Nazwa" autocomplete="name" />
			</div>
            <div class="mt-6">
				<jet-input id="description" type="text" class="mt-1 block w-full" v-model="form.description" required autofocus placeholder="Opis" autocomplete="description" />
			</div>
            <div class="mt-6" v-if="modalEditMode">
				<jet-input id="quantity" type="number" class="mt-1 block w-full" v-model="form.quantity" required autofocus placeholder="Ilość" autocomplete="quantity" />
			</div>
			<div class="mt-6">
				<select v-model=form.inventory_category_id>
					<template v-for="row in categories" :key=row>
						<option :value="row.id"> {{ row.name }} </option>
					</template>
				</select>
					<span>Selected: {{ form.inventory_category_id }}</span>
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

export default defineComponent({

	props: {
		categories: Object,
        items: Object,
		filters: Object
	},

	setup(props) {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const form = useForm({
            id: null,
			name: null,
			photo: null,
            description: null,
            quantity: null,
            inventory_category_id: props.categories[0].id
		})

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
			form.inventory_category_id = row.inventory_category_id
            form.quantity = row.quantity

			modalOpened.value = true 
		}

        const store = _ => { 
			form.post('inventoryitems/', {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			form.put('inventoryitems/' + form.id, {
				onSuccess: () => close()
			}) 
		}

        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie konserwacje związane z przedmiotem również zostaną usunięte!')) return;
            Inertia.delete('inventoryitems/' + row.id)
        }

		const columns = [
			// {name:'photo_path', label:'Zdjęcie'},
			{name:'name', label:'Przedmiot', sortable: true},
            {name:'inventory_category_id', label:'Kategoria', sortable: true},
			{name:'quantity', label:'Ilość', sortable: true},
			{name:'actions', label:'Działania'}
        ]

		return { form, columns, modalOpened, modalEditMode, close, store, edit, update, deleteRow }
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
		
	},

});
</script>