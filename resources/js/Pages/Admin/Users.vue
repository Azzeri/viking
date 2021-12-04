<template>
  <admin-panel-layout title="Użytkownicy">
    <template #page-title>Użytkownicy</template>

	<DataTable :columns=columns :data=users :filters=filters sortRoute="admin.users.index" extraClass="first:h-14 sm:first:h-auto flex sm:table-cell">

		<template #buttons>
			<button @click="modalOpened = true" class="btn btn-primary w-full sm:w-auto sm:btn-sm">
				<i class="fas fa-plus fa-lg mr-2"></i>
				Dodaj użytkownika
			</button>
		</template>

		<template #content>
			<tr v-for="row in users.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
				<td class="px-3 py-1">{{ row.id }}</td>
				<td class="px-3 py-1 flex items-center space-x-3 h-20">
					<img class="w-14 h-14 rounded-full" src='https://picsum.photos/600/400/?random' :alt=row.name>
					<div>
						<div>{{ row.name + ' ' + row.surname}}</div>
						<div class="text-sm text-gray-500">{{ row.nickname }}</div>
					</div>
				</td>
				<td class="px-3 py-1">{{ row.email }}</td>
				<td class="px-3 py-1">{{ row.date_birth }}</td>
				<td v-if="row.role" class="px-3 py-1">{{ row.role }}</td>
				<td v-else class="px-3 py-1">Nie podano</td>
				<td class="px-3 py-1 space-x-3">
					<template v-if="row.privilege_id != $page.props.privileges.IS_ADMIN">
						<i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
						<i @click="deleteRow(row)" class="fas fa-trash cursor-pointer text-red-700"></i>
					</template>
					<template v-else>
						<i class="fas fa-edit cursor-not-allowed text-gray-500"></i>
						<i class="fas fa-trash cursor-not-allowed text-gray-500"></i>
					</template>
				</td>
			</tr>
		</template>
		
	</DataTable>

	<CrudModal :show=modalOpened @close=close>

		<!-- Modal title -->
		<template #title v-if="!modalEditMode">Nowy użytkownik</template>
		<template #title v-else>Edycja użytkownika</template>

		<template #content>
			<jet-validation-errors class="my-3" v-if="form.hasErrors || formEdit.hasErrors" />

			<!-- Content for link generation -->
			<template v-if="!modalEditMode">
				<form @submit.prevent=generateLink>
					<div class="form-control mt-4">
						<label class="label"><span class="label-text">Email<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=form.email type="email" placeholder="Email użytkownika" class="input input-primary input-bordered" autocomplete="email">

						<label class="label"><span class="label-text">Rola<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=form.role type="text" placeholder="Rola użytkownika" class="input input-primary input-bordered">
					</div>
				</form>
			</template>

			<!-- Content for edit mode -->
			<template v-else>
				<form @submit.prevent=update>
					<div class="form-control mt-4">
						<label class="label"><span class="label-text">Imię<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=formEdit.name type="text" placeholder="Imię" class="input input-primary input-bordered">

						<label class="label"><span class="label-text">Nazwisko<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=formEdit.surname type="text" placeholder="Nazwisko" class="input input-primary input-bordered">

						<label class="label"><span class="label-text">Nick<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=formEdit.nickname type="text" placeholder="Nick" class="input input-primary input-bordered">

						<label class="label"><span class="label-text">Data urodzenia<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=formEdit.date_birth type="date" :max="currentDate()" min="1900-01-01" class="input input-primary input-bordered">

						<label class="label"><span class="label-text">Email<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=formEdit.email type="email" placeholder="Email" class="input input-primary input-bordered">

						<label class="label"><span class="label-text">Rola<span class="ml-1 text-red-500">*</span></span></label> 
						<input v-model=formEdit.role type="text" placeholder="Rola użytkownika" class="input input-primary input-bordered">
					</div>
				</form>
			</template>

		</template>

		<!-- Modal actions -->
		<template #footer>
			<template v-if="!modalEditMode">
				<button @click=generateLink() :disabled="formEdit.processing" :class="{ 'opacity-25': formEdit.processing }" class="btn btn-primary">Wygeneruj link do rejestracji</button>
			</template>
			<template v-else>
				<button @click=update() :disabled="form.processing" :class="{ 'opacity-25': form.processing }" class="btn btn-primary">Zapisz</button>
			</template>
        </template>

	</CrudModal>
	
  </admin-panel-layout>
</template>

<script>
import { defineComponent, ref } from "vue";
import { Link, useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'
import AdminPanelLayout from "@/Layouts/AdminPanelLayout.vue";
import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
import DataTable from '@/Components/DataTable.vue'
import CrudModal from '@/Components/CrudModal.vue'

export default defineComponent({

	props: {
		users: Object,
		filters: Object
	},

	setup() {
		const modalOpened = ref(false)
		const modalEditMode = ref(false)

		const form = useForm({
			email: null,
			role: null
		})

		const formEdit = useForm({
			id: null,
			name: null,
			surname: null,
			nickname: null,
			role: null,
			date_birth: null,
			email: null
		})

		const reset = _ => { 
			form.reset()
			form.clearErrors()
			formEdit.reset()
			formEdit.clearErrors()
			modalEditMode.value = false 
		}

		const close = _ => { 
			modalOpened.value = false
			reset() 
		}

		const edit = (row) => { 
			modalEditMode.value = true

			formEdit.id = row.id
			formEdit.name = row.name
			formEdit.surname = row.surname
			formEdit.nickname = row.nickname
			formEdit.date_birth = row.date_birth
			formEdit.email = row.email
			formEdit.role = row.role

			modalOpened.value = true 
		}

		const generateLink = _ => { 
			form.post(route('admin.users.generate_link'), {
				onSuccess: () => close()
			}) 
		}

		const update = _ => { 
			formEdit.put(route('admin.users.update', formEdit.id), {
				onSuccess: () => close()
			}) 
		}

		const deleteRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete(route('admin.users.destroy', row.id))
        }

		const currentDate = _ => new Date().toISOString().split('T')[0]

		const columns = [
			{name:'id', label:'ID', sortable: true},
			{name:'surname', label:'Użytkownik', searchable: true, sortable: true},
			{name:'email', label:'Email', searchable: true, sortable: true},
			{name:'date_birth', label:'Data urodzenia', searchable: true, sortable: true},
			{name:'role', label:'Rola', searchable: true, sortable: true},
			{name:'actions', label:'Działania'}
		]

		return { form, formEdit, columns, modalOpened, modalEditMode, close, generateLink, edit, update, currentDate, deleteRow }
	},

	components: {
		AdminPanelLayout,
		Link,
		JetValidationErrors,
		DataTable,
		CrudModal
	},

});
</script>