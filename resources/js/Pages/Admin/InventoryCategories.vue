<template>
<admin-panel-layout title="Kategorie sprzętu">
	
	<template #page-title>Kategorie sprzętu</template>

	<div v-if="!categories.data.length && filters.search == null" class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
		<h1>Brak danych</h1>
		<button @click="modalOpened = true" class="p-3 rounded-full border-2">Dodaj kategorię</button>
	</div>

	<div v-else>

		<DataTable :columns=columns :data=categories :filters=filters sortRoute="admin.inventory.category.index" extraClass="first:h-20 sm:first:h-auto flex sm:table-cell">

			<template #buttons>
				<div class="w-1/2 sm:w-auto flex justify-center">
					<Link as=button :href="route('admin.inventory.items.index')" class="sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-arrow-left fa-lg"></i>
					</Link>
				</div>
				<div class="w-1/2 sm:w-auto flex justify-center">
					<button @click="modalOpened = true" class="sm:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
						<i class="fas fa-plus fa-lg"></i>
					</button>
					<button @click="modalOpened = true" class="hidden sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
						<i class="fas fa-plus fa-lg"></i>
					</button>
				</div>				
			</template>

			<template #content>
				<tr v-for="row in categories.data" :key="row" 
					class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1 flex items-center space-x-3 h-20">
						<img class="w-14 h-14" :src=row.photo_path :alt=row.name>
						<div>
							<div>{{ row.name }}</div>
							<div class="text-sm text-gray-500">{{ row.parentCategoryName }}</div>
							<div v-if="row.subcategories.length">
								<button @click="showSubcategories(row.id)" class="px-1 rounded-lg bg-green-500 text-white">Podkategorie</button>		
								<div class="absolute bg-red-500 p-3 h-32 w-64 mt-2 rounded-lg overflow-y-scroll hidden left-1" :id=row.id>
									<div v-for="sub in row.subcategories" :key="sub">
										{{sub}}
									</div>
								</div>	
							</div>
						</div>
					</td>

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
				<select v-model=form.parentCategoryId>
					<option value="-1">Brak</option>
					<template v-for="row in categories.data" :key=row>
						<option v-if="row.id != form.id" :value="row.id"> {{ row.name }} </option>
					</template>
				</select>
					<!-- <span>Selected: {{ form.parentCategoryId }}</span> -->
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
		filters: Object
	},

	setup() {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const form = useForm({
            id: null,
			name: null,
			photo: null,
            parentCategoryId: -1
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
			form.parentCategoryId = row.parentCategoryId ? row.parentCategoryId : -1

			modalOpened.value = true 
		}

        const store = _ => { 
			form.post('inventorycategories/', {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			form.put('inventorycategories/' + form.id, {
				onSuccess: () => close()
			}) 
		}

        const deleteRow = (row) => {
            if (!confirm('Na pewno? Wszystkie przedmioty należące do kategorii i podkategorie również zostaną usunięte!')) return;
            Inertia.delete('inventorycategories/' + row.id)
        }

		const showSubcategories = (id) => {
			let element = document.getElementById(id)
			element.classList.contains('hidden') ? element.classList.remove('hidden') : element.classList.add('hidden')
		}

		const columns = [
			{name:'name', label:'Kategoria', sortable: true},
			// {name:'subcategories', label:'Podkategorie'},
			{name:'actions', label:'Działania'}
        ]

		return { form, columns, modalOpened, modalEditMode, close, store, edit, update, deleteRow, showSubcategories }
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