<template>
  <admin-panel-layout title="Kategorie zdjęć">

    <template v-if="!categories.data.length && filters.search == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnej kategorii</h1>
		<button @click="modalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj kategorię
		</button>
	</template>

	<template v-else>
		<DataTable :columns=columns :data=categories :filters=filters sortRoute="admin.photo_categories.index" extraClass="first:h-20 sm:first:h-auto flex sm:table-cell">

			<template #buttons>
				<button @click="modalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus fa-lg mr-2"></i>
					Dodaj kategorię
				</button>
			</template>

			<template #content>
				<tr v-for="row in categories.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1 ">{{ row.id }}</td>
                    <td class="px-3 py-1 flex items-center space-x-3 h-20">
						<img @click="openPhotoModal(row)" class="w-14 h-14 cursor-pointer rounded-full" :src=row.photo_path :alt=row.name>
						<div>
							<div>{{ row.name }}</div>
							<div class="text-sm text-gray-500">{{ row.parent_category.name }}</div>
							<div v-if="row.subcategories.length">
								<button @click="showSubcategories(row.id)" class="px-1 rounded-lg bg-green-500 text-white">Podkategorie</button>		
								<div :id="'category-'+row.id" class="absolute bg-red-500 p-3 h-32 w-64 mt-2 rounded-lg overflow-y-scroll hidden left-1">
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
	</template>
<!-- 
	<CrudModal :show=modalOpened @close=close>
		<template #title>Nowy post</template>

		<template #content>
			<jet-validation-errors v-if="form.hasErrors" class="my-6" />
			<form @submit.prevent=store>
				<div class="form-control mt-4">
					<label class="label"><span class="label-text">Tytuł<span class="ml-1 text-red-500">*</span></span></label> 
					<input v-model=form.title type="text" placeholder="Tytuł posta" class="input input-primary input-bordered">

					<label class="label mt-4">
						<span class="label-text">Treść<span class="ml-1 text-red-500">*</span></span>
					</label> 
					<textarea v-model=form.body class="textarea h-24 textarea-bordered textarea-primary" placeholder="Treść......"></textarea>

				</div> 
			</form>
		</template>

		<template #footer>
			<button @click=store :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="btn btn-info">Dodaj</button>
		</template>
	</CrudModal> 
	 -->
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		categories: Object,
		filters: Object
	},

	setup() {
		const modalOpened = ref(false)

		const form = useForm({
			title:null,
			body:null,
			resource_link:null,
		})

		const close = _ => { 
			modalOpened.value = false
			form.reset() 
			form.clearErrors()
		}

		const store = _ => { 
			form.post(route('admin.categories.store'), {
				onSuccess: () => close()
			}) 
		}

		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'name', label:'Nazwa', sortable: true},
			{name:'actions', label:'Działania', sortable: false},
        ]

		return { form, columns, modalOpened, close, store }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
		DataTable,
		CrudModal,
	},

});
</script>