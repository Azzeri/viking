<template>
  <admin-panel-layout title="Posty">

    <template v-if="!posts.data.length && filters.search == null">
		<h1 class="text-4xl font-bold text-center mt-6 lg:mt-12">Nie dodano jeszcze żadnego postu</h1>
		<button @click="modalOpened = true" class="btn btn-wide btn-secondary mt-4">
			<i class="fas fa-plus fa-lg mr-3"></i>
			Dodaj post
		</button>
	</template>

	<template v-else>
		<DataTable :columns=columns :data=posts :filters=filters sortRoute="admin.posts.index">

			<template #buttons>
				<button @click="modalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
					<i class="fas fa-plus fa-lg mr-2"></i>
					Dodaj post
				</button>
			</template>

			<template #content>
				<tr v-for="row in posts.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1">{{ row.id }}</td>
					<td class="px-3 py-1">{{ row.title }}</td>
					<td class="px-3 py-1">{{ `${row.date_created}, ${row.time_created}` }}</td>
					<td class="px-3 py-1">{{ `${row.user.name} "${row.user.nickname}" ${row.user.surname}` }}</td>
					<td class="px-3 py-1">
						<Link :href="route('admin.posts.show', row.id)" class="btn btn-primary btn-xs">Szczegóły</Link> 
					</td>
				</tr>
			</template>
			
		</DataTable>
	</template>

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
		posts: Object,
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
			form.post(route('admin.posts.store'), {
				onSuccess: () => close()
			}) 
		}

		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'title', label:'Tytuł', sortable: true},
			{name:'created_at', label:'Dodano', sortable: true},
			{name:'user_id', label:'Autor', sortable: true},
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