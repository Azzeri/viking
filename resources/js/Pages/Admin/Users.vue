<template>
  <admin-panel-layout title="Użytkownicy">

    <template #page-title>Użytkownicy</template>

    <div v-if="!users.data.length && filters.search == null" class="m-4 text-gray-100 p-5 glass-admin-content rounded-3xl">
		<h1>Brak danych</h1>
		<div class="flex space-x-2">
			<button v-if="categories.length" @click="modalOpened = true" class="sm:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
				<i class="fas fa-plus fa-lg"></i>
			</button>
			<button v-if="categories.length" @click="modalOpened = true" class="hidden sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
				<i class="fas fa-plus fa-lg"></i>
			</button>
		</div>
	</div>

	<div v-else>
		<DataTable :columns=columns :data=users :filters=filters sortRoute="admin.users.index" extraClass="first:h-16 sm:first:h-auto flex sm:table-cell">

			<template #buttons>
				<button @click="modalOpened = true" class="sm:hidden bg-white bg-opacity-70 text-gray-800 font-semibold rounded-full w-12 h-12 border-2 flex justify-center items-center">
					<i class="fas fa-plus fa-lg"></i>
				</button>
				<button @click="modalOpened = true" class="hidden sm:flex bg-white bg-opacity-70 text-gray-800 font-semibold px-3 py-2 rounded-full border-2">
					<i class="fas fa-plus fa-lg"></i>
				</button>
			</template>

			<template #content>
				<tr v-for="row in users.data" :key="row" class="flex flex-col flex-no-wrap rounded-r-lg sm:rounded-l-lg sm:table-row sm:mb-0 truncate sm:hover:bg-gray-100 divide-y divide-gray-300 sm:divide-none bg-white">
					<td class="px-3 py-1 flex items-center space-x-3 h-20">
						<img class="w-14 h-14 rounded-full" src='https://picsum.photos/600/400/?random' :alt=row.name>
						<div>
							<div>{{ row.name + ' ' + row.surname}}</div>
							<div class="text-sm text-gray-500">{{ row.nickname }}</div>
						</div>
					</td>
					<td class="px-3 py-1">{{ row.email }}</td>
					<td class="px-3 py-1">{{ row.date_birth }}</td>
					<td class="px-3 py-1">{{ row.role }}</td>
					<td class="px-3 py-1 space-x-3">
						<template v-if="row.privilege_id != $page.props.privileges.IS_ADMIN">
							<i @click="edit(row)" class="fas fa-edit cursor-pointer"></i>
							<i @click="deleteRow(row)" class="fas fa-trash cursor-pointer text-red-700"></i>
						</template>
					</td>
				</tr>
			</template>
			
		</DataTable>
	</div>

	<CrudModal :show=modalOpened @close=close>
		<template #title>Nowy użytkownik</template>
		<template #content v-if="!modalEditMode">

			<jet-validation-errors class="my-6" />
			<form id="generateLinkForm">
				<div class="mt-4">
					<jet-label for="email" value="Email" />
					<jet-input id="email" type="email" class="mt-1 block w-full" v-model="form.email" required placeholder="Wprowadź email" />
				</div>

				<div class="mt-4">
					<jet-label for="role" value="Rola w grupie" />
					<jet-input id="role" type="text" class="mt-1 block w-full" v-model="form.role" required placeholder="Wprowadź rolę" />
				</div>
			</form>

		</template>

		<template #content v-else>
			<jet-validation-errors class="my-6" />
			<form id="editForm" @submit.prevent=update>
				<div class="mt-6">
					<jet-input id="name" type="text" class="mt-1 block w-full" v-model="formEdit.name" required autofocus placeholder="Imię" autocomplete="name" />
				</div>

				<div class="mt-6">
					<jet-input id="surname" type="text" class="mt-1 block w-full" v-model="formEdit.surname" required placeholder="Nazwisko" autocomplete="surname" />
				</div>

				<div class="mt-6">
					<jet-input id="nickname" type="text" class="mt-1 block w-full" v-model="formEdit.nickname" placeholder="Nick" autocomplete="nickname" />
				</div>

				<div class="mt-6">
					<jet-input id="date_birth" type="date" class="mt-1 block w-full" v-model="formEdit.date_birth" :max="currentDate()" min="1900-01-01" autocomplete="date_birth" />
				</div>

				<div class="mt-6">
					<jet-input id="email" type="email" class="mt-1 block w-full" v-model="formEdit.email" required placeholder="Adres email" />
				</div>

				<div class="mt-6">
					<jet-input id="role" type="text" class="mt-1 block w-full" v-model="formEdit.role" required placeholder="Rola w grupie" />
				</div>

			</form>
		</template>

		<template #footer>
			<jet-button type="submit" form="generateLinkForm" v-if=!modalEditMode @click=generateLink class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Wygeneruj link do rejestracji
			</jet-button>

			<jet-button type="submit" form="editForm" v-else @click=update class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
				Edytuj
			</jet-button>

		</template>
	</CrudModal>
	
  </admin-panel-layout>
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
			formEdit.reset()
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
			form.post('generateRegistrationLink', {
				onSuccess: () => close()
			}) 
		}

		const update = () => { 
			formEdit.put('users/'+formEdit.id, {
				onSuccess: () => close()
			}) 
		}

		const deleteRow = (row) => {
            if (!confirm('Na pewno?')) return;
            Inertia.delete('users/' + row.id)
        }

		const currentDate = _ => new Date().toISOString().split('T')[0]

		const columns = [
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
		JetButton,
		JetInput,
		JetLabel,
		JetValidationErrors,
		DataTable,
		CrudModal,
		
	},

});
</script>